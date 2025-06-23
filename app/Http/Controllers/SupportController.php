<?php
// app/Http/Controllers/TaskController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SupportTicket;

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

        SupportTicket::create($validated);

        return response()->json(['success' => true, 'message' => 'Support request submitted successfully!']);
    }
}

