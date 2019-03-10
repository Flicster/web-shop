<?php
namespace App\Controller;

use App\Entity\Product;
use App\Form\AddProductFormType;
use App\Form\EditProductFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var CategoryRepository */
    private $categoryRepository;

    /** @var FileUploader */
    private $fileUploader;

    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        FileUploader $fileUploader
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->fileUploader = $fileUploader;
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
     * @Route("/admin/product/add", name="admin.product.add")
     */
    public function add(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(AddProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = new File($product->getImage());

            $fileName = $this->fileUploader->upload($file);

            $product->setImage($fileName);

            $date = new \DateTime();
            $product->setCreatedAt($date);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Товар успешно добавлен.');

            return $this->redirectToRoute('admin.product.index');
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
            $file = new File($product->getImage());

            $fileName = $this->fileUploader->upload($file);

            $product->setImage($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Товар успешно отредактирован.');

            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('admin/product/edit.html.twig', [
            'editProductForm' => $form->createView(),
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

        $this->addFlash('success', 'Товар успешно удален.');

        return $this->redirectToRoute('admin.product.index');
    }
}
