<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class MembersController extends Controller
{
    use GeneralTrait;
    /**
     * Join group by invite url
     */
    public function joinByInviteUrl(Request $request){
        $validator = Validator::make($request->all(), [
            'invite_url' => 'required',
        ]);
        if($validator->fails()) {
            return  $this->returnValidationError($validator->errors()->first(),400);
        }

        $user = Auth::guard('user')->user();
        if($user->group_id != null){
            return $this->returnError('you are already in a group', 400);
        }
        if($group = Group::where('invite_url', $request->invite_url)->first()){
            if($group->count < $group->max_members){
                try{
                    $user->group_id = $group->id;
                    $user->save();
                    $group->count +=1;
                    $group->save();
                    return $this->returnSuccessData($user, 'you have joined group successfully', 200);
                }catch(exception){
                    return $this->returnError('some thing went wrong', 400);
                }
                    
            }
            return $this->returnError('the group is full please choose another group', 400);
        }
        return $this->returnError('wrong invite url',404);
    }
    /**
     * Join to a public group by the id
     */
    public function joinById(Request $request, $group_id){
        $user = Auth::guard('user')->user();
        if($user->group_id != null){
            return $this->returnError('you are already in a group', 400);
        }
        if($group = Group::where('id', $group_id)->first()){
            if($group->count < $group->max_members){
                try{
                    $user->group_id = $group->id;
                    $user->save();
                    $group->count +=1;
                    $group->save();
                    return $this->returnSuccessData($user, 'you have joined group successfully', 200);
                }catch(exception){
                    return $this->returnError('some thing went wrong', 400);
                }
            }
            return $this->returnError('the group is full please choose another group', 400);
        }
        return $this->returnError('group not found', 404);
    }
    /**
     * Leave a group
     */
    public function leave(){
        $user = Auth::guard('user')->user();
        if($user->group_id != null){
            $group = $user->group;
            try{
                $user->group_id = null;
                $user->save();
                $group->count -=1;
                $group->save();
                return $this->returnSuccessData($user, 'you have left the group', 200);
            }catch(exception){
                    return $this->returnError('some thing went wrong', 400);
            }
        }
        return $this->returnError('you are not joined to any group to leave', 400);
    }
}
