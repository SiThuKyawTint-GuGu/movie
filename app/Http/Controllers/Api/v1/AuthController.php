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

    public function logout()
    {
        $user = auth()->guard('api')->user()->token();
        $user->revoke();
        return $this->successResponse('Logout successfully');
    }

    public function detail(){
        $user = auth()->guard('api')->user();

        if(!$user) return $this->errorResponse('No Data Found!',204);

        return $this->successResponse(new UserDetailResource($user));

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
