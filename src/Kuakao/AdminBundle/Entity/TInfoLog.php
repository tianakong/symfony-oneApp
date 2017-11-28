<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TInfoLog
 *
 * @ORM\Table(name="t_info_log")
 * @ORM\Entity
 */
class TInfoLog
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
     * @ORM\Column(name="mobile", type="string", length=500, nullable=false)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=3000, nullable=false)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="sendTime", type="integer", nullable=false)
     */
    private $sendtime;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=30, nullable=false)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="code", type="boolean", nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=20, nullable=false)
     */
    private $user;



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
     * Set mobile
     *
     * @param string $mobile
     * @return TInfoLog
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
     * Set content
     *
     * @param string $content
     * @return TInfoLog
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set sendtime
     *
     * @param integer $sendtime
     * @return TInfoLog
     */
    public function setSendtime($sendtime)
    {
        $this->sendtime = $sendtime;

        return $this;
    }

    /**
     * Get sendtime
     *
     * @return integer 
     */
    public function getSendtime()
    {
        return $this->sendtime;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return TInfoLog
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set code
     *
     * @param boolean $code
     * @return TInfoLog
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return boolean 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return TInfoLog
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }
}
