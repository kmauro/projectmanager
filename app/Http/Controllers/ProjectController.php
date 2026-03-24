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

    public static function editProject($id)
    {
        return view('projects.edit', ['project' => ProjectRepository::getProject($id)]);
    }

    public function viewProject($id)
    {
        return view('projects.view', ['project' => ProjectRepository::getProject($id)]);
    }



    public function uploadFile()
    {
        return view('projects.upload');
    }
}
