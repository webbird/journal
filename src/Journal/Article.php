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
    
    public function __construct(?int $articleID=null)
    {
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
    }
    
    public static function createForm()
    {
        $form = (new Form())
            ->setMethod('post')
            ->append(
                (new Text('title'))
                    ->setId('title')
                    ->setRequired(true)
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
            )->createAndPrependLabel(self::$i18n->t('Start date'))
            ->append(
                (new Text('enddate'))
                    ->setId('enddate')
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
                        (new RawHtmlBlock(self::$adapter->getWYSIWYGEditor('short','content','100%','150')))
                    )
                    ->addClass('grid')
            )
            ->append(
                (new Div())
                    ->append(
                        (new Label(self::$i18n->t('Long / full text')))
                    )
                    ->append(
                        (new RawHtmlBlock(self::$adapter->getWYSIWYGEditor('long','content','100%','600')))
                    )
                    ->addClass('grid')
            )
        ;
        if(self::getOption('block2') == 'Y') {
            $form->append(
                (new Div())
                    ->append(
                        (new Label(self::$i18n->t('Block2')))
                    )
                    ->append(
                        (new RawHtmlBlock(self::$adapter->getWYSIWYGEditor('block2','content','100%','300')))
                    )
                    ->addClass('grid')
            );
        }
        // add file upload fields
        $upload_fields = [];
        for($i=1;$i<=5;$i++) {
            $upload_fields[] = new File('file'.$i);
        }
        $div = new Div('galleryimages');
        foreach(array_values($upload_fields) as $f) {
            $div->append($f);
        }
        $form
            ->append(
                (new PurifiedHtmlBlock('<hr />'))
            )
            ->append(
                $div
                    ->addClass('grid')
            )
        ;

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
