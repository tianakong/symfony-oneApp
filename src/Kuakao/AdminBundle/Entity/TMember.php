<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kuakao\Common\Globals;

/**
 * TMember
 *
 * @ORM\Table(name="t_member")
 * @ORM\Entity
 */
class TMember
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
     * @ORM\Column(name="add_time", type="integer", nullable=false)
     */
    private $addTime;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=32, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=15, nullable=false)
     */
    private $mobile = '';

    /**
     * @var string
     *
     * @ORM\Column(name="major", type="string", length=40, nullable=false)
     */
    private $major = '';

    /**
     * @var string
     *
     * @ORM\Column(name="school", type="string", length=30, nullable=false)
     */
    private $school = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="year", type="string", nullable=true)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="target_major", type="string", length=50, nullable=false)
     */

    private $targetMajor ='';



    /**
     * @var string
     *
     * @ORM\Column(name="target_school", type="string", length=30, nullable=false)
     */
    private $targetSchool = '';

    /**
     * @var string
     *
     * @ORM\Column(name="score_46", type="string", length=10, nullable=false)
     */

    private $score46 ='';


    /**
     * @var string
     *
     * @ORM\Column(name="score_ba", type="string", length=10, nullable=false)
     */

    private $scoreBa ='';



    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = 1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=false)
     */
    private $type = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="last_login_time", type="integer", nullable=false)
     */
    private $lastLoginTime = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="last_login_ip", type="string", length=15, nullable=false)
     */
    private $lastLoginIp = '';


    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=10, nullable=false)
     */
    private $source = '';



    /**
     * @var string
     *
     * @ORM\Column(name="encrypt", type="string", length=10, nullable=false)
     */
    private $encrypt;

    /**
     * @var string
     *
     * @ORM\Column(name="is_delete", type="string", length=10, nullable=false)
     */
    private $isDelete = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=60, nullable=true)
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=10, nullable=true)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="sxcj", type="string", length=10, nullable=true)
     */
    private $sxcj = '';

    /**
     * @var string
     *
     * @ORM\Column(name="yycj", type="string", length=10, nullable=true)
     */
    private $yycj = '';

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
     * Set addTime
     *
     * @param integer $addTime
     * @return TMember
     */
    public function setAddTime($addTime)
    {
        $this->addTime = $addTime;

        return $this;
    }

    /**
     * Get addTime
     *
     * @return integer
     */
    public function getAddTime()
    {
        return $this->addTime;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return TMember
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
     * Set password
     *
     * @param string $password
     * @return TMember
     */
    public function setPassword($password)
    {
        if($password) {
            $passData = Globals::password($password);
            $this->password = $passData['password'];
            $this->setEncrypt($passData['encrypt']);
        }

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return TMember
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set major
     *
     * @param string $major
     * @return TMember
     */
    public function setMajor($major)
    {
        $this->major = $major;

        return $this;
    }

    /**
     * Get major
     *
     * @return string
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Set school
     *
     * @param string $school
     * @return TMember
     */
    public function setSchool($school)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school
     *
     * @return string
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * Set year
     *
     * @param string $year
     * @return TMember
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return \DateTime
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set targetMajor
     *
     * @param string $targetMajor
     * @return TMember
     */
    public function setTargetMajor($targetMajor)
    {
        $this->targetMajor = $targetMajor;

        return $this;
    }

    /**
     * Get targetMajor
     *
     * @return string
     */
    public function getTargetMajor()
    {
        return $this->targetMajor;
    }

    /**
     * Set targetSchool
     *
     * @param string $targetSchool
     * @return TMember
     */
    public function setTargetSchool($targetSchool)
    {
        $this->targetSchool = $targetSchool;

        return $this;
    }

    /**
     * Get targetSchool
     *
     * @return string
     */
    public function getTargetSchool()
    {
        return $this->targetSchool;
    }

    /**
     * Set score46
     *
     * @param string $score46
     * @return TMember
     */
    public function setScore46($score46)
    {
        $this->score46 = $score46;

        return $this;
    }

    /**
     * Get score46
     *
     * @return string
     */
    public function getScore46()
    {
        return $this->score46;
    }

    /**
     * Set scoreBa
     *
     * @param string $scoreBa
     * @return TMember
     */
    public function setScoreBa($scoreBa)
    {
        $this->scoreBa = $scoreBa;

        return $this;
    }

    /**
     * Get scoreBa
     *
     * @return string
     */
    public function getScoreBa()
    {
        return $this->scoreBa;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return TMember
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

    /**
     * Set type
     *
     * @param boolean $type
     * @return TMember
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return boolean
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set lastLoginTime
     *
     * @param integer $lastLoginTime
     * @return TMember
     */
    public function setLastLoginTime($lastLoginTime)
    {
        $this->lastLoginTime = $lastLoginTime;

        return $this;
    }

    /**
     * Get lastLoginTime
     *
     * @return integer
     */
    public function getLastLoginTime()
    {
        return $this->lastLoginTime;
    }

    /**
     * Set lastLoginIp
     *
     * @param string $lastLoginIp
     * @return TMember
     */
    public function setLastLoginIp($lastLoginIp)
    {
        $this->lastLoginIp = $lastLoginIp;

        return $this;
    }

    /**
     * Get lastLoginIp
     *
     * @return string
     */
    public function getLastLoginIp()
    {
        return $this->lastLoginIp;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return TMember
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set encrypt
     *
     * @param string $encrypt
     * @return TMember
     */
    public function setEncrypt($encrypt)
    {
        $this->encrypt = $encrypt;

        return $this;
    }

    /**
     * Get encrypt
     *
     * @return string
     */
    public function getEncrypt()
    {
        return $this->encrypt;
    }

    /**
     * Set isDelete
     *
     * @param string $isDelete
     * @return TMember
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return string
     */
    public function getIsDelete()
    {
        return $this->isDelete;
    }
    /**
     * Set img
     *
     * @param string $img
     * @return TMember
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set sex
     *
     * @param string $sex
     * @return TMember
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set sxcj
     *
     * @param string $sxcj
     * @return TMember
     */
    public function setSxcj($sxcj)
    {
        $this->sxcj = $sxcj;

        return $this;
    }

    /**
     * Get sxcj
     *
     * @return string
     */
    public function getSxcj()
    {
        return $this->sxcj;
    }

    /**
     * Set yycj
     *
     * @param string $yycj
     * @return TMember
     */
    public function setYycj($yycj)
    {
        $this->yycj = $yycj;

        return $this;
    }

    /**
     * Get sxcj
     *
     * @return string
     */
    public function getYycj()
    {
        return $this->yycj;
    }

}