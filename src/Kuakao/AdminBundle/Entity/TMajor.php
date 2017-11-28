<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TMajor
 *
 * @ORM\Table(name="t_major")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Kuakao\AdminBundle\Repository\TMajorRepository")
 */
class TMajor
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
     * @ORM\Column(name="name", type="string", length=36, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="proname", type="string", length=36, nullable=true)
     */
    private $proname;

    /**
     * @var string
     *
     * @ORM\Column(name="pronum", type="string", length=36, nullable=true)
     */
    private $pronum;

    /**
     * @var string
     *
     * @ORM\Column(name="ndxs", type="string", length=36, nullable=true)
     */
    private $ndxs;

    /**
     * @var string
     *
     * @ORM\Column(name="xkml", type="string", length=36, nullable=true)
     */
    private $xkml;

    /**
     * @var string
     *
     * @ORM\Column(name="yjxk", type="string", length=36, nullable=true)
     */
    private $yjxk;

    /**
     * @var string
     *
     * @ORM\Column(name="yjxknum", type="string", length=36, nullable=true)
     */
    private $yjxknum;

    /**
     * @var string
     *
     * @ORM\Column(name="mathlx", type="string", length=36, nullable=true)
     */
    private $mathlx;

    /**
     * @var string
     *
     * @ORM\Column(name="englishlx", type="string", length=36, nullable=true)
     */
    private $englishlx;

    /**
     * @var string
     *
     * @ORM\Column(name="zyjs", type="string", length=6000, nullable=true)
     */
    private $zyjs;

    /**
     * @var string
     *
     * @ORM\Column(name="jyfx", type="string", length=8000, nullable=true)
     */
    private $jyfx;

    /**
     * @var integer
     *
     * @ORM\Column(name="gzrs", type="integer", nullable=true)
     */
    private $gzrs;

    /**
     * @var string
     *
     * @ORM\Column(name="zylx", type="string", length=36, nullable=true)
     */
    private $zylx;

    /**
     * @var integer
     *
     * @ORM\Column(name="school_level", type="integer", nullable=true)
     */
    private $schoolLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="adminuser", type="string", length=36, nullable=true)
     */
    private $adminuser;

    /**
     * @var integer
     *
     * @ORM\Column(name="addtime", type="integer", nullable=true)
     */
    private $addtime;



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
     * Set name
     *
     * @param string $name
     * @return TMajor
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set proname
     *
     * @param string $proname
     * @return TMajor
     */
    public function setProname($proname)
    {
        $this->proname = $proname;

        return $this;
    }

    /**
     * Get proname
     *
     * @return string 
     */
    public function getProname()
    {
        return $this->proname;
    }

    /**
     * Set pronum
     *
     * @param string $pronum
     * @return TMajor
     */
    public function setPronum($pronum)
    {
        $this->pronum = $pronum;

        return $this;
    }

    /**
     * Get pronum
     *
     * @return string 
     */
    public function getPronum()
    {
        return $this->pronum;
    }

    /**
     * Set ndxs
     *
     * @param string $ndxs
     * @return TMajor
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
     * Set xkml
     *
     * @param string $xkml
     * @return TMajor
     */
    public function setXkml($xkml)
    {
        $this->xkml = $xkml;

        return $this;
    }

    /**
     * Get xkml
     *
     * @return string 
     */
    public function getXkml()
    {
        return $this->xkml;
    }

    /**
     * Set yjxk
     *
     * @param string $yjxk
     * @return TMajor
     */
    public function setYjxk($yjxk)
    {
        $this->yjxk = $yjxk;

        return $this;
    }

    /**
     * Get yjxk
     *
     * @return string 
     */
    public function getYjxk()
    {
        return $this->yjxk;
    }

    /**
     * Set yjxknum
     *
     * @param string $yjxknum
     * @return TMajor
     */
    public function setYjxknum($yjxknum)
    {
        $this->yjxknum = $yjxknum;

        return $this;
    }

    /**
     * Get yjxknum
     *
     * @return string 
     */
    public function getYjxknum()
    {
        return $this->yjxknum;
    }

    /**
     * Set mathlx
     *
     * @param string $mathlx
     * @return TMajor
     */
    public function setMathlx($mathlx)
    {
        $this->mathlx = $mathlx;

        return $this;
    }

    /**
     * Get mathlx
     *
     * @return string 
     */
    public function getMathlx()
    {
        return $this->mathlx;
    }

    /**
     * Set englishlx
     *
     * @param string $englishlx
     * @return TMajor
     */
    public function setEnglishlx($englishlx)
    {
        $this->englishlx = $englishlx;

        return $this;
    }

    /**
     * Get englishlx
     *
     * @return string 
     */
    public function getEnglishlx()
    {
        return $this->englishlx;
    }

    /**
     * Set zyjs
     *
     * @param string $zyjs
     * @return TMajor
     */
    public function setZyjs($zyjs)
    {
        $this->zyjs = $zyjs;

        return $this;
    }

    /**
     * Get zyjs
     *
     * @return string 
     */
    public function getZyjs()
    {
        return $this->zyjs;
    }

    /**
     * Set jyfx
     *
     * @param string $jyfx
     * @return TMajor
     */
    public function setJyfx($jyfx)
    {
        $this->jyfx = $jyfx;

        return $this;
    }

    /**
     * Get jyfx
     *
     * @return string 
     */
    public function getJyfx()
    {
        return $this->jyfx;
    }

    /**
     * Set gzrs
     *
     * @param integer $gzrs
     * @return TMajor
     */
    public function setGzrs($gzrs)
    {
        $this->gzrs = $gzrs;

        return $this;
    }

    /**
     * Get gzrs
     *
     * @return integer 
     */
    public function getGzrs()
    {
        return $this->gzrs;
    }

    /**
     * Set zylx
     *
     * @param string $zylx
     * @return TMajor
     */
    public function setZylx($zylx)
    {
        $this->zylx = $zylx;

        return $this;
    }

    /**
     * Get zylx
     *
     * @return string 
     */
    public function getZylx()
    {
        return $this->zylx;
    }

    /**
     * Set schoolLevel
     *
     * @param integer $schoolLevel
     * @return TMajor
     */
    public function setSchoolLevel($schoolLevel)
    {
        $this->schoolLevel = $schoolLevel;

        return $this;
    }

    /**
     * Get schoolLevel
     *
     * @return integer 
     */
    public function getSchoolLevel()
    {
        return $this->schoolLevel;
    }

    /**
     * Set adminuser
     *
     * @param string $adminuser
     * @return TMajor
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
     * Set addtime
     *
     * @param integer $addtime
     * @return TMajor
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
}
