<?php
declare(ticks=1);

use App\Kernel;
use Nyholm\Psr7\Factory\Psr17Factory;
use Spiral\Goridge\SocketRelay;
use Spiral\RoadRunner\PSR7Client;
use Spiral\RoadRunner\Worker;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

require '../vendor/autoload.php';

$env = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'dev';
$debug = (bool) ($_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? ('prod' !== $env));

if ($debug) {
    umask(0000);
    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new Kernel($env, $debug);
$relay = new SocketRelay('127.0.0.1', 10000);
$psr7 = new PSR7Client(new Worker($relay));
$httpFoundationFactory = new HttpFoundationFactory();

$psrFactory = new Psr17Factory();
$psrHttpFactory = new PsrHttpFactory($psrFactory, $psrFactory, $psrFactory, $psrFactory);

while ($req = $psr7->acceptRequest()) {
    try {

        $request = $httpFoundationFactory->createRequest($req);
        $response = $kernel->handle($request);
        $psr7->respond($psrHttpFactory->createResponse($response));
        $kernel->terminate($request, $response);
        $kernel->reboot(null);

    } catch (\Throwable $e) {
        $psr7->getWorker()->error((string)$e);
    }
}