<?php

if (! function_exists('module_config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
     function module_config($key = null, $default = null)
     {
         return config('module.'.$key, $default);
     }
}
