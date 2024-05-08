<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Response;

class DownloadFileController extends Controller
{
    public function download($file)
    {
        try {
            $path = public_path('assets/doc/'.$file);
            return response()->download($path);
        } catch (\Throwable $th) {
            //throw $th;
        }


    }
}
