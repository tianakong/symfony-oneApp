<?php

namespace Kuakao\AdminBundle\Controller;

use Kuakao\AdminBundle\Entity\TVideos;
use Kuakao\AdminBundle\Repository\TVideosRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * TVideos controller.
 *
 */
class TVideosController extends BaseController
{
    /**
     * 视频管理
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $page =  $request->query->getInt('page', 1);
        $speaker = $request->query->get('speaker');
        $where = [];
        if($speaker) {
            $where = ['speaker'=>$speaker];
        }
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('KuakaoAdminBundle:TVideos')->findBy($where, ['id'=>'desc']);

        $paginator = $this->get('knp_paginator');
        $videosData = $paginator->paginate($query, $page, 10);
        return $this->render('KuakaoAdminBundle:Videos/index.html.twig', array(
            'videosData' => $videosData,
            'page' => $page,
            'speaker' => $speaker,
        ));
    }

    /**
     * 视频添加
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $videos = new TVideos();
        $form = $this->createFormBuilder($videos)
            ->add('title','textarea')
            ->add('headPath')
            ->add('thumb','file')
            ->add('views')
            ->add('speaker')
            ->add('description')
            ->add('detail','textarea')
            ->add('status', 'choice', array('choices'=>array(1=>'启用', 0=>'禁用')))
            ->add('submit','submit',array("attr"=>array("formnovalidate"=>"formnovalidate","value"=>"sub")))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $path = "upload/videos";
                $tVideo = $form->getData();
                $thumb = $tVideo->getThumb();
                if($thumb){
                    $thumb = $this->get('file.save_file_handler')->save( $thumb , $path );
                    $tVideo->setThumb($thumb);
                }

                $em = $this->getDoctrine()->getManager();
                $videos->setVideo($request->request->get('video'));
                $videos->setAddTime(time());
                $videos->setAddUser($this->username);
                $em->persist($videos);
                $em->flush();
//                return new JsonResponse(['status' => 1, 'info' => '添加成功']);
                return $this->redirectToRoute('kuakao_admin_videos_index');

            } else {
                return new JsonResponse(['status' => 0, 'info' => '添加失败']);
            }
        }
        return $this->render('KuakaoAdminBundle:Videos/add.html.twig',array(
            'form' => $form->createView(),
        ));
    }

    /**
     * 视频编辑
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->query->getInt('id');
        $videosData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TVideos')->find($id);
        $form = $this->createFormBuilder($videosData)
            ->add('title','textarea')
            ->add('headPath')
            ->add('thumb','hidden')
            ->add('new_thumb','file')
            ->add('views')
            ->add('speaker')
            ->add('description')
            ->add('detail','textarea')
            ->add('status', 'choice', array('choices'=>array(1=>'启用', 0=>'禁用')))
            ->add('submit','submit',array("attr"=>array("formnovalidate"=>"formnovalidate","value"=>"Edit")))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $path = "upload/videos";
                $data = $form->getData();
                $new_thumb = $data->getNewThumb();
                if($new_thumb)
                {
                    $thumb = $data->getThumb();
                    $this->get('file.save_file_handler')->remove( $thumb);
                    $new_thumb = $this->get('file.save_file_handler')->save( $new_thumb , $path );
                    $data->setThumb($new_thumb);
                }

                $em = $this->getDoctrine()->getManager();
                $videosData->setVideo($request->request->get('video'));
                $videosData->setEditTime(time());
                $username = $this->username;
                $videosData->setEditUser($username);
                $em->persist($videosData);
                $em->flush();
//                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
                return $this->redirectToRoute('kuakao_admin_videos_index');
            } else {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }
        return $this->render('KuakaoAdminBundle:Videos/edit.html.twig',[
            'form' => $form->createView(),
            'videosData' => $videosData,
            'id' => $id,
        ]);
    }

    /**
     * 删除视频
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TVideos')->find($id);
        $em->remove($product);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }

}
