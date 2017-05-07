<?php

namespace OnlineShopBundle\Form;

use OnlineShopBundle\Entity\CategoryPromotion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryPromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('percent', IntegerType::class, array('label' => 'Отстъпка в %'))
            ->add('startDate', DateTimeType::class, array('label' => 'Начална дата'))
            ->add('endDate', DateTimeType::class, array('label' => 'Крайна дата'));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CategoryPromotion::class
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'online_shop_bundle_category_promotion_type';
    }
}
