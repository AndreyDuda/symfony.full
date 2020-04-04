<?php
declare(strict_types=1);

namespace App\Model\User\UserCase\SignUp\Request;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', Type\EmailType::class)
            ->add('password', Type\PasswordType::class);
    }

    public function configureOptions(OptionsResolver $resolver):void
    {
        $resolver->setDefault('data_class', Command::class);
    }
    /*public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', Type\TextType::class)
            ->add('lastName', Type\TextType::class)
            ->add('email', Type\EmailType::class)
            ->add('password', Type\PasswordType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver):void
    {
        $resolver->setDefault('data_class', Command::class);
    }*/
}