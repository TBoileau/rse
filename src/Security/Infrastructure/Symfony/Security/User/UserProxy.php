<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Symfony\Security\User;

use App\Security\Domain\Entity\User;
use App\Security\Domain\ValueObject\Password\HashedPassword;
use App\Shared\Domain\ValueObject\Email\EmailAddress;
use App\Shared\Domain\ValueObject\Identifier\UuidIdentifier;
use Serializable;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserProxy implements Serializable, UserInterface, PasswordAuthenticatedUserInterface, EquatableInterface
{
    public function __construct(public User $user)
    {
    }

    /**
     * @return array<array-key, string>
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): string
    {
        return (string) $this->user->hashedPassword;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        $this->user->plainPassword = null;
    }

    public function getUsername(): string
    {
        return (string) $this->user->email;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->user->email;
    }

    public function isEqualTo(UserInterface $user): bool
    {
        return $user->getUserIdentifier() === $this->getUserIdentifier();
    }

    public function serialize(): string
    {
        return serialize([
            (string) $this->user->identifier,
            (string) $this->user->email,
            (string) $this->user->hashedPassword,
        ]);
    }

    /**
     * @param string $data
     */
    public function unserialize(mixed $data): void
    {
        /** @var array<array-key, string> $deserializedData */
        $deserializedData = unserialize($data, ['allowed_classes' => false]);

        /**
         * @var string $identifier
         * @var string $email,
         * @var string $password
         */
        [
            $identifier,
            $email,
            $password,
        ] = $deserializedData;

        $this->user = new User(
            UuidIdentifier::createFromString($identifier),
            EmailAddress::createFromString($email),
            HashedPassword::createFromString($password)
        );
    }
}
