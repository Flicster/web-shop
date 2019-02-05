<?php
namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogueController extends AbstractController
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var CategoryRepository */
    private $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/catalogue", name="catalogue.index")
     */
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();
        $products = $this->productRepository->findAll();

        return $this->render('catalogue/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/catalogue/{category_id}", name="catalogue")
     */
    public function category(int $categoryId): Response
    {
        $categories = $this->categoryRepository->findAll();
        $products = $this->productRepository->findBy(['categoryId' => $categoryId], ['createdAt' => 'DESC']);

        return $this->render('catalogue/category.html.twig', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
