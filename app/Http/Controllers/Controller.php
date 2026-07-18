<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function responseSuccess($message = 'Success', $data = null, $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function responseError($message = 'Error', $errors = null, $status = 422)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    protected function responseRedirect($message = 'Success', $route = null, $status = 302)
    {
        if (request()->expectsJson()) {
            return $this->responseSuccess($message);
        }

        return redirect($route)->with('success', $message);
    }
}
