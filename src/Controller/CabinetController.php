<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CabinetController extends AbstractController
{
    /**
     * @Route("/profile", name="profile.index")
     */
    public function index(): Response
    {
        $userId = User::checkLog();

        $user = User::getUserById($userId);

        require_once(ROOT . '/views/cabinet/index.php');

        return true;
    }

    /**
     * @Route("/profile/edit", name="profile.edit")
     */
    public function edit(): Response
    {
        $userId = User::checkLog();

        $res = false;

        if (isset($_POST) and (!empty($_POST))) {
            $name = trim(strip_tags($_POST['name']));
            $password = trim(strip_tags($_POST['password']));

            //Флаг ошибок
            $errors = false;

            //Валидация полей
            if (!User::checkName($name)) {
                $errors[] = "Имя не может быть короче 2-х символов";
            }

            if (!User::checkPassword($password)) {
                $errors[] = "Пароль не может быть короче 6-ти символов";
            }

            if ($errors == false) {
                $res = User::edit($userId, $name, $password);
            }
        }

        require_once(ROOT . '/views/cabinet/edit.php');

        return true;
    }

    /**
     * @Route("/profile/order", name="profile.order.index")
     */
    public function order(): Response
    {
        $userId = User::checkLog();

        $orders = Order::getOrdersListByUserId($userId);

        require_once(ROOT . '/views/cabinet/orders.php');

        return true;
    }
}
