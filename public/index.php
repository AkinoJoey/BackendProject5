<?php
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(__DIR__ . '/..'));
spl_autoload_extensions(".php");
spl_autoload_register();

$DEBUG = true;

if (preg_match('/\.(?:png|jpg|jpeg|gif|js|css|html)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}

// ルートをロードします
$routes = include('Routing/routes.php');

// リクエストURIを解析してパスだけを取得します
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($path, '/');

// ルートにパスが存在するかチェックします
if (isset($routes[$path])) {
    // 現在のルートを取得します
    $middlewareRegister = include('Middleware/middleware-register.php');

    // ロギングミドルウェア
    $logMiddleware = new $middlewareRegister['log'][0]();

    // テスト用ミドルウェア
    $middlewares = array_map(fn($middleware)=> new $middleware(), $middlewareRegister['global']);
    array_unshift($middlewares,$logMiddleware);

    $middlewareHandler = new \Middleware\MiddlewareHandler($middlewares);

    // チェーンの最後のcallableは、HTTPRendererを返す現在の$route callableとなります
    $renderer = $middlewareHandler->run($routes[$path]);

    try {
        // ヘッダーの設定
        foreach ($renderer->getFields() as $name => $value) {
            // ヘッダーに対する単純な検証を実行します。
            $sanitized_value = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

            if ($sanitized_value && $sanitized_value === $value) {
                header("{$name}: {$sanitized_value}");
            } else {
                // ヘッダー設定に失敗した場合のログまたは処理
                // エラー処理によっては、例外をスローするか、デフォルトのまま続行することもできます
                http_response_code(500);
                if ($DEBUG) print("Failed setting header - original: '$value', sanitized: '$sanitized_value'");
                exit;
            }

            print($renderer->getContent());
        }
    } catch (Exception $e) {
        http_response_code(500);
        print("Internal error, please contact the admin.<br>");
        if ($DEBUG) print($e->getMessage());
    }
} else {
    // 一致するルートがない場合、404エラーを表示します
    http_response_code(404);
    echo "404 Not Found: The requested route was not found on this server.";
}
