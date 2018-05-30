<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Simettric\DoctrineTranslatableFormBundle\Form\AbstractTranslatableType;
use Simettric\DoctrineTranslatableFormBundle\Form\TranslatableTextType;

class SubPageType extends AbstractTranslatableType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('active');

        // you can add the translatable fields
        $this->createTranslatableMapper($builder, $options)
            ->add("name", TranslatableTextType::class)
            ->add("content", TranslatableTextType::class,[
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
            'data_class' => 'AppBundle\Entity\SubPage'
        ));
        // this is required
        $this->configureTranslationOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_subpage';
    }


}
