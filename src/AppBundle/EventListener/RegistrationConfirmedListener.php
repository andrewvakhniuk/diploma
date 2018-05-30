<?php
/**
 * Created by PhpStorm.
 * User: vahav
 * Date: 28/05/2018
 * Time: 01:17
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegistrationConfirmedListener implements EventSubscriberInterface
{

    private $em;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, \Swift_Mailer $mailer)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;

    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_CONFIRM => 'onRegistrationConfirm'
        );
    }

    public function onRegistrationConfirm( GetResponseUserEvent $event){
        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
            ->from('AppBundle:User', 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.'ROLE_ADMIN'.'"%');
        $admins = $qb->getQuery()->getResult();
        $user = $event->getUser();
        /** @var User $admin */
        foreach ($admins as  $admin){
            $message = (new \Swift_Message('Braille Score: new user was registered - '.$user->getUsername()))
                ->setFrom('send@example.com')
                ->setTo($admin->getEmail())
                ->setBody(
                    'New user was registered:
                username: '.$user->getUsername().'
                email: '.$user->getEmail(),
                    'text/plain'
                )
            ;

            $this->mailer->send($message);
        }

    }
}