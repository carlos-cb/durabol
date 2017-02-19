<?php

namespace DurabolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 */
class Post
{
    /**
     * @var int
     */
    private $id;


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
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTime
     */
    private $time;

    /**
     * @var integer
     */
    private $readNum;


    /**
     * Set author
     *
     * @param string $author
     * @return Post
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Post
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
     * Set time
     *
     * @param \DateTime $time
     * @return Post
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set readNum
     *
     * @param integer $readNum
     * @return Post
     */
    public function setReadNum($readNum)
    {
        $this->readNum = $readNum;

        return $this;
    }

    /**
     * Get readNum
     *
     * @return integer 
     */
    public function getReadNum()
    {
        return $this->readNum;
    }
    /**
     * @var string
     */
    private $title;


    /**
     * Set title
     *
     * @param string $title
     * @return Post
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
     * @var boolean
     */
    private $isEs;


    /**
     * Set isEs
     *
     * @param boolean $isEs
     * @return Post
     */
    public function setIsEs($isEs)
    {
        $this->isEs = $isEs;

        return $this;
    }

    /**
     * Get isEs
     *
     * @return boolean 
     */
    public function getIsEs()
    {
        return $this->isEs;
    }
}
