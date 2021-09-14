<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

use \webbird\journal\Base as Base;

class Publishing extends Base
{
   
    public static string $tablename = 'mod_journal_publishing';
    public static string $idfield = 'tag_id';
    
    /**
     * 
     */
    public static function getPublishingDates(string $ref, int $id)
    {
        $classname = '\webbird\journal\Journal\\'.ucfirst($ref);
        $result = self::$adapter
                    ->db()
                    ->executeQuery(
                        'SELECT * FROM '.self::$adapter->prefix().self::$tablename.' WHERE `ref`=? AND `id`=?',
                        array($ref,$id)
                    )
        ;
        return $result->fetchAllAssociative();
    }
    
}
