neo4j-social-cargomedia
=======================

## About

This is a development repo with tiny API done for a test for a company.
Stack:

[Slim Framework](http://www.slimframework.com/)
[Neo4j](http://www.neo4j.org/)

Tests done with:

[Codeception](http://codeception.com/)


Package Manager:
[Composer](http://getcomposer.org/)

## Server config

It uses http://localhost for the app.
This is hardcoded in the tests here:

	CallGetFofCept.php
	CallGetFriendsCept.php
	CallGetSuggestedCept.php

And also in the webui at

    index.html

## Running it

Download and install [Neo4j](http://www.neo4j.org/) if you don't have it.
Run it

    $ ./neo4j start

Check that is running at http://localhost:7474

Once the database is running, put the app in a local server (Apache, nginx, etc..) and run api/initdata.php (with data.json being hardcoded there)

    $ php initdata.php

This will import the data into the Neo4j database.
If you want to check that it has been imported properly, check http://localhost:7474 and execute this query from the db UI.

    $ MATCH (n) RETURN n LIMIT 100

It should return the nodes.

![alt text](http://i.imgur.com/GB2NglG.png "Yipee, nodes...")

Enter the app webui through the browser, click on the names, and check the data.

You can also GET from:

        http://localhost/api/index.php/get_friends/:id
        http://localhost/api/index.php/get_fof/:id
        http://localhost/api/index.php/get_suggested_friends/:id

and see the JSON response

## Tests

Tests can be run by going to /tests and running:

    $ php codecept.phar run

It will execute 3 basic tests that will check if the response is 200 and a JSON.
They are located on /tests/tests/api.

Stress test could be run using everybody's favourite [JMeter](http://jmeter.apache.org/)

To generate more tests from codecept run (from /tests):

     $ php codecept.phar generate:cept api <name_of_your_test>
     $ php codecept.phar build


