<?php
/**
 * User: panda_ubuntu
 * Project: integrator
 * Version: v1.0.0
 */

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    /** @var EntityManagerInterface $em */
    private $em;
    /**
     * @var User
     */
    private $user;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function supports(Request $request)
    {
        $userToken = $request->headers->get('token');

        if ($userToken) {
            $credentials = ['apiToken' => $userToken];

            $user = $this->em->getRepository(User::class)->findOneBy($credentials);

            if ($user) {
                return true;
            }

            return false;
        }

        return $userToken ? true : false;
    }

    public function getCredentials(Request $request)
    {
        if ($request->headers->get('token')) {
            return [
                'apiToken' => $request->headers->get('token'),
            ];
        }

        return false;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if ($credentials) {
            if (array_key_exists('apiToken', $credentials)) {
                $apiToken = $credentials['apiToken'];
                $this->user = $this->em->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);

                return $this->user;
            }

            return false;
        }

        return false;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
