<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TAddScores
 *
 * @ORM\Table(name="t_add_scores")
 * @ORM\Entity
 */
class TAddScores
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
     * @ORM\Column(name="uid", type="integer", nullable=true)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=20, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="test_type", type="string", length=100, nullable=true)
     */
    private $testType;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="smallint", nullable=true)
     */
    private $score;

    /**
     * @var integer
     *
     * @ORM\Column(name="school_id", type="integer", nullable=true)
     */
    private $schoolId;

    /**
     * @var integer
     *
     * @ORM\Column(name="major_id", type="integer", nullable=true)
     */
    private $majorId;



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
     * @return TAddScores
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
     * Set username
     *
     * @param string $username
     * @return TAddScores
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
     * Set testType
     *
     * @param string $testType
     * @return TAddScores
     */
    public function setTestType($testType)
    {
        $this->testType = $testType;

        return $this;
    }

    /**
     * Get testType
     *
     * @return string 
     */
    public function getTestType()
    {
        return $this->testType;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return TAddScores
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set score
     *
     * @param integer $score
     * @return TAddScores
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set schoolId
     *
     * @param integer $schoolId
     * @return TAddScores
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
     * @return TAddScores
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
}
