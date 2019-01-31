<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogueController extends AbstractController
{
    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function index($page = 1): Response
    {
        $categories = Category::getCategory();

        $latestProducts = Product::getLatestProducts($page);

        $total = Product::getTotalProducts();

        $pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');

        require_once(ROOT . '/views/catalog/index.php');

        return true;
    }

    /**
     * @Route("/category", name="catalogue")
     */
    public function category($catId, $page = 1): Response
    {
        $categories = Category::getCategory();

        //Товары из категории
        $categoryProduct = Product::getProductListByCatId($catId, $page);

        //Общее кол-во товаров в категории (для пагинации)
        $total = Product::getTotalProductsInCategory($catId);

        $pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');

        require_once(ROOT . '/views/catalog/category.php');

        return true;
    }
}
