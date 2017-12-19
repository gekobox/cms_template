<?php

namespace App\Http\Controllers;

use App\Classes\FileManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Image;

class FilesController extends Controller
{
    /**
     * Upload a file
     *
     * Params:
     * - file
     * - path (relative to public folder, doesn't necessarily have to start with a slash, no trailing slash)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile($parentType, $parentId, Request $request){
        return FileManager::uploadFile( $parentType, $parentId, $request);
    }

    /**
     * Upload a file
     *
     * Params:
     * - file
     * - path (relative to public folder, doesn't necessarily have to start with a slash, no trailing slash)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage($parentType, $parentId, Request $request){
        return FileManager::uploadFile( $parentType, $parentId, $request);
    }   
    
}
