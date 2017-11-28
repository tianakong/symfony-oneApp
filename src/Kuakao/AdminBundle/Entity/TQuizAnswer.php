<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TQuizAnswer
 *
 * @ORM\Table(name="t_quiz_answer")
 * @ORM\Entity
 */
class TQuizAnswer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="answerid", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $answerid;

    /**
     * @var integer
     *
     * @ORM\Column(name="quizid", type="smallint", nullable=true)
     */
    private $quizid;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_score", type="smallint", nullable=true)
     */
    private $minScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_score", type="smallint", nullable=true)
     */
    private $maxScore;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=true)
     */
    private $content;



    /**
     * Get answerid
     *
     * @return integer 
     */
    public function getAnswerid()
    {
        return $this->answerid;
    }

    /**
     * Set quizid
     *
     * @param integer $quizid
     * @return TQuizAnswer
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
     * Set minScore
     *
     * @param integer $minScore
     * @return TQuizAnswer
     */
    public function setMinScore($minScore)
    {
        $this->minScore = $minScore;

        return $this;
    }

    /**
     * Get minScore
     *
     * @return integer 
     */
    public function getMinScore()
    {
        return $this->minScore;
    }

    /**
     * Set maxScore
     *
     * @param integer $maxScore
     * @return TQuizAnswer
     */
    public function setMaxScore($maxScore)
    {
        $this->maxScore = $maxScore;

        return $this;
    }

    /**
     * Get maxScore
     *
     * @return integer 
     */
    public function getMaxScore()
    {
        return $this->maxScore;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return TQuizAnswer
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
}
