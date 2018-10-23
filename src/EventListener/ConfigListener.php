<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp
 * Date: 10/11/2018
 * Time: 12:19 PM
 */

namespace App\EventListener;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use \Unirest;

/**
 */
class ConfigListener
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var object
     */
    private $route;

    /**
     * ConfigListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->route = $this->container->get('router');
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $res = new Response();
        $route = $event->getRequest()->attributes->get('_route');

        if ($event->isMasterRequest()){
            $session = new Session();
            $headers = array('Accept' => 'application/json');
            $template_name = $event->getRequest()->get('template_name');
            $api_key = $event->getRequest()->get('api_key');
//        api/vitrine/tdCqnoI-YIjoNXbTTDlKeqPnaMFbQDhpZLFcgprSrXm
            Unirest\Request::verifyPeer(false); // Disables SSL cert validation
            $response = Unirest\Request::get('https://hellomonweb.net/api/vitrine/tdCqnoI-YIjoNXbTTDlKeqPnaMFbQDhpZLFcgprSrXm/all-details', $headers);

            if ($response->code === 200 ){
                $response = json_decode($response->raw_body, true);

                $session->remove('rubriques');
                $session->remove('rubrique');
                $session->remove('data');
                $session->remove('menu');

                if($response['success'] == false){
                    $session->set('error', ['success' => 'maybe false']);
//                    $event->setResponse(new RedirectResponse($this->route->generate('error404', ['template_name' => $template_name, 'api_key' => $api_key])));
                }else{
                    $session->set('rubriques', $response['rubriques']);
                    $session->set('config', $response['config']);

                    // Menu
                    $menu = [];
                    foreach($response['rubriques'] as $k => $v){
                        if ($k != 'testimonies' && $k != 'reseaux_sociaux' && $k != 'contact'){
                            if ($v['category'] == 'page' && $v['status'] == true)
                                array_push($menu, ['page_name' => ucfirst($v['name']), 'page_key' => $k, 'api_key' => $v['secret_key'], 'children' => $v['children'], 'origin' => $v['origin'], 'status' => $v['status']]);
                        }
                    }

                    // look for contact and insert it in the menu array
                    foreach ($response['rubriques'] as $k => $v){
                        if($k == 'contact' && $v['status'] == true){
                            array_push($menu, ['page_name' => ucfirst($v['name']), 'page_key' => $k, 'api_key' => $v['secret_key'], 'children' => $v['children'], 'origin' => $v['origin'], 'status' => $v['status']]);
                        }
                    }

                    $session->set('menu', $menu);
                }
            }else{
                $session->set('error', ['success' => 'definitely success is false']);
//                $event->setResponse(new RedirectResponse($this->route->generate('error404', ['template_name' => $template_name, 'api_key' => $api_key]), 302));
            }

        }


    }

}
