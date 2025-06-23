<?php
// app/Http/Controllers/TaskController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;


class SupportController extends Controller
{
    public function helpAndSupport()
    {
        return view('helpAndSupport');
    }

    public function storeSupportForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);
        
        $validated['created_by'] = Auth::user()->employee_code . ' * ' . Auth::user()->employee_name;

        SupportTicket::create($validated);

        return response()->json(['success' => true, 'message' => 'Support request submitted successfully!']);
    }
}

