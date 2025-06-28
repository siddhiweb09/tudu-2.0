<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\File;

class TeamController extends Controller
{
    public function teams()
    {
        $authusers = User::select("employee_code", "employee_name")->get();
        $authUser = Auth::user();
        $employeeIdentity = $authUser->employee_code . '*' . $authUser->employee_name;

        $teams = Team::all()->filter(function ($team) use ($employeeIdentity) {
            $isLeader = $team->team_leader === $employeeIdentity;

            // Decode team_members JSON string
            $teamMembers = json_decode($team->team_members, true) ?? [];

            $isPublic = $team->team_visibilty === 'public';
            $isTeamMember = false;

            if (is_array($teamMembers)) {
                foreach ($teamMembers as $member) {
                    if ($member === $employeeIdentity) {
                        $isTeamMember = true;
                        break;
                    }
                }
            }

            // Leader sees all, or member sees if team is public
            return $isLeader || ($isPublic && $isTeamMember);
        });

        // dd($teams);

        // ✅ Collect all unique member codes from visible teams
        $memberCodes = collect($teams)
            ->flatMap(function ($team) {
                return json_decode($team->team_members, true) ?? [];
            })
            ->map(function ($member) {
                return explode('*', $member)[0]; // Get employee_code only
            })
            ->unique()
            ->values();

        // ✅ Fetch only team members with profile pictures
        $users = User::whereIn('employee_code', $memberCodes)
            ->select('employee_code', 'employee_name', 'profile_picture')
            ->get()
            ->keyBy('employee_code');

        return view("team.viewTeams", compact("users", "teams", "authusers"));
    }

    public function createTeam(Request $request)
    {
        $activeUser = Auth::user();

        $request->validate([
            'team_name' => 'required|string',
            'team_leader' => 'required|string',
            'team_members' => 'required|array',
            'team_visibility' => 'nullable',
            'team_avatar' => 'nullable',
        ]);

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('team_avatar')) {
            $avatar = $request->file('team_avatar');
            $avatarName = 'team_' . time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();

            $uploadDir = public_path('assets/images/team_avatars');

            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }

            $avatar->move($uploadDir, $avatarName);
            $avatarPath = $avatarName;
        }

        // Create team
        $team = Team::create([
            'team_name' => $request->team_name,
            'team_leader' => $request->team_leader,
            'team_avatar' => $avatarPath, // corrected key from 'avatar_path'
            'team_members' => json_encode($request->team_members), // auto-cast via $casts
            'team_visibilty' => $request->team_visibility,
            'created_by' => $activeUser->employee_code . '*' . $activeUser->employee_name,
            'updated_by' => $activeUser->employee_code . '*' . $activeUser->employee_name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Team created successfully!',
            'team_code' => $team->team_code,
        ]);
    }

    public function getMembers($code)
    {
        $team = Team::where('team_code', $code)->first();

        if (!$team) {
            return response()->json([], 404);
        }

        $membersRaw = json_decode($team->team_members, true);
        $members = [];

        if (is_array($membersRaw)) {
            foreach ($membersRaw as $entry) {
                [$empCode, $name] = explode('*', $entry);
                $user = User::where('employee_code', $empCode)->first();

                if ($user) {
                    $members[] = [
                        'employee_code' => $user->employee_code,
                        'employee_name' => $user->employee_name,
                        'profile_picture' => $user->profile_picture,
                        'department' => $user->department,
                        'branch' => $user->branch
                    ];
                }
            }
        }

        return response()->json($members);
    }

    public function deleteTeam(Request $request)
    {
        $request->validate([
            'team_code' => 'required|string'
        ]);

        $teamCode = $request->input('team_code');
        $authUser = Auth::user();
        $employeeIdentity = $authUser->employee_code . '*' . $authUser->employee_name;

        $team = Team::where('team_code', $teamCode)->first();

        if (!$team) {
            return response()->json(['status' => 'error', 'message' => 'Team not found.'], 404);
        }

        if ($team->team_leader !== $employeeIdentity) {
            return response()->json(['status' => 'unauthorized', 'message' => 'Only the team leader can delete this team.'], 403);
        }

        $team->delete();

        return response()->json(['status' => 'success', 'message' => 'Team deleted successfully.']);
    }

    public function fetchTeamData($code)
    {
        $team = Team::where('team_code', $code)->first();
        $users = User::select('employee_code', 'employee_name')->get();

        if (!$team) {
            return response()->json([
                'status' => 'error',
                'message' => 'Team not found.',
            ], 404);
        }

        $teamMembers = json_decode($team->team_members, true) ?? [];

        return response()->json([
            'status' => 'success',
            'data' => [
                'team_code' => $team->team_code,
                'team_name' => $team->team_name,
                'team_visibilty' => $team->team_visibilty,
                'team_avatar' => $team->team_avatar,
                'team_leader' => $team->team_leader,
                'team_members' => $teamMembers,
            ],
            'users' => $users
        ]);
    }

    public function updateTeam(Request $request)
    {
        $authUser = Auth::user();
        $employeeIdentity = $authUser->employee_code . '*' . $authUser->employee_name;

        $request->validate([
            'team_code' => 'required|exists:teams,team_code',
            'team_name' => 'required|string|max:255',
            'team_leader' => 'required|string',
            'team_members' => 'array',
            'team_visibility' => 'required|in:public,private',
            'team_avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $team = Team::where('team_code', $request->team_code)->first();

        if (!$team) {
            return response()->json([
                'status' => 'error',
                'message' => 'Team not found.'
            ], 404);
        }

        // ✅ Authorization check: Only current leader can update
        if ($team->team_leader !== $employeeIdentity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Only the team leader can update this team.'
            ], 403);
        }

        // ✅ Authorized: Proceed to update
        $team->team_name = $request->team_name;
        $team->team_leader = $request->team_leader;
        $team->team_members = json_encode($request->team_members ?? []);
        $team->team_visibilty = $request->team_visibility;
        $team->updated_by = $employeeIdentity;

        if ($request->hasFile('team_avatar')) {
            $avatar = $request->file('team_avatar');
            $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('assets/images/team_avatars/'), $filename);
            $team->team_avatar = $filename;
        }

        $team->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Team updated successfully.'
        ]);
    }



}
