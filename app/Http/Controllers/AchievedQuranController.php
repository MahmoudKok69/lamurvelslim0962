<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AchievedQuran;
use App\Models\User;
use App\Models\Point;
use Carbon\Carbon;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;


class AchievedQuranController extends Controller
{
    use GeneralTrait;
    /**
     * Create 
     */
    public function create(Request $request, $user_id){
        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'to' => 'required',
            'rate' => 'required',
        ]);

        if($validator->fails()) {
            return  $this->returnValidationError($validator->errors()->first(),400);
        }

        $teacher = Auth::guard('teacher')->user();
        $user = User::where('id', $user_id)->first();
        if(!$user){
            return $this->returnError('user does not exist', 404);
        }

        $groups = $teacher->groups;
        foreach($groups as $group){
            if($user->group->id === $group->id){ 
                try{
                    $achieved_quran = AchievedQuran::create([
                        'teacher_id' => $teacher->id,
                        'user_id' => $user->id,
                        'from' => $request->from,
                        'to' => $request->to,
                        'rate' => $request->rate,
                        'teacher_name' => $teacher->name
                    ]);
                    $point_value = Point::find(1);
                    $user->points += $point_value->quran;
                    $user->save();
                    return $this->returnSuccessData($achieved_quran, 'achieved quran created successfully', 200);
                }catch(exception){
                    return $this->returnError('some thing went wrong', 500);
                }
                
            }
        }
        return $this->returnError('you are unauthorized to add achieved quran for this user', 403);
    }

    /**
     * Get achieved athkar today
     */
    public function achievedQuranToday(){
        $user = Auth::guard('user')->user();
        $achieved_quran = AchievedQuran::query()
            ->where('user_id' , $user->id)
            ->whereDate('created_at', Carbon::today())
            ->get();
        return $this->returnSuccessData($achieved_quran,'', 200);
    }

    /**
     * Get achieved athkar in a month
     */
    public function achievedQuranInMonth($month){
        $user = Auth::guard('user')->user();
        $achieved_quran = AchievedQuran::query()
            ->whereYear('created_at', Carbon::today()->format('Y'))
            ->whereMonth('created_at', $month)
            ->where('user_id', $user->id)
            ->get();

        return $this->returnSuccessData($achieved_quran,'', 200);
    }

    /**
     * Get achieved athkar in a month in a report
     */
    public function achievedQuranInMonthForTeacher($month,$user_id){
        $teacher = Auth::guard('teacher')->user();
        $user = User::find($user_id);
        $groups = $teacher->groups;
        foreach($groups as $group){
            if($user->group->id === $group->id){ 
                $achieved_quran = AchievedQuran::query()
                    ->whereYear('created_at', Carbon::today()->format('Y'))
                    ->whereMonth('created_at', $month)
                    ->where('user_id', $user->id)
                    ->get();

                return $this->returnSuccessData($achieved_quran,'', 200);
            }
        }
        return $this->returnError('You are unauthorized !', 403);
    }
}
