<?php

namespace Apps\Cores\Views\Layouts;

use \Libs\Layout;
use Libs\Menu;

class TwoColsLayout extends Layout
{

    protected $title;
    protected $header;
    protected $brand = 'Brand';

    /** @var Menu */
    protected $sideMenu;

    function init()
    {
        $this->title = \Config::APP_NAME;
        $this->sideMenu = new Menu(null, null, null, array(
            new Menu('user', '<i class="fa fa-user"></i> Tài khoản', url('/admin/user')),
            new Menu('group', '<i class="fa fa-folder-open"></i> Nhóm', url('/admin/group')),
            new Menu('list', '<i class="fa fa-list"></i> Danh mục động', url('/admin/list')),
        ));
    }

    function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    function setBrand($brand)
    {
        $this->brand = $brand;
        return $this;
    }

    function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    protected function renderSideMenu($children, $level = 1)
    {
        $ulClasses = array(
            1 => 'nav nav-second-level',
            2 => 'nav nav-third-level'
        );
        foreach ($children as $menu)
        {
            /* @var $menu Menu */
            if ($menu->hasChildren())
            {
                echo '<li>';
                echo "<a href='{$menu->url}'>{$menu->label} <span class='fa arrow'></span></a>";
                echo "<ul class='" . $ulClasses[$level] . "'>";
                $this->renderSideMenu($menu->children, $level + 1);
                echo "</ul>";
                echo '</li>';
            }
            else
            {
                echo "<li><a href='{$menu->url}'>{$menu->label}</a>";
            }
        }
    }

    protected function renderLayout($content)
    {
        ?>

        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="description" content="">
                <meta name="author" content="">

                <title><?php echo $this->title ?></title>

                <!-- Bootstrap Core CSS -->
                <link href="<?php echo url() ?>/themes/sb2/css/bootstrap.min.css" rel="stylesheet">

                <!-- MetisMenu CSS -->
                <link href="<?php echo url() ?>/themes/sb2/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

                <!-- Custom CSS -->
                <link href="<?php echo url() ?>/themes/sb2/css/sb-admin-2.css" rel="stylesheet">

                <!-- Custom Fonts -->
                <link href="<?php echo url() ?>/themes/sb2/css/font-awesome.min.css" rel="stylesheet" type="text/css">
                <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300italic,300&subset=latin,vietnamese' rel='stylesheet' type='text/css'>

                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                <!--[if lt IE 9]>
                    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
                <![endif]-->

                <link href="<?php echo url() ?>/themes/sb2/css/custom.css" rel="stylesheet" type="text/css">

                <!-- jQuery -->
                <script src="<?php echo url() ?>/themes/sb2/js/jquery.min.js"></script>
            </head>

            <body ng-app="sb2">

                <div id="wrapper">

                    <!-- Navigation -->
                    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="<?php echo url() ?>"><?php echo $this->brand ?></a>
                        </div>
                        <!-- /.navbar-header -->

                        <ul class="nav navbar-top-links navbar-right">
                            <!-- /.dropdown -->
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                                    </li>
                                </ul>
                                <!-- /.dropdown-user -->
                            </li>
                            <!-- /.dropdown -->
                        </ul>
                        <!-- /.navbar-top-links -->

                        <div class="navbar-default sidebar" role="navigation">
                            <div class="sidebar-nav navbar-collapse">
                                <ul class="nav" id="side-menu">
                                    <?php $this->renderSideMenu($this->sideMenu->children) ?>
                                </ul>
                            </div>
                            <!-- /.sidebar-collapse -->
                        </div>
                        <!-- /.navbar-static-side -->
                    </nav>

                    <div id="page-wrapper">
                        <?php if ($this->header): ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h1 class="page-header"><?php echo $this->header ?></h1>
                                </div>
                                <!-- /.col-lg-12 -->
                            </div>
                        <?php endif; ?>
                        <?php echo $content ?>
                    </div>
                    <!-- /#page-wrapper -->

                </div>
                <!-- /#wrapper -->

                <!--angular-->
                <script src="<?php echo url() ?>/themes/sb2/js/angular.min.js"></script>

                <!-- Bootstrap Core JavaScript -->
                <script src="<?php echo url() ?>/themes/sb2/js/bootstrap.min.js"></script>

                <!-- Metis Menu Plugin JavaScript -->
                <script src="<?php echo url() ?>/themes/sb2/plugins/metisMenu/metisMenu.min.js"></script>

                <!-- Custom Theme JavaScript -->
                <script src="<?php echo url() ?>/themes/sb2/js/sb-admin-2.js"></script>
                <script src="<?php echo url() ?>/themes/sb2/js/custom.js"></script>
            </body>

        </html>

        <?php
    }

}
