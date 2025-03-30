<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\LoginTokens;

interface LoginTokenRepositoryInterface
{
    /**
     * @throws LoginTokenNotFoundException if no login token exists with this
     *     selector.
     */
    public function getBySelector(string $selector): LoginToken;

    public function save(LoginToken $loginToken): void;
}
