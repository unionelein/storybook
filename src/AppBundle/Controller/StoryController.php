<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Story;
use AppBundle\Entity\StoryPart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StoryController extends Controller
{
    /**
     * @Route("/story/new")
     *
     * @return Response
     */
    public function newAction()
    {
        $story = new Story();
        $story->setStory("I am kind ". rand(1,100));
        $story->setGenre("NICEVIY");
        $story->setTitle("About me in last month");
        $story->setLikes(5);

        $storyPart = new StoryPart();
        $storyPart->setTitle("My second day of creating storybook");
        $storyPart->setContent("LALALALALLALALALALAL");
        $storyPart->setCreatedAt(new \DateTime("-1 month"));
        $storyPart->setImgFileName("horoshkaStory3.jpg");
        $storyPart->setStory($story);

        $em = $this->getDoctrine()->getManager();
        $em->persist($story);
        $em->persist($storyPart);
        $em->flush();

        return new Response("<html><body>Story was created!</body></html>");
    }

    /**
     * @Route("/story")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $stories = $em->getRepository("AppBundle:Story")
            ->findAllPublicStoryByLastStory();

        return $this->render("story/list.html.twig", [
            "stories" => $stories,
        ]);
    }

    /**
     * @Route("/story/{storyName}", name="show_story")
     */
    public function showAction($storyName)
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository("AppBundle:Story")
            ->findOneBy(["title" => $storyName]);

        if (!$story) {
            throw $this->createNotFoundException("No story found");
        }

        $last3Months = $em->getRepository("AppBundle:StoryPart")
            ->findAllStoryPartsInLast3Months($story);

        return $this->render("story/show.html.twig", [
            "story" => $story,
            "countLast3Months" => count($last3Months),
        ]);
    }

    /**
     * @Route("/story/{title}/stories", name="story_show_stories")
     * @Method("GET")
     */
    public function getStoriesAction(Story $story)
    {
        $stories = [];

        foreach ($story->getStoryParts() as $storyPart) {
            $stories[] = [
                "id"        => $storyPart->getId(),
                "storyName" => $storyPart->getTitle(),
                "avatarUri" => "/images/".$storyPart->getImgFileName(),
                "story"     => $storyPart->getContent(),
                "date"      => $storyPart->getCreatedAt()->format('M d, Y'),
            ];
        }

        $data = [
            "stories" => $stories,
        ];

        return new JsonResponse($data);
    }

}