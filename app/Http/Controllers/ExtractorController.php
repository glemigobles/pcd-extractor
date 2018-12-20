<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib;

class ExtractorController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('modules.extractor.extractor');
    }
    public function createraport(Lib $lib)
    {
        $rap=$lib->createrap($this);
        return $rap;
    }
}   
