<?php

namespace DurabolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Shop
 */
class Shop
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $foto;

    /**
     * @var float
     */
    private $minCoste;

    /**
     * @var string
     */
    private $nameShort;


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
     * Set name
     *
     * @param string $name
     * @return Shop
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

    /**
     * Set description
     *
     * @param string $description
     * @return Shop
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
     * Set foto
     *
     * @param string $foto
     * @return Shop
     */
    public function setFoto($foto)
    {
        if(!empty($foto)){
            $this->foto = $foto;
        }

        return $this;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set minCoste
     *
     * @param float $minCoste
     * @return Shop
     */
    public function setMinCoste($minCoste)
    {
        $this->minCoste = $minCoste;

        return $this;
    }

    /**
     * Get minCoste
     *
     * @return float 
     */
    public function getMinCoste()
    {
        return $this->minCoste;
    }

    /**
     * Set nameShort
     *
     * @param string $nameShort
     * @return Shop
     */
    public function setNameShort($nameShort)
    {
        $this->nameShort = $nameShort;

        return $this;
    }

    /**
     * Get nameShort
     *
     * @return string 
     */
    public function getNameShort()
    {
        return $this->nameShort;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categorys;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categorys = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add categorys
     *
     * @param \DurabolBundle\Entity\Category $categorys
     * @return Shop
     */
    public function addCategory(\DurabolBundle\Entity\Category $categorys)
    {
        $this->categorys[] = $categorys;

        return $this;
    }

    /**
     * Remove categorys
     *
     * @param \DurabolBundle\Entity\Category $categorys
     */
    public function removeCategory(\DurabolBundle\Entity\Category $categorys)
    {
        $this->categorys->removeElement($categorys);
    }

    /**
     * Get categorys
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategorys()
    {
        return $this->categorys;
    }

    public function getCategory($id)
    {
        $categories = $this->getCategorys();
        return $categories[$id] ? $categories[$id] : $categories[0];
    }

    public function getCategoryNames()
    {
        $categories = $this->getCategorys();
        $i = 0;
        $categoryNames = Array();
        foreach ($categories as $category)
        {
            $categoryNames[$i] = $category->getName();
            $i++;
        }
        return $categoryNames;
    }
    
    public function getCategoryEsNames()
    {
        $categories = $this->getCategorys();
        $i = 0;
        $categoryEsNames = Array();
        foreach ($categories as $category)
        {
            $categoryEsNames[$i] = $category->getNameEs();
            $i++;
        }
        return $categoryEsNames;
    }
    
    public function getProductNum()
    {
        $categories = $this->getCategorys();
        $productNum = 0;
        foreach ($categories as $category)
        {
            $productNum += $category->getProducts()->count();
        }
        return $productNum;
    }

    public function __toString() {
        return strval($this->id);
    }
    /**
     * @var boolean
     */
    private $isTop;


    /**
     * Set isTop
     *
     * @param boolean $isTop
     * @return Shop
     */
    public function setIsTop($isTop)
    {
        $this->isTop = $isTop;

        return $this;
    }

    /**
     * Get isTop
     *
     * @return boolean 
     */
    public function getIsTop()
    {
        return $this->isTop;
    }
    /**
     * @var integer
     */
    private $turn;


    /**
     * Set turn
     *
     * @param integer $turn
     * @return Shop
     */
    public function setTurn($turn)
    {
        $this->turn = $turn;

        return $this;
    }

    /**
     * Get turn
     *
     * @return integer 
     */
    public function getTurn()
    {
        return $this->turn;
    }
    /**
     * @var boolean
     */
    private $isPayOnline;


    /**
     * Set isPayOnline
     *
     * @param boolean $isPayOnline
     * @return Shop
     */
    public function setIsPayOnline($isPayOnline)
    {
        $this->isPayOnline = $isPayOnline;

        return $this;
    }

    /**
     * Get isPayOnline
     *
     * @return boolean 
     */
    public function getIsPayOnline()
    {
        return $this->isPayOnline;
    }
    /**
     * @var string
     */
    private $nameEs;

    /**
     * @var string
     */
    private $descriptionEs;


    /**
     * Set nameEs
     *
     * @param string $nameEs
     * @return Shop
     */
    public function setNameEs($nameEs)
    {
        $this->nameEs = $nameEs;

        return $this;
    }

    /**
     * Get nameEs
     *
     * @return string 
     */
    public function getNameEs()
    {
        return $this->nameEs;
    }

    /**
     * Set descriptionEs
     *
     * @param string $descriptionEs
     * @return Shop
     */
    public function setDescriptionEs($descriptionEs)
    {
        $this->descriptionEs = $descriptionEs;

        return $this;
    }

    /**
     * Get descriptionEs
     *
     * @return string 
     */
    public function getDescriptionEs()
    {
        return $this->descriptionEs;
    }
}
