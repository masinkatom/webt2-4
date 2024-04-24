<?php
class Place
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function getAllPlaces()
    {
        $query = "SELECT * FROM places";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $timetableActions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $timetableActions;
    }

    public function getPlaceById($id)
    {
        $query = "SELECT * FROM places WHERE id = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $place = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $place;
    }

    public function getIdByName($name)
    {
        $query = "SELECT id FROM places WHERE place = :place";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':place', $name, PDO::PARAM_STR);
        $stmt->execute();

        $id = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $id;
    }

    public function addPlace($data)
    {
        $place = $data['place'];
        $country = $data['country'];
        $searched_amount = 0;

        $query = "INSERT INTO places (place, country, searched_amount) 
        VALUES (:place, :country, :searched_amount)";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':place', $place, PDO::PARAM_STR);
        $stmt->bindParam(':country', $country, PDO::PARAM_STR);
        $stmt->bindParam(':searched_amount', $searched_amount, PDO::PARAM_INT);

        $result = $stmt->execute();
        return $result;
    }

    public function updatePlaceSearchedAmount($data)
    {
        $placeName = $data["place"];
        $id = 0;

        $res = $this->getIdByName($placeName);

        if (!empty($res) && isset($res[0]['id'])) {
            $id = $res[0]["id"];
        }
        else {
            $this->addPlace($data);
            $id = $this->getIdByName($placeName)[0]["id"];
        }

        
        $place = $this->getPlaceById($id);
        $searched_amount = $place[0]["searched_amount"] + 1;

        $query = "UPDATE places SET searched_amount=:searched_amount WHERE id=:id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':searched_amount', $searched_amount, PDO::PARAM_INT);

        $result = $stmt->execute();
        return $result;

    }
    public function deletePlace($id)
    {
        $query = "DELETE FROM places WHERE id=:id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $result = $stmt->execute();
        return $result;
    }
}
