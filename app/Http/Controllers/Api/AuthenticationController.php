<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('jwt.auth', ['except' => ['store']]);
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        // $remember    = $request->only('remember');

        // $token = Auth::attempt($credentials, $remember);
        $token = Auth::attempt($credentials);
        if (!$token) {
            Log::warning("Unauthorized");

            return response()->json([
                'error' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);

        // try {
        //     $validateUser = Validator::make($request->all(),
        //     [
        //         'email' => 'required|email',
        //         'password' => 'required'
        //     ]);

        //     if($validateUser->fails()){
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'validation error',
        //             'errors' => $validateUser->errors()
        //         ], Response::HTTP_UNAUTHORIZED);
        //     }

        //     if(!Auth::attempt($request->only(['email', 'password']))){
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'Email & Password does not match with our record.',
        //         ], Response::HTTP_UNAUTHORIZED);
        //     }

        //     $user = User::where('email', $request->email)->first();

        //     return response()->json([
        //         'status' => true,
        //         'message' => 'User Logged In Successfully',
        //         'token' => $user->createToken("API TOKEN")->plainTextToken
        //     ], Response::HTTP_OK);

        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => $th->getMessage()
        //     ], Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        Log::info('Show');

        return response()->json($user);
        // return response()->json(Auth::user());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        //Request is validated, do logout
        // try {
        //     JWTAuth::invalidate($request->token);

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'User has been logged out'
        //     ]);
        // } catch (JWTException $exception) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Sorry, user cannot be logged out'
        //     ], Response::HTTP_INTERNAL_SERVER_ERROR);
        // }

        try {
            $logout = Auth::logout();
            JWTAuth::invalidate();
            // dd($logout);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Successfully logged out']);

        // try {
        //     // $user->tokens()->delete();

        //     // Revoke the token that was used to authenticate the current request...
        //     $request->user()->currentAccessToken()->delete();

        //     // Revoke a specific token...
        //     // $user->tokens()->where('id', $tokenId)->delete();

        //     return response()->json([
        //         'status' => true,
        //         'message' => 'User Logged In Successfully',
        //         // 'token' => $user->tokens()->delete(),
        //         'token' => $request->user()->currentAccessToken()->delete(),
        //     ], Response::HTTP_OK);

        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => $th->getMessage()
        //     ], Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::factory()->getTTL() * 60,
            // 'user'         => Auth::user(),
        ]);
    }
}
