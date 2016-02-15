<?php

namespace AppBundle\Controller;

use Buzz\Browser;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

//        $currencies = $em->getRepository('AppBundle:Currency')->findBy(array(), array('id' => 'desc'));

        $browser = new Browser();
        
        $response = $browser->get('http://apilayer.net/api/list?access_key=534871545e5d0de0a90259fe7d2b5795');

        $form = $this->createFormBuilder()
                ->add('from', 'entity', array(
                    'class' => 'AppBundle:Currency',
                    'property' => 'name'))
                ->add('to', 'entity', array(
                    'class' => 'AppBundle:Currency',
                    'property' => 'name'))
                ->add('result', 'text')
                ->getForm();

        return $this->render('default/index.html.twig', array(
                    'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..'),
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        $twitter = $this->get('endroid.twitter');

        $em = $this->getDoctrine()->getManager();

        $tweetStatuses = array();

        $form = $this->createFormBuilder()
                ->add('hashtag', 'text')
                ->add('search', 'submit')
                ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $response = $twitter->query("search/tweets", 'GET', 'json', array('q' => $data['hashtag']));

            $tweets = json_decode($response->getContent());

            foreach ($tweets->statuses as $key => $tweet) {

                $entity = new Tweet();
                $entity->setText($tweet->text);
                $entity->setCreatedAt(new DateTime($tweet->created_at));
                $entity->setUserName($tweet->user->name);
                $entity->setScreenName($tweet->user->name);
                $entity->setProfileImg($tweet->user->profile_image_url);
                $entity->setRetweetCount($tweet->retweet_count);
                $entity->setFavouriteCount($tweet->favorite_count);
                $entity->setGeo($tweet->geo);
                $em->persist($entity);
            }
            $em->flush();
            $em->clear();
            $tweetStatuses = $tweets->statuses;
        }
        return $this->render('default/search.html.twig', array(
                    'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..'),
                    'form' => $form->createView(),
                    'tweets' => $tweetStatuses
        ));
    }

}
