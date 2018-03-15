<?php
namespace Migrano\Tests;

use InvalidArgumentException;
use LogicException;
use Migrano\Storage\LocalMigrationStorage;
use Migrano\MigrationFile;
use PHPUnit\Framework\TestCase;

class LocalMigrationStorageTest extends TestCase
{

    public function testListIsArrayOfMigrationFiles()
    {
        $storage = new LocalMigrationStorage(__DIR__ . '/data/migrations/valid');
        $list = $storage->listMigrationFiles();
        $this->assertInternalType('array', $list);
        $this->assertEquals(2, count($list));
        foreach ($list as $migrationFile) {
            $this->assertInstanceOf(MigrationFile::class, $migrationFile);
        }
    }

    public function testPathIsNotValidDirectory()
    {
        $this->expectException(InvalidArgumentException::class);
        new LocalMigrationStorage(__DIR__ . '/__nonsene');
    }

    public function testInvalidFileWithTwoMigrationClassesInOneFile()
    {
        $this->expectException(LogicException::class);
        $storage = new LocalMigrationStorage(__DIR__ . '/data/migrations/invalid');
        $storage->listMigrationFiles();
    }

}
