<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin.index")
     */
    public function index(): Response
    {
        self::checkAdmin();

        require_once(ROOT . '/views/admin/index.php');

        return true;
    }
}
