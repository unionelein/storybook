<?php
/**
 * Created by PhpStorm.
 * User: asuvorau
 * Date: 10/2/17
 * Time: 1:58 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class StoryController extends Controller
{
    /**
     * @Route("/story/{storyName}")
     */
    public function showAction($storyName)
    {
        $stories =[
            "My cool story about a journey",
            "Story about how i go to zoo",
            "Happy story when i was young",
        ];

        return $this->render("story/show.html.twig", [
            "name" => $storyName,
            "stories" => $stories,
        ]);

    }
}