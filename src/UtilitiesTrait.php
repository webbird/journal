<?php

declare(strict_types=1);

namespace webbird\journal;

trait UtilitiesTrait 
{
    public    static \Doctrine\DBAL\Connection $conn;
    public    static object $adapter;
    protected static object $te;
    protected static object $i18n;
    protected static array  $defaultsettings = [
        'append_title'      => 'N',
        'articles_per_page' => 0,
        'block2'            => 'N',
        'crop'              => 'N',
        'gallery'           => 'fotorama',
        'image_subdir'      => '.articles',
        'imgmaxsize'        => 123456,
        'imgmaxwidth'       => 4096,
        'imgmaxheight'      => 4096,
        'imgthumbwidth'     => 150,
        'imgthumbheight'    => 150,
        'mode'              => 'advanced',
        'subdir'            => 'articles',
        'use_second_block'  => 'N',
        'view'              => 'default',
        'view_order'        => 0,
    ];
    
    public function init()
    {
        $this->getAdapter();
        $this->getI18n();
        $this->getTE();
    }
    
    /**
     * 
     * @return \webbird\cmsbridge\Bridge
     */
    public function getAdapter() : \webbird\cmsbridge\Bridge
    {
        if(empty(self::$conn)) {
            throw new RuntimeException();
        }
        if(empty(self::$adapter)) {
            self::$adapter = new \webbird\cmsbridge\Bridge(self::$conn);
            // initialize constants
            define('THEME',self::$adapter->getThemeName());
        }
        return self::$adapter;
    }
    
    /**
     * 
     * @return \webbird\i18n\Translator
     */
    public function getI18n() : \webbird\i18n\Translator
    {
        if(empty(self::$i18n)) {
            // initialize localization
            self::$i18n = new \webbird\i18n\Translator();
            $locale = self::$i18n->getLocale();
            self::$i18n->setDefaultTextdomain('journal');
            self::$i18n->addTranslationFile(
                    'Array',
                    __DIR__.'/../locales/'.$locale.'/LC_MESSAGES/journal.php'
            );
        }
        return self::$i18n;
    }
    
    /**
     * 
     * @return \League\Plates\Engine
     */
    public function getTE() : \League\Plates\Engine
    {
        if(empty(self::$te)) {
            // initialize template engine
            self::$te = new \League\Plates\Engine(__DIR__.'/../templates/backend');
            // add translator
            self::$te->registerFunction('t', function ($string) {
                return (empty($string) ?: self::$i18n->translate($string));
            });
            // add accessor to settings
            self::$te->registerFunction('s', function ($key) {
                return $this->getOption($key);
            });
            // add accessor to bridge
            self::$te->registerFunction('bridge', function() {
                return self::$adapter;
            });
        }
        return self::$te;
    }
    
        /**
     * 
     * @param string $key
     * @return string
     */
    public static function getOption(string $key) : string
    {
        return (isset(self::$settings[$key]) ? (string)self::$settings[$key] : '');
    }
}
