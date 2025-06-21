<?php

namespace App\Http\Controllers;

class DemoController extends Controller
{
    public function demoIndex()
    {
        return view('master.list', compact('masters'));
    }
}
