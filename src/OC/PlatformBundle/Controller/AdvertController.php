<?php
	namespace OC\PlatformBundle\Controller;
	
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
			
	class AdvertController extends Controller{
		
		public function indexAction($page,Request $request){
						
				// Notre liste d'annonce en dur
				$listAdverts = array(
						array(
								'title'   => 'Recherche développpeur Symfony',
								'id'      => 1,
								'author'  => 'Alexandre',
								'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla�',
								'date'    => new \DateTime()),
						array(
								'title'   => 'Mission de webmaster',
								'id'      => 2,
								'author'  => 'Hugo',
								'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla',
								'date'    => new \DateTime()),
						array(
								'title'   => 'Offre de stage webdesigner',
								'id'      => 3,
								'author'  => 'Mathieu',
								'content' => 'Nous proposons un poste pour webdesigner. Blabla',
								'date'    => new \DateTime())
				);
				
			
			//les numeros de pages sont >=1
			/*if($page<1){
				
				//on lance une exception de page inexisatante
				//throw new NotFoundHttpException('la page '.$page.' est inexistante') ;
				throw $this->createNotFoundException('la page '.$page.' est inexistante');
			}*/
			//$serverName=$request->server->get("SERVER_NAME");
			$content=$this->get('templating')->render('OCPlatformBundle:Advert:index.html.twig',
					array(
							'page'=> $page,
							
							'listAdverts'=>$listAdverts
					));
			return new Response($content);
		}
		
		public function viewAction($id){
			
			$advert=array(
					
					'title'=>'recherche developpeur symfony2',
					'id'      => $id,
					'author'  => 'Alexandre',
					'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla',
					'date'    => new \DateTime()
					
			);
			return $this->render('OCPlatformBundle:Advert:view.html.twig',
					array(
							'advert'=> $advert
					));
			
		}
		
		public function addAction(Request $request){
			
			//si c'est apr�s un ajout d'une annonce
			if ($request->isMethod('POST')){
				//creation d'une flash info "notice" qui va etre affiche dans la page home
				$request->getSession()->getFlashBag()->add('notice','Annonce bien enregistr�e');
				//redirection vers la page de home
				return $this->redirectToRoute("oc_platform_home");
			}
			
			//utilisation de notre service Antispam
			//on récupère le service
			$antispam= $this->container->get("oc_platform.Antispam");
			
			//je pars du principe que $text contient le texte d'un message quelconque
			$text="...";
			if($antispam->isSpam($text)){
				throw new \Exception("votre message a été detecté comme spam!");
			}
			//sinon le message n'est pas un spam et on continue
			
			//appel de la vue d'ajout d'une annonce
			$content=$this->get('templating')->render('OCPlatformBundle:Advert:add.html.twig');
			return new Response($content);
		}
		
		public function editAction($id, Request $request){
			
			//test de la methode de la requete
			if($request->isMethod('POST')){
				
				//creation de'un flash info , qui va etre affich� dans la page home
				$request->getSession()->getFlashBag('notice',' modification bien enregistr�e');
				//redirection vers la vue view de $id
				return $this->redirectToRoute('oc_platform_view',array('id'=>$id));
			}
			
			//ici on doit se connecter à la bdd pour recuperer les données
			//.........
			//pour l'instant on va forcer en dur les données
			
			$advert=array(
					
					'title'=>'recherche developpeur symfony2',
					'id'      => $id,
					'author'  => 'Alexandre',
					'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla',
					'date'    => new \DateTime()
					
			);
			
			//appel de la vue de edition on utilisant renderResponse()
			return $this->render('OCPlatformBundle:Advert:edit.html.twig',
					array(
							'advert'=> $advert
					));
			
		}
		
		public function deleteAction($id){
			
			$content=$this->get('templating')->render('OCPlatformBundle:Advert:delete.html.twig',
					array(
							'id'=> $id
					));
			return new Response($content);
		}
		
		public function menuAction($limit){
			//exemple de liste
			$listAdverts=array(
					array('id'=>2,'title'=>'developpeur PHP'),
					array('id'=>3,'title'=>'webmaster'),
					array('id'=>4,'title'=>'consultant')
			);
			
			return $this->render('OCPlatformBundle:Advert:menu.html.twig',array(
					'listAdverts'=>$listAdverts
			));
		}
		
		
	}
?> 