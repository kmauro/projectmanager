<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public static function getUsers()
    {
        return User::paginate(15);
    }

    public static function getUser($id)
    {
        return User::find($id);
    }

    public static function createUser($data)
    {
        return User::create($data);
    }
}
