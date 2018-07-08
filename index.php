<?php
    require 'vendor/autoload.php';
    use Symfony\Component\Finder\Finder;
    use Elasticsearch\ClientBuilder;

    $finder = new Finder();
    $finder->files()->in(__DIR__);
    $finder->depth('== 0');
    $client = ClientBuilder::create()->build();


    foreach ($finder as $file) {
        $doc = [];
        $doc['realpath'] = $file->getRealPath(); 
        $doc['relativepath'] = $file->getRelativePath(); 
        $doc['relativepathname'] = $file->getRelativePathname();
        $doc['countent'] = $file->getContents(); 
        $doc['extension'] = $file->getExtension(); 
        $doc['size'] = $file->getSize(); 
        $params = [
            'index'=>'my_index',
            'type'=>'my_type',
            'id'=> $doc['realpath'],
            'body'=>$doc
        ];
    $response = $client->index($params);
    var_dump($response);

    }

