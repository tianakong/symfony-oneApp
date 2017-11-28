<?php

namespace Kuakao\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * 视频相关接口
 * Class VideosController
 * @package Kuakao\AppBundle\Controller
 * @author yanyuchuan <yuchuanyan@kuakao.com>
 */
class VideosController extends BaseController
{
    /**
     * 视频列表获取接口
     * @param Request $request
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $name =  $request->get('name');//主讲人
        $first = $request->get('first',0);//起始位置
        $max = $request->get('max',15);//偏移量
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TVideos');
        $query = $repository->createQueryBuilder('v')
            ->select('v.id,v.description,v.views,v.video,v.thumb')
            ->andWhere('v.speaker LIKE :speaker')
            ->setParameter('speaker', '%'.$name.'%')
            ->andWhere('v.status = :status')
            ->setParameter('status', '1')
            ->setMaxResults($max)
            ->setFirstResult($first)
            ->orderBy('v.id', 'ASC')
            ->getQuery();
        $videos = $query->getArrayResult();
        $videos2 = [];
        foreach($videos as $k=>$v){
            $v['url'] = '/playshowpage?id='.$v['id'];
            $videoData = $this->viewsAction(trim($v['video']));
            $v['video'] = $videoData['video'];
            $videos2[] = $v;
        }
        if(empty($videos)) {
            return new JsonResponse(['status'=>'-1', 'info'=>'视频数据为空']);
        }
        return new JsonResponse(['status'=>'1', 'info'=>'读取成功', 'data'=>$videos2]);
    }

    /**
     * 视频数据获取接口
     * @param Request $request
     * @return JsonResponse
     */
    private function viewsAction($vid)
    {
        $video = file_get_contents('https://player.polyv.net/videojson/'.$vid.'.js');
        $videoArray = json_decode($video, true);
        $data = [];
//        $data['title']= $videoArray['title'];
        if($videoArray['seed'] == 0){
            $data['video'] = $videoArray['mp4'][0];
        }elseif($videoArray['seed'] == 1){
            $data['video'] = $videoArray['hlsIndex'];
        }
        return $data;
    }

    /**
     * 视频收藏获取接口
     * @param Request $request
     * @return JsonResponse
     */
    public function collectAction(Request $request)
    {
        $id = (int)$request->get('id');
        $videos = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TVideos');
        $query = $videos->createQueryBuilder('v')
            ->andWhere("v.id = $id")
            ->getQuery();
        $videosData = $query->getArrayResult();
        if(empty($id))
        {
            return new JsonResponse(['status'=>'-1', 'info'=>'视频ID为空']);
        }
        if(empty($videosData))
        {
            return new JsonResponse(['status'=>'-2', 'info'=>'视频数据为空或视频ID不存在']);
        }
        return new JsonResponse(['status'=>'1', 'info'=>'读取成功', 'data'=>$videosData]);

    }

    /**
     * 视频播放获取接口
     * @param Request $request
     * @return JsonResponse
     */
/*    public function playAction(Request $request)
    {
        $id = (int)$request->get('id');
        if(empty($id))
        {
            return new JsonResponse(['status'=>'-1', 'info'=>'视频ID为空']);
        }
        $videos = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TVideos');
        $query = $videos->createQueryBuilder('v')
            ->select('v.id,v.thumb,v.views,v.video')
            ->andWhere("v.id = $id")
            ->getQuery();
        $videosData = $query->getArrayResult();
        if(empty($videosData))
        {
            return new JsonResponse(['status'=>'-2', 'info'=>'视频数据为空或视频ID不存在']);
        }
        $em = $this->getDoctrine()->getManager();
        $videos = $em->getRepository('KuakaoAdminBundle:TVideos')->find($id);
        $num = $videos->getViews();
        $views = $num+1;
        $videos->setViews($views);
        $em->persist($videos);
        $em->flush();
        $videosData[0]['url'] = '/playshowpage?id={视频id}';
        return new JsonResponse(['status'=>'1', 'info'=>'读取成功', 'data'=>$videosData]);
    }*/

    /**
     * 已移动default控制器
     * 视频播放详情页接口
     * @param Request $request
     * @return JsonResponse
     */
    /*public function playShowPageAction(Request $request)
    {
        //获取主讲人介绍
        $id = (int)$request->get('id');
        if(!$id) {
            return new JsonResponse(array('status' => -1, 'info' => '视频ID不能为空'));
        }
        $videosData = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TVideos')->find($id);
        //获取主讲人的文章列表
        $speaker = $videosData->getSpeaker();
       $where = array(
            'name'=>$speaker,
            'catid'=>6,
            'status'=>1
        );
        $categoryInfoData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo')->findBy($where,['infoid'=>'desc']);
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo');
        $query = $repository->createQueryBuilder('c')
            ->select('c.title,c.image,c.content,c.updatetime,c.name')
            ->andWhere('c.name = :name')
            ->setParameter('name', $speaker)
            ->andWhere('c.status = :status')
            ->setParameter('status', '1')
            ->andWhere('c.catid = :catid')
            ->setParameter('catid', '6')
            ->setMaxResults(5)
            ->setFirstResult(0)
            ->orderBy('c.infoid', 'DESC')
            ->getQuery();
        $categoryInfoData = $query->getArrayResult();
        return $this->render('KuakaoAppBundle:Videos:show.html.twig',array(
            'videos'=>$videosData,
            'categoryInfo'=>$categoryInfoData
        ));

    }*/
}