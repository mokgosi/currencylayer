<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Currency
 *
 * @ORM\Table(name="currency")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\CurrencyRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Currency
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
     * @ORM\Column(name="code", type="string", length=3, unique=true)
     * @Assert\Length(min=3)
     * @Assert\Length(max=3)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="exchange_rate", type="float")
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     */
    private $exchangeRate;

    /**
     * @var float
     *
     * @ORM\Column(name="surcharge_rate", type="float")
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     */
    private $surchargeRate;

    /**
     * @var string
     *
     * @ORM\Column(name="additional", type="string", length=255, nullable=true)
     */
    private $additional;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Assert\DateTime()
     */
    private $updatedAt;

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
     * Set code
     *
     * @param string $code
     * @return Currency
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
     * Set name
     *
     * @param string $name
     * @return Currency
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
     * Set exchangeRate
     *
     * @param string $exchangeRate
     * @return Currency
     */
    public function setExchangeRate($exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    /**
     * Get exchangeRate
     *
     * @return string 
     */
    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }

    /**
     * Set surcharge
     *
     * @param string $surchargeRate
     * @return Currency
     */
    public function setSurchargeRate($surchargeRate)
    {
        $this->surchargeRate = $surchargeRate;

        return $this;
    }

    /**
     * Get surchargeRate
     *
     * @return string 
     */
    public function getSurchargeRate()
    {
        return $this->surchargeRate;
    }

    /**
     * Set additional
     *
     * @param string $additional
     * @return Currency
     */
    public function setAdditional($additional)
    {
        $this->additional = $additional;

        return $this;
    }

    /**
     * Get Additional
     *
     * @return string 
     */
    public function getAdditional()
    {
        return $this->additional;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Currency
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Currency
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->setUpdatedAt(new \DateTime('now'));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

}
