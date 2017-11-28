<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TCategoryInfo
 *
 * @ORM\Table(name="t_category_info")
 * @ORM\Entity
 */
class TCategoryInfo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="infoid", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $infoid;

    /**
     * @var string
     *
     * @ORM\Column(name="catid", type="string", length=50, nullable=true)
     */
    private $catid;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=80, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * 上传文件临时字段
     */
    private $new_image;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=20, nullable=true)
     */
    private $username;

    /**
     * @var integer
     *
     * @ORM\Column(name="inputtime", type="integer", nullable=true)
     */
    private $inputtime;

    /**
     * @var integer
     *
     * @ORM\Column(name="updatetime", type="integer", nullable=true)
     */
    private $updatetime;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=16777215, nullable=true)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="praise", type="integer", nullable=true)
     */
    private $praise;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;



    /**
     * Get infoid
     *
     * @return integer 
     */
    public function getInfoid()
    {
        return $this->infoid;
    }

    /**
     * Set catid
     *
     * @param string $catid
     * @return TCategoryInfo
     */
    public function setCatid($catid)
    {
        $this->catid = $catid;

        return $this;
    }

    /**
     * Get catid
     *
     * @return string 
     */
    public function getCatid()
    {
        return $this->catid;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return TCategoryInfo
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
     * Set image
     *
     * @param string $image
     * @return TCategoryInfo
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * 上传文件临时字段
     * Set new_image
     *
     * @param string $new_image
     * @return TSpecial
     */
    public function setNewImage($new_image)
    {
        $this->new_image = $new_image;
        return $this;
    }

    /**
     * 上传文件临时字段
     * Get new_image
     *
     * @return string
     */
    public function getNewImage()
    {
        return $this->new_image;
    }


    /**
     * Set status
     *
     * @param boolean $status
     * @return TCategoryInfo
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

    /**
     * Set username
     *
     * @param string $username
     * @return TCategoryInfo
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
     * Set inputtime
     *
     * @param integer $inputtime
     * @return TCategoryInfo
     */
    public function setInputtime($inputtime)
    {
        $this->inputtime = $inputtime;

        return $this;
    }

    /**
     * Get inputtime
     *
     * @return integer 
     */
    public function getInputtime()
    {
        return $this->inputtime;
    }

    /**
     * Set updatetime
     *
     * @param integer $updatetime
     * @return TCategoryInfo
     */
    public function setUpdatetime($updatetime)
    {
        $this->updatetime = $updatetime;

        return $this;
    }

    /**
     * Get updatetime
     *
     * @return integer 
     */
    public function getUpdatetime()
    {
        return $this->updatetime;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return TCategoryInfo
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
     * Set praise
     *
     * @param integer $praise
     * @return TCategoryInfo
     */
    public function setPraise($praise)
    {
        $this->praise = $praise;

        return $this;
    }

    /**
     * Get praise
     *
     * @return integer 
     */
    public function getPraise()
    {
        return $this->praise;
    }


    /**
     * Set name
     *
     * @param string $name
     * @return TCategoryInfo
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
}
