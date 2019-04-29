<?php

namespace Webbj74\JSDL\Loader\Tests;

use PHPUnit\Framework\TestCase;
use Webbj74\JSDL\Loader\AbstractLoader;

/**
 * @coversDefaultClass \Webbj74\JSDL\Loader\AbstractLoader
 */
class AbstractLoaderTest extends TestCase
{

    /**
     * @covers ::load
     */
    public function testLoad()
    {
        $loader = $this->getMockClass(AbstractLoader::class);
        $result = $loader->load();
    }

    /**
     * @covers ::addAlias
     */
    public function testAddAlias()
    {
        $loader = $this->getMockClass(AbstractLoader::class);
        $result = $loader->addAlias();
    }

    /**
     * @covers ::removeAlias
     * @depends testAddAlias
     */
    public function testRemoveAlias()
    {
        $loader = $this->getMockClass(AbstractLoader::class);
        $result = $loader->addAlias();
        $result = $loader->removeAlias();
    }
}
