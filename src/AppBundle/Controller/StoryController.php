<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Story;
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

        $em = $this->getDoctrine()->getManager();
        $em->persist($story);
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
            ->findAllPublicStory();

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
        /*
        $cache = $this->get("doctrine_cache.providers.my_markdown_cache");
        $key = md5($title);

        if ($cache->contains($key)) {
            $title = $cache->fetch($key);
        } else {
            sleep(5);
            $title = $this->get("markdown.parser")
                ->transform($title);

            $cache->save($key,$title);
        }

        $title = $this->get("markdown.parser")
            ->transform($title);
        */

        return $this->render("story/show.html.twig", [
            "story" => $story,
        ]);
    }

    /**
     * @Route("/story/{storyName}/stories", name="story_show_stories")
     * @Method("GET")
     */
    public function getStoriesAction()
    {
        $stories = [
            ["id" => 1, "storyName" => "Life of Khoroshka", "avatarUri" => "/images/horoshkaStory.jpg",  "story" => "My life be live",  "date" => "Aug. 18, 2017"],
            ["id" => 2, "storyName" => "Philosophy",        "avatarUri" => "/images/horoshkaStory2.jpg", "story" => "some philosophy",  "date" => "Aug. 17, 2017"],
            ["id" => 3, "storyName" => "Tachka",            "avatarUri" => "/images/horoshkaStory3.jpg", "story" => "NICOVO POKATALIS", "date" => "Aug. 14, 2017"],
        ];

        $data = [
            "stories" => $stories,
        ];

        return new JsonResponse($data);
    }

}