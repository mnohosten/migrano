<?php

namespace Migrano\Tests\Fixtures;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerFactoryTest extends TestCase
{


    public function testCreate()
    {
        $this->assertInstanceOf(
            ContainerInterface::class,
            (new ContainerFactory())->create()
        );
    }

}
