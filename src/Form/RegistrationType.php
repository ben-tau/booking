<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',TextType::class,$this->getConfiguration("Nom","Votre nom ..."))
            ->add('lastname',TextType::class,$this->getConfiguration("Prénom","Votre prénom ..."))
            ->add('email',EmailType::class,$this->getConfiguration("Email","Un email valide"))
            ->add('hash',PasswordType::class,$this->getConfiguration("Mot de passe","Choisissez un bon mot de passe",["mapped" => true]))
            ->add('passwordConfirm',PasswordType::class,$this->getConfiguration("Confirmation mdp","Confirmez votre mot de passe"))
            ->add('introduction',TextType::class,$this->getConfiguration("Introduction","Description courte pour vous présenter"))
            ->add('description',TextareaType::class,$this->getConfiguration("description","Description détaillée"))
            ->add('avatar',UrlType::class,$this->getConfiguration("Avatar","Url de votre avatar"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    
}
