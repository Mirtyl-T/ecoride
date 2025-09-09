<?php
namespace App\Form;

use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marque', TextType::class, [
                'label' => 'Marque'
            ])
            ->add('modele', TextType::class, [
                'label' => 'Modèle'
            ])
            ->add('immatriculation', TextType::class, [
                'label' => 'Immatriculation'
            ])
            ->add('fuelType', ChoiceType::class, [
                'label' => 'Carburant / Énergie',
                'choices' => array_combine(Vehicule::FUEL_TYPES, Vehicule::FUEL_TYPES)
            ])
            ->add('nbPlaces', IntegerType::class, [
                'label' => 'Nombre de places'
            ])
            ->add('dateImmat', DateType::class, [
                'label' => 'Date d’immatriculation',
                'widget' => 'single_text',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
