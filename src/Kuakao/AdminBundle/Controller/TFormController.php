<?php

namespace Kuakao\AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Kuakao\AdminBundle\Entity\TForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * 巧备考 - 表单管理 controller.
 * @author hejiangtao<656669865@qq.com> 2016-05
 */
class TFormController extends BaseController
{
    /**
     * 表单列表
     * @return \Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $username = $request->get('username');
        $where = [];
        if($username) {
            $where = ['username'=>$username];
        }
        $page =  $request->query->getInt('page', 1);
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('KuakaoAdminBundle:TForm')->findBy($where, ['id'=>'desc']);
        $paginator = $this->get('knp_paginator');
        $tForms = $paginator->paginate($query, $page, 10);
        return $this->render('KuakaoAdminBundle:Form/index.html.twig', array(
            'tForms' => $tForms,
            'page' =>$page,
            'username' =>$username
        ));
    }

    /**
     * 删除表单列表信息
     * @param Request $request
     * @return JsonResponse
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TForm')->find($id);
        $em->remove($product);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
        
    }

    /**
     * Finds and displays a TForm entity.
     *
     */
    public function showAction(TForm $tForm)
    {
        return $this->render('tform/show.html.twig', array(
            'tForm' => $tForm,
        ));
    }
}
