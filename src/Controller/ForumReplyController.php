<?php
namespace App\Controller;

use App\Entity\ForumTopic;
use App\Entity\ForumReply;
use App\Form\ForumReplyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forum')]
class ForumReplyController extends AbstractController
{
    #[Route('/{id}', name: 'forum_show', methods: ['GET', 'POST'])]
    public function show(ForumTopic $topic, Request $request, EntityManagerInterface $em): Response
    {
        $reply = new ForumReply();
        $form = $this->createForm(ForumReplyType::class, $reply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reply->setTopic($topic);
            $reply->setAuthor($this->getUser());
            $reply->setCreatedAt(new \DateTimeImmutable());
            $em->persist($reply);
            $em->flush();

            return $this->redirectToRoute('forum_show', ['id' => $topic->getId()]);
        }

        return $this->render('forum/show.html.twig', [
            'topic' => $topic,
            'replies' => $topic->getReplies(),
            'form' => $form->createView(),
        ]);
    }
}
