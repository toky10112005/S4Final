<?php
namespace App\Controllers;

use App\Models\UsersModel;

class ClientsController extends BaseController
{
    public function index()
    {
        return view('login');
    }
    
}