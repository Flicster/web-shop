<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart.index")
     */
    public function index(): Response
    {
//        $productsInCart = Cart::getProducts();

        return $this->render('cart/index.html.twig', [
            'productsInCart' => [
                [
                    'id' => 1,
                    'code' => 22,
                    'name' => 'test',
                    'price' => 123,
                ],
                [
                    'id' => 2,
                    'code' => 33,
                    'name' => 'test2',
                    'price' => 321,
                ]
            ],
        ]);
    }

//    /**
//     * @Route("/cart/{id}/add", name="cart.add")
//     */
//    public function add($id): Response
//    {
//        Cart::addProduct($id);
//
//        $referrer = $_SERVER['HTTP_REFERER'];
//
//        header("Location: $referrer");
//    }
//
//    /**
//     * @Route("/cart/{id}/delete", name="cart.delete")
//     */
//    public function delete($id): Response
//    {
//        Cart::deleteProduct($id);
//
//        header('Location: /cart');
//    }
//
//    /**
//     * @Route("/order", name="order")
//     */
//    public function order(): Response
//    {
//        $productsInCart = Cart::getProducts();
//        if($productsInCart == false){
//            header('Location: /');
//        }
//
//        //Список категорий для сайдбара
//        $categories = Category::getCategory();
//
//        //Находим общую стоимость
//        $productsIds = array_keys($productsInCart);
//        $products = Product::getProductsByIds($productsIds);
//        $totalPrice = Cart::getTotalPrice($products);
//
//        //Кол-во товаров
//        $totalQuantity = Cart::itemsCount();
//
//        //Поля для формы
//        $userName ='';
//        $userPhone = '';
//        $userText = '';
//
//        //Статус успешного оформления заказа
//        $res = false;
//
//        //Проверяем, авторизован ли пользователь
//        if(!User::isGuest()){
//            //Если не гость, получаем данные о пользователе из БД
//            $userId = User::checkLog();
//            $user = User::getUserById($userId);
//            $userName = $user['name'];
//        }else{
//            //Если гость, то поля формы будут пустыми
//            $userId = false;
//        }
//
//        //Обработка формы
//        if(isset($_POST) and !empty($_POST)){
//            $userName = trim(strip_tags($_POST['name']));
//            $userPhone = trim(strip_tags($_POST['tel']));
//            $userText = trim(strip_tags($_POST['comment']));
//
//            //Флаг ошибок
//            $errors = false;
//
//            //Валидация полей
//            if (!User::checkName($userName)) {
//                $errors[] = 'Имя не может быть короче 2-х символов';
//            }
//
//            if (!User::checkPhone($userPhone)) {
//                $errors[] = 'Введите корректный номер';
//            }
//
//            if($errors == false){
//                // Если ошибок нет
//                // Сохраняем заказ в базе данных
//                $res = Order::save($userName, $userPhone, $userText, $userId, $productsInCart);
//
//                if ($res) {
//                    // Если заказ успешно сохранен
//                    // Оповещаем администратора о новом заказе по почте
//                    $adminEmail = 'oleg.lysenko.1993@gmail.com';
//                    $message = '<a href="http://e-shopper.esy.es/admin/orders">Список заказов</a>';
//                    $subject = 'Новый заказ!';
//                    mail($adminEmail, $subject, $message);
//
//                    // Очищаем корзину
//                    Cart::clear();
//                }
//            }
//        }
//
//        require_once(ROOT . '/views/cart/checkout.php');
//        return true;
//    }
}
