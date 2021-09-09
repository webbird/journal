<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

use \webbird\journal\Base as Base;

use \Soatok\Cupcake\Blends\{
    CheckboxWithLabel,
    RadioSet
};
use \Soatok\Cupcake\Form As Form;
use \Soatok\Cupcake\Ingredients\{
    Input\Text,
    Input\File,
    Input\Radio,
    Textarea,
    SelectTag,
    Div,
    PurifiedHtmlBlock,
    RawHtmlBlock,
    Label
};

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
    
    private static $defaults = [
        'article_id' => 0,
        'active' => 'Y',
        'position' => 0,
        'title' => '',
        'link' => '',
        'content_short' => '',
        'content_long' => '',
        'published_when' => null,
        'published_until' => null,
        'posted_when' => null,
        'posted_by' => null,
        'views' => 0,
        'copied_from' => null,
        'images' => [],
        'tags' => [],
    ];
    
    public function __construct(?int $articleID=null)
    {
        if($articleID) {
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
                // attach images 
                $this->images = $this->getImages($articleID);
                // get assigned tags
                $this->tags = $this->getTags($articleID);
            } catch ( \Exception $e ) {

            }
        } else {
            $this->getParameters(self::$defaults);
        }
    }
    
    public static function exists(int $articleID) {
        
    }
    
    public static function createForm(int $articleID)
    {
        $article = new self($articleID);
        $form = (new Form())
            ->setMethod('post')
            ->addClass('addarticle')
            ->append(
                (new Text('title'))
                    ->setId('title')
                    ->setRequired(true)
                    ->setValue($article->title)
            )
            ->createAndPrependLabel(self::$i18n->t('Title'))
            ->append(
                (new SelectTag('group'))
                    ->setId('group')
            )->createAndPrependLabel(self::$i18n->t('Group'))
            ->append(
                (new File('postfoto'))
                    ->setId('postfoto')
            )->createAndPrependLabel(self::$i18n->t('Preview image'))
            ->append(
                (new Div())
                    ->append(
                        (new RadioSet(self::$i18n->t('Active')))
                            ->addRadio('Y', self::$i18n->t('Yes'), 'active-y', true)
                            ->addRadio('N', self::$i18n->t('No'), 'active-n')
                )
            )->createAndPrependLabel(self::$i18n->t('Active'))
            ->append(
                (new Text('startdate'))
                    ->setId('startdate')
                    ->setValue($article->published_when)
            )->createAndPrependLabel(self::$i18n->t('Start date'))
            ->append(
                (new Text('enddate'))
                    ->setId('enddate')
                    ->setValue($article->published_until)
            )->createAndPrependLabel(self::$i18n->t('End date'))
            ->append(
                (new PurifiedHtmlBlock('<hr />'))
            )
            ->append(
                (new Div())
                    ->append(
                        (new Label(self::$i18n->t('Short / teaser text')))
                    )
                    ->append(
                        (new RawHtmlBlock(self::$adapter->getWYSIWYGEditor('short',$article->content_short,'100%','150')))
                    )
                    ->addClass('grid')
            )
            ->append(
                (new Div())
                    ->append(
                        (new Label(self::$i18n->t('Long / full text')))
                    )
                    ->append(
                        (new RawHtmlBlock(self::$adapter->getWYSIWYGEditor('long',$article->content_long,'100%','600')))
                    )
                    ->addClass('grid')
            )
        ;
        // optional block 2
        if(self::getSetting('block2') == 'Y') {
            $form->append(
                (new Div())
                    ->append(
                        (new Label(self::$i18n->t('Block2')))
                    )
                    ->append(
                        (new RawHtmlBlock(self::$adapter->getWYSIWYGEditor('block2',$article->block2,'100%','300')))
                    )
                    ->addClass('grid')
            );
        }
        // add file upload fields
        $upload_fields = [];
        for($i=1;$i<=5;$i++) {
            $upload_fields[] = new File('file'.$i);
        }
        $form
            ->append(
                (new PurifiedHtmlBlock('<hr />'))
            )
            ->append(
                (new Div())
                    ->append(
                        (new Div())
                            ->appendArray($upload_fields)
                    )

                    ->addClass('images')

        );

        return $form->render();
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
