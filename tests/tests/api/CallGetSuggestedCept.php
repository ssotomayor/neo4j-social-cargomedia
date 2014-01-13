<?php
$I = new ApiGuy($scenario);
$I->wantTo('perform actions and see result');
$I->haveHttpHeader('Content-Type','application/json');
$I->sendGET('http://localhost/cargomedia/api/index.php/get_suggested_friends/20', array());
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
