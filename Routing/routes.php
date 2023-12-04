<?php

use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;
use Database\DataAccess\Implementations\ComputerPartDAOImpl;
use Faker\Calculator\Ean;
use Types\ValueType;
use Models\ComputerPart;

return [
    'random/part' => function (): HTTPRenderer {
        $partDao = new ComputerPartDAOImpl();
        $part = $partDao->getRandom();

        if($part === null) throw new Exception("NO parts are available!");

        return new HTMLRenderer('component/computer-part-card/part', ['part' => $part]);
    },
    'parts' => function (): HTTPRenderer {
        // IDの検証
        $id = ValidationHelper::integer($_GET['id'] ?? null);

        $partDao = new ComputerPartDAOImpl();
        $part = $partDao->getById($id);

        if ($part === null) throw new Exception('Specified part was not found!');

        return new HTMLRenderer('component/computer-part-card/part', ['part' => $part]);
    },
    'types' => function(): HTMLRenderer{
        $type = $_GET['type'] ?? null;
        $page = ValidationHelper::integer($_GET['page'] ?? null);
        $perpage = ValidationHelper::integer($_GET['perpage'] ?? null);
        $parts = DatabaseHelper::getComputerPartsByType($type, $page, $perpage);
        $totalParts = DatabaseHelper::getTotalPartsByType($type);
        $queryFirstParam = '?type=' . $type . '&';
        return new HTMLRenderer('component/computer-part-card/parts',['type' => $type,'page' => $page, 'perpage' => $perpage,'parts' => $parts, 'totalParts' => $totalParts, 'queryFirstParam' => $queryFirstParam]);
        
    },
    'random/computer' => function(): HTMLRenderer{
        $cpu = DatabaseHelper::getRandomComputerPartByType('CPU');
        $gpu = DatabaseHelper::getRandomComputerPartByType('GPU');
        $ram = DatabaseHelper::getRandomComputerPartByType('RAM');
        $ssd = DatabaseHelper::getRandomComputerPartByType('SSD');
        $hdd = DatabaseHelper::getRandomComputerPartByType('HDD');

        $parts = [$cpu, $gpu, $ram, $ssd, $hdd];
        return new HTMLRenderer('component/computer-part-card/computer', ['parts'=> $parts]);
    },
    'parts/newest' => function(): HTMLRenderer{
        $page = ValidationHelper::integer($_GET['page'] ?? null);
        $perpage = ValidationHelper::integer($_GET['perpage'] ?? null);
        $parts = DatabaseHelper::getNewestComputerParts($page, $perpage);
        $totalParts = DatabaseHelper::getTotalParts();
        $queryFirstParam = 'newest?';
        return new HTMLRenderer('component/computer-part-card/parts', ['page' => $page, 'perpage' => $perpage, 'parts' => $parts, 'totalParts' => $totalParts, 'queryFirstParam' => $queryFirstParam]);
        
    },
    'parts/performance' => function() : HTMLRenderer {
        $order = $_GET['order'] ?? null;
        $parts = DatabaseHelper::getPerformanceRankedComputerParts($order);

        return new HTMLRenderer('component/computer-part-card/performance', ['parts' => $parts]);
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
    'update/part' => function (): HTMLRenderer {
        $part = null;
        $partDao = new ComputerPartDAOImpl();
        if (isset($_GET['id'])) {
            $id = ValidationHelper::integer($_GET['id']);
            $part = $partDao->getById($id);
        }
        return new HTMLRenderer('component/update-computer-part', ['part' => $part]);
    },
    'form/update/part' => function (): HTTPRenderer {
        try {
            // リクエストメソッドがPOSTかどうかをチェックします
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $required_fields = [
                'name' => ValueType::STRING,
                'type' => ValueType::STRING,
                'brand' => ValueType::STRING,
                'modelNumber' => ValueType::STRING,
                'releaseDate' => ValueType::DATE,
                'description' => ValueType::STRING,
                'performanceScore' => ValueType::INT,
                'marketPrice' => ValueType::FLOAT,
                'rsm' => ValueType::FLOAT,
                'powerConsumptionW' => ValueType::FLOAT,
                'lengthM' => ValueType::FLOAT,
                'widthM' => ValueType::FLOAT,
                'heightM' => ValueType::FLOAT,
                'lifespan' => ValueType::INT,
            ];

            $partDao = new ComputerPartDAOImpl();

            // 入力に対する単純なバリデーション。実際のシナリオでは、要件を満たす完全なバリデーションが必要になることがあります。
            $validatedData = ValidationHelper::validateFields($required_fields, $_POST);

            if (isset($_POST['id'])) $validatedData['id'] = ValidationHelper::integer($_POST['id']);

            // 名前付き引数を持つ新しいComputerPartオブジェクトの作成＋アンパッキング
            $part = new ComputerPart(...$validatedData);

            error_log(json_encode($part->toArray(), JSON_PRETTY_PRINT));

            // 新しい部品情報でデータベースの更新を試みます。
            // 別の方法として、createOrUpdateを実行することもできます。
            if (isset($validatedData['id'])) $success = $partDao->update($part);
            else $success = $partDao->create($part);

            if (!$success) {
                throw new Exception('Database update failed!');
            }

            return new JSONRenderer(['status' => 'success', 'message' => 'Part updated successfully']);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage()); // エラーログはPHPのログやstdoutから見ることができます。
            return new JSONRenderer(['status' => 'error', 'message' => 'Invalid data.']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return new JSONRenderer(['status' => 'error', 'message' => 'An error occurred.']);
        }
    },
    'delete/part' => function () : HTMLRenderer | JSONRenderer {
        // IDの検証
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return new HTMLRenderer('component/delete-computer-part');
        }elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE'){
            $id = ValidationHelper::integer($_GET['id'] ?? null);

            $partDao = new ComputerPartDAOImpl();
            $part = $partDao->getById($id);

            if ($part === null) return new JSONRenderer(['status' => 'error', 'message' => 'Specified part was not found!']);

            $deleteResult = $partDao->delete($id);

            if (!$deleteResult) throw new Exception("Delete statement was failed!");

            return new JSONRenderer(['status' => 'success']);
        }
        
    },
];
