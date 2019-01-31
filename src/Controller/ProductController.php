<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public static function view ($productId) {

        //Список категорий
        $categories = Category::getCategory();

        //Товар
        $product = Product::getProductById($productId);

        require_once(ROOT . '/views/single_item/single_item.php');
        return true;
    }
}