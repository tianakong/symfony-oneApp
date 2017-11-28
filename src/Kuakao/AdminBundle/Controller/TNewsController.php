<?php

namespace Kuakao\AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Kuakao\AdminBundle\Entity\TNews;
use Symfony\Component\HttpFoundation\Request;

/**
 * TNews controller.
 *
 */
class TNewsController extends Controller
{
    /**
     * Lists all TNews entities.
     *
     */
    public function indexAction(Request $request)
    {
        $page = $request->get('page', 1);
        $em = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TNews');

        $newsData = array();


        $newsData = $em->findAll();

        $paginator = $this->get('knp_paginator');
        $newsData = $paginator->paginate($newsData, $page, 2);

        return $this->render('KuakaoAdminBundle:News:index.html.twig', array(
            'tNews' => $newsData,
        ));
    }

    /**
     * Finds and displays a TNews entity.
     *
     */
    public function showAction(TNews $tNews)
    {

        return $this->render('tnews/show.html.twig', array(
            'tNews' => $tNews,
        ));
    }
}
