<?php

namespace Kuakao\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Kuakao\AdminBundle\Entity\TSchool;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Kuakao\AdminBundle\Entity\TArea;
use Kuakao\Service\File;

/**
 * TSchool controller.
 *
 */
class TSchoolController extends BaseController
{

    /**
     * Lists all TSchool entities.
     *
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $em = $this->getDoctrine()->getManager();

        $school_name = $request->query->get('school_name');
        $where = [];
        if($school_name) {
            $where = ['name'=>$school_name];
        }

        $page =  $request->query->getInt('page', 1);
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool');
        $query = $repository->createQueryBuilder('s')
            ->andWhere('s.name LIKE :name')
            ->setParameter('name', '%'.$school_name.'%')
            ->orderBy('s.id', 'ASC')
            ->getQuery();
        $schools = $query->getResult();

        $area = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea')->getData();

        $school_type = $this->get('parameter')->showFieldValue('school_type');
        $school_comes_under = $this->get('parameter')->showFieldValue('school_comes_under');

        $paginator = $this->get('knp_paginator');
        $tSchools = $paginator->paginate($schools, $page, 20);

        return $this->render('KuakaoAdminBundle:School/index.html.twig', array(
            'tSchools'  => $tSchools,
            'area'      => $area,
            'school_type'      => $school_type,
            'school_comes_under'      => $school_comes_under,
            'page'      => $page,
        ));
    }

    /**
     * Creates a new TSchool entity.
     *
     */
    public function addAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $school_type = $this->get('parameter')->showField('school_type');
        $school_comes_under = $this->get('parameter')->showField('school_comes_under');
        $tArea =  $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea');
        $allTArea = $tArea->getAll([]);

        $tSchool = new TSchool();
        //$form = $this->createForm('Kuakao\AdminBundle\Form\TSchoolType', $tSchool);
        $form = $this->createFormBuilder($tSchool)
            ->add('name')
            ->add('logo', 'file')
            ->add('ndxs')
            ->add('pic', 'file')
            ->add('rank')
            ->add('gzrs')
            ->add('province', ChoiceType::class, array(
                'choices'  => $allTArea,
                'choices_as_values' => true,
            ))
            ->add('is211','checkbox')
            ->add('is985','checkbox')
            ->add('isZhx','checkbox')
            ->add('isYjs','checkbox')
            ->add('area_top')
            ->add('trade_top')
            ->add('average_wage')
            ->add('about','textarea')
            ->add('jyl')
            ->add('schoolType', 'choice', array(
                'choices'  => $school_type,
                'choices_as_values' => true,
            ))
            ->add('comesUnder', 'choice', array(
                'choices'  => $school_comes_under,
                'choices_as_values' => true,
            ))
            ->add('doctor')
            ->add('master')
            ->add('keySubjects')
            ->add('schoolLevel','hidden')
            ->add('submit','submit',array("attr"=>array("formnovalidate"=>"formnovalidate","value"=>"Create")))
            //->add('submit','submit',array("attr"=>array("value"=>"Create")))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $banner_path = "upload/school_banner";
            $logo_path = "upload/school_logo";

            $data = $form->getData();

            $schoolLevel = $data->getSchoolLevel();
            $schoolLevel['bksy'] = isset($schoolLevel['bksy']) ? $schoolLevel['bksy'] : 0;
            $schoolLevel['yjsy'] = isset($schoolLevel['yjsy']) ? $schoolLevel['yjsy'] : 0;
            $level = $schoolLevel['bksy'] + $schoolLevel['yjsy'];
            $data->setSchoolLevel($level);
            
            $logo = $data->getLogo();
            $logo = $this->get('file.save_file_handler')->save( $logo , $logo_path );
            $data->setLogo($logo);

