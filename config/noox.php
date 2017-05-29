<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Number of Top News
    |--------------------------------------------------------------------------
    |
    | Maximum number of top news to be returned to the user.
    */
    
    'max_top_news' => 10,

    /*
    |--------------------------------------------------------------------------
    | Top News Interval
    |--------------------------------------------------------------------------
    |
    | Set the interval of time to count number of readers.
    */
    
    'top_news_interval' => 60,
    
    /*
    |--------------------------------------------------------------------------
    | Facebook Application ID
    |--------------------------------------------------------------------------
    |
    | The value of Facebook application ID. You can get this from Facebook 
    | Developers page.
    */
    
    'fb_app_id' => env('FB_APP_ID'),

    /*
    |--------------------------------------------------------------------------
    | Facebook Application Secret
    |--------------------------------------------------------------------------
    |
    | The value of Facebook application secret. You can get this from Facebook 
    | Developers page.
    */
    
    'fb_app_secret' => env('FB_APP_SECRET'),
];