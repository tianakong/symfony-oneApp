<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TAttachment
 *
 * @ORM\Table(name="t_attachment")
 * @ORM\Entity
 */
class TAttachment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="aid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $aid;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=50, nullable=false)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="filepath", type="string", length=200, nullable=false)
     */
    private $filepath;

    /**
     * @var integer
     *
     * @ORM\Column(name="filesize", type="integer", nullable=false)
     */
    private $filesize;

    /**
     * @var string
     *
     * @ORM\Column(name="fileext", type="string", length=10, nullable=false)
     */
    private $fileext;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isimage", type="boolean", nullable=false)
     */
    private $isimage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isthumb", type="boolean", nullable=false)
     */
    private $isthumb;

    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer", nullable=false)
     */
    private $userid;

    /**
     * @var integer
     *
     * @ORM\Column(name="uploadtime", type="integer", nullable=false)
     */
    private $uploadtime;

    /**
     * @var string
     *
     * @ORM\Column(name="uploadip", type="string", length=15, nullable=false)
     */
    private $uploadip;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;



    /**
     * Set filename
     *
     * @param string $filename
     * @return TAttachment
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set filepath
     *
     * @param string $filepath
     * @return TAttachment
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;

        return $this;
    }

    /**
     * Get filepath
     *
     * @return string 
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Set filesize
     *
     * @param integer $filesize
     * @return TAttachment
     */
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;

        return $this;
    }

    /**
     * Get filesize
     *
     * @return integer 
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * Set fileext
     *
     * @param string $fileext
     * @return TAttachment
     */
    public function setFileext($fileext)
    {
        $this->fileext = $fileext;

        return $this;
    }

    /**
     * Get fileext
     *
     * @return string 
     */
    public function getFileext()
    {
        return $this->fileext;
    }

    /**
     * Set isimage
     *
     * @param boolean $isimage
     * @return TAttachment
     */
    public function setIsimage($isimage)
    {
        $this->isimage = $isimage;

        return $this;
    }

    /**
     * Get isimage
     *
     * @return boolean 
     */
    public function getIsimage()
    {
        return $this->isimage;
    }

    /**
     * Set isthumb
     *
     * @param boolean $isthumb
     * @return TAttachment
     */
    public function setIsthumb($isthumb)
    {
        $this->isthumb = $isthumb;

        return $this;
    }

    /**
     * Get isthumb
     *
     * @return boolean 
     */
    public function getIsthumb()
    {
        return $this->isthumb;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return TAttachment
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
     * Set uploadtime
     *
     * @param integer $uploadtime
     * @return TAttachment
     */
    public function setUploadtime($uploadtime)
    {
        $this->uploadtime = $uploadtime;

        return $this;
    }

    /**
     * Get uploadtime
     *
     * @return integer 
     */
    public function getUploadtime()
    {
        return $this->uploadtime;
    }

    /**
     * Set uploadip
     *
     * @param string $uploadip
     * @return TAttachment
     */
    public function setUploadip($uploadip)
    {
        $this->uploadip = $uploadip;

        return $this;
    }

    /**
     * Get uploadip
     *
     * @return string 
     */
    public function getUploadip()
    {
        return $this->uploadip;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return TAttachment
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
     * Get aid
     *
     * @return integer 
     */
    public function getAid()
    {
        return $this->aid;
    }
}
