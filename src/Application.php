<?php
declare(strict_types=1);

namespace Migrano;

use Psr\Container\ContainerInterface as Container;
use Migrano\MigrationRepository as Repository;
use Migrano\MigrationStorage as Storage;

class Application
{
    /**
     * @var MigrationStorage
     */
    private $storage;
    /**
     * @var Container
     */
    private $container;
    /**
     * @var MigrationRepository
     */
    private $repository;


    /**
     * Application constructor.
     * @param Container $container
     * @param MigrationRepository $repository
     * @param MigrationStorage $storage
     */
    public function __construct(
        Container $container,
        Repository $repository,
        Storage $storage
    )
    {
        $this->storage = $storage;
        $this->container = $container;
        $this->repository = $repository;
    }

    public function migrate()
    {
        foreach ($this->storage->listMigrationFiles() as $migrationFile) {
            if(!$this->repository->isMigrated($migrationFile)) {
                $migration = $this->getMigration($migrationFile);
                $migration->migrate();
                $this->repository->store($migrationFile);
            }
        }
    }

    public function rollback()
    {
        foreach ($this->storage->listMigrationFiles(Storage::ORDER_DESC) as $migrationFile) {
            if($this->repository->isMigrated($migrationFile)) {
                $migration = $this->getMigration($migrationFile);
                $migration->rollback();
                $this->repository->delete($migrationFile);
                break;
            }
        }
    }

    public function rewind()
    {
        foreach ($this->storage->listMigrationFiles(Storage::ORDER_DESC) as $migrationFile) {
            if($this->repository->isMigrated($migrationFile)) {
                $migration = $this->getMigration($migrationFile);
                $migration->rollback();
                $this->repository->delete($migrationFile);
            }
        }
    }

    public function reset()
    {
        $this->rewind();
        $this->migrate();
    }

    /**
     * @param $migrationFile
     * @return Migration
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getMigration($migrationFile): Migration
    {
        if (!class_exists($migrationFile->getClassName())) {
            require_once $migrationFile->getFilename();
        }
        return $this->container->get($migrationFile->getClassName());
    }

}

