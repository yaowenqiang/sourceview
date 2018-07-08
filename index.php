<?php
    require 'vendor/autoload.php';
    use Symfony\Component\Finder\Finder;

    $finder = new Finder();
    $finder->files()->in(__DIR__);
    $finder->depth('== 0');

    foreach ($finder as $file) {
        var_dump($file->getRealPath()); 
        var_dump($file->getRelativePath()); 
        var_dump($file->getRelativePathname()); 
        //var_dump($file->getContents()); 
        var_dump($file->getExtension()); 
        var_dump($file->getSize()); 
        echo "\n";
    }

