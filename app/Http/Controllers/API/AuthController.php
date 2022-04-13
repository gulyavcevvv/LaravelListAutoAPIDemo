<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/user/register",
     *     summary="Register user",
     *     tags={"User"},
     *     @OA\Parameter(
     *         description="User name",
     *         in="query",
     *         name="name",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="User email",
     *         in="query",
     *         name="email",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="password",
     *         in="query",
     *         name="password",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *           @OA\Property(
     *             property="success",
     *             type="string",
     *             example="true",
     *           ),
     *           @OA\Property(
     *             property="token",
     *             type="object",
     *             @OA\Property(
     *               property="token",
     *               type="string",
     *               example="AbCvTmwO7yP0h9sRz5KRUplvRfW34T1GKUMryFffBy",
     *               )
     *            )
     *         ) 
     *     )
     * )
     *
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 401);
        }


        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $token = $user->createToken($user->name)->plainTextToken;

        return $this->sendResponse(['token' => $token]);
    }

    /**
     * @OA\Get(
     *     path="/api/user/token",
     *     summary="Get new token",
     *     tags={"User"},
     *     @OA\Parameter(
     *         description="User email",
     *         in="query",
     *         name="email",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="password",
     *         in="query",
     *         name="password",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *           @OA\Property(
     *             property="success",
     *             type="string",
     *             example="true",
     *           ),
     *           @OA\Property(
     *             property="token",
     *             type="object",
     *             @OA\Property(
     *               property="token",
     *               type="string",
     *               example="AbCvTmwO7yP0h9sRz5KRUplvRfW34T1GKUMryFffBy",
     *               )
     *            )
     *         ) 
     *     )
     * )
     *
     */
    public function token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8']
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError(['error' => 'The provided credentials are incorrect.'], 401);
        }

        return $this->sendResponse(['token' => $user->createToken($user->name)->plainTextToken]);
    }
}
