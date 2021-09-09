<?php

declare(strict_types=1);

namespace webbird\journal;

use \webbird\journal\Journal\Settings as Settings;

trait UtilitiesTrait 
{
    public    static \Doctrine\DBAL\Connection $conn;
    public    static object $adapter;
    public    static int    $sectionID;
    protected static object $te;
    protected static object $i18n;
    
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
                return Settings::getSetting($key);
            });
            // add accessor to bridge
            self::$te->registerFunction('bridge', function() {
                return self::$adapter;
            });
        }
        return self::$te;
    }
    
    public function setSection(int $sectionID) : self
    {
        self::$sectionID = $sectionID;
        return $this;
    }
    
    public static function getSection() : int
    {
        return self::$sectionID;
    }
}
