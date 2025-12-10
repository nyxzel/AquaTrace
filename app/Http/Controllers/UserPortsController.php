<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserPortsController extends Controller
{
    public function index()
    {
        return view('user_ports');
    }
}
