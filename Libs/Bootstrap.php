<?php

namespace Libs;

class Bootstrap
{

    static protected $instance;

    /** @var \Slim\Slim */
    public $slim;
    public $rewriteBase;

    /** @return Bootstrap */
    static function getInstance()
    {
        return static::$instance;
    }

    function __construct()
    {
        static::$instance = $this;
        //debug mode
        $debug = isset($_GET['debug']) ? 10 : \Config::DEBUG_MODE;
        if ($debug)
        {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        }
        else
        {
            ini_set('display_errors', 0);
            error_reporting(0);
        }

        //read rewritebase
        $htaccess = file_get_contents(BASE_DIR . '/Docroot/.htaccess');
        preg_match('@RewriteBase\s*(.*)$@m', $htaccess, $matches);
        $this->rewriteBase = $matches[1];

        //create slim instance
        \Slim\Slim::registerAutoloader();
        $this->slim = new \Slim\Slim(array(
            'cookies.encrypt'     => true,
            'cookies.lifetime'    => 20 * 365 * 24 * 60 . ' minutes',
            'cookies.path'        => $this->rewriteBase,
            'cookies.secure'      => false,
            'cookies.secret_key'  => \Config::CRYPT_SECRECT,
            'cookies.cipher'      => MCRYPT_RIJNDAEL_256,
            'cookies.cipher_mode' => MCRYPT_MODE_CBC
        ));
        //config session
        $this->slim->add(new \Slim\Middleware\SessionCookie(array(
            'expires'     => 20 * 365 * 24 * 60 . ' minutes',
            'path'        => $this->rewriteBase,
            'domain'      => null,
            'secure'      => false,
            'name'        => 'slim_session',
            'secret'      => \Config::CRYPT_SECRECT,
            'cipher'      => MCRYPT_RIJNDAEL_256,
            'cipher_mode' => MCRYPT_MODE_CBC
        )));

        //routing
        require_once BASE_DIR . '/routes.php';
        $this->appendRoute($routes);

        //run slim application
        $this->slim->run();
    }

    protected function appendRoute($routes, $prefix = '')
    {
        $bootstrap = $this;

        foreach ($routes as $key => $item)
        {
            if (is_object($item))
            {
                /* @var $item MvcContext */
                $context = $item;
                $context->app = $this;
                $context->rewriteBase = $this->rewriteBase;
                $map = $this->slim->map($prefix . $key, function() use($bootstrap, $context)
                {
                    $bootstrap->executeAction($context, func_get_args());
                });
                //via method
                $methods = array();
                if ($context->method == '*')
                {
                    $methods = array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH');
                }
                else
                {
                    $methods = array(strtoupper($context->method));
                }
                call_user_func_array(array($map, 'via'), $methods);
            }
            else
            {
                $this->appendRoute($item, $prefix . $key);
            }
        }
    }

    function executeAction(MvcContext $context, $args)
    {
        //create controller
        $controller = $this->createController($context);
        if (!$controller)
        {
            $this->notFound();
            return;
        }
        //execute action
        if (!is_callable(array($controller, $context->action)))
        {
            $this->notFound();
            return;
        }
        call_user_func_array(array($controller, $context->action), $args);
    }

    function notFound()
    {
        $this->slim->response->setStatus(404);
        $this->slim->response->setBody('Page Not Found');
    }

    protected function createController(MvcContext $context)
    {
        $class = $context->controller;
        if (class_exists($class))
        {
            $controller = new $class($context);
            return $controller;
        }
    }

}
