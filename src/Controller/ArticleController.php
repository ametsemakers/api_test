<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use JMS\Serializer\SerializerInterface;

/* pour le create */
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="article_list")
     * @Method({"GET"})
     */
    public function listAction(SerializerInterface $serialize)
    {
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        $data = $serialize->serialize($article, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/articles/{id}", name="article_show")
     * @Method({"GET"})
     */
    public function showAction(Article $article, SerializerInterface $serialize)
    {
        $data = $serialize->serialize($article, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/articles", name="article_create")
     * @Method({"POST"})
     */
    public function createAction(Request $request, SerializerInterface $serialize)
    {
        $data = $request->getContent();
        $article = $serialize->deserialize($data, 'App\Entity\Article', 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return new Response('', Response::HTTP_CREATED);
    }
}
