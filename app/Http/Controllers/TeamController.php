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
        $users = User::select("employee_code", "employee_name")->get();
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

        return view("team.viewTeams", compact("users", "teams"));
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
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Team created successfully!',
            'team_code' => $team->team_code,
        ]);
    }

}
