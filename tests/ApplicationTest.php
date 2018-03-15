<?php
namespace Migrano\Tests;

use Migrano\Application;
use Migrano\MigrationStorage;
use Migrano\Repository\SqliteRepository;
use Migrano\Storage\LocalMigrationStorage;
use Migrano\Tests\Fixtures\ContainerFactory;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{

    public function testMigrate()
    {
        $path = __DIR__ . '/data/migrations/valid';
        $repository = new SqliteRepository(':memory:');
        $storage = new LocalMigrationStorage($path);
        $app = new Application(
            (new ContainerFactory())->create(),
            $repository,
            $storage
        );

        $app->migrate();

        $this->assertNotEmpty($storage->listMigrationFiles());
        foreach ($storage->listMigrationFiles() as $migrationFile) {
            $this->assertTrue($repository->isMigrated($migrationFile));
        }
    }

    public function testRollback()
    {
        $path = __DIR__ . '/data/migrations/valid';
        $repository = new SqliteRepository(':memory:');
        $storage = new LocalMigrationStorage($path);
        $app = new Application(
            (new ContainerFactory())->create(),
            $repository,
            $storage
        );
        $app->migrate();
        $files = $storage->listMigrationFiles(MigrationStorage::ORDER_DESC);
        foreach ($files as $file) {
            $app->rollback();
            $this->assertFalse($repository->isMigrated($file));
            $isMigrated = false;
            foreach ($files as $checkFile) {
                $this->assertEquals($isMigrated, $repository->isMigrated($checkFile));
                if($file == $checkFile) $isMigrated = true;
            }
        }

    }

    public function testReset()
    {
        $path = __DIR__ . '/data/migrations/valid';
        $repository = new SqliteRepository(':memory:');
        $storage = new LocalMigrationStorage($path);
        $app = new Application(
            (new ContainerFactory())->create(),
            $repository,
            $storage
        );
        $files = $storage->listMigrationFiles();
        $app->migrate();
        foreach ($files as $file) $this->assertTrue($repository->isMigrated($file));
        $app->rollback();
        foreach ($storage->listMigrationFiles(MigrationStorage::ORDER_DESC) as $file) {
            $this->assertFalse($repository->isMigrated($file));
            break;
        }
        $app->reset();
        foreach ($files as $file) $this->assertTrue($repository->isMigrated($file));
    }

    public function testRewind()
    {
        $path = __DIR__ . '/data/migrations/valid';
        $repository = new SqliteRepository(':memory:');
        $storage = new LocalMigrationStorage($path);
        $app = new Application(
            (new ContainerFactory())->create(),
            $repository,
            $storage
        );
        $files = $storage->listMigrationFiles();
        $app->migrate();
        foreach ($files as $file) $this->assertTrue($repository->isMigrated($file));
        $app->rewind();
        foreach ($files as $file) $this->assertFalse($repository->isMigrated($file));
    }

}
