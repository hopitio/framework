<?php

namespace Apps\Cores\Models;

use Libs\SQL\Entity;

class StorageEntity extends Entity
{

    public $pk;
    public $val;

    function __construct($rawData = null)
    {
        parent::__construct($rawData);
        $this->val = json_decode($this->val, true);
    }

}
