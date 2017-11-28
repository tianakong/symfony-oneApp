<?php

namespace Kuakao\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Kuakao\AdminBundle\Entity\TForm;

/**
 * 巧备考相关接口
 * Class CategoryController
 * @package Kuakao\AppBundle\Controller
 * @author wangpeng <pengwang001@kuakao.com>
 */
class CategoryController extends BaseController
{

    /**
     * 获取所有资讯列表接口
     * @param Request $request
     * @return JsonResponse
     */
   /* public function categoryListAction(Request $request)
    {
        $first = $request->get('first',0);//起始位置
        $max = $request->get('max',15);//偏移量
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategory');
        $query = $repository->createQueryBuilder('c')
            ->setMaxResults($max)
            ->setFirstResult($first)
            ->getQuery();
        $categoryData = $query->getArrayResult();
        if($categoryData) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$categoryData));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));
    }*/

    /**
     * 获取考研福利列表接口
     * @param Request $request
     * @return JsonResponse
     */
    public function kyflListAction(Request $request)
    {
        $catid= '4' ;
        $first = $request->get('first',0);//起始位置
        $max = $request->get('max',15);//偏移量
//      $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSpecial');
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo');
        $query = $repository->createQueryBuilder('s')
            ->select('s.infoid,s.title,s.image')
            ->setMaxResults($max)
            ->setFirstResult($first)
            ->andWhere("s.status = :status")
            ->setParameter('status', 1)
            ->andWhere("s.catid = :catid")
            ->setParameter('catid', $catid)
            ->orderBy('s.infoid','DESC')
            ->getQuery();
        $specialData = $query->getArrayResult();
        $specialData2 = [];
        foreach($specialData as $k=>$v){
            $v['url'] = '/infoshowpage?id='.$v['infoid'].'&cid=4';
            $specialData2[] = $v;
        }
        if($specialData2) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$specialData2));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));
    }


    /**
     * 获取考研福利详情接口
     * @param Request $request
     * @return JsonResponse
     */
   /* public function kyflShowAction(Request $request)
    {
        $id = (int)$request->get('id');
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSpecial');
        $query = $repository->createQueryBuilder('s')
            ->andWhere("s.id = :id")
            ->setParameter('id', $id)
            ->andWhere("s.status = :status")
            ->setParameter('status', 1)
            ->getQuery();
        $specialInfoData = $query->getArrayResult();
        if($specialInfoData) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$specialInfoData));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));
    }*/

    /**
     * 收集专题页表单数据接口
     * @param Request $request
     * @return JsonResponse
     */
    public function formAction(Request $request)
    {
        $username = $request->get('username');
        $userphone = $request->get('userphone');
        if(!$username || !$userphone){
            return new JsonResponse(['status'=>'-1','info'=>'姓名或电话错误']);
        }
        $formModel = new TForm();
        $formModel->setUsername($username);
        $formModel->setUserphone($userphone);
        $formModel->settime(time());
        $formModel->setStatus(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($formModel);
        $em->flush();
        return new JsonResponse(['status'=>'1','info'=>'数据保存成功']);
    }

    /**
     * 获取院校资讯列表接口
     * @param Request $request
     * @return JsonResponse
     */
    public function yxzxListAction(Request $request)
    {
        $catid = 7;
        $first = $request->get('first',0);//起始位置
        $max = $request->get('max',15);//偏移量
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo');
        $query = $repository->createQueryBuilder('c')
            ->select('c.infoid,c.title,c.updatetime,c.image')
            ->setMaxResults($max)
            ->setFirstResult($first)
            ->andWhere("c.catid = :catid")
            ->setParameter('catid', $catid)
            ->andWhere("c.status = :status")
            ->setParameter('status', 1)
            ->orderBy('c.infoid','DESC')
            ->getQuery();

        $categoryInfoData = $query->getArrayResult();
//        var_dump($categoryInfoData);
        $categoryInfoData2 = [];
        foreach($categoryInfoData as $k=>$v){
            $v['url'] = '/infoshowpage?id='.$v['infoid'].'&cid=7';
            $categoryInfoData2[] = $v;
            $time = date('Y-m-d',$categoryInfoData2[$k]['updatetime']);
            $categoryInfoData2[$k]['updatetime'] = $time;
        }
        if($categoryInfoData) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$categoryInfoData2));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));

    }

    /**
     * 获取过来人说列表接口
     * todo 二期根据ID查询数据
     * @param Request $request
     * @return JsonResponse
     */
    public function glrsListAction(Request $request)
    {
        $catid = 5;
        $first = $request->get('first',0);//起始位置
        $max = $request->get('max',15);//偏移量
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo');
        $query = $repository->createQueryBuilder('c')
            ->select('c.infoid,c.title,c.updatetime,c.image')
            ->setMaxResults($max)
            ->setFirstResult($first)
            ->andWhere("c.catid = :catid")
            ->setParameter('catid', $catid)
            ->andWhere("c.status = :status")
            ->setParameter('status', 1)
            ->orderBy('c.infoid','DESC')
            ->getQuery();
        $categoryInfoData = $query->getArrayResult();
        foreach($categoryInfoData as $k=>$v){
            $v['url'] = '/infoshowpage?id='.$v['infoid'].'&cid=5';
            $categoryInfoData2[] = $v;
            $time = date('Y-m-d',$categoryInfoData2[$k]['updatetime']);
            $categoryInfoData2[$k]['updatetime'] = $time;
        }
        if($categoryInfoData) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$categoryInfoData2));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));

    }


    /**
     * 获取排行榜列表接口
     * @param Request $request
     * @return JsonResponse
     */
    public function topListAction(Request $request)
    {
        $catid = 10;
        $first = $request->get('first',0);//起始位置
        $max = $request->get('max',15);//偏移量
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo');
        $query = $repository->createQueryBuilder('c')
            ->select('c.infoid,c.title,c.image')
            ->setMaxResults($max)
            ->setFirstResult($first)
            ->andWhere("c.catid = :catid")
            ->setParameter('catid', $catid)
            ->andWhere("c.status = :status")
            ->setParameter('status', 1)
            ->orderBy('c.infoid','DESC')
            ->getQuery();
        $categoryInfoData = $query->getArrayResult();
        foreach($categoryInfoData as $k=>$v){
            $v['url'] = '/infoshowpage?id='.$v['infoid'].'&cid=10';
            $v['title'] = '';
            $categoryInfoData2[] = $v;
        }
        if($categoryInfoData) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$categoryInfoData2));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));

    }

    /**
     * 获取专家说列表接口
     * @param Request $request
     * @return JsonResponse
     */
    public function zjsListAction(Request $request)
    {
        $first = $request->get('first',0);//起始位置
        $max = $request->get('max',15);//偏移量
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TExpert');
        $query = $repository->createQueryBuilder('e')
            ->select('e.id,e.name,e.headpath,e.introduce,e.username')
            ->setFirstResult($first)
            ->setMaxResults($max)
            ->orderBy('e.id','DESC')
            ->getQuery();
        $expertData = $query->getArrayResult();
        foreach($expertData as $k=>$v){
            $v['url'] = '/expertshowpage?id='.$v['id'];
            $expertData2[] = $v;
        }
        if($expertData) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$expertData2));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));

    }
    /**
     * 获取资讯详情接口
     * @param Request $request
     * @return JsonResponse
     */
    /*public function categoryDetailAction(Request $request)
    {
        $infoid = (int)$request->get('id');
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo');
        $query = $repository->createQueryBuilder('c')
            ->andWhere("c.infoid = :infoid")
            ->setParameter('infoid', $infoid)
            ->andWhere("c.status = :status")
            ->setParameter('status', 1)
            ->getQuery();
        $categoryInfoData = $query->getArrayResult();
        $result = array(
            'data' => $categoryInfoData,
            'url'  => '/infoshowpage?id={资讯详情ID}&cid={栏目ID}'
        );
        $result = array(
            'url'  => '/infoshowpage?id={资讯详情ID}&cid={栏目ID}'
        );
        return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$result));

    }*/
    /**
     * 已移动到default控制器下
     * 资讯详情页面 todo
     * @param Request $request
     * @return JsonResponse
     */
   /* public function infoshowPageAction(Request $request)
    {
        $infoid = (int)$request->get('id');
        if(!$infoid) {
            return new JsonResponse(array('status' => -1, 'info' => '资讯详情ID不能为空'));
        }
        $categoryInfoData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo')->find($infoid);
        $catid = (int)$request->get('cid');
        if(!$catid){
            return new JsonResponse(array('status' => -1, 'info' => '栏目ID不能为空'));
        }
        $category = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategory')->find($catid);
        return $this->render('KuakaoAppBundle:Category:infoshow.html.twig',array(
            'categoryinfo'=>$categoryInfoData,
            'category' => $category
        ));
    }*/
    /**
     * 获取专家说详情接口
     * @param Request $request
     * @return JsonResponse
     */
    /*public function expertAction(Request $request)
    {
         $id = (int)$request->get('id');
         $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TExpert');
         $query = $repository->createQueryBuilder('e')
             ->andWhere("e.id = :id")
             ->setParameter('id', $id)
             ->getQuery();
         $expertData = $query->getArrayResult();
         $result = array(
             'data' => $expertData,
             'url' => '/expertshowpage?id={专家说ID}'
         );
        $result = array(
            'url' => '/expertshowpage?id={专家说ID}'
        );
        if($result){
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$result));
        }
        return new JsonResponse(array('status'=>-1,'info'=>'暂无数据'));
    }*/



    /**
     * 获取考研时间接口
     * @param Request $request
     * @return JsonResponse
     */
    public function testTimeAction(Request $request)
    {
        $members = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMember');
        $query = $members->createQueryBuilder('m')
            ->andWhere('m.id = :id')
            ->setParameter('id', $this->userid)
            ->getQuery();
        $membersData = $query->getArrayResult();
        $year= (int)$membersData[0]['year'];
        $kytime = mktime(0,0,0,12,26,$year-1);
        $kyday = floor(($kytime-time())/(60*60*24));
        $array = array(
            'year' => $year,
            'day' => $kyday
        );
        return new JsonResponse(array('status'=>1, 'info'=>'获取成功', 'data'=>$array));
    }
}