<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;


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

        $params = $request->request->all();
        if (!isset($params['_csrf_token'])||!$this->isCsrfTokenValid('secret', $params['_csrf_token'])) {
            throw $this->createNotFoundException('Not Found');
        }

        try {
            $file = $request->files->get('file');

            $url = 'http://braillescore.ibspan.waw.pl/uploader.php?direction=';
            $header = array('Content-Type: multipart/form-data');
            $fields = array('uploaded_file' => curl_file_create($file->getRealPath()));

            if (isset($params['encodingChar'])) {
                $fields['charEncoding'] = $params['encodingChar'];
                $url .= '1';
            } else {
                $url .= '2';
            }

            $fields['MAX_FILE_SIZE'] = '10000000';

            //send file to brailleScore

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
            $response = new Response();

            $file_array = explode("\n\r", $file, 3);
            $header_array = explode("\n", $file_array[1]);
            foreach ($header_array as $header_value) {
                $header_pieces = explode(':', $header_value);
                if (count($header_pieces) == 2) {
                    $headers[$header_pieces[0]] = trim($header_pieces[1]);
                }
            }

            $response->headers->set('Content-type', $headers['Content-Type']);
            $response->headers->set('Content-Disposition', $headers['Content-Disposition']);
            $response->headers->set('Content-Description', $headers['Content-Description']);

            $response->setContent($file_array[2]);

        }catch (\Exception $exception){
            $this->addFlash('danger',$this->get('translator')->trans('invalid.file'));
            return $this->redirectToRoute('default_service');
        }
        return $response;
    }
}
