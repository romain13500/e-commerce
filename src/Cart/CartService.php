<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }
    public function add(int $id) {
          //  1 - Retrouver le panier dans la session (sous forme de tableau)
        //  2 - Si inexistant, prendre un tableau vide
        $cart = $this->session->get('cart', []);

        //  3 - Voir si le product (id) existe déjà dans le tableau
        //  4 - Si il existe, augmenter la quantité
        //  5 - Sinon ajouter le product en quantité de 1
        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        }else {
            $cart[$id] = 1;
        }

        //  6 - Enregistrer le tableau
        $this->session->set('cart', $cart);
    }

    public function remove(int $id) {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        $this->session->set('cart', $cart);
    }


    public function decrement(int $id){
        $cart = $this->session->get('cart', []);

        if (!array_key_exists($id, $cart)) {
            return;
        }

            // produit panier = 1 alors on supprime
        if ($cart[$id] === 1) {
            $this->remove($id);
            return;
        }
            // produit panier + de 1 on décrémente
        $cart[$id]--;

        $this->session->set('cart', $cart);

    }

    public function getTotal() {
        $total = 0;

        foreach ($this->session->get('cart', []) as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $total += $product->getPrice() * $qty;
        }
        return $total;
    }

    public function getDetailedCartitems() {
        $detailedCart = [];
        

        foreach ($this->session->get('cart', []) as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $detailedCart[] = new Cartitem($product, $qty);
        }
        return $detailedCart;
    }
}