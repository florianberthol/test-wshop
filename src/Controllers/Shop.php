<?php

namespace Controllers;

use Utils\Controller;
use Utils\DB;

class Shop extends Controller
{
    public function getAllShops()
    {
        $db = DB::getInstance()->getConnection();
        $query = $db->prepare("SELECT * FROM shop");
        $query->execute();
        $shop = $query->fetchAll(\PDO::FETCH_ASSOC);

        $this->jsonResponse($shop);
    }

    public function getShop($id)
    {
        $db = DB::getInstance()->getConnection();
        $query = $db->prepare("SELECT * FROM shop WHERE id = :id");
        $query->execute(['id' => $id]);
        $shop = $query->fetchAll(\PDO::FETCH_ASSOC);

        $this->jsonResponse($shop);
    }

    public function postShop()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Validation
        if (empty($data['name'])) {
            $this->jsonResponse(["error" => "You must set shop name"], 400);
            return;
        }
        if (!empty($data['name'] && !preg_match("/^[0-9]{5}$/", $data['zip']))) {
            $this->jsonResponse(["error" => "Invalid zip code"], 400);
            return;
        }

        $db = DB::getInstance()->getConnection();
        $query = $db->prepare("INSERT INTO shop (name, address, zip, city) VALUES (:name, :address, :zip, :city)");
        $query->execute([
            'name' => $data['name'],
            'address' => $data['address'] ?? null,
            'zip' => $data['zip'] ?? null,
            'city' => $data['city'] ?? null,
        ]);
        $lastId = $db->lastInsertId();

        $query = $db->prepare("SELECT * FROM shop WHERE id = :id");
        $query->execute(['id' => $lastId]);
        $shop = $query->fetchAll(\PDO::FETCH_ASSOC);

        $this->jsonResponse($shop);
    }

    public function putShop(int $id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $db = DB::getInstance()->getConnection();

        $queryString = "UPDATE shop SET ";

        $updatableFields = [];
        $params = [];
        if (!empty($data['name'])) {
            $updatableFields[] = "name = :name";
            $params['name'] = $data['name'];
        }
        if (!empty($data['address'])) {
            $updatableFields[] = "address = :address";
            $params['address'] = $data['address'];
        }
        if (!empty($data['zip']) && preg_match("/^[0-9]{5}$/", $data['zip'])) {
            $updatableFields[] = "zip = :zip";
            $params['zip'] = $data['zip'];
        }
        if (!empty($data['city'])) {
            $updatableFields[] = "city = :city";
            $params['city'] = $data['city'];
        }
        $params['id'] = $id;

        $queryString .= implode(', ', $updatableFields);
        $queryString .= " WHERE id = :id";
        $query = $db->prepare($queryString);
        $query->execute($params);

        $query = $db->prepare("SELECT * FROM shop WHERE id = :id");
        $query->execute(['id' => $id]);
        $shop = $query->fetchAll(\PDO::FETCH_ASSOC);
        $this->jsonResponse($shop);
    }

    public function deleteShop(int $id)
    {
        $db = DB::getInstance()->getConnection();
        $query = $db->prepare("DELETE FROM shop WHERE id = :id");
        $query->execute(['id' => $id]);

        $this->jsonResponse(["Shop $id deleted successfully!"]);
    }
}