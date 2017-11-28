<?php

namespace Kuakao\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Kuakao\AdminBundle\Entity\TYjxk;
use Kuakao\AdminBundle\Form\TYjxkType;

/**
 * TYjxk controller.
 *
 */
class TYjxkController extends BaseController
{
    /**
     * Lists all TYjxk entities.
     *
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $page =  $request->query->getInt('page', 1);
        $name =  $request->query->get('yjname');

        $where = [];
        if($name){
            $where = ['yjname' => $name];
        }

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('KuakaoAdminBundle:TYjxk')->findBy($where,['id' => 'desc']);

        $paginator = $this->get('knp_paginator');
        $yjxkData = $paginator->paginate($query, $page, 20);

        return $this->render('KuakaoAdminBundle:Yjxk/index.html.twig', array(
            'yjxkData' => $yjxkData,
            'page' => $page,
            'yjname' => $name,
        ));
    }

    /**
     * Creates a new TYjxk entity.
     *
     */
    public function newAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $tYjxk = new TYjxk();
        $form = $this->createFormBuilder($tYjxk)
            ->add('yjname')
            ->add('yjnum')
//            ->add('ndxs')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            if( $form->isValid() )
            {
                $em = $this->getDoctrine()->getManager();
                $tYjxk->setMlname($request->request->get('mlname'));
                $tYjxk->setAdminuser($this->username);
                $tYjxk->setAddtime(time());
                $em->persist($tYjxk);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '添加成功']);
            }
            else
            {
                var_dump($form->getErrorsAsString());
            }
        }

        $MlData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('xkml');

        return $this->render('KuakaoAdminBundle:Yjxk/add.html.twig', array(
            'form'=>$form->createView(),
            'tYjxk' => $tYjxk,
            'ml' => $MlData,
        ));
    }

    /**
     * Finds and displays a TYjxk entity.
     *
     */
    public function showAction(TYjxk $tYjxk)
    {
        $deleteForm = $this->createDeleteForm($tYjxk);

        return $this->render('tyjxk/show.html.twig', array(
            'tYjxk' => $tYjxk,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TYjxk entity.
     *
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->query->getInt('id');
        $yjxkData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TYjxk')->find($id);
        $form = $this->createFormBuilder($yjxkData)
            ->add('yjname')
            ->add('yjnum')
//            ->add('ndxs')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $yjxkData->setMlname($request->request->get('mlname'));
                $yjxkData->setAdminuser($this->username);
                $yjxkData->setAddtime(time());
                $em->persist($yjxkData);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }

        $MlData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('xkml');

        return $this->render('KuakaoAdminBundle:Yjxk/edit.html.twig', [
            'form'=>$form->createView(),
            'id' => $id,
            'ml' => $MlData,
            'yjxkData' => $yjxkData,
        ]);
    }

    /**
     * Deletes a TYjxk entity.
     *
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TYjxk')->find($id);
        $em->remove($product);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }

    /**
     * Creates a form to delete a TYjxk entity.
     *
     * @param TYjxk $tYjxk The TYjxk entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TYjxk $tYjxk)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('yes_delete', array('id' => $tYjxk->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
