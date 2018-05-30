<?php

namespace AppBundle\Form;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Simettric\DoctrineTranslatableFormBundle\Form\AbstractTranslatableType;
use Simettric\DoctrineTranslatableFormBundle\Form\TranslatableTextType;

class LibraryType extends AbstractTranslatableType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        // you can add the translatable fields
        $this->createTranslatableMapper($builder, $options)
            ->add("name", TranslatableTextType::class,
                [
                    'required'=>true,
                ]);

        $builder
            ->add('file',FileType::class,[
                'label'=>'file'
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $library = $event->getData();
            if($library->getId()){
                $event->getForm()->remove('file');
            }

        });



    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Library',
        ));
        // this is required for translatable
        $this->configureTranslationOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_library';
    }


}
