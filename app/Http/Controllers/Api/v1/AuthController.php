<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\v1\auth\UserLoginRequest;
use App\Http\Requests\Api\v1\auth\UserRegisterRequest;
use App\Http\Resources\Api\v1\users\UserResource;
use App\Http\Resources\Api\v1\users\UserDetailResource;

class AuthController extends Controller
{
    use ApiResponser;

    /**
     * @OA\POST(
     *     path="/api/v1/login",
     *     tags={"Auth"},
     *     summary="Auth Login",
     *     description="Auth Login",
     *     @OA\RequestBody(
     *         description="User objects",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="email",
     *                     type="string",
     *                     example="gugu@gmail.com"
     *                 ),
     *                  @OA\Property(
     *                     property="password",
     *                     description="password",
     *                     type="string",
     *                     example="sithuadmin"
     *                 ),
     *                 required={"email","password"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *   @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *   @OA\JsonContent()
     *     )
     * )
     */

    public function login(UserLoginRequest $request)
    {

        if (Auth::guard('api')->user())
            return $this->errorResponse( 'Already login!',401);

        $user = User::where('email', $request->email)->first();

        if (!$user)
            return $this->errorResponse( "Please login first!",401);

        $hashPassword = $user->password;

        if (!Hash::check($request->password, $hashPassword))
            return $this->errorResponse( 'Crediantials do not match',401);

        return response()->json([
            'data' => new UserResource($user),
            'success' => true,
            'token' => $user->createToken('My App')->accessToken,
        ], 200);

    }

    /**
     * @OA\POST(
     *     path="/api/v1/register",
     *     tags={"Auth"},
     *     summary="Register",
     *     description="Register",
     *     @OA\RequestBody(
     *         description="User objects",
     *         required=true,
     *         @OA\MediaType(
     *               mediaType="multipart/form-data", 
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="name",
     *                     type="string",
     *                     example="gugu"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="email",
     *                     type="string",
     *                     example="gugu@gmail.com"
     *                 ),
     *                  @OA\Property(
     *                     property="password",
     *                     description="password",
     *                     type="string",
     *                     example="guguadmin"
     *                 ),
     *                  @OA\Property(
     *                     property="password_confirmation",
     *                     description="password_confirmation",
     *                     type="string",
     *                     example="guguadmin"
     *                 ),
     *                  @OA\Property(
     *                     property="image",
     *                     description="image",
     *                     type="file",
     *                     example="image"
     *                 ),
     *                 required={"name","email","password"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *   @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *   @OA\JsonContent()
     *     )
     * )
     */

    public function register(UserRegisterRequest $request)
    {

        if (Auth::guard('api')->user())
            return $this->errorResponse( 'Already login!',401);

        $data = $this->getRequestData($request);

        $user = User::create($data);

        return response()->json([
            'data' => new UserResource($user),
            'success' => true,
            'token' => $user->createToken('My App')->accessToken,
        ], 200);

    }
    private function getRequestData($request)
    {
        return [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'image'    => $request->file('image') ? $request->file('image')->store('images') : null,
        ];
    }
}
