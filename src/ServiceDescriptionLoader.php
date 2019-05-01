<?php

/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webbj74\JSDL\Loader;

/**
 * Loader for service descriptions
 * This class is based on Michael Dowling's Guzzle\Service\Description\ServiceDescriptionLoader
 */
class ServiceDescriptionLoader extends AbstractLoader
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function build($config, array $options)
    {
        $operations = array();
        if (!empty($config['operations'])) {
            foreach ($config['operations'] as $name => $operation) {
                $name = $operation['name'] = isset($operation['name']) ? $operation['name'] : $name;
                // Extend other operations
                if (!empty($operation['extends'])) {
                    $this->resolveExtension($name, $operation, $operations);
                }
                $operation['parameters'] = isset($operation['parameters']) ? $operation['parameters'] : array();
                $operations[$name] = $operation;
            }
        }

        return array(
                'apiVersion'  => isset($config['apiVersion']) ? $config['apiVersion'] : null,
                'baseUrl'     => isset($config['baseUrl']) ? $config['baseUrl'] : null,
                'description' => isset($config['description']) ? $config['description'] : null,
                'operations'  => $operations,
                'models'      => isset($config['models']) ? $config['models'] : null
            ) + $config;
    }

    /**
     * Inherit operation values from another operation.
     *
     * @param string $name
     *   Name of the operation
     * @param array  $operation
     *   Operation value array
     * @param array  $operations
     *   Currently loaded operations
     *
     * @throws \RuntimeException when extending a non-existent operation
     */
    protected function resolveExtension($name, array &$operation, array &$operations)
    {
        $resolved = array();
        $original = empty($operation['parameters']) ? false: $operation['parameters'];
        $hasClass = !empty($operation['class']);
        foreach ((array) $operation['extends'] as $extendedCommand) {
            if (empty($operations[$extendedCommand])) {
                throw new \RuntimeException("{$name} extends missing operation {$extendedCommand}");
            }
            $toArray = $operations[$extendedCommand];
            $resolved = empty($resolved)
                ? $toArray['parameters']
                : array_merge($resolved, $toArray['parameters']);

            $operation = $operation + $toArray;
            if (!$hasClass && isset($toArray['class'])) {
                $operation['class'] = $toArray['class'];
            }
        }
        $operation['parameters'] = $original ? array_merge($resolved, $original) : $resolved;
    }
}
