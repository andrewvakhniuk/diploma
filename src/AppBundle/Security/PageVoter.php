<?php
/**
 * Created by PhpStorm.
 * User: vahav
 * Date: 27/05/2018
 * Time: 19:40
 */

namespace AppBundle\Security;


use AppBundle\Entity\Page;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PageVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';
    const CONTENT = 'content';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT,self::CONTENT))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Page) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Page $page */
        $page = $subject;

        /** @var User $user */
        $user = $token->getUser();

        if (!$user instanceof User && (!$page->getPublic() || !$page->getActive())) {
            // if not logged in and (not public or not active) then deny
            return false;
        }


        // ROLE_ADMIN can do anything! The power!
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return true;
        }

        if($attribute === self::CONTENT && $page->getPublic() && $page->getActive()){
            return true;
        }

        if (!$user instanceof User){
            return false;
        }


        switch ($attribute) {
            case self::CONTENT:
                return $this->canContent($page, $user);
            case self::VIEW:
                return $this->canView($page, $user);
            case self::EDIT:
                return $this->canEdit($page, $user);

        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canContent(Page $page, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($page, $user)) {
            return true;
        }

        if($user->hasRole('ROLE_ACCESS_TO_VIEW_ALL_PAGES') || $user->getAvailablePages()->contains($page)){
            return true;
        }


        return false;
    }

    private function canEdit(Page $page, User $user)
    {

        if($user->hasRole('ROLE_ACCESS_TO_EDIT_ALL_PAGES') || $user->getEditablePages()->contains($page)){
            return true;
        }

        return false;
    }

    private function canView(Page $page, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($page, $user)) {
            return true;
        }
        //if user can edit sub
        foreach ($page->getSubPages() as $subPage){
            if($user->getEditablePages()->contains($subPage)){
                return true;
            }
        }


        return false;
    }
}