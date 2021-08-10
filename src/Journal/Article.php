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
            ->select('*')
            ->from($adapter->prefix().self::$tablename)
            ->where('article_id = ?')
            ->setParameter(0, $articleID)
        ;
        $result = $queryBuilder->execute();
        $data = $result->fetch();
        $this->getParameters($data);
        // attach images 
        $this->images = $this->getImages();
    }
    
    public function getImages() 
    {
        $queryBuilder = $this->adapter->db()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($this->adapter->prefix().ArticleImage::$tablename, 't1')
            ->rightJoin('t1', $this->adapter->prefix().Image::$tablename, 't2', 't1.img_id=t2.img_id' )
            ->where('t1.article_id', $this->article_id)
        ;
        $resultSet = $queryBuilder->execute();
        return $resultSet->fetchAllAssociative();
    }
}
