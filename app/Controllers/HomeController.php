<?php
      namespace Caaqil\Controllers;
      use Slim\Views\Twig as View;
      use Munasar\Controllers\Controller as Controller;
      class HomeController extends Controller {
            

          public function index($request , $response) {
              return $this->view->render($response , 'home.html');
           }
    }

  