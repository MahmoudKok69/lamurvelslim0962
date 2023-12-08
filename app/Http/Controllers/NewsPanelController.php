<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NewsPanel;
use App\Moels\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

class NewsPanelController extends Controller
{
    use GeneralTrait;
    /**
     * Create a new news
     */
    public function create(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);
        if($validator->fails()) {
            return  $this->returnValidationError($validator->errors()->first(),400);
        }

        $teacher = Auth::guard('teacher')->user();
        //create news
        $news = NewsPanel::create([
            'content' => $request->content,
            'teacher_id' => $teacher->id,
            'group_id' => $teacher->group->id, 
        ]);
        //response
        return $this->returnSuccessData($news, 'News Created Successfully', 200);
    }
    /**
     * Update news
     */
    public function update(Request $request, $news_id){
        $news = NewsPanel::where([
            'teacher_id' => Auth::guard('teacher')->user()->id,
            'id' => $news_id
        ])->first();
        if($news){
            $news->content = isset($request->content) ? $request->content : $news->content;
            $news->save();
            return $this->returnSuccessData($news, 'news updated successfully', 200);
        }
        return $this->returnError('failed to update news', 404);
    }
    /**
     * Delete news
     */
    public function delete($news_id){
        $news = NewsPanel::where([
            'teacher_id' => Auth::guard('teacher')->user()->id,
            'id' => $news_id
        ])->first();
        if($news){
            $news->delete();
            return $this->returnSuccessMessage('news deleted successfully', 200);
        }
        return $this->returnError('failed to delete news', 404);
    }

    /**
     * Show news
     */
    public function my_group_news(){
        $user = Auth::guard('user')->user();
        $news = NewsPanel::where('group_id', $user->group_id)->get();
        return $this->returnSuccessData('my news', $news, 200);
    }

}
