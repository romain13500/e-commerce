<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
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
                    'choice_label' => function (Category $category){
                        return strtolower($category->getName());
                    }
                ]);

                $builder->get('price')->addModelTransformer(new CallbackTransformer( 
                    function($value){
                        if ($value === null) {
                            return;
                        }
                        return $value / 100;
                    },
                    function($value){
                        if ($value === null) {
                            return;
                        }
                        return $value * 100;
                    }
                ));

            //         // *** EVENTLISTENER BEFORE AND AFTER FORM SUBMIT

            // $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            //     $form = $event->getForm();

            //     /** @var Product */ 
            //     $product = $event->getData();

            //         // CONVERTION CTS/€ FOR THE FORM
            //     if($product->getPrice() !== null){
            //        $product->setPrice($product->getPrice() / 100); 
            //     }
                
            // });

            //     // CONVERTION €/CTS FOR BDD AFTER SUBMIT
            // $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            //     $product = $event->getData();
                
            //     if($product->getPrice() !== null) {
            //         $product->setPrice($product->getPrice() * 100);
            //     }
            // });
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
