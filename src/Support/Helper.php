<?php namespace Amamarul\Modules\Support;

class Helper {
    public static function loadModuleHelpers($dir)
    {
        $helpers = \File::glob($dir . '/helpers/*.php');
        foreach ($helpers as $helper) {
            require_once $helper;
        }
    }
}
