<?php

namespace Migrano;

class MigrationFile
{

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $className;

    /**
     * MigrationFile constructor.
     * @param string $filename
     * @param string $className
     */
    public function __construct(
        string $filename,
        string $className
    )
    {
        $this->filename = $filename;
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

}
