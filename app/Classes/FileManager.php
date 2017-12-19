<?php
namespace App\Classes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileManager{
      
    public static $modelPrefix= 'App\\Models\\';
    
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
    public static function uploadFile($parentType, $parentId, Request $request){
        $validator = Validator::make($request->all(), [
            'file' => 'required',
            'fileName' => 'required'
            
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        } else {
            
            //check that the parent type has a corresponding image model  
            $imageClass= ucfirst($parentType. 'Image');
            $fullClassName= '\\App\\Models\\' . $imageClass;
            $ResourceImage= ResourceManager::make($imageClass);
            if(!isset($ResourceImage)){
                return response()->json(['errors'=>['wrong resource type']], 422);
            }
            
            $file = $request->get('file');
            $path = $request->get('path');
            
            //include a timestamp value in the file name to make it unique
            $fileName = time(). '_' . $request->get('fileName');
            
            list($type, $file) = explode(';', $file); // The type should not actually be sent in valid base64 so it's left unused
            list(, $file)      = explode(',', $file);
            $file = base64_decode($file);
            //file_put_contents(public_path().'/uploads'.$path.'/'.$fileName, $file);
            
            $disk = Storage::disk('gcs');   
            $disk->put($fileName, $file);
            
            if( $disk->exists($fileName)){
                //Save the image resource for the given parent
                $parentField= $parentType. '_id';
                $ResourceImage->{$parentField}= $parentId;
                
                //save the public url as the image value
                $ResourceImage->image_name= $fileName;
                $ResourceImage->image_url= $disk->url($fileName);
                
                //get the last position currently stored
                $lastPosition= $fullClassName::where($parentField, $parentId)->max('seq');
                
                //set the position for the new image
                $ResourceImage->seq= $lastPosition + 1;
                $ResourceImage->save();
                
                return response()->json(['url' => $disk->url($fileName)]);
            }     

            return response()->json(['errors'=>['faild to upload file']]);
        }
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
    public static function uploadImage(Request $request){
        $validator = Validator::make($request->all(), [
            'file' => 'required',
            'fileName' => 'required'            
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        } else {
            $file = $request->get('file');
            $path = $request->get('path');
            $fileName = $request->get('fileName');
            //Image::make($file)->save(public_path($path.'/').$fileName);
            
            $disk = Storage::disk('gcs');            
            $disk->put($fileName, $file);
            
            if( $disk->exists($fileName)){
                return response()->json(['status' => 'ok']);
            }            
            
            return response()->json(['errors'=>['faild to upload file']]);
        }
    }
    
    /**
     * delete file if exists
     * @param type $baseUrl
     * @param type $filename
     */
    public static function deleteFile($baseUrl, $filename){
        /*$filePath= $baseUrl . '/'. $filename;
        if(file_exists($filePath)){
            unlink($filePath);
        }
         */
        $disk = Storage::disk('gcs');   
        if( $disk->exists($filename)){
            $disk->delete($filename);
            return response()->json(['status' => 'ok']);
        }      
    }
}
