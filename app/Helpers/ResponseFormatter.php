<?php

namespace App\Helpers;

/**
 * Format response.
 */
class ResponseFormatter
{
    /**
     * API Response
     *
     * @var array
     */
    protected static $response = [
        // 'meta' => [
        //     'code' => 200,
        //     'status' => 'success',
        //     'message' => null,
        // ],
        'code' => 200,
        'status' => 'success',
        'message' => null,
        'data' => null,
    ];

    /**
     * Give success response.
     */
    public static function success($data = null, $message = null)
    {
        $response = [
            'code' => 200,
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $response['code']);
    }

    /**
     * Give error response.
     */
    public static function error($data = null, $message = null, $code = 400)
    {
        $response = [
            'code' => $code,
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $response['code']);
    }
}