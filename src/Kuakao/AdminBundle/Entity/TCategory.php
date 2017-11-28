<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TCategory
 *
 * @ORM\Table(name="t_category")
 * @ORM\Entity
 */
class TCategory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="catid", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $catid;

    /**
     * @var string
     *
     * @ORM\Column(name="catname", type="string", length=50, nullable=true)
     */
    private $catname;

    /**
     * @var string
     *
     * @ORM\Column(name="parentid", type="string", length=50, nullable=true)
     */
    private $parentid;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;



    /**
     * Get catid
     *
     * @return integer 
     */
    public function getCatid()
    {
        return $this->catid;
    }

    /**
     * Set catname
     *
     * @param string $catname
     * @return TCategory
     */
    public function setCatname($catname)
    {
        $this->catname = $catname;
    
        return $this;
    }

    /**
     * Get catname
     *
     * @return string 
     */
    public function getCatname()
    {
        return $this->catname;
    }

    /**
     * Set parentid
     *
     * @param string $parentid
     * @return TCategory
     */
    public function setParentid($parentid)
    {
        $this->parentid = $parentid;
    
        return $this;
    }

    /**
     * Get parentid
     *
     * @return string 
     */
    public function getParentid()
    {
        return $this->parentid;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return TCategory
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
