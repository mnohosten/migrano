<?php

use Migrano\Migration;
use Migrano\Tests\Fixtures\DepObj;

class MigrationFile_122 implements Migration
{
    /**
     * @var DepObj
     */
    public $depObj;


    /**
     * MigrationFile_122 constructor.
     */
    public function __construct(DepObj $depObj)
    {
        $this->depObj = $depObj;
    }

    public function migrate(): void
    {
        // TODO: Implement migrate() method.
    }

    public function rollback(): void
    {
        // TODO: Implement rollback() method.
    }


}