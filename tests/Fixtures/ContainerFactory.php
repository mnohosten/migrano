<?php

namespace Migrano\Tests\Fixtures;

use League\Container\Container;
use League\Container\ReflectionContainer;
use Psr\Container\ContainerInterface;

class ContainerFactory
{

    public function create()
    {
        $container = new Container();
        $container->share(ContainerInterface::class, $container);
        $container->delegate(new ReflectionContainer());
        return $container;
    }
    
}

