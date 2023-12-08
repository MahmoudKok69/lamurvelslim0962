<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

class AuthTeacherController extends Controller
{
    use GeneralTrait;
    /**
     * Register
     */
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'photo' => 'required',
            'gender' => 'required',
            'phone_number' => 'required',
            'address' => 'required'
        ]);
        if($validator->fails()) {
            return  $this->returnValidationError($validator->errors()->first(),400);
        }

        if($request->photo) {
            $photoName = rand() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('teachers_photos'), $photoName);
            $path='/teachers_photos' . '/' . $photoName;
        }
        $teacher = Teacher::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $path ,
            'gender' => $request->gender ,
            'phone_number' => $request->phone_number ,
            'address' => $request->address
        ]);
        return $this->returnSuccessData($teacher, 'teacher account created successfully', 200);
    }

    /**
     * Get User Profile
     */
    public function profile()
    {
        $teacher = Auth::guard('teacher')->user();
        return $this->returnSuccessData($teacher, '', 200);
    }

    /**
     * Login
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()) {
            return  $this->returnValidationError($validator->errors()->first(),400);
        }
 
        //$credentials = $request->only(['email', 'password']);

        $teacher = Teacher::where('email', $request->email)->first();
        if(isset($teacher)){
            // dd('hi');
            if(Hash::check($request->password, $teacher->password)){
                //create token
                $token = $teacher->createToken('teacher_token')->plainTextToken;

                //response
                return $this->returnSuccessData($token, 'teacher logged in successfully', 200);
            }else{
                return $this->returnError('password did not match', 400);
            }
        }
        return $this->returnError('teacher not found', 404);
    }

    /**
     * Logout
     */
    public function logout(){
        Auth::guard('teacher')->user()->tokens()->delete();

        return $this->returnSuccessData(null, 'teacher logged out successfully', 200);
    }

    //test
    public function test(){
        $teacher = Auth::guard('teacher')->user();
        return $this->returnSuccessData($teacher,'message', 200);
    }
}
