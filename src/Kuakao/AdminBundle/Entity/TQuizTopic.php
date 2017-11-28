<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TQuizTopic
 *
 * @ORM\Table(name="t_quiz_topic")
 * @ORM\Entity
 */
class TQuizTopic
{
    /**
     * @var integer
     *
     * @ORM\Column(name="topicid", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $topicid;

    /**
     * @var integer
     *
     * @ORM\Column(name="quizid", type="smallint", nullable=true)
     */
    private $quizid;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", length=65535, nullable=true)
     */
    private $title;



    /**
     * Get topicid
     *
     * @return integer 
     */
    public function getTopicid()
    {
        return $this->topicid;
    }

    /**
     * Set quizid
     *
     * @param integer $quizid
     * @return TQuizTopic
     */
    public function setQuizid($quizid)
    {
        $this->quizid = $quizid;

        return $this;
    }

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
     * Set title
     *
     * @param string $title
     * @return TQuizTopic
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
}
