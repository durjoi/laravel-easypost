<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function EmailTemplateIndex () 
    {
        return view('demo.emailtemplate.index');
    }
    public function EmailTemplateEdit () 
    {
        return view('demo.emailtemplate.edit');
    }
}
