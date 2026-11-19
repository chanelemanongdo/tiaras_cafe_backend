<?php

namespace App\Repositories;

use App\Models\Admin;
use PDO;

class AdminRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findByUsernameOrEmail(string $username): ?Admin
    {
        $sql = "
            SELECT admin_id, username, email, password, created_at
            FROM admin
            WHERE username = :username OR email = :email
            LIMIT 1
        ";

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            ':username' => $username,
            ':email' => $username
        ]);

        $row = $statement->fetch();

        if (!$row) {
            return null;
        }

        return new Admin(
            $row['username'],
            $row['email'],
            $row['password'],
            (int) $row['admin_id'],
            $row['created_at']
        );
    }
}
