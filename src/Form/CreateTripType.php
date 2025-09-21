<?php

namespace App\Form;


use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Vehicule;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CreateTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('villeDepart', TextType::class, [
                'label' => 'Ville de départ',
            ])
            ->add('villeArrivee', TextType::class, [
                'label' => 'Ville d’arrivée',
            ])
            ->add('dateDepart', DateTimeType::class, [
                'label' => 'Date et heure de départ',
                'widget' => 'single_text',
            ])
            ->add('dateArrivee', DateTimeType::class, [
                'label' => 'Date et heure d’arrivée',
                'widget' => 'single_text',
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix (en crédits)',
                'currency' => false,
            ])
            ->add('placesDispo', IntegerType::class, [
                'label' => 'Nombre de places disponibles',
            ])

            ->add('vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => function(Vehicule $vehicule) {
                    return $vehicule->getMarque() . ' ' . $vehicule->getModele() . ' (' . $vehicule->getImmatriculation() . ')';
                },
                'label' => 'Sélectionner un véhicule',
                'placeholder' => 'Choisissez un véhicule',
                'attr' => ['class' => 'form-control'],
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
