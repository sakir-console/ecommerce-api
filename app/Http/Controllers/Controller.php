<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // Validation Helper
    protected function validateWithJson($data = [], $rules = [])
    {
        $validator = Validator::make($data, $rules);

        if ($validator->passes()) {
            return true;
        }
        return $validator->getMessageBag()->all();
    }


    // Success Result
    protected function responseWithSuccess($message = '', $data = [], $code = 200)
    {

        return response()->json([
            'result' => true,
            'message' => $message,
            'data' => $data ?? "no_data"
        ], $code, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);

    }


    //Failure Result
    protected function responseWithError($message = '', $data = [], $code = 400)
    {
        return response()->json([
            'result' => false,
            'message' => $message,
            'data' => $data
        ], $code);

    }

    // Admin verification Helper
    protected function isAdmin()
    {
        if (Auth::user()->tokenCan('role:admin')) {
            return true;
        }
        return false;
    }

    // Editor verification Helper
    protected function isEditor()
    {
        if (Auth::user()->tokenCan('role:editor')) {
            return true;
        }
        return false;
    }


}
