<?php

namespace AppBundle\Controller;

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Message\Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Currency;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $browser = new Browser(new Curl());
        $browser->getClient()->setIgnoreErrors(false);

        $form = $this->createFormBuilder()
                ->add('currency', 'entity', array(
                    'placeholder' => 'Choose currency',
                    'class' => 'ApiBundle:Currency',
                    'property' => 'name',
                    'constraints' => array(new NotBlank(array('message' => 'Please select currency')))))
                ->add('exchangeRate', 'text', array('read_only' => true))
                ->add('surchargeRate', 'text', array('read_only' => true))
                ->add('amountPurchased', 'text', array('constraints' => array(
                        new NotBlank())))
                ->add('additional', 'text', array('read_only' => true))
                ->add('amountPaid', 'text', array('read_only' => true))
                ->add('surchargeAmount', 'text', array('read_only' => true))
                ->add('submit', 'submit')
                ->getForm();

        $form->handleRequest($request);

        

        if ($form->isValid()) {
            $data = $form->getData();
            $currency = $data['currency'];
            $order = array(
                'order' => array(
                    'currency' => $currency->getCode(),
                    'exchangeRate' => $data['exchangeRate'],
                    'surchargeRate' => $data['surchargeRate'],
                    'amountPurchased' => number_format($data['amountPurchased'], 2, '.', ''),
                    'amountPaid' => number_format($data['amountPaid'], 2, '.', ''),
                    'surchargeAmount' => number_format($data['surchargeAmount'], 2, '.', ''),
            ));

            $url = "http://currenzyworks/api/v1/orders";
            $headers = array(
                'Content-Type' => 'application/json',
            );

            $response = $browser->post($url, $headers, json_encode($order));

            if ($response->getStatusCode() == 200) {
                $this->addFlash('success', 'The item was created successfully.');
            }

            return $this->redirectToRoute('homepage', array('response' => $response), 301);
        }
        
        $orders = $browser->get("http://currenzyworks/api/v1/orders");

        $entities = json_decode($orders->getContent());

        return $this->render('default/index.html.twig', array(
                    'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..'),
                    'form' => $form->createView(),
                    'orders' => $entities
        ));
    }

}
