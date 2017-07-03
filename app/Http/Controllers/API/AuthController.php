<?php

namespace Noox\Http\Controllers\API;

use Carbon\Carbon;
use JWTAuth;
use Auth;
use Noox\Http\Controllers\Traits\FacebookAuthentication;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Noox\Models\FcmToken;

/**
 * @resource Authentication
 *
 * API endpoints for authentication purpose.
 */
class AuthController extends BaseController
{
    use FacebookAuthentication;

    /**
     *  API Login
     *  Will return API token <token> with its lifetime <lifetime> and time window <gracetime> to renew the token after generated. <lifetime> and <gracetime> is in minutes.
     *  The token should be included in the header "Authorization : Bearer <token>"
     *
     * @param string $email
     * @param string $password
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(\Noox\Http\Requests\APILoginRequest $request)
    {
        $token = null;
        // check if fb_token is supplied
        if ($request->input('fb_token')) {
            if ($user = $this->attemptFbAuth($request)) {
                $token = JWTAuth::fromUser($user, ['type' => 'user']);
            } else {
                return response()->json(['error' => trans('auth.failed')], 401);
            }
        }

        // if the token still empty, attempt to authenticate with email & password.
        if (empty($token)) {
            // grab credentials from the request
            $credentials = $request->only('email', 'password');
            try {
                // attempt to verify the credentials and create a token for the user
                if (!$token = JWTAuth::attempt($credentials, ['type' => 'user'])) {
                    return response()->json(['error' => trans('auth.failed')], 401);
                }
            } catch (JWTException $e) {
                // something went wrong whilst attempting to encode the token
                return response()->json(['error' => 'could_not_create_token'], 500);
            }
        }

        // add the supplied token if not exist in the database
        if (! FcmToken::where('token', $request->input('fcm_token'))->count() > 0) {
            $fcmToken = new FcmToken();
            $fcmToken->token = $request->input('fcm_token');

            Auth::user()->fcmTokens()->save($fcmToken);
        }

        // all good so return the token
        if ($token) {
                $ret = [
            'valid_until'  => Carbon::now()->addMinutes(config('jwt.ttl'))->timestamp,
            'refresh_before' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->timestamp,
            'token'     => $token,
            ];
            return response()->json($ret);
        } else {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
    }

    /**
     * Returns the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }

    /**
     * Renew the token.
     * Make sure the header contains Authorization : Bearer <token>
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getToken()
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            return $this->response->errorMethodNotAllowed('Token not provided');
        }
        try {
            $refreshedToken = JWTAuth::refresh($token);
        } catch (JWTException $e) {
            return $this->response->errorInternal('Not able to refresh Token');
        }
        $ret = [
        'valid_until'  => Carbon::now()->addMinutes(config('jwt.ttl'))->timestamp,
        'refresh_before' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->timestamp,
        'token'     => $refreshedToken,
        ];
        return response()->json($ret);
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
            ]);
        JWTAuth::invalidate($request->input('token'));
        return response()->json(['message' => 'Token invalidated.'], 200);
    }
}
