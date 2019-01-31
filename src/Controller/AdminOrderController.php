<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrderController extends AbstractController
{
    /**
     * @Route("/admin/order", name="admin.order.index")
     */
    public function index(): Response
    {
        self::checkAdmin();

        $orders = Order::getOrdersList();

        require_once(ROOT . '/views/admin_order/index.php');

        return true;
    }

    /**
     * @Route("/admin/order/{id}", name="admin.order.view")
     */
    public function view($id): Response
    {
        self::checkAdmin();

        $orders = Order::getOrderById($id);

        $productQuantity = json_decode($orders['products'], true);

        $productIds = array_keys($productQuantity);

        $products = Product::getProductsByIds($productIds);

        require_once(ROOT . '/views/admin_order/view.php');

        return true;
    }

    /**
     * @Route("/admin/order/{id}", name="admin.order.view")
     */
    public function edit($id): Response
    {
        self::checkAdmin();

        $order = Order::getOrderById($id);

        if(isset($_POST) and !empty($_POST)){
            $userName = trim(strip_tags($_POST['name']));
            $userPhone = trim(strip_tags($_POST['phone']));
            $userComment = trim(strip_tags($_POST['comment']));
            $status = trim(strip_tags($_POST['status']));

            //Записываем изменения
            Order::updateOrder($id, $userName, $userPhone, $userComment, $status);

            //Перенаправляем на страницу просмотра данного заказа
            header("Location: /admin/orders/view/$id");
        }

        require_once(ROOT . '/views/admin_order/edit.php');

        return true;
    }

    /**
     * @Route("/admin/order/{id}/delete", name="admin.order.delete")
     */
    public function delete($id): Response
    {
        self::checkAdmin();

        if (isset($_POST['submit'])) {
            //Если отправлена, то удаляем нужный товар
            Order::deleteOrderById($id);
            //и перенаправляем на страницу заказов
            header('Location: /admin/orders');
        }

        require_once(ROOT . '/views/admin_order/delete.php');

        return true;
    }
}
