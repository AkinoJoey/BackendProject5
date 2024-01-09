<?php

namespace Middleware;

use Response\HTTPRenderer;
use DateTime;

class FileLoggingMiddleware implements Middleware{

    public function handle(callable $next): HTTPRenderer
    {

        // 前処理　リクエストログ
        $start = microtime(true);
        $this->logRequest();

        $response =  $next();

        // 後処理　レスポンスログ
        $this->logResponse($start);

        return $response;
    }

    private function logRequest(): void
    {
        error_log("Logging the request");
        ob_start();
        $dateTime = (new DateTime())->format('Y-m-d H:i:s') . " ";
        echo $dateTime;

        $url = "http" . (isset($_SERVER['HTTPS']) ? "s" : "") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI] ";
        echo $url;

        $requestMethod = "Method:" . $_SERVER['REQUEST_METHOD'] . "\n";
        echo $requestMethod;

        $queryParameters = $_GET;
        echo "Param:";
        print_r($queryParameters);

        $headers = getallheaders();
        echo "Header:";
        print_r($headers);

        file_put_contents("../Storage/Logs/request_log.txt", ob_get_contents(), FILE_APPEND);
        ob_end_clean();
    }

    private function logResponse(float $start): void
    {
        error_log("Logging the response");

        ob_start();
        $statusCode = http_response_code();
        echo "Status:" . $statusCode . " ";

        $end = microtime(true);
        $responseTime = $end - $start;
        echo "Response Time:" . $responseTime . "\n";

        $headers = headers_list();
        echo "Header:";
        print_r($headers);

        file_put_contents("../Storage/Logs/request_log.txt", ob_get_contents(), FILE_APPEND);
        ob_end_clean();
    }
}