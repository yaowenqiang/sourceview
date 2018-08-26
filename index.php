<?php
    require 'vendor/autoload.php';
    use Symfony\Component\Finder\Finder;
    use Elasticsearch\ClientBuilder;

    $finder = new Finder();
    $finder->files()->in(__DIR__);
    //$finder->depth('== 0');
    $client = ClientBuilder::create()->build();

    $finfo = new finfo(FILEINFO_MIME);
    foreach ($finder as $file) {
        $doc = [];
        $doc['realpath'] = $file->getRealPath(); 
        $doc['relativepath'] = $file->getRelativePath(); 
        $doc['relativepathname'] = $file->getRelativePathname();
        $doc['ctime'] = $file->getCTime(); 
        $doc['atime'] = $file->getATime(); 
        $doc['md5'] = md5($doc['realpath']); 
        $doc['filename'] = $file->getFilename(); 
        $doc['extension'] = $file->getExtension(); 
        $doc['size'] = $file->getSize(); 
        $fileinfo = $finfo->file($doc['realpath']);
        $doc['istext'] = substr($fileinfo, 0, 4) == 'text';
        if($doc['istext']) {
            $doc['content'] = $file->getContents(); 
            $doc['lines'] = getLineNumber($doc['realpath']);
        }
        //var_dump($doc);
        
        $params = [
            'index'=>'my_index',
            'type'=>'my_type',
            'id'=> $doc['realpath'],
            'body'=>$doc
        ];
        try {
            $response = $client->index($params);
            //var_dump($response);
            echo "succeed!<br>\n";
        } catch (Exception $e) {
            var_dump($doc['realpath']);
            // var_dump($);
            echo "error!<br>\n";
        }
        
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
