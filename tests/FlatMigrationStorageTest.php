<?php
namespace Migrano\Tests;

use Migrano\Flat\FlatMigrationStorage;
use PHPUnit\Framework\TestCase;

class FlatMigrationStorageTest extends TestCase
{



    public function testListIsArrayOfMigrationFiles()
    {
        $storage = new FlatMigrationStorage(__DIR__ . '/data/migrations/valid');
        $list = $storage->listMigrationFiles();
        $this->assertInternalType('array', $list);
        $this->assertEquals(2, count($list));
        foreach ($list as $migrationFile) {
            $this->assertInstanceOf(\Migrano\MigrationFile::class, $migrationFile);
        }
    }

    public function testPathIsNotValidDirectory()
    {
        $this->expectException(\InvalidArgumentException::class);
        new FlatMigrationStorage(__DIR__ . '/__nonsene');
    }
}
