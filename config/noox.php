<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Facebook Application ID
    |--------------------------------------------------------------------------
    |
    | The value of Facebook application ID. You can get this from Facebook 
    | Developers page. Put the value in the .env file.
    */
    
    'fb_app_id' => env('FB_APP_ID'),

    /*
    |--------------------------------------------------------------------------
    | Facebook Application Secret
    |--------------------------------------------------------------------------
    |
    | The value of Facebook application secret. You can get this from Facebook 
    | Developers page. Put the value in the .env file.
    */
    
    'fb_app_secret' => env('FB_APP_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | News Report Threshold
    |--------------------------------------------------------------------------
    |
    | When the total number of news report is beyond the threshold, all admin
    | will be notified via in app notification and notification pop up if the
    | admin is logged in.
    */

    'news_report_threshold' => 10,
];