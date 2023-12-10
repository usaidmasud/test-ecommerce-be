<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Requests\UploadRequest;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class FileController extends Controller
{
    use UploadTrait;

    public function upload(UploadRequest $request)
    {
        $credentials = $request->all();
        try {
            $data = $this->uploadOne($credentials['file']);
            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
