<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp
 * Date: 10/11/2018
 * Time: 11:14 AM
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;


/**
 * Class ComponentController
 * @package App\Controller
 *
 *
 */
class ComponentController extends  Controller
{

    /**
     * @param $template_name
     * @param $api_key
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function header($template_name, $api_key){

        $session =  new Session();

        $config = $session->get('config');
        $menu = $session->get('menu');

        $contact = '';
        foreach($menu as $k => $link){
            if($link['page_key'] === 'contact'){

            }
        }
        return $this->render($template_name.'/components/_header.html.twig',
            [
                'template_name' => $template_name,
                'api_key' => $api_key,
                'config' => $config,
                'menu' => $menu
            ]);
    }

    /**
     * @param $template_name
     * @param $api_key
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function footer($template_name, $api_key){
        return $this->render($template_name.'/components/_footer.html.twig');
    }

    /**
     * @param $template_name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchForm($template_name){
        return $this->render($template_name.'/components/_search_form.html.twig');
    }

}