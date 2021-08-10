<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

/**
 * Description of Images
 *
 * @author bmartino
 */
class Image
{
    use \webbird\common\PropertyGeneratorTrait;
    
    public static string $tablename = 'mod_journal_images';
    
    public function __construct(int $imageID, \webbird\cmsbridge\Bridge $adapter)
    {
        $queryBuilder = $adapter->db()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($adapter->prefix().self::$tablename)
            ->where('img_id = ?')
            ->setParameter(0, $imageID)
        ;
        $result = $queryBuilder->execute();
        $data = $result->fetch();
        $this->getParameters($data);
    }
}
