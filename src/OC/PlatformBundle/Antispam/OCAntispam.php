<?php
namespace  OC\PlatformBundle\Antispam;

class OCAntispam
{	
	
	private $mailer;
	private $locale;
	private $minlength;
	
	public function __construct(\Swift_Mailer $mailer, $locale, $minlength){
		$this->mailer=$mailer;
		$this->locale=$locale;
		$this->minlength= (int)$minlength;
	}
	/**
	 * verifie si le texte est un spam ou pas 
	 * exemple s'il la taille du texte est mois de 50 caractères
	 * 
	 * 
	 * @param string $text
	 * @return bool
	 */
	
	public function isSpam($text){
		return strlen($text) < $this->minlength;
	}
}
?>