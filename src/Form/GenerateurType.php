<?php

namespace App\Form;

use App\Entity\Generateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class GenerateurType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomAddOn', TextType::class, [
                'label' => 'Nom de l\'Add-On : ',
            ])
            ->add('descriptionAddOn', TextareaType::class, [
                'label' => 'Description de l\'Add-On : ',

            ])
            ->add('identifiantAddOn', TextType::class, [
                'label' => 'L\'identifiant de l\'Add-On : ',

            ])
            ->add('nomDeveloppeur', TextType::class, [
                'label' => 'Nom du developpeur : ',

            ])
            ->add('dateDeCreation', DateType::class, [
                'label' => 'Date de création : ',
                'widget' => 'single_text',
            ])
            ->add('checkbox', ChoiceType::class, [
                'label' => 'Les dossiers qui devront être disponibles dans l\'add-on :',
                'choices' => [
                    'actions' => 'actions',
                    'catalog/orderActions' => 'catalog/orderActions',
                    'catalog/orderActionsConditions' => 'catalog/orderActionsConditions',
                    'community' => 'community',
                    'notification' => 'notification',
                    'crons' => 'crons',
                    'CustomPaymentModules' => 'CustomPaymentModules',
                    'hooks' => 'hooks',
                    'mxTags' => 'mxTags',
                    'publication_methods' => 'publication_methods',
                    'widgets' => 'widgets',
                    'Menu 2c avec crud' => 'Menu 2c avec crud',
                    'Menu 2c avec form' => 'Menu 2c avec form',
                    'Nouvelle section avec un crud' => 'Nouvelle section avec un crud',
                    'Nouvelle section avec un formulaire' => 'Nouvelle section avec un formulaire',
                    'Web service' => 'Web service',
                    'Interactive map' => 'Interactive map'
                ],


                'multiple' => true,
                'expanded' => true,

            ])

            ->add('identifiantAction', TextType::class, [
                'label' => 'Identifiant de l\'action'
            ])


            ->add('typeAction', ChoiceType::class, [
                'label' => 'Type d\'actions',
                'choices' => [
                    'Manage' => 'manage',
                    'Public' => 'public',
                ],
                'multiple' => false,
                'required' => true,
                'expanded' => true
            ])



            ->add('nomNotification', TextType::class,[
                'label'=> 'Nom de la notification'
            ])
            ->add('descriptionNotification', TextareaType::class,[
                'label' => 'Description de la notification'
            ])
            ->add('nomClasse',TextType::class,[
                'label' => 'Nom de la classe'
            ])
            ->add('notificationAdministrateur', CheckboxType::class,[
                'label' => 'Est une notification Administrateur',
                'required' => false,
            ])
            ->add('nomGroupe',TextType::class,[
                'label' => 'Nom du groupe dans lequel mettre la notification dans le BO'
            ])
            ->add('position', TextType::class,[
                'label' => 'Position'
            ])
            ->add('descriptionTacheCron', TextType::class,[
                'label' => 'Description de la tache planifiée'
            ])
            ->add('identifiantCron', TextType::class,[
                'label' => 'Identifiant'
            ])
            ->add('nomDossierCron',TextType::class,[
                'label' => 'Nom du dossier'
            ])
            ->add('nomFichierCron',TextType::class,[
                'label' => 'Nom du fichier'
            ])
            ->add('descriptionMx', TextType::class,[
                'label' => 'Description de la balise mx'
            ])
            ->add('identifiantMx',TextType::class,[
                'label' => 'Identifiant de la balise mx'
            ])
            ->add('nomMethodePublication',TextType::class,[
                'label' => 'Nom de la méthode de publication'
            ])
            ->add('identifiantMethodePublication',TextType::class,[
                'label' => 'identifiant de la méthode de publication'
            ])
            ->add('typeMethodePublication',ChoiceType::class,[
                'label' => 'Type de la méthode de publication',
                'choices' => [
                    'CRUDFront' => 'crudFront',
                    'Aucune' => 'aucune',
                ],
                'multiple' => false,
                'required' => true,
                'expanded' => true

            ])

            ->add('nomWidget', TextType::class,[
                'label' => 'Nom du widget'
            ])

            ->add('identifiantWidget', TextType::class,[
                'label' => 'Identifiant du widget'
            ])

            ->add('selectionEcran', ChoiceType::class,[
                'label' => 'Sélection dans laquelle ajouter le nouvel écran',
                'choices' => [
                    'Mon site/Configuration' => 'mon site/configuration',
                    'Mon site/Notifications' => 'mon site/notifications',
                    'Mes ressources/Configuration' => 'mes ressources/configuration',
                    'Ma boutique/Commandes' => 'ma boutique/commandes',
                    'Ma boutique/Booster les ventes' => 'ma boutique/booster les ventes',
                    'Statistiques/Catalogue' => 'statistiques/catalogue',
                    'Ma boutique/Configuration' => 'ma boutique/configuration',
                    'Petites annonces/Configuration' => 'petites annonces/configuration',
                    'Annuaires/Configuration' => 'annuaires/configuration',
                    'Blog/Configuration' => 'blog/configuration',
                    'Statistiques/Référencement' => 'statistiques/référencement',
                    'Référencement/Configuration globale' => 'référencement/configuration globale',
                    'Newsletter/Liste de diffusion' => 'newsletter/liste de diffusion',
                    'Statistiques/Newsletter' => 'statistiques/newsletter',
                    'Newsletter/Configuration' => 'newsletter/configuration',
                    'Communauté/Configuration' => 'communauté/configuration',
                    'Administrateur/Configuration' => 'administrateur/configuration',
                    'Forum/Configuration' => 'forum/configuration',
                    'Mes outils/quotas' => 'mes outils/quotas',
                    'Mes outils/Mes préférences' => 'mes outils/mes préférences'

            ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('descriptionMenu',TextType::class,[
                'label' => 'Description du menu'
            ])
            ->add('categorieMenu', TextType::class,[
                'label' => 'Catégorie du menu'
            ])
            ->add('nomMenu', TextType::class,[
                'label' => 'Nom du menu'
            ])
            ->add('nomCrud', TextType::class,[
                'label' => 'PlatesCRUD'
            ])

            ->add('sectionEcran',ChoiceType::class,[
                'label' => 'Section dans laquelle ajouter le nouvel écran',
                'choices' => [
                    'Mon site/Configuration' => 'mon site/configuration',
                    'Mon site/Notifications' => 'mon site/notifications',
                    'Mes ressources/Configuration' => 'mes ressources/configuration',
                    'Ma boutique/Commandes' => 'ma boutique/commandes',
                    'Ma boutique/Booster les ventes' => 'ma boutique/booster les ventes',
                    'Statistiques/Catalogue' => 'statistiques/catalogue',
                    'Ma boutique/Configuration' => 'ma boutique/configuration',
                    'Petites annonces/Configuration' => 'petites annonces/configuration',
                    'Annuaires/Configuration' => 'annuaires/configuration',
                    'Blog/Configuration' => 'blog/configuration',
                    'Statistiques/Référencement' => 'statistiques/référencement',
                    'Référencement/Configuration globale' => 'référencement/configuration globale',
                    'Newsletter/Liste de diffusion' => 'newsletter/liste de diffusion',
                    'Statistiques/Newsletter' => 'statistiques/newsletter',
                    'Newsletter/Configuration' => 'newsletter/configuration',
                    'Communauté/Configuration' => 'communauté/configuration',
                    'Administrateur/Configuration' => 'administrateur/configuration',
                    'Forum/Configuration' => 'forum/configuration',
                    'Mes outils/quotas' => 'mes outils/quotas',
                    'Mes outils/Mes préférrences' => 'mes outils/mes préférences'

                ]
            ])
            ->add('descriptionMenuForm', TextType::class,[
                'label' => 'Description du menu'
            ])
            ->add('categorieMenuForm',TextType::class,[
                'label' => 'Catégorie du menu'
            ])
            ->add('nomMenuForm',TextType::class,[
                'label' => 'Nom du menu'
            ])
            ->add('nomEcranFormulaire', TextType::class,[
                'label' => 'Nom du formulaire',
            ])
            ->add('sectionEcranCrud',ChoiceType::class,[
                'label' => 'Section dans laquelle ajouter le nouvel écran',
                'choices' => [
                    'Mon site' => 'mon site',
                    'Ressources' => 'ressources',
                    'Ma boutique' => 'ma boutique',
                    'Statistiques' => 'statistiques',
                    'Petites annonces' => 'petites annonces',
                    'Annuaires' => 'annuaires',
                    'Blog' => 'blog',
                    'Référencement' => 'référencement',
                    'Newsletter' => 'newletter',
                    'Communauté' => 'communauté',
                    'Administrateurs' => 'administrateurs',
                    'Forum' => 'forum',
                    'Mes outils' => 'mes outils',
                ]

            ])
            ->add('descriptionMenuSection',TextType::class,[
                'label' => 'Description du menu'
            ])
            ->add('categorieMenuSection',TextType::class,[
                'label' => 'Catégorie du menu'
            ])
            ->add('nomMenuSection', TextType::class,[
                'label' => 'Nom du menu'
            ])
            ->add('nomSectionCrud', TextType::class,[
                'label' => 'Nom du CRUD',
            ])

            ->add('sectionMenuFormulaire', ChoiceType::class,[
                'label' => 'Section dans laquelle ajouter le nouvel écran',
                'choices' => [
                    'Mon site' => 'mon site',
                    'Ressources' => 'ressources',
                    'Ma boutique' => 'ma boutique',
                    'Statistiques' => 'statistiques',
                    'Petites annonces' => 'petites annonces',
                    'Annuaires' => 'annuaires',
                    'Blog' => 'blog',
                    'Référencement' => 'référencement',
                    'Newsletter' => 'newletter',
                    'Communauté' => 'communauté',
                    'Administrateurs' => 'administrateurs',
                    'Forum' => 'forum',
                    'Mes outils' => 'mes outils',
                ]
            ])
            ->add('descriptionMenuFormulaire',TextType::class,[
                'label' => 'Description du menu'
            ])
            ->add('categorieMenuFormulaire',TextType::class,[
                'label' => 'Catégorie du menu'
            ])
            ->add('nomMenuFormulaire',TextType::class,[
                'label' => 'Nom du menu'
            ])
            ->add('nomFormulaire', TextType::class,[
                'label' => 'Nom du Formulaire'
            ]);






































    }









    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Generateur::class,


        ]);
    }
}
