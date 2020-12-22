<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(Request $request) {
        Storage::put('files', $request->file);

        return $request->file->hashName();
    }

    public function show($fileName) {
        return [
            'url' => 'https://example.com/'.Storage::url('files/'.$fileName),
        ];
    }
}
