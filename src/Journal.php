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
    }
    
    public function getArticles(int $sectionID) : array
    {
        $articles = [];
        $queryBuilder = $this->adapter->db()->createQueryBuilder();
        $queryBuilder
            ->select('article_id')
            ->from($this->adapter->prefix().Journal\Article::$tablename)
            ->where('section_id = ?')
            ->setParameter(0, $sectionID)
        ;
        $stmt = $queryBuilder->execute();
        while (($row = $stmt->fetchAssociative()) !== false) {
            $articles[] = new Journal\Article(intval($row['article_id']), $this->adapter);
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
        $data = array(
            'curr_tab'  => 'articles',
            'edit_url'  => 'x',
            'sectionID' => $sectionID,
            'pageID'    => $this->adapter->getPageForSection(sectionID: $sectionID),
            'base_url'  => $this->adapter->getURL(),
        );
        // additional data
        switch($data['curr_tab']) {
            case 'articles':
                $data['articles'] = $this->getArticles($sectionID);
                $data['orders'] = Journal\Sortorder::getSortorder($this->adapter);
                $data['num_articles'] = 0;
                break;

        }
echo "FILE [", __FILE__, "] FUNC [", __FUNCTION__, "] LINE [", __LINE__, "]<br /><textarea style=\"width:100%;height:200px;color:#000;background-color:#fff;\">";
print_r($data['articles']);
echo "</textarea><br />";        
        echo $this->te->render('modify', array('data'=>$data));
    }
}
