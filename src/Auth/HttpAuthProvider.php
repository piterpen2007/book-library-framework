<?php

namespace EfTech\BookLibrary\Infrastructure\Auth;

use EfTech\BookLibrary\Infrastructure\http\httpResponse;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use EfTech\BookLibrary\Infrastructure\Session\SessionInterface;
use EfTech\BookLibrary\Infrastructure\Uri\Uri;

/**
 * Поставщик услуги аутификации
 */
class HttpAuthProvider
{
    /**
     * Ключ по которому в сессии храниться id пользователя
     */
    private const USER_ID = 'user_id';
    private UserDataStorageInterface $userDataStorage;
    private SessionInterface $session;
    private Uri $loginUri;

    /**
     * @param UserDataStorageInterface $userDataStorage
     * @param SessionInterface $session
     * @param Uri $loginUri
     */
    public function __construct(
        UserDataStorageInterface $userDataStorage,
        \EfTech\BookLibrary\Infrastructure\Session\SessionInterface $session,
        \EfTech\BookLibrary\Infrastructure\Uri\Uri $loginUri
    ) {
        $this->userDataStorage = $userDataStorage;
        $this->session = $session;
        $this->loginUri = $loginUri;
    }

    public function isAuth(): bool
    {
        return $this->session->has(self::USER_ID);
    }

    public function auth(string $login, string $password): bool
    {
        $isAuth = false;
        $user = $this->userDataStorage->findUserByLogin($login);
        if (null !== $user && password_verify($password, $user->getPassword())) {
            $this->session->set(self::USER_ID, $login);
            $isAuth = true;
        }
        return $isAuth;
    }
    private function getLoginUri(): Uri
    {
        return $this->loginUri;
    }

    /** Запускает процесс аутентификации
     * @param Uri $successUri
     * @return httpResponse
     */
    public function doAuth(Uri $successUri): httpResponse
    {
        $loginUri = $this->getLoginUri();
        $loginQueryStr = $loginUri->getQuery();

        $loginQuery = [];
        parse_str($loginQueryStr, $loginQuery);
        $loginQuery['redirect'] = (string)$successUri;
        $uri = new Uri(
            $loginUri->getSchema(),
            $loginUri->getHost(),
            $loginUri->getPort(),
            $loginUri->getPath(),
            http_build_query($loginQuery),
            $loginUri->getUserInfo(),
            $loginUri->getFragment()
        );

        return ServerResponseFactory::redirect($uri);
    }
}
