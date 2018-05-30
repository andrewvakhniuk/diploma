<?php
/**
 * Created by PhpStorm.
 * User: vahav
 * Date: 07/04/2018
 * Time: 23:25
 */

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

class User extends BaseUser
{
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->availablePages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->editablePages = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $availablePages;


    /**
     * Add availablePage
     *
     * @param \AppBundle\Entity\Page $availablePage
     *
     * @return User
     */
    public function addAvailablePage(\AppBundle\Entity\Page $availablePage)
    {
        $this->availablePages[] = $availablePage;

        return $this;
    }

    /**
     * Remove availablePage
     *
     * @param \AppBundle\Entity\Page $availablePage
     */
    public function removeAvailablePage(\AppBundle\Entity\Page $availablePage)
    {
        $this->availablePages->removeElement($availablePage);
    }

    /**
     * Get availablePages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAvailablePages()
    {
        return $this->availablePages;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $editablePages;


    /**
     * Add editablePage
     *
     * @param \AppBundle\Entity\Page $editablePage
     *
     * @return User
     */
    public function addEditablePage(\AppBundle\Entity\Page $editablePage)
    {
        $this->editablePages[] = $editablePage;

        return $this;
    }

    /**
     * Remove editablePage
     *
     * @param \AppBundle\Entity\Page $editablePage
     */
    public function removeEditablePage(\AppBundle\Entity\Page $editablePage)
    {
        $this->editablePages->removeElement($editablePage);
    }

    /**
     * Get editablePages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEditablePages()
    {
        return $this->editablePages;
    }
}
