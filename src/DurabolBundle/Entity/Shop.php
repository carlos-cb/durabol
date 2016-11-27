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

    public function __toString() {
        return strval($this->id);
    }
}
