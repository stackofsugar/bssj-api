<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function sendResponse($response) {
        return [
            "status" => [
                "code" => 200
            ],
            "response" => $response
        ];
    }

    protected function sendPaginatedResponse($response, $page_num, $out_of) {
    }

    protected function sendError($message, $errmsg, $http_code = 402) {
        return response()->json([
            "status" => [
                "code" => $http_code,
                "message" => $errmsg
            ],
            "response" => $message,
        ], $http_code);
    }

    protected function sendCodedError($message, $error_code, $http_code = 402) {
        return response()->json([
            "status" => [
                "code" => $http_code,
                "errcode" => $error_code,
            ],
            "response" => $message,
        ], $http_code);
    }
}
