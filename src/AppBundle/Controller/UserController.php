<?php
/**
 * Created by PhpStorm.
 * User: vahav
 * Date: 17/05/2018
 * Time: 13:52
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function indexAction(){
        $users = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll();

        return $this->render('users/index.html.twig',[
            'users' => $users
        ]);
    }
    public function showAction(User $user){
        $changeStatusForm = $this->createChangeStatusForm($user);


        return $this->render('users/show.html.twig',[
            'change_status_form' => $changeStatusForm->createView(),
            'user' => $user
        ]);
    }
    public function changeStatusAction(Request $request, User $user)
    {
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('Not user object');
        }
        $changeStatusForm = $this->createChangeStatusForm($user);
        $changeStatusForm->handleRequest($request);

        if ($changeStatusForm->isSubmitted() && $changeStatusForm->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            if ($user->isEnabled()) {
                $user->setEnabled(false);
            } else {
                $user->setEnabled(true);
            }
            $userManager->updateUser($user);
        }

        return $this->redirectToRoute('user_show', array('id' => $user->getId()));
    }
    /**
     * Creates a form to changeStatus of employee entity.
     *
     * @param Employee $employee The Employee entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createChangeStatusForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_change_status', array('id' => $user->getId())))
            ->setMethod('POST')
            ->getForm();
    }

    public function permissionsAction(Request $request, User $user){
        $roles = $this->getParameter('roles');
        $form = $this->createForm('AppBundle\Form\UserPermissionsType', $user,['roles'=>$roles]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();


            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));

        }

        return $this->render('users/permissions.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

}