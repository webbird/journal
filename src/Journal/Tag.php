<?php

declare(strict_types=1);

namespace webbird\journal\Journal;

/**
 * Description of Group
 *
 * @author bmartino
 */
class Tag {
    use \webbird\common\PropertyGeneratorTrait;
    
    public static string $tablename = 'mod_journal_tags';
    protected \webbird\cmsbridge\Bridge $adapter;
    
    
}
