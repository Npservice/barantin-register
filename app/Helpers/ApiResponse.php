<?php
namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Collection;

class ApiResponse
{
    protected static $response = [
        'status' => 'success',
        'code' => 200,
        'message' => null,
        'data' => null
    ];
    public static function SuccessResponse(string $message = null, mixed $data = null, bool $paginate = false, array $custom = []): JsonResponse
    {
        self::$response['message'] = $message;

        self::$response = self::DataProccess($paginate, $data);
        self::$response = self::CustomMessage($custom);

        return response()->json(self::$response);
    }
    public static function ErrorResponse(string $message = null, int $code, mixed $error = null): JsonResponse
    {
        unset(self::$response['data']);

        self::$response['status'] = 'failed';
        self::$response['message'] = $message;
        self::$response['code'] = $code;
        self::$response['error'] = $error;

        return response()->json(self::$response, $code);
    }
    private static function DataProccess(bool $paginate, mixed $data)
    {
        if ($paginate) {
            unset(self::$response['data']);

            if ($data instanceof Collection) {
                return self::$response = array_merge(self::$response, $data->toArray());
            } else {
                return self::$response = array_merge(self::$response, $data);
            }
        }

        self::$response['data'] = $data;
        return self::$response;
    }


    private static function CustomMessage(array $custom)
    {
        if (count($custom) > 0) {
            return self::$response = array_merge(self::$response, $custom);
        }
        return self::$response;
    }
}

