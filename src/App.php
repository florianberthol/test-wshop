<?php

use Utils\Router;
use Controllers\Shop;

class App
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function run()
    {
        $shop = new Shop();
        $this->router->register(Router::HTTP_METHOD_GET, '/\/shops$/', [$shop, 'getAllShops']);
        $this->router->register(Router::HTTP_METHOD_GET, '/\/shop\/(?<id>[0-9]+)/', [$shop, 'getShop']);
        $this->router->register(Router::HTTP_METHOD_POST, '/\/shop$/', [$shop, 'postShop']);
        $this->router->register(Router::HTTP_METHOD_PUT, '/\/shop\/(?<id>[0-9]+)/', [$shop, 'putShop']);
        $this->router->register(Router::HTTP_METHOD_DELETE, '/\/shop\/(?<id>[0-9]+)/', [$shop, 'deleteShop']);

        $this->router->call();
    }
}