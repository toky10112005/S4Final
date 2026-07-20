<?php
namespace App\Controllers;

use App\Models\UsersModel;

class UsersController extends BaseController
{
    public function index()
    {
        return view('login');
    }
    
}