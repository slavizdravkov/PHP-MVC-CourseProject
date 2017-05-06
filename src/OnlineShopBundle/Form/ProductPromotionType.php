<?php

namespace OnlineShopBundle\Form;

use OnlineShopBundle\Entity\ProductPromotion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductPromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('percent', IntegerType::class, array('label' => 'Отстъпка в %'))
            ->add('startData', DateTimeType::class, array('label' => 'Начална дата'))
            ->add('endData', DateTimeType::class, array('label' => 'Крайна дата'));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ProductPromotion::class
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'online_shop_bundle_product_promotion_type';
    }
}
