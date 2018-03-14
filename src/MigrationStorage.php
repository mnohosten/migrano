<?php
namespace Migrano;

interface MigrationStorage
{

    /**
     * @return MigrationFile[]
     */
    public function listMigrationFiles();

}
