<?php
namespace App\Controller;

use App\Entity\Product;
use App\Form\AddProductFormType;
use App\Form\EditProductFormType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController
{
    /** @var ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/admin/product", name="admin.product.index")
     */
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render('admin/product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/admin/product/{id}/delete", name="admin.product.delete")
     */
    public function delete(int $id): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new \HttpException("Product not found", 404);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('admin.product.index');
    }

    /**
     * @Route("/admin/product/add", name="admin.product.add")
     */
    public function add(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(AddProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
        }

        return $this->render('admin/product/add.html.twig', [
            'addProductForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/product/{id}/edit", name="admin.product.edit")
     */
    public function edit(int $id, Request $request): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new \HttpException("Product not found", 404);
        }

        $form = $this->createForm(EditProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
        }

        return $this->render('admin/product/edit.html.twig', [
            'editProductForm' => $form->createView(),
        ]);
    }
}
