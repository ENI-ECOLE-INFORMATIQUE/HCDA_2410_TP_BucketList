<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,
            ['label' => 'Your idea'])
            ->add('description',TextareaType::class,
                ['label' => 'Please describe it', 'required' => false])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Category',
                'placeholder' => '--Choose a category--',
            ])
            ->add('isPublished', CheckboxType::class,['required' => false,'label' => 'Published'])
            ->add('dateCreated', null, [
                'widget' => 'single_text',
            ])
            ->add('dateUpdated', null, [
                'widget' => 'single_text',
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ]
            ])
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
           $wish = $event->getData();
           if($wish && $wish->getFilename() ) {
               //Cas où on est en modification et qu'une image est déjà présente.
               //On ajoute une checkbox pour permettre de demander la suppression de l'image.
               $form = $event->getForm();
               $form->add('deleteImage', CheckboxType::class, [
                   'required' => false,
                   'mapped' => false,
               ]);
           }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
