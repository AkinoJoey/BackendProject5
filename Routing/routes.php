<?php

use Database\DataAccess\DAOFactory;
use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;
use Database\DataAccess\Implementations\ComputerPartDAOImpl;
use Faker\Calculator\Ean;
use Types\ValueType;
use Models\ComputerPart;
use Helpers\Authenticate;
use Response\FlashData;
use Response\Render\RedirectRenderer;
use Models\User;
use Exceptions\AuthenticationFailureException;


return [
    'random/part' => function (): HTTPRenderer {
        $partDao = DAOFactory::getComputerPartDAO();
        $part = $partDao->getRandom();

        if($part === null) throw new Exception("NO parts are available!");

        return new HTMLRenderer('component/computer-part-card/part', ['part' => $part]);
    },
    'parts' => function (): HTTPRenderer {
        // IDの検証
        $id = ValidationHelper::integer($_GET['id'] ?? null);

        $partDao = DAOFactory::getComputerPartDAO();
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
    'update/part' => function (): HTTPRenderer {
        if (!Authenticate::isLoggedIn()) {
            FlashData::setFlashData('error', 'Permission Denied.');
            return new RedirectRenderer('random/part');
        }

        $part = null;
        $partDao = DAOFactory::getComputerPartDAO();
        if (isset($_GET['id'])) {
            $id = ValidationHelper::integer($_GET['id']);
            $part = $partDao->getById($id);
        }
        return new HTMLRenderer('component/update-computer-part', ['part' => $part]);
    },
    'form/update/part' => function (): HTTPRenderer {
        if (!Authenticate::isLoggedIn()) {
            FlashData::setFlashData('error', 'Permission Denied.');
            return new RedirectRenderer('random/part');
        }
        
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

            $partDao = DAOFactory::getComputerPartDAO();

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

            $partDao = DAOFactory::getComputerPartDAO();
            $part = $partDao->getById($id);

            if ($part === null) return new JSONRenderer(['status' => 'error', 'message' => 'Specified part was not found!']);

            $deleteResult = $partDao->delete($id);

            if (!$deleteResult) throw new Exception("Delete statement was failed!");

            return new JSONRenderer(['status' => 'success']);
        }
    },
    'parts/all' => function () : HTMLRenderer {
        $partDap = DAOFactory::getComputerPartDAO();
        $parts = $partDap->getAll(0, 15);

        if($parts === null) throw new Exception("NO parts are available!");

        return new HTMLRenderer('component/computer-part-card/all',['parts' => $parts]);
    },
    'parts/type' => function () : HTMLRenderer {
        $type = $_GET['type'] ?? null;
        $partDap = new ComputerPartDAOImpl();
        $totalParts = $partDap->getCountByType($type);
        $parts = $partDap->getAllByType($type, 0, $totalParts);

        if ($parts === null) throw new Exception("NO parts are available!");

        return new HTMLRenderer('component/computer-part-card/all', ['parts' => $parts]);
    },
    'register' => function (): HTTPRenderer {
        if (Authenticate::isLoggedIn()) {
            FlashData::setFlashData('error', 'Cannot register as you are already logged in.');
            return new RedirectRenderer('random/part');
        }

        return new HTMLRenderer('page/register');
    },
    'form/register' => function (): HTTPRenderer {
        // ユーザが現在ログインしている場合、登録ページにアクセスすることはできません。
        if (Authenticate::isLoggedIn()) {
            FlashData::setFlashData('error', 'Cannot register as you are already logged in.');
            return new RedirectRenderer('random/part');
        }

        try {
            // リクエストメソッドがPOSTかどうかをチェックします
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $required_fields = [
                'username' => ValueType::STRING,
                'email' => ValueType::EMAIL,
                'password' => ValueType::PASSWORD,
                'confirm_password' => ValueType::PASSWORD,
                'company' => ValueType::STRING,
            ];

            $userDao = DAOFactory::getUserDAO();

            // シンプルな検証
            $validatedData = ValidationHelper::validateFields($required_fields, $_POST);

            if ($validatedData['confirm_password'] !== $validatedData['password']) {
                FlashData::setFlashData('error', 'Invalid Password!');
                return new RedirectRenderer('register');
            }

            // Eメールは一意でなければならないので、Eメールがすでに使用されていないか確認します
            if ($userDao->getByEmail($validatedData['email'])) {
                FlashData::setFlashData('error', 'Email is already in use!');
                return new RedirectRenderer('register');
            }

            // 新しいUserオブジェクトを作成します
            $user = new User(
                username: $validatedData['username'],
                email: $validatedData['email'],
                company: $validatedData['company']
            );

            // データベースにユーザーを作成しようとします
            $success = $userDao->create($user, $validatedData['password']);

            if (!$success) throw new Exception('Failed to create new user!');

            // ユーザーログイン
            Authenticate::loginAsUser($user);

            FlashData::setFlashData('success', 'Account successfully created.');
            return new RedirectRenderer('random/part');
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'Invalid Data.');
            return new RedirectRenderer('register');
        } catch (Exception $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'An error occurred.');
            return new RedirectRenderer('register');
        }
    },
    'logout' => function (): HTTPRenderer {
        if (!Authenticate::isLoggedIn()) {
            FlashData::setFlashData('error', 'Already logged out.');
            return new RedirectRenderer('random/part');
        }

        Authenticate::logoutUser();
        FlashData::setFlashData('success', 'Logged out.');
        return new RedirectRenderer('random/part');
    },
    'login' => function (): HTTPRenderer {
        if (Authenticate::isLoggedIn()) {
            FlashData::setFlashData('error', 'You are already logged in.');
            return new RedirectRenderer('random/part');
        }

        return new HTMLRenderer('page/login');
    },
    'form/login' => function (): HTTPRenderer {
        if (Authenticate::isLoggedIn()) {
            FlashData::setFlashData('error', 'You are already logged in.');
            return new RedirectRenderer('random/part');
        }

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $required_fields = [
                'email' => ValueType::EMAIL,
                'password' => ValueType::STRING,
            ];

            $validatedData = ValidationHelper::validateFields($required_fields, $_POST);

            Authenticate::authenticate($validatedData['email'], $validatedData['password']);

            FlashData::setFlashData('success', 'Logged in successfully.');
            return new RedirectRenderer('update/part');
        } catch (AuthenticationFailureException $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'Failed to login, wrong email and/or password.');
            return new RedirectRenderer('login');
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'Invalid Data.');
            return new RedirectRenderer('login');
        } catch (Exception $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'An error occurred.');
            return new RedirectRenderer('login');
        }
    },
];
