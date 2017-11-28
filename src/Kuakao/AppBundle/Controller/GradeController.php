<?php

namespace Kuakao\AppBundle\Controller;

use Kuakao\AdminBundle\Entity\TGrade;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * 成绩相关接口
 * Class VideosController
 * @package Kuakao\AppBundle\Controller
 * @author yanyuchuan <yuchuanyan@kuakao.com>
 */
class GradeController extends BaseController
{
    /**
     * 添加成绩接口
     * @param Request $request
     * @return JsonResponse
     */
    public function addgradeAction(Request $request)
    {
//        $username = $this->userData->getName();
        $username = "哈哈";
        if(empty($username))
        {
            return new JsonResponse(['status'=>'-1', 'info'=>'没有接收到用户名']);
        }
        $year = $request->get("year");
        $school = $request->get("school");
        $major = $request->get("major");
        $zzfs = $request->get("zzfs",-1);
        $wyfs = $request->get("wyfs",-1);
        $ywk1 = $request->get("ywk1",-1);
        $ywk2 = $request->get("ywk2",-1);

        $em = $this->getDoctrine()->getManager();
        $grades = new TGrade();
        $grades -> setUsername($username);
        $grades -> setYear($year);
        $grades -> setSchool($school);
        $grades -> setMajor($major);
        $grades -> setZzfs($zzfs);
        $grades -> setWyfs($wyfs);
        $grades -> setYwk1($ywk1);
        $grades -> setYwk2($ywk2);
        $em->persist($grades);
        $em->flush();
        return new JsonResponse(['status'=>'1', 'info'=>'添加成功']);
    }

    /**
     * 成绩查询接口
     * @param Request $request
     * @return JsonResponse
     */
    public function findgradeAction(Request $request)
    {
//        $username = $this->userData->getName();
        $username = "呵呵";
        if(empty($username))
        {
            return new JsonResponse(['status'=>'-1', 'info'=>'没有接收到用户名']);
        }
        $grades = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TGrade');
        $query = $grades->createQueryBuilder('g')
            ->andWhere("g.username = :name")
            ->setParameter('name', $username)
            ->getQuery();
        $gradesData = $query->getArrayResult();
        if(empty($gradesData))
        {
            return new JsonResponse(['status'=>'-2', 'info'=>'用户数据为空或用户不存在']);
        }
        return new JsonResponse(['status'=>'1', 'info'=>'获取成功', 'data'=>$gradesData]);
    }

    /**
     * 成绩排名接口
     * @param Request $request
     * @return JsonResponse
     */
    public function sortgradeAction(Request $request)
    {
        $school = $request->get("school");
        if(empty($school))
        {
            return new JsonResponse(['status'=>'-5', 'info'=>'没有接收到学校']);
        }
        $major = $request->get("major");
        if(empty($major))
        {
            return new JsonResponse(['status'=>'-6', 'info'=>'没有接收到专业']);
        }

        $grades = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TGrade');

        $zz = $grades->createQueryBuilder('g')
            ->andWhere("g.school = :school and g.major = :major")
            ->setParameter('school', $school)
            ->setParameter('major', $major)
            ->orderBy('g.zzfs','desc')
            ->setMaxResults(20)
            ->setFirstResult(0)
            ->getQuery();
        $zzData = $zz->getArrayResult();
        if(empty($zzData))
        {
            return new JsonResponse(['status'=>'-1', 'info'=>'政治排名数据为空']);
        }

        $wy = $grades->createQueryBuilder('g')
            ->andWhere("g.school = :school and g.major = :major")
            ->setParameter('school', $school)
            ->setParameter('major', $major)
            ->orderBy('g.wyfs','desc')
            ->setMaxResults(20)
            ->setFirstResult(0)
            ->getQuery();
        $wyData = $wy->getArrayResult();
        if(empty($wyData))
        {
            return new JsonResponse(['status'=>'-2', 'info'=>'外语排名数据为空']);
        }

        $ywk1 = $grades->createQueryBuilder('g')
            ->andWhere("g.school = :school and g.major = :major")
            ->setParameter('school', $school)
            ->setParameter('major', $major)
            ->orderBy('g.ywk1','desc')
            ->setMaxResults(20)
            ->setFirstResult(0)
            ->getQuery();
        $ywk1Data = $ywk1->getArrayResult();
        if(empty($ywk1Data))
        {
            return new JsonResponse(['status'=>'-3', 'info'=>'业务课1排名数据为空']);
        }

        $ywk2 = $grades->createQueryBuilder('g')
            ->andWhere("g.school = :school and g.major = :major")
            ->setParameter('school', $school)
            ->setParameter('major', $major)
            ->orderBy('g.ywk2','desc')
            ->setMaxResults(20)
            ->setFirstResult(0)
            ->getQuery();
        $ywk2Data = $ywk2->getArrayResult();
        if(empty($ywk2Data))
        {
            return new JsonResponse(['status'=>'-4', 'info'=>'业务课2排名数据为空']);
        }
        return new JsonResponse(['status'=>'1', 'info'=>'获取数据完成', 'data1'=>$zzData, 'data2'=>$wyData, 'data3'=>$ywk1Data, 'data4'=>$ywk2Data]);
    }
}