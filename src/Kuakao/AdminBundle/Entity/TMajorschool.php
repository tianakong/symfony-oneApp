<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TMajorschool
 *
 * @ORM\Table(name="t_majorschool", uniqueConstraints={@ORM\UniqueConstraint(name="schoolName", columns={"schoolName", "majorName"})}, indexes={@ORM\Index(name="majorId", columns={"majorId"}), @ORM\Index(name="schoolId", columns={"schoolId"})})
 * @ORM\Entity
 */
class TMajorschool
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
     * @ORM\Column(name="schoolId", type="integer", nullable=true)
     */
    private $schoolid;

    /**
     * @var string
     *
     * @ORM\Column(name="schoolName", type="string", length=36, nullable=true)
     */
    private $schoolname;

    /**
     * @var integer
     *
     * @ORM\Column(name="majorId", type="integer", nullable=true)
     */
    private $majorid;

    /**
     * @var string
     *
     * @ORM\Column(name="majorName", type="string", length=36, nullable=true)
     */
    private $majorname;

    /**
     * @var string
     *
     * @ORM\Column(name="zhengzhi", type="string", length=500, nullable=false)
     */
    private $zhengzhi;

    /**
     * @var string
     *
     * @ORM\Column(name="waiyu", type="string", length=500, nullable=false)
     */
    private $waiyu;

    /**
     * @var string
     *
     * @ORM\Column(name="ywk1", type="string", length=500, nullable=false)
     */
    private $ywk1;

    /**
     * @var string
     *
     * @ORM\Column(name="ywk2", type="string", length=1000, nullable=false)
     */
    private $ywk2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="english1", type="boolean", nullable=true)
     */
    private $english1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="english2", type="boolean", nullable=true)
     */
    private $english2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="math0", type="boolean", nullable=true)
     */
    private $math0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="math1", type="boolean", nullable=true)
     */
    private $math1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="math2", type="boolean", nullable=true)
     */
    private $math2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="math3", type="boolean", nullable=true)
     */
    private $math3;



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
     * Set schoolid
     *
     * @param integer $schoolid
     * @return TMajorschool
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

    /**
     * Set schoolname
     *
     * @param string $schoolname
     * @return TMajorschool
     */
    public function setSchoolname($schoolname)
    {
        $this->schoolname = $schoolname;

        return $this;
    }

    /**
     * Get schoolname
     *
     * @return string 
     */
    public function getSchoolname()
    {
        return $this->schoolname;
    }

    /**
     * Set majorid
     *
     * @param integer $majorid
     * @return TMajorschool
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
     * Set majorname
     *
     * @param string $majorname
     * @return TMajorschool
     */
    public function setMajorname($majorname)
    {
        $this->majorname = $majorname;

        return $this;
    }

    /**
     * Get majorname
     *
     * @return string 
     */
    public function getMajorname()
    {
        return $this->majorname;
    }

    /**
     * Set zhengzhi
     *
     * @param string $zhengzhi
     * @return TMajorschool
     */
    public function setZhengzhi($zhengzhi)
    {
        $this->zhengzhi = $zhengzhi;

        return $this;
    }

    /**
     * Get zhengzhi
     *
     * @return string 
     */
    public function getZhengzhi()
    {
        return $this->zhengzhi;
    }

    /**
     * Set waiyu
     *
     * @param string $waiyu
     * @return TMajorschool
     */
    public function setWaiyu($waiyu)
    {
        $this->waiyu = $waiyu;

        return $this;
    }

    /**
     * Get waiyu
     *
     * @return string 
     */
    public function getWaiyu()
    {
        return $this->waiyu;
    }

    /**
     * Set ywk1
     *
     * @param string $ywk1
     * @return TMajorschool
     */
    public function setYwk1($ywk1)
    {
        $this->ywk1 = $ywk1;

        return $this;
    }

    /**
     * Get ywk1
     *
     * @return string 
     */
    public function getYwk1()
    {
        return $this->ywk1;
    }

    /**
     * Set ywk2
     *
     * @param string $ywk2
     * @return TMajorschool
     */
    public function setYwk2($ywk2)
    {
        $this->ywk2 = $ywk2;

        return $this;
    }

    /**
     * Get ywk2
     *
     * @return string 
     */
    public function getYwk2()
    {
        return $this->ywk2;
    }

    /**
     * Set english1
     *
     * @param boolean $english1
     * @return TMajorschool
     */
    public function setEnglish1($english1)
    {
        $this->english1 = $english1;

        return $this;
    }

    /**
     * Get english1
     *
     * @return boolean 
     */
    public function getEnglish1()
    {
        return $this->english1;
    }

    /**
     * Set english2
     *
     * @param boolean $english2
     * @return TMajorschool
     */
    public function setEnglish2($english2)
    {
        $this->english2 = $english2;

        return $this;
    }

    /**
     * Get english2
     *
     * @return boolean 
     */
    public function getEnglish2()
    {
        return $this->english2;
    }

    /**
     * Set math0
     *
     * @param boolean $math0
     * @return TMajorschool
     */
    public function setMath0($math0)
    {
        $this->math0 = $math0;

        return $this;
    }

    /**
     * Get math0
     *
     * @return boolean 
     */
    public function getMath0()
    {
        return $this->math0;
    }

    /**
     * Set math1
     *
     * @param boolean $math1
     * @return TMajorschool
     */
    public function setMath1($math1)
    {
        $this->math1 = $math1;

        return $this;
    }

    /**
     * Get math1
     *
     * @return boolean 
     */
    public function getMath1()
    {
        return $this->math1;
    }

    /**
     * Set math2
     *
     * @param boolean $math2
     * @return TMajorschool
     */
    public function setMath2($math2)
    {
        $this->math2 = $math2;

        return $this;
    }

    /**
     * Get math2
     *
     * @return boolean 
     */
    public function getMath2()
    {
        return $this->math2;
    }

    /**
     * Set math3
     *
     * @param boolean $math3
     * @return TMajorschool
     */
    public function setMath3($math3)
    {
        $this->math3 = $math3;

        return $this;
    }

    /**
     * Get math3
     *
     * @return boolean 
     */
    public function getMath3()
    {
        return $this->math3;
    }
}
