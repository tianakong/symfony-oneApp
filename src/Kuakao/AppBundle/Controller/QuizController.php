<?php

namespace Kuakao\AppBundle\Controller;

use Kuakao\AdminBundle\Entity\TQuizAnswer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * 测试相关接口
 * Class VideosController
 * @package Kuakao\AppBundle\Controller
 * @author wangpeng <pengwang001@kuakao.com>
 */
class QuizController extends BaseController
{
    /**
     * 获取题库接口
     * @param Request $request
     * @return JsonResponse
     */
    public function quizAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuiz');
        $query = $repository->createQueryBuilder('q')
            ->select('q.quizid,q.icon,q.title,q.subtitle')
            ->getQuery();
        $quizData = $query->getArrayResult();
        if($quizData){
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功','data'=>$quizData));
        }
        return new JsonResponse(array('status'=>-2,'info'=>'暂无数据'));
    }

    /**
     * 获取试卷接口
     * @param Request $request
     * @return JsonResponse
     */
    public function quizTopicAction(Request $request)
    {
        /*
        //获取测试题
        $quizid = (int)$request->get('id');
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizTopic');
        $query = $repository->createQueryBuilder('q')
            ->select('q.topicid,q.title')
            ->andWhere('q.quizid = :quizid')
            ->setParameter('quizid',$quizid)
            ->getQuery();
        $quizTopicData = $query->getArrayResult();
        if(!$quizTopicData){
            return new JsonResponse(array('status'=>-1, 'info'=>'获取测试题失败'));
        }
        //获取测试题选项
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizTopicOption');
        foreach($quizTopicData as $v){
            $topicid = $v['topicid'];
            $query = $repository->createQueryBuilder('q')
                ->select('q.optionid,q.topicid,q.quizid,q.content,q.score')
                ->andWhere('q.quizid = :quizid')
                ->setParameter('quizid',$quizid)
                ->andWhere('q.topicid = :topicid')
                ->setParameter('topicid',$topicid)
                ->getQuery();
            $quizTopicOptionData = $query->getArrayResult();
            if(!$quizTopicOptionData){
                return new JsonResponse(array('status'=>-2, 'info'=>'获取测试题选项失败'));
            }
            foreach($quizTopicOptionData as $va){
                $quizTopicOptionData2[] = $va;
            };
        }
        if($quizTopicOptionData2) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$quizTopicData,'data2'=>$quizTopicOptionData2));
        }
        return new JsonResponse(array('status'=>-3, 'info'=>'暂无数据'));*/



        //获取测试题
        $quizid = (int)$request->get('id');
        if(!$quizid){
            return new JsonResponse(array('status'=>-1, 'info'=>'题库ID不能为空!'));
        }
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizTopic');
        $query = $repository->createQueryBuilder('q')
            ->select('q.topicid,q.title,q.quizid')
            ->andWhere('q.quizid = :quizid')
            ->setParameter('quizid',$quizid)
            ->getQuery();
        $quizTopicData = $query->getArrayResult();
        if(!$quizTopicData){
            return new JsonResponse(array('status'=>-1, 'info'=>'获取测试题失败'));
        }
        //获取测试题选项
        $repository2 = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizTopicOption');
        $query = $repository2->createQueryBuilder('q')
            ->select('q.optionid,q.topicid,q.quizid,q.content,q.score')
            ->getQuery();
        $quizTopicOptionData = $query->getArrayResult();
        //把题目选项插入试题
        foreach ($quizTopicOptionData as $v) {
            foreach($quizTopicData as $va) {
                if($va['topicid'] == $v['topicid'] && $va['quizid'] == $v['quizid']){
                    for($i=0;$i<count($quizTopicData);$i++){
                        if($v['topicid'] == $quizTopicData[$i]['topicid']) {
                            $quizTopicData[$i]['option'][]= $v;
                        }
                    }
                }
            }
        }
        if($quizTopicData){
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$quizTopicData));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));
    }


    /**
     * 题库提交接口 todo
     * @param Request $request
     * @return JsonResponse
     */
    public function quizAnswerAction(Request $request)
    {
        $quizid = (int)$request->get('id');
        if(!$quizid){
            return new JsonResponse(array('status'=>-1,'info'=>'题库id不能为空'));
        }
        $score = (int)$request->get('score');
        if(!$score){
            return new JsonResponse(array('status'=>-2,'info'=>'测试分数不能为空'));
        }
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TQuizAnswer');
        $query = $repository->createQueryBuilder('q')
            ->select('q.answerid,q.content')
            ->andWhere('q.quizid = :quizid')
            ->setParameter('quizid',$quizid)
            ->andWhere('q.minScore < :score')
            ->setParameter('score',$score)
            ->andWhere('q.maxScore > :score')
            ->setParameter('score',$score)
            ->getQuery();
        $quizAnswerData = $query->getArrayResult();
        if($quizAnswerData){
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功','data'=>$quizAnswerData));
        }
        return new JsonResponse(array('status'=>-3,'info'=>'暂无数据'));
    }
}