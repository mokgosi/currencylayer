<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CurrencyOrder
 *
 * @ORM\Table(name="currency_order")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\CurrencyOrderRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CurrencyOrder
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
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=255)
     * @Assert\NotBlank()
     * 
     */
    private $currency;

    /**
     * @var float
     *
     * @ORM\Column(name="exchange_rate", type="decimal", precision=10, scale=2)
     * @Assert\NotBlank()
     */
    private $exchangeRate;

    /**
     * @var float
     *
     * @ORM\Column(name="surcharge_rate", type="decimal", precision=10, scale=2)
     * @Assert\NotBlank()
     */
    private $surchargeRate;

    /**
     * @var float
     *
     * @ORM\Column(name="amount_purchased", type="decimal", precision=10, scale=2)
     * @Assert\NotBlank()
     */
    private $amountPurchased;

    /**
     * @var float
     *
     * @ORM\Column(name="amount_paid", type="decimal", precision=10, scale=2)
     */
    private $amountPaid;

    /**
     * @var float
     *
     * @ORM\Column(name="surcharge_amount", type="decimal", precision=10, scale=2)
     */
    private $surchargeAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @ORM\OneToOne(targetEntity="Additional", inversedBy="order")
     */
    private $additional;

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
     * Set currency
     *
     * @param integer $currency
     * @return CurrencyOrder
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set exchangeRate
     *
     * @param decimal $exchangeRate
     * @return CurrencyOrder
     */
    public function setExchangeRate($exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    /**
     * Get exchangeRate
     *
     * @return decimal 
     */
    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }

    /**
     * Set surchargeRate
     *
     * @param decimal $surchargeRate
     * @return CurrencyOrder
     */
    public function setSurchargeRate($surchargeRate)
    {
        $this->surchargeRate = $surchargeRate;

        return $this;
    }

    /**
     * Get surchargeRate
     *
     * @return decimal
     */
    public function getSurchargeRate()
    {
        return $this->surchargeRate;
    }

    /**
     * Set amountPurchased
     *
     * @param float $amountPurchased
     * @return CurrencyOrder
     */
    public function setAmountPurchased($amountPurchased)
    {
        $this->amountPurchased = $amountPurchased;

        return $this;
    }

    /**
     * Get amountPurchased
     *
     * @return float 
     */
    public function getAmountPurchased()
    {
        return $this->amountPurchased;
    }

    /**
     * Set amountPaid
     *
     * @param float $amountPaid
     * @return CurrencyOrder
     */
    public function setAmountPaid($amountPaid)
    {
        $this->amountPaid = $amountPaid;

        return $this;
    }

    /**
     * Get amountPaid
     *
     * @return float 
     */
    public function getAmountPaid()
    {
        return $this->amountPaid;
    }

    /**
     * Set surchargeAmount
     *
     * @param float $surchargeAmount
     * @return CurrencyOrder
     */
    public function setSurchargeAmount($surchargeAmount)
    {
        $this->surchargeAmount = $surchargeAmount;

        return $this;
    }

    /**
     * Get surchargeAmount
     *
     * @return float 
     */
    public function getSurchargeAmount()
    {
        return $this->surchargeAmount;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return CurrencyOrder
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    /**
     * Set additional
     *
     * @param \ApiBundle\Entity\Additional $additional
     * @return CurrencyOrder
     */
    public function setAdditional(\ApiBundle\Entity\Additional $additional = null)
    {
        $this->additional = $additional;
        $additional->setOrder($this);
        return $this;
    }

    /**
     * Get additional
     *
     * @return \ApiBundle\Entity\Additional 
     */
    public function getAdditional()
    {
        return $this->additional;
    }

}
