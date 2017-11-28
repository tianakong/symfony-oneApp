<?php

namespace Kuakao\AdminBundle\Controller;



use Symfony\Component\HttpFoundation\Request;
use Kuakao\AdminBundle\Entity\TInfoLog;

/**
 * TInfoLog controller.
 *
 */
class TInfoLogController extends BaseController
{
    /**
     * 短信列表
     *
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $page =  $request->query->getInt('page', 1);
        $mobile = $request->query->get('mobile');
        $where = [];
        if($mobile) {
            $where = ['mobile'=>$mobile];
        }
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('KuakaoAdminBundle:TInfoLog')->findBy($where, ['id'=>'desc']);
        $paginator = $this->get('knp_paginator');
        $logData = $paginator->paginate($query, $page, 10);

        return $this->render('KuakaoAdminBundle:TInfoLog/index.html.twig', array(
            'logData' => $logData,
            'page' => $page,
            'mobile' => $mobile,
        ));
    }
}
