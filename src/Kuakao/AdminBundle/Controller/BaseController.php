<?php

namespace Kuakao\AdminBundle\Controller;

use Kuakao\AdminBundle\Repository\TMenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{
    protected $userid;

    protected $username;

    protected $userData;

    /**
     * 全局构造方法
     */
    public function __construct()
    {
        //判断用户是否登录，如果没有登录则跳转到登录页面
        if( !isset($_SESSION['_sf2_attributes']['admin_login_id']) ) {
            header("location:/admin/");exit;
        }
        $this->userid = $_SESSION['_sf2_attributes']['admin_login_id'];
        $this->username = $_SESSION['_sf2_attributes']['admin_login_data']->getUsername();
        $this->userData = $_SESSION['_sf2_attributes']['admin_login_data'];
    }

    /**
     * todo 全局方法
     * 每个action里第一行调用
     * @param array $params['routeName', 'actionName']
     * @return json
     */
    protected function _globalHook(array $params)
    {
        self::_priv($params['routeName']);
    }

    /**
     * 权限检查
     * @param $routeName
     */
    private function _priv($routeName)
    {
        $em = $this->getDoctrine()->getManager();
        //查询管理员最新信息
        $adminData =$em->getRepository('KuakaoAdminBundle:TAdmin')->find($this->userid);
        //判断管理员所属角色的权限
        $result = $em->getRepository('KuakaoAdminBundle:TAdminRolePriv')->findBy(['roleid'=>$adminData->getRoleid(), 'routename'=>$routeName]);
        if(!$result) {
            if (isset($_POST['ajax'])) {
                echo json_encode(['status'=>0, 'info'=>'没有权限']);exit;
            }
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("没有权限");history.go(-1);</script>';exit;
        }
    }

    /**
     * 头部导航
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function topAction()
    {
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMenu');
        $menuData = $repository->findBy(['parentid' => 0, 'display' => 1], ['listorder'=>'desc']);
        $menuData = self::checkMenu($menuData);
        return $this->render("KuakaoAdminBundle:topMenu.html.twig", ['menuData' => $menuData]);
    }

    /**
     * 左侧小导航
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function leftMenuAction(Request $request)
    {
        $this->getDoctrine()->getManager()->clear();
        $topMenuId = $request->cookies->get('top_menu', 1);
        $em = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMenu');
        $menuDatas = $em->getAll(['display'=>1]);
        $menuDatas = self::checkMenu($menuDatas);
        $topMenuName = '';
        $leftNavigation = '';
        //获取当前大菜单名称
        foreach($menuDatas as $val) {
            if($val->getId() == $topMenuId) {
                $topMenuName = $val->getName();
            }
        }
        if($topMenuId && $menuDatas) {
            $leftNavigation = TMenuRepository::tree_nav(TMenuRepository::list_to_tree($menuDatas, $topMenuId));
        }
        return $this->render("KuakaoAdminBundle:leftMenu.html.twig", ['leftNavigation' => $leftNavigation, 'topMenuName'=>$topMenuName]);
    }

    /**
     * 判断路由是否存在
     * @param $routeName 路由名称
     * @return mixed
     */
    public function isRoute($routeName)
    {
        $collection = $this->get('router')->getRouteCollection();
        return array_key_exists($routeName, $collection->all());
    }

    /**
     * 检测角色的菜单权限
     * @param $menuData
     * @return array
     */
    final private function checkMenu($menuData)
    {
        $menu = array();
        //查询管理员最新信息
        $adminData = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TAdmin')->find($this->userid);
        $em = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TAdminRolePriv');
        foreach($menuData as $val) {
            $res = $em->findOneBy(['routename'=>$val->getRoutename(), 'roleid'=>$adminData->getRoleid()]);
            if($res) {
                $menu[] = $val;
            }
        }
        return $menu;
    }
}