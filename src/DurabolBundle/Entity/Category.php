<?php

namespace DurabolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 */
class Category
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
     * @return Category
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
     * @return Category
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $products;

    /**
     * @var \DurabolBundle\Entity\Shop
     */
    private $shop;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add products
     *
     * @param \DurabolBundle\Entity\Product $products
     * @return Category
     */
    public function addProduct(\DurabolBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \DurabolBundle\Entity\Product $products
     */
    public function removeProduct(\DurabolBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set shop
     *
     * @param \DurabolBundle\Entity\Shop $shop
     * @return Category
     */
    public function setShop(\DurabolBundle\Entity\Shop $shop = null)
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Get shop
     *
     * @return \DurabolBundle\Entity\Shop 
     */
    public function getShop()
    {
        return $this->shop;
    }
    /**
     * @var string
     */
    private $foto;


    /**
     * Set foto
     *
     * @param string $foto
     * @return Category
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
     * @return Category
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
