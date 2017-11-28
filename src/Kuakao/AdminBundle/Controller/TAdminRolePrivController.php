<?php

namespace Kuakao\AdminBundle\Controller;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Kuakao\AdminBundle\Entity\TAdminRolePriv;
use Kuakao\AdminBundle\Repository\TMenuRepository;
use Symfony\Component\Security\Acl\Domain\DoctrineAclCache;

/**
 * TAdminRolePriv controller.
 *
 */
class TAdminRolePrivController extends BaseController
{
    /**
     * 权限配置
     *
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $eq_pid = $request->request->get('eq_pid', 0);
        //查询启用的角色
        $adminRole = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAdminRole')->findBy(['disabled'=>1]);
        $em = $this->getDoctrine()->getManager();
        //查询所有菜单
        $menuData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMenu')->findAll();
        //提取顶级菜单
        $topMenuData = array();
        foreach ($menuData as $val) {
            if ($val->getParentid() == 0) {
                $topMenuData[] = $val;
            }
        }
        $topMenuData = TMenuRepository::fillOption($topMenuData);
        //选择权限菜单
        $menuData = TMenuRepository::lev_menu($menuData, $eq_pid);
        foreach($menuData as $val) {
            $val->name = str_repeat('&nbsp;├─', isset($val->lev) ? $val->lev : 0).$val->name;
        }

        $tAdminRolePrivs = $em->getRepository('KuakaoAdminBundle:TAdminRolePriv')->findAll();

        $params = array(
            'adminRole' => $adminRole,
            'topMenuData' => $topMenuData,
            'menuData' => $menuData,
            'eq_pid' => $eq_pid,
        );
        return $this->render('KuakaoAdminBundle:AdminRolePriv/index.html.twig', $params);
    }

    /**
     * 根据角色ID查询权限数据
     * @param Request $request
     * @return JsonResponse
     */
    public function readAction(Request $request)
    {
        $roleid = $request->get('roleid', 0);
        if(!$roleid) {
            return new JsonResponse(['status'=>0, 'info'=>'请传入角色ID']);
        }
        $rolePriv = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAdminRolePriv')->findBy(['roleid'=>$roleid]);
        $data = array();
        foreach($rolePriv as $key=>$val) {
            $data[$key]['id']           = $val->getId();
            $data[$key]['roleid']       = $val->getRoleid();
            $data[$key]['routename']    = $val->getRoutename();
        }
        if($rolePriv) {
            return new JsonResponse(['status'=>1, 'info'=>'读取成功', 'data'=>$data]);
        } else {
            return new JsonResponse(['status'=>-1, 'info'=>'读取失败,内容为空']);
        }
    }

    /**
     * 设置角色权限
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $adminRolePriv = new TAdminRolePriv();
        if($request->isMethod('POST') && $request->request->get('roleid'))
        {
            $em = $this->getDoctrine()->getManager();
            $menu_ids = $request->request->get('menu_id');
            //删除该角色的所有权限
            $data = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAdminRolePriv')->findBy(['roleid'=>$request->request->get('roleid')]);
            foreach($data as $val) {
                $em->remove($val);
                $em->flush();
            }
            if($menu_ids) {
                try {
                    //添加角色权限
                    $adminRolePriv->setRoleid($request->request->get('roleid'));
                    foreach ($menu_ids as $val) {
                        if ($val) {
                            $adminRolePriv->setRoutename($val);
                            $em->persist($adminRolePriv);
                            $em->flush();
                            $em->clear();
                        }
                    }
                    return new JsonResponse(['status' => 1, 'info' => '操作成功']);
                } catch (Exception $e) {
                    return new JsonResponse(['status' => 0, 'info' => '操作失败']);
                }
            }
            return new JsonResponse(['status' => 1, 'info' => '操作成功']);
        }
        return new JsonResponse(['status'=>-1, 'info'=>'访问错误']);
    }
}
