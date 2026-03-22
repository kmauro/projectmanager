<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProjectRepository;

class ProjectController extends Controller
{
    

    public static function index()
    {
        return view('projects.index', ['projects' => ProjectRepository::getProjectsPaginated(25)]);
    }

    public function uploadFile()
    {
        return view('projects.upload');
    }
}
