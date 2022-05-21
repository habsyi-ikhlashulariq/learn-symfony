<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="app_product" , methods={"GET"})
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }

    /**
     * @Route("/product/showAll", name="show_product", methods={"GET"})
     */
    public function showAll(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();
 
        $data = [];
 
        foreach ($products as $product) {
           $data[] = [
               'category' => $product->getCategory(),
               'id' => $product->getId(),
               'name' => $product->getName(),
               'price' => $product->getPrice(),
               'description' => $product->getDescription(),
           ];
        }

        $response = [
            "code" => 200,
            "message" => "Success get Data",
            "data" => $data
        ];

        return $this->json($response);
    }

    /**
     * @Route("/product", name="add_product", methods={"POST"})
     */

    public function new(Request $request): Response
    {
        $bodyrequest = $request->getContent();
        $datarequest = json_decode($bodyrequest, true);
        
        $getCategory = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find(2);
        
        $category = new Category();
        $category->setName($datarequest['category_name']);
 
        $product = new Product();
        $product->setName($datarequest['product_name']);
        $product->setPrice($datarequest['price']);
        $product->setDescription($datarequest['description']);

        $product->setCategory($category);
        dd($getCategory->getProducts());
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($category);
        $entityManager->persist($product);
        $entityManager->flush();
 
        return $this->json('Created new project successfully with id ' . $product->getId() . " and category ". $category->getId());
    }

    /**
     * @Route("/category", name="show_product_byID", methods={"GET"})
     */

    public function showAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $category = $entityManager->getRepository(Category::class)->find(2);

        $product = new Product();

        $product->setCategory($category);
        dd($category->getProducts());

    }

}
