<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp
 * Date: 10/12/2018
 * Time: 3:04 PM
 */

namespace App\Menu;


use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class Builder implements ContainerAwareInterface
{

//    use ContainerAwareTrait;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the container.
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $session = new Session();

        $menus = $session->get('menu');
        $request = $this->container->get('request_stack');

        $r = $request->getCurrentRequest();

        $template_name = $r->request->get('template_name');

        foreach ($menus as $k => $v) {
            if ($v['status'] === true) {
                if ($v['page_key'] === 'accueil')
                    $menu->addChild(
                        $v['page_name'],
                        [
                            'route' => 'homepage',
                            'routeParams' => [
                                'template_name' => $template_name,
                                'api_key' => $v['api_key']
                            ]
                        ]);
                else
                    $menu->addChild($v['page_name'],
                        [
                            'route' => 'page',
                            'routeParams' => [
                                'template_name' => $template_name,
                                'api_key' => $v['api_key'],
                                'page_name' => $v['page_name']
                            ]
                        ]);
            }

        }

        return $menu;
    }
}