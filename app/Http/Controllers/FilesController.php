<?php

namespace App\Http\Controllers;

use App\Repositories\FilesRepository;
use Illuminate\Http\Request;

class FilesController extends Controller
{

    public function __construct(private readonly FilesRepository $filesRepository)
    {
    }
    public function upload(Request $request) {
        return $this->filesRepository->uploadFile($request);
    }

    public function download(Request $request) {
        $fileId = $request->route('id');
        return $this->filesRepository->downloadFile($fileId);
    }
}
