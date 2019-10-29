<?php

namespace CapstoneLogic\Users\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use CapstoneLogic\Users\Http\Request\SignupRequest;
use CapstoneLogic\Users\Http\Request\LoginRequest;
use CapstoneLogic\Users\Model\User;
use CapstoneLogic\Users\Resource\UserResource;

class AuthController extends Controller {

    /**
     * @OA\Get(
     *     path="/api/users/auth",
     *     tags={"Auth"},
     *     @OA\Response(
     *          response=200,
     *          description="User data",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", format="string", type="string"),
     *              @OA\Property(property="data", type="object",
     *                  allOf={
     *                      @OA\JsonContent(ref="#/components/schemas/User")
     *                  }
     *              )
     *          )
     *     ),
     *     @OA\Response(response=403,description="Unauthorized"),
     *     @OA\Response(response=500,description="Server error"),
     * )
     */
    public function show(Request $request) {
        $user = $request->user();

        return response([
            'status' => 'success',
            'data' => new UserResource(User::findOrFail($user->id))
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/users/auth/signup",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="first_name",type="string"),
     *              @OA\Property(property="last_name",type="string"),
     *              @OA\Property(property="email",type="string",format="email"),
     *              @OA\Property(property="password",type="string")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *     )
     * )
     */
    public function signup(SignupRequest $request) {
        
        $user = new User([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        $user->save();
        
        $user = User::find($user->id);
        $user->assignRole('customer');

        return response()->json([
            'message' => 'Successfully created user!',
            'data' => new UserResource($user)
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/users/auth/login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         description="Returns API token with given user email and password",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="email",type="string",format="email"),
     *              @OA\Property(property="password",type="string")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User sign in",
     *         @OA\JsonContent(
     *              @OA\Property(property="access_token",type="string"),
     *              @OA\Property(property="token_type",type="string"),
     *              @OA\Property(property="expires_at",type="string"),
     *         ),
     *     )
     * )
     */
    public function login(LoginRequest $request) {
        $credentials = request(['email', 'password']);
        
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        
        $user = $request->user();
        $user = User::find($user->id);
        
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/users/auth/logout",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="User logout",
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *     )
     * )
     */
    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
}
