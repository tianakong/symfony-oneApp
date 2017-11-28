<?php

namespace Kuakao\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Kuakao\AdminBundle\Entity\TAttachment;
use Kuakao\AdminBundle\Form\TAttachmentType;

/**
 * TAttachment controller.
 *
 */
class TAttachmentController extends Controller
{
    /**
     * Lists all TAttachment entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tAttachments = $em->getRepository('KuakaoAdminBundle:TAttachment')->findAll();

        return $this->render('tattachment/index.html.twig', array(
            'tAttachments' => $tAttachments,
        ));
    }

    /**
     * Creates a new TAttachment entity.
     *
     */
    public function newAction(Request $request)
    {
        $tAttachment = new TAttachment();
        $form = $this->createForm('Kuakao\AdminBundle\Form\TAttachmentType', $tAttachment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tAttachment);
            $em->flush();

            return $this->redirectToRoute('tattachment_show', array('id' => $tAttachment->getId()));
        }

        return $this->render('tattachment/new.html.twig', array(
            'tAttachment' => $tAttachment,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TAttachment entity.
     *
     */
    public function showAction(TAttachment $tAttachment)
    {
        $deleteForm = $this->createDeleteForm($tAttachment);

        return $this->render('tattachment/show.html.twig', array(
            'tAttachment' => $tAttachment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TAttachment entity.
     *
     */
    public function editAction(Request $request, TAttachment $tAttachment)
    {
        $deleteForm = $this->createDeleteForm($tAttachment);
        $editForm = $this->createForm('Kuakao\AdminBundle\Form\TAttachmentType', $tAttachment);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tAttachment);
            $em->flush();

            return $this->redirectToRoute('tattachment_edit', array('id' => $tAttachment->getId()));
        }

        return $this->render('tattachment/edit.html.twig', array(
            'tAttachment' => $tAttachment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a TAttachment entity.
     *
     */
    public function deleteAction(Request $request, TAttachment $tAttachment)
    {
        $form = $this->createDeleteForm($tAttachment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tAttachment);
            $em->flush();
        }

        return $this->redirectToRoute('tattachment_index');
    }

    /**
     * Creates a form to delete a TAttachment entity.
     *
     * @param TAttachment $tAttachment The TAttachment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TAttachment $tAttachment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tattachment_delete', array('id' => $tAttachment->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
