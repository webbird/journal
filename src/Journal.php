<?php

declare(strict_types=1);

namespace webbird\journal;

/**
 * Description of Journal
 *
 * @author bmartino
 */
class Journal 
{
    const VERSION = '0.1';
    
    protected object $bridge;
    protected object $db;
    protected object $te;
    protected object $i18n;
    protected array  $settings = [
        'append_title'      => 'N',
        'block2'            => 'N',
        'crop'              => 'N',
        'gallery'           => 'fotorama',
        'imgmaxsize'        => 123456,
        'imgmaxwidth'       => 4096,
        'imgmaxheight'      => 4096,
        'imgthumbwidth'     => 150,
        'imgthumbheight'    => 150,
        'mode'              => 'advanced',
        'articles_per_page' => 0,
        'use_second_block'  => 'N',
        'view'              => 'default',
        'view_order'        => 0,
        'subdir'            => 'articles',
        'image_subdir'      => '.articles',
    ];
    
    /**
     * constructor
     */
    public function __construct()
    {
        // initialize CMSBridge
        $this->bridge = new \webbird\cmsbridge\Bridge(__DIR__.'/../../..');
        // initialize constants
        define('CMS_NAME',$this->bridge->getCMSName());
        // initialize DB connection
        $this->db = $this->bridge->getDBHandle();
        // initialize localization
        $this->i18n = new \webbird\i18n\Translator();
        $locale = $this->i18n->getLocale();
        $this->i18n->setDefaultTextdomain('journal');
        $this->i18n->addTranslationFile(
                'Array',
                __DIR__.'/../locales/'.$locale.'/LC_MESSAGES/journal.php'
        );
        // initialize template engine
        $this->te = new \League\Plates\Engine(__DIR__.'/../templates/backend');
        // add translator
        $this->te->registerFunction('t', function ($string) {
            return $this->i18n->translate($string);
        });
        // add accessor to settings
        $this->te->registerFunction('s', function ($key) {
            return $this->getOption($key);
        });
    }
    
    /**
     * 
     * @param string $key
     * @return string
     */
    public function getOption(string $key) : string
    {
        return (isset($this->settings[$key]) ? $this->settings[$key] : '');
    }
    
    /**
     * 
     * @param int $section_id
     */
    public function modify(int $section_id) : void
    {
        $data = array(
            'curr_tab' => 'articles',
            'edit_url' => 'x'
        );
        echo $this->te->render('modify', array('data'=>$data));
    }
}
