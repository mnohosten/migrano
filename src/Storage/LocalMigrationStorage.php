<?php
declare(strict_types=1);

namespace Migrano\Storage;

use DirectoryIterator;
use InvalidArgumentException;
use LogicException;
use Migrano\Migration;
use Migrano\MigrationFile;
use Migrano\MigrationStorage;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use SplFileInfo;

class LocalMigrationStorage implements MigrationStorage
{
    /**
     * @var string
     */
    private $path;


    /**
     * FlatMigrationStorage constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->setPath($path);
    }

    public function listMigrationFiles($orderedBy = self::ORDER_ASC)
    {
        $migrationFiles = [];
        foreach (new DirectoryIterator($this->path) as $fileInfo) {
            if($this->isInvalidMigrationFile($fileInfo)) continue;
            $migrationFiles[$fileInfo->getFilename()] = new MigrationFile(
                $fileInfo->getRealPath(),
                $this->getClassName($fileInfo)
            );
        }
        ($orderedBy === self::ORDER_ASC)
            ? sort($migrationFiles)
            : rsort($migrationFiles);
        return $migrationFiles;
    }

    /**
     * @param string $path
     */
    private function setPath(string $path): void
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException(
                "Path {$path} is not valid directory"
            );
        }
        $this->path = $path;
    }

    /**
     * @param SplFileInfo $fileInfo
     * @return mixed
     */
    private function getClassName(SplFileInfo $fileInfo)
    {
        $ast = $this->getParser()->parse(file_get_contents($fileInfo->getRealPath()));
        $classes = [];
        $uses = $this->getClassUsesFromAst($ast);
        foreach ($ast as $item) {
            if ($item instanceof Class_) {
                foreach ($item->implements as $implement) {
                    $implementedClass = trim(
                        isset($uses[$implement->toString()])
                            ? $uses[$implement->toString()]
                            : $implement->toString(),
                        '\\'
                    );
                    if ($implementedClass == Migration::class) {
                        $classes[] = $item->name->toString();
                        break;
                    }
                }
            }
        }
        if (count($classes) !== 1) {
            throw new LogicException(
                "In migration file `{$fileInfo->getRealPath()}` can be only one migration."
            );
        }
        $className = reset($classes);
        return $className;
    }

    /**
     * @return Parser
     */
    private function getParser(): Parser
    {
        static $parser = null;
        if(!isset($parser)) {
            $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        }
        return $parser;
    }

    /**
     * @param $ast
     * @return array
     */
    private function getClassUsesFromAst($ast): array
    {
        $uses = [];
        foreach ($ast as $item) {
            if ($item instanceof Use_) {
                foreach ($item->uses as $use) {
                    $uses[$use->getAlias()->toString()] = $use->name->toString();
                }
            }
        }
        return $uses;
    }

    /**
     * @param $fileInfo
     * @return bool
     */
    private function isInvalidMigrationFile($fileInfo): bool
    {
        return $fileInfo->isDot() || $fileInfo->getExtension() !== 'php';
    }

}

