<?php

namespace Kuakao\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Kuakao\AdminBundle\Entity\TMember;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
/**
 * TMember controller.
 *
 */
class TMemberController extends BaseController
{
    /**
     * 会员列表
     * @return \Symfony\Component\HttpFoundation\Response
     * @author wangbingang<bingangwang@kuakao.com> 2016-05-09
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $name = $request->get('name');
        if($name) {
            $where = ['name'=>$name,'isDelete'=>0]; //dql
        }else{
            $where = ['isDelete'=>0];
        }
        $page = $request->query->getInt('page', 1);
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('KuakaoAdminBundle:TMember')->findBy($where, ['id'=>'desc']);
        $paginator = $this->get('knp_paginator');
        $memberData = $paginator->paginate($query, $page, 20);
        return $this->render('KuakaoAdminBundle:Member/index.html.twig', array(
            'memberData' => $memberData,
            'page' => $page,
            'name' => $name,
        ));
    }
    /**
     * 添加会员
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author wangbingang<bingangwang@kuakao.com>
     */
    public function addAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $member = new TMember();
        $form = $this->createFormBuilder($member)
            ->add('name')
            ->add('password')
            ->add('mobile')
            ->add('status', 'choice', array('choices'=>array(1=>'启用', 0=>'禁用')))
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted())
        {

            if($form->isValid())
            {
                /*if($request->request->get('password') !== $request->request->get('confirm_password')) {
                    return new JsonResponse(['status' =>0, 'info' => '重复密码不正确']);
                }*/
                $em = $this->getDoctrine()->getManager();
                $member->setAddTime(time());
                $em->persist($member);
                $em->flush();
                return new JsonResponse(['status'=>1, 'info'=>'添加成功']);
            }
            else {
                var_dump($form->getErrorsAsString());
                return new JsonResponse(['status'=>0, 'info'=>'添加失败']);
            }
        }
        return $this->render('KuakaoAdminBundle:Member/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * 编辑会员
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author wangbingang<bingangwang@kuakao.com>
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $id = $request->query->getInt('id');
        $memberData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMember')->find($id);
        $form = $this->createFormBuilder($memberData)
            ->add('name')
            ->add('mobile')
            ->add('type','choice', array('choices'=>array(1=>'咨询师', 0=>'普通会员')))
            ->add('status', 'choice', array('choices'=>array(1=>'启用', 0=>'禁用')))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $memberData->setMajor($request->request->get('major'));
                $memberData->setSchool($request->request->get('school'));
                $memberData->setYear($request->request->get('year'));
                $memberData->setTargetMajor($request->request->get('targetMajor'));
                $memberData->setTargetSchool($request->request->get('targetSchool'));
                $memberData->setScore46($request->request->get('score'));//四六级
                $memberData->setScoreBa($request->request->get('scoreBa'));
                $memberData->setImg($request->request->get('img'));
                $memberData->setSex($request->request->get('sex'));
                $memberData->setSxcj($request->request->get('sxcj'));
                $memberData->setYycj($request->request->get('yycj'));
                $em->persist($memberData);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
            } else {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }
        return $this->render('KuakaoAdminBundle:Member/edit.html.twig', [
            'form' => $form->createView(),
            'memberData' => $memberData,
            'id' => $id,
        ]);
    }
    /**
     * 删除会员
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author wangbingang<bingangwang@kuakao.com>
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $member = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMember')->find($id);
//        $member->setIsDelete(1);
//        $em->persist($member);
        $em->remove($member);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }
    /**
     * 会员详情
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author wangbingang<bingangwang@kuakao.com>
     */
    public function showAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $id = $request->query->getInt('id');
        $memberData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMember')->find($id);
        $form = $this->createFormBuilder($memberData)
            ->add('name')
            ->add('mobile')
            ->add('type','choice', array('choices'=>array(1=>'咨询师', 0=>'普通会员')))
            ->add('status', 'choice', array('choices'=>array(1=>'启用', 0=>'禁用')))
            ->getForm();
        $form->handleRequest($request);

        $schoolModel = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TSchool');
        $majorModel = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TMajor');
        //本科学校
        $school = $schoolModel->find($memberData->getSchool());
        //本科专业
        $major = $majorModel->find($memberData->getMajor());
        //意向学校
        $targetSchool = $schoolModel->find($memberData->getTargetSchool());
        //意向专业
        $targetMajor = $majorModel->find($memberData->getTargetMajor());
        //替换学校,专业,目标学校和目标专业的ID为名称
        if(!empty($school)){
            $memberData->setSchool($school->getName());
        }
        if(!empty($major)){
            $memberData->setMajor($major->getName());
        }
        if(!empty($targetMajor)){
            $memberData->setTargetMajor($targetMajor->getName());
        }
        if(!empty($targetSchool)){
            $memberData->setTargetSchool($targetSchool->getName());
        }
        //数学成绩,英语成绩键值反转
        $sxsprray = array(
            1=>'班级/年级前20%',
            2=>'其他',
            3=>'班级/年级后20%'
        );
        $yycjArray = array(
            1=>'六级550分以上',
            2=>'六级550分以下',
            3=>'四级550分以上',
            4=>'四级550分以下'
        );
        if($memberData->getSxcj()){
            $memberData->setSxcj($sxsprray[$memberData->getSxcj()]);
        }
        if($memberData->getYycj()){
            $memberData->setYycj($yycjArray[$memberData->getYycj()]);
        }
        return $this->render('KuakaoAdminBundle:Member/show.html.twig', [
            'form' => $form->createView(),
            'memberData' => $memberData,
            'id' => $id,
        ]);
    }

}
