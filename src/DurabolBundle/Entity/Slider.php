<?php

namespace DurabolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slider
 */
class Slider
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
     * @return Slider
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
}
