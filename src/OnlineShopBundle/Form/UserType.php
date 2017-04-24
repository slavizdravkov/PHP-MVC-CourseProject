<?php

namespace OnlineShopBundle\Form;

use OnlineShopBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Име'])
            ->add('lastName', TextType::class, ['label' => 'Фамилия'])
            ->add('city', null, ['label' => 'Град', 'placeholder' => 'Избери град'])
            ->add('address', TextareaType::class, ['label' => 'Адрес'])
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'required' => false,
                    'first_options' => ['label' => 'Парола'],
                    'second_options' => ['label' => 'Повтори паролата']
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'online_shop_bundle_user_type';
    }
}
