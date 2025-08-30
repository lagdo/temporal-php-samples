<?php

declare(strict_types=1);

namespace Sample\Temporal\Factory;

use FilesystemIterator;
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
        $dirPathLength = strlen($directory);
        $classes = [];
        $fileIterator = new FilesystemIterator($directory);
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
