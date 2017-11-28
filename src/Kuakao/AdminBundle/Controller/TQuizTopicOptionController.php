<?php

namespace Kuakao\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Kuakao\AdminBundle\Entity\TQuizTopicOption;
use Kuakao\AdminBundle\Form\TQuizTopicOptionType;

/**
 * TQuizTopicOption controller.
 *
 */
class TQuizTopicOptionController extends Controller
{
    /**
     * Lists all TQuizTopicOption entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tQuizTopicOptions = $em->getRepository('KuakaoAdminBundle:TQuizTopicOption')->findAll();

        return $this->render('tquiztopicoption/index.html.twig', array(
            'tQuizTopicOptions' => $tQuizTopicOptions,
        ));
    }

    /**
     * Creates a new TQuizTopicOption entity.
     *
     */
    public function newAction(Request $request)
    {
        $tQuizTopicOption = new TQuizTopicOption();
        $form = $this->createForm('Kuakao\AdminBundle\Form\TQuizTopicOptionType', $tQuizTopicOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tQuizTopicOption);
            $em->flush();

            return $this->redirectToRoute('quiztopicoption_show', array('id' => $tQuizTopicOption->getId()));
        }

        return $this->render('tquiztopicoption/new.html.twig', array(
            'tQuizTopicOption' => $tQuizTopicOption,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TQuizTopicOption entity.
     *
     */
    public function showAction(TQuizTopicOption $tQuizTopicOption)
    {
        $deleteForm = $this->createDeleteForm($tQuizTopicOption);

        return $this->render('tquiztopicoption/show.html.twig', array(
            'tQuizTopicOption' => $tQuizTopicOption,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TQuizTopicOption entity.
     *
     */
    public function editAction(Request $request, TQuizTopicOption $tQuizTopicOption)
    {
        $deleteForm = $this->createDeleteForm($tQuizTopicOption);
        $editForm = $this->createForm('Kuakao\AdminBundle\Form\TQuizTopicOptionType', $tQuizTopicOption);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tQuizTopicOption);
            $em->flush();

            return $this->redirectToRoute('quiztopicoption_edit', array('id' => $tQuizTopicOption->getId()));
        }

        return $this->render('tquiztopicoption/edit.html.twig', array(
            'tQuizTopicOption' => $tQuizTopicOption,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a TQuizTopicOption entity.
     *
     */
    public function deleteAction(Request $request, TQuizTopicOption $tQuizTopicOption)
    {
        $form = $this->createDeleteForm($tQuizTopicOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tQuizTopicOption);
            $em->flush();
        }

        return $this->redirectToRoute('quiztopicoption_index');
    }

    /**
     * Creates a form to delete a TQuizTopicOption entity.
     *
     * @param TQuizTopicOption $tQuizTopicOption The TQuizTopicOption entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TQuizTopicOption $tQuizTopicOption)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('quiztopicoption_delete', array('id' => $tQuizTopicOption->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
