<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{

    public function signup(Request $request)
    {
        $valid = Validator::make($request->all(), [
            "email" => "required",
            "name" => "required",
            "phone" => "required",
            "password" => "required",
        ]);

        if ($valid->fails()) {
            return response()->json([
                "msg" => "Data not Entered Properly",
            ]);
        } else {

            $existingUser = User::where("email", $request->email)->first();

            if (!$existingUser) {
                return response()->json([
                    "msg" => "User Already Exists",
                ]);
            } else {

                $user = new User();

                $user->email = $request->email;
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->password = $request->password;
                $user->type = "user";
                $user->forgetOtp = "0000";
                if ($user->save()) {
                    return response()->json([
                        "msg" => "Signup Successfull"
                    ]);
                } else {
                    return response()->json([
                        "msg" => "Something Went wrong"
                    ]);

                }
            }


        }

    }

    public function login(Request $request)
    {
        $valid = Validator::make($request->all(), [
            "phone" => "required",
            "password" => "required"
        ]);

        if (!$valid->fails()) {
            $existingUser = User::where("phone", $request->phone)->get();

            if (count($existingUser) > 0) {
                if ($request->password == $existingUser[0]->password) {
                    return response()->json([
                        "msg" => "Login Successfull"
                    ]);
                } else {
                    return response()->json([
                        "msg" => "Incorrect password"
                    ]);

                }

                // return response()->json([
                //     "msg" => $existingUser[0]->password
                // ]);
            } else {
                return response()->json([
                    "msg" => "User not found"
                ]);
            }


        } else {
            return response()->json([
                "msg" => "Data not Entered Properly"
            ]);
        }
    }
}
