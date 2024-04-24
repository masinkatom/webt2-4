<?php
class Stat
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function updateStat($id)
    {
        $query = "UPDATE unique_users SET amount = amount + 1 WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $result = $stmt->execute();
        return $result;

    }
}
