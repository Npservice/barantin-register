<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserLoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/user/login",
     *     tags={"User"},
     *     summary="Login user",
     *     description="Endpoint untuk login user with endpoint '/api/v2'",
     *     operationId="loginUser",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="username",
     *                      type="string",
     *                      description="Username for login",
     *                      default="induk",
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      format="password",
     *                      description="Password for login",
     *                      default="12345678"
     *                  )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function loginUser(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'username' => ['required'],
                'password' => ['required'],
            ]);

            if ($validate->fails()) {
                $error = $validate->errors();
                return ApiResponse::errorResponse('Validation failed', 422, $error->all());
            }


            $user = User::where('username', $request->username)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                $user->tokens()->delete();
                $token = $user->createToken($user->id . 'BarantinK3y')->plainTextToken;
                return ApiResponse::successResponse('Login Successfully', collect($user)->except('refresh_token', 'created_at', 'updated_at'), false, ['token' => 'Bearer ' . $token]);
            }

            return ApiResponse::errorResponse('Unauthorized', 401, 'Account not found');
        } catch (\Throwable $e) {
            return ApiResponse::errorResponse('Failed to login account', 500, $e->getMessage());
        }
    }
}
