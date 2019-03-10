<?php
namespace App\Controller;

use App\Entity\Orders;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /** @var ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/cart", name="cart.index")
     */
    public function index(): Response
    {
        $productsIds = $this->get('session')->get('products');
        $productsInCart = [];
        $productsIdsCount = [];
        $products = [];
        $totalPrice = 0;

        if ($productsIds) {
            $productsIdsCount = array_count_values($productsIds);
            $products = $this->productRepository->findBy(['id' => $productsIds]);
        }

        foreach ($products as $product) {
            $productSum = $product->getPrice() * $productsIdsCount[$product->getId()];

            $productsInCart[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'code' => $product->getCode(),
                'price' => $product->getPrice(),
                'count' => $productsIdsCount[$product->getId()],
                'sum' => $productSum,
            ];

            $totalPrice += $productSum;
        }

        return $this->render('cart/index.html.twig', [
            'productsInCart' => $productsInCart,
            'totalPrice' => $totalPrice,
        ]);
    }

    /**
     * @Route("/cart/{id}/add", name="cart.add")
     */
    public function add(int $id, Request $request): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new \HttpException("Product not found", 404);
        }

        $products = $this->get('session')->get('products');

        if (is_null($products)) {
            $products = [];
        }

        $products[] = $id;

        $this->get('session')->set('products', $products);

        $this->addFlash('success', 'Товар успешно добавлен в корзину.');

        return $this->redirect(
            $request
                ->headers
                ->get('referer')
        );
    }

    /**
     * @Route("/cart/{id}/delete", name="cart.delete")
     */
    public function delete(int $id, Request $request): Response
    {
        $productsIds = $this->get('session')->get('products');

        if (in_array($id, $productsIds)) {
            $key = array_search($id, $productsIds);

            if ($key !== false) {
                unset($productsIds[$key]);
            }
        }

        $this->get('session')->set('products', $productsIds);

        return $this->redirect(
            $request
                ->headers
                ->get('referer')
        );
    }

    /**
     * @Route("/order", name="order")
     */
    public function order(): Response
    {
        $productsIds = $productsIds = $this->get('session')->get('products');
        $order = new Orders();
        $order->setUser($this->getUser());
        $order->setCreatedAt(new \DateTime());
        $order->setStatus(Orders::STATUS_TYPES['New']);
        $order->setProducts(join(',', $productsIds));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($order);
        $entityManager->flush();

        $this->get('session')->remove('products');

        $this->addFlash('success', 'Ваш заказ был оформлен, наш администратор свяжется с Вами в ближайшее время!');

        return $this->redirectToRoute('cart.index');
    }
}
