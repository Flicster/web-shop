<?php
namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="product.view")
     */
    public function view(Product $product): Response
    {
        return $this->render('products/view.html.twig', [
            'product' => $product,
        ]);
    }
}
