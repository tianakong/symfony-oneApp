<?php

namespace Kuakao\AdminBundle\Controller;

use Kuakao\AdminBundle\Entity\TAdmin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * TAdmin controller.
 * @author wangbingang<bingangwang@kuakao.com>
 *
 */
class TAdminController extends BaseController
{
    /**
     * 管理员列表
     *
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $page =  $request->query->getInt('page', 1);
        $username = $request->query->get('username');
        $where = [];
        if($username) {
            $where = ['username'=>$username];
        }
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('KuakaoAdminBundle:TAdmin')->findBy($where, ['userid'=>'desc']);
        $paginator = $this->get('knp_paginator');
        $adminData = $paginator->paginate($query, $page, 10);
        //查询角色名称
        $roleData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAdminRole')->getData();
        return $this->render('KuakaoAdminBundle:Admin/index.html.twig', array(
            'adminData' => $adminData,
            'roleData' => $roleData,
            'page' => $page,
            'username' => $username,
        ));
    }

    /**
     * 添加管理员
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $admin = new TAdmin();
        $form = $this->createFormBuilder($admin)
            ->add('username')
            ->add('password')
            ->add('email')
            ->add('realname')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                if($request->request->get('password') !== $request->request->get('confirm_password')) {
                    return new JsonResponse(['status' =>0, 'info' => '重复密码不正确']);
                }
                $em = $this->getDoctrine()->getManager();
                $admin->setRoleid($request->request->get('roleid'));
                $em->persist($admin);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '添加成功']);
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '添加失败']);
            }
        }
        //列出角色
        $roleData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAdminRole')->findBy(['disabled'=>1]);

        return $this->render('KuakaoAdminBundle:Admin/add.html.twig', ['form'=>$form->createView(), 'roleData'=>$roleData]);
    }

    /**
     * 编辑管理员
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->query->getInt('id');
        $adminData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAdmin')->find($id);
        $form = $this->createFormBuilder($adminData)
            ->add('username')
            ->add('password')
            ->add('email')
            ->add('realname')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $adminData->setRoleid( $request->request->get('roleid') );
                $em->persist($adminData);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }
        //列出角色
        $roleData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAdminRole')->findBy(['disabled'=>1]);
        return $this->render('KuakaoAdminBundle:Admin/edit.html.twig', [
            'form'=>$form->createView(),
            'roleData'=>$roleData,
            'adminData' => $adminData,
            'id' => $id,
        ]);
    }

    /**
     * 删除管理员
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAdmin')->find($id);
        $em->remove($product);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }


}
