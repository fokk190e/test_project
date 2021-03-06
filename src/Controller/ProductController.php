<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use App\Service\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProductList()
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getRole() == 'ROLE_MANAGER') {
            $categories  = $user->getCategories();
            $categoriesId = [];

            if (count($categories)) {
                foreach ($categories as $category) {
                    $categoriesId[] = $category->getId();
                }

                $productList = $this->getDoctrine()->getRepository(Product::class)->getProductsAllowCategories($categoriesId);
            }

        } else {
            $productList = $this->getDoctrine()->getRepository(Product::class)->findAll();
        }

        return $this->render('Product/products.html.twig', [
            'user' => $user,
            'products' => $productList
        ]);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProduct(Request $request, Product $product)
    {
        return $this->render('Product/product.html.twig', [
            'product' => '$product'
        ]);
    }

    public function addProduct(Request $request, UploadService $uploadService)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form['imageFilename']->getData();

            if ($file) {
                $newFilename = $uploadService->uploadFile($file);
                $product->setImageFilename($newFilename);
            }

            $this->getDoctrine()->getManager()->persist($product);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_list');
        }

        return $this->render('Product/product.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request redirectToRoute
     * @param Product $product
     * @param UploadService $uploadService
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function editProduct(Request $request, Product $product, UploadService $uploadService)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form['imageFilename']->getData();

            if ($file) {
                $newFilename = $uploadService->uploadFile($file);
                $product->setImageFilename($newFilename);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_list');
        }

        return $this->render('Product/product.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
