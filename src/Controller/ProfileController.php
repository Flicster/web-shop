<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
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
            $user->setUpdatedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
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

        return $this->render('profile/order.html.twig', [
            'orders' => $user->getOrders(),
        ]);
    }
}
