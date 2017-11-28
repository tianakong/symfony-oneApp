<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TAdminRole
 *
 * @ORM\Table(name="t_admin_role", indexes={@ORM\Index(name="listorder", columns={"listorder"}), @ORM\Index(name="disabled", columns={"disabled"})})
 * @ORM\Entity(repositoryClass="Kuakao\AdminBundle\Repository\TAdminRoleRepository")
 */
class TAdminRole
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="rolename", type="string", length=50, nullable=false)
     */
    private $rolename;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="listorder", type="smallint", nullable=false)
     */
    private $listorder;

    /**
     * @var boolean
     *
     * @ORM\Column(name="disabled", type="boolean", nullable=false)
     */
    private $disabled;



    /**
     * Get id
     *
     * @return boolean
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rolename
     *
     * @param string $rolename
     * @return TAdminRole
     */
    public function setRolename($rolename)
    {
        $this->rolename = $rolename;

        return $this;
    }

    /**
     * Get rolename
     *
     * @return string
     */
    public function getRolename()
    {
        return $this->rolename;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return TAdminRole
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set listorder
     *
     * @param integer $listorder
     * @return TAdminRole
     */
    public function setListorder($listorder)
    {
        $this->listorder = $listorder;

        return $this;
    }

    /**
     * Get listorder
     *
     * @return integer
     */
    public function getListorder()
    {
        return $this->listorder;
    }

    /**
     * Set disabled
     *
     * @param boolean $disabled
     * @return TAdminRole
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled
     *
     * @return boolean
     */
    public function getDisabled()
    {
        if($this->disabled == 1 ) {
            return '启用';
        } else {
            return '禁用';
        }
    }
}
