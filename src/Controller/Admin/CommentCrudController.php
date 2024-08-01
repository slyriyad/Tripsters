<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Commentaires')
            ->setEntityLabelInSingular('Commentaire');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('author.email', 'Email de l\'auteur'),
            TextField::new('activity.tripActivities[0].trip.name', 'Nom du voyage')->formatValue(function ($value, $entity) {

                $tripActivity = $entity->getActivity()->getTripActivities()->first();
                return $tripActivity ? $tripActivity->getTrip()->getName() : 'N/A';
            }),
        ];
    }
    
}
