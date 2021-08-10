<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

/**
 * Description of ArticleImage
 *
 * @author bmartino
 */
class ArticleImage 
{
    use \webbird\common\PropertyGeneratorTrait;
    
    public static string $tablename = 'mod_journal_article_image';
}
