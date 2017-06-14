<?php

namespace Noox\Http\Controllers\Traits;

use Noox\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

/**
 * An ugly trait used to authenticate with Facebook. Should be using Guzzle instead of CURL.
 */
trait FacebookAuthentication
{
  /**
   * Attempt to verify the supplied access token with facebook and match the returned FB ID with the user in database.
   * 
   * @param  \Illuminate\Http\Request $request
   * @return Noox\Models\User
   */
  public function attemptFbAuth(Request $request)
  {
    if (! $fbToken = $request->input('fb_token')) {
        return;
    }

    $result = $this->exec_auth($fbToken);
    $fbid   = '';
    if (!empty($result->data) && $result->data->is_valid) {
      $fbid = $result->data->user_id;
        if ($user = User::where('fb_id', '=', $fbid)->first()) {
            return $user;
        }
    }
    return;
  }

  /**
   * Attempt to extract Facebook ID from supplied access token.
   * 
   * @param  \Illuminate\Http\Request $request
   * @return string
   */
  public function extractFbId(Request $request)
  {
    if (! $fbToken = $request->input('fb_token')) {
        return;
    }

    $result = $this->exec_auth($fbToken);
    $fbid   = '';
    if (!empty($result->data) && $result->data->is_valid) {
      return $result->data->user_id;
    }
    return;
  }

  /**
   * Utility function to be used by fb_auth function.
   * 
   * @param  [String] $user_access_token [access token to authenticate]
   * @return [JSON Object]               [raw result from Facebook's Graph API]
   */
  protected function exec_auth($fbToken)
  {
    $client = new Client([
            'base_uri' => 'https://graph.facebook.com',
            'timeout'  => 10,
            ]);

    $uri = 'debug_token?input_token='.$fbToken.'&access_token='.config('noox.fb_app_id').'|'.config('noox.fb_app_secret');

    try {
      $response = $client->get($uri);
    } catch (\GuzzleHttp\Exception\ConnectException $e) {
      $this->response->errorInternal('Unable to connect to Facebook server.');
    } catch (\GuzzleHttp\Exception\ServerException $e) {
      $this->response->error('Article analysis service unavailable.', 503);
    }

    return json_decode($response->getBody());
  }
}