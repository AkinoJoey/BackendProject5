<?php

use Exceptions\AuthenticationFailureException;
use Helpers\ValidationHelper;
use Helpers\Authenticate;
use Models\ComputerPart;
use Response\FlashData;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\RedirectRenderer;
use Database\DataAccess\DAOFactory;
use Response\Render\JSONRenderer;
use Routing\Route;
use Types\ValueType;
use Models\User;
use Response\Render\MediaRenderer;

return [
    'login' => Route::create('login', function (): HTTPRenderer {
        return new HTMLRenderer('page/login');
    })->setMiddleware(['guest']),
    'form/login' => Route::create('form/login', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $required_fields = [
                'email' => ValueType::EMAIL,
                'password' => ValueType::STRING,
            ];

            $validatedData = ValidationHelper::validateFields($required_fields, $_POST);

            Authenticate::authenticate($validatedData['email'], $validatedData['password']);

            FlashData::setFlashData('success', 'Logged in successfully.');
            return new RedirectRenderer('random/part');
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
    })->setMiddleware(['guest']),
    'register' => Route::create('register', function (): HTTPRenderer {
        return new HTMLRenderer('page/register');
    })->setMiddleware(['guest']),
    'form/register' => Route::create('form/register', function (): HTTPRenderer {
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

            // 期限を30分に設定
            $lasts = 1 * 60 * 30;
            $param = [
                'id' => $user->getId(),
                'user' => hash('sha256', $user->getEmail()),
                'expiration' => time() + $lasts
            ];
            
            $signedUrl = Route::create('verify/email', function(){})->getSignedURL($param);
            Authenticate::sendVerificationEmail($user, $signedUrl);

            FlashData::setFlashData('success', 'Account successfully created. Please check your email!');
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
    })->setMiddleware(['guest']),
    'logout' => Route::create('logout', function (): HTTPRenderer {
        Authenticate::logoutUser();
        FlashData::setFlashData('success', 'Logged out.');
        return new RedirectRenderer('random/part');
    })->setMiddleware(['auth']),
    'random/part' => Route::create('random/part', function (): HTTPRenderer {
        $partDao = DAOFactory::getComputerPartDAO();
        $part = $partDao->getRandom();

        if ($part === null) throw new Exception('No parts are available!');

        return new HTMLRenderer('component/computer-part-card/part', ['part' => $part]);
    }),
    'parts' => Route::create('parts', function (): HTTPRenderer {
        // IDの検証
        $id = ValidationHelper::integer($_GET['id'] ?? null);

        $partDao = DAOFactory::getComputerPartDAO();
        $part = $partDao->getById($id);

        if ($part === null) throw new Exception('Specified part was not found!');

        return new HTMLRenderer('component/computer-part-card/part', ['part' => $part]);
    }),
    'update/part' => Route::create('update/part', function (): HTTPRenderer {
        $user = Authenticate::getAuthenticatedUser();
        $part = null;
        $partDao = DAOFactory::getComputerPartDAO();
        if (isset($_GET['id'])) {
            $id = ValidationHelper::integer($_GET['id']);
            $part = $partDao->getById($id);
            if ($user->getId() !== $part->getSubmittedById()) {
                FlashData::setFlashData('error', 'Only the author can edit this computer part.');
                return new RedirectRenderer('register');
            }
        }
        return new HTMLRenderer('component/update-computer-part', ['part' => $part]);
    })->setMiddleware(['auth', 'verify']),
    'form/update/part' => Route::create('form/update/part', function (): HTTPRenderer {
        try {
            // クエストメソッドがPOSTかどうかをチェックします
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

            // 入力に対する単純な認証。実際のシナリオでは、要件を満たす完全な認証が必要になることがあります
            $validatedData = ValidationHelper::validateFields($required_fields, $_POST);

            $user = Authenticate::getAuthenticatedUser();

            // idが設定されている場合は、認証を行います
            if (isset($_POST['id'])) {
                $validatedData['id'] = ValidationHelper::integer($_POST['id']);
                $currentPart = $partDao->getById($_POST['id']);
                if ($currentPart === null || $user->getId() !== $currentPart->getSubmittedById()) {
                    return new JSONRenderer(['status' => 'error', 'message' => 'Invalid Data Permissions!']);
                }
            }

            $validatedData['submitted_by_id'] = $user->getId();

            $part = new ComputerPart(...$validatedData);

            error_log(json_encode($part->toArray(), JSON_PRETTY_PRINT));

            // 新しい部品情報でデータベースの更新を試みます。
            // 別の方法として、createOrUpdateを実行することもできます。
            if (isset($validatedData['id'])) $success = $partDao->update($part);
            else $success = $partDao->create($part);

            if (!$success) {
                throw new Exception('Database update failed!');
            }

            return new JSONRenderer(['status' => 'success', 'message' => 'Part updated successfully', 'id' => $part->getId()]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());
            return new JSONRenderer(['status' => 'error', 'message' => 'Invalid data.']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return new JSONRenderer(['status' => 'error', 'message' => 'An error occurred.']);
        }
    })->setMiddleware(['auth', 'verify']),
    'test/share/files/jpg' => Route::create('test/share/files/jpg', function (): HTTPRenderer {
        // このURLは署名を必要とするため、URLが正しい署名を持つ場合にのみ、この最終ルートコードに到達します。
        $required_fields = [
            'user' => ValueType::STRING,
            'filename' => ValueType::STRING, // 本番環境では、有効なファイルパスに対してバリデーションを行いますが、ファイルパスの単純な文字列チェックを行います。
        ];

        $validatedData = ValidationHelper::validateFields($required_fields, $_GET);

        return new MediaRenderer(sprintf("private/shared/%s/%s", $validatedData['user'], $validatedData['filename']), 'jpg');
    })->setMiddleware(['signature']),
    'test/share/files/jpg/generate-url' => Route::create('test/share/files/jpg/generate-url', function (): HTTPRenderer {
        $required_fields = [
            'user' => ValueType::STRING,
            'filename' => ValueType::STRING,
        ];

        $validatedData = ValidationHelper::validateFields($required_fields, $_GET);

        if (isset($_GET['lasts'])) {
            $validatedData['expiration'] = time() + ValidationHelper::integer($_GET['lasts']);
        }

        return new JSONRenderer(['url' => Route::create('test/share/files/jpg', function () {
        })->getSignedURL($validatedData)]);
    }),
    'verify/email' => Route::create('verify/email', function () : HTTPRenderer {
        $required_fields = [
            'id' => ValueType::INT,
            'user' => ValueType::STRING,
        ];

        $validatedData = ValidationHelper::validateFields($required_fields, $_GET);

        $user = Authenticate::getAuthenticatedUser();

        // ユーザーの詳細とパラメーターが一致しているか確認
        if ($user === null || $user->getId() !== $validatedData['id'] || hash('sha256', $user->getEmail()) !== $validatedData['user']) {
            FlashData::setFlashData('error', 'Invalid URL.');
            return new RedirectRenderer('random/part');
        }
        
        // email_verifiedを更新
        $user->setEmailVerified(true);
        $userDao = DAOFactory::getUserDAO();
        $userDao->update($user);

        FlashData::setFlashData('success', 'E-mail verification successful.');
        return new RedirectRenderer('random/part');
    })->setMiddleware(['signature']),
    'verify/resend' => Route::create('verify/resend', function() : HTTPRenderer {
        $user = Authenticate::getAuthenticatedUser();

        if($user->getEmailVerified()) return new RedirectRenderer('mypage');
        
        return new HTMLRenderer('page/verificationEmail', ['user'=> $user]);
    })->setMiddleware(['auth']),
    'form/verify/resend' => Route::create('form/verify/resend', function() : HTTPRenderer {
        try{
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $required_fields = [
                'email' => ValueType::EMAIL,
            ];

            $validatedData = ValidationHelper::validateFields($required_fields, $_POST);

            $user = Authenticate::getAuthenticatedUser();

            if ($user->getEmail() !== $validatedData['email']) {
                $user->setEmail($validatedData['email']);
                $userDao = DAOFactory::getUserDAO();
                $userDao->update($user);
            }

            // 期限を30分に設定
            $lasts = 1 * 60 * 30;
            $param = [
                'id' => $user->getId(),
                'user' => hash('sha256', $user->getEmail()),
                'expiration' => time() + $lasts
            ];

            $signedUrl = Route::create('verify/email', function () {
            })->getSignedURL($param);
            Authenticate::sendVerificationEmail($user, $signedUrl);

            FlashData::setFlashData('success', 'We sent a verification email. Please check your email!');
            return new RedirectRenderer('random/part');
        }catch(\InvalidArgumentException $e) {
            error_log($e->getMessage());
            FlashData::setFlashData('error', 'Invalid Data.');
            return new RedirectRenderer('verify/resend');
        } catch (Exception $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'An error occurred.');
            return new RedirectRenderer('verify/resend');
        }
        
    })->setMiddleware(['auth']),
    'mypage' => Route::create('mypage',function () : HTTPRenderer {
        $user = Authenticate::getAuthenticatedUser();
        return new HTMLRenderer('page/mypage', ['user'=> $user]);
    })->setMiddleware(['auth', 'verify']),
    'form/mypage' => Route::create('form/mypage', function(): HTTPRenderer{
        try{
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $required_fields = [
                'email' => ValueType::EMAIL,
            ];

            $validatedData = ValidationHelper::validateFields($required_fields, $_POST);

            $userDao = DAOFactory::getUserDAO();

            if ($userDao->getByEmail($validatedData['email'])) {
                FlashData::setFlashData('error', 'Email is already in use!');
                return new RedirectRenderer('mypage');
            }

            // ユーザーのメールアドレスとemail_verifyを更新
            $user = Authenticate::getAuthenticatedUser();
            $user->setEmail($validatedData['email']);
            $user->setEmailVerified(false);
            $userDao = DAOFactory::getUserDAO();
            $userDao->update($user);

            // 期限を30分に設定
            $lasts = 1 * 60 * 30;
            $param = [
                'id' => $user->getId(),
                'user' => hash('sha256', $user->getEmail()),
                'expiration' => time() + $lasts
            ];

            $signedUrl = Route::create('verify/email', function () {
            })->getSignedURL($param);
            Authenticate::sendVerificationEmail($user, $signedUrl);

            FlashData::setFlashData('success', 'We sent a verification email. Please check your email!');
            return new RedirectRenderer('random/part');
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());
            FlashData::setFlashData('error', 'Invalid Data.');
            return new RedirectRenderer('mypage');
        } catch (Exception $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'An error occurred.');
            return new RedirectRenderer('mypage');
        }
        
    })->setMiddleware(['auth', 'verify']),

];
