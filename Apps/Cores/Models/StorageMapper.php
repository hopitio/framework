<?php

namespace Apps\Cores\Models;

use Libs\SQL\Mapper;

class StorageMapper extends Mapper
{

    public function makeEntity($rawData)
    {
        return new StorageEntity($rawData);
    }

    public function tableAlias()
    {
        return 'st';
    }

    public function tableName()
    {
        return 'cores_storage';
    }

    function update($id, $val)
    {
        $inserted = $this->db->GetOne("SELECT pk FROM cores_storage WHERE pk=?", array($id));
        $val = json_encode($val);
        if ($inserted)
        {
            $this->db->update('cores_storage', array('val' => $val), 'pk=?', array($id));
        }
        else
        {
            $this->db->insert('cores_storage', array('val' => $val));
        }
    }

    function delete($id)
    {
        $this->db->delete('cores_storage', 'pk=?', array($id));
    }

}
