<?php

namespace App\Http\Controllers;

class TaskController extends Controller
{
    public function allTask()
    {
        return view('tasks.allTasks');
    }

    public function taskCalender()
    {
        return view('tasks.calender');
    }
}
