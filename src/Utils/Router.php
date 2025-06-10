<?php

namespace Utils;

class Router
{
    public const HTTP_METHOD_GET = 'GET';
    public const HTTP_METHOD_POST = 'POST';
    public const HTTP_METHOD_PUT = 'PUT';
    public const HTTP_METHOD_DELETE = 'DELETE';

    public function __construct(private array $routes = []) {}


    /**
     * Registers a route with the specified HTTP method and route pattern.
     *
     * @param string $httpMethod The HTTP method (GET, POST, PUT, DELETE).
     * @param string $route The route pattern.
     * @param callable $method The callable to handle the route.
     * @throws \InvalidArgumentException If the HTTP method is not supported.
     * @throws \RuntimeException If the route already exists for the specified method.
     */
    public function register(string $httpMethod, string $route, array $method): void
    {
        if (!$this->isAuthorizedMethod($httpMethod)) {
            throw new \InvalidArgumentException("HTTP method '$httpMethod' is not supported.");
        }

        if (isset($this->routes[$httpMethod][$route])) {
            throw new \RuntimeException("Route '$route' already exists for method '$httpMethod'.");
        }

        $this->routes[$httpMethod][$route] = $method;
    }

    /**
     * Calls the appropriate method based on the current request's HTTP method and URI.
     */
    public function call(): void
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];

        foreach ($this->routes[$httpMethod] as $route => $method) {
            if (preg_match($route, $requestUri, $matches)) {
                // Remove numeric keys from matches to avoid passing them as parameters
                $matches = array_filter($matches, fn($k) => !is_int($k), ARRAY_FILTER_USE_KEY);
                call_user_func_array($method, array_values($matches));
            }
        }

        // If no route matched, return a 404 response
        header('Content-Type: application/json', true, 404);
        echo json_encode(['error' => 'Not Found']);
    }

    /**
     * Finds a route based on the HTTP method and request URI.
     *
     * @param string $method
     * @return bool
     */
    private function isAuthorizedMethod(string $method): bool
    {
        return in_array($method, [
            self::HTTP_METHOD_GET,
            self::HTTP_METHOD_POST,
            self::HTTP_METHOD_PUT,
            self::HTTP_METHOD_DELETE
        ]);
    }
}