<?php

namespace Knp\Bundle\KnpBundlesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bundles dependency entity
 *
 * @ORM\Entity(repositoryClass="Knp\Bundle\KnpBundlesBundle\Repository\DependencyRepository")
 * @ORM\Table(name="dependency")
 */
class Dependency
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Depdendency value, for example "friendsofsymfony/user-bundle"
     *
     * @ORM\Column(type="string", length=127)
     */
    protected $value;

    /**
     * Keyword slug
     *
     * @ORM\Column(type="string", length=127)
     */
    protected $slug;

    public function getId()
    {
        return $this->id;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        $this->setSlug(preg_replace('/[^a-z0-9_\s-]/', '', preg_replace("/[\s_]/", "-", strtolower(trim($value)))));
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}
