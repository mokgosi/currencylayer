<?php

namespace ApiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCurrencyData  extends AbstractFixture  implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $currencies = array('USD' =>'United States Dollar', 'GBP'=>'British Pound Sterling', 'EUR'=>'Euro', 'KES' => 'Kenyan Shilling');
        $rates      = array('USD' =>'0.0808279', 'GBP'=>'0.0527032', 'EUR'=>'0.0718710', 'KES' => '7.81498');
        $surcharge  = array('USD' =>'7.5', 'GBP'=>'5', 'EUR'=>'5', 'KES' => '2.5');
        $additional = array('USD' =>'null', 'GBP'=>'email', 'EUR'=>'discount - 2%', 'KES' => 'no action');
        
        foreach ($currencies as $key => $value) {
            $rate = new \ApiBundle\Entity\Currency();
            $rate->setCode($key);
            $rate->setName($value);
            $rate->setExchangeRate($rates[$key]);
            $rate->setSurchargeRate($surcharge[$key]);
            $rate->setAdditional($additional[$key]);
            $rate->setCreatedAt(new \DateTime('now'));
            $rate->setUpdatedAt(new \DateTime('now'));
            $manager->persist($rate);
        }
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
