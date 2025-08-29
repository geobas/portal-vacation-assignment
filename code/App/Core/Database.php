<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function connect(): PDO
    {
        if (self::$pdo === null) {
            $env = parse_ini_file(__DIR__ . '/../../.env');

            $dsn = "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4";
            try {
                self::$pdo = new PDO($dsn, $env['DB_USER'], $env['DB_PASS'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
            } catch (PDOException $e) {
                die('DB connection failed: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
