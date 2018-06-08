<?php
/**
 * Created by PhpStorm.
 * User: vahav
 * Date: 05/06/2018
 * Time: 06:54
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
 public function testAction(){
     return $this->render('test.html.twig');
 }
}