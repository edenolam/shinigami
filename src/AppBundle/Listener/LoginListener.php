<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 15/11/2017
 * Time: 16:40
 */

namespace AppBundle\Listener;



use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\HttpUtils;

class LoginListener implements AuthenticationSuccessHandlerInterface
{


    private $router;
    private $token;
    private $httpUtils;

    public function __construct(RouterInterface $router, HttpUtils $httpUtils, TokenStorageInterface $token)
    {
        $this->router = $router;
        $this->httpUtils = $httpUtils;
        $this->token = $token;
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return $this->httpUtils->createRedirectResponse($request, $this->determineTargetUrl($token));
    }

    private function determineTargetUrl($token)
    {
        if(array_search("ROLE_CUSTOMER", $token->getUser()->getRoles()) !== false){
            return 'customer_panel';
        } elseif(array_search("ROLE_STAFF", $token->getUser()->getRoles()) !== false){
            return 'staff_search';
        } elseif (array_search("ROLE_SUPER_ADMIN", $token->getUser()->getRoles()) !== false) {
			return 'staff_search';
		} else {
            return 'homepage';
        }
    }
}