<?php

namespace OnlineShopBundle\Form;

use OnlineShopBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends UserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('cash', IntegerType::class, ['label' => 'Сума за покупки'])
            ->add('status', null, ['label' => 'Статус', 'placeholder' => 'Избери статус'])
            ->add('roles', ChoiceType::class, array(
            'choices' => [
                            'User' => 'ROLE_USER',
                            'Editor' => 'ROLE_EDITOR',
                            'Admin' => 'ROLE_ADMIN'
                        ],
            'expanded' => true,
            'multiple' => true
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class
            ]);
    }

    public function getBlockPrefix()
    {
        return 'online_shop_bundle_user_edit_type';
    }
}
