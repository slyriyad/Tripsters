<?php
namespace App\Controller;

use App\Entity\ForumTopic;
use App\Form\ForumTopicType;
use App\Repository\ForumTopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/forum')]
class ForumController extends AbstractController
{
    #[Route('/', name: 'forum_index')]
    public function index(ForumTopicRepository $forumTopicRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer la requête pour les sujets du forum
        $query = $forumTopicRepository->createQueryBuilder('t')->getQuery();

        // Utilisation du paginator pour paginer les résultats
        $pagination = $paginator->paginate(
            $query, // La requête (ne pas exécuter avec getResult())
            $request->query->getInt('page', 1), // Page actuelle
            10 // Limite d'éléments par page
        );

        // Transmettre la variable pagination à la vue Twig
        return $this->render('forum/index.html.twig', [
            'pagination' => $pagination, // Variable pagination transmise à la vue
        ]);
    }


    #[Route('/new', name: 'forum_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $topic = new ForumTopic();
        $form = $this->createForm(ForumTopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $topic->setAuthor($this->getUser());
            $topic->setCreatedAt(new \DateTimeImmutable());
            $em->persist($topic);
            $em->flush();

            return $this->redirectToRoute('forum_index');
        }

        return $this->render('forum/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
