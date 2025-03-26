<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function(ContainerConfigurator $configurator) {

$services = $configurator->services()
    ->defaults()
        ->autowire()
        ->public();


// Main database connection.
$services->set(PDO::class)
    ->class(PDO::class)
    ->args([
        "mysql:host=%env(DB_HOST)%;port=%env(DB_PORT)%;dbname=%env(DB_NAME)%;charset=utf8mb4",
        '%env(DB_USER)%',
        '%env(DB_PASS)%',
        [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ],
    ])
;


// Import almost everything.
$services->load('Jp\\ArcsDesigner\\Application\\', '../src/Application')
    ->exclude([
    ])
;

$services->load('Jp\\ArcsDesigner\\Infrastructure\\', '../src/Infrastructure');
$services->load('Jp\\ArcsDesigner\\Presentation\\', '../src/Presentation');


// Domain services.


// Templating
$services->set(\Twig\Loader\FilesystemLoader::class)
    ->arg('$paths', [__DIR__ . '/../templates'])
;
$services->alias(
    \Twig\Loader\LoaderInterface::class,
    \Twig\Loader\FilesystemLoader::class,
);
$services->set(\Twig\Environment::class)
    ->arg('$options', [
        'cache' => __DIR__ . '/../templates/cache',
        'auto_reload' => true,
    ])
;
$services->alias(
    \Jp\ArcsDesigner\Presentation\RendererInterface::class,
    \Jp\ArcsDesigner\Infrastructure\TwigRenderer::class,
);


// Middleware
$services->set(\Jp\ArcsDesigner\Application\Middleware\HtmlErrorMiddleware::class)
    ->arg('$environment', '%env(ENVIRONMENT)%')
;
$services->set(\Jp\ArcsDesigner\Application\Middleware\JsonErrorMiddleware::class)
    ->arg('$environment', '%env(ENVIRONMENT)%')
;


// Interfaces
$services->alias(
    \Jp\ArcsDesigner\Domain\Affinities\AffinityRepositoryInterface::class,
    \Jp\ArcsDesigner\Infrastructure\DatabaseAffinityRepository::class
);
$services->alias(
    \Jp\ArcsDesigner\Domain\CardComments\CardCommentRepositoryInterface::class,
    \Jp\ArcsDesigner\Infrastructure\DatabaseCardCommentRepository::class
);
$services->alias(
    \Jp\ArcsDesigner\Domain\CardIterations\CardIterationRepositoryInterface::class,
    \Jp\ArcsDesigner\Infrastructure\DatabaseCardIterationRepository::class
);
$services->alias(
    \Jp\ArcsDesigner\Domain\Cards\CardRepositoryInterface::class,
    \Jp\ArcsDesigner\Infrastructure\DatabaseCardRepository::class
);


};
