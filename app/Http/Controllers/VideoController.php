<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    use GeneralTrait;
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'url' => 'required'
        ]);

        if($validator->fails()) {
            return  $this->returnValidationError($validator->errors()->first(),400);
        }
        
        $video = Video::create([
            'title' => $request->title,
            'url' => $request->url
        ]);

        return $this->returnSuccessData($video, 'video created successfully', 200);
    }
    public function update(Request $request,$id){
        $video = Video::find($id);
        if($video){
            $video->title = isset($request->title) ? $request->title : $video->title;
            $video->url = isset($request->url) ? $request->url : $video->url;
            $video->save();
            return $this->returnSuccessData($video, 'video updated successfully', 200);
        }
        return $this->returnError('video not found', 404);
    }
    public function delete(Request $request,$id){
        $video = Video::find($id);
        if($video){
            $video->delete();
            return $this->returnSuccessData($video, 'video deleted successfully', 200);
        }
        return $this->returnError('video not found', 404);
    }
    public function show($id){
        $video = Video::find($id);
        if($video){
            return $this->returnSuccessData($video, '', 200);
        }
        return $this->returnError('video not found', 404);
    }
    public function showAll(){
        $videos = Video::all();
        return $this->returnSuccessData($videos, '', 200);
    }
}
