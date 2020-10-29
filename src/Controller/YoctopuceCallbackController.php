<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use YAPI;
use YModule;

class YoctopuceCallbackController extends AbstractController
{
    /**
     * @Route("/yoctopuce/callback", name="yoctopuce_callback")
     */
    public function index(): Response
    {
        $error = "";
        if(YAPI::TestHub("callback", 10, $error) == YAPI::SUCCESS) {
            YAPI::RegisterHub("callback");
            $module = YModule::FirstModule();
            while (!is_null($module)) {
                if ($module->get_beacon() == YModule::BEACON_ON) {
                    $module->set_beacon(YModule::BEACON_OFF);
                } else {
                    $module->set_beacon(YModule::BEACON_ON);
                }
                $module = $module->nextModule();
            }
            return new Response("");
       }
        return $this->render('yoctopuce_callback/index.html.twig', [
            'controller_name' => 'YoctopuceCallbackController',
        ]);
    }
}
