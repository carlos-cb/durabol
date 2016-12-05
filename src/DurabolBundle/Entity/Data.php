<?php

namespace DurabolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Data
 */
class Data
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $receivername;


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
     * Set receivername
     *
     * @param string $receivername
     * @return Data
     */
    public function setReceivername($receivername)
    {
        $this->receivername = $receivername;

        return $this;
    }

    /**
     * Get receivername
     *
     * @return string 
     */
    public function getReceivername()
    {
        return $this->receivername;
    }
    /**
     * @var string
     */
    private $receiverphone;

    /**
     * @var string
     */
    private $receiveradress;

    /**
     * @var string
     */
    private $receiverprovince;

    /**
     * @var string
     */
    private $receivercity;

    /**
     * @var string
     */
    private $receiverpostcode;

    /**
     * @var string
     */
    private $receivershuihao;

    /**
     * @var boolean
     */
    private $receivergerenshui;

    /**
     * @var \DurabolBundle\Entity\User
     */
    private $user;


    /**
     * Set receiverphone
     *
     * @param string $receiverphone
     * @return Data
     */
    public function setReceiverphone($receiverphone)
    {
        $this->receiverphone = $receiverphone;

        return $this;
    }

    /**
     * Get receiverphone
     *
     * @return string 
     */
    public function getReceiverphone()
    {
        return $this->receiverphone;
    }

    /**
     * Set receiveradress
     *
     * @param string $receiveradress
     * @return Data
     */
    public function setReceiveradress($receiveradress)
    {
        $this->receiveradress = $receiveradress;

        return $this;
    }

    /**
     * Get receiveradress
     *
     * @return string 
     */
    public function getReceiveradress()
    {
        return $this->receiveradress;
    }

    /**
     * Set receiverprovince
     *
     * @param string $receiverprovince
     * @return Data
     */
    public function setReceiverprovince($receiverprovince)
    {
        $this->receiverprovince = $receiverprovince;

        return $this;
    }

    /**
     * Get receiverprovince
     *
     * @return string 
     */
    public function getReceiverprovince()
    {
        return $this->receiverprovince;
    }

    /**
     * Set receivercity
     *
     * @param string $receivercity
     * @return Data
     */
    public function setReceivercity($receivercity)
    {
        $this->receivercity = $receivercity;

        return $this;
    }

    /**
     * Get receivercity
     *
     * @return string 
     */
    public function getReceivercity()
    {
        return $this->receivercity;
    }

    /**
     * Set receiverpostcode
     *
     * @param string $receiverpostcode
     * @return Data
     */
    public function setReceiverpostcode($receiverpostcode)
    {
        $this->receiverpostcode = $receiverpostcode;

        return $this;
    }

    /**
     * Get receiverpostcode
     *
     * @return string 
     */
    public function getReceiverpostcode()
    {
        return $this->receiverpostcode;
    }

    /**
     * Set receivershuihao
     *
     * @param string $receivershuihao
     * @return Data
     */
    public function setReceivershuihao($receivershuihao)
    {
        $this->receivershuihao = $receivershuihao;

        return $this;
    }

    /**
     * Get receivershuihao
     *
     * @return string 
     */
    public function getReceivershuihao()
    {
        return $this->receivershuihao;
    }

    /**
     * Set receivergerenshui
     *
     * @param boolean $receivergerenshui
     * @return Data
     */
    public function setReceivergerenshui($receivergerenshui)
    {
        $this->receivergerenshui = $receivergerenshui;

        return $this;
    }

    /**
     * Get receivergerenshui
     *
     * @return boolean 
     */
    public function getReceivergerenshui()
    {
        return $this->receivergerenshui;
    }

    /**
     * Set user
     *
     * @param \DurabolBundle\Entity\User $user
     * @return Data
     */
    public function setUser(\DurabolBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \DurabolBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
