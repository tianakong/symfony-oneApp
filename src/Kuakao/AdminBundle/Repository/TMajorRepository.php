<?php

namespace Kuakao\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TMajorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TMajorRepository extends EntityRepository
{
    public function getAll($where = [], $order=['listorder'=>'asc'])
    {
        return $this->findBy($where, $order);
    }

    public function getLx($lx='')
    {
        $class = array(
            'xkml' => array('哲学','经济学','法学','教育学','文学','历史学','理学','工学','农学','医学','军事学','管理学','艺术学'),
            'mathlx' => array('不考数学','数学一','数学二','数学三'),
            'englishlx' => array('英语一','英语二'),
            'zylx' => array('专业硕士','学术硕士')
        );
        if(array_key_exists($lx,$class))
        {
            return $class[$lx];
        }
        else
        {
            return $class;
        }
    }
}
