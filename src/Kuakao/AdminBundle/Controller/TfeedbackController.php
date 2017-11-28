<?php

namespace Kuakao\AdminBundle\Controller;

use Kuakao\AdminBundle\Repository\TFeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Kuakao\AdminBundle\Entity\TFeedback;
use Kuakao\AdminBundle\Form\TFeedbackType;

/**
 * Tfeedback controller.
 *
 */
class TfeedbackController extends BaseController
{

    /**
     * 反馈管理
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
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
        $query = $em->getRepository('KuakaoAdminBundle:TFeedback')->findBy($where, ['id'=>'desc']);
        $paginator = $this->get('knp_paginator');
        $feedbackData = $paginator->paginate($query, $page, 5);

        return $this->render('KuakaoAdminBundle:Feedback/index.html.twig', array(
            'feedbackData' => $feedbackData,
            'page' => $page,
            'username' => $username,
        ));
    }

    /**
     * 编辑反馈
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public  function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $id = $request->query->getInt('id');
        $feedback = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TFeedback')->find($id);
        $form = $this->createFormBuilder($feedback)
            ->add('username')
            ->add('content','textarea')
            ->add('status','choice', array('choices'=>array(1=>'已处理', 0=>'未处理')))
            ->add('replyContent','textarea')
            ->getform();
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $feedback->setReplyTime(time());
                $em->persist($feedback);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '修改成功!']);
            }else{
                return new JsonResponse(['status' => 0, 'info' => '修改失败!']);
            }
        }
        return $this->render('KuakaoAdminBundle:Feedback/edit.html.twig',array(
            'form'=>$form->createView(),
            'feedback'=>$feedback,
            'id'=>$id,
        ));
    }

    /**
     * 删除反馈
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public  function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $feedbackData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TFeedback')->find($id);
        $em->remove($feedbackData);
        $em->flush();
        return new JsonResponse(['status' => '1','info' => '删除成功']);
    }
}
