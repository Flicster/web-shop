<?php
namespace App\Controller;

use App\Entity\Category;
use App\Form\AddCategoryFormType;
use App\Form\EditCategoryFormType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    /** @var CategoryRepository */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/admin/category", name="admin.category.index")
     */
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/category/add", name="admin.category.add")
     */
    public function add(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(AddCategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/add.html.twig', [
            'addCategoryForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/{id}/edit", name="admin.category.edit")
     */
    public function edit(int $id, Request $request): Response
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw new \HttpException("Category not found", 404);
        }

        $form = $this->createForm(EditCategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'editCategoryForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/{id}", name="admin.category.delete")
     */
    public function delete(int $id): Response
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw new \HttpException("Category not found", 404);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('admin.category.index');
    }
}
