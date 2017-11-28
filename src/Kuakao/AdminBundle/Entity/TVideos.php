<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TVideos
 *
 * @ORM\Table(name="t_videos")
 * @ORM\Entity
 */
class TVideos
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
     * @ORM\Column(name="title", type="string", length=1000, nullable=true)
     */
    private $title;


    /**
     * @var string
     *
     * @ORM\Column(name="head_path", type="string", length=300, nullable=true)
     */
    private $headPath;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb", type="string", length=300, nullable=true)
     */
    private $thumb;

    /**
     * 上传文件临时字段
     */
    private $new_thumb;

    /**
     * @var integer
     *
     * @ORM\Column(name="views", type="smallint", nullable=true)
     */
    private $views;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=300, nullable=true)
     */
    private $video;

    /**
     * @var string
     *
     * @ORM\Column(name="speaker", type="string", length=10, nullable=true)
     */
    private $speaker;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string", length=1000, nullable=true)
     */
    private $detail;

    /**
     * @var integer
     *
     * @ORM\Column(name="add_time", type="integer", nullable=true)
     */
    private $addTime;

    /**
     * @var string
     *
     * @ORM\Column(name="add_user", type="string", length=20, nullable=true)
     */
    private $addUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="edit_time", type="integer", nullable=true)
     */
    private $editTime;

    /**
     * @var string
     *
     * @ORM\Column(name="edit_user", type="string", length=20, nullable=true)
     */
    private $editUser;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
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
     * @return TVideos
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
     * Set headPath
     *
     * @param string $thumb
     * @return TVideos
     */
    public function setHeadPath($headPath)
    {
        $this->headPath = $headPath;

        return $this;
    }

    /**
     * Get headPath
     *
     * @return string
     */
    public function getHeadPath()
    {
        return $this->headPath;
    }




    /**
     * Set thumb
     *
     * @param string $thumb
     * @return TVideos
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;
    
        return $this;
    }

    /**
     * Get thumb
     *
     * @return string 
     */
    public function getThumb()
    {
        return $this->thumb;
    }


    /**
     * 上传文件临时字段
     * Set new_thumb
     *
     * @param string $new_thumb
     * @return TVideos
     */
    public function setNewThumb($new_thumb)
    {
        $this->new_thumb = $new_thumb;
        return $this;
    }

    /**
     * 上传文件临时字段
     * Get new_thumb
     *
     * @return string
     */
    public function getNewThumb()
    {
        return $this->new_thumb;
    }
    
    
    
    
    
    
    /**
     * Set views
     *
     * @param integer $views
     * @return TVideos
     */
    public function setViews($views)
    {
        $this->views = $views;
    
        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set video
     *
     * @param string $video
     * @return TVideos
     */
    public function setVideo($video)
    {
        $this->video = $video;
    
        return $this;
    }

    /**
     * Get video
     *
     * @return string 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set speaker
     *
     * @param string $speaker
     * @return TVideos
     */
    public function setSpeaker($speaker)
    {
        $this->speaker = $speaker;
    
        return $this;
    }

    /**
     * Get speaker
     *
     * @return string 
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return TVideos
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
     * Set detail
     *
     * @param string $detail
     * @return TVideos
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
    
        return $this;
    }

    /**
     * Get detail
     *
     * @return string 
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set addTime
     *
     * @param integer $addTime
     * @return TVideos
     */
    public function setAddTime($addTime)
    {
        $this->addTime = $addTime;
    
        return $this;
    }

    /**
     * Get addTime
     *
     * @return integer 
     */
    public function getAddTime()
    {
        return $this->addTime;
    }

    /**
     * Set addUser
     *
     * @param string $addUser
     * @return TVideos
     */
    public function setAddUser($addUser)
    {
        $this->addUser = $addUser;
    
        return $this;
    }

    /**
     * Get addUser
     *
     * @return string 
     */
    public function getAddUser()
    {
        return $this->addUser;
    }

    /**
     * Set editTime
     *
     * @param integer $editTime
     * @return TVideos
     */
    public function setEditTime($editTime)
    {
        $this->editTime = $editTime;
    
        return $this;
    }

    /**
     * Get editTime
     *
     * @return integer 
     */
    public function getEditTime()
    {
        return $this->editTime;
    }

    /**
     * Set editUser
     *
     * @param string $editUser
     * @return TVideos
     */
    public function setEditUser($editUser)
    {
        $this->editUser = $editUser;
    
        return $this;
    }

    /**
     * Get editUser
     *
     * @return string 
     */
    public function getEditUser()
    {
        return $this->editUser;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return TVideos
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
