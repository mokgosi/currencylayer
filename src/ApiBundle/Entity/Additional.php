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
     * @var decimal
     *
     * @ORM\Column(name="discount_amount", type="decimal", precision=10, scale=6)
     */
    private $discountAmount;
    
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
}
