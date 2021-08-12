<?php

declare(strict_types=1);

namespace webbird\journal;

/**
 * Description of Journal
 *
 * @author bmartino
 */
class Journal 
{
    const VERSION = '0.1';
    
    public    object $adapter;
    protected object $te;
    protected object $i18n;
    protected array  $settings = [
        'append_title'      => 'N',
        'block2'            => 'N',
        'crop'              => 'N',
        'gallery'           => 'fotorama',
        'imgmaxsize'        => 123456,
        'imgmaxwidth'       => 4096,
        'imgmaxheight'      => 4096,
        'imgthumbwidth'     => 150,
        'imgthumbheight'    => 150,
        'mode'              => 'advanced',
        'articles_per_page' => 0,
        'use_second_block'  => 'N',
        'view'              => 'default',
        'view_order'        => 0,
        'subdir'            => 'articles',
        'image_subdir'      => '.articles',
    ];
    
    /**
     * constructor
     */
    public function __construct(\Doctrine\DBAL\Connection $conn)
    {
        // initialize CMSBridge
        $this->adapter = new \webbird\cmsbridge\Bridge($conn);
        // initialize constants
        define('THEME',$this->adapter->getThemeName());
        // initialize localization
        $this->i18n = new \webbird\i18n\Translator();
        $locale = $this->i18n->getLocale();
        $this->i18n->setDefaultTextdomain('journal');
        $this->i18n->addTranslationFile(
                'Array',
                __DIR__.'/../locales/'.$locale.'/LC_MESSAGES/journal.php'
        );
        // initialize template engine
        $this->te = new \League\Plates\Engine(__DIR__.'/../templates/backend');
        // add translator
        $this->te->registerFunction('t', function ($string) {
            return (empty($string) ?: $this->i18n->translate($string));
        });
        // add accessor to settings
        $this->te->registerFunction('s', function ($key) {
            return $this->getOption($key);
        });
        // add accessor to bridge
        $this->te->registerFunction('bridge', function() {
            return $this->adapter;
        });
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
        $queryBuilder = $this->adapter->db()->createQueryBuilder();
        $queryBuilder
            ->select('t1.`article_id`')
            ->from($this->adapter->prefix().Journal\Article::$tablename, 't1')
            ->join('t1', $this->adapter->prefix().Journal\ArticleHasSection::$tablename, 't2', 't1.`article_id`=t2.`article_id`')
            ->where('t2.`section_id` = ?')
            ->setParameter(0, $sectionID)
#            ->orderBy()
        ;
     
        try {
            $stmt = $queryBuilder->execute();
            while (($row = $stmt->fetchAssociative()) !== false) {
                $articles[] = new Journal\Article(intval($row['article_id']), $this->adapter);
            }
        } catch ( \Exception $e ) {
            
        }

        return $articles;
    }
    
    /**
     * 
     * @param string $key
     * @return string
     */
    public function getOption(string $key) : string
    {
        return (isset($this->settings[$key]) ? (string)$this->settings[$key] : '');
    }
    
    /**
     * 
     * @param int $sectionID
     */
    public function modify(int $sectionID) : void
    {
        $pageID = $this->adapter->getPageForSection(sectionID: $sectionID);
        $data = array(
            'curr_tab'  => 'articles',
            'edit_url'  => $this->adapter->getEditURL(pageID: $pageID),
            'sectionID' => $sectionID,
            'pageID'    => $pageID,
            'base_url'  => $this->adapter->getURL(),
        );

        // additional data
        switch($data['curr_tab']) {
            case 'articles':
                $data['articles'] = $this->getArticles($sectionID);
                $data['orders'] = Journal\Sortorders::getSortorders();
                $data['num_articles'] = 0;
                break;

        }
        echo $this->te->render('modify', array('data'=>$data));
    }
}
