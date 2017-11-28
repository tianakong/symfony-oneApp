<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TYjxk
 *
 * @ORM\Table(name="t_yjxk")
 * @ORM\Entity
 */
class TYjxk
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
     * @var string
     *
     * @ORM\Column(name="yjname", type="string", length=36, nullable=true)
     */
    private $yjname;

    /**
     * @var string
     *
     * @ORM\Column(name="yjnum", type="string", length=36, nullable=true)
     */
    private $yjnum;

    /**
     * @var string
     *
     * @ORM\Column(name="mlname", type="string", length=36, nullable=true)
     */
    private $mlname;

    /**
     * @var integer
     *
     * @ORM\Column(name="addtime", type="integer", nullable=true)
     */
    private $addtime;

    /**
     * @var string
     *
     * @ORM\Column(name="adminuser", type="string", length=36, nullable=true)
     */
    private $adminuser;

    /**
     * @var string
     *
     * @ORM\Column(name="ndxs", type="string", length=36, nullable=true)
     */
    private $ndxs;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type;



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
     * Set yjname
     *
     * @param string $yjname
     * @return TYjxk
     */
    public function setYjname($yjname)
    {
        $this->yjname = $yjname;

        return $this;
    }

    /**
     * Get yjname
     *
     * @return string 
     */
    public function getYjname()
    {
        return $this->yjname;
    }

    /**
     * Set yjnum
     *
     * @param string $yjnum
     * @return TYjxk
     */
    public function setYjnum($yjnum)
    {
        $this->yjnum = $yjnum;

        return $this;
    }

    /**
     * Get yjnum
     *
     * @return string 
     */
    public function getYjnum()
    {
        return $this->yjnum;
    }

    /**
     * Set mlname
     *
     * @param string $mlname
     * @return TYjxk
     */
    public function setMlname($mlname)
    {
        $this->mlname = $mlname;

        return $this;
    }

    /**
     * Get mlname
     *
     * @return string 
     */
    public function getMlname()
    {
        return $this->mlname;
    }

    /**
     * Set addtime
     *
     * @param integer $addtime
     * @return TYjxk
     */
    public function setAddtime($addtime)
    {
        $this->addtime = $addtime;

        return $this;
    }

    /**
     * Get addtime
     *
     * @return integer 
     */
    public function getAddtime()
    {
        return $this->addtime;
    }

    /**
     * Set adminuser
     *
     * @param string $adminuser
     * @return TYjxk
     */
    public function setAdminuser($adminuser)
    {
        $this->adminuser = $adminuser;

        return $this;
    }

    /**
     * Get adminuser
     *
     * @return string 
     */
    public function getAdminuser()
    {
        return $this->adminuser;
    }

    /**
     * Set ndxs
     *
     * @param string $ndxs
     * @return TYjxk
     */
    public function setNdxs($ndxs)
    {
        $this->ndxs = $ndxs;

        return $this;
    }

    /**
     * Get ndxs
     *
     * @return string 
     */
    public function getNdxs()
    {
        return $this->ndxs;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return TYjxk
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }
}
