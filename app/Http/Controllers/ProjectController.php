<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    
    
    
    


    public function index()
    {
        return view('projects.index', ['projects' => Project::paginate(25)]);
    }

    public function create()
    {
        return view('projects.create');
    }
}
