<?php
declare(strict_types=1);

namespace Migrano;

interface MigrationStorage
{

    const ORDER_ASC = 'asc';
    const ORDER_DESC = 'desc';

    /**
     * @return MigrationFile[]
     */
    public function listMigrationFiles($orderedBy = self::ORDER_ASC);

}
