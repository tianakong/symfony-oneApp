<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TSchool
 *
 * @ORM\Table(name="t_school")
 * @ORM\Entity
 */
class TSchool
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
     * @ORM\Column(name="name", type="string", length=30, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=200, nullable=false)
     */
    private $logo;
    private $logo_tmp;

    /**
     * @var string
     *
     * @ORM\Column(name="ndxs", type="string", length=5, nullable=false)
     */
    private $ndxs;

    /**
     * @var string
     *
     * @ORM\Column(name="pic", type="string", length=200, nullable=false)
     */
    private $pic;
    private $pic_tmp;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="smallint", nullable=false)
     */
    private $rank;

    /**
     * @var integer
     *
     * @ORM\Column(name="gzrs", type="smallint", nullable=false)
     */
    private $gzrs;

    /**
     * @var integer
     *
     * @ORM\Column(name="province", type="smallint", nullable=false)
     */
    private $province;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_211", type="boolean", nullable=false)
     */
    private $is211;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_985", type="boolean", nullable=false)
     */
    private $is985;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_zhx", type="boolean", nullable=false)
     */
    private $isZhx;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_yjs", type="boolean", nullable=false)
     */
    private $isYjs;

    /**
     * @var string
     *
     * @ORM\Column(name="area_top", type="string", length=255, nullable=true)
     */
    private $areaTop;

    /**
     * @var string
     *
     * @ORM\Column(name="trade_top", type="string", length=255, nullable=true)
     */
    private $tradeTop;

    /**
     * @var string
     *
     * @ORM\Column(name="average_wage", type="string", length=10, nullable=true)
     */
    private $averageWage;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", length=65535, nullable=true)
     */
    private $about;

    /**
     * @var string
     *
     * @ORM\Column(name="jyl", type="string", length=10, nullable=true)
     */
    private $jyl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="school_type", type="smallint", nullable=true)
     */
    private $schoolType;

    /**
     * @var boolean
     *
     * @ORM\Column(name="comes_under", type="smallint", nullable=true)
     */
    private $comesUnder;

    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer", nullable=true)
     */
    private $userid;

    /**
     * @var integer
     *
     * @ORM\Column(name="inputtime", type="integer", nullable=true)
     */
    private $inputtime;

    /**
     * @var integer
     *
     * @ORM\Column(name="updatetime", type="integer", nullable=true)
     */
    private $updatetime;

    /**
     * @var integer
     *
     * @ORM\Column(name="school_level", type="integer", nullable=true)
     */
    private $schoolLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="doctor", type="integer", nullable=true)
     */
    private $doctor;

    /**
     * @var integer
     *
     * @ORM\Column(name="master", type="integer", nullable=true)
     */
    private $master;

    /**
     * @var integer
     *
     * @ORM\Column(name="key_subjects", type="integer", nullable=true)
     */
    private $keySubjects;



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
     * @return TSchool
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
     * Set logo
     *
     * @param string $logo
     * @return TSchool
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set logo_tmp
     *
     * @param string $logo_tmp
     * @return TSchool
     */
    public function setLogoTmp($logo_tmp)
    {
        $this->logo_tmp = $logo_tmp;

        return $this;
    }

    /**
     * Get logo_tmp
     *
     * @return string
     */
    public function getLogoTmp()
    {
        return $this->logo_tmp;
    }

    /**
     * Set ndxs
     *
     * @param string $ndxs
     * @return TSchool
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
     * Set pic
     *
     * @param string $pic
     * @return TSchool
     */
    public function setPic($pic)
    {
        $this->pic = $pic;

        return $this;
    }

    /**
     * Get pic
     *
     * @return string 
     */
    public function getPic()
    {
        return $this->pic;
    }

    /**
     * Set pic_tmp
     *
     * @param string $pic_tmp
     * @return TSchool
     */
    public function setPicTmp($pic_tmp)
    {
        $this->pic_tmp = $pic_tmp;

        return $this;
    }

    /**
     * Get pic_tmp
     *
     * @return string
     */
    public function getPicTmp()
    {
        return $this->pic_tmp;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return TSchool
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set gzrs
     *
     * @param integer $gzrs
     * @return TSchool
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
     * Set province
     *
     * @param integer $province
     * @return TSchool
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get province
     *
     * @return integer 
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set is211
     *
     * @param boolean $is211
     * @return TSchool
     */
    public function setIs211($is211)
    {
        $this->is211 = $is211;

        return $this;
    }

    /**
     * Get is211
     *
     * @return boolean 
     */
    public function getIs211()
    {
        return $this->is211;
    }

    /**
     * Set is985
     *
     * @param boolean $is985
     * @return TSchool
     */
    public function setIs985($is985)
    {
        $this->is985 = $is985;

        return $this;
    }

    /**
     * Get is985
     *
     * @return boolean 
     */
    public function getIs985()
    {
        return $this->is985;
    }

    /**
     * Set isZhx
     *
     * @param boolean $isZhx
     * @return TSchool
     */
    public function setIsZhx($isZhx)
    {
        $this->isZhx = $isZhx;

        return $this;
    }

    /**
     * Get isZhx
     *
     * @return boolean 
     */
    public function getIsZhx()
    {
        return $this->isZhx;
    }

    /**
     * Set isYjs
     *
     * @param boolean $isYjs
     * @return TSchool
     */
    public function setIsYjs($isYjs)
    {
        $this->isYjs = $isYjs;

        return $this;
    }

    /**
     * Get isYjs
     *
     * @return boolean 
     */
    public function getIsYjs()
    {
        return $this->isYjs;
    }

    /**
     * Set areaTop
     *
     * @param string $areaTop
     * @return TSchool
     */
    public function setAreaTop($areaTop)
    {
        $this->areaTop = $areaTop;

        return $this;
    }

    /**
     * Get areaTop
     *
     * @return string 
     */
    public function getAreaTop()
    {
        return $this->areaTop;
    }

    /**
     * Set tradeTop
     *
     * @param string $tradeTop
     * @return TSchool
     */
    public function setTradeTop($tradeTop)
    {
        $this->tradeTop = $tradeTop;

        return $this;
    }

    /**
     * Get tradeTop
     *
     * @return string 
     */
    public function getTradeTop()
    {
        return $this->tradeTop;
    }

    /**
     * Set averageWage
     *
     * @param string $averageWage
     * @return TSchool
     */
    public function setAverageWage($averageWage)
    {
        $this->averageWage = $averageWage;

        return $this;
    }

    /**
     * Get averageWage
     *
     * @return string 
     */
    public function getAverageWage()
    {
        return $this->averageWage;
    }

    /**
     * Set about
     *
     * @param string $about
     * @return TSchool
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string 
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set jyl
     *
     * @param string $jyl
     * @return TSchool
     */
    public function setJyl($jyl)
    {
        $this->jyl = $jyl;

        return $this;
    }

    /**
     * Get jyl
     *
     * @return string 
     */
    public function getJyl()
    {
        return $this->jyl;
    }

    /**
     * Set schoolType
     *
     * @param integer $schoolType
     * @return TSchool
     */
    public function setSchoolType($schoolType)
    {
        $this->schoolType = $schoolType;

        return $this;
    }

    /**
     * Get schoolType
     *
     * @return integer 
     */
    public function getSchoolType()
    {
        return $this->schoolType;
    }

    /**
     * Set comesUnder
     *
     * @param integer $comesUnder
     * @return TSchool
     */
    public function setComesUnder($comesUnder)
    {
        $this->comesUnder = $comesUnder;

        return $this;
    }

    /**
     * Get comesUnder
     *
     * @return integer 
     */
    public function getComesUnder()
    {
        return $this->comesUnder;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return TSchool
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return integer 
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set inputtime
     *
     * @param integer $inputtime
     * @return TSchool
     */
    public function setInputtime($inputtime)
    {
        $this->inputtime = $inputtime;

        return $this;
    }

    /**
     * Get inputtime
     *
     * @return integer 
     */
    public function getInputtime()
    {
        return $this->inputtime;
    }

    /**
     * Set updatetime
     *
     * @param integer $updatetime
     * @return TSchool
     */
    public function setUpdatetime($updatetime)
    {
        $this->updatetime = $updatetime;

        return $this;
    }

    /**
     * Get updatetime
     *
     * @return integer 
     */
    public function getUpdatetime()
    {
        return $this->updatetime;
    }

    /**
     * Set schoolLevel
     *
     * @param integer $schoolLevel
     * @return TSchool
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
     * Set doctor
     *
     * @param integer $doctor
     * @return TSchool
     */
    public function setDoctor($doctor)
    {
        $this->doctor = $doctor;

        return $this;
    }

    /**
     * Get doctor
     *
     * @return integer 
     */
    public function getDoctor()
    {
        return $this->doctor;
    }

    /**
     * Set master
     *
     * @param integer $master
     * @return TSchool
     */
    public function setMaster($master)
    {
        $this->master = $master;

        return $this;
    }

    /**
     * Get master
     *
     * @return integer 
     */
    public function getMaster()
    {
        return $this->master;
    }

    /**
     * Set keySubjects
     *
     * @param integer $keySubjects
     * @return TSchool
     */
    public function setKeySubjects($keySubjects)
    {
        $this->keySubjects = $keySubjects;

        return $this;
    }

    /**
     * Get keySubjects
     *
     * @return integer 
     */
    public function getKeySubjects()
    {
        return $this->keySubjects;
    }
}
