<?php

namespace App\Http\Controllers\API\V1;

use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\UnauthorizedException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);

    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        $token = auth()->attempt($credentials);
        if (!$token) {
            throw new UnauthorizedException();
        }


        return Response::message(__('auth.messages.you_have_successfully_logged_into_your_account'))->data($this->respondWithToken($token))->send();
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return Response::message(__('auth.messages.your_account_information_has_been_found'))->data(auth()->user())->send();
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return Response::message(__('auth.messages.you_have_successfully_logged_out'))->data(auth()->user())->send();
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {

        dd(auth()->refresh());
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            // 'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
