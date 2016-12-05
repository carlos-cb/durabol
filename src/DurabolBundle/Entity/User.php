<?php

namespace DurabolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var int
     */
    protected $id;


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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function __toString() {
        return strval($this->id);
    }

    public function getOrderInfoSum()
    {
        $orderInfos = $this->orderInfos;
        $sum = 0;
        foreach($orderInfos as $orderInfo)
        {
            if(($orderInfo->getState() != "未付款") && ($orderInfo->getState() != "已取消") && $orderInfo->getParent())
            {
                $sum += $orderInfo->getTotalPrice();
            }
        }

        return $sum;
    }

    /**
     * @var integer
     */
    private $isAutonomo;

    /**
     * @var \DurabolBundle\Entity\Cart
     */
    private $cart;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $orderInfos;


    /**
     * Set isAutonomo
     *
     * @param integer $isAutonomo
     * @return User
     */
    public function setIsAutonomo($isAutonomo)
    {
        $this->isAutonomo = $isAutonomo;

        return $this;
    }

    /**
     * Get isAutonomo
     *
     * @return integer 
     */
    public function getIsAutonomo()
    {
        return $this->isAutonomo;
    }

    /**
     * Set cart
     *
     * @param \DurabolBundle\Entity\Cart $cart
     * @return User
     */
    public function setCart(\DurabolBundle\Entity\Cart $cart = null)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart
     *
     * @return \DurabolBundle\Entity\Cart 
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Add orderInfos
     *
     * @param \DurabolBundle\Entity\OrderInfo $orderInfos
     * @return User
     */
    public function addOrderInfo(\DurabolBundle\Entity\OrderInfo $orderInfos)
    {
        $this->orderInfos[] = $orderInfos;

        return $this;
    }

    /**
     * Remove orderInfos
     *
     * @param \DurabolBundle\Entity\OrderInfo $orderInfos
     */
    public function removeOrderInfo(\DurabolBundle\Entity\OrderInfo $orderInfos)
    {
        $this->orderInfos->removeElement($orderInfos);
    }

    /**
     * Get orderInfos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrderInfos()
    {
        return $this->orderInfos;
    }
    /**
     * @var integer
     */
    private $discount;


    /**
     * Set discount
     *
     * @param integer $discount
     * @return User
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return integer 
     */
    public function getDiscount()
    {
        return $this->discount;
    }
    /**
     * @var \DurabolBundle\Entity\Data
     */
    private $data;


    /**
     * Set data
     *
     * @param \DurabolBundle\Entity\Data $data
     * @return User
     */
    public function setData(\DurabolBundle\Entity\Data $data = null)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \DurabolBundle\Entity\Data 
     */
    public function getData()
    {
        return $this->data;
    }
}
