<?php

namespace Kuakao\AdminBundle\Controller;

use Kuakao\AdminBundle\Entity\TQuiz;
use Kuakao\AdminBundle\Entity\TQuizTopicOption;
use Kuakao\AdminBundle\Form\TQuizTopicOptionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Kuakao\AdminBundle\Entity\TQuizTopic;
use Kuakao\AdminBundle\Form\TQuizTopicType;

/**
 * TQuizTopic controller.
 *
 */
class TQuizTopicController extends BaseController
{
    /**
     * Lists all TQuizTopic entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tQuizTopics = $em->getRepository('KuakaoAdminBundle:TQuizTopic')->findAll();

        return $this->render('tquiztopic/index.html.twig', array(
            'tQuizTopics' => $tQuizTopics,
        ));
    }

    /**
     * 测试小题添加
     *
     */
    public function newAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $quizid = $request->query->getInt('quizid');
        //查询选项数与题目数
        $tQuiz = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuiz')->findOneBy(['quizid'=>$quizid]);
        $optionNum =  $tQuiz->getOptionNum();
        $topicNum = $tQuiz->getTopicNum();
        $tQuizTopic = new TQuizTopic();
        $form = $this->createFormBuilder($tQuizTopic)
            ->add('title')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $tQuizTopic->setQuizid($request->query->getInt('quizid'));
                $em->persist($tQuizTopic);
                $em->flush();
                for($i=1;$i<=$optionNum;$i++)
                {
                    $topicoptioncon = $request->request->get('content'.$i);
                    $topicoptionscore = $request->request->get('score'.$i);
                    if($topicoptioncon && $topicoptionscore+1)
                    {
                        $tQuizTopicOption = new  TQuizTopicOption();
                        $emm = $this->getDoctrine()->getManager();
                        $tQuizTopicOption->setContent($topicoptioncon);
                        $tQuizTopicOption->setScore($topicoptionscore);
                        $tQuizTopicOption->setTopicid($tQuizTopic->getTopicid());
                        $tQuizTopicOption->setQuizid($quizid);
                        $emm->persist($tQuizTopicOption);
                        $emm->flush();
                    }
                }
                return new JsonResponse(['status' => 1, 'info' => '添加成功']);
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '添加失败']);
            }
        }
        //小标题
        $quizData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizTopic')->findBy(['quizid'=>$quizid],['topicid'=>'asc']);
        //选项分数
        $optionData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizTopicOption')->findBy(['quizid'=>$quizid],['optionid'=>'asc']);
        //现有标题数
        $topicNumx = count($quizData);
        return $this->render('KuakaoAdminBundle:QuizTopic/new.html.twig', [
            'form' => $form->createView(),
            'quizid' => $quizid,
            'OptionNum' => $optionNum,
            'TopicNum' => $topicNum,
            'TopicNumx' => $topicNumx,
            'quizData' => $quizData,
            'optionData' => $optionData,
        ]);
    }

    /**
     * Finds and displays a TQuizTopic entity.
     *
     */
    public function showAction(TQuizTopic $tQuizTopic)
    {
        $deleteForm = $this->createDeleteForm($tQuizTopic);

        return $this->render('tquiztopic/show.html.twig', array(
            'tQuizTopic' => $tQuizTopic,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * 测试小题修改
     *
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $quizid = intval($request->get('quizid'));
        $topicid = $request->query->getInt('topicid');
        $optionid = $request->get('optionid');

        //查询选项数
        $tQuiz = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuiz')->findOneBy(['quizid'=>$quizid]);
        $optionNum = $tQuiz->getOptionNum();

        $tQuizTopic = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizTopic')->find($topicid);
        $form = $this->createFormBuilder($tQuizTopic)
            ->add('title')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($tQuizTopic);
                $em->flush();

                for($i=1;$i<=$optionNum;$i++)
                {
                    $topicoptioncon = $request->request->get('content'.$i);
                    $topicoptionscore = $request->request->get('score'.$i);
                    $optionid = $request->request->get('optionid'.$i);
                    if($topicoptioncon && $topicoptionscore)
                    {
                        $tQuizTopicOption = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizTopicOption')->find($optionid);
                        $emm = $this->getDoctrine()->getManager();
                        $tQuizTopicOption->setContent($topicoptioncon);
                        $tQuizTopicOption->setScore($topicoptionscore);
//                        $tQuizTopicOption->setQuizid($quizid);
//                        $tQuizTopicOption->setTopicid($topicid);
                        $emm->persist($tQuizTopicOption);
                        $emm->flush();
                    }
                }
                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }
        //小标题
        $quizData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizTopic')->findBy(['quizid'=>$quizid],['topicid'=>'asc']);
        //选项分数
        $optionData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizTopicOption')->findBy(['topicid'=>$topicid],['optionid'=>'asc']);
        //现有标题数
        $topicNumx = count($quizData);

        return $this->render('KuakaoAdminBundle:QuizTopic/edit.html.twig', [
            'form'=>$form->createView(),
//            'OptionNum'=>$optionNum,
            'tQuizTopic' => $tQuizTopic,
            'quizid' => $quizid,
            'topicid' =>$topicid,
            'quizData' => $quizData,
            'optionData' => $optionData,
            'topicNumx' => $topicNumx,
        ]);
    }

    /**
     * Deletes a TQuizTopic entity.
     *
     */
    public function deleteAction(Request $request, TQuizTopic $tQuizTopic)
    {
        $form = $this->createDeleteForm($tQuizTopic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tQuizTopic);
            $em->flush();
        }

        return $this->redirectToRoute('quiztopic_index');
    }

    /**
     * Creates a form to delete a TQuizTopic entity.
     *
     * @param TQuizTopic $tQuizTopic The TQuizTopic entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TQuizTopic $tQuizTopic)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('quiztopic_delete', array('id' => $tQuizTopic->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
