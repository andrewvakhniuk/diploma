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
}
