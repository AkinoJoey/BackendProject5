<?php

namespace Helpers;

use Database\MySQLWrapper;
use Exception;

class DatabaseHelper
{
    public static function getRandomComputerPart(): array
    {
        $db = new MySQLWrapper();

        $stmt = $db->prepare("SELECT * FROM computer_parts ORDER BY RAND() LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $part = $result->fetch_assoc();

        if (!$part) throw new Exception('Could not find a single part in database');

        return $part;
    }

    public static function getComputerPartById(int $id): array
    {
        $db = new MySQLWrapper();

        $stmt = $db->prepare("SELECT * FROM computer_parts WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $part = $result->fetch_assoc();

        if (!$part) throw new Exception('Could not find a single part in database');

        return $part;
    }

    public static function getComputerPartsByType(string $type, int $page, int $perpage) : array {
        $db = new MySQLWrapper();

        $offset = ($page - 1) * $perpage;
        $stmt = $db->prepare("SELECT * FROM computer_parts WHERE type = ? LIMIT ? OFFSET ?");
        $stmt->bind_param('sii', $type, $perpage, $page);
        $stmt->execute();

        $result = $stmt->get_result();
        $parts = [];

        while($row = $result->fetch_assoc()){
            $parts[] = $row;
        }

        if (!$parts) throw new Exception('Could not find parts in database');

        return $parts;
    }

    public static function getTotalParts(string $type, int $perpage) : int {
        $db = new MySQLWrapper();
        $stmt = $db->prepare("SELECT COUNT(*) FROM computer_parts WHERE type = ?");
        $stmt->bind_param('s', $type);
        $stmt->execute();

        $result = $stmt->get_result();
        
        return $result->fetch_row()[0];
    }
}
