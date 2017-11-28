<?php

namespace Kuakao\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Kuakao\AdminBundle\Entity\TCategoryInfo;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Kuakao\AdminBundle\Form\TCategoryInfoType;

/**
 * 巧备考 - 栏目咨讯管理 controller.
 *
 */
class TCategoryInfoController extends BaseController
{
    /**
     * 栏目咨讯列表
     * @return \Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        //按标题搜索
        $title = $request->get('title');
        //按栏目搜索
        $catid = $request->get('catid');
        /*$where = [];
        if($title) {
            $where = ['title'=>$title];
        }*/
        //栏目列表
        $parentidData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategory')->findAll();
        $page =  $request->query->getInt('page', 1);
        $em = $this->getDoctrine()->getManager();
//        $query = $em->getRepository('KuakaoAdminBundle:TCategoryInfo')->findBy($where,['infoid'=>'desc']);
        $query = $em->getRepository('KuakaoAdminBundle:TCategoryInfo')->createQueryBuilder('c');

        if($title) {
            $query->andWhere('c.title LIKE :title')
                ->setParameter('title','%%'.$title.'%%');
        }
        if($catid){
            $query->andWhere('c.catid = :catid')
                ->setParameter('catid',$catid);
        }
        $query ->orderBy('c.infoid','desc');
        $paginator = $this->get('knp_paginator');
        $tCategoryInfos = $paginator->paginate($query, $page, 20);
        return $this->render('KuakaoAdminBundle:CategoryInfo/index.html.twig', array(
            'tCategoryInfos' => $tCategoryInfos,
            'page' =>$page,
            'title' =>$title,
            'parentidData'=>$parentidData


        ));
    }

    /**
     * 栏目分类
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
   /* public function cotgoryAjaxAction(Request $request)
    {
        //当前所选栏目
         $catid = $request->get('catid');
       if($catid){
            $query->andWhere('c.catid = :catid')
                ->setParameter('catid',$catid);
        }
    }*/

    /**
     * 栏目资讯添加
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function newAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $tCategoryInfo = new TCategoryInfo();
        $form = $this->createFormBuilder($tCategoryInfo)
            ->add('title')
            ->add('content')
            ->add('image', 'file')
            ->add('praise')
            ->add('name')
            ->add('status')
            ->add('submit','submit',array("attr"=>array("formnovalidate"=>"formnovalidate","value"=>"sub")))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {

            if ($form->isValid())
            {

                $path = "upload/categoryinfo";
                $tCategoryInfo = $form->getData();
                $image = $tCategoryInfo->getImage();
                if($image){
                    $image = $this->get('file.save_file_handler')->save( $image , $path );
                    $tCategoryInfo->setImage($image);
                }
                $em = $this->getDoctrine()->getManager();
                $tCategoryInfo->setCatid($request->request->get('catid'));
                $tCategoryInfo->setUsername($this->username);
                $tCategoryInfo->setInputtime(time());
                $tCategoryInfo->setUpdatetime(time());
                $em->persist($tCategoryInfo);
                $em->flush();
//                return new JsonResponse(['status' => 1, 'info' => '添加成功']);
//                return $this->redirectToRoute('kuakao_admin_categoryinfo_edit', array('id' => $tCategoryInfo->getInfoId()));
                return $this->redirectToRoute('kuakao_admin_categoryinfo_index');
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '添加失败']);
            }
        }
        //栏目列表
        $parentidData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategory')->findAll();
        return $this->render('KuakaoAdminBundle:CategoryInfo/new.html.twig', [
            'form'=>$form->createView(),
            'parentidData'=>$parentidData
        ]);
    }

    /**
     * Finds and displays a TCategoryInfo entity.
     *
     */
    public function showAction(TCategoryInfo $tCategoryInfo)
    {
        $deleteForm = $this->createDeleteForm($tCategoryInfo);

        return $this->render('tcategoryinfo/show.html.twig', array(
            'tCategoryInfo' => $tCategoryInfo,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * 栏目资讯编辑
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author hejiangtao<656669865@qq.com> 2016-05
     */

    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $infoid = $request->query->getInt('infoid');
        $tCategoryInfo = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo')->find($infoid);
        $form = $this->createFormBuilder($tCategoryInfo)
            ->add('title')
            //->add('username')
            ->add('image','hidden')
            ->add('new_image','file')
            ->add('content')
            ->add('praise')
            ->add('name')
            ->add('status')
            ->add('submit','submit',array("attr"=>array("formnovalidate"=>"formnovalidate","value"=>"Edit")))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $path = "upload/categoryinfo";
                $data = $form->getData();
                $new_image = $data->getNewImage();
                if($new_image)
                {
                    $image = $data->getImage();
                    $this->get('file.save_file_handler')->remove( $image);
                    $new_image = $this->get('file.save_file_handler')->save( $new_image , $path );
                    $data->setImage($new_image);
                }
                $em = $this->getDoctrine()->getManager();
                $tCategoryInfo->setCatid($request->request->get('catid') );
                $tCategoryInfo->setUsername($this->username);
                $tCategoryInfo->setUpdatetime(time());
                $em->persist($tCategoryInfo);
                $em->flush();
//                return new JsonResponse(['status' => 1, 'info' => '修改成功']);$_SERVER['HTTP_REFERER']
//                return $this->redirectToRoute('kuakao_admin_categoryinfo_index',['catid'=>$request->request->get('catid')]);
                echo "<script>history.go(-2)</script>";

            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }
        //栏目列表
        $parentidData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategory')->findAll();
        return $this->render('KuakaoAdminBundle:CategoryInfo/edit.html.twig', [
            'form'=>$form->createView(),
            'parentidData'=>$parentidData,
            'tCategoryInfo' => $tCategoryInfo,
            'infoid' => $infoid,
        ]);
    }



    /**
     * 栏目资讯删除
     * @return JsonResponse
     * @author hejiangtao<656669865@qq.com> 2016-05
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $infoid = $request->request->getInt('infoid');
        $em = $this->getDoctrine()->getManager();
        $tExpert = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo')->find($infoid);
        $em->remove($tExpert);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }

    /**
     * Creates a form to delete a TCategoryInfo entity.
     *
     * @param TCategoryInfo $tCategoryInfo The TCategoryInfo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TCategoryInfo $tCategoryInfo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('categoryinfo_delete', array('id' => $tCategoryInfo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
