<?php

namespace App\Http\Controllers\API\V1;

use App\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
    public function login(Request $request)
    {

        dd($request->get());
        dd($token = JWTAuth::getToken('value'));
        $credentials = request(['email', 'password']);

        $token = auth()->setTTL(15)->attempt($credentials);
        $refreshToken = auth()->setTTL(1440)->attempt($credentials);
        if (!$token) {
            throw new UnauthorizedException();
        }


        return Response::message(__('auth.messages.you_have_successfully_logged_into_your_account'))->data($this->respondWithToken($token,$refreshToken))->send();
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
    protected function respondWithToken(string $token, string $refreshToken): array
    {
        return [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            // 'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
