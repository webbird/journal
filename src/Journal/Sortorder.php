<?php

declare(strict_types=1);

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace webbird\journal\Journal;

/**
 * Description of Sortorder
 *
 * @author bmartino
 */
class Sortorder 
{
    public static string $tablename = 'mod_journal_sortorder';
    
    protected static array $order;
    
    public static function getSortorder(\webbird\cmsbridge\Bridge $adapter) : array
    {
        if(empty(self::$order))
        {
            $queryBuilder = $adapter->db()->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from($adapter->prefix().self::$tablename)
            ;
            $stmt = $queryBuilder->execute();
            while (($row = $stmt->fetchAssociative()) !== false) {
                self::$order[$row['order_id']] = $row;
            }
        }
        return self::$order;
    }
}
