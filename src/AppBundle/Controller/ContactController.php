<?php
/**
 * Created by PhpStorm.
 * User: vahav
 * Date: 30/08/2018
 * Time: 10:47
 */

namespace AppBundle\Controller;

use AppBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\Request;


class ContactController extends Controller
{

    public function contactAction(Request $request){
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $mailer = $this->get('mailer');
            $message = (new \Swift_Message())
                ->setSubject($form->get('subject')->getData())
                ->setFrom('vah.a.val@gmail.com')
                ->setBody($form->get('message')->getData(),'text/html');

//            dump($form->get('all')->getData());die;
            if($form->get('all')->getData()){
                $receivers = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll();
            }else{
                $receivers = $form->get('receivers')->getData();
            }
            foreach ($receivers as $receiver){
                $message->setTo($receiver->getEmail());
                $mailer->send($message);
            }
//            $this->get('swiftmailer.command.spool_send')->run(new ArgvInput(array()), new ConsoleOutput());


            $this->addFlash('success',$this->get('translator')->trans('the.email.was.was.successfully.send'));
            return $this->redirectToRoute('contact_contact');

        }
        return $this->render('contact/contact.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}