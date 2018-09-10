<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navbar()
    {

        $pages = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page')->findBy(['main' => true]);

//        dump($pages);die;
        return $this->render('navbar.html.twig', [
            'pages' => $pages
        ]);
    }

    // for admin priviligues / to show or not to show menu depends on roles

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {

        return $this->render('default/index.html.twig', []);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function serviceAction(){
        return $this->render('default/service.html.twig', []);
    }

    public function serviceConvertAction(Request $request){
        /**@var  UploadedFile $file */

        $file = $request->files->get('file');
//        $uploader = $this->get('app.service.file_uploader');
//        /**@var $uploader \AppBundle\Service\FileUploader*/
//
//        $name = $uploader->upload($file);
////        $fullPath = realpath($uploader->getTargetDir()).$name;
//        $fullPath = 'http://braillescore.inplay.com.ua/uploads/files/'.$name;
//        dump($fullPath);die;
        $url = 'http://braillescore.ibspan.waw.pl/uploader.php?direction=1';
        $header = array('Content-Type: multipart/form-data');
        $fields = array('uploaded_file' => '@' . $file->getRealPath());
//        $fields['charEncoding']='pl';
        $fields['MAX_FILE_SIZE'] = '10000000';


        $resource = curl_init();

        curl_setopt($resource, CURLOPT_URL, $url);
        curl_setopt($resource, CURLOPT_HTTPHEADER, $header);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($resource, CURLOPT_POST, 1);
        curl_setopt($resource, CURLOPT_POSTFIELDS, $fields);

        curl_setopt($resource, CURLOPT_HEADER, 1);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($resource, CURLOPT_BINARYTRANSFER, 1);

        $file = curl_exec($resource);

        curl_close($resource);

        dump($file);die;
    }
}
