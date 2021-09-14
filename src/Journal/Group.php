<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

use \webbird\journal\Base as Base;

use \Soatok\Cupcake\Form As Form;
use \Soatok\Cupcake\Ingredients\{
    Button,
    Div,
    Input\Text,
    Input\Checkbox,
    Label
};

/**
 * Description of Group
 *
 * @author bmartino
 */
class Group extends Base
{
    use \webbird\common\PropertyGeneratorTrait;
    
    public static string $tablename = 'mod_journal_groups';
    public static string $idfield = 'group_id';
    
    private static $defaults = [
        'group_id' => 0,
        'group_active' => 'Y',
        'group_position' => 1,
        'group_title' => '',
        'group_created' => null,
    ];
    
    public function __construct(?int $groupID=null)
    {
        if($groupID) {
            $result = self::$adapter->db()->executeQuery('SELECT * FROM '.self::$adapter->prefix().self::$tablename.' WHERE group_id=?',array($groupID));
            $data = $result->fetchAssociative();
            $this->getParameters($data);
        } else {
            $this->getParameters(self::$defaults);
        }
    }
    
    public function save()
    {
        
    }
    
    public static function createForm(int $groupID)
    {
        $group = new self($groupID);
        $form = (new Form())
            ->setMethod('post')
            ->addClass('addgroup')
            ->append(
                (new Text('title'))
                    ->setId('title')
                    ->setRequired(true)
                    ->setValue($group->group_title)
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
