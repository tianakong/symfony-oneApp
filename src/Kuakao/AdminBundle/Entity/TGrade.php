<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TGrade
 *
 * @ORM\Table(name="t_grade")
 * @ORM\Entity
 */
class TGrade
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
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=32, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="school", type="string", length=32, nullable=true)
     */
    private $school;

    /**
     * @var string
     *
     * @ORM\Column(name="major", type="string", length=32, nullable=true)
     */
    private $major;

    /**
     * @var integer
     *
     * @ORM\Column(name="zzfs", type="integer", nullable=true)
     */
    private $zzfs;

    /**
     * @var integer
     *
     * @ORM\Column(name="wyfs", type="integer", nullable=true)
     */
    private $wyfs;

    /**
     * @var integer
     *
     * @ORM\Column(name="ywk1", type="integer", nullable=true)
     */
    private $ywk1;

    /**
     * @var integer
     *
     * @ORM\Column(name="ywk2", type="integer", nullable=true)
     */
    private $ywk2;

    /**
     * @var string
     *
     * @ORM\Column(name="adminuser", type="string", length=32, nullable=true)
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
     * Set year
     *
     * @param integer $year
     * @return TGrade
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
     * Set username
     *
     * @param string $username
     * @return TGrade
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set school
     *
     * @param string $school
     * @return TGrade
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
     * Set major
     *
     * @param string $major
     * @return TGrade
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
     * Set zzfs
     *
     * @param integer $zzfs
     * @return TGrade
     */
    public function setZzfs($zzfs)
    {
        $this->zzfs = $zzfs;

        return $this;
    }

    /**
     * Get zzfs
     *
     * @return integer 
     */
    public function getZzfs()
    {
        return $this->zzfs;
    }

    /**
     * Set wyfs
     *
     * @param integer $wyfs
     * @return TGrade
     */
    public function setWyfs($wyfs)
    {
        $this->wyfs = $wyfs;

        return $this;
    }

    /**
     * Get wyfs
     *
     * @return integer 
     */
    public function getWyfs()
    {
        return $this->wyfs;
    }

    /**
     * Set ywk1
     *
     * @param integer $ywk1
     * @return TGrade
     */
    public function setYwk1($ywk1)
    {
        $this->ywk1 = $ywk1;

        return $this;
    }

    /**
     * Get ywk1
     *
     * @return integer 
     */
    public function getYwk1()
    {
        return $this->ywk1;
    }

    /**
     * Set ywk2
     *
     * @param integer $ywk2
     * @return TGrade
     */
    public function setYwk2($ywk2)
    {
        $this->ywk2 = $ywk2;

        return $this;
    }

    /**
     * Get ywk2
     *
     * @return integer 
     */
    public function getYwk2()
    {
        return $this->ywk2;
    }

    /**
     * Set adminuser
     *
     * @param string $adminuser
     * @return TGrade
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
     * @return TGrade
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
