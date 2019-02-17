<?php
namespace App\Controller;

use App\Form\EditOrderFormType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrderController extends AbstractController
{
    /** @var OrderRepository */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @Route("/admin/order", name="admin.order.index")
     */
    public function index(): Response
    {
        $orders = $this->orderRepository->findAll();

        return $this->render('admin/order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/admin/order/{id}", name="admin.order.view")
     */
    public function view(int $id): Response
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            throw new \HttpException("Order not found", 404);
        }

        $products = $order->getProducts();

        return $this->render('products/view.html.twig', [
            'order' => $order,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/admin/order/{id}", name="admin.order.view")
     */
    public function edit(int $id, Request $request): Response
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            throw new \HttpException("Order not found", 404);
        }

        $form = $this->createForm(EditOrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
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
        $order = $this->orderRepository->find($id);

        if (!$order) {
            throw new \HttpException("Order not found", 404);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($order);
        $entityManager->flush();

        return $this->redirectToRoute('admin.order.index');
    }
}
