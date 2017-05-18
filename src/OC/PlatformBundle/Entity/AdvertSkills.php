<?php

namespace OC\PlatformBundle\Entity;
use OC\PlatformBundle\Entity\Skill;
use OC\PlatformBundle\Entity\Advert;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdvertSkills
 *
 * @ORM\Table(name="oc_advert_skills")
 * @ORM\Entity(repositoryClass="OC\PlatformBundle\Repository\AdvertSkillsRepository")
 */
class AdvertSkills
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=255)
     */
    private $level;

	/**
	 * 
	 * @var object
	 * 
	 * @ORM\ManyToOne(targetEntity="OC\PlatformBundle\Entity\Advert")
	 * @ORM\JoinColumn(nullable=false)
	 * 
	 */
    private $advert;
	
	/**
	 * @var object
	 * 
	 * @ORM\ManyToOne(targetEntity="OC\PlatformBundle\Entity\Skill")
	 * @ORM\JoinColumn(nullable=false)
	 * 
	 */
    private $skill;
    
    
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
     * Set level
     *
     * @param string $level
     *
     * @return AdvertSkills
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set advert
     *
     * @param \OC\PlatformBundle\Entity\Advert $advert
     *
     * @return AdvertSkills
     */
    public function setAdvert(Advert $advert)
    {
        $this->advert = $advert;

        return $this;
    }

    /**
     * Get advert
     *
     * @return \OC\PlatformBundle\Entity\Advert
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * Set skill
     *
     * @param \OC\PlatformBundle\Entity\Skill $skill
     *
     * @return AdvertSkills
     */
    public function setSkill(Skill $skill)
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * Get skill
     *
     * @return \OC\PlatformBundle\Entity\Skill
     */
    public function getSkill()
    {
        return $this->skill;
    }
}
