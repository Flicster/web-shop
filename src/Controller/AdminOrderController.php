<?php
namespace App\Controller;

use App\Form\EditOrderFormType;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrderController extends AbstractController
{
    /** @var OrdersRepository */
    private $ordersRepository;

    /** @var ProductRepository */
    private $productRepository;

    public function __construct(OrdersRepository $ordersRepository, ProductRepository $productRepository)
    {
        $this->ordersRepository = $ordersRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/admin/order", name="admin.order.index")
     */
    public function index(): Response
    {
        $orders = $this->ordersRepository->findAll();

        return $this->render('admin/order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/admin/order/{id}", name="admin.order.view")
     */
    public function view(int $id): Response
    {
        $order = $this->ordersRepository->find($id);

        if (!$order) {
            throw new \HttpException("Order not found", 404);
        }

        $productsIds = explode(',', $order->getProducts());
        $products = $this->productRepository->findBy(['id' => $productsIds]);

        return $this->render('admin/order/view.html.twig', [
            'order' => $order,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/admin/order/{id}/edit", name="admin.order.edit")
     */
    public function edit(int $id, Request $request): Response
    {
        $order = $this->ordersRepository->find($id);

        if (!$order) {
            throw new \HttpException("Order not found", 404);
        }

        $form = $this->createForm(EditOrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            $order->setUpdatedAt($date);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('admin.order.index');
        }

        return $this->render('admin/order/edit.html.twig', [
            'editOrderForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/order/{id}/delete", name="admin.order.delete")
     */
    public function delete(int $id): Response
    {
        $order = $this->ordersRepository->find($id);

        if (!$order) {
            throw new \HttpException("Order not found", 404);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($order);
        $entityManager->flush();

        return $this->redirectToRoute('admin.order.index');
    }
}
