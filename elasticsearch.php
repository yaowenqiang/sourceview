<?php
    require 'vendor/autoload.php';

    use Elasticsearch\ClientBuilder;
    $client = ClientBuilder::create()->build();
    $params = [
        'index'=>'my_index',
        'type'=>'my_type',
        'id'=>'my_id',
        'body'=>['textField'=>"abc"]
    ];
    $response = $client->index($params);
    var_dump($response);

