<?php

namespace App\Http\Controllers;

use App\Models\PersonalTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskMedia;
use App\Models\TaskLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class PersonalTaskController extends Controller
{
    // List view (default)
    public function index(Request $request)
    {
        $user = Auth::user();
        $assignBy = $user->employee_code . '*' . $user->employee_name;
        $tasks = PersonalTask::where('assign_by', $assignBy)
            ->orderBy('due_date', 'asc')
            ->get();

        return view('personal-tasks.index', compact('tasks'));
    }

    // Kanban view
    public function kanban()
    {
        return view('personal-tasks.kanban');
    }

    // Matrix view
    public function matrix(Request $request)
    {
        $user = Auth::user();
        $assignBy = $user->employee_code . '*' . $user->employee_name;

        $tasks = PersonalTask::where('assign_by', $assignBy)
            ->where('status', '!=', 'completed')
            ->orderBy('due_date', 'asc')
            ->get();


        return view('personal-tasks.matrix', compact('tasks'));
    }

    // Calendar view
    public function calendar(Request $request)
    {
        $user = Auth::user();
        $assignBy = $user->employee_code . '*' . $user->employee_name;
        $month = $request->query('month', date('n'));
        $year = $request->query('year', date('Y'));

        $tasks = PersonalTask::where('assign_by', $assignBy)
            ->where(function ($query) use ($month, $year) {
                $query->whereMonth('due_date', $month)
                    ->whereYear('due_date', $year);
            })
            ->orderBy('due_date', 'asc')
            ->get();



        return view('personal-tasks.calendar', compact('tasks', 'month', 'year'));
    }

    public function show(PersonalTask $task)
    {
        Log::info('Showing task:', ['task_id' => $task->task_id, 'id' => $task->id]);
        return response()->json($task);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $activeUser = $user->employee_code . "*" . $user->employee_name;
        // Decode JSON inputs
        $tasks = json_decode($request->tasks_json, true) ?? [];
        $links = json_decode($request->links_json, true) ?? [];
        $reminders = json_decode($request->reminders_json, true) ?? [];
        $frequencyDuration = json_decode($request->frequency_duration_json, true) ?? [];
        // Validate the main task data
        $validator = Validator::make($request->all(), [
            'task_title' => 'required|string|max:255',
            'task_description' => 'required|string',
            'due_date' => 'nullable|string',
            'priority' => 'required|in:high,medium,low',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:10240', // 10MB max
            'reminders_json' => 'json',
            'frequency' => 'required_if:recurring,on',
            'frequency_duration_json' => 'json',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->errors()
            ], 422);
        }
        // Create the task
        $task = PersonalTask::create([
            'title' => $request->task_title,
            'description' => $request->task_description,
            'task_list' => $request->tasks_json,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'is_recurring' => $request->has('recurring') ? true : false,
            'frequency' => $request->frequency,
            'frequency_duration' => $request->frequency_duration_json,
            'reminders' => $request->reminders_json,
            'links' => $request->links_json,
            'status' => 'Pending',
            'assign_by' => $activeUser,
        ]);
        // Log task creation
        TaskLog::create([
            'task_id' => $task->task_id,
            'log_description' => 'Task created',
            'added_by' => $activeUser,
        ]);
        // Handle file uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $fileName = $this->storeFile($document);

                TaskMedia::create([
                    'task_id' => $task->task_id,
                    'category' => 'document',
                    'file_name' => $fileName,
                    'created_by' => $activeUser,
                ]);

                // Log document upload
                TaskLog::create([
                    'task_id' => $task->task_id,
                    'log_description' => 'Document uploaded: ' . $fileName,
                    'added_by' => $activeUser,
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'messages' => 'Task created successfully!'
        ]);
    }

    private function storeFile($file)
    {
        // Create uploads directory if it doesn't exist
        $uploadPath = public_path('assets/uploads');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        // Generate unique filename
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        // Move file to public/assets/uploads
        $file->move($uploadPath, $fileName);
        return $fileName;
    }


    public function update(Request $request, PersonalTask $task)
    {
        // $this->authorize('update', $task);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:todo,in_progress,completed',
            'time_estimate' => 'nullable|integer|min:0',
            'is_habit' => 'boolean',
            'habit_frequency' => 'nullable|in:daily,weekly,monthly,weekdays',
            'okr' => 'nullable|string',
        ]);

        if ($request->category === '_new_category') {
            $validated['category'] = $request->new_category_name;
        }

        $task->update($validated);
        return redirect()->back()->with('success', 'Task updated successfully');
    }

    public function updateStatus(Request $request, PersonalTask $task)
    {
        // $this->authorize('update', $task);
        $request->validate([
            'status' => 'required|in:todo,in_progress,completed',
        ]);

        $task->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function destroy(PersonalTask $task)
    {
        // $this->authorize('delete', $task);
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully');
    }

    public function getDocuments($taskId)
    {
        $documents = TaskMedia::where('task_id', $taskId)->get();
        return response()->json($documents);
    }

    public function uploadDocument(Request $request, $taskId)
    {
        $user = Auth::user();
        $activeUser = $user->employee_code . "*" . $user->employee_name;
        $request->validate([
            'document' => 'required|file', // 10MB max
        ]);

        // Handle file uploads
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $uploadPath = public_path('assets/uploads');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            // Generate unique filename
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            // Move file to public/assets/uploads
            $file->move($uploadPath, $fileName);

            $document = TaskMedia::create([
                'task_id' => $taskId,
                'file_name' => $fileName,
                'category' => 'document',
                'created_by' => $activeUser,
            ]);

            // Log document upload
            TaskLog::create([
                'task_id' => $taskId,
                'log_description' => 'Document uploaded: ' . $request->file('document')->getClientOriginalName(),
                'added_by' => $activeUser,
            ]);

            return response()->json(['success' => true, 'document' => $document]);
        }
        return response()->json(['success' => false, 'error' => 'No file uploaded']);
    }

    public function addNote(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'task_id' => 'required',
            'note' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid request',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $taskId = $request->input('task_id');
            $newNote = trim($request->input('note'));
            $assign_by = Auth::id();

            // Get existing notes
            $task = DB::table('personal_tasks')
                ->where('task_id', $taskId)
                ->where('assign_by', $assign_by)
                ->first(['notes']);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'error' => 'Task not found'
                ], 404);
            }

            // Decode existing notes or initialize empty array
            $notesArray = $task->notes ? json_decode($task->notes, true) : [];

            // Add new note with timestamp
            $notesArray[] = [
                'timestamp' => now()->toDateTimeString(),
                'content' => $newNote,
                'id' => uniqid('note_', true)
            ];

            // Update database with JSON encoded notes
            $updated = DB::table('personal_tasks')
                ->where('task_id', $taskId)
                ->where('assign_by', $assign_by)
                ->update(['notes' => json_encode($notesArray)]);

            return response()->json([
                'success' => (bool) $updated,
                'data' => $updated ? null : ['error' => 'Update failed']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function editNote(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'task_id' => 'required',
            'note_id' => 'required|string',
            'note' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid request',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $taskId = $request->input('task_id');
            $noteId = $request->input('note_id');
            $newContent = trim($request->input('note'));
            $assign_by = Auth::id();

            // Get the task with notes
            $task = DB::table('personal_tasks')
                ->where('task_id', $taskId)
                ->where('assign_by', $assign_by)
                ->first(['notes']);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'error' => 'Task not found'
                ], 404);
            }

            // Decode notes JSON
            $notesArray = json_decode($task->notes, true) ?? [];

            // Find and update the specific note
            $noteUpdated = false;
            foreach ($notesArray as &$note) {
                if ($note['id'] === $noteId) {
                    $note['content'] = $newContent;
                    $noteUpdated = true;
                    break;
                }
            }

            if (!$noteUpdated) {
                return response()->json([
                    'success' => false,
                    'error' => 'Note not found in task'
                ], 404);
            }

            // Update the task with modified notes
            $rowsAffected = DB::table('personal_tasks')
                ->where('task_id', $taskId)
                ->where('assign_by', $assign_by)
                ->update(['notes' => json_encode($notesArray)]);

            return response()->json([
                'success' => (bool) $rowsAffected,
                'updated_note_id' => $noteId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteNote(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'task_id' => 'required',
            'note_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid request',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $taskId = $request->input('task_id');
            $noteId = $request->input('note_id');
            $assign_by = Auth::id();

            // Get the task with notes
            $task = DB::table('personal_tasks')
                ->where('task_id', $taskId)
                ->where('assign_by', $assign_by)
                ->first(['notes']);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'error' => 'Task not found'
                ], 404);
            }

            // Decode notes JSON
            $notesArray = json_decode($task->notes, true) ?? [];

            // Count notes before deletion for verification
            $originalCount = count($notesArray);

            // Filter out the note to delete
            $updatedNotes = array_filter($notesArray, function ($note) use ($noteId) {
                return $note['id'] !== $noteId;
            });

            // Check if any note was actually removed
            if (count($updatedNotes) === $originalCount) {
                return response()->json([
                    'success' => false,
                    'error' => 'Note not found in task'
                ], 404);
            }

            // Re-index array and update database
            $rowsAffected = DB::table('personal_tasks')
                ->where('task_id', $taskId)
                ->where('assign_by', $assign_by)
                ->update(['notes' => json_encode(array_values($updatedNotes))]);

            return response()->json([
                'success' => (bool) $rowsAffected,
                'deleted_note_id' => $noteId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteDocument(Request $request)
    {

        $user = Auth::user();
        $activeUser = $user->employee_code . "*" . $user->employee_name;
        // Validate input
        $validator = Validator::make($request->all(), [
            'doc_id' => 'required|integer',
            'task_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid request',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $docId = $request->input('doc_id');
            $taskId = $request->input('task_id');

            // Get the document record
            $document = DB::table('task_medias')
                ->where('id', $docId)
                ->where('task_id', $taskId)
                ->where('created_by', $activeUser)
                ->first(['file_name']);

            if (!$document) {
                return response()->json([
                    'success' => false,
                    'error' => 'Document not found'
                ], 404);
            }

            // Delete from database
            $deleted = DB::table('task_medias')
                ->where('id', $docId)
                ->where('task_id', $taskId)
                ->where('created_by', $activeUser)
                ->delete();

            if ($deleted) {
                // Delete the physical file from public/uploads
                $filePath = public_path('uploads/' . $document->file_name);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Document deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to delete document'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function fetchTasks(Request $request)
    {
        $user = Auth::user();

        $assignBy = $user->employee_code . '*' . $user->employee_name;

        $tasks = PersonalTask::where('assign_by', $assignBy)->get();

        return response()->json([
            'status' => 'success',
            'tasks' => $tasks,
            'assign_by' => $assignBy, // optional: for debugging
        ]);
    }
}
