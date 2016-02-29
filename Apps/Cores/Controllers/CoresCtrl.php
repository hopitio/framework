<?php

namespace Apps\Cores\Controllers;

use Apps\Cores\Views\Layouts\TwoColsLayout;
use Apps\Cores\Views\Layouts\ContentOnlyLayout;
use Apps\Cores\Models\UserEntity;
use Apps\Cores\Models\UserMapper;
use Libs\Menu;

abstract class CoresCtrl extends \Libs\Controller
{

    /**
     * Không dùng trực tiếp biến này, phải dùng ->user()
     * @var \Apps\Cores\Models\UserEntity 
     */
    private $user;
    private $userSeed = array();

    /** @var TwoColsLayout */
    protected $twoColsLayout;

    /** @var ContentOnlyLayout */
    protected $contentOnlyLayout;
    protected $themeConfig;

    /** @var \Apps\Cores\Models\UserEntity */
    protected function user()
    {
        if (!$this->userSeed)
        {
            return new UserEntity;
        }

        if (!$this->user)
        {
            $user = UserMapper::makeInstance()
                    ->filterPk($this->userSeed['pk'])
                    ->getEntity();
            if ($user->pass != $this->userSeed['pass'])
            {
                return new UserEntity;
            }

            $this->user = $user;
        }

        return $this->user;
    }

    protected function init()
    {
        $this->userSeed = $this->session->get('user');

        $this->themeConfig = $themeConfig = getConfig('Themes/sb2.config.php');
        $this->twoColsLayout = new TwoColsLayout($this->context);
        $this->twoColsLayout->setTemplatesDirectory(dirname(__DIR__) . '/Views');
        $this->twoColsLayout
                ->setBasicInfo($themeConfig['brand'], $themeConfig['companyWebsite'])
                ->setUser($this->user())
                ->setSideMenu(new Menu(null, null, null, array(
                    new Menu('user', '<i class="fa fa-user"></i> Tài khoản', url('/admin/user')),
                    new Menu('group', '<i class="fa fa-folder-open"></i> Nhóm', url('/admin/group')),
                    new Menu('setting', '<i class="fa fa-cog"></i> Tùy chỉnh', url('/admin/setting'))
        )));

        $this->contentOnlyLayout = new ContentOnlyLayout($this->context);
        $this->contentOnlyLayout->setTemplatesDirectory(dirname(__DIR__) . '/Views');
        $this->contentOnlyLayout->setBrand($themeConfig['brand']);
    }

    protected function requireLogin()
    {
        if (!$this->user() || !$this->user()->pk)
        {
            $uri = str_replace(url(), '/', $_SERVER['REQUEST_URI']);
            $this->resp->redirect(url('/admin/login?callback=' . $uri));
        }
    }

    protected function requireAdmin()
    {
        $this->requireLogin();
        if (!$this->user()->isAdmin)
        {
            $this->resp->setStatus(403);
            $this->resp->setBody('Bạn không có quyền truy cập chức năng này');
        }
    }

    function __destruct()
    {
        $uri = $_SERVER['REQUEST_URI'];
        //không lưu js, css, login
        if (strpos($uri, '.js') !== false || strpos($uri, '.css') !== false || strpos($uri, '/login') !== false)
        {
            return;
        }

        $histories = $this->session->get('histories', array());

        //nếu trùng xóa history cũ đẩy cái mới lên
        foreach ($histories as $i => $page)
        {
            if ($page == $uri)
            {
                array_splice($histories, $i, 1);
            }
        }
        $histories[] = $uri;

        //chỉ giữ lại 5 cái gần nhất
        while (count($histories) > 5)
        {
            array_shift($histories);
        }

        $this->session->set('histories', $histories);
    }

}
