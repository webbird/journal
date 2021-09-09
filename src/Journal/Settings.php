<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

use \webbird\journal\Base as Base;

class Settings extends Base
{
    public static string $tablename = 'mod_journal_settings';
    public static array $settings;
    protected static array  $defaultSettings = [
        'append_title'      => 'N',
        'articles_per_page' => 0,           // unlimited
        'block2'            => 'N',         // WBCE only
        'crop'              => 'N',         // crop images
        'gallery'           => 'fotorama',  // gallery (frontend)
        'group_order'       => '6',         // custom
        'image_subdir'      => '.articles', // located under media
        'imgmaxsize'        => 123456,
        'imgmaxwidth'       => 4096,
        'imgmaxheight'      => 4096,
        'imgthumbwidth'     => 150,
        'imgthumbheight'    => 150,
        'mode'              => 'advanced',  // admin mode advanced || basic
        'subdir'            => 'articles',
        'use_second_block'  => 'N',         // WBCE only
        'view'              => 'default',
        'view_order'        => '6',         // custom
    ];
    
    public static function getSettings() : array
    {
        if(empty(self::$settings))
        {
            $result = self::$adapter
                        ->db()
                        ->executeQuery(
                            'SELECT * FROM '.self::$adapter->prefix().self::$tablename.' WHERE section_id=?',
                            array(self::getSection())
                        )
            ;
            $data = $result->fetchAllAssociative();
            // convert
            $settings = array();
            foreach(array_values($data) as $s) {
                $settings[$s['set_name']] = $s['set_value'];
            }
            // merge with defaults
            self::$settings = \array_merge(self::$defaultSettings, $settings);
        }
        return self::$settings;
    }
    
    /**
     * 
     * @param string $key
     * @return string
     */
    public static function getSetting(string $key) : string
    {
        if(empty(self::$settings)) {
            self::getSettings();
        }
        return (isset(self::$settings[$key]) ? (string)self::$settings[$key] : '');
    }
}
