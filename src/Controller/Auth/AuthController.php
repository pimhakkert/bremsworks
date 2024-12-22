<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\BremsMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\ValidatorBuilder;

class AuthController extends AbstractController
{
    #[Route('/login', name: 'auth_login')]
    public function login(): Response
    {
        $title = 'Login';
        return $this->render('auth/login.html.twig', get_defined_vars());
    }

    #[Route('/register', name: 'auth_register')]
    public function register(Request $request): Response
    {

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
//        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
//
//        //Do not tell end user email does not exist
//        if(!$user) {
//            return new Response("", 200);
//        }
//
//        $otc = $user->generateOtc();
//        $entityManager->flush();
        $otc = "123ABC";

        //SEND EMAIL
        $result = $bremsMailer->sendOtcMail($data['email'], $otc);
        if(!$result) {
            return new Response(null, 500);
        }

        return new Response(null, 200);
    }
}
