<?php
namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /** @var ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/product/{id}", name="product.view")
     */
    public function view(int $id): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new \HttpException("Product not found", 404);
        }

        return $this->render('product/view.html.twig', [
            'product' => $product,
        ]);
    }
}
