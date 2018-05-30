<?php
/**
 * Created by PhpStorm.
 * User: vahav
 * Date: 27/05/2018
 * Time: 21:45
 */

namespace AppBundle\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    protected $_session;
    protected $_router;
    protected $_translator;

    public function __construct(SessionInterface $session, RouterInterface $router, TranslatorInterface $translator)
    {
        $this->_session = $session;
        $this->_router = $router;
        $this->_translator = $translator;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $this->_session->getFlashBag()->add('danger', $this->_translator->trans('access.denied'));

        if ($request->headers->get('referer')) {
            $route = $request->headers->get('referer');
        } else {
            $route = $this->_router->generate('homepage');
        }

        return new RedirectResponse($route);
    }
}