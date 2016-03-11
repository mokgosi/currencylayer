<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Additional
 *
 * @ORM\Table(name="additional")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\AdditionalRepository")
 */
class Additional
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="currency_order_id", type="integer")
     */
    private $orderId;

    /**
     * @var float
     *
     * @ORM\Column(name="discount_amount", type="float")
     */
    private $discountAmount;
    
    /**
     * @ORM\OneToOne(targetEntity="CurrencyOrder", mappedBy="additional")
     * @ORM\JoinColumn(name="currency_order_id", referencedColumnName="id")
     */
    private $order;


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
     * Set discountAmount
     *
     * @param float $discountAmount
     * @return Additional
     */
    public function setDiscountAmount($discountAmount)
    {
        $this->discountAmount = $discountAmount;

        return $this;
    }

    /**
     * Get discountAmount
     *
     * @return float 
     */
    public function getDiscountAmount()
    {
        return $this->discountAmount;
    }

    /**
     * Set order
     *
     * @param \ApiBundle\Entity\CurrencyOrder $order
     * @return Additional
     */
    public function setCurrencyOrder(\ApiBundle\Entity\CurrencyOrder $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \ApiBundle\Entity\CurrencyOrder 
     */
    public function getCurrencyOrder()
    {
        return $this->order;
    }
}
