<?php
	namespace OC\PlatformBundle\Controller;
	
	use OC\PlatformBundle\Entity\Advert;
	use OC\PlatformBundle\Entity\Image;
	use OC\PlatformBundle\Entity\Application;
	use OC\PlatformBundle\Entity\AdvertSkills;
	
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    
    //partie use de formulaire
    use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\FormType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    //validation du formulaire
	use Symfony\Component\Validator\Constraints\NotBlankValidator;
	//injection de la classebuilder du formulaire de advert
	use OC\PlatformBundle\Form\AdvertType;
		   
		//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
			
	class AdvertController extends Controller{
		
		public function indexAction($page,Request $request){
						
				// Notre liste d'annonce en dur
				$em=$this->getDoctrine()->getEntityManager();
				$listAdverts=$em->getRepository('OC\PlatformBundle\Entity\Advert')
				->findBy(array('published'=>true));
				/*$listAdverts = array(
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
				);*/
				
			
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
			
			
			$em=$this->getDoctrine()->getEntityManager();
			
			$advert=$em->getRepository('OCPlatformBundle:Advert')->find($id);
			
			if(null===$advert){
				throw new NotFoundHttpException("aucun Advert ne correspond à $id");
			}
			
			$listskill=$em->getRepository("OCPlatformBundle:AdvertSkills")->findBy(array('advert'=>$advert));
			$listApplication=$em->getRepository('OCPlatformBundle:Application')
			->findBy(array('advert'=> $advert));
			
			//$listCategories=$advert->getCategories();
			return $this->render('OCPlatformBundle:Advert:view.html.twig',
					array(
							'advert'=> $advert,
							'listapplication' =>$listApplication,
							'listskill'=>$listskill
					));
			
		}
		
		public function addAction(Request $request){
			
			
			//utilisation de notre service Antispam
			//on récupère le service
			//$antispam= $this->container->get("oc_platform.Antispam");
			
			//je pars du principe que $text contient le texte d'un message quelconque
			/*$text="...";
			if($antispam->isSpam($text)){
				throw new \Exception("votre message a été detecté comme spam!");
			}*/
			//sinon le message n'est pas un spam et on continue
			
			//creation de l'objet advert
			$advert=new Advert();
			
			//deux fonctions de generer le formulaire soit:
			//$form=$this->get('form.factory')->create(AdvertType::class,$advert);
			//soit:
			$form=$this->createForm(AdvertType::class,$advert);
			
			
			//si c'est apr�s un ajout d'une annonce
			if ($request->isMethod('POST')){
				
				//on recupere les valeurs POST du formulaire
				$form->handleRequest($request);
				
				//verifications des valeurs du POST de la request transmise au formulaire
				if($form->isValid()){
					
					$image=new Image();
					$image->setUrl("http://www.catswhocode.com/blog/wp-content/uploads/2016/06/javascript.jpg");
					$image->setAlt("javascript");
					
					$advert->setImage($image);
					//appel du EntityManager
					$em=$this->getDoctrine()->getManager();
					
					$em->persist($advert);
					$em->persist($image);
					//on declenche l'enregistrement'
					$em->flush();
					
					//creation d'une flash info "notice" qui va etre affiche dans la page home
					$request->getSession()->getFlashBag()->add('notice','Annonce bien enregistrée');
					//redirection vers la page de home
					return $this->redirectToRoute("oc_platform_home");
				}
			}
			
			/*$advert->setTitle("Recherche développeur c++ ");
			$advert->setAuthor("Abi");
			$advert->setContent("nous recherchons un développeur c++");
			
			$image=new Image();
			$image->setUrl("https://upload.wikimedia.org/wikipedia/commons/thumb/d/da/BjarneStroustrup.jpg/220px-BjarneStroustrup.jpg");
			$image->setAlt("Bjarne Stroustrup, l'inventeur du C++");
			
			$advert->setImage($image);
			
			$app1=new Application();
			$app1->setAuthor("alain");
			$app1->setContent("je suis interessé par cet offre");
			$app1->setAdvert($advert);
			
			$app2=new Application();
			$app2->setAuthor("christophe");
			$app2->setContent("c'est un offre interessant, je compte deposer ma candidature");
			$app2->setAdvert($advert);
			
			
			//appel du EntityManager
			$em=$this->getDoctrine()->getManager();
			
			$em->persist($advert);
			//si on n'avait pas defiin le cascade={"persist"}
			//on devrait persister l'entité Image avec 
			//$em->persist($image)
			
			//on doit persister les applications car pas de cascade
			$em->persist($app1);
			$em->persist($app2);
			//on declenche l'enregistrement'
			$em->flush();
			//appel de la vue d'ajout d'une annonce
			*/
			
			$content=$this->get('templating')->render('OCPlatformBundle:Advert:add.html.twig',array(
						'form'=>$form->CreateView(),
					
			));
			return new Response($content);
		}
		
		public function editAction($id, Request $request){
				
			//ici on doit se connecter à la bdd pour recuperer les données
			$em=$this->getDoctrine()->getManager();
			$advert=$em->getRepository('OCPlatformBundle:Advert')->find($id);
			if(null===$advert){
				throw new NotFoundHttpException('advert inexistant correspondant au $id');
			}
			//construction du formulaire
			$form=$this->get('form.factory')->create(AdvertType::class,$advert);
			
			
			
			//test de la methode de la requete
			if($request->isMethod('POST')){
				
				$form->handleRequest($request);
				
				if($form->isValid()){
					$em->flush();
					//creation de'un flash info , qui va etre affich� dans la page home
					$request->getSession()->getFlashBag('notice',' modification bien enregistrée');
					//redirection vers la vue view de $id
					return $this->redirectToRoute('oc_platform_view',array('id'=>$id));
				}
			}
			
			
			
			/*
			//on recupère les categories concernant cet advert
			$listcategories=$em->getRepository('OCPlatformBundle:Category')->findAll();
			
			//lier les categories à l'advert 
			foreach ($listcategories as $category){
				$advert->addCategory($category);
			}
			
			//on recupere les competences (skills) possible
			$listskills=$em->getRepository('OCPlatformBundle:Skill')->findAll();
			
			foreach($listskills as $skill){
				$advertskill=new AdvertSkills();
				
				$advertskill->setLevel("débutant");
				$advertskill->setAdvert($advert);
				$advertskill->setSkill($skill);
				
				$em->persist($advertskill);
			}
			//on ne persiste pas l'advert qui est l'entité propriétaire
			//car il est récupéré depuis Doctrine
			// donc pas de $em->persist($advert)
			
			//enregistrer le changement
			$em->flush();
			*/
			//appel de la vue de edition on utilisant renderResponse()
			return $this->render('OCPlatformBundle:Advert:edit.html.twig',
					array(
							'form'=> $form->createView()
					));
			
		}
		
		public function deleteAction($id){
			
			$em=$this->getDoctrine()->getManager();
			$advert=$em->getRepository('OCPlatformBundle:Advert')->find($id);
			
			
			if(null===$advert){
				throw new NotFoundHttpException('advert inexistant correspondant au $id');
			}
			
			$listcategories=$advert->getCategories();
			
			//supprimer les categories de l'advert
			foreach ($listcategories as $category){
				$advert->removeCategory($category);
			}
			
			$listApplication=$em->getRepository('OCPlatformBundle:Application')
			->findBy(array('advert'=> $advert));
			
			foreach ($listApplication as $app){
				$em->remove($app);
			}
			
			//supprimer l'advert de advertskill
			$listadvertskills=$em->getRepository(AdvertSkills::class)->findBy(array('advert'=>$advert));
			
			foreach($listadvertskills as $advertskill){
				$em->remove($advertskill);
			}
			
			$em->remove($advert);
			// enregistrement les suppression
			
			$em->flush();
			
			$content=$this->get('templating')->render('OCPlatformBundle:Advert:delete.html.twig',
					array(
							'advert'=> $advert,
							'id'=>$id
					));
			return new Response($content);
		}
		
		public function menuAction($limit){
			//exemple de liste
			$em=$this->getDoctrine()->getEntityManager();
			$listAdverts=$em->getRepository('OC\PlatformBundle\Entity\Advert')
			->findBy(array('published'=>true),array('date'=>'desc'),$limit);
			/*$listAdverts=array(
					array('id'=>2,'title'=>'developpeur PHP'),
					array('id'=>3,'title'=>'webmaster'),
					array('id'=>4,'title'=>'consultant')
			);*/
			
			return $this->render('OCPlatformBundle:Advert:menu.html.twig',array(
					'listAdverts'=>$listAdverts
			));
		}
		
		
	}
?> 