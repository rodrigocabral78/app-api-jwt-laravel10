<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make(
                $request->all(),
                [
                    'name'                  => 'required|string|max:255',
                    'email'                 => 'required|string|email|max:255|unique:users',
                    'password'              => 'required|string|min:6|confirmed',
                    'password_confirmation' => 'required|string|min:6|same:password',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'validation error',
                    'errors'  => $validateUser->errors()->toArray(),
                ], Response::HTTP_BAD_REQUEST);
            }

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password,
            ]);

            // return response()->json([
            //     'status'       => true,
            //     'message'      => 'User Created Successfully',
            //     'access_token' => $user->createToken("API TOKEN")->plainTextToken
            // ], Response::HTTP_OK);

            $token = Auth::login($user);

            return response()->json([
                'access_token' => $token,
                'token_type'   => 'bearer',
                'expires_in'   => Auth::factory()->getTTL() * 60,
                'user'         => Auth::user(),
            ], Response::HTTP_CREATED);

            // return response()->json([
            //     // 'status'        => 'success',
            //     'status'       => true,
            //     'message'      => 'User created successfully',
            //     'access_token' => $token,
            //     'user'         => $user,
            //     // 'authorisation' => [
            //     //     'token' => $token,
            //     //     'type'  => 'bearer',
            //     // ],
            // ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
