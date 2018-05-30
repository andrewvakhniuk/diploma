<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Simettric\DoctrineTranslatableFormBundle\Form\AbstractTranslatableType;
use Simettric\DoctrineTranslatableFormBundle\Form\TranslatableTextType;

class PageType extends AbstractTranslatableType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('active',CheckboxType::class,[
            'label'=>'active',
            'required'=>false,
        ]);
        $builder->add('public',CheckboxType::class,[
            'label'=>'public',
            'required'=>false,
        ]);

        // you can add the translatable fields
        $this->createTranslatableMapper($builder, $options)
            ->add("name", TranslatableTextType::class,[
                'label'=>'appellation'
            ])
            ->add("content", TranslatableTextType::class,[
                'label'=>'content',
                'attr'=>[
                    'class'=>'text-editor'
                ]
            ])
        ;

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Page'
        ));
        // this is required for translatable
        $this->configureTranslationOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_page';
    }


}
