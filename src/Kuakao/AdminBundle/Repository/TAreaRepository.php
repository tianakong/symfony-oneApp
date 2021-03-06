<?php

namespace Kuakao\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Kuakao\AdminBundle\Entity\TSchool;

/**
 * TLogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TAreaRepository extends EntityRepository
{
    public function getAll($where = [], $order=['listorder'=>'ASC'])
    {
        $arr = array();

        $list = $this->findBy($where, $order);

        foreach ($list as $key => $val)
        {
            if (is_object($val))
            {
                $id     = $val->getId();
                $name   = $val->getName();
                $arr[$name] = $id;
            }
        }

        return $arr;
    }

    /**
     * 根据条件查询数据,并把数据的id作为键值返回
     * @param array $where
     * @param array $order
     * @return array
     */
    public function getData($where = [], $order = ['listorder'=>'asc'])
    {
        $data = $this->findBy($where, $order);
        $newData = array();
        foreach($data as $val) {
            $newData[$val->getId()] = $val;
        }
        return $newData;
    }

}
