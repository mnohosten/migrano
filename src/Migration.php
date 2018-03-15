<?php
declare(strict_types=1);

namespace Migrano;

interface Migration
{

    /**
     * Execute migration forward migration
     */
    public function migrate(): void;


    /**
     * Execute rollback migration
     */
    public function rollback(): void;

}