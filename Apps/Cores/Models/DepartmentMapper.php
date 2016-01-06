<?php

namespace Apps\Cores\Models;

use Libs\SQL\Mapper;

class DepartmentMapper extends Mapper
{

    protected $loadUsers;
    protected $loadChildDeps;
    protected $loadAncestors;

    protected function userMapper()
    {
        return UserMapper::makeInstance();
    }

    function __construct()
    {
        parent::__construct();
        $this->orderBy('dep.pathSort');
    }

    public function makeEntity($rawData)
    {
        $entity = new DepartmentEntity($rawData);
        $entity->pk = (int) $entity->pk;

        if ($this->loadUsers)
        {
            $entity->users = $this->loadUsers($entity->pk);
        }
        if ($this->loadChildDeps)
        {
            $entity->deps = $this->loadChildDeps($entity->pk);
        }
        if ($this->loadAncestors)
        {
            $entity->ancestors = $this->loadAncestors($entity);
        }

        return $entity;
    }

    public function tableAlias()
    {
        return 'dep';
    }

    public function tableName()
    {
        return 'cores_department';
    }

    function filterPk($depPk)
    {
        $this->where('dep.pk=?', __FUNCTION__)->setParam($depPk, __FUNCTION__);
        return $this;
    }

    function filterParent($depPk)
    {
        $this->where('dep.depFk=?', __FUNCTION__)->setParam($depPk, __FUNCTION__);
        return $this;
    }

    /**
     * Tự động load user trực thuộc
     */
    function setLoadUsers($bool = true)
    {
        $this->loadUsers = $bool;
        return $this;
    }

    /** Tự động load đơn vị trực thuộc */
    function setLoadChildDeps($bool = true)
    {
        $this->loadChildDeps = $bool;
        return $this;
    }

    /** Tự động load đơn vị tổ tiên */
    function setLoadAncestors($bool = true)
    {
        $this->loadAncestors = $bool;
        return $this;
    }

    /** Tự động load tất cả object liên quan */
    function setLoad($loadAncestors = true, $loadChildDeps = true, $loadUsers = true)
    {
        $this->setLoadAncestors($loadAncestors)
                ->setLoadChildDeps($loadChildDeps)
                ->setLoadUsers($loadUsers);
        return $this;
    }

    /** @return EntitySet<UserEntity> */
    function loadUsers($depPk)
    {
        return $this->userMapper()
                        ->filterParent($depPk)
                        ->getAll();
    }

    /** @return EntitySet<DepartmentEntity> */
    function loadChildDeps($depPk)
    {
        return $this->makeInstance()
                        ->filterParent($depPk)
                        ->getAll();
    }

    /**
     * 
     * @param DepartmentEntity $dep
     * @return EntitySet<DepartmentEntity> 
     */
    function loadAncestors(DepartmentEntity $dep)
    {
        $pks = explode('/', trim($dep->path, '/'));
        //remove this dep
        array_pop($pks);

        if (count($pks))
        {
            $pks = implode(',', $pks);
            return $this->makeInstance()
                            ->where("dep.pk IN($pks)")
                            ->getAll();
        }
        else
        {
            return $this->makeEntitySet();
        }
    }

}
