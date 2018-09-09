<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 10/12/2017
 * Time: 2:09 PM
 */

namespace AppBundle\Form;

use AppBundle\Entity\Address;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class UserPermissionsType extends AbstractType
{
    private $translator;
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator= $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = $options['roles'];


        $builder
            ->add('availablePages', EntityType::class, array(
                'label'=>'available.pages.to.view',
                'class'       => 'AppBundle:Page',
                'placeholder' => 'choose.pages.that.user.can.have.access.to',
                'required' => false,
                'multiple'=>true,
                'group_by' => function($value, $key, $index) {

                    return $value->getMainPage()? $value->getMainPage()->getName() : $value->getName();
                },
                'choice_label' =>function($page){
                    return $page->getMainPage()? $page->getName() .' - '.$this->translator->trans('sub.page') : $page->getName() .' - '. $this->translator->trans('main.page');
                }
            ))
            ->add('editablePages', EntityType::class, array(
                'label'=>'available.pages.to.edit',
                'class'       => 'AppBundle:Page',
                'placeholder' => 'choose.pages.that.user.can.have.access.to',
                'required' => false,
                'multiple'=>true,
                'group_by' => function($value, $key, $index) {

                    return $value->getMainPage()? $value->getMainPage()->getName() : $value->getName();
                },
                'choice_label' =>function($page){
                    return $page->getMainPage()? $page->getName() .' - '.$this->translator->trans('sub.page') : $page->getName() .' - '. $this->translator->trans('main.page');
                }
            ))
            ->add('ROLE_ACCESS_TO_VIEW_ALL_PAGES',CheckboxType::class,[
                'label'=> 'permission.to.view.all.pages',
                'mapped'=>false,
                'required' => false,
            ])
            ->add('ROLE_ACCESS_TO_EDIT_ALL_PAGES',CheckboxType::class,[
                'label'=> 'permission.to.edit.all.pages',
                'mapped'=>false,
                'required' => false,
            ])
            ->add('ROLE_ADD_PAGE',CheckboxType::class,[
                'label'=> 'permission.to.add.new.page',
                'mapped'=>false,
                'required' => false,
            ])

            ->add('ROLE_ADD_LIBRARY',CheckboxType::class,[
                'label'=> 'permission.to.add.new.library',
                'mapped'=>false,
                'required' => false,
            ])
            ->add('ROLE_EDIT_LIBRARY',CheckboxType::class,[
                'label'=> 'permission.to.edit.libraries',
                'mapped'=>false,
                'required' => false,
            ])
            ->add('ROLE_SEND_EMAIL',CheckboxType::class,[
                'label'=> 'permissions.to.contact.users',
                'mapped'=>false,
                'required' => false,
            ])
            ->add('ROLE_PERMISSION_PANEL',CheckboxType::class,[
                'label'=> 'permission.to.check.users.and.control.their.permissions',
                'mapped'=>false,
                'required' => false,
            ])
        ;

        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) use ($roles){
            $user = $event->getData();
            $form = $event->getForm();
            foreach ($roles as $role){

                if($user->hasRole($role)){
                    $form->get($role)->setData(true);
                }else{
                    $form->get($role)->setData(false);
                }
            }
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($roles){
            $user =  $event->getData();
            $form = $event->getForm();
            foreach ($roles as $role){

                if($form->get($role)->getData()){
                    $user->addRole($role);
                }else{
                    $user->removeRole($role);
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'roles'=> [],


        ));
    }}