<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\GeneralTrait;
use App\Models\AchievedAthkar;
use App\Models\User;
use App\Models\Athkar;
use App\Models\Point;
use Carbon\Carbon;

class AchievedAthkarController extends Controller
{
    use GeneralTrait;
    /**
     * Create 
     */
    public function create(Request $request, $id){
        $user = Auth::guard('user')->user();
        if(!AchievedAthkar::query()
            ->where('user_id' , $user->id)
            ->whereDate('created_at', Carbon::today())
            ->exists())
        {
            try{
                $achieved_athkar = AchievedAthkar::create([
                    'user_id' => $user->id,
                    'athkar_id' => $id,
                ]);
                $point_value = Point::find(1);
                $user->points += $point_value->athkar;
                $user->save();
                return $this->returnSuccessData($achieved_athkar, 'Athkar checked successfully', 200);
            }catch(exception){
                return $this->returnError('some thing went wrong', 500);
            }
           
        }
        return $this->returnError('athkar is already exist', 400);
    }

    /**
     * Get achieved athkar today
     */
    public function achievedAthkarToday(){
        $user = Auth::guard('user')->user();
        $achieved_athkar = AchievedAthkar::query()
            ->where('user_id' , $user->id)
            ->whereDate('created_at', Carbon::today())
            ->get();
        return $this->returnSuccessData($achieved_athkar,'', 200);
    }

    /**
     * Get achieved athkar in a month
     */
    public function achievedAthkarInMonth($month){
        $user = Auth::guard('user')->user();
        $achieved_athkar = AchievedAthkar::query()
            ->whereYear('created_at', Carbon::today()->format('Y'))
            ->whereMonth('created_at', $month)
            ->where('user_id', $user->id)
            ->get();

        return $this->returnSuccessData($achieved_athkar,'', 200);
    }

    /**
     * Get achieved athkar in a month for report
     */
    public function achievedAthkarInMonthForTeacher($month,$user_id){
        $teacher = Auth::guard('teacher')->user();
        $user = User::find($user_id);
        //return $teacher->groups;
        $groups = $teacher->groups;
        foreach($groups as $group){
            if($user->group->id === $group->id){ 
                $achieved_athkar = AchievedAthkar::query()
                ->whereYear('created_at', Carbon::today()->format('Y'))
                ->whereMonth('created_at', $month)
                ->where('user_id', $user->id)
                ->get();
                return $this->returnSuccessData($achieved_athkar,'', 200);
            }
        }
        return $this->returnError('You are unauthorized !', 403);
    }
    /**
     * Get a list of athkar
     */
    public function athkary(){
        $user = Auth::guard('user')->user();
        $athkary = Athkar::where('age', $user->age)->get();
        return $this->returnSuccessData($athkary, '', 200);
    }

    /**
     * Get a list of all the athkar
     */
    public function athkars(){
        $athkars = Athkar::all();
        return $this->returnSuccessData($athkars, '', 200);
    }
}
