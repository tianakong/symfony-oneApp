<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TScores
 *
 * @ORM\Table(name="t_scores")
 * @ORM\Entity
 */
class TScores
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
     * @ORM\Column(name="year", type="integer", nullable=true)
     */
    private $year;


    /**
     * 学校名称临时字段
     */
    public $schoolName;

    /**
     * 省份名称临时字段
     */
    public $shengName;

    /**
     * 学校名称临时字段
     */
    public $majorName;

    /**
     * @var integer
     *
     * @ORM\Column(name="sheng_id", type="smallint", nullable=true)
     */
    private $shengId;

    /**
     * @var integer
     *
     * @ORM\Column(name="school_id", type="smallint", nullable=true)
     */
    private $schoolId;

    /**
     * @var integer
     *
     * @ORM\Column(name="major_id", type="smallint", nullable=true)
     */
    private $majorId;

    /**
     * @var integer
     *
     * @ORM\Column(name="enroll_num", type="integer", nullable=true)
     */
    private $enrollNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="forecast_score", type="smallint", nullable=true)
     */
    private $forecastScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_score", type="smallint", nullable=true)
     */
    private $totalScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="recruit_num", type="integer", nullable=true)
     */
    private $recruitNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="push_avoid_num", type="integer", nullable=true)
     */
    private $pushAvoidNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="political", type="smallint", nullable=true)
     */
    private $political;

    /**
     * @var integer
     *
     * @ORM\Column(name="english", type="smallint", nullable=true)
     */
    private $english;

    /**
     * @var integer
     *
     * @ORM\Column(name="profes1", type="smallint", nullable=true)
     */
    private $profes1;

    /**
     * @var integer
     *
     * @ORM\Column(name="profes2", type="smallint", nullable=true)
     */
    private $profes2;

    /**
     * @var string
     *
     * @ORM\Column(name="add_user", type="string", length=20, nullable=true)
     */
    private $addUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="edit_time", type="integer", nullable=true)
     */
    private $editTime;



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
     * Set year
     *
     * @param integer $year
     * @return TScores
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set shengId
     *
     * @param integer $shengId
     * @return TScores
     */
    public function setShengId($shengId)
    {
        $this->shengId = $shengId;

        return $this;
    }

    /**
     * Get shengId
     *
     * @return integer 
     */
    public function getShengId()
    {
        return $this->shengId;
    }

    /**
     * Set schoolId
     *
     * @param integer $schoolId
     * @return TScores
     */
    public function setSchoolId($schoolId)
    {
        $this->schoolId = $schoolId;

        return $this;
    }

    /**
     * Get schoolId
     *
     * @return integer 
     */
    public function getSchoolId()
    {
        return $this->schoolId;
    }

    /**
     * Set majorId
     *
     * @param integer $majorId
     * @return TScores
     */
    public function setMajorId($majorId)
    {
        $this->majorId = $majorId;

        return $this;
    }

    /**
     * Get majorId
     *
     * @return integer 
     */
    public function getMajorId()
    {
        return $this->majorId;
    }

    /**
     * Set enrollNum
     *
     * @param integer $enrollNum
     * @return TScores
     */
    public function setEnrollNum($enrollNum)
    {
        $this->enrollNum = $enrollNum;

        return $this;
    }

    /**
     * Get enrollNum
     *
     * @return integer 
     */
    public function getEnrollNum()
    {
        return $this->enrollNum;
    }

    /**
     * Set forecastScore
     *
     * @param integer $forecastScore
     * @return TScores
     */
    public function setForecastScore($forecastScore)
    {
        $this->forecastScore = $forecastScore;

        return $this;
    }

    /**
     * Get forecastScore
     *
     * @return integer 
     */
    public function getForecastScore()
    {
        return $this->forecastScore;
    }

    /**
     * Set totalScore
     *
     * @param integer $totalScore
     * @return TScores
     */
    public function setTotalScore($totalScore)
    {
        $this->totalScore = $totalScore;

        return $this;
    }

    /**
     * Get totalScore
     *
     * @return integer 
     */
    public function getTotalScore()
    {
        return $this->totalScore;
    }

    /**
     * Set recruitNum
     *
     * @param integer $recruitNum
     * @return TScores
     */
    public function setRecruitNum($recruitNum)
    {
        $this->recruitNum = $recruitNum;

        return $this;
    }

    /**
     * Get recruitNum
     *
     * @return integer 
     */
    public function getRecruitNum()
    {
        return $this->recruitNum;
    }

    /**
     * Set pushAvoidNum
     *
     * @param integer $pushAvoidNum
     * @return TScores
     */
    public function setPushAvoidNum($pushAvoidNum)
    {
        $this->pushAvoidNum = $pushAvoidNum;

        return $this;
    }

    /**
     * Get pushAvoidNum
     *
     * @return integer 
     */
    public function getPushAvoidNum()
    {
        return $this->pushAvoidNum;
    }

    /**
     * Set political
     *
     * @param integer $political
     * @return TScores
     */
    public function setPolitical($political)
    {
        $this->political = $political;

        return $this;
    }

    /**
     * Get political
     *
     * @return integer 
     */
    public function getPolitical()
    {
        return $this->political;
    }

    /**
     * Set english
     *
     * @param integer $english
     * @return TScores
     */
    public function setEnglish($english)
    {
        $this->english = $english;

        return $this;
    }

    /**
     * Get english
     *
     * @return integer 
     */
    public function getEnglish()
    {
        return $this->english;
    }

    /**
     * Set profes1
     *
     * @param integer $profes1
     * @return TScores
     */
    public function setProfes1($profes1)
    {
        $this->profes1 = $profes1;

        return $this;
    }

    /**
     * Get profes1
     *
     * @return integer 
     */
    public function getProfes1()
    {
        return $this->profes1;
    }

    /**
     * Set profes2
     *
     * @param integer $profes2
     * @return TScores
     */
    public function setProfes2($profes2)
    {
        $this->profes2 = $profes2;

        return $this;
    }

    /**
     * Get profes2
     *
     * @return integer 
     */
    public function getProfes2()
    {
        return $this->profes2;
    }

    /**
     * Set addUser
     *
     * @param string $addUser
     * @return TScores
     */
    public function setAddUser($addUser)
    {
        $this->addUser = $addUser;

        return $this;
    }

    /**
     * Get addUser
     *
     * @return string 
     */
    public function getAddUser()
    {
        return $this->addUser;
    }

    /**
     * Set editTime
     *
     * @param integer $editTime
     * @return TScores
     */
    public function setEditTime($editTime)
    {
        $this->editTime = $editTime;

        return $this;
    }

    /**
     * Get editTime
     *
     * @return integer 
     */
    public function getEditTime()
    {
        return $this->editTime;
    }
}
