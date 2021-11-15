<?php

declare(strict_types=1);

namespace App\Security\Domain\Entity;

use App\Security\Domain\ValueObject\Password\HashedPassword;
use App\Security\Domain\ValueObject\Password\PlainPassword;
use App\Shared\Domain\ValueObject\Date\DateTime;
use App\Shared\Domain\ValueObject\Email\EmailAddress;
use App\Shared\Domain\ValueObject\Identifier\UuidIdentifier;

class User
{
    private function __construct(
        public UuidIdentifier $identifier,
        public EmailAddress $email,
        public ?HashedPassword $hashedPassword = null,
        public ?PlainPassword $plainPassword = null,
        public ?DateTime $expiredAt = null
    ) {
    }

    public static function create(
        UuidIdentifier $identifier,
        EmailAddress $email,
        ?HashedPassword $hashedPassword = null,
        ?PlainPassword $plainPassword = null,
        ?DateTime $expiredAt = null
    ): User {
        return new User($identifier, $email, $hashedPassword, $plainPassword, $expiredAt);
    }
}
