<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('all',CheckboxType::class,[
                'label'=>false,
                'required'=>false
            ])
            ->add('receivers',EntityType::class,[
                'class' => 'AppBundle:User',
                'label' => 'receivers',
                'multiple' => true,
                'required' => false,
            ])
            ->add('subject',TextType::class,[
                'label'=>false,
                'attr'=> [
                    'placeholder'=>'subject'
                ]
            ])
            ->add('message',TextareaType::class,[
                'label'=>false,
                'attr'=> [
                    'cols' => '5',
                    'rows' => '5',
                    'placeholder'=>'message'
                ]
            ])
        ;
    }


}
