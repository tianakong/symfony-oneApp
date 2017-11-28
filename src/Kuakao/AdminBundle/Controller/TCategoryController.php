<?php

namespace Kuakao\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Kuakao\AdminBundle\Entity\TCategory;
use Kuakao\AdminBundle\Form\TCategoryType;

/**
 * 巧备考 - 栏目管理 controller.
 *
 */
class TCategoryController extends BaseController
{
    /**
     * 栏目列表页面
     * @return \Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $page =  $request->query->getInt('page', 1);

        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('KuakaoAdminBundle:TCategory')->findAll();
        $paginator = $this->get('knp_paginator');
        $tCategorys = $paginator->paginate($query, $page, 10);
        return $this->render('KuakaoAdminBundle:Category/index.html.twig', array(
            'tCategorys' => $tCategorys,
            'page' =>$page,
        ));
    }

    /**
     * 栏目名称添加
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function newAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $tCategory = new TCategory();
        $form = $this->createFormBuilder($tCategory)
            ->add('catname')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
//                $tCategory->setParentid($request->request->get('parentid'));
                $em->persist($tCategory);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '添加成功']);
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '添加失败']);
            }
        }
        $parentidData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategory')->findBy(['parentid'=>1]);

        return $this->render('KuakaoAdminBundle:Category/new.html.twig', [
            'form'=>$form->createView(),
            'parentidData'=>$parentidData
        ]);
    }

    /**
     * Finds and displays a TCategory entity.
     *
     */
    public function showAction(TCategory $tCategory)
    {
        $deleteForm = $this->createDeleteForm($tCategory);

        return $this->render('tcategory/show.html.twig', array(
            'tCategory' => $tCategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * 栏目名称修改
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $catid = $request->query->getInt('catid');
        $tCategory = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategory')->find($catid);
        $form = $this->createFormBuilder($tCategory)
            ->add('catname')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $tCategory->setParentid($request->request->get('parentid') );
                $em->persist($tCategory);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }
        //列出父级
        $parentidData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategory')->findBy(['parentid'=>1]);;

        return $this->render('KuakaoAdminBundle:Category/edit.html.twig', [
            'form'=>$form->createView(),
            'parentidData'=>$parentidData,
            'tCategory' => $tCategory,
            'catid' => $catid,
        ]);
    }

    /**
     * 栏目名称删除
     * @return JsonResponse
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $catid = $request->request->getInt('catid');
        $em = $this->getDoctrine()->getManager();
        $tCategory = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategory')->find($catid);
        $em->remove($tCategory);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }

    /**
     * Creates a form to delete a TCategory entity.
     *
     * @param TCategory $tCategory The TCategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TCategory $tCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tcategory_delete', array('id' => $tCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
