<?php

namespace Apps\Cores\Controllers\Rest;

use Libs\Json;

class StorageCtrl extends \Libs\RestCtrl
{

    function get()
    {
        $id = $this->req->get('id');
        $entity = $this->storageMapper
                ->makeInstance()
                ->filterPk($id)
                ->getEntity();

        $this->resp->setBody(Json::encode($entity->val));
    }

    function update()
    {
        $id = $this->req->get('id');
        $val = $this->restInput();
        $this->storageMapper->update($id, $val);

        $this->resp->setBody(Json::encode(array(
                    'status' => true
        )));
    }

    function delete()
    {
        $id = $this->req->get('id');
    }

}
