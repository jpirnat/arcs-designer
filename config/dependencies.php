<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function(ContainerConfigurator $configurator) {

$services = $configurator->services()
    ->defaults()
        ->autowire()
        ->public()
        ->bind('string $environment', '%env(ENVIRONMENT)%')
;


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
$services->set(\Jp\ArcsDesigner\Domain\Logins\LoginPasswordAuthenticator::class);
$services->set(\Jp\ArcsDesigner\Domain\Logins\LoginTokenAuthenticator::class);
$services->set(\Jp\ArcsDesigner\Domain\LoginTokens\LoginTokenGenerator::class);


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

// Session
$services->alias(
    \Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface::class,
    \Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage::class,
);
$services->set(\Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage::class)
    ->arg('$options', [
        'cookie_lifetime' => 60 * 20, // 20 minutes
    ])
;
$services->set(\Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag::class);
$services->alias(
    \Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface::class,
    \Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag::class,
);

$services->set(\Symfony\Component\HttpFoundation\Session\Flash\FlashBag::class);
$services->alias(
    \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface::class,
    \Symfony\Component\HttpFoundation\Session\Flash\FlashBag::class,
);

$services->set(\Symfony\Component\HttpFoundation\Session\Session::class);
$services->alias(
    \Symfony\Component\HttpFoundation\Session\SessionInterface::class,
    \Symfony\Component\HttpFoundation\Session\Session::class,
);


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
$services->alias(
    \Jp\ArcsDesigner\Domain\LoginTokens\LoginTokenRepositoryInterface::class,
    \Jp\ArcsDesigner\Infrastructure\DatabaseLoginTokenRepository::class
);
$services->alias(
    \Jp\ArcsDesigner\Domain\Users\PasswordHashRepositoryInterface::class,
    \Jp\ArcsDesigner\Infrastructure\DatabasePasswordHashRepository::class
);
$services->alias(
    \Jp\ArcsDesigner\Domain\SetCards\SetCardRepositoryInterface::class,
    \Jp\ArcsDesigner\Infrastructure\DatabaseSetCardRepository::class
);
$services->alias(
    \Jp\ArcsDesigner\Domain\Sets\SetRepositoryInterface::class,
    \Jp\ArcsDesigner\Infrastructure\DatabaseSetRepository::class
);
$services->alias(
    \Jp\ArcsDesigner\Domain\Users\UserRepositoryInterface::class,
    \Jp\ArcsDesigner\Infrastructure\DatabaseUserRepository::class
);


};
