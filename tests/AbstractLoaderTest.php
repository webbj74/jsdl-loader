<?php

namespace Webbj74\JSDL\Loader\Tests;

use PHPUnit\Framework\TestCase;
use Webbj74\JSDL\Loader\AbstractLoader;

/**
 * @coversDefaultClass \Webbj74\JSDL\Loader\AbstractLoader
 */
class AbstractLoaderTest extends TestCase
{
    private function getFixturePath($filename)
    {
        return __DIR__ . '/fixtures/' . $filename;
    }

    /**
     * @covers ::load
     */
    public function testLoadThrowsExceptionIfFileDoesNotExist()
    {
        $loader = $this->getMockForAbstractClass(AbstractLoader::class);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/No such file or directory/');
        $loader->load($this->getFixturePath('nonexistent.json'));
    }

    /**
     * @covers ::load
     */
    public function testLoadThrowsExceptionIfFileHasUnknownExtension()
    {
        $loader = $this->getMockForAbstractClass(AbstractLoader::class);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/Unknown file extension/');
        $loader->load($this->getFixturePath('bad_extension.notjson'));
    }

    /**
     * @covers ::load
     */
    public function testLoadFromFile()
    {
        $loader = $this->getMockForAbstractClass(AbstractLoader::class);
        $this->confirmValidLoaderFile($loader, $this->getFixturePath('AbstractLoaderTest_testLoadFromFile.json'));
    }

    /**
     * @covers ::load
     * @dataProvider badConfigProvider
     */
    public function testLoadThrowsExceptionForBadConfigArgument($config)
    {
        $loader = $this->getMockForAbstractClass(AbstractLoader::class);
        $this->expectException(\InvalidArgumentException::class);
        $loader->load($config);
    }

    /**
     * Data provider for invalid config arguments.
     *
     * @return array
     *   Series of bag arguments to provide.
     */
    public function badConfigProvider()
    {
        return [
            'integer'  => [0],
            'object'  => [(object)['foo' => 'bar']],
        ];
    }

    /**
     * @covers ::addAlias
     */
    public function testAddAlias()
    {
        $loader = $this->getMockForAbstractClass(AbstractLoader::class);
        $loader->addAlias('testAddAlias', $this->getFixturePath('AbstractLoaderTest_testAddAlias.json'));
        $this->confirmValidLoaderFile($loader, 'testAddAlias');
    }

    /**
     * @covers ::removeAlias
     * @depends testAddAlias
     */
    public function testRemoveAlias()
    {
        $loader = $this->getMockForAbstractClass(AbstractLoader::class);
        $loader->addAlias('testRemoveAlias', $this->getFixturePath('AbstractLoaderTest_testRemoveAlias.json'));
        $loader->removeAlias('testRemoveAlias');
        $this->confirmInvalidLoaderFile($loader, 'testRemoveAlias');
    }

    /**
     * Confirm ::load will throw exception if invalid filename supplied.
     *
     * @param Webbj74\JSDL\Loader\AbstractLoader $loader
     *   The loader instance to test.
     * @param string $filename
     *   The filename to attempt to load.
     */
    private function confirmInvalidLoaderFile($loader, $filename)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/(No such file or directory|Unknown file extension)/');
        $loader->load($filename);
    }

    /**
     * Confirm ::load will not throw exception if valid filename supplied.
     *
     * @param Webbj74\JSDL\Loader\AbstractLoader $loader
     *   The loader instance to test.
     * @param string $filename
     *   The filename to attempt to load.
     */
    private function confirmValidLoaderFile($loader, $filename)
    {
        // Assumes the best if no exceptions are thrown.
        $this->assertNull($loader->load($filename));
    }
}
