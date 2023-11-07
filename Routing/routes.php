<?php

use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;

return [
    'random/part' => function (): HTTPRenderer {
        $part = DatabaseHelper::getRandomComputerPart();

        return new HTMLRenderer('component/computer-part-card/part', ['part' => $part]);
    },
    'parts' => function (): HTTPRenderer {
        // IDの検証
        $id = ValidationHelper::integer($_GET['id'] ?? null);

        $part = DatabaseHelper::getComputerPartById($id);
        return new HTMLRenderer('component/computer-part-card/part', ['part' => $part]);
    },
    'types' => function(): HTMLRenderer{
        $type = $_GET['type' ?? null];
        $page = ValidationHelper::integer($_GET['page'] ?? null);
        $perpage = ValidationHelper::integer($_GET['perpage'] ?? null);
        $parts = DatabaseHelper::getComputerPartsByType($type, $page, $perpage);
        $totalParts = DatabaseHelper::getTotalParts($type, $perpage);

        return new HTMLRenderer('component/computer-part-card/types',['type' => $type,'page' => $page, 'perpage' => $perpage,'parts' => $parts, 'totalParts' => $totalParts]);
        
    },
    'api/random/part' => function (): HTTPRenderer {
        $part = DatabaseHelper::getRandomComputerPart();
        return new JSONRenderer(['part' => $part]);
    },
    'api/parts' => function () {
        $id = ValidationHelper::integer($_GET['id'] ?? null);
        $part = DatabaseHelper::getComputerPartById($id);
        return new JSONRenderer(['part' => $part]);
    },
];
