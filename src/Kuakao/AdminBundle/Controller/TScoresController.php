<?php

namespace Kuakao\AdminBundle\Controller;

use Kuakao\AdminBundle\Repository\TScoresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Kuakao\AdminBundle\Entity\TScores;
use Kuakao\AdminBundle\Form\TScoresType;

/**
 * TScores controller.
 *
 */
class TScoresController extends BaseController
{
    /**
     * 分数线管理
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $page =  $request->query->getInt('page', 1);
        $forecastScore = $request->query->get('forecastScore');
        /*$where = [];
        if($forecastScore) {
            $where = ['forecastScore'=>$forecastScore];
        }*/

        $em = $this->getDoctrine()->getManager();
//        $query = $em->getRepository('KuakaoAdminBundle:TScores')->findBy($where,['id'=>'desc']);
        $query = $em->getRepository('KuakaoAdminBundle:TScores')->createQueryBuilder('s');
        $query->orderBy('s.id','desc');
        if($forecastScore) {
            //$query->andWhere('s.userGroup = '.$forecastScore);
        }
        $paginator = $this->get('knp_paginator');
        $scores = $paginator->paginate($query, $page, 20);

//        获取学校 省份 专业 名称
        foreach ($scores as $k=>$v) {
            $schoolData = $em->getRepository('KuakaoAdminBundle:TSchool')->findOneBy(['id'=>$v->getSchoolId()]);
            if($schoolData){
                $v->schoolName = $schoolData->getName();
            }
            $areaData = $em->getRepository('KuakaoAdminBundle:TArea')->findOneBy(['id'=>$v->getShengId()]);
            if($areaData){
                $v->shengName = $areaData->getName();
            }
            $majorData = $em->getRepository('KuakaoAdminBundle:TMajor')->findOneBy(['id'=>$v->getMajorId()]);
            if($majorData){
             $v->majorName = $majorData->getName();
            }
        }

        return $this->render('KuakaoAdminBundle:TScores/index.html.twig', array(
            'scores' => $scores,
            'page' => $page,
            'forecastScore' => $forecastScore,
        ));
    }

    /**
     * 添加分数线
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public  function addAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $scores = new TScores();
        $form = $this->createFormBuilder($scores)
            ->add('year')
            ->add('forecastScore')
            ->add('recruitNum')
            ->add('pushAvoidNum')
            ->add('political')
            ->add('english')
            ->add('profes1')
            ->add('profes2')
            ->add('enrollNum')
            ->add('totalScore')
            ->getform();
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $scores->setAdduser($this->username);
                $scores->setSchoolId($request->request->get('school'));
                $scores->setMajorId($request->request->get('major'));
                $scores->setShengId($request->request->get('sheng'));
                $scores->setEditTime(time());
                $em->persist($scores);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '添加成功']);
            }else{
                return new JsonResponse(['status' => 0, 'info' => '添加失败']);
            }
        }
        return $this->render('KuakaoAdminBundle:TScores/add.html.twig',array(
            'form'=>$form->createView(),
        ));

    }

    /**
     * 加载省份
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajaxAction(Request $request)
    {
//        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $sid = $request->query->get('sid');
        $zone = $request->query->get('zone');
        $areaData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea')->findBy(['zone'=>$zone]);
        if($areaData)
        {
            return $this->render('KuakaoAdminBundle:TScores/ajax.html.twig', array(
                'sheng' => $areaData,
                'sid'=> $sid,
            ));
        }
    }

    /**
     * 加载学校
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajax2Action(Request $request)
    {
//        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $cid = $request->query->get('cid');
        $proid = $request->query->get('proid');
        $schoolData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool')->findBy(['province'=>$proid]);
        if($schoolData)
        {
            return $this->render('KuakaoAdminBundle:TScores/ajax2.html.twig', array(
                'school' => $schoolData,
                'cid' => $cid,
            ));
        }
    }

    /**
     * 加载学科门类
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajaxxkmlAction(Request $request)
    {
//        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
//        $xkname = $request->query->get('xkname');
        $MlData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('xkml');
        if($MlData)
        {
            return $this->render('KuakaoAdminBundle:TScores/ajaxxkml.html.twig', array(
                'xkml' => $MlData,
//                'xkname' => $xkname,
            ));
        }
    }

    /**
 * 加载一级学科
 * @param Request $request
 * @return \Symfony\Component\HttpFoundation\Response
 */
    public function ajaxyjxkAction(Request $request)
    {
//        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $yjnum = $request->query->get('yjnum');
        $mlname = $request->query->get('mlname');
        $yjxkData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TYjxk')->findBy(['mlname'=>$mlname]);
        if($yjxkData)
        {
            return $this->render('KuakaoAdminBundle:TScores/ajaxyjxk.html.twig', array(
                'yjnum' => $yjnum,
                'yjxk' => $yjxkData,
            ));
        }
    }

    /**
     * 加载专业名称
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajaxmajorAction(Request $request)
    {
//        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $yjxk = $request->query->get('yjxk');
        $majorData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->findBy(['yjxk'=>$yjxk]);
        if($majorData)
        {
            return $this->render('KuakaoAdminBundle:TScores/ajaxmajor.html.twig', array(
                'major' => $majorData,
            ));
        }
    }

    /**
     * 编辑分数线
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public  function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $id = $request->query->getInt('id');
        $scores = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TScores')->find($id);
        $form = $this->createFormBuilder($scores)
            ->add('year')
/*            ->add('forecastScore')
            ->add('recruitNum')
            ->add('pushAvoidNum')
            ->add('enrollNum')*/
            ->add('political')
            ->add('english')
            ->add('profes1')
            ->add('profes2')
            ->add('totalScore')
            ->getform();
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $scores->setAdduser($this->username);
                $scores->setSchoolId($request->request->get('school'));
                $scores->setMajorId($request->request->get('major'));
                $scores->setShengId($request->request->get('sheng'));
                $em->persist($scores);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
            }else{
                return new JsonResponse(['status' => 0, 'info' => '修改失败!']);
            }
        }
        //获取学校
        $schoolId = $scores->getSchoolId();
        $schoolData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool')->find($schoolId);

        //获取省份
        $shengId = $scores->getShengId();
        $areaData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea')->find($shengId);

        //获取专业
        $majorId = $scores->getMajorId();
        $majorData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->find($majorId);
        //获取学科门类
        $MlData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('xkml');
        return $this->render('KuakaoAdminBundle:TScores/edit.html.twig',array(
            'form'=>$form->createView(),
            'scores'=>$scores,
            'school' => $schoolData,
            'area'=>$areaData,
            'major'=>$majorData,
            'xkml' => $MlData,
            'id'=>$id,
        ));

    }

    /**
     * 删除分数线
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public  function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TScores')->find($id);
        $em->remove($product);
        $em->flush();
        return new JsonResponse(['status' => '1','info' => '删除成功']);
    }
}
