<?php

namespace OnlineShopBundle\Form;

use OnlineShopBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Име на продукта'))
            ->add('description', TextareaType::class, array('label' => 'Описание'))
            ->add('size', TextType::class, array('label' => 'Размер'))
            ->add('quantity', IntegerType::class, array('label' => 'Количество'))
            ->add('price', MoneyType::class, array('label' => 'Цена', 'currency' => 'BGN'))
            ->add('imageUrl', FileType::class,
                [
                    'label' => 'Снимка (jpeg, png, tif файл)',
                    'data_class' => null,
                    'required' => false
                ])
            ->add('category', null,
                    [
                        'label' => 'Категория',
                        'placeholder' => 'Добави категория'
                    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Product::class,
            ]
        );
    }

//    public function getBlockPrefix()
//    {
//        return 'online_shop_bundle_product_type';
//    }
}
