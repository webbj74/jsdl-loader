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
     * Prefixes a file with the full path to the fixtures directory.
     *
     * @return string
     *   The full path to the fixture.
     */
    private function getFixturePath($filename)
    {
        return __DIR__ . '/fixtures/' . $filename;
    }

    /**
     * Verify that ::load throws InvalidArgumentException if file does not exist.
     */
    public function testLoadThrowsExceptionIfFileDoesNotExist()
    {
        $loader = $this->getMockForAbstractClass(AbstractLoader::class);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/No such file or directory/');
        $loader->load($this->getFixturePath('nonexistent.json'));
    }

    /**
     * Verify that ::load throws InvalidArgumentException if file extension is not known.
     */
    public function testLoadThrowsExceptionIfFileHasUnknownExtension()
    {
        $loader = $this->getMockForAbstractClass(AbstractLoader::class);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/Unknown file extension/');
        $loader->load($this->getFixturePath('bad_extension.notjson'));
    }

    /**
     *  Verify that ::load handles file argument.
     */
    public function testLoadFromFile()
    {
        $loader = $this->getMockForAbstractClass(AbstractLoader::class);
        $loader->expects($this->once())
            ->method('build')
            ->with([
                'name' => 'testLoadFromFile',
                'apiVersion' => '0.1',
                'description' => 'testLoadFromFile',
            ]);
        $this->confirmValidLoaderFile($loader, $this->getFixturePath('AbstractLoaderTest_testLoadFromFile.json'));
    }

    /**
     *  Verify that ::load handles array argument with config.
     */
    public function testLoadFromConfig()
    {
        $config = [
            'name' => 'testLoadFromConfig',
            'apiVersion' => '0.1',
            'description' => 'testLoadFromConfig',
        ];
        $loader = $this->getMockForAbstractClass(AbstractLoader::class);
        $loader->expects($this->once())
            ->method('build')
            ->with($config);
        $this->assertNull($loader->load($config));
    }

    /**
     * Verify that ::load throws InvalidArgumentException if given an argument it can't deal with.
     *
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
     * Verify that an alias may be added.
     *
     * To do this we add an alias and use load to confirm that it was added.
     */
    public function testAddAlias()
    {
        $loader = $this->getMockForAbstractClass(AbstractLoader::class);
        $loader->expects($this->once())
            ->method('build')
            ->with([
                'name' => 'testAddAlias',
                'apiVersion' => '0.1',
                'description' => 'testAddAlias',
            ]);
        $loader->addAlias('testAddAlias', $this->getFixturePath('AbstractLoaderTest_testAddAlias.json'));
        $this->confirmValidLoaderFile($loader, 'testAddAlias');
    }

    /**
     * Verify that an alias may be removed.
     *
     * To do this we add an alias, confirm that it was added, then expect an exception if we remove the alias.
     */
    public function testRemoveAlias()
    {
        $loader = $this->getMockForAbstractClass(AbstractLoader::class);
        $loader->expects($this->once())
            ->method('build')
            ->with([
                'name' => 'testRemoveAlias',
                'apiVersion' => '0.1',
                'description' => 'testRemoveAlias',
            ]);
        $loader->addAlias('testRemoveAlias', $this->getFixturePath('AbstractLoaderTest_testRemoveAlias.json'));
        $this->confirmValidLoaderFile($loader, 'testRemoveAlias');
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
