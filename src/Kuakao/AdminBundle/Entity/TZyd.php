<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TZyd
 *
 * @ORM\Table(name="t_zyd")
 * @ORM\Entity
 */
class TZyd
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", nullable=false)
     */
    private $uid;

    /**
     * @var integer
     *
     * @ORM\Column(name="majorid", type="integer", nullable=false)
     */
    private $majorid;

    /**
     * @var integer
     *
     * @ORM\Column(name="schoolid", type="integer", nullable=false)
     */
    private $schoolid;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     * @return TZyd
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return integer 
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set majorid
     *
     * @param integer $majorid
     * @return TZyd
     */
    public function setMajorid($majorid)
    {
        $this->majorid = $majorid;

        return $this;
    }

    /**
     * Get majorid
     *
     * @return integer 
     */
    public function getMajorid()
    {
        return $this->majorid;
    }

    /**
     * Set schoolid
     *
     * @param integer $schoolid
     * @return TZyd
     */
    public function setSchoolid($schoolid)
    {
        $this->schoolid = $schoolid;

        return $this;
    }

    /**
     * Get schoolid
     *
     * @return integer 
     */
    public function getSchoolid()
    {
        return $this->schoolid;
    }
}
