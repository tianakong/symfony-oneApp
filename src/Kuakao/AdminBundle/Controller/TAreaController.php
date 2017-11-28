<?php

namespace Kuakao\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Kuakao\AdminBundle\Entity\TArea;
use Kuakao\AdminBundle\Form\TAreaType;

/**
 * TArea controller.
 *
 */
class TAreaController extends BaseController
{
    /**
     * Lists all TArea entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $where = [];
        $page =  $request->query->getInt('page', 1);
        $tAreas = $em->getRepository('KuakaoAdminBundle:TArea')->findBy($where, ['listorder'=>'DESC']);
        $paginator = $this->get('knp_paginator');
        $tAreas = $paginator->paginate($tAreas, $page, 20);

        return $this->render('KuakaoAdminBundle:Area:index.html.twig', array(
            'tAreas'    => $tAreas,
            'page'      => $page,
        ));
    }

    /**
     * Creates a new TArea entity.
     *
     */
    public function addAction(Request $request)
    {
        $tArea = new TArea();
        $form = $this->createForm('Kuakao\AdminBundle\Form\TAreaType', $tArea);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $listorder = $tArea->getListorder();
            if(empty($listorder)){
                $tArea->setListorder(0);
            }
            $em->persist($tArea);
            $em->flush();
            return new JsonResponse(['status' => 1, 'info' => '添加成功']);
        }
        return $this->render('KuakaoAdminBundle:Area:add.html.twig', array(
            'tArea' => $tArea,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TArea entity.
     *
     */
    public function showAction(TArea $tArea)
    {
        $deleteForm = $this->createDeleteForm($tArea);

        return $this->render('tarea/show.html.twig', array(
            'tArea' => $tArea,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TArea entity.
     *
     */
    public function editAction(Request $request)
    {
        $id = $request->query->getInt('id');
        $tArea = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea')->find($id);
        $form = $this->createForm('Kuakao\AdminBundle\Form\TAreaType', $tArea);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tArea);
            $em->flush();
            return new JsonResponse(['status' => 1, 'info' => '修改成功']);
        }
        return $this->render('@KuakaoAdmin/Area/add.html.twig', array(
            'tArea'     => $tArea,
            'form'      => $form->createView(),
            'id'        => $id,
        ));
    }

    /**
     * Deletes a TArea entity.
     *
     */
    public function deleteAction(Request $request)
    {
        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $area = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea')->find($id);
        $em->remove($area);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }

    /**
     * Creates a form to delete a TArea entity.
     *
     * @param TArea $tArea The TArea entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TArea $tArea)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tarea_delete', array('id' => $tArea->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
