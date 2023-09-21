<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Service\Auth\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request){
        $user = $this->userService->create([
            'name' => $request->validated()['name'],
            'email' => $request->validated()['email'],
            'password' => Hash::make($request->validated()['password'])
        ]);

        if(!$user){
            return response(
                ['message' => 'Erro ao criar usuário'],
                Response::HTTP_FORBIDDEN);
        }
        //TODO - especificar os erros

        $token = $user->createToken($request->nameToken)->plainTextToken;

        $response =[
            'user' => $user,
            'token' => $token
        ];

        return response($response, Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json(['token' => $request->user()->createToken('API Token')->plainTextToken]);
        }

        return response([
            'message' => 'Email ou senha invalidos.'
        ], Response::HTTP_UNAUTHORIZED);

    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Deslogado com sucesso.'
        ], Response::HTTP_OK);
    }
}
