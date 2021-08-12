<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

class ArticleHasImages
{
    use \webbird\common\PropertyGeneratorTrait;
    
    public static string $tablename = 'mod_journal_articlehasimages';
    
    public static function getImagesForArticle(int $articleID) : array
    {
        $adapter = \webbird\cmsbridge\Bridge::getAdapter();
        $queryBuilder = $adapter->db()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($adapter->prefix().self::$tablename, 't1')
            ->rightJoin('t1', $adapter->prefix().Image::$tablename, 't2', 't1.`img_id`=t2.`img_id`' )
            ->where('t1.`article_id` = ?')
            ->setParameter(0, $articleID)
        ;
        try {
            $resultSet = $queryBuilder->execute();
            return $resultSet->fetchAllAssociative();
        } catch ( \Exception $e ) {
            return [];
        }
    }
}
