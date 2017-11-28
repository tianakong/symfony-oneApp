<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TLog
 *
 * @ORM\Table(name="t_log", indexes={@ORM\Index(name="module", columns={"module", "file", "action"}), @ORM\Index(name="username", columns={"username", "action"})})
 * @ORM\Entity(repositoryClass="Kuakao\AdminBundle\Repository\TLogRepository")
 */
class TLog
{
    /**
     * @var integer
     *
     * @ORM\Column(name="logid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $logid;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=15, nullable=false)
     */
    private $field;

    /**
     * @var integer
     *
     * @ORM\Column(name="value", type="integer", nullable=false)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="module", type="string", length=15, nullable=false)
     */
    private $module;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=20, nullable=false)
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=20, nullable=false)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="querystring", type="string", length=255, nullable=false)
     */
    private $querystring;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="text", length=16777215, nullable=false)
     */
    private $data;

    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer", nullable=false)
     */
    private $userid;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=20, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15, nullable=false)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime", nullable=false)
     */
    private $time;



    /**
     * Get logid
     *
     * @return integer 
     */
    public function getLogid()
    {
        return $this->logid;
    }

    /**
     * Set field
     *
     * @param string $field
     * @return TLog
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return string 
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set value
     *
     * @param integer $value
     * @return TLog
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set module
     *
     * @param string $module
     * @return TLog
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return string 
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return TLog
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set action
     *
     * @param string $action
     * @return TLog
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set querystring
     *
     * @param string $querystring
     * @return TLog
     */
    public function setQuerystring($querystring)
    {
        $this->querystring = $querystring;

        return $this;
    }

    /**
     * Get querystring
     *
     * @return string 
     */
    public function getQuerystring()
    {
        return $this->querystring;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return TLog
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return TLog
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
     * Set username
     *
     * @param string $username
     * @return TLog
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
     * Set ip
     *
     * @param string $ip
     * @return TLog
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return TLog
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }
}
