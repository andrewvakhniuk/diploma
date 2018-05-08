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


    public function indexAction(Request $request)
    {
//        $em = $this->getDoctrine()->getManager();


        // return index homepage
        return $this->render('default/index.html.twig', []);
    }

}
