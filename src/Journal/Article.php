<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

/**
 * Description of Article
 *
 * @author bmartino
 */
class Article 
{
    use \webbird\common\PropertyGeneratorTrait;
    
    public static string $tablename = 'mod_journal_articles';
    protected \webbird\cmsbridge\Bridge $adapter;
    
    public function __construct(int $articleID, \webbird\cmsbridge\Bridge $adapter)
    {
        $this->adapter = $adapter;
        $queryBuilder = $adapter->db()->createQueryBuilder();
        $queryBuilder
            ->select('t1.*, t4.`group_title`')
            ->from($adapter->prefix().self::$tablename, 't1')
            ->join('t1', $adapter->prefix().ArticleHasSection::$tablename, 't2', 't1.`article_id`=t2.`article_id`')
            ->rightJoin('t1', $adapter->prefix().ArticleHasGroups::$tablename, 't3', 't1.`article_id`=t3.`article_id`')
            ->rightJoin('t3', $adapter->prefix().Group::$tablename, 't4', 't3.`group_id`=t4.`group_id`')
            ->where('t1.`article_id` = ?')
            ->setParameter(0, $articleID)
        ;
        try {
            $result = $queryBuilder->execute();
            $data = $result->fetch();
            $this->getParameters($data);
            // attach images 
            $this->images = $this->getImages($articleID);
            // get assigned tags
            $this->tags = $this->getTags($articleID);
        } catch ( \Exception $e ) {
            
        }
    }
    
    /**
     * 
     * @param int $articleID
     * @return array
     */
    public function getImages(int $articleID) : array
    {
        return ArticleHasImages::getImagesForArticle($articleID);
    }
    
    public function getTags(int $articleID) : array
    {
        return ArticleHasTags::getTagsForArticle($articleID);
    }
}
