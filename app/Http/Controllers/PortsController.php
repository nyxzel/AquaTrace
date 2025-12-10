<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortsController extends Controller
{
    public function index()
    {
        return view('portsLanding');
    }
}
