<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\CurrencyOrder;
use ApiBundle\Entity\Additional;
use ApiBundle\Form\CurrencyOrderType;
use DateTime;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

//use ApiBundle\Services\GoogleSearch;

class ApiController extends FOSRestController
{

    /**
     * This is the documentation description of your method, it will appear
     * on a specific pane. It will read all the text until the first
     * annotation.
     *
     * @ApiDoc(
     *   resource=true,
     *   description="This is a description of your API method.",
     *   https=true
     * )
     */
    public function getCurrenciesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $currencies = $em->getRepository('ApiBundle:Currency')->findAll();
        $view = $this->view($currencies);
        return $this->handleView($view);
    }

    /**
     * Get currency by code
     *
     * @ApiDoc(
     *   resource=true,
     *   description="This method returns a single currency using currency 3 letter code. e.g. USD",
     *   requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="currency id"
     *      }
     *   },
     *   https=true
     * )
     */
    public function getCurrencyAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $currency = $em->getRepository('ApiBundle:Currency')->findOneBy(array('id' => $id));
        $view = $this->view($currency);
        return $this->handleView($view);
    }

    /**
     * Get all currency orders.
     *
     * @ApiDoc(
     *   resource=true,
     *   description="This method returns a single currency using id",
     *   
     * )
     */
    public function getOrdersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $currencies = $em->getRepository('ApiBundle:CurrencyOrder')->findBy(array(), array('id' => 'DESC'));
        $view = $this->view($currencies);
        return $this->handleView($view);
    }

    /**
     * Create order form submitted data. 
     * Return calculated ZAR amount to pay for a currency order.
     * 
     * @ApiDoc(
     *   resource = true,
     *   description = "This is a description of your API method.",
     *   https = true,
     *   input = {"class"="ApiBundle\Form\CurrencyOrderType", "options" = {"method" = "POST"}},
     *   output="ApiBundle\Entity\CurrencyOrder",
     *   statusCodes = {
     *       200 = "Returned when successful",
     *       400 = "Returned when the form has errors"
     *   }
     * )
     * 
     * @param Request $request the request object
     * 
     * @return Response
     */
    public function postOrderAction(Request $request)
    {
        $form = $this->createForm(new CurrencyOrderType(), new CurrencyOrder());

        $form->submit(($request->request->get($form->getName())));

        if ($form->isValid()) {
            $order = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $currency = $em->getRepository('ApiBundle:Currency')->findOneBy(array('code' => $order->getCurrency()));
            if (preg_match('/^discount/', $currency->getAdditional())) {
                $discount = $this->createDiscount($order, $currency);
                $order->setAdditional($discount);
            }
            $em->persist($order);
            $em->flush();
            $view = $this->view($order);
            return $this->handleView($view);
        }

        $view = $this->view($form, 400);
        return $this->handleView($view);
    }

    public function createDiscount($order, $currency)
    {
        $em = $this->getDoctrine()->getManager();
        $discount = new Additional();
        $exchangeAmount = $this->calculateBaseAmount($order->getAmountPurchased(), $order->getExchangeRate());
        $surchargeAmount = $this->calculateSurchargeAmount($order->getSurchargeRate(), $exchangeAmount);
        $surchargedTotal = $this->calculateSurchargedTotal($exchangeAmount, $surchargeAmount);
        $discountAmount = $this->calculateDiscountAmount($surchargedTotal, $currency->getAdditional());
        $discount->setDiscountAmount($discountAmount);
        $em->persist($discount);
        $em->flush();
        return $discount;
    }

    /**
     * This is the documentation description of your method, it will appear
     * on a specific pane. It will read all the text until the first
     * annotation.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get amount to pay - given these parameters."
     * )
     * @QueryParam(name="amount")
     * @QueryParam(name="exchangeRate")
     * @QueryParam(name="surchargeRate")
     * @QueryParam(name="additional")
     * 
     */
    public function getTotalAction(ParamFetcher $paramFetcher)
    {
        $amount = $paramFetcher->get('amount');
        $exchangeRate = $paramFetcher->get('exchangeRate');
        $surchargeRate = $paramFetcher->get('surchargeRate');
        $additional = $paramFetcher->get('additional');

        $exchangeAmount = $this->calculateBaseAmount($amount, $exchangeRate);
        $surchargeAmount = $this->calculateSurchargeAmount($surchargeRate, $exchangeAmount);
        $surchargedTotal = $this->calculateSurchargedTotal($exchangeAmount, $surchargeAmount);
        $finalAmountToPay = $this->calculateDiscountedTotal($surchargedTotal, $additional);

        $calculations = array(
            'amountPaid' => $finalAmountToPay,
            'surchargeAmount' => $surchargeAmount);

        $view = $this->view($calculations);
        return $this->handleView($view);
    }

    /**
     * Calculate exact amount of ZAR to pay for a requested foreign currency -
     * based on current exchange rate.
     * 
     * @param type $amount
     * @param type $exchangeRate
     */
    public function calculateBaseAmount($amount, $exchangeRate)
    {
        $amount *= $this->utilReverseExchangeRate($exchangeRate);
        return $amount;
    }

    /**
     * Calculate surcharged amount.
     * 
     * @param type $amount
     * @param type $surchargeAmount
     * @return type
     */
    public function calculateSurchargedTotal($amount, $surchargeAmount)
    {
        $amount += $surchargeAmount;
        return $amount;
    }

    /**
     * Calculate discounted amount.
     * 
     * @param type $amount 
     * @param type $additional
     * 
     */
    public function calculateDiscountedTotal($amount, $additional)
    {
        if (preg_match('/^discount/', $additional)) {
            $discountArray = explode(' ', $additional);
            $discountPercentage = str_replace('%', '', $discountArray[2]);
            $amount -= $amount * ($discountPercentage / 100);
        }
        return $amount;
    }

    /**
     * Calculate discounted total amount.
     * 
     * @param type $amount 
     * @param type $additional
     * 
     */
    public function calculateDiscountAmount($amount, $additional)
    {
        $discountArray = explode(' ', $additional);
        $discountPercentage = str_replace('%', '', $discountArray[2]);
        $discount = $amount * ($discountPercentage / 100);
        return $discount;
    }

    /**
     * Calculate surcharge amount.
     * 
     * @param type $surchargeRate
     * @param type $amount
     * @return type
     */
    public function calculateSurchargeAmount($surchargeRate, $amount)
    {
        $amount *= ($surchargeRate / 100);
        return $amount;
    }

    /**
     * Util - Reverse exchange rate to get accurate rate. 
     * Free services out there don't allow this for free subscribers 
     * 
     * @param type $exchangeRate
     * @return type
     */
    public function utilReverseExchangeRate($exchangeRate)
    {
        return 1 / $exchangeRate;
    }

}
