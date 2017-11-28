<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TNews
 *
 * @ORM\Table(name="t_news", indexes={@ORM\Index(name="title", columns={"title"})})
 * @ORM\Entity
 */
class TNews
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
     * @ORM\Column(name="title", type="string", length=150, nullable=false)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="addTime", type="integer", nullable=false)
     */
    private $addtime;

    /**
     * @var string
     *
     * @ORM\Column(name="addUser", type="string", length=20, nullable=false)
     */
    private $adduser;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=2000, nullable=false)
     */
    private $content;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;



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
     * Set title
     *
     * @param string $title
     * @return TNews
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set addtime
     *
     * @param integer $addtime
     * @return TNews
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

    /**
     * Set adduser
     *
     * @param string $adduser
     * @return TNews
     */
    public function setAdduser($adduser)
    {
        $this->adduser = $adduser;

        return $this;
    }

    /**
     * Get adduser
     *
     * @return string 
     */
    public function getAdduser()
    {
        return $this->adduser;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return TNews
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
     * Set status
     *
     * @param boolean $status
     * @return TNews
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
