<?php

namespace Migrano\Tests;

use InvalidArgumentException;
use Migrano\MigrationFile;
use Migrano\Repository\SqliteRepository;
use PHPUnit\Framework\TestCase;

class SqliteRepositoryTest extends TestCase
{


    public function testIsMigratedOnEmptyDatabaseIsFalse()
    {
        $repository = $this->getInMemoryRepository();
        $file = new MigrationFile('none', 'none');
        $this->assertFalse($repository->isMigrated($file));
    }

    public function testIsMigrated()
    {
        $repository = $this->getInMemoryRepository();
        $file = new MigrationFile('none', 'none');
        $this->assertFalse($repository->isMigrated($file));
        $repository->store($file);
        $this->assertTrue($repository->isMigrated($file));
    }

    public function testDelete()
    {
        $repository = $this->getInMemoryRepository();
        $file = new MigrationFile('none', 'none');
        $repository->store($file);
        $this->assertTrue($repository->isMigrated($file));
        $repository->delete($file);
        $this->assertFalse($repository->isMigrated($file));
    }

    public function testStore()
    {
        $repository = $this->getInMemoryRepository();
        $file = new MigrationFile('path_example', __CLASS__);
        $repository->store($file);
        $this->assertTrue($repository->isMigrated($file));
    }

    public function testRepositoryDatabaseDoesNotExists()
    {
        $this->expectException(InvalidArgumentException::class);
        new SqliteRepository(__DIR__ . '/file_that_does_not_exists.sqlite');
    }

    private function getInMemoryRepository(): SqliteRepository
    {
        return new SqliteRepository(':memory:');
    }

}
