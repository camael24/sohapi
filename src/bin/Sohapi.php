<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 16/01/14
 * Time: 16:57
 */
namespace Sohapi\Bin;
use Hoa\Core\Core;

require __DIR__ . '/../../vendor/autoload.php';
/**
 * Here we go…
 */
try {
    unset($_SERVER['TERM']);

    ini_set('xdebug.max_nesting_level', '300');
    ini_set('xdebug.max_nesting_level', '300');


    $core       = Core::getInstance();
    $parameters = $core->getParameters();
    /**
     * @var \Hoa\Core\Parameter\Parameter $parameters
     */
    $cwd =  __DIR__ . '/../../../../../../';
    $parameters->setKeyword('cwd', realpath($cwd));
    $parameters->setParameter('protocol.Application', '(:cwd:)/Application/');
    $parameters->setParameter('protocol.Public', '(:%root.application:)/Public/');
    $parameters->setParameter('namespace.prefix.Application', '(:cwd:)/');

    $core->setProtocol();

    $router = new \Hoa\Router\Cli();
    $router->get(
        'g',
        '(?:(?<vendor>\w+)\s+)?(?<library>\w+)?(?::(?<command>\w+))?(?<_tail>.*?)',
        'main',
        'main',
        array(
            'vendor'  => 'sohapi',
            'library' => 'core',
            'command' => 'generate'
        )
    );

    $dispatcher = new \Hoa\Dispatcher\ClassMethod(array(
        'synchronous.call'
        => '(:%variables.vendor:lU:)\Bin\Command\(:%variables.library:lU:)\(:%variables.command:lU:)',
        'synchronous.able'
        => 'main'
    ));

    $dispatcher->setKitName('Hoa\Console\Dispatcher\Kit');
    exit($dispatcher->dispatch($router));
} catch (\Hoa\Core\Exception $e) {

    $message = $e->raise(true);
} catch (\Exception $e) {

    $message = $e->getMessage();
}

\Hoa\Console\Cursor::colorize('foreground(white) background(red)');
echo $message, "\n";
\Hoa\Console\Cursor::colorize('normal');
