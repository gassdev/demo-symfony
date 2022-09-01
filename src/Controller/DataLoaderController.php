<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataLoaderController extends AbstractController
{
    /**
     * @Route("/data/loader", name="app_data_loader")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $file_product =
            dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'products.json';

        $file_category =
            dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'categories.json';

        $data_products = json_decode(file_get_contents($file_product))[0]->data;
        $data_categories = json_decode(file_get_contents($file_category))[0]
            ->data;
        //     ->data;

        $categories = [];

        for ($i = 0; $i < 10; $i++) {
            $data_category = $data_categories[$i];
            $category = new Category();
            $category->setName($data_category[0]);

            $em->persist($category);
            $categories[] = $category;
        }

        $products = [];

        foreach ($data_products as $data_product) {
            $random_keys = array_rand($categories, 3);
            $product_categories = [];
            $product_categories[] = $categories[$random_keys[0]];
            $product_categories[] = $categories[$random_keys[1]];
            $product_categories[] = $categories[$random_keys[2]];

            $product = new Product();
            $number = random_int(400, 800);
            $imageURL = 'http://picsum.photos/id/' . $number . '/290/180';
            $product
                ->setName($data_product[0])
                ->setPrice($data_product[1])
                ->setDescription($data_product[2])
                ->setContent('Content')
                ->setImage($imageURL)
                ->setPromo($data_product[4])
                ->addCategory($product_categories[0])
                ->addCategory($product_categories[1])
                ->addCategory($product_categories[2]);

            $em->persist($product);
            $products[] = $product;
        }

        $em->flush();

        // dd($data_products);
        // dd($data_categories);

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DataLoaderController.php',
        ]);
    }
}
