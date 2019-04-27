<?php

namespace App\Controller;


use App\FlexSession\TypeProvider;
use FlexSession\TypeProvider\TypeProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @author Aleksandr Arofikin <sashaaro@gmail.com>
 *
 * @Route("/")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request, TypeProviderInterface $typeProvider)
    {
        $time = $request->getSession()->get('time');

        return $this->render('index.html.twig', [
            'time' => $time,
            'flexSessionType' => $typeProvider->provide()['type']
        ]);
    }

    /**
     * @Route("/update")
     */
    public function updateAction(Request $request)
    {
        $request->getSession()->set('time', date('H:i:s'));

        return $this->redirect('/');
    }

    /**
     * @Route("/change-flex-session-type/{type}", requirements={"type": "pdo|file"})
     */
    public function changeFlexSessionTypeAction($type, TypeProvider $typeProvider)
    {
        $typeProvider->changeType($type);

        return $this->redirect('/');
    }
}