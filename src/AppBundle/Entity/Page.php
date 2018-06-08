<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Page
 */
class Page
{
    /**
     * @Gedmo\Locale
     */
    private $locale;

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function __construct()
    {
        $this->active = true;
        $this->public = true;
        $this->main = true;
        $this->subPages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var int
     */
    private $id;

    /**
     * @Gedmo\Translatable
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $active;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Page
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Page
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @Gedmo\Translatable
     * @var string
     */
    private $content;


    /**
     * Set content
     *
     * @param string $content
     *
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $subPages;

    /**
     * Add subPage
     *
     * @param \AppBundle\Entity\SubPage $subPage
     *
     * @return Page
     */
    public function addSubPage(\AppBundle\Entity\SubPage $subPage)
    {
        $this->subPages[] = $subPage;

        return $this;
    }

    /**
     * Remove subPage
     *
     * @param \AppBundle\Entity\SubPage $subPage
     */
    public function removeSubPage(\AppBundle\Entity\SubPage $subPage)
    {
        $this->subPages->removeElement($subPage);
    }

    /**
     * Get subPages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubPages()
    {
        return $this->subPages;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->name;
    }

    /**
     * @var boolean
     */
    private $main;

    /**
     * @var \AppBundle\Entity\Page
     */
    private $mainPage;


    /**
     * Set main
     *
     * @param boolean $main
     *
     * @return Page
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return boolean
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set mainPage
     *
     * @param \AppBundle\Entity\Page $mainPage
     *
     * @return Page
     */
    public function setMainPage(\AppBundle\Entity\Page $mainPage = null)
    {
        $this->mainPage = $mainPage;

        return $this;
    }

    /**
     * Get mainPage
     *
     * @return \AppBundle\Entity\Page
     */
    public function getMainPage()
    {
        return $this->mainPage;
    }

    /**
     * @var boolean
     */
    private $public;


    /**
     * Set public
     *
     * @param boolean $public
     *
     * @return Page
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }
}
