<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Logins;

use Jp\ArcsDesigner\Domain\Users\PasswordHashRepositoryInterface;
use Jp\ArcsDesigner\Domain\Users\UserId;
use Jp\ArcsDesigner\Domain\Users\UserNotFoundException;
use Jp\ArcsDesigner\Domain\Users\UserRepositoryInterface;
use function bin2hex;
use function password_hash;
use function password_needs_rehash;
use function password_verify;
use function random_bytes;
use const PASSWORD_DEFAULT;

final readonly class LoginPasswordAuthenticator
{
    public function __construct(
        private PasswordHashRepositoryInterface $passwordHashRepository,
        private UserRepositoryInterface $userRepository,
    ) {}

    /**
     * Authenticate a user via email address and password.
     *
     * @throws InvalidEmailAddressException if $emailAddress is invalid.
     * @throws IncorrectPasswordException if $password is invalid.
     */
    public function authenticate(string $emailAddress, string $password): UserId
    {
        // Generate a random password hash to validate against when the user
        // gives an invalid email address, to protect against timing attacks.
        /** @noinspection PhpUnhandledExceptionInspection */
        $dummyPassword = bin2hex(random_bytes(8)); // length 16
        $dummyHash = password_hash($dummyPassword, PASSWORD_DEFAULT);

        // Get the password hash for the user with this email address.
        try {
            $hash = $this->passwordHashRepository->getByEmailAddress(
                $emailAddress,
            );
        } catch (UserNotFoundException) {
            password_verify($password, $dummyHash);

            throw new InvalidEmailAddressException(
                "Invalid email address: $emailAddress."
            );
        }

        if (!password_verify($password, $hash)) {
            throw new IncorrectPasswordException('Password does not match.');
        }

        // Get the user's data.
        /** @noinspection PhpUnhandledExceptionInspection */
        $user = $this->userRepository->getByEmailAddress($emailAddress);

        // If the user's password hash needs to be updated, do so now.
        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            $new = password_hash($password, PASSWORD_DEFAULT);
            $this->passwordHashRepository->save($user->id, $new);
        }

        return $user->id;
    }
}
