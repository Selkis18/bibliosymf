<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LivreRepository;
use App\Form\LivreType;

/**
 * @Route("/admin")
 */
class LivreController extends AbstractController
{
    /**
     * @Route("/livre", name="livre")
     *
     * Pour interroger une table de la BDD (=requête SELECT) on va utiliser la classe Repository correspondante (donc pour la table "livre" on utilise "livreRepository").
     */
    public function index(LivreRepository $livreRepository): Response
    {
        $liste_livres = $livreRepository->findAll();
        //dd($liste_livres);
        return $this->render('livre/index.html.twig', [
            'livres' => $liste_livres,
        ]);
    }

    /**
     * @Route("/livre/ajouter", name="livre_ajouter")
     */
    public function nouveau(Request $request, EntityManagerInterface $em){
        $livre = new Livre;
        $formLivre = $this->createForm(LivreType::class, $livre);
        $formLivre->handleRequest($request);
        if( $formLivre->isSubmitted() && $formLivre->isValid() ){
            if( $fichier = $formLivre->get("couverture")->getData() ){
                $destination = $this->getParameter("dossier_images");
                $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $nomFichier);
                $nouveauNom .= "_" . uniqid() . "." . $fichier->guessExtension();
                /* le fichier uploadé est enregistré dans un dossier temporaire. On va le
                    déplacer vers le dossier images avec le nouveau nom de fichier */
                $fichier->move($destination, $nouveauNom);
                $livre->setCouverture($nouveauNom);
            }
            $em->persist($livre);
            $em->flush();
            $this->addFlash("success", "Le nouveau livre a bien été ajouté");
            return $this->redirectToRoute("livre");
        }
        return $this->render("livre/ajouter.html.twig", ["formLivre" => $formLivre->createView()]);
    }

      /**
     * @Route("/livre/{id}", name="livre_detail", methods={"GET"}, requirements={"id"="\d+"})
     */

     public function show(Livre $livre): Response {
         return $this->render('livre/detail.html.twig',[
            'livre' => $livre,
         ]);
     }


    /**
     * @Route("/livre/modifier{id}", name="livre_modifier")
     */
    public function maj(EntityManagerInterface $em, Request $request, LivreRepository $lr, $id) {
        $livre = $lr->find($id);
        $formLivre = $this->createForm(LivreType::class, $livre);
        $formLivre->handleRequest($request);
        if( $formLivre->isSubmitted() && $formLivre->isValid() ){
            if( $fichier = $formLivre->get("couverture")->getData() ){
                $destination = $this->getParameter("dossier_images");
                $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $nomFichier);
                $nouveauNom .= "_" . uniqid() . "." . $fichier->guessExtension();
                $fichier->move($destination, $nouveauNom);
                $livre->setCouverture($nouveauNom);
            }
            $em->persist($livre);
            $em->flush();
            $this->addFlash("success", "Le nouveau livre a bien été modifié");
            return $this->redirectToRoute("livre");
        }
        return $this->render("livre/modifier.html.twig", ["formLivre" => $formLivre->createView()]);
    }


    /**
     * @Route("livre/ajouter", name="livre_ajouter_v1")
     */
    public function ajouter(Request $request, EntityManagerInterface $em){
        /* La classe Request  a des propriétés qui correspondent à toutes les variables superglobales de PHP ex : $_SERVER, $_POST, $_GET, $_COOKIE, $_SESSION, $_FILES.
        Pour utiliser certaines classes (nommée des services), on va utiliser l'injection de dépendance : on va placer un objet de cette classe dans les paramètre d'une méthode, et l'objet sera instancié automatique (par ex : l'objet de la classe Request contiendra toutes les valeurs des variables surperglobales)

        Pour récupérer le contenu de $_POST on utilise la propriété 'request' de cet pbjet.
        Pour récupérer le contenu de $_GET on utilise la propriété 'query' de cet objet.

        La classe entityManagerInterface va nous servir à modifier (enregistrer, mettre à jour, supprimer) des données dans les tables de la BDD.
        */

        if($request->request->has("titre")) {
            $titre = $request->request->get("titre");
        }
        if ($request->request->has("auteur")) {
            $auteur = $request->request->get("auteur");
        }

        if(!empty($titre) && !empty($auteur)) {
            $nouveauLivre = new Livre;
            //dump($nouveauLivre);
            $nouveauLivre->setTitre($titre);
            $nouveauLivre->setAuteur($auteur);
            //dd($nouveauLivre);
            $em->persist($nouveauLivre); // La méthode persist() prépare la requete "insert into" et la met en attente.
            $em->flush(); // La méthode "flush" execute les requêtes en attente et donc modifie la BDD.
            $this->addFlash("success", "Le nouveau livre a bien été enregistré"); // La méthode addFlash() permet d'enregistrer dans la session, un message à afficher. Le 1er paramètre est le type du message (par exemple : succes, danger, warning...) le 2eme paramètre est le message à afficher. Ces mesages resteront dans la session tant qu'ils n'ont pas été affichés.
            return $this->redirectToRoute("livre");
        }
        //dump($request);
        return $this->render('livre/formulaire.html.twig');
    }

    /**
     * @Route("/livre/modifier/{id}", name="livre_modifier_v1")
     */
    public function modifier(EntityManagerInterface $em, Request $request, LivreRepository $livreRepository, $id) {
        // la méthode find() récupère le livre dont l'identifiant est passé en paramètre. $livreAmodifier sera donc un objet de la classe Entity\Livre.
        $livreAmodifier = $livreRepository->find($id);
        if($request->isMethod("POST")) {
            $titre = $request->request->get("titre");
            $auteur = $request->request->get("auteur");
            if(!empty($titre) && !empty($auteur)) {
                $livreAmodifier->setTitre($titre);
                $livreAmodifier->setAuteur($auteur);
                $em->flush(); // Tous les objets de la classe entity qui ont un id non null vobt permettre à l'EntityManager d'éxecuter une requête UPDATE pour mettre à jour la BDD selon les modifications des propriétés de ces objets quand on va lancer la méthode 'flush de l'entityManager.
                $this->addFlash("success", "Le livre n°$id a bien été modifié");
                return $this->redirectToRoute("livre");
            } else {
                $this->addFlash("danger", "Le titre et l'auteur ne peuvent pas être vide");
            }
        }
        //dump($livreAmodifier);
        return $this->render("livre/formulaire.html.twig", ["livre"=>$livreAmodifier]);
    }


    /**
     * @Route("/livre/supprimer/{id}", name="livre_supprimer")
     */

     public function supprimer(EntityManagerInterface $em, Request $request, LivreRepository $livreRepository, $id) {
        $livreAsupprimer = $livreRepository->find($id);
        if($request->isMethod("POST")) {
            $em ->remove($livreAsupprimer);  // La méthode remove() prépare la requête DELETE et la met en attente
            $em ->flush();
            $this->addFlash("success", "Le livre a bien été supprimé");
            return $this->redirectToRoute("livre");
        }
        return $this->render("livre/supprimer.html.twig", ["livre"=>$livreAsupprimer]);
     }

}
