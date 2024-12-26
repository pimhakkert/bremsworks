<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin', name: 'admin_')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function number(): Response
    {
        $title = 'Admin Home';
        return $this->render('admin/index.html.twig', get_defined_vars());
    }
}
