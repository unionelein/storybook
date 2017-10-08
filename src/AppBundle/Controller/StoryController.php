<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StoryController extends Controller
{
    /**
     * @Route("/story/{storyName}")
     */
    public function showAction($storyName)
    {
        $title = "This story about the last year of my *life*.";
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


        return $this->render("story/show.html.twig", [
            "name" => $storyName,
            "title" => $title,
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