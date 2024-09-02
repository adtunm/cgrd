<?php
global $exception;
$code = empty($exception->getCode()) ? 500 : $exception->getCode();
http_response_code($code);
if (array_key_exists('DEBUG', $_ENV) && $_ENV['DEBUG'] == true) {
    print '<pre>';
    print 'ERROR CODE: ' . $code;
    print '<br>';
    print 'MESSAGE: ' . $exception->getMessage();
    print '<br>';
    print 'FILE: ' . $exception->getFile();
    print '<br>';
    print 'LINE: ' . $exception->getLine();
    print '<br>';
    print 'BACKTRACE:';
    print '<br>';
    print $exception->getTraceAsString();
    print '</pre>';
} else {
    print 'ERROR CODE: ' . $code;
    print '<br>';
    switch ($code) {
        case 404:
            $message = 'Page not found';
            break;
        case 403:
            $message = 'Not authorized';
            break;
        default:
            $message = 'Something went wrong';
            break;
    }
    print 'MESSAGE: ' . $message;
    print '<br>';
    print '<a href="/">Return to homepage</a>' ;
}
