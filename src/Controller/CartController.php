<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"} )
     */
    public function add($id, ProductRepository $productRepository, CartService $cartService, FlashBagInterface $flashBag)
    {
        //  0 - Sécurisation : est ce que le produit existe
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le Produit $id n'existe pas !");
        }

        $cartService->add($id);


        $flashBag->add('success', "Le produit a été ajouté au panier ! ");

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);

    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(CartService $cartService)
    {
        $detailedCart = $cartService->getDetailedCartitems();
        $total = $cartService->getTotal();
        
        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total
        ]);
    }


    /**
     * @Route("cart/delete/{id}", name="cart_delete", requirements={"id": "\d+"})
     */
    public function delete($id, ProductRepository $productRepository, CartService $cartService){
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit n'existe pas !");
        }
        $cartService->remove($id);

        $this->addFlash("success", "Le produit a bien été supprimé du panier !");
        return $this->redirectToRoute("cart_show");
    }
}
