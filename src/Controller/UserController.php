<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(): Response
    {
        $res = false;
        $name = '';
        $email = '';
        $password = '';

        if (isset($_POST) and (!empty($_POST))) {
            $name = trim(strip_tags($_POST['name']));
            $email = trim(strip_tags($_POST['email']));
            $password = trim(strip_tags($_POST['password']));

            //Флаг ошибок
            $errors = false;

            //Валидация полей
            if (!User::checkName($name)) {
                $errors[] = "Имя не может быть короче 2-х символов";
            }

            if (!User::checkEmail($email)) {
                $errors[] = "Некорректный Email";
            }

            if (!User::checkPassword($password)) {
                $errors[] = "Пароль не может быть короче 6-ти символов";
            }

            if (User::checkEmailExists($email)) {
                $errors[] = "Такой email уже используется";
            }

            if ($errors == false) {
                $res = User::register($name, $email, password_hash($password, PASSWORD_DEFAULT));
            }
        }

        require_once(ROOT . '/views/user/register.php');

        return true;
    }
}
