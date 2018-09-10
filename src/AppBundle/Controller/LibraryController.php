<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Library;
use AppBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * Library controller.
 *
 */
class LibraryController extends Controller
{
    /**
     * Lists all library entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $libraries = $em->getRepository('AppBundle:Library')->findAll();

        return $this->render('library/index.html.twig', array(
            'libraries' => $libraries,
        ));
    }

    /**
     * Creates a new library entity.
     *
     */
    public function newAction(Request $request)
    {
        $fileUploader = $this->get('app.service.file_uploader');

        $library = new Library();
        $form = $this->createForm('AppBundle\Form\LibraryType', $library);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /**@var UploadedFile $file*/

            $file = $request->files->get('appbundle_library')['file'];


            $translator = $this->get('translator');

            if ($file !== null) {
                $allowedTypes =['ppt','xls','doc','pptx','xlsx','docx','pdf','txt','xml','bs','mid','wav','mp3','mpeg','mp4','aac','webm','ogg','flac'];

//                $ext = $file->guessExtension();
                $extension = $file->getClientOriginalExtension();

                if ($extension === null ) {
                    $form->get('file')->addError(new FormError($translator->trans('file.can.not.be.empty')));
                }
                if (!in_array($extension,$allowedTypes,true)) {
                    $form->get('file')->addError(new FormError($translator->trans('supported.extensions')." : " .implode(", ",$allowedTypes). ' !!!'));
                }

                if ($form->isValid()) {
                    $library->setFile($fileUploader->upload($file));

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($library);
                    $em->flush();

                    return $this->redirectToRoute('library_show', array('id' => $library->getId()));
                }
            } else {
                $form->get('file')->addError(new FormError($translator->trans('file.can.not.be.empty')));

            }
        }
        return $this->render('library/new.html.twig', array(
            'library' => $library,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a library entity.
     *
     */
    public function showAction(Library $library)
    {
        $array = explode('.', $library->getFile());
        $extension = end($array);
        unset($array);


        return $this->render('library/show.html.twig', array(
            'library' => $library,
            'extension'=> $extension,
        ));
    }

    /**
     * Displays a form to edit an existing library entity.
     *
     */
    public function editAction(Request $request, Library $library)
    {
        $deleteForm = $this->createDeleteForm($library);
        $editForm = $this->createForm('AppBundle\Form\LibraryType', $library);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('library_index');
        }

        return $this->render('library/edit.html.twig', array(
            'library' => $library,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a library entity.
     *
     */
    public function deleteAction(Request $request, Library $library)
    {
        $form = $this->createDeleteForm($library);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($library);
            $em->flush();
        }

        return $this->redirectToRoute('library_index');
    }

    /**
     * Creates a form to delete a library entity.
     *
     * @param Library $library The library entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Library $library)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('library_delete', array('id' => $library->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
