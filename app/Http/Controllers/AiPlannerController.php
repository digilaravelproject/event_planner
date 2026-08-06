<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AiPlannerController extends Controller
{
    /**
     * Display the AI Planner page.
     */
    public function index()
    {
        return view('ai-planner.index');
    }
}
