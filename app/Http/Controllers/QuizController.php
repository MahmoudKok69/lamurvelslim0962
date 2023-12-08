<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\UserQuiz;
use App\Models\Question;
use App\Models\GroupQuiz;
use App\Models\Point;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class QuizController extends Controller
{
    use GeneralTrait;
    /**
     * Teacher create quiz and it's questions
     */
    public function store(Request $request){
        $teacher = Auth::guard('teacher')->user();

        $quiz = Quiz::create([
            'title' => $request->title,
            'teacher_id' => $teacher->id,
            'count' => $request->count,
        ]);

        $questions = $request->questions;
        foreach($questions as $question){
            Question::create([
                'quiz_id' => $quiz->id,
                'label' => $question["label"],
                'answer' => $question["answer"],
                'option1' => $question["option1"],
                'option2' => isset($question["option2"]) ? $question["option2"] : null,
                'option3' => isset($question["option3"]) ? $question["option3"] : null,
            ]);
        }

        return $this->returnSuccessData(null, 'quiz and questions created successfully', 200);
    }

    /**
     * Teacher update the quiz title
     */

    public function update(Request $request,$id){
        $quiz = Quiz::find($id);
        if($quiz){
            $quiz->title = isset($request->title) ? $request->title : $quiz->title;
            $quiz->save();
            return $this->returnSuccessData($quiz, 'quiz updated successfully', 200);
        }
        return $this->returnError('quiz not found', 404);
    }

    /**
     * Teacher delete quiz
     */
    public function delete(Request $request,$id){
        $quiz = Quiz::find($id);
        if($quiz){
            $quiz->delete();
            return $this->returnSuccessData($quiz, 'quiz deleted successfully', 200);
        }
        return $this->returnError('quiz not found', 404);
    }

    /**
     * Teacher get all the quizzes that he created
     */
    public function createdQuizzes(){
        $teacher = Auth::guard('teacher')->user();
        return $this->returnSuccessData($teacher->createdQuizzes, '', 200);
    }

    /**
     * User get all quizzes in his group
     */
    public function listQuizzes(){
        $user = Auth::guard('user')->user();
        return $quizzes = GroupQuiz::where('group_id', $user->group_id)->get();
    }
  
    /**
         * User get the questions of a quiz
         */
        public function listQuestions($quiz_id){
            $user = Auth::guard('user')->user();
            return $questions = Question::where('quiz_id', $quiz_id)->get();
        }
    
    /**
     * User get his achieved quizzes
     */
    public function achievedQuizzes(){
        $user = Auth::guard('user')->user();
        return $user->achieved_quizzes;
    }
    /**
     * User answer the quiz
     */
    public function assignResult(Request $request){
        $validator = Validator::make($request->all(),[
            'count' => 'required',
            'quiz_id' => 'required'
        ]);

        if($validator->fails()) {
            return  $this->returnValidationError($validator->errors()->first(),400);
        }
        
        $user = Auth::guard('user')->user();
        $quiz_points = Point::find(1)->quiz;
        if(UserQuiz::where(['quiz_id' => $request->quiz_id, 'user_id' => $user->id])->exists()){
            return $this->returnError('quiz already done', 400);
        }
         $user_quiz = UserQuiz::create([
                'quiz_id' => $request->quiz_id,
                'user_id' => $user->id,
                'result' => ($request->count * $quiz_points)
            ]);
            $user->points = ($request->count * $quiz_points);
            $user->save();
            return $this->returnSuccessData($user_quiz, 'Result assigned successfully', 200);
    }

    /**
     * Teacher assign quiz to his groups
     */
    public function assignQuiz(Request $request,$quiz_id){
        $teacher = Auth::guard('teacher')->user();
        $quiz = Quiz::find($quiz_id);
        $groups = $request->groups;
        if($quiz->teacher_id === $teacher->id){
            foreach($groups as $group){
                if(!GroupQuiz::where([
                    'group_id' => $group["id"],
                    'quiz_id' => $quiz_id
                ])->exists()){
                    GroupQuiz::create([
                        'group_id' => $group["id"],
                        'quiz_id' => $quiz_id
                    ]);
                } 
            }
        return $this->returnSuccessData(null, 'quiz assign successfully to groups',200);
        }
       return $this->returnError('you are unauthorized', 403);
    }
}
