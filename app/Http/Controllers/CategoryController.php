<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return inertia('Category/Index');
    //    $message = "Hello from Server";
    // return inertia('Category/Index',[
    //     'message'=> $message
    //     ]);
    }
}
