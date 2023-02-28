<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"} )
     */
    public function add($id, ProductRepository $productRepository, SessionInterface $session, FlashBagInterface $flashBag)
    {
        //  0 - Sécurisation : est ce que le produit existe
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le Produit $id n'existe pas !");
        }
        
        //  1 - Retrouver le panier dans la session (sous forme de tableau)
        //  2 - Si inexistant, prendre un tableau vide
        $cart = $session->get('cart', []);

        //  3 - Voir si le product (id) existe déjà dans le tableau
        //  4 - Si il existe, augmenter la quantité
        //  5 - Sinon ajouter le product en quantité de 1
        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        }else {
            $cart[$id] = 1;
        }

        //  6 - Enregistrer le tableau
        $session->set('cart', $cart);

        $flashBag->add('success', "Le produit a été ajouté au panier ! ");

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);

    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(SessionInterface $session, ProductRepository $productRepository)
    {
        $detailecart = [];
        $total = 0;

        foreach ($session->get('cart', []) as $id => $qty) {
            $product = $productRepository->find($id);

            $detailecart[] = [
                'product' => $product,
                'qty' => $qty
            ];

            $total += ($product->getPrice() * $qty);
        }
        
        return $this->render('cart/index.html.twig', [
            'items' => $detailecart,
            'total' => $total
        ]);
    }
}
