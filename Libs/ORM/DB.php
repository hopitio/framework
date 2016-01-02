<?php

namespace Libs;

class DB
{

    /** @var ADOConnection */
    protected $db;

    function __construct($server, $user, $pwd, $db)
    {
        require_once (BASE_DIR . '/Libs/Adodb5/adodb.inc.php');
        $this->db = NewADOConnection('mysql');
        $this->db->Connect($server, $user, $pwd, $db);
    }

}
