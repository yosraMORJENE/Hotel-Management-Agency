<?php

namespace App\Form;

use App\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Image;

class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomHotel', TextType::class, ['label' => 'Hotel Name'])
            ->add('capacity', IntegerType::class, ['label' => 'Capacity'])  // ✅ use correct property
            ->add('price', NumberType::class, ['label' => 'Price'])
            ->add('address', TextareaType::class, [
                'required' => false,
                'label' => 'Address',
                'attr' => [
                    'rows' => 2,
                ],
            ])
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Image (upload)',
                'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                        'mimeTypesMessage' => 'Please upload a valid image file.',
                    ]),
                ],
                'help' => 'Upload an image from your computer (JPG/PNG/WebP).',
            ])
            ->add('image', TextType::class, [
                'required' => false,
                'label' => 'Image (URL)',
                'help' => 'Optional: paste an external image URL. If you upload a file, the upload will be used.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotel::class,
        ]);
    }
}
