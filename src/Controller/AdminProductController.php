<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController
{
    /**
     * @Route("/admin/product", name="admin.product.index")
     */
    public function index(): Response
    {
        self::checkAdmin();

        $products = Product::getProductsList();

        require_once(ROOT . '/views/admin_product/index.php');

        return true;
    }

    /**
     * @Route("/admin/product/{id}/delete", name="admin.product.delete")
     */
    public function delete($id): Response
    {
        self::checkAdmin();

        if (isset($_POST['submit'])) {
            //Если отправлена, то удаляем нужный товар
            Product::deleteProductById($id);
            //и перенаправляем на страницу товаров
            header('Location: /admin/product');
        }

        require_once(ROOT . '/views/admin_product/delete.php');

        return true;
    }

    /**
     * @Route("/admin/product/add", name="admin.product.add")
     */
    public function add(): Response
    {
        self::checkAdmin();

        $categories = Category::getCategoryListAdmin();

        if (isset($_POST) and !empty($_POST)) {
            $options['name'] = trim(strip_tags($_POST['name']));
            $options['code'] = trim(strip_tags($_POST['code']));
            $options['price'] = trim(strip_tags($_POST['price']));
            $options['category'] = trim(strip_tags($_POST['category']));
            $options['brand'] = trim(strip_tags($_POST['brand']));
            $options['description'] = trim(strip_tags($_POST['description']));
            $options['availability'] = trim(strip_tags($_POST['availability']));
            $options['is_new'] = trim(strip_tags($_POST['is_new']));
            $options['status'] = trim(strip_tags($_POST['status']));

            //Если все ок, записываем новый товар
            $id = Product::addProduct($options);

            // Если запись добавлена
            if ($id) {
                // Проверим, загружалось ли через форму изображение
                if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                    // Если загружалось, переместим его в нужную папку, дадим новое имя
                    move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                }
            };

            header('Location: /admin/product');
        }

        require_once(ROOT . '/views/admin_product/add.php');

        return true;
    }

    /**
     * @Route("/admin/product/{id}/edit", name="admin.product.edit")
     */
    public function edit($id): Response
    {
        self::checkAdmin();

        $categories = Category::getCategoryListAdmin();

        $product = Product::getProductById($id);

        if (isset($_POST) and !empty($_POST)) {
            $options['name'] = trim(strip_tags($_POST['name']));
            $options['code'] = trim(strip_tags($_POST['code']));
            $options['price'] = trim(strip_tags($_POST['price']));
            $options['category'] = trim(strip_tags($_POST['category']));
            $options['brand'] = trim(strip_tags($_POST['brand']));
            $options['description'] = trim(strip_tags($_POST['description']));
            $options['availability'] = trim(strip_tags($_POST['availability']));
            $options['is_new'] = trim(strip_tags($_POST['is_new']));
            $options['status'] = trim(strip_tags($_POST['status']));

            Product::editProduct($id, $options);

            // Если запись добавлена
            if ($id) {
                // Проверим, загружалось ли через форму изображение
                if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                    // Если загружалось, переместим его в нужную папке, дадим новое имя
                    move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                }
            };

            header('Location: /admin/product');
        }

        require_once(ROOT . '/views/admin_product/edit.php');

        return true;
    }
}
