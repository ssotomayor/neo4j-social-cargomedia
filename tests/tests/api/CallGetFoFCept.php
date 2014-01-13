<?php
$I = new ApiGuy($scenario);
$I->wantTo('Call Get Friends of Friends (FoF) and see Result');
$I->haveHttpHeader('Content-Type','application/json');
$I->sendGET('http://localhost/cargomedia/api/index.php/get_fof/3', array());
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
