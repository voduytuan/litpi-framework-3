<?php

namespace Litpi;

class Router
{
    private $registry;
    private $args = array();

    public function __construct($registry)
    {
        $this->registry = $registry;

        $this->initRoute();
    }

    public function getArg($key = '')
    {
        if ($key != '') {
            if (!isset($this->args[$key])) {
                return null;
            }

            return $this->args[$key];
        } else {
            $output = '';
            //return full args string
            foreach ($this->args as $k => $v) {
                $output .= $k . '/' . $v . '/';
            }

            return $output;
        }

    }

    public function checkArg($key = '')
    {
        return isset($this->args[$key]);
    }

    public function delegate()
    {
        // Analyze route
        $this->getController($module, $controller, $action, $args);



        //assign args
        $this->extractArgs($args);

        $me = new \Model\User();
        $me->updateFromSession($this->registry);

        if ($me->checkPerm($this->registry, $notfound, $notlogin)) {
            $this->registry->me = $me;

        } elseif ($notfound) {
            $classmapList = classmapList();
            if (isset($classmapList[$module.'\notfound'])) {
                $controller = 'notfound';
            } else {
                $module = 'site';
                $controller = 'notfound';
            }
        } elseif ($notlogin) {
            $returnUrl = base64_encode(Helper::curPageURL());

            $classmapList = classmapList();
            if (isset($classmapList[$module.'\login'])) {
                $controller = 'login';
                $redirectUrl = $this->registry->conf['rooturl_' . $module] . 'login?redirect=' . $returnUrl;

            } else {
                $module = 'site';
                $controller = 'login';
                $redirectUrl = $this->registry->conf['rooturl'] . 'login?redirect=' . $returnUrl;
            }

            $this->registry->response->setStatusCode(302);
            $this->registry->response->headers->set('location', $redirectUrl);
        }

        if (!$notlogin) {

            //reassign module, controller and action because it can be change on the conditions above
            $this->registry->module = $module;
            $this->registry->controller = $controller;
            $this->registry->action = $action;


            // Initiate the class
            $class = '\\controller\\' . $module . '\\' . $controller;

            $controller = new $class($this->registry);

            //refine action string : append Action
            $action .= 'Action';

            // Run action
            $controller->$action();
        }

    }

    private function extractArgs($args)
    {
        if (count($args) == 0) {
            return false;
        }
        $this->args = $args;
        
        return true;
    }

    private function getController(&$module, &$controller, &$action, &$args)
    {

        $route = $this->registry->route;

        // Get separate parts
        $route = trim($route, '/\\');
        $parts = explode('/', $route);

        for ($i = 0; $i < count($parts); $i++) {
            $parts[$i] = $this->filterRouterInput($parts[$i]);
        }

        $module = array_shift($parts);
        $controller = array_shift($parts);
        $action = array_shift($parts);

        if (count($parts) > 0) {
            $args = $this->parseArgsString($parts);
        }
    }

    //param format: name1/value1/name2/value2
    private function parseArgsString($argArr)
    {
        $outputArr = array();

        for ($i = 0; $i < count($argArr); $i += 2) {
            if (isset($argArr[($i + 1)])) {
                $outputArr[$argArr[$i]] = strlen($argArr[($i + 1)]) == 0 ? '' : $argArr[($i + 1)];
            } else {
                $outputArr[$argArr[$i]] = '';
            }
        }

        return $outputArr;
    }

    public function filterRouterInput($input)
    {
        $output = $input;
        $output = htmlspecialchars($output);

        return $output;
    }

    private function initRoute($defaultModule = '')
    {
        $route = '';

        //get the filename of the request URI online - after '?' character
        if (($pos = strpos($this->registry->request->server->get('REQUEST_URI'), '?')) !== false) {
            $cleanURI = substr($this->registry->request->server->get('REQUEST_URI'), 0, $pos);
        } else {
            $cleanURI = $this->registry->request->server->get('REQUEST_URI');
        }

        if ($this->registry->request->query->get('route') == '' || $cleanURI == '/' || $cleanURI == '/index.php') {
            $route = $defaultModule;
        } else {
            $route = $this->registry->request->query->get('route');
        }

        rewriteruleParsing($route);

        return $route;
    }

    public static function getProtocol($registry)
    {
        $protocol = 'http';

        if ($registry->request->server->has('HTTP_X_FORWARDED_PROTO')) {
            $protocol = $registry->request->server->get('HTTP_X_FORWARDED_PROTO') == 'https' ? 'https' : 'http';

        } elseif ($registry->request->server->has('HTTPS')) {
            $protocol = $registry->request->server->get('HTTPS') != 'off' ? 'https' : 'http';
        }

        return $protocol;
    }

    public static function getHost($registry)
    {
        $host = '';

        if ($registry->request->server->has('HTTP_X_FORWARDED_HOST')
            && $host = $registry->request->server->get('HTTP_X_FORWARDED_HOST')) {
            $elements = explode(',', $host);
            $host = trim(end($elements));
        } else {
            if ($registry->request->server->has('HTTP_HOST')
                && !$host = $registry->request->server->get('HTTP_HOST')) {
                if ($registry->request->server->has('SERVER_NAME')
                    && !$host = $registry->request->server->get('SERVER_NAME')) {
                    $serverAddr = $registry->request->server->get('SERVER_ADDR');
                    $host = $serverAddr != '' ? $serverAddr : '';
                }
            }
        }

        // Remove port number from host
        $host = preg_replace('/:\d+$/', '', $host);

        return trim($host);
    }

    public static function redirectUrl($response, $url, $statusCode = 0)
    {
        $response->setStatusCode($statusCode);
        $response->headers->set('Location', $url);

        return $response;
    }

    public static function getSubdomain($request, $host)
    {
        $subdomain = str_replace($host, '', $request->server->get('HTTP_HOST'));

        return $subdomain;
    }

    public static function getUrlInHttps($request)
    {
        return 'https://' . $request->server->get('SERVER_NAME') . $request->server->get('REQUEST_URI');
    }
}
