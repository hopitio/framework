<?php

namespace Apps\Cores\Models;

class Permission
{

    function getAll()
    {
        $ret = array();
        $pemDir = BASE_DIR . '/Config/Permissions';
        foreach (scandir($pemDir) as $item)
        {
            if ($item == '.' || $item == '..')
            {
                continue;
            }

            $file = $pemDir . '/' . $item;
            if (!is_file($file))
            {
                continue;
            }

            $dom = simplexml_load_file($file);
            if (!$dom)
            {
                continue;
            }

            $app = array('name' => strval($dom->attributes()->name), 'groups' => array());
            foreach ($dom->children() as $group)
            {
                $pems = array();
                foreach ($group->children() as $pem)
                {
                    $pems[] = array(
                        'id'   => strval($pem->attributes()->id),
                        'name' => strval($pem->attributes()->name)
                    );
                }
                $app['groups'] = array(
                    'name'        => strval($group->attributes()->name),
                    'permissions' => $pems
                );
            }

            $ret[] = $app;
        }
        return $ret;
    }

}
