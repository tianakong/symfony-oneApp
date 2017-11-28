<?php

namespace Kuakao\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * TAdmin controller.
 * @author wangbingang<bingangwang@kuakao.com>
 *
 */
class TMajorSchoolController extends BaseController
{
    /**
     * 学校和专业关系列表
     *
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $page =  $request->query->getInt('page', 1);
        $username = $request->query->get('username');
        /*$where = [];
        if($username) {
            $where = ['username'=>$username];
        }*/
        $em = $this->getDoctrine()->getManager();
//        $query = $em->getRepository('KuakaoAdminBundle:TMajorschool')->findBy($where, ['id'=>'desc']);
        $query = $em->getRepository('KuakaoAdminBundle:TMajorschool')->createQueryBuilder('m');
            $query ->orderBy('m.id','desc');
        if($username) {
            $query->andWhere('m.schoolname = :schoolname')
                ->setParameter('schoolname',$username);
        }
        $paginator = $this->get('knp_paginator');
        $data = $paginator->paginate($query, $page, 20);
        return $this->render('KuakaoAdminBundle:TMajorSchool/index.html.twig', array(
            'data' => $data,
            'page' => $page,
            'username' => $username,
        ));
    }

    /**
     * 删除学校和专业关系
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool')->find($id);
        $em->remove($product);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }

    /**
     * 编辑学校和专业关系
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->query->getInt('id');
        $data = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool')->find($id);
        $form = $this->createFormBuilder($data)
            ->add('majorname')
            ->add('schoolname')
            ->add('zhengzhi')
            ->add('waiyu')
            ->add('ywk1')
            ->add('ywk2')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($data);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }

        return $this->render('KuakaoAdminBundle:TMajorSchool/edit.html.twig', [
            'form'=>$form->createView(),
            'data' => $data,
            'id' => $id,
        ]);
    }

    /**
     * 修复关系表数据
     */
    public function xiufuAction()
    {
        set_time_limit(0);
        $data = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool')->findBy(['majorid'=>NULL]);
        $data2 = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool')->findBy(['schoolid'=>NULL]);
        $data = array_merge($data, $data2);
        $schoolModel = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool');
        $majorModel = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor');
        $em = $this->getDoctrine()->getManager();
        foreach($data as $val) {
            $schoolData = $schoolModel->findOneBy(['name'=>$val->getSchoolname()]);
            $majorData = $majorModel->findOneBy(['name'=>$val->getMajorname()]);
            if(!$schoolData || !$majorData) {
                continue;
            }
            $val->setMajorid($majorData->getId());
            $val->setSchoolid($schoolData->getId());
            $em->persist($val);
            $em->flush();
        }
        header("Content-type: text/html; charset=utf-8");
        echo '<script>alert("修复成功");history.go(-1);</script>';exit;
    }
}