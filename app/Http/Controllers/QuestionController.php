<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    // public function create(Request $request){
    //     $validator = Validator::make($request->all(),[
    //         'lable' => 'required',
    //         'answer' => 'required',
    //         'option1' => 'required'
    //     ]);
        
    //     if($validator->fails()) {
    //         return  $this->returnValidationError($validator->errors()->first(),400);
    //     }

    //     $question = Question::create([
    //         'title' => $request->title,
    //         'answer' => $request->answer,
    //         'option1' => $request->option1,
    //         'option2' => isset($request->option2) ? $request->option2 : null,
    //         'option3' => isset($request->option3) ? $request->option3 : null,
    //     ]);

    //     return $this->returnSuccessData($question, 'question created successfully', 200);
    // }

    // public function createQuestions(Request $request){
    //     $validator = Validator::make($request->all(),[
    //         'questions' => 'required',
    //     ]);

    //     if($validator->fails()) {
    //         return  $this->returnValidationError($validator->errors()->first(),400);
    //     }
        
    //     $questions = $request->questions;

    //     foreach($questions as $question){
    //         Question::create([
    //             'title' => $question->title,
    //             'answer' => $question->answer,
    //             'option1' => $question->option1,
    //             'option2' => isset($question->option2) ? $question->option2 : null,
    //             'option3' => isset($question->option3) ? $question->option3 : null,
    //         ]);
    //     }
    //     return $this->returnSuccessData($question, 'question created successfully', 200);
    // }
    /**
     * Teacher update a question
     */
    public function update(Request $request,$id){
        $question = Question::find($id);
        if($question){
            $question->title = isset($request->title) ? $request->title : $question->title;
            $question->answer = isset($request->answer) ? $request->answer : $question->answer;
            $question->option1 = isset($request->option1) ? $request->option1 : $question->option1;
            $question->option2 = isset($request->option2) ? $request->option2 : $question->option2;
            $question->option3 = isset($request->option3) ? $request->option3 : $question->option3;
            $question->save();
            return $this->returnSuccessData($question, 'question updated successfully', 200);
        }
        return $this->returnError('question not found', 404);
    }
    /**
     * Teacher delete question
     */
    public function delete(Request $request,$id){
        $question = Question::find($id);
        if($question){
            $question->delete();
            return $this->returnSuccessData($question, 'question deleted successfully', 200);
        }
        return $this->returnError('question not found', 404);
    }


    // public function show($id){
    //     $question = Question::find($id);
    //     if($question){
    //         return $this->returnSuccessData($question, '', 200);
    //     }
    //     return $this->returnError('question not found', 404);
    // }
    // public function showAll(){
    //     $questions = Question::all();
    //     return $this->returnSuccessData($questions, '', 200);
    // }
}
