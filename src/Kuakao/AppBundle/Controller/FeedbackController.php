<?php

namespace Kuakao\AppBundle\Controller;

use Kuakao\AdminBundle\Entity\TFeedback;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Kuakao\Common\Globals;
/**
 * 帮助与反馈相关接口
 * Class VideosController
 * @package Kuakao\AppBundle\Controller
 * @author wangpeng <pengwang001@kuakao.com>
 */

class FeedbackController extends BaseController
{
    /**
     * 获取帮助与反馈列表接口
     * @param Request $request
     * @return JsonResponse
     */
    public function helpListAction(Request $request)
    {
        $catid = 9;
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo');
        $query = $repository->createQueryBuilder('c')
            ->select('c.infoid,c.catid,c.title')
            ->andWhere("c.catid = :catid")
            ->setParameter('catid', $catid)
            ->getQuery();
        $categoryInfoData = $query->getArrayResult();
        $newcategoryInfoData = [];
        foreach($categoryInfoData as $k=>$v){
            $v['url'] = '/infoshowpage?id='.$v['infoid'].'&cid='.$catid;
            $newcategoryInfoData[$k] = $v;
        }
        if($categoryInfoData) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$newcategoryInfoData));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));
    }

    /**
     * 添加反馈接口 todo
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
/*        $content = array(
            'text'=>'反馈内容',
            'pic'=>array(
                'pic1',
                'pic2',
                'pic3',
                'pic4',
                'pic5',
                'pic6',
                'pic7',
                'pic8',
                'pic9'
            )
        );
        $con = json_encode($content);
        return new JsonResponse(['content'=>$content]);
        die();*/

        $content = $request->request->get('content'); //数组,Json
        $content = json_decode($content, true);
        if(empty($content['text'])){
            return new JsonResponse(['status'=>'-1','info'=>'text参数为空!']);
        }
        $feedbackModel = new TFeedback();
        $pics = array();
        if(!empty($content['pic'])){
            foreach($content['pic'] as $key=>$val) {
                //原始图片
                $fileName = md5($this->userid.$key.Globals::random(5));
                $fileName = $fileName.'.png';
                $file = '/upload/feedback/'.$fileName;
                file_put_contents($_SERVER['DOCUMENT_ROOT'].$file, base64_decode($val));
                //压缩图片
                $imgsrc = $_SERVER['DOCUMENT_ROOT'].$file;
                $imginfo = pathinfo($imgsrc);
                $imgdst = $imginfo['dirname'].'/s_'.$imginfo['basename'];
                Globals::image_png_size_add($imgsrc,$imgdst);
                $pics[] = $file;
            }
            $pics = json_encode($pics);
            $feedbackModel->setImg($pics);
        }
        $feedbackModel->setAddTime(time());
        $feedbackModel->setContent($content['text']);
        $feedbackModel->setStatus(0);
        $username = $this->userData->getName();
        $feedbackModel->setUsername($username);
        $em = $this->getDoctrine()->getManager();
        $em->persist($feedbackModel);
        $em->flush();
        return new JsonResponse(['status'=>'1','info'=>'操作成功']);
    }
    /**
     * 获取我的反馈列表接口
     * @param Request $request
     * @return JsonResponse
     */
    public function myFeedbackAction(Request $request)
    {
        $repositroy = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TFeedback');
        $username = $this->userData->getName();
        $query = $repositroy->createQueryBuilder('f')
            ->select('f.id,f.content,f.replyContent')
            ->andWhere("f.username = :username")
            ->setParameter('username',$username)
            ->getQuery();
        $feedbackData = $query->getArrayResult();
        $newFeedbackData = [];
        foreach($feedbackData as $k=>$v){
            $v['url'] = '/feedbackshowpage?id='.$v['id'];
            $newFeedbackData[] = $v;
        }
        if($newFeedbackData) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$newFeedbackData));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));
    }

    /**
     * 获取帮助详情接口
     * @param Request $request
     * @return JsonResponse
     */
    /* public function categoryInfoAction(Request $request)
     {
         $catid = (int)$request->get('catid');
         $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo');
         $query = $repository->createQueryBuilder('c')
             ->andWhere("c.status = :status")
             ->setParameter('status', 1)
             ->andWhere("c.catid = :catid")
             ->setParameter('catid', $catid)
             ->getQuery();
         $categoryInfoData = $query->getArrayResult();
         if($categoryInfoData) {
             return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$categoryInfoData));
         }
         return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));
     }
         $result = array(
             'url' => '/infoshowpage?id={帮助ID}&cid={栏目ID}'
         );
         return new JsonResponse(array('status' => 1, 'info' => '读取成功', 'data' => $result));
     }*/

}