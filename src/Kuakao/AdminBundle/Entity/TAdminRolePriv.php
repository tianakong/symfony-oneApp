<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TAdminRolePriv
 *
 * @ORM\Table(name="t_admin_role_priv", uniqueConstraints={@ORM\UniqueConstraint(name="roleid", columns={"roleid", "routename"})})
 * @ORM\Entity
 */
class TAdminRolePriv
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
     * @var boolean
     *
     * @ORM\Column(name="roleid", type="boolean", nullable=false)
     */
    private $roleid;

    /**
     * @var string
     *
     * @ORM\Column(name="routename", type="string", length=100, nullable=false)
     */
    private $routename;



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
     * Set roleid
     *
     * @param boolean $roleid
     * @return TAdminRolePriv
     */
    public function setRoleid($roleid)
    {
        $this->roleid = $roleid;

        return $this;
    }

    /**
     * Get roleid
     *
     * @return boolean 
     */
    public function getRoleid()
    {
        return $this->roleid;
    }

    /**
     * Set routename
     *
     * @param string $routename
     * @return TAdminRolePriv
     */
    public function setRoutename($routename)
    {
        $this->routename = $routename;

        return $this;
    }

    /**
     * Get routename
     *
     * @return string 
     */
    public function getRoutename()
    {
        return $this->routename;
    }
}
