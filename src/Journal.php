<?php

declare(strict_types=1);

namespace webbird\journal;

use \webbird\journal\Journal\Article as Article;

/**
 * Description of Journal
 *
 * @author bmartino
 */
class Journal extends Base
{
    use UtilitiesTrait;
   
    const VERSION = '0.1';

    public function __construct(\Doctrine\DBAL\Connection $conn)
    {
        self::$conn = $conn;
        $this->init();
    }
   
    /**
     * get all articles for given $sectionID
     * 
     * @param int $sectionID
     * @return array
     */
    public function getArticles(int $sectionID) : array
    {
        $articles = [];
        try {
            $queryBuilder = self::$adapter->db()->createQueryBuilder();
            $queryBuilder
                ->select('t1.`article_id`')
                ->from(self::$adapter->prefix().Journal\Article::$tablename, 't1')
                ->join('t1', self::$adapter->prefix().Journal\ArticleHasSection::$tablename, 't2', 't1.`article_id`=t2.`article_id`')
                ->where('t2.`section_id` = ?')
                ->setParameter(0, $sectionID)
#            ->orderBy()
            ;
            $stmt = $queryBuilder->execute();
            while (($row = $stmt->fetchAssociative()) !== false) {
                $articles[] = new Journal\Article(intval($row['article_id']));
            }
        } catch ( \Exception $e ) {
            throw new RuntimeException('DB Error: '.$e->getMessage());
        }

        return $articles;
    }
    
    /**
     * 
     * @param int $sectionID
     */
    public function modify(int $sectionID) : void
    {
        // Defaults
        $curr_tab = 'articles';

        $action = $this->getAction();
        if(!empty($action)) {
            switch($action) {
                case 'add_article':
                    #echo Journal\Article::createForm();
                    break;
            }
        }
        
        $pageID = self::$adapter->getPageForSection(sectionID: $sectionID);
        $data = array(
            'curr_tab'  => $curr_tab,
            'edit_url'  => self::$adapter->getEditURL(pageID: $pageID),
            'sectionID' => $sectionID,
            'pageID'    => $pageID,
            'base_url'  => self::$adapter->getURL(),
        );

        // additional data
        switch($data['curr_tab']) {
            case 'articles':
            default:
                if(isset($_REQUEST['mod_journal_add_article_btn'])) {
                    $data['form'] = Journal\Article::createForm();
                } else {
                    $data['articles'] = $this->getArticles($sectionID);
                    $data['orders'] = Journal\Sortorders::getSortorders();
                    $data['num_articles'] = 0;
                }
                break;

        }
        echo self::$te->render('modify', array('data'=>$data));
    }
    
    private function getAction() : string
    {
        $action = $_POST['jac'] ?? '';
        $actions = array(
            'add_article',
        );
        if(in_array($action, $actions)) {
            return (string)$action;
        }
        return '';
    }
}
