<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TQuiz
 *
 * @ORM\Table(name="t_quiz")
 * @ORM\Entity
 */
class TQuiz
{
    /**
     * @var integer
     *
     * @ORM\Column(name="quizid", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $quizid;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=100, nullable=true)
     */
    private $icon;

    /**
     * 上传文件临时字段
     */
    private $new_icon;
    

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=100, nullable=true)
     */
    private $subtitle;

    /**
     * @var integer
     *
     * @ORM\Column(name="topic_num", type="smallint", nullable=true)
     */
    private $topicNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="option_num", type="smallint", nullable=true)
     */
    private $optionNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="preson_num", type="integer", nullable=true)
     */
    private $presonNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="time", type="integer", nullable=true)
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100, nullable=true)
     */
    private $username;



    /**
     * Get quizid
     *
     * @return integer 
     */
    public function getQuizid()
    {
        return $this->quizid;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return TQuiz
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * 上传文件临时字段
     * Set new_icon
     *
     * @param string $new_icon
     * @return TQuiz
     */
    public function setNewIcon($new_icon)
    {
        $this->new_icon = $new_icon;
        return $this;
    }

    /**
     * 上传文件临时字段
     * Get new_icon
     *
     * @return string
     */
    public function getNewIcon()
    {
        return $this->new_icon;
    }



    /**
     * Set title
     *
     * @param string $title
     * @return TQuiz
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
     * Set subtitle
     *
     * @param string $subtitle
     * @return TQuiz
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set topicNum
     *
     * @param integer $topicNum
     * @return TQuiz
     */
    public function setTopicNum($topicNum)
    {
        $this->topicNum = $topicNum;

        return $this;
    }

    /**
     * Get topicNum
     *
     * @return integer 
     */
    public function getTopicNum()
    {
        return $this->topicNum;
    }

    /**
     * Set optionNum
     *
     * @param integer $optionNum
     * @return TQuiz
     */
    public function setOptionNum($optionNum)
    {
        $this->optionNum = $optionNum;

        return $this;
    }

    /**
     * Get optionNum
     *
     * @return integer 
     */
    public function getOptionNum()
    {
        return $this->optionNum;
    }

    /**
     * Set presonNum
     *
     * @param integer $presonNum
     * @return TQuiz
     */
    public function setPresonNum($presonNum)
    {
        $this->presonNum = $presonNum;

        return $this;
    }

    /**
     * Get presonNum
     *
     * @return integer 
     */
    public function getPresonNum()
    {
        return $this->presonNum;
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return TQuiz
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return integer 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return TQuiz
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
}
