<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * Page / Action : Accueil
     */
    public function index()
    {
        #récupérer les derniers article dans la BDD
        # récupérer les 6 derniers articles de la BDD par odre décroissant
        /* ->getRepository(xxx::class) : l'entité que je souhaite récupérer les données.
         * ->findBy() : recup les données selon +ieurs critéres
         * ->findOneBy() : recup un enregistrement selon +ieurs critéres
         * ->findAll() : recup toutes les données de la table
         * ->find(id) : recup une donnée via son ID
         */
        $posts = $this->getDoctrine() #créer une variable = getDoctrine, le fichier qui va récup en bdd
        ->getRepository(Post::class)
            ->findBy([], ['id' => 'DESC'], 6);

        # Transmettre à la vue
        return $this->render('default/index.html.twig', ['posts'=>$posts]);
    }

    /**
     * Page / Action : Contact
     */
    public function contact()
    {
        return $this->render('default/contact.html.twig');
    }

    /**
     * Page / Action : Categorie
     * Permet d'afficher les articles d'une catégorie
     * @Route("/{alias}", name="default_category", methods={"GET"})
     */
    public function category($alias)
    {
        #Récupération de la categorie via son alias dans l'url
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['alias' =>$alias]);
        /*
         * Grace à la relation entre Post et Category
         * (OneToMany), je suis en mesure de récupérer
         * les articles de la catégorie.
         */
        $posts = $category->getPost();
        return $this->render('default/category.html.twig',['posts'=> $posts]);

    }


    /**
     * Page / Action : Article
     * Permet d'afficher un article du site
     * @Route("/{category}/{alias}_{id}.html", name="default_article", methods={"GET"})
     */
    public function post($id, $category, $alias)
    {
        $post =$this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);
        # URL : https://localhost:8000/politique/couvre-feu-quand-la-situation-sanitaire-s-ameliorera-t-elle_14155614.html
        $this->addFlash(
            'notice',
            'Votre article a été enregistré!'
        );
        return $this->render('default/post.html.twig', ['post'=>$post]);
    }


}