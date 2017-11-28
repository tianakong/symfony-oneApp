<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TFeedback
 *
 * @ORM\Table(name="t_feedback")
 * @ORM\Entity
 */
class TFeedback
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
     * @ORM\Column(name="username", type="string", length=20, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=1000, nullable=true)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="add_time", type="integer", nullable=true)
     */
    private $addTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="reply_content", type="string", length=1000, nullable=true)
     */
    private $replyContent;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=1000, nullable=true)
     */
    private $img;

    /**
     * @var integer
     *
     * @ORM\Column(name="reply_time", type="integer", nullable=true)
     */
    private $replyTime;



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
     * Set username
     *
     * @param string $username
     * @return TFeedback
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
     * Set content
     *
     * @param string $content
     * @return TFeedback
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
     * Set addTime
     *
     * @param integer $addTime
     * @return TFeedback
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
     * Set status
     *
     * @param boolean $status
     * @return TFeedback
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
     * Set replyContent
     *
     * @param string $replyContent
     * @return TFeedback
     */
    public function setReplyContent($replyContent)
    {
        $this->replyContent = $replyContent;

        return $this;
    }

    /**
     * Get replyContent
     *
     * @return string 
     */
    public function getReplyContent()
    {
        return $this->replyContent;
    }

    /**
     * Set img
     *
     * @param string $img
     * @return TFeedback
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set replyTime
     *
     * @param integer $replyTime
     * @return TFeedback
     */
    public function setReplyTime($replyTime)
    {
        $this->replyTime = $replyTime;

        return $this;
    }

    /**
     * Get replyTime
     *
     * @return integer 
     */
    public function getReplyTime()
    {
        return $this->replyTime;
    }
}
