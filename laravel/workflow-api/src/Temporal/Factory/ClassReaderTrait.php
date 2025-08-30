<?php

declare(strict_types=1);

namespace Sample\Temporal\Factory;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

use function str_replace;
use function strlen;
use function substr;

trait ClassReaderTrait
{
    /**
     * Read PHP classes from a directory
     *
     * @param string $directory
     * @param string $namespace
     *
     * @return array<ReflectionClass>
     */
    protected function readClasses(string $directory, string $namespace): array
    {
        $directory = rtrim($directory, '/\\');
        $namespace = rtrim($namespace, '\\');
        $dirPathLength = strlen($directory);
        $classes = [];
        $dirIterator = new RecursiveDirectoryIterator($directory);
        $fileIterator = new RecursiveIteratorIterator($dirIterator);
        // Iterate on dir content
        foreach($fileIterator as $file)
        {
            // Skip everything except PHP files
            if(!$file->isFile() || $file->getExtension() !== 'php')
            {
                continue;
            }

            $path = str_replace('/', '\\', $file->getPath());
            $className = $namespace . substr($path, $dirPathLength) .
                '\\' . $file->getBasename('.php');
            $class = new ReflectionClass($className);
            if(!$class->isInterface())
            {
                $classes[] = $class;
            }
        }
        return $classes;
    }
}
