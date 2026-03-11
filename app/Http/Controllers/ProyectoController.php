<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;

class ProyectoController extends Controller
{
    
    
    
    


    public function index()
    {
        return view('projects.index', ['projects' => Proyecto::paginate(25)]);
    }

    public function create()
    {
        return view('projects.create');
    }
}
