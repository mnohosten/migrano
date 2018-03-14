<?php

namespace Migrano;

interface Migration
{

    /**
     * Execute migration forward migration
     */
    public function migrate(): void;


    /**
     * Execute rollback migration
     */
    public function rollback(): void;

}