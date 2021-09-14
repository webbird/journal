<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

use \webbird\journal\Base as Base;

use \Soatok\Cupcake\Blends\{
    CheckboxWithLabel,
    RadioSet
};
use \Soatok\Cupcake\Form As Form;
use \Soatok\Cupcake\Ingredients\{
    Input\Text,
    Input\File,
    Input\Radio,
    Textarea,
    SelectTag,
    Div,
    PurifiedHtmlBlock,
    RawHtmlBlock,
    Label
};

class Settings extends Base
{
    public    static string $tablename = 'mod_journal_settings';
    public    static array  $settings;
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
        'tag_order'         => '6',
        'use_second_block'  => 'N',         // WBCE only
        'view'              => 'default',
        'view_order'        => '6',         // custom
    ];
    
    /**
     * 
     * @param int $articleID
     * @return type
     */
    public static function createForm(int $articleID)
    {
        FormConfigLoader::loadForm('settings', __DIR__.'/settings.form.php');
        $article = new self($articleID);
        $form = (new Form())
            ->setMethod('post')
            ->addClass('settings asgrid');
        
        $settings = self::getSettings();
        foreach($settings as $key => $value) {
echo "KEY $key VALUE $value<br />";            
            $form->append(
                (new Text($key))
                    ->setId($key)
                    ->setRequired(true)
                    ->setValue((string)$value)
            )
            ->createAndPrependLabel(self::$i18n->t($key))
            ;
        }
        return $form->render();
    }
    
    /**
     * get settings (retrieve from DB and combine with defaults)
     * 
     * @return array
     */
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
