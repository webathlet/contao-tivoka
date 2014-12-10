<?php

namespace BugBuster\Tivoka;

/**
 * Class Runtime
 * 
 * @author BugBuster
 * @author C-C-A (thanks)
 */
class Runtime
{

    /**
     * Determinate if curl is enabled.
     *
     * @return bool
     */
    public static function isCurlEnabled()
    {
        return function_exists('curl_init');
    }
    
    /**
     * Determinate if allow_url_fopen is enabled.
     *
     * @return bool
     */
    public static function isAllowUrlFopenEnabled()
    {
        return (bool) ini_get('allow_url_fopen');
    }
    
}

