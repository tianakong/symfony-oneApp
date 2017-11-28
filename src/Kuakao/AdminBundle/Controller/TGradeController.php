<?php

namespace Kuakao\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Kuakao\AdminBundle\Entity\TGrade;
use Kuakao\AdminBundle\Form\TGradeType;

/**
 * TGrade controller.
 *
 */
class TGradeController extends BaseController
{
    /**
     * Lists all TGrade entities.
     *
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $page =  $request->query->getInt('page', 1);
        $name =  $request->query->get('username');

        $where = [];
        if($name){
            $where = ['username' => $name];
        }

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('KuakaoAdminBundle:TGrade')->findBy($where,['id' => 'desc']);

        $paginator = $this->get('knp_paginator');
        $gradeData = $paginator->paginate($query, $page, 20);

        return $this->render('KuakaoAdminBundle:Grade/index.html.twig', array(
            'gradeData' => $gradeData,
            'page' => $page,
            'username' => $name,
        ));
    }

    /**
     * Creates a new TGrade entity.
     *
     */
    public function newAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $tGrade = new TGrade();
        $form = $this->createFormBuilder($tGrade)
            ->add('year')
            ->add('username')
            ->add('school')
            ->add('major')
            ->add('zzfs')
            ->add('wyfs')
            ->add('ywk1')
            ->add('ywk2')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            if( $form->isValid() )
            {
                $em = $this->getDoctrine()->getManager();
                $tGrade->setAdminuser($this->username);
                $tGrade->setAddtime(time());
                $em->persist($tGrade);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '添加成功']);
            }
            else
            {
                var_dump($form->getErrorsAsString());
            }
        }

        return $this->render('KuakaoAdminBundle:Grade/add.html.twig', array(
            'form'=>$form->createView(),
            'tGrade' => $tGrade,
        ));
    }

    /**
     * Finds and displays a TGrade entity.
     *
     */
    public function showAction(TGrade $tGrade)
    {
        $deleteForm = $this->createDeleteForm($tGrade);

        return $this->render('tgrade/show.html.twig', array(
            'tGrade' => $tGrade,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TGrade entity.
     *
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->query->getInt('id');
        $gradeData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TGrade')->find($id);
        $form = $this->createFormBuilder($gradeData)
            ->add('year')
            ->add('username')
            ->add('school')
            ->add('major')
            ->add('zzfs')
            ->add('wyfs')
            ->add('ywk1')
            ->add('ywk2')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $gradeData->setAdminuser($this->username);
                $gradeData->setAddtime(time());
                $em->persist($gradeData);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }

        return $this->render('KuakaoAdminBundle:Grade/edit.html.twig', [
            'form'=>$form->createView(),
            'id' => $id,
            'gradeData' => $gradeData,
        ]);
    }

    /**
     * Deletes a TGrade entity.
     *
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TGrade')->find($id);
        $em->remove($product);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }

    /**
     * Creates a form to delete a TGrade entity.
     *
     * @param TGrade $tGrade The TGrade entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TGrade $tGrade)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_delete', array('id' => $tGrade->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
