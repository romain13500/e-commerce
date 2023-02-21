<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function create(FormFactoryInterface $factory, Request $request, SluggerInterface $slugger){


        $builder = $factory->createBuilder(FormType::class, null, [
            'data_class' => Product::class
        ]);

        $builder->add('name', TextType::class, [
            'label' => 'Nom du produit',
            'attr' => ['placeholder' => 'Entrez le nom du produit']

        ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Description courte',
                'attr' => ['placeholder' => 'Description du produit']
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'attr' => ['placeholder' => 'Entrez un prix']
            ])


            ->add('mainPicture', UrlType::class, [
                'label' => 'Image du produit',
                'attr' => ['placeholder' => 'Entrez une URL d\'image ']
            ])

            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => 'name'
            ]
        );

        $form = $builder->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted()){

            $product = $form->getData();
            $product->setSlug(strtolower($slugger->slug($product->getName())));

            // $product = new Product;
            // $product->setName($data['name'])
            //     ->setShortDescription($data['shortDescription'])
            //     ->setPrice($data['price'])
            //     ->setCategory($data['category']);

                dd($product);
        }
        
        

        $formView = $form->createView();


        return $this->render('product/create.html.twig', ['formView' => $formView] );
    }
}
