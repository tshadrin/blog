<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Symfony вызывает этот метод если вы используете
     * такие возможности как switch_user или remember_me
     */
    public function loadUserByUsername($username): UserInterface
    {
        $user = $this->userRepository->findOneBy(['email' => $username]);
        if (!$user instanceof User) {
            throw new UsernameNotFoundException('');
        }

        return $user;
    }

    /**
     * Проверяет, что данные пользователя не изменились
     * Если в firewall  "stateless: true" метод не выполняется
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $user;
    }

    /**
     * Сказать Symfony использовать этот UserProvider для этой сущности User
     */
    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}
