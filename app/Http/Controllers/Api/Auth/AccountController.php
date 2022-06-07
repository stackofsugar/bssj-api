<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller {
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            "username" => "required|unique:users|max:255|min:3|alpha_dash",
            "fullname" => "required|max:255|min:3",
            "address" => "required|max:255|min:3",
            "phone" => "required|unique:users|numeric|max:999999999999",
            "email" => "required|email:dns|unique:users|max:255",
            "password" => "required"
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), "Unprocessable content", 422);
        }

        $validated = $validator->validated();
        $validated["password"] = Hash::make($validated["password"]);
        $user = User::create($validated);

        return $this->sendResponse([
            "message" => "User succesfully registered",
            "username" => $user->username,
            "token" => $user->createToken("bssj-api")->plainTextToken
        ]);
    }

    public function authenticate(Request $request) {
        $credentials = Validator::make($request->all(), [
            "username" => "required",
            "password" => "required",
        ]);

        if ($credentials->fails()) {
            return $this->sendError($credentials->errors(), "Unprocessable content", 422);
        }

        if (Auth::attempt($credentials->validated())) {
            return $this->sendResponse([
                "message" => "Authentication successful",
                "token" => $request->user()->createToken($request->user()->username . "|" . $request->ip())->plainTextToken
            ]);
        } else {
            return $this->sendError("Wrong password or username", "Unauthorized", 401);
        }
    }

    public function invalidate(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse([
            "message" => "Token successfully invalidated"
        ]);
    }

    public function getUser(Request $request) {
        $user_copy = $request->user();
        unset($user_copy->password);
        return $this->sendResponse([
            "logged_in" => true,
            "user_info" => $user_copy,
        ]);
    }

    public function updateProfile(Request $request) {
        $user = $request->user();
        $edit_request = Validator::make($request->all(), [
            "username" => "required_without_all:fullname,address,phone,email,password|unique:users|max:255|min:3|alpha_dash",
            "fullname" => "required_without_all:username,address,phone,email,password|max:255|min:3",
            "address" => "required_without_all:fullname,username,phone,email,password|max:255",
            "phone" => "required_without_all:fullname,address,username,email,password|unique:users|numeric|max:999999999999",
            "email" => "required_without_all:fullname,address,phone,username,password|email:dns|unique:users|max:255",
            "password" => "required_without_all:fullname,address,phone,email,username",
        ], [
            "required_without_all" => "Atleast one attribute should be edited!"
        ]);

        if ($edit_request->fails()) {
            return $this->sendError($edit_request->errors(), "Unprocessable content", 422);
        }

        $validated = $edit_request->validated();

        if (isset($validated["password"])) {
            $validated["password"] = Hash::make($validated["password"]);
        }

        foreach ($validated as $key => $value) {
            $user->$key = $value;
        }

        $user->save();
        $user_copy = $user;
        unset($user_copy->password);

        return $this->sendResponse([
            "new_profile" => $user_copy,
        ]);
    }

    public function testLogin(Request $request) {
        return [
            "logged_in" => true,
            "username" => $request->user()->username,
        ];
    }
}
