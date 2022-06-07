<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller {
    public function testAdmin() {
        return [
            "admin" => true
        ];
    }

    public function getUser(Request $request) {
        $req = $request->segment(4);

        if (is_numeric($req)) {
            $user = User::find($req);
        } else {
            $user = User::where("username", $req)->first();
        }

        if (!isset($user)) {
            return $this->sendError("User not found", "User not found", 404);
        }

        return $this->sendResponse([
            "user" => $user,
        ]);
    }

    public function getAllUser(Request $request) {
        $page_limit = 0;
        if (isset($request->all()["page_limit"])) {
            $page_limit = $request->all()["page_limit"];
        }

        if (is_numeric($page_limit)) {
            $page_limit = intval($page_limit);
        }

        if (!is_numeric($page_limit) || $page_limit == 0) {
            $page_limit = 10;
        }

        $response = User::paginate($page_limit);

        return $this->sendResponse([
            "total_items" => $response->total(),
            "items_per_page" => $response->perPage(),
            "last_page" => $response->lastPage(),
            "current_page" => $response->currentPage(),
            "first_id_in_page" => $response->firstItem(),
            "last_id_in_page" => $response->lastItem(),
            "data" => $response->getCollection()->toArray(),
        ]);
    }

    public function updateUser(Request $request) {
        $req = $request->segment(4);

        if (is_numeric($req)) {
            $user = User::find($req);
        } else {
            $user = User::where("username", $req)->first();
        }

        if (!isset($user)) {
            return $this->sendError("User not found", "User not found", 404);
        }

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

        return $this->sendResponse([
            "new_profile" => $user,
        ]);
    }
}
