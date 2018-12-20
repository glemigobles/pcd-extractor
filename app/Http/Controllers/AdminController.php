<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function uploadfile(Request $request)
    {
                if(!$request->hasFile('file'))
                    return response()->json([
                        'error' => 'No File Uploaded'
                    ]);

                $file = $request->file('file');

                if(!$file->isValid())
                    return response()->json([
                        'error' => 'File is not valid!'
                    ]);  

                $path=  $file->store('app/public/uploads');
                
                return response()->json([
                    'success' => $path
                    ]);
                
    }
}
