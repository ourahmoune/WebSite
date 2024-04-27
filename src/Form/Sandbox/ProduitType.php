<?php

namespace App\Form\Sandbox;
use App\Entity\Sandbox\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('libelle', TextType::class, [
            'label' => 'libelle de nouveau produit ',
        ]
            
            )
            ->add('prix', NumberType::class, [
                'label' => 'Prix (en euros)',
                'scale' => 2, // permet de définir le nombre de décimales à afficher
                'attr' => [
                    'step' => '0.01', // permet de définir l'incrément de la valeur du champ
                    'min' => '0', // permet de définir la valeur minimale autorisée
                'invalid_message' =>'le prix est un nombre',
                ],
                
            ])
    

            ->add('stock', NumberType::class, [
                'label' => 'Quantité en stock',
                
                
            ])
            
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
