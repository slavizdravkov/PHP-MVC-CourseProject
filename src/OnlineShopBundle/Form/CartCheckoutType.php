<?php

namespace OnlineShopBundle\Form;

use OnlineShopBundle\Entity\Cart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartCheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shipFirstName', TextType::class, array('label' => 'Име'))
            ->add('shipLastName', TextType::class, array('label' => 'Фамилия'))
            ->add('shipCity', TextType::class, array('label' => 'Град'))
            ->add('shipZipCode', TextType::class, array('label' => 'Пощенски код'))
            ->add('shipAddress', TextareaType::class, array('label' => 'Адрес'))
            ->add('shipEmail', EmailType::class, array('label' => 'Email'))
            ->add('shipPhone', TextType::class, array('label' => 'Телефон'));
//            ->add('paymentMethod', ChoiceType::class, array(
//                'choices' => array(
//                    'PayPal' => 'card',
//                    'При получаване' => 'on_delivery'
//                ),
//                'multiple' => false,
//                'expanded' => true,
//                'label' => 'Начин на плащане'
//            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Cart::class,
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'online_shop_bundle_cart_checkout_type';
    }
}
