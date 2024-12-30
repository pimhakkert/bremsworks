<?php

namespace App\Form;

use App\Entity\Isotope;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'attr' => [
                    'placeholder' => 'Product Title',
                ]
            ])
            ->add('description')
            ->add('quantity')
            ->add('price')
            ->add('category', ChoiceType::class, [
                'choices' => array_combine(Product::ALL_CATEGORIES, Product::ALL_CATEGORIES),
            ])
            ->add('width', null, [
                'label' => 'Width (mm)'
            ])
            ->add('height', null, [
                'label' => 'Height (mm)'
            ])
            ->add('depth', null, [
                'label' => 'Depth (mm)'
            ])
            ->add('weight', null, [
                'label' => 'Weight (g)'
            ])
            ->add('color')
            ->add('brand')
            ->add('model')
            ->add('condition', ChoiceType::class, [
                'choices' => array_combine(Product::ALL_CONDITIONS, Product::ALL_CONDITIONS),
            ])
            ->add('isotopes', EntityType::class, [
                'class' => Isotope::class,
                'choice_label' => 'name',
                'multiple' => true,
                'choice_value' => 'id',
                'attr' => [
                    'data-options' => json_encode([
                        'maxItems' => 3,
                        'plugins' => ['remove_button']
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
