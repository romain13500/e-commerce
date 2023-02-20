<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="product_category")
     */
    public function category($slug, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);
        
        if (!$category) {
            throw new NotFoundHttpException("La catégorie n'existe pas");
        }

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}", name="product_show")
     */
    public function show($slug, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            throw new NotFoundHttpException("Le produit n'existe pas !");
        }

        return $this->render('product/show.html.twig', ['product' => $product]);
    }


    /**
     * @Route("/admin/product/create", name="product_create")
     */
    public function create(){
        return $this->render('product/create.html.twig');
    }
}
