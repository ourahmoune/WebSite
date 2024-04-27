<?php
namespace App\Form;

use App\Entity\Panier;
use App\Entity\Sandbox\Produit;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class PanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $quantiteMax = $options['quantiteMax'];
        $quantitePanier = $options['quantitePanier'];
        $quantiteChoices = array_combine(range(-$quantitePanier, $quantiteMax), range(-$quantitePanier, $quantiteMax));
        $builder
            ->add('quantite',ChoiceType::class,
                [
                    'choices' => $quantiteChoices,
                    'data' => 0,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['quantiteMax', 'quantitePanier' ]);
        $resolver->setAllowedTypes('quantiteMax', 'integer');
        $resolver->setAllowedTypes('quantitePanier', 'integer');
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }



}