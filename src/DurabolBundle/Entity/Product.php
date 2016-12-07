<?php

namespace DurabolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 */
class Product
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
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $foto;


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
     * @return Product
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
     * Set price
     *
     * @param float $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Product
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set foto
     *
     * @param string $foto
     * @return Product
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
     * @var integer
     */
    private $unit;

    /**
     * @var boolean
     */
    private $isShow;

    /**
     * @var boolean
     */
    private $isSale;

    /**
     * @var float
     */
    private $discountPrice;

    /**
     * @var \DurabolBundle\Entity\Category
     */
    private $category;


    /**
     * Set unit
     *
     * @param integer $unit
     * @return Product
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return integer 
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set isShow
     *
     * @param boolean $isShow
     * @return Product
     */
    public function setIsShow($isShow)
    {
        $this->isShow = $isShow;

        return $this;
    }

    /**
     * Get isShow
     *
     * @return boolean 
     */
    public function getIsShow()
    {
        return $this->isShow;
    }

    /**
     * Set isSale
     *
     * @param boolean $isSale
     * @return Product
     */
    public function setIsSale($isSale)
    {
        $this->isSale = $isSale;

        return $this;
    }

    /**
     * Get isSale
     *
     * @return boolean 
     */
    public function getIsSale()
    {
        return $this->isSale;
    }

    /**
     * Set discountPrice
     *
     * @param float $discountPrice
     * @return Product
     */
    public function setDiscountPrice($discountPrice)
    {
        $this->discountPrice = $discountPrice;

        return $this;
    }

    /**
     * Get discountPrice
     *
     * @return float 
     */
    public function getDiscountPrice()
    {
        return $this->discountPrice;
    }

    /**
     * Set category
     *
     * @param \DurabolBundle\Entity\Category $category
     * @return Product
     */
    public function setCategory(\DurabolBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \DurabolBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
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
     * @return Product
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
}
