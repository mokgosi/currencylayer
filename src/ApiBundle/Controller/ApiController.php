<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Entity\Currency;

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
    public function getListAction()
    {
        $currencyService = $this->get('api_layer');
        $response = $currencyService->query('list');
        $list = json_decode($response->getContent());
        $em = $this->getDoctrine()->getManager();
        
        foreach ($list->currencies as $key => $item) {
            $entity = new Currency();
            $entity->setCode($key);
            $entity->setName($item);
            $entity->setCreatedAt(new \DateTime());
            $entity->setUpdatedAt(new \DateTime());
            $em->persist($entity);
        }
        $em->flush();
        $em->clear();

        $view = $this->view($response);
        return $this->handleView($view);
    }

}
