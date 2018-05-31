<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ordering;
use AppBundle\Entity\Visit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class DefaultController extends Controller
{

    public function navbar()
    {

        $pages = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page')->findBy(['main' => true]);

//        dump($pages);die;
        return $this->render('navbar.html.twig', [
            'pages' => $pages
        ]);
    }

    // for admin priviligues / to show or not to show menu depends on roles
    public function navbarConfigurations()
    {

        $security = $this->get('security.authorization_checker');

        $hasAccess = $security->isGranted('IS_AUTHENTICATED_FULLY');

        if ($hasAccess) {
            $pages = $this->getUser()->getEditablePages();
            $isGrantedEditAllPages = $security->isGranted('ROLE_ACCESS_TO_EDIT_ALL_PAGES');
            $isGrantedAddPage = $security->isGranted('ROLE_ADD_PAGE');

            $hasAccess = count($pages) || $isGrantedEditAllPages || $isGrantedAddPage ;
        }
        return $this->render('navbarConfigurations.html.twig', [
            'hasPages' => $hasAccess,
        ]);
    }

    public function indexAction(Request $request)
    {
//        $em = $this->getDoctrine()->getManager();

        // return index homepage
        return $this->render('default/index.html.twig', []);
    }

}
