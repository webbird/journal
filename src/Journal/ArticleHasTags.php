<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

/**
 * Description of ArticleImage
 *
 * @author bmartino
 */
class ArticleHasTags 
{
    use \webbird\common\PropertyGeneratorTrait;
    
    public static string $tablename = 'mod_journal_articlehastags';
    
    public static function getTagsForArticle(int $articleID) : array
    {
        $adapter = \webbird\cmsbridge\Bridge::getAdapter();
        $queryBuilder = $adapter->db()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($adapter->prefix().ArticleHasTags::$tablename, 't1')
            ->rightJoin('t1', $adapter->prefix().Tag::$tablename, 't2', 't1.tag_id=t2.tag_id' )
            ->where('t1.article_id = ?')
            ->setParameter(0, $articleID)
        ;
        $resultSet = $queryBuilder->execute();
        return $resultSet->fetchAllAssociative();
    }
}
