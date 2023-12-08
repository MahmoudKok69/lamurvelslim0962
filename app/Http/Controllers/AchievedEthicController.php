<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\GeneralTrait;
use App\Models\AchievedEthic;
use App\Models\User;
use App\Models\Point;
use Carbon\Carbon;

class AchievedEthicController extends Controller
{
    use GeneralTrait;
    /**
     * Create 
     */
    public function create(Request $request, $id){
        $user = Auth::guard('user')->user();
        if(!AchievedEthic::query()
            ->where('user_id' , $user->id)
            ->whereDate('created_at', Carbon::today())
            ->exists())
        {
            try{
                $achieved_ethic = AchievedEthic::create([
                    'user_id' => $user->id,
                    'ethic_id' => $id,
                ]);
                $point_value = Point::find(1);
                $user->points += $point_value->ethic;
                $user->save();
                return $this->returnSuccessData($achieved_ethic, 'Ethic checked successfully', 200);
            }catch(exception){
                return $this->returnError('some thing went wrong', 500);
            }
            
        }
        return $this->returnError('athkar is already exist', 400);
    }

    /**
     * Get achieved athkar today
     */
    public function achievedEthicToday(){
        $user = Auth::guard('user')->user();
        $achieved_ethic = AchievedEthic::query()
            ->where('user_id' , $user->id)
            ->whereDate('created_at', Carbon::today())
            ->get();
        return $this->returnSuccessData($achieved_ethic,'', 200);
    }

    /**
     * Get achieved athkar in a month
     */
    public function achievedEthicInMonth($month){
        $user = Auth::guard('user')->user();
        $achieved_ethic = AchievedEthic::query()
            ->whereYear('created_at', Carbon::today()->format('Y'))
            ->whereMonth('created_at', $month)
            ->where('user_id', $user->id)
            ->get();

        return $this->returnSuccessData($achieved_ethic,'', 200);
    }

    /**
     * Get achieved athkar in a month in a report
     */
    public function achievedEthicInMonthForTeacher($month,$user_id){
        $teacher = Auth::guard('teacher')->user();
        $user = User::find($user_id);
        $groups = $teacher->groups;
        foreach($groups as $group){
            if($user->group->id === $group->id){ 
                $achieved_ethic = AchievedEthic::query()
                    ->whereYear('created_at', Carbon::today()->format('Y'))
                    ->whereMonth('created_at', $month)
                    ->where('user_id', $user->id)
                    ->get();

                return $this->returnSuccessData($achieved_ethic,'', 200);
            }
        }
        return $this->returnError('You are unauthorized !', 403);
    }
}
