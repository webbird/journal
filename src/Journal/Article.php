<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

use \webbird\journal\Base as Base;

/**
 * Description of Article
 *
 * @author bmartino
 */
class Article extends Base
{
    use \webbird\common\PropertyGeneratorTrait;
    #use \webbird\journal\UtilitiesTrait;
    
    public static string $tablename = 'mod_journal_articles';
    public static string $idfield = 'article_id';
    
    private static $defaults = [
        'article_id' => 0,
        'article_active' => 'Y',
        'article_position' => 0,
        'article_title' => '',
        'article_link' => '',
        'article_content_short' => '',
        'article_content_long' => '',
        'article_views' => 0,
        'article_copied_from' => null,
        'article_images' => [],
        'article_tags' => [],
    ];
    
    public function __construct(?int $articleID=null)
    {
        if($articleID>0) {
            $queryBuilder = self::$adapter->db()->createQueryBuilder();
            $queryBuilder
                ->select('t1.*, t4.`group_title`')
                ->from(self::$adapter->prefix().self::$tablename, 't1')
                ->join('t1', self::$adapter->prefix().ArticleHasSection::$tablename, 't2', 't1.`article_id`=t2.`article_id`')
                ->rightJoin('t1', self::$adapter->prefix().ArticleHasGroups::$tablename, 't3', 't1.`article_id`=t3.`article_id`')
                ->rightJoin('t3', self::$adapter->prefix().Group::$tablename, 't4', 't3.`group_id`=t4.`group_id`')
                ->where('t1.`article_id` = ?')
                ->setParameter(0, $articleID)
            ;
            try {
                $result = $queryBuilder->execute();
                $data = $result->fetch();
                $this->getParameters($data);
                $this->images = $this->getImages($articleID); // attach images 
                $this->tags = $this->getTags($articleID);     // get tags
                $this->publishing_dates = $this->getMyPublishingDates($articleID);
            } catch ( \Exception $e ) {

            }
        } else {
            $this->getParameters(self::$defaults);
        }
    }
    
    /**
     * 
     * @param int $articleID
     * @return type
     */
    public static function createForm(int $articleID)
    {
        $article = new self($articleID);
        echo "FILE [", __FILE__, "] FUNC [", __FUNCTION__, "] LINE [", __LINE__, "]<br /><textarea style=\"width:100%;height:200px;color:#000;background-color:#fff;\">";
        print_r($article);
        echo "</textarea><br />";
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
    
    public function getMyPublishingDates(int $articleID) : array
    {
        return Publishing::getPublishingDates('article', $articleID);
    }
    
    public function getTags(int $articleID) : array
    {
        return ArticleHasTags::getTagsForArticle($articleID);
    }
}
