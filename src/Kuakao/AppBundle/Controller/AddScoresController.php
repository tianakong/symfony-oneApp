<?php

namespace Kuakao\AppBundle\Controller;


use Kuakao\AdminBundle\Entity\TAddScores;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * 添加成绩接口
 * Class CategoryController
 * @package Kuakao\AppBundle\Controller
 * @author wangpeng <pengwang001@kuakao.com>
 */
class AddScoresController extends BaseController
{

    /**
     * 添加成绩接口 todo
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $testType = $request->get('type');
        $subject = $request->get('subject');
        $score = $request->get('score');
        $schoolId = $request->get('schoolid');
        $majorId = $request->get('majorid');
        if(!$testType){
            return new JsonResponse(['status'=>'-1','info'=>'请检查科目类型']);
        }
        if(!$subject){
            return new JsonResponse(['status'=>'-2','info'=>'请检查科目']);
        }
        if(!$score){
            return new JsonResponse(['status'=>'-3','info'=>'请检查成绩']);
        }
        if(!$schoolId){
            return new JsonResponse(['status'=>'-4','info'=>'请检查学校ID']);
        }
        if(!$majorId){
            return new JsonResponse(['status'=>'-5','info'=>'请检查专业ID']);
        }
        $em = $this->getDoctrine()->getManager();
        $userid = $this->userid;
        $userName = $this->userData->getName();
        $formModel = new TAddScores();
        $formModel->setUid($userid);
        $formModel->setUsername($userName);
        $formModel->setTestType($testType);
        $formModel->setSubject($subject);
        $formModel->setScore($score);
        $formModel->setSchoolId($schoolId);
        $formModel->setMajorId($majorId);
        $em->persist($formModel);
        $em->flush();
        return new JsonResponse(['status'=>'1','info'=>'数据保存成功']);
    }

    /**
     * 获取平均分最高分最低分接口 todo
     * @param Request $request
     * @return JsonResponse
     */
    public function maxMinAction(Request $request)
    {
        $schoolId = $request->get('sid');
        $majorId = $request->get('mid');
//        $testType = $request->get('type');
        $userid = $this->userid;
//        $userid = 1;
        if(!$schoolId){
            return new JsonResponse(array('status'=>-2, 'info'=>'请检查学校ID'));
        }
        if(!$majorId){
            return new JsonResponse(array('status'=>-3, 'info'=>'请检查专业ID'));
        }
        //政治
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAddScores');
        $query = $repository->createQueryBuilder('a')
            ->select('a.testType,max(a.score) as maxscore,min(a.score) as minscore,avg(a.score) as avgscore')
            ->andWhere("a.schoolId = :schoolId")
            ->setParameter('schoolId', $schoolId)
            ->andWhere("a.majorId = :majorId")
            ->setParameter('majorId', $majorId)
            ->andWhere("a.testType = :testType")
            ->setParameter('testType', 'zhengzhi')
            ->andWhere("a.uid = :uid")
            ->setParameter('uid', $userid)
            ->getQuery();
        $addScoresData = $query->getArrayResult();
        $newAddScoresData = [];
        foreach($addScoresData as $k=>$v){
            $v['avgscore'] = round($v['avgscore'],2);
            $newAddScoresData[] = $v;
        }
        //业务课1
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAddScores');
        $query = $repository->createQueryBuilder('a')
            ->select('a.testType,max(a.score) as maxscore,min(a.score) as minscore,avg(a.score) as avgscore')
            ->andWhere("a.schoolId = :schoolId")
            ->setParameter('schoolId', $schoolId)
            ->andWhere("a.majorId = :majorId")
            ->setParameter('majorId', $majorId)
            ->andWhere("a.testType = :testType")
            ->setParameter('testType', 'ywk1')
            ->andWhere("a.uid = :uid")
            ->setParameter('uid', $userid)
            ->getQuery();
        $ywk1Data = $query->getArrayResult();
        $newywk1Data = [];
        foreach($ywk1Data as $k=>$v){
            $v['avgscore'] = round($v['avgscore'],2);
            $newywk1Data[] = $v;
        }
        //业务课2
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAddScores');
        $query = $repository->createQueryBuilder('a')
            ->select('a.testType,max(a.score) as maxscore,min(a.score) as minscore,avg(a.score) as avgscore')
            ->andWhere("a.schoolId = :schoolId")
            ->setParameter('schoolId', $schoolId)
            ->andWhere("a.majorId = :majorId")
            ->setParameter('majorId', $majorId)
            ->andWhere("a.testType = :testType")
            ->setParameter('testType', 'ywk2')
            ->andWhere("a.uid = :uid")
            ->setParameter('uid', $userid)
            ->getQuery();
        $ywk2Data = $query->getArrayResult();
        $newywk2Data = [];
        foreach($ywk2Data as $k=>$v){
            $v['avgscore'] = round($v['avgscore'],2);
            $newywk2Data[] = $v;
        }
        //外语
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAddScores');
        $query = $repository->createQueryBuilder('a')
            ->select('a.testType,max(a.score) as maxscore,min(a.score) as minscore,avg(a.score) as avgscore')
            ->andWhere("a.schoolId = :schoolId")
            ->setParameter('schoolId', $schoolId)
            ->andWhere("a.majorId = :majorId")
            ->setParameter('majorId', $majorId)
            ->andWhere("a.testType = :testType")
            ->setParameter('testType', 'waiyu')
            ->andWhere("a.uid = :uid")
            ->setParameter('uid', $userid)
            ->getQuery();
        $waiyuData = $query->getArrayResult();
        $newwaiyuData = [];
        foreach($waiyuData as $k=>$v){
            $v['avgscore'] = round($v['avgscore'],2);
            $newwaiyuData[] = $v;
        }
        $newData = array_merge($newAddScoresData,$newwaiyuData,$newywk1Data,$newywk2Data);
        $newData2 = [];
        foreach($newData as $K=>$v){
            if($v['testType'] == !null){
                $newData2[] = $v;
            }
        }
        if($newData2) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$newData2));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));

    }


    /**
     * 获取成绩排名接口 todo
     * @param Request $request
     * @return JsonResponse
     */
    public function sortScoresAction(Request $request)
    {
        $schoolId = $request->get('sid');
        $majorId = $request->get('mid');
        $first = $request->get('first',0);
        $max = $request->get('max',10);

        $userid = $this->userid;
//        $userid = 1;
        if(!$schoolId){
            return new JsonResponse(array('status'=>-2, 'info'=>'请检查学校ID'));
        }
        if(!$majorId){
            return new JsonResponse(array('status'=>-3, 'info'=>'请检查专业ID'));
        }
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAddScores');
        //政治成绩排名
        $query = $repository->createQueryBuilder('a')
            ->select('a.id,a.username,a.score')
            ->andWhere("a.schoolId = :schoolId")
            ->setParameter('schoolId', $schoolId)
            ->andWhere("a.majorId = :majorId")
            ->setParameter('majorId', $majorId)
            ->andWhere("a.testType = :testType")
            ->setParameter('testType','zhengzhi')
            /*->andWhere("a.uid = :uid")
            ->setParameter('uid', $userid)*/
            ->orderBy('a.score','DESC')
            ->setFirstResult($first)
            ->setMaxResults($max)
            ->getQuery();
        $zhengzhiData = $query->getArrayResult();

        //外语成绩排名
        $query = $repository->createQueryBuilder('a')
            ->select('a.id,a.username,a.score')
            ->andWhere("a.schoolId = :schoolId")
            ->setParameter('schoolId', $schoolId)
            ->andWhere("a.majorId = :majorId")
            ->setParameter('majorId', $majorId)
            ->andWhere("a.testType = :testType")
            ->setParameter('testType','waiyu')
            /*->andWhere("a.uid = :uid")
            ->setParameter('uid', $userid)*/
            ->orderBy('a.score','DESC')
            ->setFirstResult($first)
            ->setMaxResults($max)
            ->getQuery();
        $waiyuData = $query->getArrayResult();

        //业务课1成绩排名
        $query = $repository->createQueryBuilder('a')
            ->select('a.id,a.username,a.score')
            ->andWhere("a.schoolId = :schoolId")
            ->setParameter('schoolId', $schoolId)
            ->andWhere("a.majorId = :majorId")
            ->setParameter('majorId', $majorId)
            ->andWhere("a.testType = :testType")
            ->setParameter('testType','ywk1')
            /*->andWhere("a.uid = :uid")
            ->setParameter('uid', $userid)*/
            ->orderBy('a.score','DESC')
            ->setFirstResult($first)
            ->setMaxResults($max)
            ->getQuery();
        $ywk1Data = $query->getArrayResult();


        //业务课2成绩排名
        $query = $repository->createQueryBuilder('a')
            ->select('a.id,a.username,a.score')
            ->andWhere("a.schoolId = :schoolId")
            ->setParameter('schoolId', $schoolId)
            ->andWhere("a.majorId = :majorId")
            ->setParameter('majorId', $majorId)
            ->andWhere("a.testType = :testType")
            ->setParameter('testType','ywk2')
           /* ->andWhere("a.uid = :uid")
            ->setParameter('uid', $userid)*/
            ->orderBy('a.score','DESC')
            ->setFirstResult($first)
            ->setMaxResults($max)
            ->getQuery();
        $ywk2Data = $query->getArrayResult();
        //隐藏账号中间四位
        $newData = [];
        $newzhengzhiData = [];
        $newwaiyuData = [];
        $newywk1Data = [];
        $newywk2Data = [];
        foreach($zhengzhiData as $k=>$v){
            $v['username'] = substr_replace($v['username'],'****',3,4);
            $newzhengzhiData[]= $v;
        }
        foreach($waiyuData as $k=>$v){
            $v['username'] = substr_replace($v['username'],'****',3,4);
            $newwaiyuData[]= $v;
        }
        foreach($ywk1Data as $k=>$v){
            $v['username'] = substr_replace($v['username'],'****',3,4);
            $newywk1Data[]= $v;
        }
        foreach($ywk2Data as $k=>$v){
            $v['username'] = substr_replace($v['username'],'****',3,4);
            $newywk2Data[]= $v;
        }
        $newData['zhengzhi'] = $newzhengzhiData;
        $newData['waiyu'] = $newwaiyuData;
        $newData['ywk1'] = $newywk1Data;
        $newData['ywk2'] = $newywk2Data;


        if($newData) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$newData));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));

    }


}