<?php
use Everyman\Neo4j\Client,
    Everyman\Neo4j\Cypher\Query;

class DB
{
    protected $cache;
    protected $client;
    protected $errorRes;

    private function initialize()
    {
        $this->errorRes = array('message' => 'No results');
        $this->client = new Client();
        $this->cache = new Everyman\Neo4j\Cache\Variable();
        $this->client->getEntityCache()->setCache($this->cache);
    }


    /* Returns friends of given node
       @param nodeId
       returns array
    */
    public function get_friends($nodeId)
    {
        $this->initialize();
        if ($this->cache->get("friends")) {
            return $this->cache->get("friends");
        }
        $arrFriends = array();
        $queryString = "START n=node({nodeId}) " .
            "MATCH (n)<-[:FRIENDS]-(x)" .
            "RETURN x";
        $query = new Everyman\Neo4j\Cypher\Query($this->client, $queryString, array('nodeId' => (int)$nodeId - 1));
        try {
            $result = $query->getResultSet();
            foreach ($result as $row) {
                $arrFriends[] = $row[0]->getProperties();
            }
        } catch (Exception $e) {
            $this->errorRes = array('message' => 'Non existant id', 'error' => $e->getCode());
        }
        $this->cache->set("friends", $arrFriends);
        $result = (empty($arrFriends) ? $this->errorRes : $arrFriends);
        return $result;
    }

    /* Returns friends of friends of the given node
       @param nodeId
       returns array
    */
    public function get_fof($nodeId)
    {
        $this->initialize();
        $queryString = "START n=node({nodeId}) " .
            "MATCH (n)<-[:FRIENDS]-(x)<-[:FRIENDS]-(y)" .
            "WHERE (y) <> (n)" .
            "RETURN distinct y";
        $query = new Query($this->client, $queryString, array('nodeId' => (int)$nodeId - 1));
        try {
        $result = $query->getResultSet();
            foreach ($result as $row) {
                $arrFof[] = $row[0]->getProperties();
            }
        } catch (Exception $e) {
            $this->errorRes = array('message' => 'Non existant id','error' => $e->getCode());
        }
        $result = (empty($arrFof) ? $this->errorRes : $arrFof);
        return $result;
    }

    /* Returns people who know 2 or more of his friends
        @param nodeId
        returns array
        TODO: This method needs to be optimized
    */
    public function get_suggested_friends($nodeId)
    {
        $this->initialize();
        $arrSuggested = array();
        try {
        $friendsCompare = $this->get_friends($nodeId);
        $fof = $this->get_fof($nodeId);
            foreach ($fof as $k => $v) {
                $possibleSuggestedFriends = $this->get_friends($v["id"]);
                $matches = 0;
                foreach ($possibleSuggestedFriends as $key => $value) {
                    if (in_array($value, $friendsCompare)) {
                        $matches++;
                        if ($matches >= 2) {
                            $arrSuggested[] = $v;
                            $matches = 0;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->errorRes = array('message' => 'Non existant id','error' => $e->getCode());
        }

        $result = (empty($arrSuggested) ? $this->errorRes : $arrSuggested);
        return $result;
    }

    /* Returns all nodes in DB */
    public function get_all()
    {
        $this->initialize();
        $queryString = "MATCH (n) RETURN n LIMIT 100";
        $query = new Query($this->client, $queryString, array());
        $result = $query->getResultSet();

        foreach ($result as $row) {
            $arrAll[] = $row[0]->getProperties();
        }

        $result = (empty($arrAll) ? $this->errorRes : $arrAll);
        return $result;

    }
}