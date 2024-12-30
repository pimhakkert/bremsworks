<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/products', name: 'admin_products_')]
class ProductController extends AbstractController
{
    #[Route('', name: 'list')]
    public function adminProductList(EntityManagerInterface $entityManager): Response
    {
        $title = 'Products';

        $products = $entityManager->getRepository(Product::class)->findAll();
        $count = $entityManager->getRepository(Product::class)->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('admin/products/list.html.twig', get_defined_vars());
    }

    #[Route('/new', name: 'create')]
    public function adminProductCreate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $title = 'Create Product';

        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        $formView = $form->createView();

        if($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $entityManager->persist($product);

            $entityManager->flush();

            return $this->redirectToRoute('admin_products_list');
        }

        return $this->render('admin/products/create.html.twig', get_defined_vars());
    }
}
