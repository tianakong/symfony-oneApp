<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TQuizTopicOption
 *
 * @ORM\Table(name="t_quiz_topic_option")
 * @ORM\Entity
 */
class TQuizTopicOption
{
    /**
     * @var integer
     *
     * @ORM\Column(name="optionid", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $optionid;

    /**
     * @var integer
     *
     * @ORM\Column(name="topicid", type="smallint", nullable=true)
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
     * @ORM\Column(name="content", type="text", length=65535, nullable=true)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="smallint", nullable=true)
     */
    private $score;



    /**
     * Get optionid
     *
     * @return integer 
     */
    public function getOptionid()
    {
        return $this->optionid;
    }

    /**
     * Set topicid
     *
     * @param integer $topicid
     * @return TQuizTopicOption
     */
    public function setTopicid($topicid)
    {
        $this->topicid = $topicid;

        return $this;
    }

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
     * @return TQuizTopicOption
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
     * Set content
     *
     * @param string $content
     * @return TQuizTopicOption
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
     * Set score
     *
     * @param integer $score
     * @return TQuizTopicOption
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
    }
}
