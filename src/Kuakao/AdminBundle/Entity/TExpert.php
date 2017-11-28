<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TExpert
 *
 * @ORM\Table(name="t_expert")
 * @ORM\Entity
 */
class TExpert
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="headpath", type="string", length=255, nullable=true)
     */
    private $headpath;

    /**
     * 上传文件临时字段
     */
    private $new_headpath;


    /**
     * @var string
     *
     * @ORM\Column(name="department", type="string", length=255, nullable=true)
     */
    private $department;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="introduce", type="string", length=255, nullable=true)
     */
    private $introduce;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;



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
     * @return TExpert
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
     * Set headpath
     *
     * @param string $headpath
     * @return TExpert
     */
    public function setHeadpath($headpath)
    {
        $this->headpath = $headpath;
    
        return $this;
    }

    /**
     * Get headpath
     *
     * @return string 
     */
    public function getHeadpath()
    {
        return $this->headpath;
    }

    /**
     * 上传文件临时字段
     * Set new_headpath
     *
     * @param string $new_headpath
     * @return TExpert
     */
    public function setNewHeadpath($new_headpath)
    {
        $this->new_headpath = $new_headpath;
        return $this;
    }

    /**
     * 上传文件临时字段
     * Get new_headpath
     *
     * @return string
     */
    public function getNewHeadpath()
    {
        return $this->new_headpath;
    }


    /**
     * Set department
     *
     * @param string $department
     * @return TExpert
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    
        return $this;
    }

    /**
     * Get department
     *
     * @return string 
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return TExpert
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
     * Set introduce
     *
     * @param string $introduce
     * @return TExpert
     */
    public function setIntroduce($introduce)
    {
        $this->introduce = $introduce;
    
        return $this;
    }

    /**
     * Get introduce
     *
     * @return string 
     */
    public function getIntroduce()
    {
        return $this->introduce;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return TExpert
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
}
