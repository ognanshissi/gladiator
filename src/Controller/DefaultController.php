<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp
 * Date: 10/11/2018
 * Time: 11:07 AM
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 *
 * @Route("/{template_name}/{api_key}/")
 *
 */
class DefaultController extends Controller
{

    /**
     * @Route("", name="homepage")
     * @param $template_name
     * @param $api_key
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepage($template_name, $api_key): Response
    {
        $session = new Session();

        $page = '';
        $posts = $session->get('rubriques');

        foreach ($posts as $k => $v) {
            if ($k == 'accueil') {
                $page = $v;
            }

            // grab about page info

            // grab service page info

            // store them inside a variable
        }
        return $this->render($template_name . '/index.html.twig',
            [
                'page' => $page
            ]);
    }


    /**
     *
     * @Route("404", name="error404")
     *
     * @param $template_name
     * @param $api_key
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function error404($template_name, $api_key): Response
    {
        return $this->render($template_name . '/404.html.twig');
    }

    /**
     *
     * @Route("search", name="search")
     *
     * @param $template_name
     * @param $api_key
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function search($template_name, $api_key): Response
    {
        return $this->render($template_name . '/search.html.twig');
    }

    /**
     *
     * @Route("page/{page_name}", name="page")
     *
     * @param $template_name
     * @param $api_key
     * @param $page_name
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function page($template_name, $api_key, $page_name): Response
    {

        $session = new Session();

        $page = '';
        $posts = $session->get('rubriques');


        foreach ($posts as $k => $v) {
            if ($k == $page_name) {
                $page = $v;
            }
        }


        return $this->render($template_name . '/page.html.twig',
            [
                'page_name' => $page_name,
                'page' => $page
            ]);
    }


}