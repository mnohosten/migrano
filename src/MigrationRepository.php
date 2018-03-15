<?php
declare(strict_types=1);

namespace Migrano;

interface MigrationRepository
{

    public function isMigrated(MigrationFile $file);
    public function delete(MigrationFile $file);
    public function store(MigrationFile $file);

}
