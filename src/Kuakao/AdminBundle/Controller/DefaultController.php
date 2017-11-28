<?php

namespace Kuakao\AdminBundle\Controller;

use Kuakao\AdminBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * 数据库添加操作
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertAction()
    {
        $product = new Product();
        $product->setName('水杯');
        $product->setPrice('28.8');
        $product->setDescription('这是一个很好的水杯');
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        return $this->render('KuakaoAdminBundle:Default:index.html.twig');
    }

    /**
     * 数据库查询操作
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function selectAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('KuakaoAdminBundle:Product')
            ->findAllOrderByName();
        //$product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:Product')->find($id);
        //$repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:Product');
        //$product = $repository->findBy(['name'=>'水杯', 'price'=>'28.8']);
        echo '<pre>';
        print_r($products);
        echo '</pre>';
        return $this->render('KuakaoAdminBundle:Default:index.html.twig');
    }

    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:Product')->find($id);
        $product->setName('新水杯');
        $em->flush();
        return $this->render('KuakaoAdminBundle:Default:index.html.twig');
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:Product')->find($id);
        $em->remove($product);
        $em->flush();
        return $this->render('KuakaoAdminBundle:Default:index.html.twig');
    }
}
