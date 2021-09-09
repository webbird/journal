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
        } catch ( \Exception $ex ) {
            throw new RuntimeException('DB Error: '.$ex->getMessage());
        }

        return $articles;
    }
    
    public function getGroups(int $sectionID) : array
    {
        $groups = [];
        try {
            $queryBuilder = self::$adapter->db()->createQueryBuilder();
            $queryBuilder
                ->select('t1.`group_id`')
                ->from(self::$adapter->prefix().Journal\Group::$tablename, 't1')
                ->join('t1', self::$adapter->prefix().Journal\GroupHasSection::$tablename, 't2', 't1.`group_id`=t2.`group_id`')
                ->where('t2.`section_id` = ?')
                ->setParameter(0, $sectionID)
                ;
            $stmt = $queryBuilder->execute();
            while (($row = $stmt->fetchAssociative()) !== false) {
                $groups[] = new Journal\Group(intval($row['group_id']));
            }
        } catch (Exception $ex) {
            throw new RuntimeException('DB Error: '.$ex->getMessage());
        }
        return $groups;
    }
    
    /**
     * 
     * @param int $sectionID
     */
    public function modify(int $sectionID) : void
    {
        $this->setSection($sectionID);
        
        // Defaults
        $curr_tab = filter_input(\INPUT_GET, 'tab', \FILTER_SANITIZE_STRING) ?? 'articles';

        /*
        $action = $this->getAction();
        if(!empty($action)) {
            switch($action) {
                case 'add_article':
                    #echo Journal\Article::createForm();
                    break;
            }
        }
         * 
         */
        
        $pageID = self::$adapter->getPageForSection(sectionID: $sectionID);
        $data = array(
            'curr_tab'  => $curr_tab,
            'edit_url'  => self::$adapter->getEditURL(pageID: $pageID),
            'delim'     => self::$adapter->getDelimiter(),
            'sectionID' => $sectionID,
            'pageID'    => $pageID,
            'base_url'  => self::$adapter->getURL(),
            'orders'    => Journal\Sortorders::getSortorders(),
        );

        // additional data
        switch($data['curr_tab']) {
            case 'groups':
                $data = array_merge($data,$this->getGroupTab($sectionID));
                break;
            case 'articles':
            default:
                $data = array_merge($data,$this->getArticleTab($sectionID));
                break;
        }
        echo "FILE [", __FILE__, "] FUNC [", __FUNCTION__, "] LINE [", __LINE__, "]<br /><textarea style=\"width:100%;height:200px;color:#000;background-color:#fff;\">";
        print_r($data);
        echo "</textarea><br />";
        echo self::$te->render('modify', array('data'=>$data));
    }
    
    /**
     * 
     * @return string
     */
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
    
    /**
     * populate the [Articles] tab
     * @param int $sectionID
     * @return array
     */
    private function getArticleTab(int $sectionID) : array
    {
        $data = [];
        $articleID = filter_input(\INPUT_GET, 'article_id', \FILTER_SANITIZE_NUMBER_INT) ?? null;
        $addnew = filter_input(\INPUT_POST, 'mod_journal_add_article_btn', \FILTER_SANITIZE_STRING) ?? null;
        if($addnew) {
            $articleID = 0;
        }
        
        if($articleID) {
            //$data['article'] = new Journal\Article(intval($_REQUEST['article_id']));
            $data['article'] = Journal\Article::createForm(intval($articleID));
        } else {
            $data['articles'] = $this->getArticles($sectionID);
            $data['num_articles'] = 0;
        }
        return $data;
    }
    
    /**
     * populate the [Groups] tab
     * @param int $sectionID
     * @return array
     */
    private function getGroupTab(int $sectionID) : array
    {
        $data = [];
        $data['groups'] = $this->getGroups($sectionID);
        $data['form'] = Journal\Group::createForm(0);
        return $data;
    }
}
