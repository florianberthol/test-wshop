<?php

namespace Utils;

class Controller
{
    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json', true, $statusCode);
        echo json_encode($data);
        exit;
    }
}