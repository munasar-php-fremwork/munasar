<?php
      namespace Caaqil\Controllers;
      use Slim\Views\Twig as View;
      use Caaqil\Models\Students;
      use Caaqil\Models\Dalab;
      use Caaqil\Models\Groups;
      use \DateTime;
      use Caaqil\Controllers\Controller as Controller;
      class HomeController extends Controller {
            

          public function index($request , $response) {
              return $this->view->render($response , 'home.html');
          }
  }
  