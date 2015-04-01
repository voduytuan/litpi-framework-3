<?php

namespace Codeception\Lib\Connector;

use Symfony\Component\BrowserKit\Client as BrowserKitClient;
use Symfony\Component\BrowserKit\Response;

class LitpiConnectorHelper extends BrowserKitClient
{
    use Shared\PhpSuperGlobalsConverter;

    private $registry;

    public function setRegistry($registry)
    {
        $this->registry = $registry;
    }

    public function doRequest($request)
    {
        //Parsing route
        $route = str_replace('http://localhost/', '', $request->getUri());

        $this->registry->request->query->set('route', $route);
        $router = new \Litpi\Router($this->registry);
        $this->registry->router = $router;
        $router->delegate();

        //render content
        $headers = $this->registry->response->headers->all();
        $content = $this->registry->response->getContent();

        $connectorResponse = new Response($content, $this->registry->response->getStatusCode(), $headers);
        return $connectorResponse;
    }
}
