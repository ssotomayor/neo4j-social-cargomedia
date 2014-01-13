<?php

require "vendor/autoload.php";
require "DB.php";

$app = new \Slim\Slim();
$app->response->headers->set('Content-Type', 'application/json');


$app->get('/', function () {
    echo "Root, home";
});

$app->get('/get_friends/:id', function ($id) {
    $db = new DB();
    $friends = $db->get_friends($id);
    echo json_encode($friends);
});


$app->get('/get_fof/:id', function ($id) {
    $db = new DB();
    $fof = $db->get_fof($id);
    echo json_encode($fof);
});

$app->get('/get_suggested_friends/:id', function ($id) {
    $db = new DB();
    $fof = $db->get_suggested_friends($id);
    echo json_encode($fof);
});

$app->get('/get_all/', function () {
    $db = new DB();
    $fof = $db->get_all();
    echo json_encode($fof);
});

$app->run();