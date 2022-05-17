<?php

namespace App\Http\Response;

trait ResponseJson
{
    /**
     * @param $data
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function json($code = 200, $msg = '', $data = [])
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ], $code);
    }
}