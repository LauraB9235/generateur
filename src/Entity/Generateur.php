<?php

namespace App\Entity;

use App\Repository\GenerateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GenerateurRepository::Class)
 */
class Generateur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="le nom est obligatoire")
     * @ORM\Column(type="string", length=255)
     */
    private $nomAddOn;

    /**
     * @Assert\NotBlank (message="La description est obligatoire")
     * @ORM\Column(type="string", length=255)
     */
    private $descriptionAddOn;

    /**
     * @Assert\NotBlank(message="Le nom du developpeur est obligatoire")
     * @ORM\Column(type="string", length=255)
     */
    private $nomDeveloppeur;

    /**
     * @Assert\NotBlank (message="La date de création est obligatoire")
     * @ORM\Column(type="date")
     */
    private $dateDeCreation;

    /**
     * @Assert\NotBlank (message="L'identifiant de l-add-on est obligatoire")
     * @ORM\Column(type="string", length=255)
     */
    private $identifiantAddOn;

    /**
     * @var array
     */
     public $checkbox = [];

    /**
     * @var string
     */
     public $typeAction;

    /**
     * @var string
     */
     public $identifiantAction;


    /**
     * @var string
     */
     public $nomNotification;

    /**
     * @var string
     */
     public $descriptionNotification;

    /**
     * @var string
     */
     public $nomClasse;

    /**
     * @var bool
     */
     public $notificationAdministrateur;

    /**
     * @var string
     */
     public $nomGroupe;

    /**
     * @var string
     */
     public $position;

    /**
     * @var string
     */
     public $descriptionTacheCron;

    /**
     * @var string
     */
     public $identifiantCron;

    /**
     * @var string
     */
     public $nomDossierCron;

    /**
     * @var string
     */
     public $nomFichierCron;

    /**
     * @var string
     */
     public $descriptionMx;

    /**
     * @var string
     */
     public $identifiantMx;


    /**
     * @var string
     */
     public $nomMethodePublication;

    /**
     * @var string
     */
     public $identifiantMethodePublication;

    /**
     * @var string
     */
     public $typeMethodePublication;

    /**
     * @var string
     */
     public $nomWidget;

    /**
     * @var string
     */
     public $identifiantWidget;

    /**
     * @var string
     */
     public $selectionEcran;

    /**
     * @var string
     */
     public $descriptionMenu;

    /**
     * @var string
     */
     public $categorieMenu;

    /**
     * @var string
     */
     public $nomMenu;

    /**
     * @var string
     */
     public $nomCrud;

    /**
     * @var string
     */
     public $sectionEcran;

    /**
     * @var string
     */
     public $descriptionMenuForm;

    /**
     * @var string
     */
     public $categorieMenuForm;

    /**
     * @var string
     */
     public $nomMenuForm;

    /**
     * @var string
     */
     public $nomEcranFormulaire;

    /**
     * @var string
     */
     public $sectionEcranCrud;

    /**
     * @var string
     */
     public $descriptionMenuSection;

    /**
     * @var string
     */
     public $categorieMenuSection;

    /**
     * @var string
     */
     public $nomMenuSection;

    /**
     * @var string
     */
     public $nomSectionCrud;

    /**
     * @var string
     */
     public $sectionMenuFormulaire;

    /**
     * @var string
     */
     public $descriptionMenuFormulaire;

    /**
     * @var string
     */
     public $categorieMenuFormulaire;

    /**
     * @var string
     */
     public $nomMenuFormulaire;

    /**
     * @var string
     */
     public $nomFormulaire;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAddOn(): ?string
    {
        return $this->nomAddOn;
    }

    public function setNomAddOn(string $nomAddOn): self
    {
        $this->nomAddOn = $nomAddOn;

        return $this;
    }

    public function getDescriptionAddOn(): ?string
    {
        return $this->descriptionAddOn;
    }

    public function setDescriptionAddOn(string $descriptionAddOn): self
    {
        $this->descriptionAddOn = $descriptionAddOn;

        return $this;
    }

    public function getNomDeveloppeur(): ?string
    {
        return $this->nomDeveloppeur;
    }

    public function setNomDeveloppeur(string $nomDeveloppeur): self
    {
        $this->nomDeveloppeur = $nomDeveloppeur;

        return $this;
    }

    public function getDateDeCreation(): ?\DateTimeInterface
    {
        return $this->dateDeCreation;
    }

    public function setDateDeCreation(\DateTimeInterface $dateDeCreation): self
    {
        $this->dateDeCreation = $dateDeCreation;

        return $this;
    }

    public function getIdentifiantAddOn(): ?string
    {
        return $this->identifiantAddOn;
    }

    public function setIdentifiantAddOn(string $identifiantAddOn): self
    {
        $this->identifiantAddOn = $identifiantAddOn;

        return $this;
    }

    /**
     * @return array
     */
    public function getCheckbox(): array
    {
        return $this->checkbox;
    }

    /**
     * @param array $checkbox
     */
    public function setCheckbox(array $checkbox): void
    {
        $this->checkbox = $checkbox;
    }






    /**
     * @return string
     */
    public function getTypeAction(): string
    {
        return $this->typeAction;
    }

    /**
     * @param string $typeAction
     */
    public function setTypeAction(string $typeAction): void
    {
        $this->typeAction = $typeAction;
    }







    /**
     * @return string
     */
    public function getIdentifiantAction(): string
    {
        return $this->identifiantAction;
    }

    /**
     * @param string $identifiantAction
     */
    public function setIdentifiantAction(string $identifiantAction): void
    {
        $this->identifiantAction = $identifiantAction;
    }

    /**
     * @return string
     */
    public function getNomNotification(): string
    {
        return $this->nomNotification;
    }

    /**
     * @param string $nomNotification
     */
    public function setNomNotification(string $nomNotification): void
    {
        $this->nomNotification = $nomNotification;
    }

    /**
     * @return string
     */
    public function getDescriptionNotification(): string
    {
        return $this->descriptionNotification;
    }

    /**
     * @param string $descriptionNotification
     */
    public function setDescriptionNotification(string $descriptionNotification): void
    {
        $this->descriptionNotification = $descriptionNotification;
    }

    /**
     * @return string
     */
    public function getNomClasse(): string
    {
        return $this->nomClasse;
    }

    /**
     * @param string $nomClasse
     */
    public function setNomClasse(string $nomClasse): void
    {
        $this->nomClasse = $nomClasse;
    }





    /**
     * @return bool
     */
    public function isNotificationAdministrateur(): bool
    {
        return $this->notificationAdministrateur;
    }

    /**
     * @param bool $notificationAdministrateur
     */
    public function setNotificationAdministrateur(bool $notificationAdministrateur): void
    {
        $this->notificationAdministrateur = $notificationAdministrateur;
    }



    /**
     * @return string
     */
    public function getNomGroupe(): string
    {
        return $this->nomGroupe;
    }

    /**
     * @param string $nomGroupe
     */
    public function setNomGroupe(string $nomGroupe): void
    {
        $this->nomGroupe = $nomGroupe;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getDescriptionTacheCron(): string
    {
        return $this->descriptionTacheCron;
    }

    /**
     * @param string $descriptionTacheCron
     */
    public function setDescriptionTacheCron(string $descriptionTacheCron): void
    {
        $this->descriptionTacheCron = $descriptionTacheCron;
    }

    /**
     * @return string
     */
    public function getIdentifiantCron(): string
    {
        return $this->identifiantCron;
    }

    /**
     * @param string $identifiantCron
     */
    public function setIdentifiantCron(string $identifiantCron): void
    {
        $this->identifiantCron = $identifiantCron;
    }

    /**
     * @return string
     */
    public function getNomDossierCron(): string
    {
        return $this->nomDossierCron;
    }

    /**
     * @param string $nomDossierCron
     */
    public function setNomDossierCron(string $nomDossierCron): void
    {
        $this->nomDossierCron = $nomDossierCron;
    }

    /**
     * @return string
     */
    public function getNomFichierCron(): string
    {
        return $this->nomFichierCron;
    }

    /**
     * @param string $nomFichierCron
     */
    public function setNomFichierCron(string $nomFichierCron): void
    {
        $this->nomFichierCron = $nomFichierCron;
    }

    /**
     * @return string
     */
    public function getDescriptionMx(): string
    {
        return $this->descriptionMx;
    }

    /**
     * @param string $descriptionMx
     */
    public function setDescriptionMx(string $descriptionMx): void
    {
        $this->descriptionMx = $descriptionMx;
    }

    /**
     * @return string
     */
    public function getIdentifiantMx(): string
    {
        return $this->identifiantMx;
    }

    /**
     * @param string $identifiantMx
     */
    public function setIdentifiantMx(string $identifiantMx): void
    {
        $this->identifiantMx = $identifiantMx;
    }

    /**
     * @return string
     */
    public function getNomMethodePublication(): string
    {
        return $this->nomMethodePublication;
    }

    /**
     * @param string $nomMethodePublication
     */
    public function setNomMethodePublication(string $nomMethodePublication): void
    {
        $this->nomMethodePublication = $nomMethodePublication;
    }

    /**
     * @return string
     */
    public function getIdentifiantMethodePublication(): string
    {
        return $this->identifiantMethodePublication;
    }

    /**
     * @param string $identifiantMethodePublication
     */
    public function setIdentifiantMethodePublication(string $identifiantMethodePublication): void
    {
        $this->identifiantMethodePublication = $identifiantMethodePublication;
    }

    /**
     * @return string
     */
    public function isTypeMethodePublication(): string
    {
        return $this->typeMethodePublication;
    }

    /**
     * @param string $typeMethodePublication
     */
    public function setTypeMethodePublication(string $typeMethodePublication): void
    {
        $this->typeMethodePublication = $typeMethodePublication;
    }

    /**
     * @return string
     */
    public function getNomWidget(): string
    {
        return $this->nomWidget;
    }

    /**
     * @param string $nomWidget
     */
    public function setNomWidget(string $nomWidget): void
    {
        $this->nomWidget = $nomWidget;
    }

    /**
     * @return string
     */
    public function getIdentifiantWidget(): string
    {
        return $this->identifiantWidget;
    }

    /**
     * @param string $identifiantWidget
     */
    public function setIdentifiantWidget(string $identifiantWidget): void
    {
        $this->identifiantWidget = $identifiantWidget;
    }

    /**
     * @return string
     */
    public function getSelectionEcran(): string
    {
        return $this->selectionEcran;
    }

    /**
     * @param string $selectionEcran
     */
    public function setSelectionEcran(string $selectionEcran): void
    {
        $this->selectionEcran = $selectionEcran;
    }

    /**
     * @return string
     */
    public function getDescriptionMenu(): string
    {
        return $this->descriptionMenu;
    }

    /**
     * @param string $descriptionMenu
     */
    public function setDescriptionMenu(string $descriptionMenu): void
    {
        $this->descriptionMenu = $descriptionMenu;
    }

    /**
     * @return string
     */
    public function getCategorieMenu(): string
    {
        return $this->categorieMenu;
    }

    /**
     * @param string $categorieMenu
     */
    public function setCategorieMenu(string $categorieMenu): void
    {
        $this->categorieMenu = $categorieMenu;
    }

    /**
     * @return string
     */
    public function getNomMenu(): string
    {
        return $this->nomMenu;
    }

    /**
     * @param string $nomMenu
     */
    public function setNomMenu(string $nomMenu): void
    {
        $this->nomMenu = $nomMenu;
    }

    /**
     * @return string
     */
    public function getNomCrud(): string
    {
        return $this->nomCrud;
    }

    /**
     * @param string $nomCrud
     */
    public function setNomCrud(string $nomCrud): void
    {
        $this->nomCrud = $nomCrud;
    }

    /**
     * @return string
     */
    public function getSectionEcran(): string
    {
        return $this->sectionEcran;
    }

    /**
     * @param string $sectionEcran
     */
    public function setSectionEcran(string $sectionEcran): void
    {
        $this->sectionEcran = $sectionEcran;
    }

    /**
     * @return string
     */
    public function getDescriptionMenuForm(): string
    {
        return $this->descriptionMenuForm;
    }

    /**
     * @param string $descriptionMenuForm
     */
    public function setDescriptionMenuForm(string $descriptionMenuForm): void
    {
        $this->descriptionMenuForm = $descriptionMenuForm;
    }

    /**
     * @return string
     */
    public function getCategorieMenuForm(): string
    {
        return $this->categorieMenuForm;
    }

    /**
     * @param string $categorieMenuForm
     */
    public function setCategorieMenuForm(string $categorieMenuForm): void
    {
        $this->categorieMenuForm = $categorieMenuForm;
    }

    /**
     * @return string
     */
    public function getNomMenuForm(): string
    {
        return $this->nomMenuForm;
    }

    /**
     * @param string $nomMenuForm
     */
    public function setNomMenuForm(string $nomMenuForm): void
    {
        $this->nomMenuForm = $nomMenuForm;
    }

    /**
     * @return string
     */
    public function getNomEcranFormulaire(): string
    {
        return $this->nomEcranFormulaire;
    }

    /**
     * @param string $nomEcranFormulaire
     */
    public function setNomEcranFormulaire(string $nomEcranFormulaire): void
    {
        $this->nomEcranFormulaire = $nomEcranFormulaire;
    }



    /**
     * @return string
     */
    public function getSectionEcranCrud(): string
    {
        return $this->sectionEcranCrud;
    }

    /**
     * @param string $sectionEcranCrud
     */
    public function setSectionEcranCrud(string $sectionEcranCrud): void
    {
        $this->sectionEcranCrud = $sectionEcranCrud;
    }

    /**
     * @return string
     */
    public function getDescriptionMenuSection(): string
    {
        return $this->descriptionMenuSection;
    }

    /**
     * @param string $descriptionMenuSection
     */
    public function setDescriptionMenuSection(string $descriptionMenuSection): void
    {
        $this->descriptionMenuSection = $descriptionMenuSection;
    }

    /**
     * @return string
     */
    public function getCategorieMenuSection(): string
    {
        return $this->categorieMenuSection;
    }

    /**
     * @param string $categorieMenuSection
     */
    public function setCategorieMenuSection(string $categorieMenuSection): void
    {
        $this->categorieMenuSection = $categorieMenuSection;
    }

    /**
     * @return string
     */
    public function getNomMenuSection(): string
    {
        return $this->nomMenuSection;
    }

    /**
     * @param string $nomMenuSection
     */
    public function setNomMenuSection(string $nomMenuSection): void
    {
        $this->nomMenuSection = $nomMenuSection;
    }

    /**
     * @return string
     */
    public function getNomSectionCrud(): string
    {
        return $this->nomSectionCrud;
    }

    /**
     * @param string $nomSectionCrud
     */
    public function setNomSectionCrud(string $nomSectionCrud): void
    {
        $this->nomSectionCrud = $nomSectionCrud;
    }

    /**
     * @return string
     */
    public function getSectionMenuFormulaire(): string
    {
        return $this->sectionMenuFormulaire;
    }

    /**
     * @param string $sectionMenuFormulaire
     */
    public function setSectionMenuFormulaire(string $sectionMenuFormulaire): void
    {
        $this->sectionMenuFormulaire = $sectionMenuFormulaire;
    }



    /**
     * @return string
     */
    public function getDescriptionMenuFormulaire(): string
    {
        return $this->descriptionMenuFormulaire;
    }

    /**
     * @param string $descriptionMenuFormulaire
     */
    public function setDescriptionMenuFormulaire(string $descriptionMenuFormulaire): void
    {
        $this->descriptionMenuFormulaire = $descriptionMenuFormulaire;
    }

    /**
     * @return string
     */
    public function getCategorieMenuFormulaire(): string
    {
        return $this->categorieMenuFormulaire;
    }

    /**
     * @param string $categorieMenuFormulaire
     */
    public function setCategorieMenuFormulaire(string $categorieMenuFormulaire): void
    {
        $this->categorieMenuFormulaire = $categorieMenuFormulaire;
    }

    /**
     * @return string
     */
    public function getNomMenuFormulaire(): string
    {
        return $this->nomMenuFormulaire;
    }

    /**
     * @param string $nomMenuFormulaire
     */
    public function setNomMenuFormulaire(string $nomMenuFormulaire): void
    {
        $this->nomMenuFormulaire = $nomMenuFormulaire;
    }

    /**
     * @return string
     */
    public function getNomFormulaire(): string
    {
        return $this->nomFormulaire;
    }

    /**
     * @param string $nomFormulaire
     */
    public function setNomFormulaire(string $nomFormulaire): void
    {
        $this->nomFormulaire = $nomFormulaire;
    }












    public function __toString()
    {
        return $this->name;
    }















































}
