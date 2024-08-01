<?php

namespace App\Controller\Admin;

use App\Entity\Trip;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Expense;
use App\Entity\Activity;
use App\Entity\CategoryExpense;
use App\Entity\CategoryActivity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tripsters - Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::subMenu('Utilisateur', 'fas fa-user')
            ->setSubItems([
                MenuItem::linkToCrud("Liste des utlisateurs", 'fas fa-list', User::class),
        ]);
        yield MenuItem::subMenu('Activité', 'fas fa-futbol')
            ->setSubItems([
                MenuItem::linkToCrud("Liste des activités", 'fas fa-list', Activity::class),
                MenuItem::linkToCrud("Catégories des activités", 'fas fa-icons', CategoryActivity::class),
                MenuItem::linkToCrud("Commentaires", 'fas fa-comments', Comment::class),
        ]);
        yield MenuItem::subMenu('Dépense', 'fas fa-sack-dollar')
            ->setSubItems([
                MenuItem::linkToCrud("Liste des dépenses", 'fas fa-list', Expense::class),
                MenuItem::linkToCrud("Catégories des depenses", 'fas fa-icons', CategoryExpense::class),
            ]);
        yield MenuItem::subMenu('Voyage', 'fas fa-plane')
            ->setSubItems([
                MenuItem::linkToCrud("Liste des voyages", 'fas fa-list', User::class),
        ]);
        
    }
}
