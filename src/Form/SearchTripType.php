<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Vehicule;

class SearchTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('villeDepart', TextType::class, [
                'required' => false,
                'label' => 'Ville de départ'
            ])
            ->add('villeArrivee', TextType::class, [
                'required' => false,
                'label' => 'Ville d’arrivée'
            ])
            ->add('dateDepart', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Date',
                'html5' => true
            ])
            ->add('prixMax', NumberType::class, [
                'required' => false,
                'label' => 'Prix maximum',
                'scale' => 2,
            ])
            ->add('placesMin', IntegerType::class, [
                'required' => false,
                'label' => 'Places minimales'
            ])
            ->add('preferences', TextType::class, [
                'required' => false,
                'label' => 'Vos préférences'
            ])
            ->add('fuelType', ChoiceType::class, [
                'required' => false,
                'label' => 'Carburant / Énergie',
                'choices' => array_combine(Vehicule::FUEL_TYPES, Vehicule::FUEL_TYPES)
            ]);
    }

    
}
