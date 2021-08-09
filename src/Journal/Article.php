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
    
    public function __construct(int $articleID, $j)
    {
        $queryBuilder = $j->db()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($j->bridge->getDBPrefix().self::$tablename)
            ->where('article_id = ?')
            ->setParameter(0, $articleID)
        ;
        $result = $queryBuilder->execute();
        $data = $result->fetch();
        $this->getParameters($data);
    }
}
