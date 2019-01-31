<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/admin/category", name="admin.category.index")
     */
    public function index(): Response
    {
        self::checkAdmin();

        $categories = Category::getCategoryListAdmin();

        require_once(ROOT . '/views/admin_category/index.php');

        return true;
    }

    /**
     * @Route("/admin/category/{id}", name="admin.category.delete")
     */
    public function delete($id): Response
    {
        self::checkAdmin();

        if (isset($_POST['submit'])) {
            //Если отправлена, то удаляем нужный товар
            Category::deleteCategoryById($id);
            //и перенаправляем на страницу товаров
            header('Location: /admin/category');
        }

        require_once(ROOT . '/views/admin_category/delete.php');

        return true;
    }

    /**
     * @Route("/admin/category/add", name="admin.category.add")
     */
    public function add(): Response
    {
        self::checkAdmin();

        if (isset($_POST) and !empty($_POST)) {
            $options['name'] = trim(strip_tags($_POST['name']));
            $options['sort_order'] = trim(strip_tags($_POST['sort_order']));
            $options['status'] = trim(strip_tags($_POST['status']));

            Category::addCategory($options);

            header('Location: /admin/category');
        }


        require_once(ROOT . '/views/admin_category/add.php');

        return true;
    }

    /**
     * @Route("/admin/category/{id}/edit", name="admin.category.edit")
     */
    public function edit($id): Response
    {
        self::checkAdmin();

        $category = Category::getCategoryById($id);

        if (isset($_POST) and !empty($_POST)) {
            $options['name'] = trim(strip_tags($_POST['name']));
            $options['sort_order'] = trim(strip_tags($_POST['sort_order']));
            $options['status'] = trim(strip_tags($_POST['status']));

            Category::editCategory($id, $options);

            header('Location: /admin/category');
        }

        require_once(ROOT . '/views/admin_category/edit.php');

        return true;
    }
}
