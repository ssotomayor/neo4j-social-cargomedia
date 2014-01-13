<?php
require "vendor/autoload.php";
use Everyman\Neo4j\Client,
    Everyman\Neo4j\Index\NodeIndex;

$client = new Client();
$usersIndex = new NodeIndex($client, 'users');

// Initialize the data
$data = json_decode(file_get_contents('data.json'));

// Create Nodes
foreach ($data as $k => $v){
    $node = $client->makeNode();
    foreach($v as $key => $val){
        if(!is_array($val)){
          $node = $node->setProperty((string)$key, $val);
        } else {
            $arrFriends = $val;
        }
    }
      $node = $node->save();
      $arrIds[$node->getId()] = $arrFriends;
}


//Create relationships
foreach ($arrIds as $k => $v){
    foreach ($v as $key => $friend){
        $friendship = $client->makeRelationship();
        $friendship ->setStartNode($client->getNode($k))
            ->setEndNode($client->getNode($friend-1))
            ->setType('FRIENDS')
            ->save();
    }
}