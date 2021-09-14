<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

require_once __DIR__.'/vendor/autoload.php';

use \Noodlehaus\Config As Config;

class FormConfigLoader 
{
    private static array $forms = [];
    
    public static function loadForm(string $formname, string $filename)
    {
        try {
            $temp = Config::load($filename);
        } catch ( \Exception $e ) {
            echo "FILE [", __FILE__, "] FUNC [", __FUNCTION__, "] LINE [", __LINE__, "]<br /><textarea style=\"width:100%;height:200px;color:#000;background-color:#fff;\">";
            print_r($e);
            echo "</textarea><br />";
        }
        echo "FILE [", __FILE__, "] FUNC [", __FUNCTION__, "] LINE [", __LINE__, "]<br /><textarea style=\"width:100%;height:200px;color:#000;background-color:#fff;\">";
        print_r($temp);
        echo "</textarea><br />";
        self::$forms[$formname] = $temp;
    }
}
