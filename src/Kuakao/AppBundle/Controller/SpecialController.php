<?php

namespace Kuakao\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * 专题接口
 * Class SpecialController
 * @package Kuakao\AppBundle\Controller
 * @author wangpeng <pengwang001@kuakao.com>
 */
class SpecialController extends BaseController
{
    /**
     * 专题列表接口
     * @param Request $request
     * @return JsonResponse
     */
    public function specialListAction(Request $request)
    {
        $first = $request->get('first',0);//起始位置
        $max = $request->get('max',15);//偏移量
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSpecial');
        $query = $repository->createQueryBuilder('s')
            ->select('s.id,s.title,s.image,s.url')
            ->setMaxResults($max)
            ->setFirstResult($first)
            ->andWhere("s.status = :status")
            ->setParameter('status', 1)
            ->orderBy('s.id','DESC')
            ->getQuery();
        $specialData = $query->getArrayResult();
        $specialData2 = [];
        foreach($specialData as $k=>$v){
            if(empty($v['url'])){
                $v['url'] = '/specialshowpage?id='.$v['id'];
            }
            $specialData2[] = $v;
        }
        if($specialData2) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$specialData2));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));
    }
    /**
     * 已移动到default控制器下
     * 专题详情页面
     * @param Request $request
     * @return JsonResponse
     */
   /* public function specialshowPageAction(Request $request)
    {
        $id = (int)$request->get('id');
        if(!$id) {
            return new JsonResponse(array('status' => -1, 'info' => '专题ID不能为空'));
        }
        $specialData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSpecial')->find($id);
        return $this->render('KuakaoAppBundle:Special:show.html.twig',array(
            'special'=>$specialData,
        ));
    }*/

}