            $pic = $data->getPic();
            $pic = $this->get('file.save_file_handler')->save( $pic , $banner_path );
            $data->setPic($pic);
            $data->setInputtime(time());
            $data->setUserid($this->userid);
            $em = $this->getDoctrine()->getManager();
            $em->persist($tSchool);
            $em->flush();
            return $this->redirectToRoute('kuakao_admin_school_show', array('id' => $tSchool->getId()));

        }

        return $this->render('@KuakaoAdmin/School/add.html.twig', array(
            'tSchool' => $tSchool,
            'form' => $form->createView(),
        ));
    }


    public function ueditorAction(Request $request)
    {
//        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $action = $request->query->get('action');
        $this->get('ueditor')->controller($action);
        die();
    }
    /**
     * Finds and displays a TSchool entity.
     *
     */
    public function showAction(TSchool $tSchool)
    {
        $area = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea')->getData();
        $school_type = $this->get('parameter')->showFieldValue('school_type');
        $school_comes_under = $this->get('parameter')->showFieldValue('school_comes_under');

        return $this->render('KuakaoAdminBundle:School:show.html.twig', array(
            'tSchool'                 => $tSchool,
            'area'                    => $area,
            'school_type'             => $school_type,
            'school_comes_under'      => $school_comes_under
        ));
    }

    /**
     * Displays a form to edit an existing TSchool entity.
     *
     */
    public function editAction(Request $request)
    {

        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $id = $request->query->getInt('id');
        $tSchool = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool')->find($id);

        $school_type = $this->get('parameter')->showField('school_type');
        $school_comes_under = $this->get('parameter')->showField('school_comes_under');
        $tArea =  $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea');
        $allTArea = $tArea->getAll([]);

        $editForm = $this->createFormBuilder($tSchool)
            ->add('name')
            ->add('logo','hidden')
            ->add('logo_tmp','file')
            ->add('ndxs')
            ->add('pic','hidden')
            ->add('pic_tmp', 'file')
            ->add('rank')
            ->add('gzrs')
            ->add('province', ChoiceType::class, array(
                'choices'  => $allTArea,
                'choices_as_values' => true,
            ))
            ->add('is211','checkbox')
            ->add('is985','checkbox')
            ->add('isZhx','checkbox')
            ->add('isYjs','checkbox')
            ->add('area_top')
            ->add('trade_top')
            ->add('average_wage')
            ->add('about','textarea')
            ->add('jyl')
            ->add('schoolType', 'choice', array(
                'choices'  => $school_type,
                'choices_as_values' => true,
            ))
            ->add('comesUnder', 'choice', array(
                'choices'  => $school_comes_under,
                'choices_as_values' => true,
            ))
            ->add('schoolLevel','hidden')
            ->add('doctor')
            ->add('master')
            ->add('keySubjects')
            ->add('submit','submit',array("attr"=>array("formnovalidate"=>"formnovalidate","value"=>"Edit")))
            ->getForm();
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $banner_path = "upload/school_banner";
            $logo_path = "upload/school_logo";
            $data = $editForm->getData();

            $schoolLevel = $data->getSchoolLevel();
            $schoolLevel['bksy'] = isset($schoolLevel['bksy']) ? $schoolLevel['bksy'] : 0;
            $schoolLevel['yjsy'] = isset($schoolLevel['yjsy']) ? $schoolLevel['yjsy'] : 0;
            $level = $schoolLevel['bksy'] + $schoolLevel['yjsy'];
            $data->setSchoolLevel($level);

            $logo = $data->getLogoTmp();
            if($logo)
            {
                $old_logo = $data->getLogo();
                $this->get('file.save_file_handler')->remove( $old_logo);
                $logo = $this->get('file.save_file_handler')->save( $logo , $logo_path );
                $data->setLogo($logo);
            }
            $pic = $data->getPicTmp();
            if($pic)
            {
                $old_pic = $data->getPic();
                $this->get('file.save_file_handler')->remove( $old_pic);
                $pic = $this->get('file.save_file_handler')->save( $pic , $banner_path );
                $data->setPic($pic);
            }
            $data->setUpdatetime(time());
            $em = $this->getDoctrine()->getManager();
            $em->persist($tSchool);
            $em->flush();

            return $this->redirectToRoute('kuakao_admin_school_show', array('id' => $tSchool->getId()));
        }

        return $this->render('KuakaoAdminBundle:School/edit.html.twig', array(
            'tSchool' => $tSchool,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * 院校删除
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->request->getInt('id');
        $em = $this->getDoctrine()->getManager();
        $school = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool')->find($id);
        $logo = $school->getLogo();
        $pic  = $school->getPic();
        $this->get('file.save_file_handler')->remove($logo);
        $this->get('file.save_file_handler')->remove($pic);
        $em->remove($school);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }

    /**
     * Creates a form to delete a TSchool entity.
     *
     * @param TSchool $tSchool The TSchool entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TSchool $tSchool)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('kuakao_admin_school_delete', array('id' => $tSchool->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
