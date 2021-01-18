<?php

namespace App\Controller;

use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController // abstract = heritage
{
    /**
     * Une route est l'équivalent d'une nouvelle page web dans notre projet. Avec le composant annotations, on peut faire correspondre une URL avec une méthode d'un contrôleur.
     * Cette méthode va afficher la page souhaitée.
     * Cette fonction Route() prend au moins un paramètre : l'URL qui va être dans la barre d'adresse du navigateur
     *
     * Une méthode d'un contrôleur qui est liée à une route doit retourner un objet de la classe Response
     *
     * @Route("/test", name="test")
     */

    public function index(): Response
    {
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/TestController.php',
        // ]);
        // La méthode render permet d'afficher le contenu d'un fichier template. Le premier paramètre est le nom du fichier (le nom du fichier est donné à partir du dossier 'templates). le deuxième paramètre est un array dont les indices seront les noms des variables envoyées au template.

        return $this->render("base.html.twig");
    }

/**
 * @Route("/test/salutations", name="test_salutation")
 */

 public function salutation() {
    //  Exercice : le contenu de la balise h1 doit être "Salutations"
     return $this->render("test/salutations.html.twig");
 }

    /* Exercice : créer une nouvelle route "/test/calcul". Pour l'affichage, une nouvelle vue test/calcul.html.twig.
    title : calcul
    h1 : Résultat du calcul
    contenu : 5 x 3 = 15
    */

/**
 * route paramétrée : {a} veut dire que cette partie de l'URL pourra prendre n'importe quelle valeur. Pour pouvoir utiliser cette valeur, on doit l'indiquer comme paramètre de la méthode calcul()
 *
 * @Route("/test/calcul/{a}/{b}", name="test_calcul")
 */

public function calcul($a, $b) {
    //$a = 10;
    //$b = 2;
    // Exercice : Afficher aussi le résultat de a + b, a/b et a-b
    return $this->render("test/calcul.html.twig", [ "aa" => $a, "b" => $b ]);
}

/**
 * @Route("/test/affichage", name="test_affichage")
 */

 public function affichage() {
     $tableau = ["nom" => "Dupont", "prenom" => "Liam", "age" => 20, "ville" => "Paris"];
     $tableau2 = ["Bonjour", 5, true, 46, false];
     //dump($tableau);  // équivalent de var_dump
     //dump($tableau2);
     //dd($tableau);  // dump and die : affiche un var_dump et arrête le script
     return $this-> render("test/affichage.html.twig", [
        "tab" => $tableau,
        "tab2" => $tableau2
    ]);
 }

/**
 * @Route("/test/affichage/objet", name="test_affichage_objet")
 */

 public function affichageObjet() {
     $obj = new stdClass;
     $obj->nom = "Doe";
     $obj->prenom = "John";
     $obj->age = "30";
     $obj->ville = "Marseille";
     return $this->render("test/affichage.html.twig", ["tab" => $obj]);
 }

}