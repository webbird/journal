<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

use \webbird\journal\Base as Base;

class Tag extends Base
{
    use \webbird\common\PropertyGeneratorTrait;
    
    public static string $tablename = 'mod_journal_tags';
    public static string $idfield = 'tag_id';
    
    private static $defaults = [
        'tag_id' => 0,
        'tag_title' => '',
        'tag_color' => '',
        'hover_color' => '',
        'text_color' => '#000',
        'text_hover_color' => '#000',
        'tag_cssclass' => '',
        'tag_created' => null,
    ];
    
    public function __construct(?int $tagID=null)
    {
        if($tagID) {
            $result = self::$adapter->db()->executeQuery('SELECT * FROM '.self::$adapter->prefix().self::$tablename.' WHERE tag_id=?',array($tagID));
            $data = $result->fetchAssociative();
            $this->getParameters($data);
        } else {
            $this->getParameters(self::$defaults);
        }
    }
    
    public static function createForm(int $tagID)
    {
        $tag= new self($tagID);
        $form = (new Form())
            ->setMethod('post')
            ->addClass('addtag')
            ->append(
                (new Text('title'))
                    ->setId('title')
                    ->setRequired(true)
                    ->setValue($tag->tag_title)
            )
            ->createAndPrependLabel(self::$i18n->t('Title'))
            ->append(
                (new Checkbox('active'))
                    ->setId('active')
            )
            ->createAndPrependLabel(self::$i18n->t('Active'))
            ->append(
                (new Button('submit'))
                    ->setType('submit')
                    ->setLabel(self::$i18n->t('Save'))
                    ->addClass("btn btn-primary")
            )
        ;
        return $form->render();
    }
    
}
