<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Traits\GeneralTrait;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class GroupController extends Controller
{
    use GeneralTrait;
    /**
     * Create
     */
    public function create(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'age' => 'required',
            'max_members' => 'required',
            'isPrivate' => 'required',
        ]);

        if($validator->fails()) {
            return  $this->returnValidationError($validator->errors()->first(),400);
        }

        $teacher = Auth::guard('teacher')->user();
        //create group
        $group = Group::create([
            'title' => $request->title,
            'teacher_id' => $teacher->id,
            'invite_url' => Str::random(20),
            'age' => $request->age,
            'max_members' => $request->max_members,
            'isPrivate' => $request->isPrivate,
            'institute' => isset($request->institute) ? $request->institute : null,
            'description' => $request->description
        ]);

        return $this->returnSuccessData($group, 'Group created successfully', 200);
    }
    /**
     * Update
     */
    public function update(Request $request,$group_id){
        $teacher = Auth::guard('teacher')->user();
        $group = Group::find($group_id);
        if($group){
            if($group->teacher_id === $teacher->id){
                $group->title =  isset($request->title) ? $request->title : $group->title;
                $group->age =  isset($request->age) ? $request->age : $group->age;
                $group->max_members =  isset($request->max_members) ? $request->max_members : $group->max_members;
                $group->isPrivate =  isset($request->isPrivate) ? $request->isPrivate : $group->isPrivate;
                $group->isAvailable =  isset($request->isAvailable) ? $request->isAvailable : $group->isAvailable;
                $group->institute =  isset($request->institute) ? $request->institute : $group->institute;
                $group->description =  isset($request->description) ? $request->description : $group->description;
                $group->save();
                return $this->returnSuccessData($group, 'Group updated successfully', 200);
            }
            return $this->returnError('you are unauthorized to update this group', 403);
        }
        return $this->returnError('group not found', 404);
    }
    /**
     * Reset Invite url
     */
    public function resetInviteUrl($group_id){
        $teacher = Auth::guard('teacher')->user();
        $group = Group::find($group_id);
        if($group){
            if($group->teacher_id === $teacher->id){
                $group->invite_url = Str::random(20);
                $group->save();
                return $this->returnSuccessData($group, 'invite url reset successfully', 200);
            }
            return $this->returnError('unauthorized to reset invite url of this group', 403);
        }
        return $this->returnError('group not found', 404);
    }
    /**
     * Get all the users in the group
     */
    public function usersOfGroup($group_id){
        $users = User::where('group_id', $group_id)->get();
        //response
        return $this->returnData('users', $users);
    }

    /**
     * Get all the users in the group for the auth teacher
     */
    // public function usersOfTeacherGroup(){
    //     $teacher = Auth::guard('teacher')->user();
    //     $users = User::where('group_id', $teacher->group->id)->get();
    //     //response
    //     return $this->returnData('users', $users);
    // }

    /**
     * Get top 3 of a group
     */
    public function top3($group_id){
        $users = DB::table('users')
               ->where('group_id', $group)
               ->orderBy('points', 'desc')
               ->take(3)
               ->get();
        //$users = User::where('group_id', $group_id)->get();
        //response
        return $this->returnData('users', $users);
    }

    /**
     * Get all the news of a group
     */
    // public function group_news($group_id){
    //     $news = NewsPanel::where('group_id', $group_id)->get();
    //     //response
    //     return $this->returnData('news', $news);
    // }

    /**
     * Get all the groups that are public and has space and avaialble
     */
    public function availableGroups(Request $request){

        $user = Auth::guard('user')->user();
        $groups = DB::table('groups')
            ->join('teachers', 'teachers.id', '=', 'groups.teacher_id')
           // ->join('orders', 'users.id', '=', 'orders.user_id')
            ->where([
                'groups.isPrivate' => 0,
                'groups.isAvailable' => 1,
                'teachers.gender' => $user->gender,
                ['groups.max_members' , '>', 'groups.count']
             ])
            ->select('groups.*')
            ->get();
        return $this->returnSuccessData($groups, '', 200);
    }

    /**
     * Teacher gets his groups
     */
    public function createdGroups(){
        $teacher = Auth::guard('teacher')->user();
        return $this->returnSuccessData($teacher->createdGroups, '', 200);
    }
}
