<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Page;
use AppBundle\Entity\SubPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * Subpage controller.
 *
 */
class SubPageController extends Controller
{
    /**
     * Lists all subPage entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $subPages = $em->getRepository('AppBundle:SubPage')->findAll();

        return $this->render('subpage/index.html.twig', array(
            'subPages' => $subPages,
        ));
    }

    /**
     * @Entity("page", expr="repository.find(page_id)")*
     * @param Request $request
     * @param Page $page
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Page $page, Request $request)
    {
        $subPage = new Page();
        $form = $this->createForm('AppBundle\Form\PageType', $subPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subPage->setMain(false);
            $subPage->setMainPage($page);

            $user = $this->getUser();
            $user->addAvailablePage($subPage);
            $user->addEditablePage($subPage);

            $em = $this->getDoctrine()->getManager();
            $em->persist($subPage);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('page_show', array('id' => $page->getId()));
        }

        return $this->render('page/new.html.twig', array(
            'subPage' => $subPage,
            'page'=>$page,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a subPage entity.
     *
     */
    public function showAction(SubPage $subPage)
    {
        $deleteForm = $this->createDeleteForm($subPage);

        return $this->render('subpage/show.html.twig', array(
            'subPage' => $subPage,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing subPage entity.
     *
     */
    public function editAction(Request $request, SubPage $subPage)
    {
        $deleteForm = $this->createDeleteForm($subPage);
        $editForm = $this->createForm('AppBundle\Form\SubPageType', $subPage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('subpage_edit', array('id' => $subPage->getId()));
        }

        return $this->render('subpage/edit.html.twig', array(
            'subPage' => $subPage,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a subPage entity.
     *
     */
    public function deleteAction(Request $request, SubPage $subPage)
    {
        $form = $this->createDeleteForm($subPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($subPage);
            $em->flush();
        }

        return $this->redirectToRoute('subpage_index');
    }

    /**
     * Creates a form to delete a subPage entity.
     *
     * @param SubPage $subPage The subPage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SubPage $subPage)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('subpage_delete', array('id' => $subPage->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
