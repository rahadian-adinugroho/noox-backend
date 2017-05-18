<?php

namespace Noox\Http\Controllers\Traits;

use Noox\Models\User;
use Illuminate\Http\Request;

/**
 * An ugly trait used to authenticate with Facebook. Should be using Guzzle instead of CURL.
 */
trait FacebookAuthentication
{
  /**
   * Attempt to verify the supplied access token with facebook and match the returned FB ID with the user in database.
   * 
   * @param  \Illuminate\Http\Request $request
   * @return \Noox\Models\User
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
    }
    if ($user = User::where('fb_id', '=', $fbid)->first()) {
        return $user;
    }
    return;
  }

  /**
   * Get data from facebook using access token.
   * @param  [String] $user_access_token [access token]
   * @param  [String] $var_name          [name of field to get from Facebook's server]
   * @return [String]                    [Result data on success, else boolean FALSE on failure.]
   */
  public function getFbData($user_access_token, $var_name)
  {
    $url = "https://graph.facebook.com/me?fields=".$var_name."&access_token=".$user_access_token;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // our server

    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // facebook's server

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resultString = curl_exec($ch);

    if (curl_errno($ch))
    {
      echo 'error: ' . curl_error($ch);
      curl_close($ch);
      return FALSE;
    }
    else
    {
      curl_close($ch);
      $result = json_decode($resultString);
      if(empty($result->error))
      {
        return $result;
      }
      else
      {
        return FALSE;
      }
    }
  }

  /**
   * Utility function to be used by fb_auth function
   * @param  [String] $user_access_token [access token to authenticate]
   * @return [JSON Object]               [raw result from Facebook's Graph API]
   */
  protected function exec_auth($fbToken)
  {
    $url = 'https://graph.facebook.com/debug_token?input_token='.$fbToken.'&access_token='.config('noox.fb_app_id').'|'.config('noox.fb_app_secret');
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // our server

    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); // facebook's server

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);

    if (curl_errno($ch))
    {
      echo 'error: ' . curl_error($ch);
    }

    curl_close($ch);

    return json_decode($result);
  }
}