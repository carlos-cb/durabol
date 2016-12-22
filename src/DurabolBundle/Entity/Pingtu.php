<?php

namespace DurabolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pingtu
 */
class Pingtu
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
    private $foto;


    /**
     * Set foto
     *
     * @param string $foto
     * @return Pingtu
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
     * @var string
     */
    private $routing;


    /**
     * Set routing
     *
     * @param string $routing
     * @return Pingtu
     */
    public function setRouting($routing)
    {
        $this->routing = $routing;

        return $this;
    }

    /**
     * Get routing
     *
     * @return string 
     */
    public function getRouting()
    {
        return $this->routing;
    }
    /**
     * @var integer
     */
    private $shopId;


    /**
     * Set shopId
     *
     * @param integer $shopId
     * @return Pingtu
     */
    public function setShopId($shopId)
    {
        $this->shopId = $shopId;

        return $this;
    }

    /**
     * Get shopId
     *
     * @return integer 
     */
    public function getShopId()
    {
        return $this->shopId;
    }
}
