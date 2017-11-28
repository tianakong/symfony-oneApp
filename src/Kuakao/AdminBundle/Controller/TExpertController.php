<?php

namespace Kuakao\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Kuakao\AdminBundle\Entity\TExpert;
use Kuakao\AdminBundle\Form\TExpertType;

/**
 * 巧备考 - 专家管理 controller.
 * @author hejiangtao<656669865@qq.com> 2016-05
 */
class TExpertController extends BaseController
{
    /**
     * 专家列表
     * @return \Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $name = $request->get('name');
        $where = [];
        if($name) {
            $where = ['name'=>$name];
        }
        $page = $request->query->getInt('page', 1);
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('KuakaoAdminBundle:TExpert')->findBy($where, ['id'=>'desc']);
        $paginator = $this->get('knp_paginator');
        $tExperts = $paginator->paginate($query, $page, 20);
        return $this->render('KuakaoAdminBundle:Expert/index.html.twig', array(
            'tExperts' => $tExperts,
            'page' => $page,
            'name' => $name,
        ));
    }

    /**
     * 添加专家
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function newAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $tExpert = new TExpert();
        $form = $this->createForm('Kuakao\AdminBundle\Form\TExpertType', $tExpert);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $path = "upload/expert";
                $tExpert = $form->getData();

                $headpath = $tExpert->getHeadpath();
                if($headpath){
                    $headpath = $this->get('file.save_file_handler')->save( $headpath , $path );
                    $tExpert->setHeadpath($headpath);
                }
                $tExpert->setUsername($this->username);
                //$tExpert->setAddTime(time());
                $em->persist($tExpert);
                $em->flush();
                //return new JsonResponse(['status'=>1, 'info'=>'添加成功']);
                return $this->redirectToRoute('kuakao_admin_expert_edit', array('id' => $tExpert->getId()));
            }
            else
            {
                return new JsonResponse(['status'=>0, 'info'=>'添加失败']);
            }
        }

        return $this->render('KuakaoAdminBundle:Expert/new.html.twig', [
            'tExpert' => $tExpert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * 专家显示
     * @return \Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function showAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->query->getInt('id');
        $tExpert = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TExpert')->find($id);
        return $this->render('KuakaoAdminBundle:Expert/show.html.twig', array(
            'tExpert' => $tExpert,
        ));
    }

    /**
     * 专家修改
     * @return \Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->query->getInt('id');
        $tExpert = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TExpert')->find($id);
        $form = $this->createFormBuilder($tExpert)
            ->add('name')
            ->add('new_headpath','file')
            ->add('headpath','hidden')
            ->add('department')
            ->add('subject')
            ->add('introduce')
            //->add('username')
            ->add('submit','submit',array("attr"=>array("formnovalidate"=>"formnovalidate","value"=>"Edit")))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid()) {
                $path = "upload/expart";
                $data = $form->getData();
                $new_headpath = $data->getNewHeadpath();
                if($new_headpath)
                {
                    $headpath = $data->getHeadpath();
                    $this->get('file.save_file_handler')->remove( $headpath);
                    $new_headpath = $this->get('file.save_file_handler')->save( $new_headpath , $path );
                    $data->setHeadpath($new_headpath);
                }
                $em = $this->getDoctrine()->getManager();
                $tExpert->setUsername($this->username);
                $em->persist($tExpert);
                $em->flush();
//                return $this->redirectToRoute('kuakao_admin_expert_show', array('id' => $tExpert->getId()));
                return $this->redirectToRoute('kuakao_admin_expert_index');
            }
        }
        return $this->render('KuakaoAdminBundle:Expert/edit.html.twig', [
            'form'=>$form->createView(),
            'tExpert' => $tExpert,
            'id' => $id,
        ]);
    }

    /**
     * 专家删除
     * @return JsonResponse
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $tExpert = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TExpert')->find($id);
        $em->remove($tExpert);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }

    /**
     * Creates a form to delete a TExpert entity.
     *
     * @param TExpert $tExpert The TExpert entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TExpert $tExpert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('expert_delete', array('id' => $tExpert->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
