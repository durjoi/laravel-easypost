<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function EmailTemplateIndex () 
    {
        return view('demo.emailtemplate.index');
    }

    public function EmailTemplateCreate () 
    {
        return view('demo.emailtemplate.create');
    }

    public function EmailTemplateEdit () 
    {
        return view('demo.emailtemplate.edit');
    }
}
