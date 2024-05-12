<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Admin;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;




class LoginController extends Controller
{
    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="userLogin",
     *      tags={"Authentication"},
     *      summary="Login User Barantin",
     *      description="User Access Login",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="username",
     *                      type="string",
     *                      description="Username for login",
     *                      default="admin",
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
     * @OA\Response(
     *     response=200,
     *     description="Login Successful",
     *     @OA\JsonContent(
     *         @OA\Property(
     *             property="status",
     *             type="string",
     *             example="success",
     *             description="Status of the response"
     *         ),
     *         @OA\Property(
     *             property="code",
     *             type="integer",
     *             example=200,
     *             description="HTTP status code"
     *         ),
     *         @OA\Property(
     *             property="message",
     *             type="string",
     *             example="Login Successfully",
     *             description="Message of the response"
     *         ),
     *         @OA\Property(
     *             property="data",
     *             type="object",
     *             @OA\Property(
     *                 property="id",
     *                 type="string",
     *                 example="(example)",
     *                 description="User ID"
     *             ),
     *             @OA\Property(
     *                 property="nama",
     *                 type="string",
     *                 example="test oke",
     *                 description="User's name"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 example="email@example.com",
     *                 description="User's name"
     *             ),
     *             @OA\Property(
     *                 property="role",
     *                 type="string",
     *                 example="cabang",
     *                 description="User's role"
     *             ),
     *             @OA\Property(
     *                 property="status_user",
     *                 type="integer",
     *                 example=1,
     *                 description="User's status"
     *             ),
     *             @OA\Property(
     *                 property="created_at",
     *                 type="string",
     *                 example="2024-03-29T09:59:40.000000Z",
     *                 description="User's creation date"
     *             ),
     *             @OA\Property(
     *                 property="updated_at",
     *                 type="string",
     *                 example="2024-03-29T11:10:09.000000Z",
     *                 description="User's last update date"
     *             )
     *         ),
     *         @OA\Property(
     *             property="token",
     *             type="string",
     *             example="(example)",
     *             description="Access token"
     *         )
     *     )
     * ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="status",
     *                  type="string",
     *                  example="failed",
     *                  description="Status of the response"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example=422,
     *                  description="HTTP status code"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="validation failed",
     *                  description="Error message"
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="array",
     *                  @OA\Items(type="string"),
     *                  example={"The username field is required.", "The password field is required."},
     *                  description="Validation errors"
     *              )
     *          )
     *      )
     * )
     */


    public function login(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'username' => ['required'],
                'password' => ['required'],
            ]);

            if ($validate->fails()) {
                $error = $validate->errors();
                return ApiResponse::ErrorResponse('Validation failed', 422, $error->all());
            }

            if (Auth::guard('api')->attempt($request->only('username', 'password'))) {
                $user = Admin::where('username', $request->username)->first();
                $user->tokens()->delete();
                $token = $user->createToken($user->id . 'BarantinK3y')->plainTextToken;
                return ApiResponse::SuccessResponse('Login Successfully', $user, false, ['token' => 'Bearer ' . $token]);
            }

            return ApiResponse::ErrorResponse('Unauthorized', 401, 'Account not found');
        } catch (\Throwable $e) {
            return ApiResponse::ErrorResponse('Failed to login account', 500, $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
