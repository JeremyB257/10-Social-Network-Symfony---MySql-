<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('pseudo')
            ->add('birthday')
            ->add('biography')
            ->add('imgFile', FileType::class, [
                'required' => false,
                'label' => 'Image',
                'mapped' => false,
                'constraints' => [
                    new File(maxSize: '1024k', mimeTypes: ['image/jpg', 'image/jpeg', 'image/png']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
