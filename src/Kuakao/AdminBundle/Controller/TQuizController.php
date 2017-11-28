<?php

namespace Kuakao\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Kuakao\AdminBundle\Entity\TQuiz;
use Kuakao\AdminBundle\Form\TQuizType;
use Kuakao\Service\File;

/**
 * 测试题管理 controller.
 *
 */
class TQuizController extends BaseController
{
    /**
     * 测试题列表
     * @return \Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $title = $request->get('title');
        $where = [];
        if($title) {
            $where = ['title'=>$title];
        }
        $page =  $request->query->getInt('page', 1);
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('KuakaoAdminBundle:TQuiz')->findBy($where,['quizid'=>'desc']);
        $paginator = $this->get('knp_paginator');
        $tQuiz = $paginator->paginate($query, $page, 10);
        return $this->render('KuakaoAdminBundle:Quiz/index.html.twig', array(
            'tQuiz' => $tQuiz,
            'page' =>$page,
            'title' =>$title,
        ));
    }

    /**
     * 测试题添加.
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function newAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $tQuiz = new TQuiz();
        $form = $this->createFormBuilder($tQuiz)
            ->add('icon','file')
            ->add('title')
            ->add('subtitle')
            ->add('topic_num')
            ->add('option_num')
            ->add('preson_num')
            ->add('submit','submit',array("attr"=>array("formnovalidate"=>"formnovalidate","value"=>"sub")))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $path = "upload/quiz";
                $tQuiz = $form->getData();
                $icon = $tQuiz->getIcon();
                $icon = $this->get('file.save_file_handler')->save( $icon , $path );
                $tQuiz->setIcon($icon);

                $em = $this->getDoctrine()->getManager();
                $tQuiz->setUsername($this->username);
                $tQuiz->setTime(time());
                $em->persist($tQuiz);
                $em->flush();
//                return new JsonResponse(['status' => 1, 'info' => '添加成功']);
                return $this->redirectToRoute('kuakao_admin_quiz_index');

            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '添加失败']);
            }
        }
        return $this->render('KuakaoAdminBundle:Quiz/new.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * Finds and displays a TQuiz entity.
     *
     */
    public function showAction(TQuiz $tQuiz)
    {
        $deleteForm = $this->createDeleteForm($tQuiz);

        return $this->render('tquiz/show.html.twig', array(
            'tQuiz' => $tQuiz,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TQuiz entity.
     *
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $quizid = $request->query->getInt('quizid');
        $quizData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuiz')->find($quizid);
        $form = $this->createFormBuilder($quizData)
            ->add('icon','hidden')
            ->add('new_icon','file')
            ->add('title')
            ->add('subtitle')
            ->add('topic_num')
            ->add('option_num')
            ->add('preson_num')
            ->add('submit','submit',array("attr"=>array("formnovalidate"=>"formnovalidate","value"=>"Edit")))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $path = "upload/quiz";
                $data = $form->getData();
                $new_icon = $data->getNewIcon();
                if($new_icon)
                {
                    $icon = $data->getICon();
                    $this->get('file.save_file_handler')->remove( $icon);
                    $new_icon = $this->get('file.save_file_handler')->save( $new_icon , $path );
                    $data->setIcon($new_icon);
                }
                
                $em = $this->getDoctrine()->getManager();
                $quizData->setUsername($this->username);
                $quizData->setTime(time());
                $em->persist($quizData);
                $em->flush();
//                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
                return $this->redirectToRoute('kuakao_admin_quiz_index');
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }
        return $this->render('KuakaoAdminBundle:Quiz/edit.html.twig', [
            'form'=>$form->createView(),
            'quizData' => $quizData,
            'quizid' => $quizid,
        ]);

    }

    /**
     * Deletes a TQuiz entity.
     *
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $id = $request->request->getInt('quizid');
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuiz')->find($id);
        $em->remove($product);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }

    /**
     * Creates a form to delete a TQuiz entity.
     *
     * @param TQuiz $tQuiz The TQuiz entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TQuiz $tQuiz)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('quiz_delete', array('id' => $tQuiz->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
