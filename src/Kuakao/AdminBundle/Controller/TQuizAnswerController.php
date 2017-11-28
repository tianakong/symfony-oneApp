<?php

namespace Kuakao\AdminBundle\Controller;


use Kuakao\AdminBundle\Repository\TQuizAnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Kuakao\AdminBundle\Entity\TQuizAnswer;

/**
 * TQuizAnswer controller.
 *
 */
class TQuizAnswerController extends BaseController
{
    /**
     * 测试题答案管理
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {

    }

    /**
     * 测试题答案添加
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {

        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $quizid = $request->query->getInt('quizid');
        $quizAnswer = new TQuizAnswer();
        $form = $this->createFormBuilder($quizAnswer)
            ->add('minScore')
            ->add('maxScore')
            ->add('content','textarea')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $quizAnswer->setQuizid($quizid);
                $em->persist($quizAnswer);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '添加成功']);
            } else {
                return new JsonResponse(['status' => 0, 'info' => '添加失败']);
            }
        }
        $quizAnswerData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizAnswer')->findBy(['quizid'=>$quizid]);
        return $this->render('KuakaoAdminBundle:QuizAnswer/add.html.twig',array(
            'form' => $form->createView(),
            'quizid' => $quizid,
            'quizAnswerData' => $quizAnswerData
        ));
    }

    /**
     * 测试题答案修改
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $id = $request->query->getInt('id');
        $quizAnswerData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizAnswer')->find($id);

        $form = $this->createFormBuilder($quizAnswerData)
            ->add('minScore')
            ->add('maxScore')
            ->add('content','textarea')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($quizAnswerData);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
            } else {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }
        $quizid = $quizAnswerData->getQuizid();
        return $this->render('KuakaoAdminBundle:QuizAnswer/edit.html.twig',array(
            'form' => $form->createView(),
            'id' => $id,
            'quizid' => $quizid,
            'quizAnswerData' => $quizAnswerData
        ));
    }

    /**
     * 测试题答案删除
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizAnswer')->find($id);
        $em->remove($product);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);

    }

}
