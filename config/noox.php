<?php

return [

    /*
    |--------------------------------------------------------------------------
    | News Analyzer Base URL
    |--------------------------------------------------------------------------
    |
    | The location of news analyzer AI in the network. 
    | Format: http://10.10.10.10:10000
    */
    
    'analyzer_base_url' => env('NOOX_ANALYZER_BASE_URL', 'null'),

    /*
    |--------------------------------------------------------------------------
    | News Extractor Script Location
    |--------------------------------------------------------------------------
    |
    | The directory of which the Python script of site scrapper is located.
    | This command will be executed: shell_exec('cd {config('noox.news_extractor_dir')} && py nooxcrawler.py {$url}')
    */
    
    'news_extractor_dir' => env('NOOX_NEWS_EXTRACTOR_DIR'),

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
    | Set the interval of time (in minutes) to count number of readers.
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