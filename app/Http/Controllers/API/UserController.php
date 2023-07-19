<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;



class UserController extends Controller
{

    public function index()
    {
        $users = User::all();

        foreach($users as $key => $user) {
            $user['user_roles'] = $user->roles->pluck('name')->implode(', ');
        }

        return $users;
    }

    public function store(Request $request)
    {

        $rules = [
            'full_name' => 'required|string',
            'email_address' => 'required|email|unique:users',
            'roles' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {          
            return response()->json([
                'status' => "error",
                'errors' => $validator->errors()->all()
            ],422); 
        }

        $user = new User();
        $user->full_name = $request->full_name;
        $user->email_address = $request->email_address;
        $user->save();

        $user->roles()->attach($request->roles);

        return response()->json([
            'status' => "error",
            'message' => "User $user->full_name has been added",
        ],200); 
    }
}
