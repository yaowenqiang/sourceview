<?php
    require 'vendor/autoload.php';
    use Symfony\Component\Finder\Finder;
    use Elasticsearch\ClientBuilder;

    $finder = new Finder();
    $finder->files()->in(__DIR__);
    //$finder->depth('== 0');
    $client = ClientBuilder::create()->build();

    $finfo = new finfo(FILEINFO_MIME,'/usr/share/misc/magic');
    foreach ($finder as $file) {
        $doc = [];
        $doc['realpath'] = $file->getRealPath(); 
        $doc['relativepath'] = $file->getRelativePath(); 
        $doc['relativepathname'] = $file->getRelativePathname();
        $doc['ctime'] = $file->getCTime(); 
        $doc['atime'] = $file->getATime(); 
        $doc['md5'] = md5($doc['relativepathname']); 
        $doc['filename'] = $file->getFilename(); 
        $doc['content'] = $file->getContents(); 
        $doc['extension'] = $file->getExtension(); 
        $doc['size'] = $file->getSize(); 
        $fileinfo = $finfo->file($doc['relativepathname']);
        $doc['istext'] = substr($fileinfo, 0, 4) == 'text';
        if($doc['istext']) {
            $doc['lines'] = getLineNumber($doc['relativepathname']);
        }
        
        $params = [
            'index'=>'my_index',
            'type'=>'my_type',
            'id'=> $doc['realpath'],
            'body'=>$doc
        ];
    $response = $client->index($params);
    var_dump($response);
        
    }

    function getLineNumber($file='') {
        if ($file) {
            $linecount = 0;
            $handle = fopen($file, "r");
            while(!feof($handle)){
              $line = fgets($handle);
              $linecount++;
            }
            fclose($handle);
            return $linecount;
        } else {
            return 0;
        }
    }    
