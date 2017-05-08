<?php

namespace OnlineShopBundle\Form;

use OnlineShopBundle\Entity\UserProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Име на продукта', 'disabled' => true))
            ->add('description', TextareaType::class, array('label' => 'Описание', 'disabled' => true))
            ->add('size', TextType::class, array('label' => 'Размер', 'disabled' => true))
            ->add('quantity', IntegerType::class, array('label' => 'Количество'))
            ->add('price', MoneyType::class, array('label' => 'Цена'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => UserProduct::class
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'online_shop_bundle_user_product_type';
    }
}
