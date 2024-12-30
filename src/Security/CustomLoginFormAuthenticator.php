<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class CustomLoginFormAuthenticator extends AbstractLoginFormAuthenticator
{

    private $urlGenerator;
    private $entityManager;

    public function __construct(UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('auth_login');
    }

    public function authenticate(Request $request): Passport
    {
        $otc = $request->request->get('otc');
        $password = $request->request->get('password');
//        dd($request->getPayload()->get('_csrf_token'));
        $csrfToken = $request->getPayload()->get('_csrf_token');

        // If there's a magic code, treat it as a valid credential
        if (!empty($otc)) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['otc' => $otc]);

            if($user && $user->getOtcCreated() !== null) {

                //Check if the OTC is still valid
                $now = new \DateTimeImmutable();
                $otcCreated = $user->getOtcCreated();

                //Max time of 10 minutes
                if($now->getTimestamp() - $otcCreated->getTimestamp() < 600) {
                    return new SelfValidatingPassport((new UserBadge($user->getUserIdentifier())), [new CsrfTokenBadge('authenticate', $csrfToken)]);
                }
            }

            throw new CustomUserMessageAuthenticationException("Invalid code. Please try again.");
        }



        // Otherwise, proceed with regular email and password authentication
        $email = $request->request->get('email');
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if(!$user) {
            throw new AuthenticationException();
        }

        return new Passport(
            (new UserBadge($user->getUserIdentifier())),
            new PasswordCredentials($password),
            [new CsrfTokenBadge('authenticate', $csrfToken)]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('auth_loggedin'));
    }
}
