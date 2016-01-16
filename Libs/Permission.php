<?php

namespace Libs;

abstract class Permission
{

    abstract static function getName();

    static function getAll()
    {
        $permissions = array();
        $dir = BASE_DIR . '/Config/Permissions';
        foreach (scandir($dir) as $item)
        {
            if ($item == '.' || $item == '..')
            {
                continue;
            }
            $class = "\\Config\\Permissions\\" . str_replace('.php', '', $item);
            $groupName = $class::getName();
            $permissions[$groupName] = array();

            $oClass = new \ReflectionClass($class);
            foreach ($oClass->getConstants() as $k => $v)
            {
                $permissions[$groupName][$k] = $v;
            }
        }

        return $permissions;
    }

}
