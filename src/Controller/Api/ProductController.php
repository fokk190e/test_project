<?php

namespace App\Controller\Api;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Swagger\Annotations as SWG;

class ProductController extends AbstractController
{
    /**
     * @SWG\Post(
     *     path="/api/product/add",
     *     summary="Add new product",
     *     description="Add new product",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="user-token",
     *         in="header",
     *         description="User token",
     *         type="string",
     *         required= true,
     *     ),
     *     @SWG\Parameter(
     *         name="name",
     *         in="query",
     *         description="Product name",
     *         type="string",
     *         required= true,
     *     ),
     *     @SWG\Parameter(
     *         name="image",
     *         in="query",
     *         description="Image",
     *         type="string",
     *         required= true,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="New record successfully created.",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="With any possible problem.",
     *     ),
     * )
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     *
     * @return JsonResponse
     *
     */
    public function add(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        if (!$request->getContent()) {
            return $this->json('No data.', 400);
        }

        $product = $serializer->deserialize($request->getContent(), Product::class, 'json', ['new' => 1]);
        $errors = $validator->validate($product);

        if (\count($errors)) {
            return $this->json('Validate error', 400);
        }

        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();

        return $this->json('Successfully.');
    }

    /**
     * @SWG\Put(
     *     path="/api/product/edit/{id}",
     *     summary="Edit product",
     *     description="v product",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="user-token",
     *         in="header",
     *         description="User token",
     *         type="string",
     *         required= true,
     *     ),
     *     @SWG\Parameter(
     *         name="name",
     *         in="query",
     *         description="Product name",
     *         type="string",
     *         required= true,
     *     ),
     *     @SWG\Parameter(
     *         name="image",
     *         in="query",
     *         description="Image",
     *         type="string",
     *         required= true,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="New record successfully created.",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="With any possible problem.",
     *     ),
     * )
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param Product $product
     *
     * @return JsonResponse
     *
     */
    public function edit(Request $request, Product $product, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        if (!$request->getContent()) {
            return $this->json('No data.', 400);
        }

        $product_edit = $serializer->deserialize($request->getContent(), Product::class, 'json', ['object' => $product]);
        $errors = $validator->validate($product_edit);

        if (\count($errors)) {
            return $this->json('Validate error', 400);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->json('Successfully.');
    }
}
