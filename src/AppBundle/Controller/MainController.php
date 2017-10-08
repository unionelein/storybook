<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 08.10.2017
 * Time: 19:57
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class MainController extends Controller
{
    public function homepageAction()
    {
        return $this->render("main/homepage.html.twig");
    }
}