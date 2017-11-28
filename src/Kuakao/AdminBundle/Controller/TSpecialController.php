<?php

namespace Kuakao\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Kuakao\AdminBundle\Entity\TSpecial;
use Kuakao\AdminBundle\Form\TSpecialType;

/**
 * 巧备考 - 专题管理 controller.
 *
 */
class TSpecialController extends BaseController
{
    /**
     * 专题列表
     * @return \Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $title = $request->get('name');
        $where = [];
        if($title) {
            $where = ['title'=>$title];
        }
        $page = $request->query->getInt('page', 1);
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('KuakaoAdminBundle:TSpecial')->findBy($where, ['id'=>'desc']);
        $paginator = $this->get('knp_paginator');
        $tSpecials = $paginator->paginate($query, $page, 10);
        return $this->render('KuakaoAdminBundle:Special/index.html.twig', array(
            'tSpecials' => $tSpecials,
            'page' => $page,
            'title' => $title,
        ));
    }

    /**
     * 专题添加
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function newAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $tSpecial = new TSpecial();
        $form = $this->createForm('Kuakao\AdminBundle\Form\TSpecialType', $tSpecial);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $path = "upload/special";
                $tSpecial = $form->getData();
                $image = $tSpecial->getImage();
                if($image){
                    $image = $this->get('file.save_file_handler')->save( $image , $path );
                    $tSpecial->setImage($image);
                }
                $em = $this->getDoctrine()->getManager();
                $tSpecial->setTime(time());
                $tSpecial->setUsername($this->username);
                $em->persist($tSpecial);
                $em->flush();
//                return new JsonResponse(['status'=>1, 'info'=>'添加成功']);
//                return $this->redirectToRoute('kuakao_admin_special_edit', array('id' => $tSpecial->getId()));
                return $this->redirectToRoute('kuakao_admin_special_index');
            }
            else
            {
                return new JsonResponse(['status'=>0, 'info'=>'添加失败']);
            }
        }

        return $this->render('KuakaoAdminBundle:Special/new.html.twig', [
            'tSpecial' => $tSpecial,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a TSpecial entity.
     *
     */
    public function showAction(TSpecial $tSpecial)
    {
        $deleteForm = $this->createDeleteForm($tSpecial);

        return $this->render('tspecial/show.html.twig', array(
            'tSpecial' => $tSpecial,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * 专题修改
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->query->getInt('id');
        $tSpecial = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSpecial')->find($id);
        $form = $this->createFormBuilder($tSpecial)
            ->add('title')
            ->add('description','textarea')
            ->add('image','hidden')
            ->add('new_image','file')
            ->add('content')
            ->add('status')
            ->add('url')
//            ->add('time')
//            ->add('username')
            ->add('submit','submit',array("attr"=>array("formnovalidate"=>"formnovalidate","value"=>"Edit")))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $path = "upload/special";
                $data = $form->getData();
                $new_image = $data->getNewImage();
                if($new_image)
                {
                    $image = $data->getImage();
                    $this->get('file.save_file_handler')->remove( $image);
                    $new_image = $this->get('file.save_file_handler')->save( $new_image , $path );
                    $data->setImage($new_image);
                }
                $em = $this->getDoctrine()->getManager();
                $tSpecial->setTime(time());
                $tSpecial->setUsername($this->username);
                $em->persist($tSpecial);
                $em->flush();
//                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
                return $this->redirectToRoute('kuakao_admin_special_index');
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }
        return $this->render('KuakaoAdminBundle:Special/edit.html.twig', [
            'form'=>$form->createView(),
            'tSpecial' => $tSpecial,
            'id' => $id,
        ]);
    }

    /**
     * 专题删除
     * @return JsonResponse
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $tSpecial = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSpecial')->find($id);
        $em->remove($tSpecial);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);        
    }

    /**
     * Creates a form to delete a TSpecial entity.
     *
     * @param TSpecial $tSpecial The TSpecial entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TSpecial $tSpecial)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('special_delete', array('id' => $tSpecial->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
