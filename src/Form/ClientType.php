<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Name'])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'required' => true,
            ])
            ->add('nbrPersonne', IntegerType::class, ['label' => 'Number of Persons'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('hotel', EntityType::class, [
                'class' => Hotel::class,
                'choice_label' => 'nomHotel',
                'label' => 'Hotel'
            ])
            ->add('pensionType', ChoiceType::class, [
                'label' => 'Pension Type',
                'required' => true,
                'placeholder' => 'Choose a pension type',
                'choices' => [
                    'ALL_INCLUSIVE' => 'ALL_INCLUSIVE',
                    'FULL_BOARD' => 'FULL_BOARD',
                    'HALF_BOARD' => 'HALF_BOARD',
                    'BED_BREAKFAST' => 'BED_BREAKFAST',
                    'ROOM_ONLY' => 'ROOM_ONLY',
                ],
            ])
            ->add('dateArrivee', DateTimeType::class, ['label' => 'Arrival Date'])
            ->add('dateDepart', DateTimeType::class, ['label' => 'Departure Date'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}