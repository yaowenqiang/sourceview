<?php
    require 'vendor/autoload.php';
    use Symfony\Component\Finder\Finder;

    $finder = new Finder();
    $finder->files()->in(__DIR__);

    foreach ($finder as $file) {
        var_dump($file->getRealPath()); 
        var_dump($file->getRelativePath()); 
        var_dump($file->getRelativePathname()); 
        echo "\n";
    }

