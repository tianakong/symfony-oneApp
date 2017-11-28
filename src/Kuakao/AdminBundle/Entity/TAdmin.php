<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kuakao\AdminBundle\Repository\TAdminRepository;
use Kuakao\Common\Globals;

/**
 * TAdmin
 *
 * @ORM\Table(name="t_admin", indexes={@ORM\Index(name="username", columns={"username"})})
 * @ORM\Entity
 */
class TAdmin
{
    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    private $userid;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=20, nullable=true)
     *
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=32, nullable=true)
     *
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="roleid", type="smallint", nullable=false)
     *
     */
    private $roleid;

    /**
     * @var string
     *
     * @ORM\Column(name="encrypt", type="string", length=6, nullable=false)
     *
     */
    private $encrypt;

    /**
     * @var string
     *
     * @ORM\Column(name="lastloginip", type="string", length=15, nullable=false)
     *
     */
    private $lastloginip;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastlogintime", type="integer", nullable=false)
     *
     */
    private $lastlogintime;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=40, nullable=true)
     *
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="realname", type="string", length=50, nullable=false)
     */
    private $realname;



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
     * @return TAdmin
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
     * Set password
     *
     * @param string $password
     * @return TAdmin
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
     * Set roleid
     *
     * @param integer $roleid
     * @return TAdmin
     */
    public function setRoleid($roleid)
    {
        $this->roleid = $roleid;

        return $this;
    }

    /**
     * Get roleid
     *
     * @return integer 
     */
    public function getRoleid()
    {
        return $this->roleid;
    }

    /**
     * Set encrypt
     *
     * @param string $encrypt
     * @return TAdmin
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
     * Set lastloginip
     *
     * @param string $lastloginip
     * @return TAdmin
     */
    public function setLastloginip($lastloginip)
    {
        $this->lastloginip = $lastloginip;

        return $this;
    }

    /**
     * Get lastloginip
     *
     * @return string 
     */
    public function getLastloginip()
    {
        return $this->lastloginip;
    }

    /**
     * Set lastlogintime
     *
     * @param integer $lastlogintime
     * @return TAdmin
     */
    public function setLastlogintime($lastlogintime)
    {
        $this->lastlogintime = $lastlogintime;

        return $this;
    }

    /**
     * Get lastlogintime
     *
     * @return integer 
     */
    public function getLastlogintime()
    {
        return $this->lastlogintime;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return TAdmin
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set realname
     *
     * @param string $realname
     * @return TAdmin
     */
    public function setRealname($realname)
    {
        $this->realname = $realname;

        return $this;
    }

    /**
     * Get realname
     *
     * @return string 
     */
    public function getRealname()
    {
        return $this->realname;
    }
}
