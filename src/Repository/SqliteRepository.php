<?php
declare(strict_types=1);

namespace Migrano\Repository;

use InvalidArgumentException;
use Migrano\MigrationFile;
use Migrano\MigrationRepository;
use PDO;
use RuntimeException;

class SqliteRepository implements MigrationRepository
{

    private const IN_MEMORY = ':memory:';

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * SqliteRepository constructor.
     * @param string $databasePath
     */
    public function __construct(string $databasePath)
    {
        $this->validate($databasePath);
        $this->setPdo($databasePath);
    }

    public function isMigrated(MigrationFile $file)
    {
        $stmt = $this->pdo->prepare(<<<SQL
select count(*) from migration_file where fileName = ?        
SQL
        );
        $stmt->execute([$file->getFilename()]);
        return $stmt->fetch(PDO::FETCH_COLUMN) == '1';
    }

    public function delete(MigrationFile $file)
    {
        $sql = <<<SQL
delete from migration_file where fileName = ?
SQL;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$file->getFilename()]);
    }

    public function store(MigrationFile $file)
    {
        $sql = <<<SQL
insert into migration_file (fileName, className)
values (?, ?)
SQL;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$file->getFilename(), $file->getClassName()]);
    }

    /**
     * @param string $databasePath
     */
    private function validate(string $databasePath): void
    {
        $this->validateExtension();
        $this->validateDatabasePath($databasePath);
    }

    /**
     * @throws RuntimeException
     */
    private function validateExtension(): void
    {
        if (!extension_loaded('pdo_sqlite')) {
            throw new RuntimeException(
                __CLASS__ . " requires that pdo_sqlite exention is loaded."
            );
        }
    }

    /**
     * @param string $databasePath
     * @throws InvalidArgumentException
     */
    private function validateDatabasePath(string $databasePath): void
    {
        if (!$this->isDatabasePathValid($databasePath)) {
            throw new InvalidArgumentException(
                "Database path: `{$databasePath}` doesn't exists."
            );
        }
    }

    private function isDatabasePathValid(string $databasePath): bool
    {
        return file_exists($databasePath) || $databasePath == self::IN_MEMORY;
    }

    /**
     * @param string $databasePath
     */
    private function setPdo(string $databasePath): void
    {
        $this->pdo = new PDO("sqlite:{$databasePath}");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec($this->getSchema());
    }

    /**
     * @return string
     */
    private function getSchema(): string
    {
        return <<<SCHEMA
CREATE TABLE IF NOT EXISTS migration_file (
  fileName TEXT NOT NULL PRIMARY KEY,
  className TEXT NOT NULL
);
SCHEMA;
    }

}

