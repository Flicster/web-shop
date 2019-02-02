<?php
namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
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
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();
        $latestProducts = $this->productRepository->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('base.html.twig', [
            'categories' => $categories,
            'products' => $latestProducts,
        ]);
    }
}
