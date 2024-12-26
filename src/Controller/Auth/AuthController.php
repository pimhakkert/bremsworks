<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\BremsMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\ValidatorBuilder;

class AuthController extends AbstractController
{
    #[Route(path: '/login', name: 'auth_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $title = 'Login';

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'title' => $title,
        ]);
    }

    #[Route(path: '/logout', name: 'auth_logout')]
    public function logout(): void
    {
        //This exception shouldn't be reached. Symfony intercepts this route and logs the user out.
        throw new \LogicException('');
    }

    /**
     * Sends the user to their correct home page depending on the role
     * @param Request $request
     * @return Response
     */
    #[Route(path: '/loggedin', name: 'auth_loggedin')]
    public function loggedIn(Request $request, Security $security): Response
    {
        if($security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_home');
        } else {
            return new Response("Customer flow not yet finished");
        }
    }

    #[Route('/register', name: 'auth_register')]
    public function register(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager, BremsMailer $bremsMailer): Response
    {
        $title = 'Register';
        $genericError = false;

        if($request->getMethod() == 'POST') {

            $email = $request->request->get('email');
            $firstName = $request->request->get('first_name');
            $lastName = $request->request->get('last_name');

            $password = $request->request->get('password');
            $wantsMarketingEmails = $request->request->has('email_for_news');

            if(empty($email) || empty($firstName) || empty($lastName) || empty($password)) {
                $genericError = true;
                goto renderView;
            }

            $user = new User();
            $user->setEmail($email);
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setWantsMarketingEmails($wantsMarketingEmails);

            $user->setPassword($hasher->hashPassword($user, $password));
            $user->setVerifyEmailToken(uniqid());
            $user->setOtc(null);

            $entityManager->persist($user);
            $entityManager->flush();
            //TODO what about verify email??? I think they first need to verify, and on that result page get forwarded.
            // Auto log in. having to log in after registering is stupid

            $verificationUrl = $this->generateUrl('auth_verify_email', ['token' => $user->getVerifyEmailToken()], UrlGeneratorInterface::ABSOLUTE_URL);

            //TODO what to do when error? Delete user?
            $result = $bremsMailer->sendVerificationEmail($email, $firstName, $verificationUrl);

            //Forward
            $title = 'Verify your email';
            return $this->render('auth/verify_email.html.twig', get_defined_vars());
        }

        renderView:
        return $this->render('auth/register.html.twig', get_defined_vars());
    }

    #[Route('/verify-email/{token}', name: 'auth_verify_email')]
    public function verifyEmail(Request $request, EntityManagerInterface $entityManager, Security $security, string $token): Response
    {
        $title = 'Email verification';
        if(empty($token)) {
            return new Response(null, 404);
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['verifyEmailToken' => $token]);
        if(!$user) {
            return new Response(null, 404);
        }

        $user->setVerifyEmailToken(null);
        $entityManager->flush();

        $security->login($user);
        return $this->render('auth/email_verified.html.twig', get_defined_vars());
    }

    #[Route('/login/otc-request', name: 'auth_otc_request', methods: ['POST'])]
    public function otcRequest(Request $request, BremsMailer $bremsMailer, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $isEmail = Validation::createIsValidCallable(null, (new Email()))($data['email']);

        if(!$isEmail) {
            return new Response(null, 400);
        }

        //Get user from email
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        //Do not tell end user email does not exist
        if(!$user) {
            return new Response("", 200);
        }

        $user->generateOtc();
        $entityManager->flush();

        $otc = $user->getOtc();

        //SEND EMAIL
        $result = $bremsMailer->sendOtcMail($data['email'], $otc);
        if(!$result) {
            return new Response(null, 500);
        }

        return new Response(null, 200);
    }
}
