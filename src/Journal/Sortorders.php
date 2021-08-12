<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

class Sortorders
{
    public static string $tablename = 'mod_journal_sortorder';
    
    protected static array $order;
    
    public static function getSortorders() : array
    {
        if(empty(self::$order))
        {
            $adapter = \webbird\cmsbridge\Bridge::getAdapter();
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
