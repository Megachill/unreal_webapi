<?php
/**
 * @author      Megachill
 * @date        31/03/2017
 * @file        AuthController.php
 * @copyright   MIT
 *
 * This controller handles all authentication routes
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{

    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|max:255',
            'password' => 'required'
        ]);

        try {
            // if the user could not be authenticated
            // for further security you COULD throw a 404 (not found) instead
            // to throw potential hackers off
            if (!$token = $this->auth->attempt($request->only('username', 'password'))) {
                return response()->json([
                    'error' => 'credentials_not_found'
                ], 401);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'error' => 'token_expired'
            ], 500);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'error' => 'token_invalid'
            ], 500);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'unauthorized',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }

        // no errors we generated a token, send response
        return response()->json([
            'data' => $request->user(),
            'meta' => [
                'token' => $token
            ]
        ], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|min:4|max:255|unique:users',
            'email'     => 'required|email|max:255|unique:users',
            'password'  => 'required|min:6',
        ]);

        $user = User::create([
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => app('hash')->make($request->password),
        ]);

        $token = $this->auth->attempt($request->only('username','password'));

        return response()->json([
            'data' => $user,
            'meta' => [
                'token' => $token
            ],
        ], 200);
    }

    /**
     * logs the current provided token out
     *
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function logout(Request $request)
    {
        $this->auth->invalidate($this->auth->getToken());
        return response()->json([
            'message' => 'logout_success',
        ], 200);

        /*$this->auth->invalidate($this->auth->getToken());
        return response(null, 200);*/
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }

}