<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchForm;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product")
     */
    public function index(
        ProductRepository $repository,
        Request $request
    ): Response {
        $data = new SearchData();
        $data->page = $request->query->getInt('page', 1);
        $form = $this->createForm(SearchForm::class, $data);

        $form->handleRequest($request);

        [$min, $max] = $repository->findMinMax($data);

        $products = $repository->findSearch($data);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('product/_products.html.twig', [
                    'products' => $products,
                ]),
                'sorting' => $this->renderView('product/_sorting.html.twig', [
                    'products' => $products,
                ]),
                'pagination' => $this->renderView(
                    'product/_pagination.html.twig',
                    [
                        'products' => $products,
                    ]
                ),
                'pages' => ceil(
                    $products->getTotalItemCount() /
                        $products->getItemNumberPerPage()
                ),
                'min' => $min,
                'max' => $max,
            ]);
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'min' => $min,
            'max' => $max,
        ]);
    }
}
