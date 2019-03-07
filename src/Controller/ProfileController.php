<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileFormType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/profile", name="profile.index")
     */
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/profile/edit", name="profile.edit")
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('profile.index');
        }

        return $this->render('profile/edit.html.twig', [
            'editProfileForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/order", name="profile.order.index")
     */
    public function order(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $orders = $user->getOrders();

        foreach ($orders as $order) {
            $productsIds = explode(',', $order->getProducts());
            $productsIdsCount = array_count_values($productsIds);
            $products = $this->productRepository->findBy(['id' => array_unique($productsIds)]);

            $order->productsEntity = $products;
            foreach ($products as $product) {
                $product->count = $productsIdsCount[$product->getId()];
                $product->sum = $product->getPrice() * $productsIdsCount[$product->getId()];
            }
        }

        return $this->render('profile/orders.html.twig', [
            'orders' => $user->getOrders(),
        ]);
    }
}
