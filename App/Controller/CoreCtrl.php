<?php

namespace App\Controller;

use App\View\Layout\DefaultLayout;
use App\View\Layout\ContentOnlyLayout;
use App\Model\UserEntity;
use App\Model\UserMapper;
use Lib\Menu;

abstract class CoreCtrl extends \Lib\Controller {

    /**
     * Không dùng trực tiếp biến này, phải dùng ->user()
     * @var \App\Model\UserEntity 
     */
    private $user;
    private $userSeed = array();

    /** @var DefaultLayout */
    protected $twoColsLayout;

    /** @var ContentOnlyLayout */
    protected $contentOnlyLayout;

    /** @var \Lib\Setting */
    protected $setting;

    /** @var \App\Model\UserEntity */
    protected function user() {
        if (!$this->userSeed) {
            return new UserEntity;
        }

        if (!$this->user) {
            $user = UserMapper::makeInstance()
                    ->filterPk($this->userSeed['pk'])
                    ->getEntity();
            if ($user->pass != $this->userSeed['pass']) {
                return new UserEntity;
            }

            $this->user = $user;
        }

        return $this->user;
    }

    protected function init() {
        parent::init();

        $this->userSeed = $this->session->get('user');
        $this->setting = new \Lib\Setting('Cores');

        $this->twoColsLayout = new DefaultLayout($this->context);
        $this->twoColsLayout->setTemplatesDirectory(dirname(__DIR__) . '/View');
        $this->twoColsLayout
                ->setBrand($this->setting->getSetting('themeBrand'))
                ->setCompanyWebsite($this->setting->getSetting('themeCompanyWebsite'))
                ->setUser($this->user())
                ->setSideMenu(new Menu(null, null, null, array(
                    'map'     => new Menu('map', '<i class="fa fa-truck"></i> Quản lý liên thông', url('/admin/map')),
                    'user'    => new Menu('user', '<i class="fa fa-user"></i> Tài khoản', url('/admin/user')),
                    'group'   => new Menu('group', '<i class="fa fa-folder-open"></i> Nhóm', url('/admin/group')),
                    'setting' => new Menu('setting', '<i class="fa fa-cog"></i> Cấu hình hệ thống', url('/admin/setting')),
                    'app'     => new Menu('app', '<i class="fa fa-th-large"></i> Quản lý ứng dụng', url('/admin/application'))
        )));

        $this->contentOnlyLayout = new ContentOnlyLayout($this->context);
        $this->contentOnlyLayout->setTemplatesDirectory(dirname(__DIR__) . '/View');
    }

    protected function requireLogin() {
        if (!$this->user() || !$this->user()->pk) {
            $uri = $_SERVER['REQUEST_URI'];
            $this->resp->redirect(url('/account/login?callback=' . $uri));
        }
    }

    protected function requireAdmin() {
        $this->requireLogin();
        if (!$this->user()->isAdmin) {
            $this->resp->setStatus(403);
            $this->resp->setBody('Bạn không có quyền truy cập chức năng này');
        }
    }

    function __destruct() {
        $uri = $_SERVER['REQUEST_URI'];
        //không lưu js, css, login
        if (strpos($uri, '.js') !== false || strpos($uri, '.css') !== false || strpos($uri, '/login') !== false) {
            return;
        }

        $histories = $this->session->get('histories', array());

        //nếu trùng xóa history cũ đẩy cái mới lên
        foreach ($histories as $i => $page) {
            if ($page == $uri) {
                array_splice($histories, $i, 1);
            }
        }
        $histories[] = $uri;

        //chỉ giữ lại 5 cái gần nhất
        while (count($histories) > 5) {
            array_shift($histories);
        }

        $this->session->set('histories', $histories);
    }

}
