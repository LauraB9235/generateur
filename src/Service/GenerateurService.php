<?php


namespace App\Service;

use ZipArchive;
use DOMDocument;
use PDO;

class GenerateurService
{

    public function generate($creationForm, $entityManager, $creation, $html, $crudListHtml, $search)
    {
        //on recupere dans le formulaire le nom de l'identifiantAddOn qui est ajouter a la variable $identifiant
        $identifiant = $creationForm->get('identifiantAddOn')->getData();


        //on récupére la valeur cocher par l'utilisateur au niveau des checkbox
        $checkbox = $creationForm->get('checkbox')->getData();
       // $valeur = implode([$checkbox[0]]);

        //on récupére le choix des boutons radios de "type d'actions" de la checkbox "actions"
        $choixtypeAction = $creationForm->get('typeAction')->getData();

        //on récupére dans le formulaire la valeur de "identifiant de l'action" de la checkbox "actions"
        $identifiantAction = $creationForm->get('identifiantAction')->getData();

        //on récupére dans le formulaire la valeur de "Decription de la notification" de la checkbox "notifications"
        $descriptionNotifications = $creationForm->get('descriptionNotification')->getData();

        //on récupére dans le formulaire la valeur de "Nom du groupe dans lequel mettre la notification dans le BO" de la checkbox "notifications"
        $nomGroupe = $creationForm->get('nomGroupe')->getData();

        //on récupére dans le formulaire la valeur de "position" de la checkbox "notifications"
        $position = $creationForm->get('position')->getData();

        //on récupére dans le formulaire la valeur de "nom de la notification" de la checkbox "notifications"
        $nomNotification = $creationForm->get('nomNotification')->getData();

        //on récupére dans le formulaire la valeur de "Description de la notification" de la checkbox "notifications"
        $nomDescription = $creationForm->get('descriptionNotification')->getData();

        //on récupére dans le formulaire le choix cocher ou non de "Est une notification Administrateur" de la checkbox "notifications"
        $notificationAdministrateur = $creationForm->get('notificationAdministrateur')->getData();

        //on récupére dans le formulaire la valeur de "nom de la classe" de la checkbox "notifications"
        $nomClasse = $creationForm->get('nomClasse')->getData();

        //on récupére dans le formulaire la valeur de "identifiant" de la checkbox "crons"
        $identifiantCrons = $creationForm->get('identifiantCron')->getData();

        //on récupére dans le formulaire la valeur de "description de la tâche" de la checkbox "crons"
        $descriptionCrons = $creationForm->get('descriptionTacheCron')->getData();

       //on récupére dans le formulaire la valeur de "nom du dossier" de la checkbox "crons"
        $nomDossierCrons = $creationForm->get('nomDossierCron')->getData();

        //on récupére dans le formulaire la valeur de "identifiant de la balise mx" de la checkbox "mx_tags"
        $identifiantMxTags = $creationForm->get('identifiantMx')->getData();

        //on récupére dans le formulaire la valeur de "Description de la balise mx" de la checkbox "mx_tags"
        $descriptionBaliseMxTags = $creationForm->get('descriptionMx')->getData();

         //on récupére dans le formulaire la valeur de "nom de la méthode de publication" de la checkbox "publication_methods"
        $nomMethodePublication = $creationForm->get('nomMethodePublication')->getData();

        //on récupére dans le formulaire la valeur de "identifiant de la méthode de publication" de la checkbox "publication_methods"
        $identifiantmethodPublication = $creationForm->get('identifiantMethodePublication')->getData();

        //on récupére la choix du formulaire des boutons radio "type d'action" de la checkbox "publication_methods"
        $choixPublicationMethode = $creationForm->get('typeMethodePublication')->getData();

        //on récupére dans le formulaire la valeur de "identifiant du Widget" de la checkbox "Widgets"
        $identifiantWidget = $creationForm->get('identifiantWidget')->getData();

        //on récupére dans le formulaire la valeur du "nom du Widget" de la checkbox "Widgets"
        $nomWidget = $creationForm->get('nomWidget')->getData();

        //on recupere dans le formulaire la valeur de "nom du CRUD" de la checkbox "Menu 2c avec crud"
        $nomCrudMenu2c = $creationForm->get('nomCrud')->getData();

        //on recupere dans le formulaire la valeur de "description du menu" de la checkbox "Menu 2c avec crud"
        $descriptionMenu = $creationForm->get('descriptionMenu')->getData();

        //on recupere dans le formulaire la valeur de "categorie du menu" de la checkbox "Menu 2c avec crud"
        $categorieMenu = $creationForm->get('categorieMenu')->getData();

        //on recupere dans le formulaire la valeur du "nom de menu" de la checkbox "Menu 2c avec crud"
        $nomMenu = $creationForm->get('nomMenu')->getData();

        //on recupere dans le formulaire la valeur du "nom du formulaire" de la checkbox "Menu 2c avec form"
        $nomFormulaire = $creationForm->get('nomEcranFormulaire')->getData();

        //on recupere dans le formulaire la valeur du "nom du menu" de la checkbox "Nouvelle section avec un crud"
        $nomMenuSectionCrud = $creationForm->get('nomMenuSection')->getData();

        //on récupére dans le formulaire la valeur de "nom du Crud" de la checkbox "Nouvelle section avec un crud"
        $nomCrudSection = $creationForm->get('nomSectionCrud')->getData();

        //on récupére dans le formulaire la valeur du "nom du menu" de la checkbox "Nouvelle section avec un formulaire"
        $nomMenuFormulaire = $creationForm-> get('nomMenuFormulaire')->getData();







        //on persist le formulaire
        $entityManager->persist($creation);
        //on envoie le formulaire dans la base de données
        $entityManager->flush();


        //on gere le temps des fichiers inserer dans le zip
        $files = glob($identifiant . ".zip");
        $currentTime = time();
        foreach ($files as $file) {
            $lastModifiedTime = filemtime($file);
            $timeDiff = abs($currentTime - $lastModifiedTime) / (60 * 60);
            if (is_file($file) && $timeDiff > 1) {
                unlink($file);
            }
        }

        //Fichier Database.sql
        $mysqlDatabaseName = "generateur";
        $mysqlUserName = "root";
        $mysqlPassword = "salut";
        $mysqlHostName = "localhost";
        $mysqlExporthPath = "database.sql";

        $command = 'mysqldump --opt -h' . $mysqlHostName . ' -u' . $mysqlUserName . '-p' . $mysqlPassword . '' . $mysqlDatabaseName . ' > ' . $mysqlExporthPath;
        exec($command, $output, $worked);
        switch ($worked) {
            case 0:
                echo 'La base de données <b>' . $mysqlDatabaseName . ' </b> a été stockée avec succés dans le chemin suivant' . getcwd() . '/' . $mysqlExporthPath . '</b>';
                break;
            case 1:
                echo 'Une erreur s\'est produite lors de l\'exportation de <b>' . $mysqlDatabaseName . '</b> vers' . getcwd() . '/' . $mysqlExporthPath . '</b>';
                break;
            case 2 :
                echo ' Une erreur d\'exportation s\'est produite, veuillez vérifier les informations suivantes :
                       <br/><br/><table><tr><td>MySqlDatabaseName : </td><td><b>' . $mysqlDatabaseName . '</b></td></tr>
                       <tr><td>MySqlUserName :</td><td><b>' . $mysqlUserName . '</b></td></tr>
                       <tr<td>MySqlPassword : </td><td><b>NOTSHOWN</b></td></tr>
                       <tr><td>MySqlHostName : </td><td><b>' . $mysqlHostName . '</b></td></tr></table>';
                break;

        }








        //Fichier "data.xml" qui se trouve dans le dossier specifs/addons/nom de l'identifiant de l'add-on rentrer dans le formulaire
        //On se connecte a MySql
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        //On récupére la derniére ligne inserer dans la table generateur
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');

        //On crée un DomDocument
        $xmlFile = new DOMDocument("1.0", "UTF-8");
        //Le format du fichier Xml est correct
        $xmlFile->formatOutput = true;

        //on crée la balise parent <data>
        $data = $xmlFile->createElement('data');
        //on ferme la balise parent </data>
        $xmlFile->appendChild($data);

        //on affiche chaque entrée une a une
        while ($creation = $datas->fetch()) {

            //on crée la balise enfant <title> et on lui ajoute le nom de l'add_on inserer dans le formulaire
            $title = $xmlFile->createElement('title', $creation['nom_add_on']);
            //on créé la balise fermante </title> de la balise parent <data>
            $data->appendChild($title);

            //on crée la balise enfant <name> et on lui ajoute le nom de l'identifiant insérer dans le formulaire
            $name = $xmlFile->createElement('name', $creation['identifiant_add_on']);
            //on crée la balise fermante </name> de la balise parent <data>
            $data->appendChild($name);

            //on crée la balise enfant <version>
            $version = $xmlFile->createElement('version');
            //on crée la balise fermante </version> de la balise parent <data>
            $data->appendChild($version);

            //on crée la balise enfant <num>
            $num = $xmlFile->createElement('num', 1);

            //on crée l'attribut date à l'interieur de la balise <num>
            $date = $xmlFile->createAttribute('date');

            //on ajoute la valeur de date_de_creation à l'attribut date
            $date->value = $creation['date_de_creation'];
            //on ferme la balise <num>
            $num->appendChild($date);

            //on crée la balise fermant </num> de la balise parent <version>
            $version->appendChild($num);

            //on crée la balise enfant <emajine_compatibility>
            $emajineCompatibility = $xmlFile->createElement('emajine_compatibility');
            //on crée la balise fermante </emajine_compatibility> de la balise parent <data>
            $data->appendChild($emajineCompatibility);

            //on crée la balise enfant <min_version>
            $minVersion = $xmlFile->createElement('min-version', '2.13a');
            //on crée la balise fermante </min_version> de la balise parent <emajine_compatibility>
            $emajineCompatibility->appendChild($minVersion);

            //on crée la balise enfant <webo_shop_info>
            $weboShopInfo = $xmlFile->createElement('webo_shop_info');
            //on crée la balise fermante </webo_shop_info> de la balise parente <data>
            $data->appendChild($weboShopInfo);

            //on crée la balise <key>
            $key = $xmlFile->createElement("key");
            //on crée la balise fermante </key> de la balise parente <webo_shop_info>
            $weboShopInfo->appendChild($key);

            //on crée la balise <url>
            $url = $xmlFile->createElement("url");
            //on crée la balise fermante </url> de la balise parente <webo_shop_info>
            $weboShopInfo->appendChild($url);

            //on crée la balise <author>
            $author = $xmlFile->createElement("author");
            //on crée la balise fermante </author> de la balise parente <webo_shop_info>
            $weboShopInfo->appendChild($author);

            //on crée la balise <name>
            $name_author = $xmlFile->createElement("name");
            //on crée la balise enfant <!CDATA> de la balise parent <name> et on y ajoute le nom du developpeur inserer dans le formulaire.
            $name_author->appendChild($xmlFile->createCDATASection($creation['nom_developpeur']));
            //on crée la balise fermante </name> de la balise parente <author>
            $author->appendChild($name_author);

            //on crée la balise <email>
            $email = $xmlFile->createElement("email");
            //on crée la balise fermante </email> de la balise parente </author>
            $author->appendChild($email);

            //on crée la balise <description>
            $description = $xmlFile->createElement("description");
            //on crée la balise <!CDATA> on y ajoute la description de l'add_on inserer dans le formulaire
            $description->appendChild($xmlFile->createCDATASection($creation['description_add_on']));
            //on crée la balise fermante </description> de la balise parente <data>
            $data->appendChild($description);

            //on créé la balise <compatibility>
            $compatibility = $xmlFile->createElement('compatibility');
            //on crée la balise <!CDATA> qui est vide
            $compatibility->appendChild($xmlFile->createCDATASection("e-majine 2.13a et +"));
            //on crée la balise fermante </compatibility> de la balise <data>
            $data->appendChild($compatibility);
        }

        // on enregiste le fichier sous le nom de "data.xml" qui se trouve dans le dossier specifs/addons/nom de l'identifiant de l'add-on rentrer dans le formulaire
        $xmlFile->save('data.xml');




        //fichiers PHP qui porte le nom de "l'identifiant de l'add-on.php" rentrer dans le formulaire et qui se trouve dans le dossier "class"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        //On récupére la derniére ligne inserer dans la table generateur
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');

        $filename = $identifiant . '.class.php';
        $php_file = fopen($filename, 'w+');
        if (filesize($filename) > 0) {
            $contents = fread($php_file, filesize($filename));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_file, '<?php' . "\r\n");
            fwrite($php_file, ' namespace Addon\\' . $identifiant . ';' . "\r\n");
            fwrite($php_file, "\r\n");
            fwrite($php_file, '/**' . "\r\n");
            fwrite($php_file, ' * ' . $identifiant . "\r\n");
            fwrite($php_file, ' *' . "\r\n");
            fwrite($php_file, ' * Master de la classe' . "\r\n");
            fwrite($php_file, ' *' . "\r\n");
            fwrite($php_file, ' * @author Medialibs' . "\r\n");
            fwrite($php_file, ' *' . "\r\n");
            fwrite($php_file, ' * @date ' . $creation['date_de_creation'] . "\r\n");
            fwrite($php_file, ' */' . "\r\n");
            fwrite($php_file, 'class ' . $identifiant . ' extends \Addons_Entity' . "\r\n");
            fwrite($php_file, '{' . "\r\n");
            fwrite($php_file, '   //singleton' . "\r\n");
            fwrite($php_file, '  protected static $instance;' . "\r\n");
            fwrite($php_file, "\r\n");
            fwrite($php_file, ' /**' . "\r\n");
            fwrite($php_file, '  * Récupération du singleton' . "\r\n");
            fwrite($php_file, '  *' . "\r\n");
            fwrite($php_file, '  * Permet d\'instancier le singleton depuis les classes standards :' . "\r\n");
            fwrite($php_file, '  *' . "\r\n");
            fwrite($php_file, "  * @return \AddOn\ $identifiant\ $identifiant " . "\r\n");
            fwrite($php_file, '  */' . "\r\n");
            fwrite($php_file, '  static public function getInstance()' . "\r\n");
            fwrite($php_file, '  {' . "\r\n");
            fwrite($php_file, '   if (!self::instance) {' . "\r\n");
            fwrite($php_file, '     self::$instance = \Addons_Manager::getInstance()->getAddon(end(explode(\'\\\\\', get_class($this))), true);' . "\r\n");
            fwrite($php_file, '   }' . "\r\n");
            fwrite($php_file, '   return self::$instance;' . "\r\n");
            fwrite($php_file, ' }' . "\r\n");
            fwrite($php_file, " \r\n");
            fwrite($php_file, ' /**' . "\r\n");
            fwrite($php_file, '  * Permet de lancer des actions spécifiques après installation de l\'add_on' . "\r\n");
            fwrite($php_file, '  *' . "\r\n");
            fwrite($php_file, '  * @return null' . "\r\n");
            fwrite($php_file, '  */' . "\r\n");
            fwrite($php_file, '   public function onInstallation()' . "\r\n");
            fwrite($php_file, '  {' . "\r\n");
            fwrite($php_file, "\r\n");
            fwrite($php_file, '   }' . "\r\n");
            fwrite($php_file, "\r\n");
            fwrite($php_file, '  /**' . "\r\n");
            fwrite($php_file, '   * Permet de lancer des actions spécifiques après désinstallation de l\'add_on' . "\r\n");
            fwrite($php_file, '   *' . "\r\n");
            fwrite($php_file, '   * @return null' . "\r\n");
            fwrite($php_file, '   */' . "\r\n");
            fwrite($php_file, '   public function onUninstallation()' . "\r\n");
            fwrite($php_file, '  {' . "\r\n");
            fwrite($php_file, "\r\n");
            fwrite($php_file, '    }' . "\r\n");
            fwrite($php_file, "\r\n");
            fwrite($php_file, '  /**' . "\r\n");
            fwrite($php_file, '   *' . "\r\n");
            fwrite($php_file, '   * Permet de lancer des actions spécifiques après la désactivation de l\'add-on' . "\r\n");
            fwrite($php_file, '   *' . "\r\n");
            fwrite($php_file, '   * @return null' . "\r\n");
            fwrite($php_file, '   */' . "\r\n");
            fwrite($php_file, '   public function onDisable()' . "\r\n");
            fwrite($php_file, '  {' . "\r\n");
            fwrite($php_file, "\r\n");
            fwrite($php_file, '   }' . "\r\n");
            fwrite($php_file, "\r\n");
            fwrite($php_file, '  /**' . "\r\n");
            fwrite($php_file, '   * Permet de lancer des actions spécifiques avant l\'export de l\'add-on' . "\r\n");
            fwrite($php_file, '   *' . "\r\n");
            fwrite($php_file, '   * @return null' . "\r\n");
            fwrite($php_file, '   */' . "\r\n");
            fwrite($php_file, '   public function onExport()' . "\r\n");
            fwrite($php_file, '   {' . "\r\n");
            fwrite($php_file, "\r\n");
            fwrite($php_file, '   }' . "\r\n");
            fwrite($php_file, "\r\n");
            fwrite($php_file, '  /**' . "\r\n");
            fwrite($php_file, '   * Vérifie l\'activation de l\'add-on ' . "\r\n");
            fwrite($php_file, '   *' . "\r\n");
            fwrite($php_file, '   * @return boolean true si l\'add-on est actif' . "\r\n");
            fwrite($php_file, '   */' . "\r\n");
            fwrite($php_file, '   public function isEnabled()' . "\r\n");
            fwrite($php_file, '   {' . "\r\n");
            fwrite($php_file, '   return $this->status() == \Addons_Entity::STATUS_ACTIVE;' . "\r\n");
            fwrite($php_file, '   }' . "\r\n");
            fwrite($php_file, "\r\n");
            fwrite($php_file, '  /**' . "\r\n");
            fwrite($php_file, '   * Retourne le chemin de destination des images' . "\r\n");
            fwrite($php_file, '   *' . "\r\n");
            fwrite($php_file, '   * @return string' . "\r\n");
            fwrite($php_file, '   */' . "\r\n");
            fwrite($php_file, '   public function imagePath()' . "\r\n");
            fwrite($php_file, '   {' . "\r\n");
            fwrite($php_file, "   return '/images/addons/$identifiant/';" . "\r\n");
            fwrite($php_file, '   }' . "\r\n");
            fwrite($php_file, '}' . "\r\n");
        }
        fclose($php_file);



        //fichier portant le nom de l'identifiant de l'add-on et qui se trouve dans le dossier specifs/hooks/actions/manage/

        $file1 = $identifiant .'.php';
        $php_file1 = fopen($file1, 'w+');
        if (filesize($file1) > 0) {
            $contents = fread($php_file1, filesize($file1));
        }
            fwrite($php_file1,'<?php'."\r\n");
            fwrite($php_file1,'/**'."\r\n");
            fwrite($php_file1,' *'.$identifiant."\r\n");
            fwrite($php_file1,' *'."\r\n");
            fwrite($php_file1,' *'."\r\n");
            fwrite($php_file1,' *'."\r\n");
            fwrite($php_file1,' * @author Medialibs'."\r\n");
            fwrite($php_file1,' *'."\r\n");
            fwrite($php_file1,' * @date 2018-07-12'."\r\n");
            fwrite($php_file1,' */'."\r\n");
            fwrite($php_file1,'require_once \em_misc::getSpecifPath() . \'/addons/test1/class/tools/interface/InterfaceScreen.class.php\';'."\r\n");
            fwrite($php_file1,"\r\n");
            fwrite($php_file1,'class '.$identifiant."\r\n");
            fwrite($php_file1,'{'."\r\n");
            fwrite($php_file1,'    public function start()'."\r\n");
            fwrite($php_file1,'    {'."\r\n");
            fwrite($php_file1,'        $is = new Addon\test1\InterfaceScreen();'."\r\n");
            fwrite($php_file1,'        $is->start();'."\r\n");
            fwrite($php_file1,'    }'."\r\n");
            fwrite($php_file1,'}'."\r\n");

            fclose($php_file1);



        //fichier "MxToolsAbstract.class.php" qui se trouve dans le dossier class/tools/abstracts
        $fileMxToolsAbstract = 'MxToolsAbstract.class.php';
        $php_fileMxToolsAbstract = fopen($fileMxToolsAbstract, 'w+');
        if (filesize($fileMxToolsAbstract) > 0) {
            $contents = fread($php_fileMxToolsAbstract, filesize($fileMxToolsAbstract));
        }
        fwrite($php_fileMxToolsAbstract, '<?php' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   namespace Addon\\' . $identifiant . ';' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '   class MxToolsAbstract{' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '   protected $templateFile;' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   protected $templatePath;' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   protected $mx;' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   protected $allowModelesCascades = false;' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '   function __construct()' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   {' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     $this->initMx();' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function initMx(){' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     $this->mx = new \Modelixe($this->templateFile);' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '     $this->mx->SetMxTemplatePath( $this->getTemplatePath() . $this->templatePath);' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     $this->mx->SetModeliXe("mx");' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '   protected function getSkin(){' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '      if( !$this->skin){' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '      $skin = \em_misc::getSkin();' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '      $skin = $skin["root"]["Skin"]["config"]["name"];' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '      if( $this->allowModelesCascades){' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '        $i = 1;' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '         while(' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '            isset($this->templatesFile)' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '            && !file_exists( \em_misc::getSpecifPath() . "../modeles/" . \em_misc::getLang() . "/" . $skin . "/" .$this->templatePath . "/". $this->templateFile )' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '            && $i<=count( $GLOBALS[\'siteconfig\'][\'RUBRIQUE\'][\'SKIN\'] )' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '            ){' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '            $skin = $GLOBALS[\'siteconfig\'][\'RUBRIQUE\'][\'.SKIN.\'][\'skin\'.$i];' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '            $i++;' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '         }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '         return $skin;' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '    }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '    else{' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '         return $this->skin;' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '    }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function getTemplatePath(){' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     return \em_misc::getSpecifPath() . "../modeles/" . \em_misc::getLang() . "/" . $this->getSkin() . "/";' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function setSkin($_skin){' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     $this->skin = $_skin;' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function getStringContent()' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   {' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '    if($this->mx)' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '      return $this->mx->mxWrite();' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '    return "";' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function deleteBlock($block)' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   {' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     \em_mx::delete( $this->mx, $block);' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function getBlock($block)' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   {' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     return \em_mx::get( $this->mx, $block);' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function loopBlock($block)' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   {' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     return \em_mx::loop( $this->mx, $block);' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function mxText( $block, $content)' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   {' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     $this->mx->mxText( $block, $content);' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function mxAttribut( $block, $content)' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   {' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     $this->mx->mxAttribut($block, $content);' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function start(){}' . "\r\n");
        fwrite($php_fileMxToolsAbstract, "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function setTemplateFile($file)' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   {' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     $this->templateFile = $file;' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function setTemplatePath($path)' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   {' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     $this->templatePath = $path;' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function getMx()' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   {' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     return $this->mx;' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   public function setAllowModelesCascade($boolean)' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   {' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '     $this->allowModelesCascade = $boolean;' . "\r\n");
        fwrite($php_fileMxToolsAbstract, '   }' . "\r\n");
        fwrite($php_fileMxToolsAbstract, ' }' . "\r\n");

        fclose($php_fileMxToolsAbstract);


        //fichier "InterfaceInstallation.class.php" qui se trouve dans le dossier \specifs\addons\nom de l'identifiant de l'add-on\class\tools\interface
        $fileInterfaceInstallation = 'InterfaceInstallation.class.php';
        $php_fileInterfaceInstallation = fopen($fileInterfaceInstallation, 'w+');
        if (filesize($fileInterfaceInstallation) > 0) {
            $contents = fread($php_fileInterfaceInstallation, filesize($fileInterfaceInstallation));
        }
        fwrite($php_fileInterfaceInstallation, '<?php' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '   namespace Addon\\' . $identifiant . ';' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '   /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    * InterfaceInstallation' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    * Déplacement des fichiers modeles / scripts / images' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    * @author Célestin' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    * @date 2016-09-20' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '   class InterfaceInstallation' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '   {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "    CONST ADD_ON_NAME = '$identifiant';" . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '    protected $addOnRoot;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    protected $siteRoot;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '   /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    * Constructeur' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    public function __construct()' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '      $this->addOnRoot = \Addons_Tools::getPathAddon( self::ADD_ON_NAME ) . \'/assets/\';' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '      $this->siteRoot =  \em_misc::getSpecifPath() . \'../\';' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '    }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '  /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '   * Déplacement des fichiers modeles / scripts / images' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '   *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '   * @return null' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '   */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '   public function start()' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '   {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     if ($this-> alreadyInstalled()) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '         return;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '     //installation des scripts : ' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     $this-> installScripts();' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     $this-> installModeles();' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     $this-> intallImages();' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '  }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '   /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    * Est ce que les fichiers ont déjà été déplacés ?' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    * @return boolean' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     protected function alreadyInstalled()' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       return  $this->scriptAreOK()' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '           && $this->modelesAreOK()' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '           && $this->imagesAreOK();' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '   /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    * Les scripts ont-ils été déplacés?' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    * @return boolean' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     protected function scriptAreOK()' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       if(is_dir($this->addOnRoot . \'scripts\')) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          return is_dir($this->siteRoot . \'scripts/addons/\' . self::ADD_ON_NAME);' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       } else {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          return true;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '   /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    * Les modèles ont-ils été déplacés?' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    * @return boolean' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     protected function modelesAreOK()' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       if (is_dir($this->addOnRoot . \'modeles\')){' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '           // récupération du modèle par défaut' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '           $dir = $this->getModeleDirToTest();' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '           return is_dir(' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '              $this->siteRoot . \'modeles/\' . \em_misc::getDefaultLang() . \em_misc::getSkin()[\'root\'][\'Skin\'][\'config\'][\'name\'] . $dir ' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '           );' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       }else{' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '         return true;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '    /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     * Les images ont-elles été déplacées?' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     * @return boolean' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '      protected function imagesAreOK()' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '      {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          if(is_dir($this->addOnRoot . \'images\')) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '             return is_dir($this->siteRoot . \'images/addons/\' . self::ADD_ON_NAME);' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          } else {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '             return true;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '    /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     * Retourne le chemin du premier répertoire au bout d\'arbo' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     * @return string' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '     */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '      protected function getModeleDirToTest()' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '      {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          $i = 100;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          $file = $this->addOnRoot . \'modeles\';' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          do{' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '            $previous  = $file;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '            $file =  $this->getFirstDir($file);' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '            $i--;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          } while($file && $i > 0);' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '          return  str_replace($this->addOnRoot,\'\', $previous);' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '      }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '      /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * Retourne le premier repertoire d\'un répertoire' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * @Param string $dir répertoire dans lequel effectuer la recherche' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * @return string' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '        protected function getFirstDir($dir)' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '        {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '            $files = glob($dir . \'/*\');' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '            foreach ($files as $file){' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '              if(is_dir($file)) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '                 return $file;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '              }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '            }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '            return false;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '        }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '      /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * Copie des scripts' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * ' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * @return null' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '        protected function installScripts()' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '        {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '            $this->copyTree($this->addOnRoot . \'scripts\', $this->siteRoot . \'scripts/addons/\' . self:: ADD_ON_NAME);' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '        }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '     /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '      * On copie les templates pour tous les modèles de toutes les langues' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '      *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '      * @return null' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '      */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       protected function installModeles()' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          //pour chaque langue' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          foreach(glob($this->siteRoot . \'modeles/*\') as $lang) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '              if(is_dir($lang)){' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '                 foreach(glob($lang . \'/*\') as $modele){' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '                    if(is_dir($modele)) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '                        $this->copyTree($this->addOnRoot . \'modeles\', $modele);' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '                    }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '                 }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '             }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '       /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * Copie des images' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * @return null' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       protected function installImage()' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '           $this->copyTree($this->addOnRoot . \'images\', $this->siteRoot . \'images/addons/\' . self::ADD_ON_NAME);' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '      /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * Fonction de copie d\'une arborescence à une autre' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * @param string $source chemin vers l\'arborescence source' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * @param string $dest chemin vers l\'arborescence destination' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * @return boolean | null' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '      protected function copyTree($source,$dest)' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '      {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '         if (!file_exists($source)) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '             return;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '         }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '          if( is_link($source)) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '           return symlink(readlink($source), $dest);' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '          if( is_file($source)) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '           return copy ($source,$dest);' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '          if(!is_dir($dest)) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '           $this->makeDir($dest);');
        fwrite($php_fileInterfaceInstallation, '          }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '          $dir  = dir ($source);' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          while (false !== $entry = $dir->read()) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '              if( $entry ==  \'.\' || $entry ==  \'..\') {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '               continue;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '              }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '              $this->copyTree("$source/$entry", "$dest/$entry");' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '          $dir->close();' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '          return true;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '      /**' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * création d\'un répertoire' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       * @param string $dirToCreate répertoire à créer' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       *' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       *  return null' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       */' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       protected function makeDir( $dirToCreate )' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '           if(!is_dir($dirToCreate)) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '              $dirs = explode( \'/\',substr($dirToCreate, 1));' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '              $path = \'\';' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '              foreach($dirs as $dir) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '                  $path .=  \'/\' .  $dir;' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '                  if(!is_dir($path)) {' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '                     mkdir($path);' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '                  }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '              }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '           }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, '       }' . "\r\n");
        fwrite($php_fileInterfaceInstallation, "\r\n");
        fwrite($php_fileInterfaceInstallation, '    }' . "\r\n");

        fclose($php_fileInterfaceInstallation);


        
        //fichier InterfaceScreen.class.php qui se trouve dans le dossier \specifs\addons\nom de l'identifiant de l'add-on\class\tools\interface
        $fileInterfaceScreen = 'InterfaceScreen.class.php';
        $php_fileInterfaceScreen = fopen($fileInterfaceScreen, 'w+');
        if (filesize($fileInterfaceScreen) > 0) {
            $contents = fread($php_fileInterfaceScreen, filesize($fileInterfaceScreen));
        }
        fwrite($php_fileInterfaceScreen, '<?php' . "\r\n");
        fwrite($php_fileInterfaceScreen, '    namespace Addon\\' . $identifiant . ';' . "\r\n");
        fwrite($php_fileInterfaceScreen, "\r\n");
        fwrite($php_fileInterfaceScreen, '    /**' . "\r\n");
        fwrite($php_fileInterfaceScreen, '     * InterfaceScreen' . "\r\n");
        fwrite($php_fileInterfaceScreen, '     *' . "\r\n");
        fwrite($php_fileInterfaceScreen, '     * Affichage de l\'état de l\'add-on' . "\r\n");
        fwrite($php_fileInterfaceScreen, '     *' . "\r\n");
        fwrite($php_fileInterfaceScreen, '     * @author Medialibs' . "\r\n");
        fwrite($php_fileInterfaceScreen, '     *' . "\r\n");
        fwrite($php_fileInterfaceScreen, '     * @date 2016-06-28' . "\r\n");
        fwrite($php_fileInterfaceScreen, '     */' . "\r\n");
        fwrite($php_fileInterfaceScreen, "\r\n");
        fwrite($php_fileInterfaceScreen, '    require_once __DIR__ .\'/../abstracts/MxToolsAbstract.class.php\';' . "\r\n");
        fwrite($php_fileInterfaceScreen, '    require_once __DIR__ .\'/InterfaceTools.class.php\';' . "\r\n");
        fwrite($php_fileInterfaceScreen, '    require_once __DIR__ .\'/../../' . $identifiant . '.class.php\';' . "\r\n");
        fwrite($php_fileInterfaceScreen, "\r\n");
        fwrite($php_fileInterfaceScreen, '    class InterfaceScreen extends MxToolsAbstract' . "\r\n");
        fwrite($php_fileInterfaceScreen, '    {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '        protected $templateFile  = \'interfaceScreen.html\';' . "\r\n");
        fwrite($php_fileInterfaceScreen, '        protected $speTemplatePath = \'/../../manageTpls/\';' . "\r\n");
        fwrite($php_fileInterfaceScreen, "\r\n");
        fwrite($php_fileInterfaceScreen, '        function __construct(){}' . "\r\n");
        fwrite($php_fileInterfaceScreen, "\r\n");
        fwrite($php_fileInterfaceScreen, '        /**' . "\r\n");
        fwrite($php_fileInterfaceScreen, '         * Affiche l\'interface de gestion de l\'add-on' . "\r\n");
        fwrite($php_fileInterfaceScreen, '         *' . "\r\n");
        fwrite($php_fileInterfaceScreen, '         * @return string' . "\r\n");
        fwrite($php_fileInterfaceScreen, '         */' . "\r\n");
        fwrite($php_fileInterfaceScreen, '         public function start()' . "\r\n");
        fwrite($php_fileInterfaceScreen, '         {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '             $this->initMx();' . "\r\n");
        fwrite($php_fileInterfaceScreen, '             $this->deleteBlock(\'actionResult\');' . "\r\n");
        fwrite($php_fileInterfaceScreen, "\r\n");
        fwrite($php_fileInterfaceScreen, '             if (!empty($_POST)) {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                 foreach($_POST as $key => $value) {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                     if(strpos($key, \'action\') === 0) {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                        $method = substr($key,6);' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                        $obj    = new InterfaceTools();' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                        if(method_exists($obj,$method)) {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                           $this->mxText(\'actionResult.txt\', $obj->$method());' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                        }' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                     }' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                 }' . "\r\n");
        fwrite($php_fileInterfaceScreen, '             }' . "\r\n");
        fwrite($php_fileInterfaceScreen, "\r\n");
        fwrite($php_fileInterfaceScreen, '             if (!empty($_GET[\'cron\'])) {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                 $obj = new InterfaceTools();' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                 $obj->execCron($_GET[\'cron\']);' . "\r\n");
        fwrite($php_fileInterfaceScreen, '             }' . "\r\n");
        fwrite($php_fileInterfaceScreen, '             $this->displayCrons();' . "\r\n");
        fwrite($php_fileInterfaceScreen, '             $this->initStateInfo();' . "\r\n");
        fwrite($php_fileInterfaceScreen, '             echo $this->getStringContent();' . "\r\n");
        fwrite($php_fileInterfaceScreen, "\r\n");
        fwrite($php_fileInterfaceScreen, '             exit;' . "\r\n");
        fwrite($php_fileInterfaceScreen, '         }' . "\r\n");
        fwrite($php_fileInterfaceScreen, "\r\n");
        fwrite($php_fileInterfaceScreen, '         /**' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          * Récupère le chemin vers le template' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          *' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          * @return string' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          */' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          public function getTemplatePath()' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '              return __DIR__ . $this->speTemplatePath;' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          }' . "\r\n");
        fwrite($php_fileInterfaceScreen, "\r\n");
        fwrite($php_fileInterfaceScreen, '         /**' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          * Récupération et affichage de la liste des crons' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          *' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          * @return null' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          */' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          public function displayCrons()' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '              $obj = new InterfaceTools();' . "\r\n");
        fwrite($php_fileInterfaceScreen, '              $crons = $obj->getAllCrons();' . "\r\n");
        fwrite($php_fileInterfaceScreen, '              if (empty($crons)) {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                  $this->deleteBlock(\'installed.crons\');' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                  return;' . "\r\n");
        fwrite($php_fileInterfaceScreen, '              }' . "\r\n");
        fwrite($php_fileInterfaceScreen, '              $addonName = explode(\'\\\\\', get_class($this))[1];' . "\r\n");
        fwrite($php_fileInterfaceScreen, '              foreach($crons as $cron){' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                  $this->mxText(\'installed.crons.cron.text\', str_replace(array($addonName . \'_addons_cron_\',\'.class.php\'), \'\',$cron));' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                  $this->mxAttribut(\'installed.crons.cron.href\', $_SERVER[\'REQUEST_URI\'] . \'?cron=\' . $cron);' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                  $this->loopBlock(\'installed.crons.cron\');' . "\r\n");
        fwrite($php_fileInterfaceScreen, '              }' . "\r\n");
        fwrite($php_fileInterfaceScreen, '          }' . "\r\n");
        fwrite($php_fileInterfaceScreen, "\r\n");
        fwrite($php_fileInterfaceScreen, '          /**' . "\r\n");
        fwrite($php_fileInterfaceScreen, '           * Initialise les informations d\'état de l\'add-on' . "\r\n");
        fwrite($php_fileInterfaceScreen, '           *' . "\r\n");
        fwrite($php_fileInterfaceScreen, '           * @return null' . "\r\n");
        fwrite($php_fileInterfaceScreen, '           */' . "\r\n");
        fwrite($php_fileInterfaceScreen, '           protected function initStateInfo()' . "\r\n");
        fwrite($php_fileInterfaceScreen, '           {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '               //affichage de l\'info' . "\r\n");
        fwrite($php_fileInterfaceScreen, '               $data  = $this->actions;' . "\r\n");
        fwrite($php_fileInterfaceScreen, '               $addon = ' . $identifiant . '::getInstance();' . "\r\n");
        fwrite($php_fileInterfaceScreen, "\r\n");
        fwrite($php_fileInterfaceScreen, '               if($addon) {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                  if($addon->isEnabled()) {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                     $this->deleteBlock(\'state.disabled\');' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                     $this->deleteBlock(\'state_action.enableAction\');' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                  } else {' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                     $this->deleteBlock(\'state.enabled\');' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                     $this->deleteBlock(\'state_action.disableAction\');' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                  }' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                  $this->deleteBlock(\'notInstalled\');' . "\r\n");
        fwrite($php_fileInterfaceScreen, '               }else{' . "\r\n");
        fwrite($php_fileInterfaceScreen, '                  $this->deleteBlock(\'installed\');' . "\r\n");
        fwrite($php_fileInterfaceScreen, '               }' . "\r\n");
        fwrite($php_fileInterfaceScreen, '           }' . "\r\n");
        fwrite($php_fileInterfaceScreen, '    }' . "\r\n");
        fclose($php_fileInterfaceScreen);


        //fichier InterfaceTools.class.php qui se trouve dans le dossier \specifs\addons\nom de l'identifiant de l'add-on\class\tools\interface
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        //On récupére la derniére ligne inserer dans la table generateur
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');


        $fileInterfaceTools = 'InterfaceTools.class.php';
        $php_fileInterfaceTools = fopen($fileInterfaceTools, 'w+');
        if (filesize($fileInterfaceTools) > 0) {
            $contents = fread($php_fileInterfaceTools, filesize($fileInterfaceTools));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileInterfaceTools, '<?php' . "\r\n");
            fwrite($php_fileInterfaceTools, '    namespace AddOn\\' . $identifiant . ';' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '    /**' . "\r\n");
            fwrite($php_fileInterfaceTools, '     * InterfaceTools' . "\r\n");
            fwrite($php_fileInterfaceTools, '     *' . "\r\n");
            fwrite($php_fileInterfaceTools, '     * Gestion de l\'interface avec emajine (Installation, Désinstallation, Activation, Désactivation)' . "\r\n");
            fwrite($php_fileInterfaceTools, '     *' . "\r\n");
            fwrite($php_fileInterfaceTools, '     * @author Célestin' . "\r\n");
            fwrite($php_fileInterfaceTools, '     *' . "\r\n");
            fwrite($php_fileInterfaceTools, '     * @date 2016-09-20' . "\r\n");
            fwrite($php_fileInterfaceTools, '     */' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '    require_once __DIR__ . \'/InterfaceInstallation.class.php\';' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '    class InterfaceTools' . "\r\n");
            fwrite($php_fileInterfaceTools, '    {' . "\r\n");
            fwrite($php_fileInterfaceTools, '        const ADDON_TRADE_NAME = "' . $creation['nom_add_on'] . '" ;' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '        protected $addonName;' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '        /**' . "\r\n");
            fwrite($php_fileInterfaceTools, '         * Constructeur' . "\r\n");
            fwrite($php_fileInterfaceTools, '         */' . "\r\n");
            fwrite($php_fileInterfaceTools, '         public function __construct()' . "\r\n");
            fwrite($php_fileInterfaceTools, '         {' . "\r\n");
            fwrite($php_fileInterfaceTools, '             $addonData = explode(\'\\\\\' , get_classes($this));' . "\r\n");
            fwrite($php_fileInterfaceTools, '             $this->addonName = $addonData[1];' . "\r\n");
            fwrite($php_fileInterfaceTools, '         }' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '         /**' . "\r\n");
            fwrite($php_fileInterfaceTools, '          * Installe l\'add-on' . "\r\n");
            fwrite($php_fileInterfaceTools, '          *' . "\r\n");
            fwrite($php_fileInterfaceTools, '          * @return string' . "\r\n");
            fwrite($php_fileInterfaceTools, '          */' . "\r\n");
            fwrite($php_fileInterfaceTools, '          public function install()' . "\r\n");
            fwrite($php_fileInterfaceTools, '          {' . "\r\n");
            fwrite($php_fileInterfaceTools, '              $am = new \Addons_Manager();' . "\r\n");
            fwrite($php_fileInterfaceTools, '              if($am->isStructureOk($this->addonName)) {' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  // on récupère l\'objet Addons_Entity. Si l\'addon n\'est pas installé, la fonction le fera :' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  $addon = $am->getAddon($this->addonName, true);' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '                  $this->cleanCache();' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '                  return \'Votre add-on "\' . self::ADDON_TRADE_NAME . \'" a été correctement installé\';' . "\r\n");
            fwrite($php_fileInterfaceTools, '              } else{' . "\r\n");
            fwrite($php_fileInterfaceTools, "                  return 'Une erreur s\'est produite';" . "\r\n");
            fwrite($php_fileInterfaceTools, '              }' . "\r\n");
            fwrite($php_fileInterfaceTools, '          }' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '          /**' . "\r\n");
            fwrite($php_fileInterfaceTools, '           * Désinstalle de l\'add-on' . "\r\n");
            fwrite($php_fileInterfaceTools, '           *' . "\r\n");
            fwrite($php_fileInterfaceTools, '           * @return string' . "\r\n");
            fwrite($php_fileInterfaceTools, '           */' . "\r\n");
            fwrite($php_fileInterfaceTools, '           public function uninstall()');
            fwrite($php_fileInterfaceTools, '           {' . "\r\n");
            fwrite($php_fileInterfaceTools, '               $am = new \Addons_Manager();' . "\r\n");
            fwrite($php_fileInterfaceTools, '               // on vérifie la structure de l\'add-on parce que, quoi qu\'il arrive,' . "\r\n");
            fwrite($php_fileInterfaceTools, '               // si l\'add-on n\'est pas installé, il le sera avant d\'être désinstallé' . "\r\n");
            fwrite($php_fileInterfaceTools, '               if($am->isStructureOk($this->addonName)) {' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  // on récupére l\'objet Addons_Entity. Si l\'add-on n\'est pas installé, la fonction le fera :' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  $addon = $am->getAddon($this->addonName, true);' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  $am->uninstall($addon);' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '                  $this->cleancache();' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '                  return \'Votre add-on "\' . self::ADDON_TRADE_NAME . \'" a été correctement désinstallé\';' . "\r\n");
            fwrite($php_fileInterfaceTools, '               }' . "\r\n");
            fwrite($php_fileInterfaceTools, '               else{' . "\r\n");
            fwrite($php_fileInterfaceTools, "                  return 'Une erreur s\'est produite.';" . "\r\n");
            fwrite($php_fileInterfaceTools, '               }' . "\r\n");
            fwrite($php_fileInterfaceTools, '           }' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '           /**' . "\r\n");
            fwrite($php_fileInterfaceTools, '            * Active l\'add-on' . "\r\n");
            fwrite($php_fileInterfaceTools, '            *' . "\r\n");
            fwrite($php_fileInterfaceTools, '            * @return string' . "\r\n");
            fwrite($php_fileInterfaceTools, '            */' . "\r\n");
            fwrite($php_fileInterfaceTools, '             public function enable()' . "\r\n");
            fwrite($php_fileInterfaceTools, '             {' . "\r\n");
            fwrite($php_fileInterfaceTools, '                 $am = new \Addons_Manager();' . "\r\n");
            fwrite($php_fileInterfaceTools, '                 // on vérifie la structure de l\'add-on parce que, quoi qu\'il arrive,' . "\r\n");
            fwrite($php_fileInterfaceTools, '                 // si l\'add-on n\'est pas installé, il le sera avant d\'être activé' . "\r\n");
            fwrite($php_fileInterfaceTools, '                 if ($am->isStructureOk($this->addonName)) {' . "\r\n");
            fwrite($php_fileInterfaceTools, '                     // on récupére l\'objet Addons-Entity. Si l\'add-on n\'est pas installé, la fonction le fera :' . "\r\n");
            fwrite($php_fileInterfaceTools, '                        $addon = $am->getAddon($this->addonName, true);' . "\r\n");
            fwrite($php_fileInterfaceTools, '                        $am->enable($addon,true);' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '                        $this->cleanCache();' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '                        //installation des fichiers assets' . "\r\n");
            fwrite($php_fileInterfaceTools, '                        $assetsInstallation = new InterfaceInstallation();' . "\r\n");
            fwrite($php_fileInterfaceTools, '                        $assetsInstallation->start();' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '                     return \'Votre add-on "\' . self::ADDON_TRADE_NAME . \'" a été activé.\';' . "\r\n");
            fwrite($php_fileInterfaceTools, '                 }' . "\r\n");
            fwrite($php_fileInterfaceTools, '                 else{' . "\r\n");
            fwrite($php_fileInterfaceTools, "                     return 'Une erreur s\'est produite';" . "\r\n");
            fwrite($php_fileInterfaceTools, '                 }' . "\r\n");
            fwrite($php_fileInterfaceTools, '             }' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '             /**' . "\r\n");
            fwrite($php_fileInterfaceTools, '              * Désactive l\'add-on' . "\r\n");
            fwrite($php_fileInterfaceTools, '              *' . "\r\n");
            fwrite($php_fileInterfaceTools, '              * @return string' . "\r\n");
            fwrite($php_fileInterfaceTools, '              */' . "\r\n");
            fwrite($php_fileInterfaceTools, '              public function disable()' . "\r\n");
            fwrite($php_fileInterfaceTools, '              {' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  $am = new \Addons_Manager();' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  //on vérifie la structure de l\'add-on parce que, quoi qu\'il arrive,' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  //si l\'addon n\'est pas installé, il le sera avant d\'être désactivé' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  if ($am ->isStructureOk($this->addonName)) {' . "\r\n");
            fwrite($php_fileInterfaceTools, '                      // on récupère l\'objet Addons-Entity. Si l\'add-on n\'est pas installé, la fonction le fera :' . "\r\n");
            fwrite($php_fileInterfaceTools, '                         $addon = $am->getAddon($this->addonName, true);' . "\r\n");
            fwrite($php_fileInterfaceTools, '                         $am->disable($addon);' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '                        $this->cleanCache();' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '                     return \'Votre add-on "\' . self::ADDON_TRADE_NAME . \'" a été désactivé.\';' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  }else{' . "\r\n");
            fwrite($php_fileInterfaceTools, "                     return 'Une erreur s\'est produite';" . "\r\n");
            fwrite($php_fileInterfaceTools, '                  }' . "\r\n");
            fwrite($php_fileInterfaceTools, '              }' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '             /**' . "\r\n");
            fwrite($php_fileInterfaceTools, '              * Réinitialise le cache des développements spécifiques' . "\r\n");
            fwrite($php_fileInterfaceTools, '              *' . "\r\n");
            fwrite($php_fileInterfaceTools, '              * @return null' . "\r\n");
            fwrite($php_fileInterfaceTools, '              */' . "\r\n");
            fwrite($php_fileInterfaceTools, '              protected function cleanCache()' . "\r\n");
            fwrite($php_fileInterfaceTools, '              {' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  $cacheManager = new \Emajine_Cache();' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  $cacheManager->clean(\'devspe\');' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '              }' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '             /**' . "\r\n");
            fwrite($php_fileInterfaceTools, '              * Execution du cron passé en paramétre' . "\r\n");
            fwrite($php_fileInterfaceTools, '              *' . "\r\n");
            fwrite($php_fileInterfaceTools, '              * @return null' . "`\r\n");
            fwrite($php_fileInterfaceTools, '              */' . "\r\n");
            fwrite($php_fileInterfaceTools, '              public function execCron($cronFile)' . "\r\n");
            fwrite($php_fileInterfaceTools, '              {' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  require_once \em_misc::getClassPath() . \'/core/Emajine_Cron/Emajine_Cron_Tools.class.php\';' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  $_SESSION[\'cronslogscreen\'] = true;' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  list($addonName, $cronName) = explode(\'_addons_\', $cronFile);' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '                  if($cronName) {' . "\r\n");
            fwrite($php_fileInterfaceTools, '                     $addon = \Addons_Manager::getInstance()->getAddon($addonName, true);' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '                     if ($addon) {' . "\r\n");
            fwrite($php_fileInterfaceTools, '                         $cron = $addon->getOneCron($cronName);' . "\r\n");
            fwrite($php_fileInterfaceTools, '                         $className = get_class($cron);' . "\r\n");
            fwrite($php_fileInterfaceTools, '                         $cron->start();' . "\r\n");
            fwrite($php_fileInterfaceTools, '                         unset($_SESSION[\'cronlogscreen\']);' . "\r\n");
            fwrite($php_fileInterfaceTools, '                         exit;' . "\r\n");
            fwrite($php_fileInterfaceTools, '                     }' . "\r\n");
            fwrite($php_fileInterfaceTools, '                  }' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '                   return;' . "\r\n");
            fwrite($php_fileInterfaceTools, '              }' . "\r\n");
            fwrite($php_fileInterfaceTools, "\r\n");
            fwrite($php_fileInterfaceTools, '              /**' . "\r\n");
            fwrite($php_fileInterfaceTools, '               * Récupère la liste de tous les crons de l\'add-on' . "\r\n");
            fwrite($php_fileInterfaceTools, '               *' . "\r\n");
            fwrite($php_fileInterfaceTools, '               * @return array' . "\r\n");
            fwrite($php_fileInterfaceTools, "               */" . "\r\n");
            fwrite($php_fileInterfaceTools, '               public function getAllCrons()' . "\r\n");
            fwrite($php_fileInterfaceTools, '               {' . "\r\n");
            fwrite($php_fileInterfaceTools, '                   //on vérifie la structure de l\'add-on parce que, quoi qu\'il arrive' . "\r\n");
            fwrite($php_fileInterfaceTools, '                   //si l\'add-on n\'est pas installé, il le sera avant d\'être désactivé' . "\r\n");
            fwrite($php_fileInterfaceTools, '                   if(\Addons_Manager::getInstance()->isStructureOk($this->addonName)) {' . "\r\n");
            fwrite($php_fileInterfaceTools, '                      //on récupère l\'objet Addons_Entity. Si l\'add-on n\'est pas installé, la fonction le fera : ' . "\r\n");
            fwrite($php_fileInterfaceTools, '                      $addon = \Addons_Manager::getInstance()->getAddon($this->addonName, true);' . "\r\n");
            fwrite($php_fileInterfaceTools, '                      return $addon->getAllCrons();' . "\r\n");
            fwrite($php_fileInterfaceTools, '                   }' . "\r\n");
            fwrite($php_fileInterfaceTools, '                      return array();' . "\r\n");
            fwrite($php_fileInterfaceTools, '               }' . "\r\n");
            fwrite($php_fileInterfaceTools, '    }' . "\r\n");
        }
        fclose($php_fileInterfaceTools);


        //fichiers portant le nom du choix "Identifiant de l'action" dans la checkbox actions type d'actions "manage" qui se trouve dans le dossier \specifs\addons\nom de l'identifiant de l'add-on\class\actions\manage
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        //On récupére la derniére ligne inserer dans la table generateur
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');

        $fileAction = $identifiantAction . '.php';
        $php_fileAction = fopen($fileAction, 'w+');
        if (filesize($fileAction) > 0) {
            $contents = fread($php_fileAction, filesize($fileAction));
        }
            if ($choixtypeAction == 'manage') {


                while ($creation = $datas->fetch()) {
                    fwrite($php_fileAction, '<?php' . "\r\n");
                    fwrite($php_fileAction, 'namespace Addon\\' . $identifiant . ';' . "\r\n");
                    fwrite($php_fileAction, '/**' . "\r\n");
                    fwrite($php_fileAction, '* Action du BO "' . $identifiantAction . '"' . "\r\n");
                    fwrite($php_fileAction, '*' . "\r\n");
                    fwrite($php_fileAction, '* @author [Name] <[name]@medialibs.com>' . "\r\n");
                    fwrite($php_fileAction, '*' . "\r\n");
                    fwrite($php_fileAction, '* @since ' . $creation['date_de_creation'] . "\r\n");
                    fwrite($php_fileAction, '*/' . "\r\n");
                    fwrite($php_fileAction, "\r\n");
                    fwrite($php_fileAction, 'class ' . $identifiantAction . "\r\n");
                    fwrite($php_fileAction, '{' . "\r\n");
                    fwrite($php_fileAction, '    /**' . "\r\n");
                    fwrite($php_fileAction, '     * Point d\'entrée de l\'action' . "\r\n");
                    fwrite($php_fileAction, '     *' . "\r\n");
                    fwrite($php_fileAction, '     * @return null' . "\r\n");
                    fwrite($php_fileAction, '     */' . "\r\n");
                    fwrite($php_fileAction, '    public function start()' . "\r\n");
                    fwrite($php_fileAction, '    {' . "\r\n");
                    fwrite($php_fileAction, '        \em_output::echoAndExit(\'Hello world\');' . "\r\n");
                    fwrite($php_fileAction, '    }' . "\r\n");
                    fwrite($php_fileAction, '}' . "\r\n");
                }
            } else {
                //fichiers portant le nom du choix "Identifiant de l'action" dans la checkbox actions type d'actions "public"
                while ($creation = $datas->fetch()) {
                    fwrite($php_fileAction, '<?php' . "\r\n");
                    fwrite($php_fileAction, 'namespace Addon\\' . $identifiant . ';' . "\r\n");
                    fwrite($php_fileAction, '/**' . "\r\n");
                    fwrite($php_fileAction, '* Action public "' . $identifiantAction . '"' . "\r\n");
                    fwrite($php_fileAction, '*' . "\r\n");
                    fwrite($php_fileAction, '* @author [Name] <[name]@medialibs.com>' . "\r\n");
                    fwrite($php_fileAction, '*' . "\r\n");
                    fwrite($php_fileAction, '* @since ' . $creation['date_de_creation'] . "\r\n");
                    fwrite($php_fileAction, '*/' . "\r\n");
                    fwrite($php_fileAction, "\r\n");
                    fwrite($php_fileAction, 'class ' . $identifiantAction . "\r\n");
                    fwrite($php_fileAction, '{' . "\r\n");
                    fwrite($php_fileAction, '    /**' . "\r\n");
                    fwrite($php_fileAction, '     * Point d\'entrée de l\'action' . "\r\n");
                    fwrite($php_fileAction, '     *' . "\r\n");
                    fwrite($php_fileAction, '     * @return null' . "\r\n");
                    fwrite($php_fileAction, '     */' . "\r\n");
                    fwrite($php_fileAction, '    public function start()' . "\r\n");
                    fwrite($php_fileAction, '    {' . "\r\n");
                    fwrite($php_fileAction, '        \em_output::echoAndExit(\'Hello world\');' . "\r\n");
                    fwrite($php_fileAction, '    }' . "\r\n");
                    fwrite($php_fileAction, '}' . "\r\n");
                }

                fclose($php_fileAction);

            }


        //fichiers portant le nom du "l'identifiantAddOn" dans la checkbox "notification" et qui se trouve dans le dossier \specifs\addons\nom de l'identifiant de l'add-on\class\notifications
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        //On récupére la derniére ligne inserer dans la table generateur
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');

        $fileNotifications = $identifiant . '.php';
        $php_fileNotification = fopen($fileNotifications, 'w+');
        if (filesize($fileNotifications) > 0) {
            $contents = fread($php_fileNotification, filesize($fileNotifications));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileNotification, '<?php' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, 'namespace Addon\\' . $nomClasse . ';' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, 'require_once __DIR__ . \'/../' . $identifiant . '.class.php\';' . "\r\n");
            fwrite($php_fileNotification, '/**' . "\r\n");
            fwrite($php_fileNotification, ' * Classe de gestion de la notification spécifique de l\'add-on ' . $identifiant . '' . "\r\n");
            fwrite($php_fileNotification, ' *' . "\r\n");
            fwrite($php_fileNotification, ' * ' . $descriptionNotifications . '' . "\r\n");
            fwrite($php_fileNotification, ' *' . "\r\n");
            fwrite($php_fileNotification, ' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>' . "\r\n");
            fwrite($php_fileNotification, ' *' . "\r\n");
            fwrite($php_fileNotification, ' * @since' . $creation['date_de_creation'] . "\r\n");
            fwrite($php_fileNotification, ' */' . "\r\n");
            fwrite($php_fileNotification, 'class ' . $nomClasse . ' extends \Mail_Notification_Abstract ' . "\r\n");
            fwrite($php_fileNotification, '{' . "\r\n");
            fwrite($php_fileNotification, '    //Template' . "\r\n");
            fwrite($php_fileNotification, '    const PATH = \'specifs/addons/' . $identifiant . '/notification\';' . "\r\n");
            fwrite($php_fileNotification, '    //A créer' . "\r\n");
            fwrite($php_fileNotification, '    const TEMPLATE =\'notifications_template.html\';' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, '    //Panneau de configuration' . "\r\n");
            fwrite($php_fileNotification, '    const GROUP = ' . "'" . $nomGroupe . "'" . ';' . "\r\n");
            fwrite($php_fileNotification, '    const ORDER =' . "'" . $position . "'" . ';' . "\r\n");
            fwrite($php_fileNotification, '    const NAME =' . "'" . $nomNotification . "'" . ';' . "\r\n");
            fwrite($php_fileNotification, '    const DESCRIPTION =' . "'" . $nomDescription . "'" . ';' . "\r\n");
            fwrite($php_fileNotification, '    const TYPE = \'html\';' . "\r\n");
            fwrite($php_fileNotification, '    const IS_SENT_TO_ADMIN =' . $notificationAdministrateur . ';' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, '    //Notification' . "\r\n");
            fwrite($php_fileNotification, '    const MAIL_SUBJECT = \'{firstname} {fastname}!\';' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, '   //Tags' . "\r\n");
            fwrite($php_fileNotification, '   protected $customTagsLabel = array(' . "\r\n");
            fwrite($php_fileNotification, '      \'title\' => \'Titre du client\',' . "\r\n");
            fwrite($php_fileNotification, '      \'firstname\' => \'Prénom du client\',' . "\r\n");
            fwrite($php_fileNotification, '      \'lastname\' => \'Nom du client\',' . "\r\n");
            fwrite($php_fileNotification, '   );' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, '   protected $customTagsHtml = array(' . "\r\n");
            fwrite($php_fileNotification, '      \'title\' => \'<mx:text id="title" />\',' . "\r\n");
            fwrite($php_fileNotification, '      \'firstname\' => \'<mx:text id="firstname" />\',' . "\r\n");
            fwrite($php_fileNotification, '      \'lastname\' => \'<mx:text id="firstname" />\'' . "\r\n");
            fwrite($php_fileNotification, '   );' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, '   // Les fausses données pour l’envoi de test' . "\r\n");
            fwrite($php_fileNotification, '   protected $fakeData = array(' . "\r\n");
            fwrite($php_fileNotification, '      \'firstnamemember\' => \'Bob\',' . "\r\n");
            fwrite($php_fileNotification, '      \'lastnamemember\' => \'Round\',' . "\r\n");
            fwrite($php_fileNotification, '   );' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, '   protected $subjectFakeData = array(' . "\r\n");
            fwrite($php_fileNotification, '      \'firstname\' => \'John\',' . "\r\n");
            fwrite($php_fileNotification, '      \'lastname\' => \'Rand\',' . "\r\n");
            fwrite($php_fileNotification, '   );' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, '   /**' . "\r\n");
            fwrite($php_fileNotification, '    * Retourne l\'etat d\'activation dans le webo facto du module gérant la notification' . "\r\n");
            fwrite($php_fileNotification, '    *' . "\r\n");
            fwrite($php_fileNotification, '    * @return bool' . "\r\n");
            fwrite($php_fileNotification, '    */' . "\r\n");
            fwrite($php_fileNotification, '    public function isEnabled()' . "\r\n");
            fwrite($php_fileNotification, '    {' . "\r\n");
            fwrite($php_fileNotification, '        $addon =' . $identifiant . '::getInstance();' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, '        if ($addon) {' . "\r\n");
            fwrite($php_fileNotification, '             if ($addon->isEnabled()) {' . "\r\n");
            fwrite($php_fileNotification, '                 return true;' . "\r\n");
            fwrite($php_fileNotification, '             }' . "\r\n");
            fwrite($php_fileNotification, '        }' . "\r\n");
            fwrite($php_fileNotification, '        return false;' . "\r\n");
            fwrite($php_fileNotification, '    }' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, '   /**' . "\r\n");
            fwrite($php_fileNotification, '    * Envoi la notification' . "\r\n");
            fwrite($php_fileNotification, '    *' . "\r\n");
            fwrite($php_fileNotification, '    * @Param integer $userId' . "\r\n");
            fwrite($php_fileNotification, '    * @Param array $datas The datas' . "\r\n");
            fwrite($php_fileNotification, '    *' . "\r\n");
            fwrite($php_fileNotification, '    *@return boolean' . "\r\n");
            fwrite($php_fileNotification, '    */' . "\r\n");
            fwrite($php_fileNotification, '    public function send($userId,$datas)' . "\r\n");
            fwrite($php_fileNotification, '    {' . "\r\n");
            fwrite($php_fileNotification, '        if(empty($userId)) {' . "\r\n");
            fwrite($php_fileNotification, '           return false;' . "\r\n");
            fwrite($php_fileNotification, '    }' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, '    $person = self::getPerson($userId);' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, '    $messageData = array(' . "\r\n");
            fwrite($php_fileNotification, '       \'data\'   => $datas,' . "\r\n");
            fwrite($php_fileNotification, '       \'person\' => $person' . "\r\n");
            fwrite($php_fileNotification, '    );' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, '    $subjectData = array(' . "\r\n");
            fwrite($php_fileNotification, '       \'{firstname}\' => $datas["modificateur"][\'prenom\'],' . "\r\n");
            fwrite($php_fileNotification, '       \'{lastname}\' => $datas["modificateur"][\'nom\']' . "\r\n");
            fwrite($php_fileNotification, '    );' . "\r\n");
            fwrite($php_fileNotification, '    if ($this->sendMx($messageData, $subjectData, array($person))) {' . "\r\n");
            fwrite($php_fileNotification, '        return true;' . "\r\n");
            fwrite($php_fileNotification, '    }' . "\r\n");
            fwrite($php_fileNotification, '    return false;' . "\r\n");
            fwrite($php_fileNotification, '  }' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, '  /**' . "\r\n");
            fwrite($php_fileNotification, '   * Remplissage du template avec les données fournies' . "\r\n");
            fwrite($php_fileNotification, '   *' . "\r\n");
            fwrite($php_fileNotification, '   * @param array $data' . "\r\n");
            fwrite($php_fileNotification, '   * @param string $type' . "\r\n");
            fwrite($php_fileNotification, '   * @param string $mxPrefix' . "\r\n");
            fwrite($php_fileNotification, '   *' . "\r\n");
            fwrite($php_fileNotification, '   * @return modeliXe' . "\r\n");
            fwrite($php_fileNotification, '   */' . "\r\n");
            fwrite($php_fileNotification, '   public function fillMx(array $data, $type = \'html_\', $mxPrefix = \'\')' . "\r\n");
            fwrite($php_fileNotification, '   {' . "\r\n");
            fwrite($php_fileNotification, '       $mx = $this->getMxTemplate();' . "\r\n");
            fwrite($php_fileNotification, '       \em_mx::text($mx, \'firstnamemember\', $data[\'data\'][\'prenom\']);' . "\r\n");
            fwrite($php_fileNotification, '       \em_mx::text($mx, \'lastnamemember\',  $data[\'data\'][\'nom\']);' . "\r\n");
            fwrite($php_fileNotification, '       return $mx;' . "\r\n");
            fwrite($php_fileNotification, '   }' . "\r\n");
            fwrite($php_fileNotification, "\r\n");
            fwrite($php_fileNotification, ' }' . "\r\n");
        }
        fclose($php_fileNotification);


        //fichiers portant le nom du "l'identifiantAddOn" dans la checkbox "crons" qui se trouve dans le dossier specifs\addons\nom de l'identifiant de l'add-on\class\crons
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        //On récupére la derniére ligne inserer dans la table generateur
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');

        $fileCrons = 'cron_' . $identifiantCrons . '.php';
        $fileCrons1 = 'cron_' . $identifiantCrons;
        $php_fileCrons = fopen($fileCrons, 'w+');
        if (filesize($fileNotifications) > 0) {
            $contents = fread($php_fileCrons, filesize($fileCrons));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileCrons, '<?php' . "\r\n");
            fwrite($php_fileCrons, 'namespace Addons\\' . $identifiant . ';' . "\r\n");
            fwrite($php_fileCrons, "\r\n");
            fwrite($php_fileCrons, 'require_once \em_misc::getSpecifPath() . \'addons/' . $identifiant . '/class/tools/libs/MonitoringLog.class.php\';' . "\r\n");
            fwrite($php_fileCrons, "\r\n");
            fwrite($php_fileCrons, "\r\n");
            fwrite($php_fileCrons, '/**' . "\r\n");
            fwrite($php_fileCrons, ' * Tache repetitive' . " $descriptionCrons ." . "\r\n");
            fwrite($php_fileCrons, ' * ' . "\r\n");
            fwrite($php_fileCrons, ' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>' . "\r\n");
            fwrite($php_fileCrons, ' *' . "\r\n");
            fwrite($php_fileCrons, ' *@since' . $creation['date_de_creation'] . "\r\n");
            fwrite($php_fileCrons, ' */' . "\r\n");
            fwrite($php_fileCrons, 'class ' . $fileCrons1 . "\r\n");
            fwrite($php_fileCrons, "\r\n");
            fwrite($php_fileCrons, '{' . "\r\n");
            fwrite($php_fileCrons, '    //Fréquence d\'exécution de la tâche' . "\r\n");
            fwrite($php_fileCrons, '    private $period = [\'min\' => [0], \'hour\' => [1]];' . "\r\n");
            fwrite($php_fileCrons, '    const LOG_PATH = \'logs/' . $nomDossierCrons . '/\';' . "\r\n");
            fwrite($php_fileCrons, "\r\n");
            fwrite($php_fileCrons, '    //' . "\r\n");
            fwrite($php_fileCrons, '    private $monitoringLog;' . "\r\n");
            fwrite($php_fileCrons, "\r\n");
            fwrite($php_fileCrons, '    /**' . "\r\n");
            fwrite($php_fileCrons, '     * Le cron est actif?' . "\r\n");
            fwrite($php_fileCrons, '     *' . "\r\n");
            fwrite($php_fileCrons, '     * -Pendant le développement il est préférable de lancer le cron manuellement, initialisez la valeur sur false' . "\r\n");
            fwrite($php_fileCrons, '     * -En production, initialiser la variable à true pour que la tâche soit automatiquement executée' . "\r\n");
            fwrite($php_fileCrons, '     */' . "\r\n");
            fwrite($php_fileCrons, '     private $enabled = false;' . "\r\n");
            fwrite($php_fileCrons, "\r\n");
            fwrite($php_fileCrons, '    /**' . "\r\n");
            fwrite($php_fileCrons, '     * Constructeur' . "\r\n");
            fwrite($php_fileCrons, '     */' . "\r\n");
            fwrite($php_fileCrons, '     public function __construct() {}' . "\r\n");
            fwrite($php_fileCrons, "\r\n");
            fwrite($php_fileCrons, '    /**' . "\r\n");
            fwrite($php_fileCrons, '     * Retourne la fréquence d\'exécution de la tâche' . "\r\n");
            fwrite($php_fileCrons, '     *' . "\r\n");
            fwrite($php_fileCrons, '     *@return array' . "\r\n");
            fwrite($php_fileCrons, '     */' . "\r\n");
            fwrite($php_fileCrons, '     public function getPeriod()' . "\r\n");
            fwrite($php_fileCrons, '     {' . "\r\n");
            fwrite($php_fileCrons, '        return $this->period;' . "\r\n");
            fwrite($php_fileCrons, '     }' . "\r\n");
            fwrite($php_fileCrons, "\r\n");
            fwrite($php_fileCrons, '    /**' . "\r\n");
            fwrite($php_fileCrons, '     * Retourne l\'état d\'activation de la tâche' . "\r\n");
            fwrite($php_fileCrons, '     *' . "\r\n");
            fwrite($php_fileCrons, '     * @return bool' . "\r\n");
            fwrite($php_fileCrons, '     */' . "\r\n");
            fwrite($php_fileCrons, '     public function isEnabled()' . "\r\n");
            fwrite($php_fileCrons, '     {' . "\r\n");
            fwrite($php_fileCrons, '        return $this->enabled;' . "\r\n");
            fwrite($php_fileCrons, '     }' . "\r\n");
            fwrite($php_fileCrons, "\r\n");
            fwrite($php_fileCrons, '    /**' . "\r\n");
            fwrite($php_fileCrons, '     * Traitement à effectuer' . "\r\n");
            fwrite($php_fileCrons, '     *' . "\r\n");
            fwrite($php_fileCrons, '     * @return null' . "\r\n");
            fwrite($php_fileCrons, '     */' . "\r\n");
            fwrite($php_fileCrons, '     public function start()' . "\r\n");
            fwrite($php_fileCrons, '     {' . "\r\n");
            fwrite($php_fileCrons, '        $this->monitoringLog = new MonitoringLog();' . "\r\n");
            fwrite($php_fileCrons, '        // Exemple des données à afficher' . "\r\n");
            fwrite($php_fileCrons, '        $this->monitoringLog->setService(\'Test cron\');' . "\r\n");
            fwrite($php_fileCrons, '        // Exemple type de tâche executé par le cron' . "\r\n");
            fwrite($php_fileCrons, '        $this->monitoringLog->setType(\'import\');' . "\r\n");
            fwrite($php_fileCrons, '        $this->monitoringLog->beforeProcess();' . "\r\n");
            fwrite($php_fileCrons, '        // Traitement ....' . "\r\n");
            fwrite($php_fileCrons, '        $messageLogSummary = $this->process();' . "\r\n");
            fwrite($php_fileCrons, '        // Les données qui seront écrites dans le fichier' . "\r\n");
            fwrite($php_fileCrons, '        $this->monitoringLog->writeLog(' . "\r\n");
            fwrite($php_fileCrons, '        true,' . "\r\n");
            fwrite($php_fileCrons, '        $messageLogSummary' . "\r\n");
            fwrite($php_fileCrons, '        );' . "\r\n");
            fwrite($php_fileCrons, '      }' . "\r\n");
            fwrite($php_fileCrons, "\r\n");
            fwrite($php_fileCrons, '     /**' . "\r\n");
            fwrite($php_fileCrons, '      * Process' . "\r\n");
            fwrite($php_fileCrons, '      *' . "\r\n");
            fwrite($php_fileCrons, '      * @return string' . "\r\n");
            fwrite($php_fileCrons, '      */' . "\r\n");
            fwrite($php_fileCrons, '      public function process()' . "\r\n");
            fwrite($php_fileCrons, '      {' . "\r\n");
            fwrite($php_fileCrons, '          // Traitement ....' . "\r\n");
            fwrite($php_fileCrons, '          return \'Message à afficher sur le CRUD monitoring\';' . "\r\n");
            fwrite($php_fileCrons, '       }' . "\r\n");
            fwrite($php_fileCrons, "\r\n");
            fwrite($php_fileCrons, '     /**' . "\r\n");
            fwrite($php_fileCrons, '      * Gestion d\'un rapport sur l\'exécution de la tâche' . "\r\n");
            fwrite($php_fileCrons, '      *  - si elle est lancée manuellement alors le message sera affiché à l\'écran' . "\r\n");
            fwrite($php_fileCrons, '      *  - si ele est exécutée automatiquement alrors le message sera envoyé par email' . "\r\n");
            fwrite($php_fileCrons, '      *' . "\r\n");
            fwrite($php_fileCrons, '      * @param mixed données à afficher ou à envoyer dans le mail' . "\r\n");
            fwrite($php_fileCrons, '      * @param string adresse email du destinaire' . "\r\n");
            fwrite($php_fileCrons, '      *' . "\r\n");
            fwrite($php_fileCrons, '      * @return null' . "\r\n");
            fwrite($php_fileCrons, '      */' . "\r\n");
            fwrite($php_fileCrons, '      private function log($datas, $email = \'prestation@medialibs.com\')' . "\r\n");
            fwrite($php_fileCrons, '      {' . "\r\n");
            fwrite($php_fileCrons, '          //\cron_tools::dump($datas, $email);' . "\r\n");
            fwrite($php_fileCrons, '      }' . "\r\n");
            fwrite($php_fileCrons, '    }' . "\r\n");
        }
        fclose($php_fileCrons);


        //fichier emajine_menu_monitoring.xml qui se trouve dans le dossier specifs\nom de l'identifiant de l'add-on\class\menus\monitoring via la checkbox crons
        //On crée un DomDocument
        $emajineMenuMonitoring = new DOMDocument("1.0", "UTF-8");
        //Le format du fichier Xml est correct
        $emajineMenuMonitoring->formatOutput = true;

        //on crée la balise parent <emajine_specif>
        $emajine_specif = $emajineMenuMonitoring->createElement('emajine_specif');
        //on ferme la balise parent </emajine_specif>
        $emajineMenuMonitoring->appendChild($emajine_specif);

        //on crée la balise enfant <libelle> et on lui ajoute le nom de l'add_on inserer dans le formulaire
        $libelle = $emajineMenuMonitoring->createElement('libelle', 'Monitoring');
        //on créé la balise fermante </libelle> de la balise parent <emajine_specif>
        $emajine_specif->appendChild($libelle);

        //on crée la balise enfant <libelle> et on lui ajoute le nom de l'add_on inserer dans le formulaire
        $parent = $emajineMenuMonitoring->createElement('parent', '43');
        //on créé la balise fermante </libelle> de la balise parent <emajine_specif>
        $emajine_specif->appendChild($parent);

        //on crée la balise enfant <script> et on lui ajoute le nom de l'add_on inserer dans le formulaire
        $script = $emajineMenuMonitoring->createElement('script', 'addons/' . $identifiant . '/class/menus/monitoring/menuMonitoring.class.php');
        //on créé la balise fermante </libelle> de la balise parent <emajine_specif>
        $emajine_specif->appendChild($script);

        $emajineMenuMonitoring->save('emajine_menu_monitoring.xml');


        //fichier "menu_monitoring.php" qui se trouve dans le dossier specifs\nom de l'identifiant de l'add-on\class\menus\monitoring avec la checkbox "crons"
        $fileMenuMonitoring = 'menuMonitoring.class.php';
        $php_fileMenuMonitoring = fopen($fileMenuMonitoring, 'w+');
        if (filesize($fileMenuMonitoring) > 0) {
            $contents = fread($php_fileMenuMonitoring, filesize($fileMenuMonitoring));
        }

        fwrite($php_fileMenuMonitoring, '<?php' . "\r\n");
        fwrite($php_fileMenuMonitoring, 'namespace Addon\\' . $identifiant . ';' . "\r\n");
        fwrite($php_fileMenuMonitoring, "\r\n");
        fwrite($php_fileMenuMonitoring, 'require_once \em_misc::getSpecifPath() . \'addons/' . $identifiant . '/class/tools/libs/cruds/Monitoring_CRUD.class.php\';' . "\r\n");
        fwrite($php_fileMenuMonitoring, "\r\n");
        fwrite($php_fileMenuMonitoring, '/**' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' * monitoring' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' *' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' * Ecran de contrôle des tâches plannifiées' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' *' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' * @author Robson <robson@medialibs.com>' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' *' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' * @since      2020-03-16' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' */' . "\r\n");
        fwrite($php_fileMenuMonitoring, 'class menuMonitoring' . "\r\n");
        fwrite($php_fileMenuMonitoring, '{' . "\r\n");
        fwrite($php_fileMenuMonitoring, "\r\n");
        fwrite($php_fileMenuMonitoring, '/**' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' * Constructeur' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' */' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' public function __construct() {}' . "\r\n");
        fwrite($php_fileMenuMonitoring, "\r\n");
        fwrite($php_fileMenuMonitoring, '/**' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' * Gestion de la section' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' *' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' * Par exemple, si la méthode start retourne "Hello", la section affichera "Hello"' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' *' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' * @return string' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' */' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' public function start()' . "\r\n");
        fwrite($php_fileMenuMonitoring, ' {' . "\r\n");
        fwrite($php_fileMenuMonitoring, '     $crudObject = new Monitoring_CRUD();' . "\r\n");
        fwrite($php_fileMenuMonitoring, '     $output     = $crudObject->start();' . "\r\n");
        fwrite($php_fileMenuMonitoring, "\r\n");
        fwrite($php_fileMenuMonitoring, '     return $output;' . "\r\n");
        fwrite($php_fileMenuMonitoring, '  }' . "\r\n");
        fwrite($php_fileMenuMonitoring, '}' . "\r\n");
        fclose($php_fileMenuMonitoring);


        //fichiers portant le nom du "l'identifiant de la balise mx" dans la checkbox "mxtags" qui se trouve dans le dossier specifs\addons\identifiant de l'add-on\class\mxTags\identifiant de mxTag
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        //On récupére la derniére ligne inserer dans la table generateur
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');

        $fileMxTags = 'mxtag_' . $identifiantMxTags . '.php';
        $fileMxTags1 = $identifiantMxTags . '.class.php';

        $php_fileMxTags = fopen($fileMxTags, 'w+');
        if (filesize($fileMxTags) > 0) {
            $contents = fread($php_fileMxTags, filesize($fileMxTags));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileMxTags, '<?php' . "\r\n");
            fwrite($php_fileMxTags, 'namespace Addon\\' . $identifiant . ';' . "\r\n");
            fwrite($php_fileMxTags, "\r\n");
            fwrite($php_fileMxTags, '/**' . "\r\n");
            fwrite($php_fileMxTags, ' * Gestion de la balise MX "' . $descriptionBaliseMxTags . '"' . "\r\n");
            fwrite($php_fileMxTags, ' *' . "\r\n");
            fwrite($php_fileMxTags, ' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>' . "\r\n");
            fwrite($php_fileMxTags, ' *' . "\r\n");
            fwrite($php_fileMxTags, ' * @since ' . $creation['date_de_creation'] . "\r\n");
            fwrite($php_fileMxTags, ' */' . "\r\n");
            fwrite($php_fileMxTags, ' function getSpecifTag' . ucfirst($identifiantMxTags) . '($value)' . "\r\n");
            fwrite($php_fileMxTags, ' {' . "\r\n");
            fwrite($php_fileMxTags, '     $objectName = \'Addon\\\\' . $identifiant . '\\\\mx' . ucfirst($identifiantMxTags) . '\';' . "\r\n");
            fwrite($php_fileMxTags, '     \em_misc::loadPHP(__DIR__ . \'/' . $fileMxTags1 . '\', $objectName);' . "\r\n");
            fwrite($php_fileMxTags, '     $obj = new $objectName();' . "\r\n");
            fwrite($php_fileMxTags, '     preg_match_all(\'/([a-z]*)="([^"]*)"/\', $value[2], $matches, PREG_SET_ORDER);' . "\r\n");
            fwrite($php_fileMxTags, '     foreach ($matches as $params) {' . "\r\n");
            fwrite($php_fileMxTags, '         $obj->setTagParams($params[1], $params[2]);' . "\r\n");
            fwrite($php_fileMxTags, '     }' . "\r\n");
            fwrite($php_fileMxTags, '     $obj->setDevPath($path);' . "\r\n");
            fwrite($php_fileMxTags, '     return $obj->start();' . "\r\n");
            fwrite($php_fileMxTags, ' }' . "\r\n");
        }
        fclose($php_fileMxTags);


        //fichier portant le nom de "l'identifiant de la balise mx.class.php" venant de la checkbox "mxtags" qui se trouve dans le dossier specifs\addons\identifiant de l'add-on\class\mxTags\identifiant de mxTag\
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        //On récupére la derniére ligne inserer dans la table generateur
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');

        $fileMxTags1 = $identifiantMxTags . '.class.php';
        $php_fileMxTags1 = fopen($fileMxTags1, 'w');
        if (filesize($fileMxTags1) > 0) {
            $contents = fread($php_fileMxTags1, filesize($fileMxTags1));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileMxTags1, '<?php' . "\r\n");
            fwrite($php_fileMxTags1, 'namespace Addon\\' . $identifiant . ';' . "\r\n");
            fwrite($php_fileMxTags1, "\r\n");
            fwrite($php_fileMxTags1, '/**' . "\r\n");
            fwrite($php_fileMxTags1, ' * Gestion de la balise MX "' . $descriptionBaliseMxTags . '"' . "\r\n");
            fwrite($php_fileMxTags1, ' *' . "\r\n");
            fwrite($php_fileMxTags1, ' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>' . "\r\n");
            fwrite($php_fileMxTags1, ' *' . "\r\n");
            fwrite($php_fileMxTags1, ' * @since ' . $creation['date_de_creation'] . "\r\n");
            fwrite($php_fileMxTags1, ' */' . "\r\n");
            fwrite($php_fileMxTags1, ' class mx' . ucfirst($identifiantMxTags) . '' . "\r\n");
            fwrite($php_fileMxTags1, ' {' . "\r\n");
            fwrite($php_fileMxTags1, '     /**' . "\r\n");
            fwrite($php_fileMxTags1, '      * Constructeur' . "\r\n");
            fwrite($php_fileMxTags1, '      */' . "\r\n");
            fwrite($php_fileMxTags1, '      public function __construct()' . "\r\n");
            fwrite($php_fileMxTags1, '      {}' . "\r\n");
            fwrite($php_fileMxTags1, '      /**' . "\r\n");
            fwrite($php_fileMxTags1, '       * Initialisation de l\'emplacement du dossier specifs' . "\r\n");
            fwrite($php_fileMxTags1, '       *' . "\r\n");
            fwrite($php_fileMxTags1, '       * @param string $path Emplacement des développements' . "\r\n");
            fwrite($php_fileMxTags1, '       *' . "\r\n");
            fwrite($php_fileMxTags1, '       * @return null' . "\r\n");
            fwrite($php_fileMxTags1, '       */' . "\r\n");
            fwrite($php_fileMxTags1, '       public function setDevPath($path)' . "\r\n");
            fwrite($php_fileMxTags1, '       {' . "\r\n");
            fwrite($php_fileMxTags1, '           $this->devPath = $path;' . "\r\n");
            fwrite($php_fileMxTags1, '       }' . "\r\n");
            fwrite($php_fileMxTags1, '       /**' . "\r\n");
            fwrite($php_fileMxTags1, '        * Récupération de l\'emplacement du dossier specifs' . "\r\n");
            fwrite($php_fileMxTags1, '        *' . "\r\n");
            fwrite($php_fileMxTags1, '        * @return string' . "\r\n");
            fwrite($php_fileMxTags1, '        */' . "\r\n");
            fwrite($php_fileMxTags1, '        public function getDevPath()' . "\r\n");
            fwrite($php_fileMxTags1, '        {' . "\r\n");
            fwrite($php_fileMxTags1, '            return $this->devPath;' . "\r\n");
            fwrite($php_fileMxTags1, '        }' . "\r\n");
            fwrite($php_fileMxTags1, '        /**' . "\r\n");
            fwrite($php_fileMxTags1, '         * Initialisation des attribus de la balise spécifique' . "\r\n");
            fwrite($php_fileMxTags1, '         *' . "\r\n");
            fwrite($php_fileMxTags1, '         * @param string $attributName Nom de l\'attribut' . "\r\n");
            fwrite($php_fileMxTags1, '         * @param string $attributValue Valeur de l\'attribut' . "\r\n");
            fwrite($php_fileMxTags1, '         *' . "\r\n");
            fwrite($php_fileMxTags1, '         * @return null' . "\r\n");
            fwrite($php_fileMxTags1, '         */' . "\r\n");
            fwrite($php_fileMxTags1, '         public function setTagParams($attributName, $attributValue)' . "\r\n");
            fwrite($php_fileMxTags1, '         {' . "\r\n");
            fwrite($php_fileMxTags1, '              $varname = \'_tagParam\' . ucFirst($attributName);' . "\r\n");
            fwrite($php_fileMxTags1, '              $this->$varname = $attributValue;' . "\r\n");
            fwrite($php_fileMxTags1, '         }' . "\r\n");
            fwrite($php_fileMxTags1, '         /**' . "\r\n");
            fwrite($php_fileMxTags1, '          * Récupération de la valeur définie pour un attribut de la balise' . "\r\n");
            fwrite($php_fileMxTags1, '          *' . "\r\n");
            fwrite($php_fileMxTags1, '          * @param string $attributName Nom de l\'attribut' . "\r\n");
            fwrite($php_fileMxTags1, '          *' . "\r\n");
            fwrite($php_fileMxTags1, '          * @return string' . "\r\n");
            fwrite($php_fileMxTags1, '          */' . "\r\n");
            fwrite($php_fileMxTags1, '          public function getTagParam($attributName)' . "\r\n");
            fwrite($php_fileMxTags1, '          {' . "\r\n");
            fwrite($php_fileMxTags1, '              $varname = \'_tagParam\' . ucFirst($attributName);' . "\r\n");
            fwrite($php_fileMxTags1, '              if (isset($this->$varname)) {' . "\r\n");
            fwrite($php_fileMxTags1, '                  return $this->$varname;' . "\r\n");
            fwrite($php_fileMxTags1, '              }' . "\r\n");
            fwrite($php_fileMxTags1, '              return \'\';' . "\r\n");
            fwrite($php_fileMxTags1, '          }' . "\r\n");
            fwrite($php_fileMxTags1, '          /**' . "\r\n");
            fwrite($php_fileMxTags1, '           * Gestion de la balise. La méthode retourne le contenu de celle-ci.' . "\r\n");
            fwrite($php_fileMxTags1, '           *' . "\r\n");
            fwrite($php_fileMxTags1, '           * Si start retourne \'test\' alors la balise prendra comme valeur \'test\'' . "\r\n");
            fwrite($php_fileMxTags1, '           *' . "\r\n");
            fwrite($php_fileMxTags1, '           * @return string' . "\r\n");
            fwrite($php_fileMxTags1, '           */' . "\r\n");
            fwrite($php_fileMxTags1, '           public function start()' . "\r\n");
            fwrite($php_fileMxTags1, '           {' . "\r\n");
            fwrite($php_fileMxTags1, '               return \'Hello world\';' . "\r\n");
            fwrite($php_fileMxTags1, '           }' . "\r\n");
            fwrite($php_fileMxTags1, ' }' . "\r\n");
        }
        fclose($php_fileMxTags1);

        //fichier "methodManage portant le nom de "l'identifiant de la "méthode de publication.class.php" venant de la checkbox "publication_methods" qui se trouve dans le dossier specifs\addons\identifiant de l'add-on\class\publication_methods\test4
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        //On récupére la derniére ligne inserer dans la table generateur
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');

        $fileMethodePublication = 'methodeManage' . $identifiantmethodPublication . '.class.php';
        $php_fileMethodePublication = fopen($fileMethodePublication, 'w+');
        if (filesize($fileMethodePublication) > 0) {
            $contents = fread($php_fileMethodePublication, filesize($fileMethodePublication));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileMethodePublication, '<?php' . "\r\n");
            fwrite($php_fileMethodePublication, 'namespace Addon\\' . $identifiant . ';');
            fwrite($php_fileMethodePublication, "\r\n");
            fwrite($php_fileMethodePublication, "\r\n");
            fwrite($php_fileMethodePublication, '/**' . "\r\n");
            fwrite($php_fileMethodePublication, ' * Configuration du type de page"' . $nomMethodePublication . '"' . "\r\n");
            fwrite($php_fileMethodePublication, ' *' . "\r\n");
            fwrite($php_fileMethodePublication, ' *' . "\r\n");
            fwrite($php_fileMethodePublication, ' *' . "\r\n");
            fwrite($php_fileMethodePublication, ' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com> ' . "\r\n");
            fwrite($php_fileMethodePublication, ' *' . "\r\n");
            fwrite($php_fileMethodePublication, ' * @since ' . $creation['date_de_creation'] . "\r\n");
            fwrite($php_fileMethodePublication, ' */' . "\r\n");
            fwrite($php_fileMethodePublication, 'class ' . $identifiantmethodPublication . "\r\n");
            fwrite($php_fileMethodePublication, '{' . "\r\n");
            fwrite($php_fileMethodePublication, '   /**' . "\r\n");
            fwrite($php_fileMethodePublication, '    * Constructeur' . "\r\n");
            fwrite($php_fileMethodePublication, '    */' . "\r\n");
            fwrite($php_fileMethodePublication, '    public function __construct()' . "\r\n");
            fwrite($php_fileMethodePublication, '    {' . "\r\n");
            fwrite($php_fileMethodePublication, '    }' . "\r\n");
            fwrite($php_fileMethodePublication, "\r\n");
            fwrite($php_fileMethodePublication, '   /**' . "\r\n");
            fwrite($php_fileMethodePublication, '    * Retourne la description de la rubrique' . "\r\n");
            fwrite($php_fileMethodePublication, '    *' . "\r\n");
            fwrite($php_fileMethodePublication, '    * @return string' . "\r\n");
            fwrite($php_fileMethodePublication, '    */' . "\r\n");
            fwrite($php_fileMethodePublication, '    public function getDescription()' . "\r\n");
            fwrite($php_fileMethodePublication, '    {' . "\r\n");
            fwrite($php_fileMethodePublication, '        return \'\';' . "\r\n");
            fwrite($php_fileMethodePublication, '    }' . "\r\n");
            fwrite($php_fileMethodePublication, "\r\n");
            fwrite($php_fileMethodePublication, '    /**' . "\r\n");
            fwrite($php_fileMethodePublication, '     * Gestion de la configuration' . "\r\n");
            fwrite($php_fileMethodePublication, '     *' . "\r\n");
            fwrite($php_fileMethodePublication, '     * @return string' . "\r\n");
            fwrite($php_fileMethodePublication, '     */' . "\r\n");
            fwrite($php_fileMethodePublication, '     public function start()' . "\r\n");
            fwrite($php_fileMethodePublication, '     {' . "\r\n");
            fwrite($php_fileMethodePublication, '         require_once \em_misc::getClassPath() . \'/core/Emajine_Specif/Emajine_Specif_PublicationMethod.class.php\';' . "\r\n");
            fwrite($php_fileMethodePublication, "\r\n");
            fwrite($php_fileMethodePublication, '         $form = new \Emajine_Form();' . "\r\n");
            fwrite($php_fileMethodePublication, '         $form->addElement(\Emajine_Form::elementFactory(\'text\', \'Titre\', \'label\'));' . "\r\n");
            fwrite($php_fileMethodePublication, '         $obj = new \Emajine_Specif_PublicationMethod($form);' . "\r\n");
            fwrite($php_fileMethodePublication, '         return $obj->start();' . "\r\n");
            fwrite($php_fileMethodePublication, '     }' . "\r\n");
            fwrite($php_fileMethodePublication, '}' . "\r\n");
        }
        fclose($php_fileMethodePublication);

        //fichier "methodPublic portant le nom de "l'identifiant de la méthode de publication.class.php"  venant de la checkbox "publication_methods" qui se trouve dans le dossier specifs\addons\identifiant de l'add-on\class\publication_methods\test4
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileMethodePublicationCrud = 'methodePublic' . $identifiantmethodPublication . '.class.php';
        $php_fileMethodePublicationCrud = fopen($fileMethodePublicationCrud, 'w+');
        if (filesize($fileMethodePublicationCrud) > 0) {
            $contents = fread($php_fileMethodePublicationCrud, filesize($fileMethodePublicationCrud));
        }
        if ($choixPublicationMethode == 'crudFront') {

            //fichier methodManage portant le nom de l'identifiant de la méthode de publication.class.php venant de la checkbox publication_methods via la ChoiceType CRUDFront


            while ($creation = $datas->fetch()) {
                fwrite($php_fileMethodePublicationCrud, '<?php' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, 'namespace Addon\\' . $identifiant . ';' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, 'require_once \em_misc::getSpecifPath() . \'addons/' . $identifiant . '/class/tools/Manager.class.php\';' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '/**' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' * Gestion du type de page "' . $nomMethodePublication . '"' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com> ' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' * @since ' . $creation['date_de_creation'] . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '*/'."\r\n");
                fwrite($php_fileMethodePublicationCrud, 'class methodPublic' . $identifiantmethodPublication . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '{' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '     private $beginList;' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '     private $title;' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '     const UPDATE_STRING = \'update\';' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '     const SAVE_STRING   = \'add\';' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '     const NB_PAR_PAGE   = 50;');
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '     /**' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * Constructeur' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * Vous aurez autant d\'argument à l\'appel de cette méthode que de champs défini lors de la configuration.' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * Ainsi, par exemple, si vous définissez un nom et un nombre d\'élément à afficher, vous pourrez récupérer' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * ces éléments via la définition suivante :' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * public function __construct($title = false, $nbElements = 10)' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      */' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      public function __construct($title = false)' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      {' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          $this->title = $title;' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      }' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '     /**' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * Gestion et affichage du contenu' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * @return string' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      */' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '     public function start()' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '     {' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         // Récupere le template contenant le CRUD , veuillez copier le fichier crudList.html dans cette répertoire(répertoire à créer)' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         $mx = \em_mx::initMx(\em_misc::getPublicTemplatePath(\'specifs/addons/' . $identifiant . '/publication/crudList.html\'));' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         // Traitement ...' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         $this->initListMx($mx);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         // Traitement...........' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '        // Affichage du formulaire' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '        $this->initFormMx($mx, $idActor);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '        return \em_mx::write($mx);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '     }' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '     /**' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * Initialise la liste $mx' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * @param Modelixe $mx' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * @return string' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      */' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      public static function initListMx($mx)' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      {' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          // Template pour les recherches' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          $mxSearch = \em_mx::initMx(\em_misc::getPublicTemplatePath(\'specifs/addons/' . $identifiant . '/publication/search.html\'));' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          $this->initFormSearchMx($mxSearch);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         // Traitement ....' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         \em_mx::text($mx, \'searchBloc.search\', \em_mx::write($mxSearch));' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         // Affichage de la pagination à calculer les nombres des données à afficher et les nombres des pages' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         $this->initPagination($mx, $nbTotalOfData, $page);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         $this->beginList = ($page - 1) * self::NB_PAR_PAGE;' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         // Initialiser le CRUD à afficher' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         $datas = Manager::getData($isListMember, self::NB_PAR_PAGE, $this->beginList);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         // Remplir le template (exemple)' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         foreach ($datas as $value) {' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            \em_mx::text($mx, \'members.member.name\', $value[\'nom\']);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            \em_mx::text($mx, \'members.member.prenom\', $value[\'prenom\']);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            \em_mx::text($mx, \'members.member.login\', $value[\'login\']);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            \em_mx::text($mx, \'members.member.mail\', $value[\'mail\']);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            \em_mx::text($mx, \'members.member.date_crea\', $value[\'dateCrea\']);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         }' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '         // Traitement' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      }' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      /**' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       * Initialise le formulaire $mx' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       * @param Modelixe $mx' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       * @param int $idActor' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       * @return null' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       */' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       private function initFormMx($mx, $idActor)' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       {' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            $nameVal     = "";' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            $prenomVal   = "";' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            $loginVal    = "";' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            $mailVal     = "";' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            $submitValue = self::SAVE_STRING;' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '             if (0 != $idActor) {' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '                 $actor       = $this->getUserById($idActor);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '                 $nameVal     = stripslashes($actor[\'nom\']);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '                 $prenomVal   = stripslashes($actor[\'prenom\']);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '                 $mailVal     = $actor[\'mail\'];' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '                 $submitValue = self::UPDATE_STRING;' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '             }' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '             $form = \Emajine_API::form();' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '             $form->addElement(\'fieldset\', \'Membre\');' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '             $form->addElement(\'text\', \'nom\', \'Nom\', [\'value\' => $nameVal], true);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '             $form->addElement(\'text\', \'prenom\', \'Prenom\', [\'value\' => $prenomVal], true);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '             $form->addElement(\'text\', \'login\', \'Login\', [\'value\' => $loginVal], true);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '             $form->addElement(\'text\', \'mail\', \'Mail\', [\'value\' => $mailVal], true);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '             $form->addElement(\'submit\', $submitValue, \'Enregistrer\');' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '             if ($form->validate()) {' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '                 // Traitement, insertion ou sauvegarde après validation du formulaire' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '             }' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      }' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '     /**' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * Initialiser la pagination pour la gestion des membres' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * @param Modelixe  $mx' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * @param int         $nbTotalOfData' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * @param int         $page' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      * @return null' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      */' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      private function initPagination($mx, $nbTotalOfData, $page)' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      {' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          if (0 == $nbTotalOfData) {' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '              \em_mx::delete($mx, \'pager\');' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          }' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          require_once \em_misc::getClassPath() . \'/core/Emajine_API/Emajine_Pager.class.php\';' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          $pager = new \em_pager();' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          $pager->setMxPrefix(\'pager\');' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          $pager->setNbItems($nbTotalOfData);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          $pager->setNbItemsPerPage(self::NB_PAR_PAGE);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          $pager->setCurrentPage($page);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          $pager->setUrlMask(\'page-{page}\');' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '          $pager->generate($mx);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      }' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      /**' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       * Initialise le formulaire de recherche' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       * @param Modelixe $mx' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       * @param boolean $searchKey' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       * @return null' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       */' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       private function initFormSearchMx($mx, $searchKey = false)' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '       {' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            if (!empty($searchKey)) {' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '                \em_mx::attr($mx, \'search.searchKey\', $searchKey);' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            }' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            \em_mx::attr($mx, \'search.idsearch\', \'input-filter\');' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            \em_mx::attr($mx, \'search.searchType\', \'search\');' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            \em_mx::attr($mx, \'search.searchName\', \'search\');' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            \em_mx::attr($mx, \'search.onchange\', \'search()\');' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            \em_mx::attr($mx, \'search.placeholder\', \'search\');' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            \em_mx::attr($mx, \'search.dataTable\', \'order-table\');' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            \em_mx::attr($mx, \'search.size\', \'15\');' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '            \em_misc::loadScript(\'/scripts/filter.js\');' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '      }' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, '}' . "\r\n");

            }
        } else {


            //fichier methodManage portant le nom de l'identifiant de la méthode de publication.class.php venant de la checkbox publication_methods via le ChoiceType Aucune



            while ($creation = $datas->fetch()) {
                fwrite($php_fileMethodePublicationCrud, '<?php' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, 'namespace Addon\\' . $identifiant . ';' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '/**' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' * Gestion du type de page "' . $nomMethodePublication . '"' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com> ' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' *' . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' * @since' . $creation['date_de_creation'] . "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' */'."\r\n");
                fwrite($php_fileMethodePublicationCrud, ' class methoPublic'.$identifiant."\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' {'."\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, '     // Titre de la méthode de publication'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '     private $title = \'\';'."\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' /**'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  * Constructeur'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  *'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  * Vous aurez autant d\'argument à l\'appel de cette méthode que de champs défini lors de la configuration.'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  * Ainsi, par exemple, si vous définissez un nom et un nombre d\'élément à afficher, vous pourrez récupérer'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  * ces éléments via la définition suivante :'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  * public function __construct($title = false, $nbElements = 10)'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  */');
                fwrite($php_fileMethodePublicationCrud, '  public function __construct($title = false)'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  {'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '       $this->title = $title;'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  }'."\r\n");
                fwrite($php_fileMethodePublicationCrud, "\r\n");
                fwrite($php_fileMethodePublicationCrud, ' /**'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  * Gestion et affichage du contenu'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  *'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  * @return string'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  */'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  public function start()'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  {');
                fwrite($php_fileMethodePublicationCrud, '      return $this->title;'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '  }'."\r\n");
                fwrite($php_fileMethodePublicationCrud, '}'."\r\n");

            }
            fclose($php_fileMethodePublicationCrud);
        }

        //fichier "method.xml" qui se trouve dans le dossier publication_methods et dans le dossier portant le nom de l'identifiant de la méthode de publication du formulaire via la checkbox publication_methods
        //On crée un DomDocument
        $publicationMethode = new DOMDocument("1.0", "UTF-8");
        //Le format du fichier Xml est correct
        $publicationMethode->formatOutput= true;

            //on crée la balise parent <method>
            $method = $publicationMethode->createElement('method');
            //on ferme la balise parent </method>
            $publicationMethode->appendChild($method);


            //on crée la balise enfant <congig>
            $config = $publicationMethode->createElement('config');
            //on créé la balise fermante </config> de la balise parent <method>
            $method->appendChild($config);

            //on crée la balise enfant <name> et on lui ajoute le nom de la methode de publication du formulaire
            $namePublicTest = $publicationMethode->createElement('name', $nomMethodePublication);
            //on créé la balise fermante </name> de la balise parent <config>
            $config->appendChild($namePublicTest);

            //on crée la balise enfant <methodSet> et on lui ajoute _specifiq_
            $methodSet = $publicationMethode->createElement('methodset', '_specifique_');
            //on créé la balise fermante </methodSet> de la balise parent <config>
            $config->appendChild($methodSet);

            //on crée la balise enfant <scriptmanage> et on lui ajoute le chemin du fichier methodeManage.class.php
            $scriptManage = $publicationMethode->createElement('scriptmanage', $identifiantmethodPublication . '/' . $fileMethodePublication);
            //on créé la balise fermante </scriptmanage> de la balise parent <config>
            $config->appendChild($scriptManage);

            //on crée la balise enfant <scriptPublic> et on lui ajoute le chemin du fichier methodePublic.class.php
            $scriptPublic = $publicationMethode->createElement('scriptpublic', $identifiantmethodPublication . '/' . $fileMethodePublicationCrud);
            //on créé la balise fermante </scriptpublic> de la balise parent <config>
            $config->appendChild($scriptPublic);


            $publicationMethode->save('method_'.$identifiantmethodPublication.'.xml');

        //fichier Manager.class.php qui se trouve dans le dossier \specifs\addons\test2\class\tools avec la checkbox publication_methods
        $fileManager = 'Manager.class.php';
        $php_fileManager = fopen($fileManager, 'w+');
        if (filesize($fileManager) > 0) {
            $contents = fread($php_fileManager, filesize($fileManager));
        }
          fwrite($php_fileManager,'<?php'."\r\n");
          fwrite($php_fileManager,'namespace Addon\#NOM_ADDON#;'."\r\n");
          fwrite($php_fileManager,'require_once \em_misc::getSpecifPath() . \'addons/#NOM_ADDON#/class/tools/crud/MonCrud.class.php\';'."\r\n");
          fwrite($php_fileManager,"\r\n");
          fwrite($php_fileManager,'/**'."\r\n");
          fwrite($php_fileManager,' * Manager'."\r\n");
          fwrite($php_fileManager,' *'."\r\n");
          fwrite($php_fileManager,' * @since #DATE#'."\r\n");
          fwrite($php_fileManager,' */'."\r\n");
          fwrite($php_fileManager,' class Manager'."\r\n");
          fwrite($php_fileManager,' {'."\r\n");
          fwrite($php_fileManager,'    /**'."\r\n");
          fwrite($php_fileManager,'     * Retourne les types distincts qui sont utilisés'."\r\n");
          fwrite($php_fileManager,'     *'."\r\n");
          fwrite($php_fileManager,'     * @param int $nbrePage'."\r\n");
          fwrite($php_fileManager,'     * @param int $beginList'."\r\n");
          fwrite($php_fileManager,'     * @param array $dataToSearch'."\r\n");
          fwrite($php_fileManager,'     *'."\r\n");
          fwrite($php_fileManager,'     * @return array'."\r\n");
          fwrite($php_fileManager,'     */'."\r\n");
          fwrite($php_fileManager,'     public static function getData($isListMember = false, $nbrePage = null, $beginList = null, $dataToSearch = null)'."\r\n");
          fwrite($php_fileManager,'     {'."\r\n");
          fwrite($php_fileManager,'         // Traitement ....'."\r\n");
          fwrite($php_fileManager,"\r\n");
          fwrite($php_fileManager,'         // Exemple'."\r\n");
          fwrite($php_fileManager,'         $query = \'SELECT id_acteur, nom, prenom ,login, mail, dateCrea FROM acteur\';'."\r\n");
          fwrite($php_fileManager,'         if (!empty($dataToSearch)) {'."\r\n");
          fwrite($php_fileManager,'             $data = ['."\r\n");
          fwrite($php_fileManager,'                \'nom LIKE \'%\' .$dataToSearch. \'%\'\','."\r\n");
          fwrite($php_fileManager,'                \'prenom LIKE \'%\' . $dataToSearch . \'%\'\','."\r\n");
          fwrite($php_fileManager,'                \'login LIKE \'%\' . $dataToSearch . \'%\'\','."\r\n");
          fwrite($php_fileManager,'                \'mail LIKE \'%\' . $dataToSearch . \'%\'\','."\r\n");
          fwrite($php_fileManager,'              ];'."\r\n");
          fwrite($php_fileManager,'              $query .= \' AND \' . implode(\' OR \', $data);'."\r\n");
          fwrite($php_fileManager,'         }'."\r\n");
          fwrite($php_fileManager,'         if ($isListMember) {'."\r\n");
          fwrite($php_fileManager,'             $query .= \' ORDER BY date_crea DESC LIMIT \' . $nbrePage . \' OFFSET \' . $beginList;'."\r\n");
          fwrite($php_fileManager,'         }'."\r\n");
          fwrite($php_fileManager,'         return \em_db::all($query);'."\r\n");
          fwrite($php_fileManager,'         }'."\r\n");
          fwrite($php_fileManager,"\r\n");
          fwrite($php_fileManager,'         /**'."\r\n");
          fwrite($php_fileManager,'          * Modification'."\r\n");
          fwrite($php_fileManager,'          *'."\r\n");
          fwrite($php_fileManager,'          * @param int $id'."\r\n");
          fwrite($php_fileManager,'          * @param array $infos'."\r\n");
          fwrite($php_fileManager,'          *'."\r\n");
          fwrite($php_fileManager,'          * @return null'."\r\n");
          fwrite($php_fileManager,'          */'."\r\n");
          fwrite($php_fileManager,'          public static function updateData($id, $infos)'."\r\n");
          fwrite($php_fileManager,'          {'."\r\n");
          fwrite($php_fileManager,'               // Traitement modification'."\r\n");
          fwrite($php_fileManager,'          }'."\r\n");
          fwrite($php_fileManager,"\r\n");
          fwrite($php_fileManager,'          /** Delete member'."\r\n");
          fwrite($php_fileManager,'           *'."\r\n");
          fwrite($php_fileManager,'           * @param int $id'."\r\n");
          fwrite($php_fileManager,'           *'."\r\n");
          fwrite($php_fileManager,'           * @return boolean'."\r\n");
          fwrite($php_fileManager,'           */');
          fwrite($php_fileManager,'           public static function deleteMember($id)'."\r\n");
          fwrite($php_fileManager,'           {'."\r\n");
          fwrite($php_fileManager,'               // Traitement suppression'."\r\n");
          fwrite($php_fileManager,'           }'."\r\n");
          fwrite($php_fileManager,"\r\n");
          fwrite($php_fileManager,'           /**'."\r\n");
          fwrite($php_fileManager,'            * Afficher la pagination'."\r\n");
          fwrite($php_fileManager,'            *'."\r\n");
          fwrite($php_fileManager,'            * @param      ModeliXe  $mx                 Objet modelixe'."\r\n");
          fwrite($php_fileManager,'            * @param      Int       $nbElements         The number of elements'."\r\n");
          fwrite($php_fileManager,'            * @param      int       $nbElementsPerPage  The number of elements per page'."\r\n");
          fwrite($php_fileManager,'            * @param      int       $currentPage        The current page'."\r\n");
          fwrite($php_fileManager,'            *'."\r\n");
          fwrite($php_fileManager,'            * @return     null'."\r\n");
          fwrite($php_fileManager,'            */'."\r\n");
          fwrite($php_fileManager,'            public static function setPager(&$mx, $nbElements, $nbElementsPerPage, $currentPage)'."\r\n");
          fwrite($php_fileManager,'            {'."\r\n");
          fwrite($php_fileManager,'                if (0 == $nbElements) {'."\r\n");
          fwrite($php_fileManager,'                    \em_mx::delete($mx, \'pager\');'."\r\n");
          fwrite($php_fileManager,'                    return;'."\r\n");
          fwrite($php_fileManager,'            }'."\r\n");
          fwrite($php_fileManager,'            $pager = new \em_pager();'."\r\n");
          fwrite($php_fileManager,'            $pager->setMxPrefix(\'pager\');');
          fwrite($php_fileManager,'            $pager->setNbItems($nbElements);'."\r\n");
          fwrite($php_fileManager,'            $pager->setNbItemsPerPage($nbElementsPerPage);'."\r\n");
          fwrite($php_fileManager,'            $pager->setCurrentPage($currentPage);'."\r\n");
          fwrite($php_fileManager,'            $pager->setUrlMask(\'page-{page}\');'."\r\n");
          fwrite($php_fileManager,'            $pager->generate($mx);'."\r\n");
          fwrite($php_fileManager,'            }'."\r\n");
          fwrite($php_fileManager,'}'."\r\n");
          fclose($php_fileManager);


        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');

        //fichier widgetManage qui se trouve dans le dossier widget via la checkbox Widgets
        $fileWidgetManage = 'widgetManage'.ucfirst($identifiantWidget).'.class.php';
        $php_fileWidgetManage = fopen($fileWidgetManage, 'w+');
        if (filesize($fileWidgetManage) > 0) {
            $contents = fread($php_fileWidgetManage, filesize($fileWidgetManage));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileWidgetManage, '<?php' . "\r\n");
            fwrite($php_fileWidgetManage, 'namespace Addon\\' . $identifiant . ';' . "\r\n");
            fwrite($php_fileWidgetManage, '/**' . "\r\n");
            fwrite($php_fileWidgetManage, ' * Gestion du widget "' .ucfirst($nomWidget) . '"' . "\r\n");
            fwrite($php_fileWidgetManage, ' *' . "\r\n");
            fwrite($php_fileWidgetManage, ' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>' . "\r\n");
            fwrite($php_fileWidgetManage, ' *' . "\r\n");
            fwrite($php_fileWidgetManage, ' * @since' . $creation['date_de_creation'] . "\r\n");
            fwrite($php_fileWidgetManage, ' */' . "\r\n");
            fwrite($php_fileWidgetManage, ' class widgetManage' . ucfirst($identifiantWidget) . "\r\n");
            fwrite($php_fileWidgetManage, ' {' . "\r\n");
            fwrite($php_fileWidgetManage, "\r\n");
            fwrite($php_fileWidgetManage, '    /**' . "\r\n");
            fwrite($php_fileWidgetManage, '     * Constructeur' . "\r\n");
            fwrite($php_fileWidgetManage, '     */' . "\r\n");
            fwrite($php_fileWidgetManage, '     public function __construct()' . "\r\n");
            fwrite($php_fileWidgetManage, '     {' . "\r\n");
            fwrite($php_fileWidgetManage, '     }' . "\r\n");
            fwrite($php_fileWidgetManage, "\r\n");
            fwrite($php_fileWidgetManage, '    /**' . "\r\n");
            fwrite($php_fileWidgetManage, '     * Gestion de la configuration' . "\r\n");
            fwrite($php_fileWidgetManage, '     *' . "\r\n");
            fwrite($php_fileWidgetManage, '     * @return null' . "\r\n");
            fwrite($php_fileWidgetManage, '     */' . "\r\n");
            fwrite($php_fileWidgetManage, '     public function start($form, $id)' . "\r\n");
            fwrite($php_fileWidgetManage, '     {' . "\r\n");
            fwrite($php_fileWidgetManage, '         $form->addElement(\'text\', \'title\', \'Titre\');' . "\r\n");
            fwrite($php_fileWidgetManage, '     }' . "\r\n");
            fwrite($php_fileWidgetManage, ' }' . "\r\n");
        }
        fclose($php_fileWidgetManage);


        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');

        //fichier widgetManage qui se trouve dans le dossier widget via la checkbox Widgets
        $fileWidgetTest = 'widget'.ucfirst($identifiantWidget).'.class.php';
        $php_fileWidgetTest = fopen($fileWidgetTest, 'w+');
        if (filesize($fileWidgetTest) > 0) {
            $contents = fread($php_fileWidgetTest, filesize($fileWidgetTest));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileWidgetTest, '<?php' . "\r\n");
            fwrite($php_fileWidgetTest, 'namespace Addon\\' . $identifiant . ';' . "\r\n");
            fwrite($php_fileWidgetTest,'/**'."\r\n");
            fwrite($php_fileWidgetTest,' * Gestion du widget "' .ucfirst($nomWidget) . '"' . "\r\n");
            fwrite($php_fileWidgetTest,' *'."\r\n");
            fwrite($php_fileWidgetTest,' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>' . "\r\n");
            fwrite($php_fileWidgetTest,' *'."\r\n");
            fwrite($php_fileWidgetTest,' */'."\r\n");
            fwrite($php_fileWidgetTest,' class widget'.ucfirst($identifiantWidget) ."\r\n");
            fwrite($php_fileWidgetTest,' {'."\r\n");
            fwrite($php_fileWidgetTest,"\r\n");
            fwrite($php_fileWidgetTest,'     // Variable contenant un contenu prévu pour s\'afficher dans la zone de contenu'."\r\n");
            fwrite($php_fileWidgetTest,'     private $contentZone = \'\';'."\r\n");
            fwrite($php_fileWidgetTest,'     private $title  = \'\';'."\r\n");
            fwrite($php_fileWidgetTest,"\r\n");
            fwrite($php_fileWidgetTest,'    /**'."\r\n");
            fwrite($php_fileWidgetTest,'     * Constructeur'."\r\n");
            fwrite($php_fileWidgetTest,'     */'."\r\n");
            fwrite($php_fileWidgetTest,'     function __construct($parent, $title)'."\r\n");
            fwrite($php_fileWidgetTest,'     {'."\r\n");
            fwrite($php_fileWidgetTest,'         $this->title = $title;'."\r\n");
            fwrite($php_fileWidgetTest,'     }'."\r\n");
            fwrite($php_fileWidgetTest,"\r\n");
            fwrite($php_fileWidgetTest,'    /**'."\r\n");
            fwrite($php_fileWidgetTest,'     * Gestion et affichage du widget'."\r\n");
            fwrite($php_fileWidgetTest,'     *'."\r\n");
            fwrite($php_fileWidgetTest,'     * @return string'."\r\n");
            fwrite($php_fileWidgetTest,'     */'."\r\n");
            fwrite($php_fileWidgetTest,'     public function start()'."\r\n");
            fwrite($php_fileWidgetTest,'     {'."\r\n");
            fwrite($php_fileWidgetTest,'         // Your code here'."\r\n");
            fwrite($php_fileWidgetTest,'     }'."\r\n");
            fwrite($php_fileWidgetTest,'}'."\r\n");
        }
        fclose($php_fileWidgetTest);







        //fichier method.xml qui se trouve dans le dossier publication_methods et dans le dossier portant le nom de l'identifiant de la méthode de publication du formulaire via la checkbox publication_methods
        //On crée un DomDocument
        $specifboxWidget = new DOMDocument("1.0", "UTF-8");
        //Le format du fichier Xml est correct
        $specifboxWidget->formatOutput= true;

        //on crée la balise parent <method>
        $methodWidget = $specifboxWidget->createElement('method');
        //on ferme la balise parent </method>
        $specifboxWidget->appendChild($methodWidget);


        //on crée la balise enfant <congig>
        $configWidget = $specifboxWidget->createElement('config');
        //on créé la balise fermante </config> de la balise parent <method>
        $methodWidget->appendChild($configWidget);

        //on crée la balise enfant <name> et on lui ajoute le nom de la methode de publication du formulaire
        $nameWidget = $specifboxWidget->createElement('name', $nomWidget);
        //on créé la balise fermante </name> de la balise parent <config>
        $configWidget->appendChild($nameWidget);

        //on crée la balise enfant <template> et on lui ajoute le nom du fichier html de widget_
        $templateWidget = $specifboxWidget->createElement('template', 'widget'.ucfirst($identifiantWidget).'.html');
        //on créé la balise fermante </methodSet> de la balise parent <config>
        $configWidget->appendChild($templateWidget);

        //on crée la balise enfant <scriptmanage> et on lui ajoute le chemin du fichier widgetManage.class.php
        $scriptManageWidget = $specifboxWidget->createElement('scriptmanage', $identifiantWidget . '/' . $fileWidgetManage);
        //on créé la balise fermante </scriptmanage> de la balise parent <config>
        $configWidget->appendChild($scriptManageWidget);


        //on crée la balise enfant <scriptPublic> et on lui ajoute le chemin du fichier methodePublic.class.php
        $scriptPublicWidget = $specifboxWidget->createElement('scriptpublic', $identifiantWidget . '/' .  $fileWidgetTest);
        //on créé la balise fermante </scriptpublic> de la balise parent <config>
        $configWidget->appendChild($scriptPublicWidget);

        //on crée la balise enfant <methodSet> et on lui ajoute _specifiq_
        $methodSetWidget = $specifboxWidget->createElement('methodset', '_specifique_');
        //on créé la balise fermante </methodSet> de la balise parent <config>
        $configWidget->appendChild($methodSetWidget);

        $specifboxWidget->save('specifbox_'.$identifiantWidget.'.xml');









        //fichier hooks2C.class.php qui se trouve dans le dossier hooks avec la checkbox Menu2C avec crud
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileHooksMenu2cCrud = 'hook_2C.class.php';
        $php_fileHooksMenu2cCrud = fopen($fileHooksMenu2cCrud, 'w+');
        if (filesize($fileHooksMenu2cCrud) > 0) {
            $contents = fread($php_fileHooksMenu2cCrud, filesize($fileHooksMenu2cCrud));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileHooksMenu2cCrud, '<?php' . "\r\n");
            fwrite($php_fileHooksMenu2cCrud, 'namespace Addon\\' . $identifiant . ' ;'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, 'require_once \em_misc::getSpecifPath() . \'addons/' . $identifiant . '/class/tools/crud/' . $nomMenu  . 'class.php\';' . "\r\n");
            fwrite($php_fileHooksMenu2cCrud, "\r\n");
            fwrite($php_fileHooksMenu2cCrud, '/**' . "\r\n");
            fwrite($php_fileHooksMenu2cCrud, ' * ' . $descriptionMenu . "\r\n");
            fwrite($php_fileHooksMenu2cCrud, ' *'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, ' * @author Medialibs' . "\r\n");
            fwrite($php_fileHooksMenu2cCrud, ' *' . "\r\n");
            fwrite($php_fileHooksMenu2cCrud, ' * @since ' . $creation['date_de_creation']  . "\r\n");
            fwrite($php_fileHooksMenu2cCrud, ' */'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, ' class hook_2C extends \Emajine_Hooks'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, ' {'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '    /**'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     * Intervention sur les écrans en 2 colonnes (écrans de configuration)'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     *'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     * @param string $className Identifiant de l\'écran'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     * @param string $label Libellé de la zone'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     * @param array $items Items proposés dans la colonne de gauche'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     * @param string $defaultItem Item par défaut'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     *'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     * @return null'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     */'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     public function getContenersZoneParams($className, &$label, &$items, &$defaultItem)'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     {'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '         if (in_array($className, array(\'Emajine_Configuration_Notifications\'))) {'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '             $newElementLabel =' . "'" .$categorieMenu."'" . ';'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '             if (!is_array($items[$newElementLabel])) {'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '                 $items[$newElementLabel] = array();'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '             }'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '             $items[$newElementLabel][\'myCallbackName\'][\'label\'] ='. "'".$nomMenu."'".';'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '        }'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     }'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     public function actionMyCallbackName()'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     {'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '         $customCRUD = new'.$nomCrudMenu2c.'();'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '         return $customCRUD->start();'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, '     }'."\r\n");
            fwrite($php_fileHooksMenu2cCrud, ' }'."\r\n");

        }
        fclose($php_fileHooksMenu2cCrud);



        //fichier portant le nom du crud.class.php qui se trouve dans le dossier tools/crud via la checkbox Menu2c avec Crud
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileMenu2cCrud = $nomMenu.'.class.php';
        $php_fileMenu2cCrud = fopen($fileMenu2cCrud, 'w+');
        if (filesize($fileMenu2cCrud) > 0) {
            $contents = fread($php_fileMenu2cCrud, filesize($fileMenu2cCrud));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileMenu2cCrud,'<?php'."\r\n");
            fwrite($php_fileMenu2cCrud,'namespace Addon\\'.$identifiant. ' ;'."\r\n");
            fwrite($php_fileMenu2cCrud,'require_once \em_misc::getClassPath() . \'/core/Emajine_API/Emajine_CRUD.class.php\';'."\r\n");
            fwrite($php_fileMenu2cCrud,'/**'."\r\n");
            fwrite($php_fileMenu2cCrud,' *'."\r\n");
            fwrite($php_fileMenu2cCrud,' * Master de la classe'."\r\n");
            fwrite($php_fileMenu2cCrud,' *'."\r\n");
            fwrite($php_fileMenu2cCrud,' * @author Medialibs'."\r\n");
            fwrite($php_fileMenu2cCrud,' *'."\r\n");
            fwrite($php_fileMenu2cCrud,' * @since ' . $creation['date_de_creation']  . "\r\n");
            fwrite($php_fileMenu2cCrud,' */'."\r\n");
            fwrite($php_fileMenu2cCrud,' class ' .$nomCrudMenu2c. ' extends \Emajine_CRUD'."\r\n");
            fwrite($php_fileMenu2cCrud,' {'."\r\n");
            fwrite($php_fileMenu2cCrud,"\r\n");
            fwrite($php_fileMenu2cCrud,'     /**'."\r\n");
            fwrite($php_fileMenu2cCrud,'      * Constructeur du CRUD'."\r\n");
            fwrite($php_fileMenu2cCrud,'      *'."\r\n");
            fwrite($php_fileMenu2cCrud,'      * @return null'."\r\n");
            fwrite($php_fileMenu2cCrud,'      */'."\r\n");
            fwrite($php_fileMenu2cCrud,'      public function __construct()'."\r\n");
            fwrite($php_fileMenu2cCrud,'      {'."\r\n");
            fwrite($php_fileMenu2cCrud,'           $this->initCrud();'."\r\n");
            fwrite($php_fileMenu2cCrud,'           return parent::__construct();'."\r\n");
            fwrite($php_fileMenu2cCrud,'      }'."\r\n");
            fwrite($php_fileMenu2cCrud,"\r\n");
            fwrite($php_fileMenu2cCrud,'      /**'."\r\n");
            fwrite($php_fileMenu2cCrud,'       * Génère le formulaire d\'ajout ou de modification d\'une {nom de l\'entité}'."\r\n");
            fwrite($php_fileMenu2cCrud,'       *'."\r\n");
            fwrite($php_fileMenu2cCrud,'       * @param emajine_form   $form   Un objet formulaire'."\r\n");
            fwrite($php_fileMenu2cCrud,'       * @param string     $mode   Le type de formulaire. 2 valeurs possibles : add ou edit'."\r\n");
            fwrite($php_fileMenu2cCrud,'       * @return null'."\r\n");
            fwrite($php_fileMenu2cCrud,'       */'."\r\n");
            fwrite($php_fileMenu2cCrud,'       public function _getFormDatas($form, $mode)'."\r\n");
            fwrite($php_fileMenu2cCrud,'       {'."\r\n");
            fwrite($php_fileMenu2cCrud,'           // Ajouter votre code de création de formulaire ici'."\r\n");
            fwrite($php_fileMenu2cCrud,'           $this->hookGetFormDatas($form, $mode);'."\r\n");
            fwrite($php_fileMenu2cCrud,'           $this->_getFormDatasActions($form,$mode);'."\r\n");
            fwrite($php_fileMenu2cCrud,'       }'."\r\n");
            fwrite($php_fileMenu2cCrud,"\r\n");
            fwrite($php_fileMenu2cCrud,'      /**'."\r\n");
            fwrite($php_fileMenu2cCrud,'       * Initialisation du crud'."\r\n");
            fwrite($php_fileMenu2cCrud,'       *'."\r\n");
            fwrite($php_fileMenu2cCrud,'       * @return null'."\r\n");
            fwrite($php_fileMenu2cCrud,'       */'."\r\n");
            fwrite($php_fileMenu2cCrud,'       private function initCrud()'."\r\n");
            fwrite($php_fileMenu2cCrud,'       {'."\r\n");
            fwrite($php_fileMenu2cCrud,'         $this->setListTitle(\'Titre\');'."\r\n");
            fwrite($php_fileMenu2cCrud,'         $this->setListDescription(\'Description\');'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // Ajouter nom table'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // $this->setDBTable(\'specifs_table\');'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // Ajouter les champ à séléctionner'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // $this->setDBFields(\'\');'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // Ajouter la clé primaire'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // $this->setDBId(\'id\');'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // Ajouter la colonne à mettre comme label'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // $this->setDBLabel(\'label\');'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // Ajouter le tri'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // $this->setListDefaultSort(\'my_column\', \'ASC\');'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // Ajouter les colonnes à afficher'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // $this->setListMap(array(\'col1\' => \'Libele colonne 1\', \'col2\' => \'Libele colonne 2\'));'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // Ajouter le libellé pour le bouton ajouter'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // $this->setListNewElementLinkLabel(\'Ajouter une ...\');'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // Ajouter une recherche'."\r\n");
            fwrite($php_fileMenu2cCrud,'         // $this->setListSearchCrit(array(\'Libellé\' => \'label\', \'Etat\' => \'status\'));'."\r\n");
            fwrite($php_fileMenu2cCrud,'       }'."\r\n");
            fwrite($php_fileMenu2cCrud,"\r\n");
            fwrite($php_fileMenu2cCrud,'      /**'."\r\n");
            fwrite($php_fileMenu2cCrud,'       * Verifier si on peut supprimer une {nom de l\'entité} lors de l\'affichage du message de confirmation'."\r\n");
            fwrite($php_fileMenu2cCrud,'       *'."\r\n");
            fwrite($php_fileMenu2cCrud,'       * @param string $message'."\r\n");
            fwrite($php_fileMenu2cCrud,'       * @param array $param'."\r\n");
            fwrite($php_fileMenu2cCrud,'       *'."\r\n");
            fwrite($php_fileMenu2cCrud,'       * @return string'."\r\n");
            fwrite($php_fileMenu2cCrud,'       */'."\r\n");
            fwrite($php_fileMenu2cCrud,'       public function _delete_confirmation($message = null, array $param = array())'."\r\n");
            fwrite($php_fileMenu2cCrud,'       {'."\r\n");
            fwrite($php_fileMenu2cCrud,'           // à utiliser si on veut modifier le comportement de la confirmation de suppression'."\r\n");
            fwrite($php_fileMenu2cCrud,'           // return parent::_delete_confirmation($message, $param);'."\r\n");
            fwrite($php_fileMenu2cCrud,'       }'."\r\n");
            fwrite($php_fileMenu2cCrud,"\r\n");
            fwrite($php_fileMenu2cCrud,'       /**'."\r\n");
            fwrite($php_fileMenu2cCrud,'        * Suppression d\'une {nom de l\'entité}\''."\r\n");
            fwrite($php_fileMenu2cCrud,'        *'."\r\n");
            fwrite($php_fileMenu2cCrud,'        *@return null'."\r\n");
            fwrite($php_fileMenu2cCrud,'        */'."\r\n");
            fwrite($php_fileMenu2cCrud,'        public function _delete()'."\r\n");
            fwrite($php_fileMenu2cCrud,'        {'."\r\n");
            fwrite($php_fileMenu2cCrud,'        }'."\r\n");
            fwrite($php_fileMenu2cCrud,"\r\n");
            fwrite($php_fileMenu2cCrud,'       /**'."\r\n");
            fwrite($php_fileMenu2cCrud,'        * Enlever les actions Afficher détails des lignes'."\r\n");
            fwrite($php_fileMenu2cCrud,'        *'."\r\n");
            fwrite($php_fileMenu2cCrud,'        * @param object $list'."\r\n");
            fwrite($php_fileMenu2cCrud,'        *'."\r\n");
            fwrite($php_fileMenu2cCrud,'        * @return ?'."\r\n");
            fwrite($php_fileMenu2cCrud,'        */'."\r\n");
            fwrite($php_fileMenu2cCrud,'        public function _getListActions($list)'."\r\n");
            fwrite($php_fileMenu2cCrud,'        {'."\r\n");
            fwrite($php_fileMenu2cCrud,'            // if ($this->_rights[\'modifier\']) {'."\r\n");
            fwrite($php_fileMenu2cCrud,'            //     $list->setEditAction($this->_layerIDPrefix);'."\r\n");
            fwrite($php_fileMenu2cCrud,'            // }'."\r\n");
            fwrite($php_fileMenu2cCrud,'            // if ($this->_rights[\'supprimer\']) {'."\r\n");
            fwrite($php_fileMenu2cCrud,'            //     $list->setDeleteAction($this->_layerIDPrefix);'."\r\n");
            fwrite($php_fileMenu2cCrud,'            // }'."\r\n");
            fwrite($php_fileMenu2cCrud,'            // $this->_getAdditionnalListActions($list);'."\r\n");
            fwrite($php_fileMenu2cCrud,'        }'."\r\n");
            fwrite($php_fileMenu2cCrud,"\r\n");
            fwrite($php_fileMenu2cCrud,'       /**'."\r\n");
            fwrite($php_fileMenu2cCrud,'        * Définition des actions de masse'."\r\n");
            fwrite($php_fileMenu2cCrud,'        *'."\r\n");
            fwrite($php_fileMenu2cCrud,'        * @return null'."\r\n");
            fwrite($php_fileMenu2cCrud,'        */'."\r\n");
            fwrite($php_fileMenu2cCrud,'        public function _getMassivesActions($list)'."\r\n");
            fwrite($php_fileMenu2cCrud,'        {'."\r\n");
            fwrite($php_fileMenu2cCrud,'            // fonction à vide permettant de ne pas afficher les actions de masse'."\r\n");
            fwrite($php_fileMenu2cCrud,'        }'."\r\n");
            fwrite($php_fileMenu2cCrud,"\r\n");
            fwrite($php_fileMenu2cCrud,'       /**'."\r\n");
            fwrite($php_fileMenu2cCrud,'        * Redefinir la fonction getLayerTabs pour que l\'onglet détail ne s\'affiche plus lors d\'une edition');
            fwrite($php_fileMenu2cCrud,'        *'."`\r\n");
            fwrite($php_fileMenu2cCrud,'        * @param object $layer'."\r\n");
            fwrite($php_fileMenu2cCrud,'        * @return null'."\r\n");
            fwrite($php_fileMenu2cCrud,'        */'."\r\n");
            fwrite($php_fileMenu2cCrud,'        public function _getLayerTabs($layer)'."\r\n");
            fwrite($php_fileMenu2cCrud,'        {'."\r\n");
            fwrite($php_fileMenu2cCrud,'            // $get = array();'."\r\n");
            fwrite($php_fileMenu2cCrud,'            // $get[\'ch\'] = $_GET[\'ch\'];'."\r\n");
            fwrite($php_fileMenu2cCrud,'            // $get[\'id\'] = $_GET[\'id\'];'."\r\n");
            fwrite($php_fileMenu2cCrud,'            // if ($this->_rights[\'modifier\']) {'."\r\n");
            fwrite($php_fileMenu2cCrud,'            //     $get[\'action\'] = \'edit\';'."\r\n");
            fwrite($php_fileMenu2cCrud,'            //     $js = \em_js::layer($this->getLayerId($_GET[\'id\']),'."\r\n");
            fwrite($php_fileMenu2cCrud,'            //                         getRewriteUrl(false, $get), i18n(\'Edit\'));'."\r\n");
            fwrite($php_fileMenu2cCrud,'            //     $label = i18n(\'_edit_\');'."\r\n");
            fwrite($php_fileMenu2cCrud,'            //     $img = \em_misc::getManageImg(\'actions/edit_small.png\', $label);'."\r\n");
            fwrite($php_fileMenu2cCrud,'            //     $layer->addTab(\'_edit_\', $img, $js, \'edit\');'."\r\n");
            fwrite($php_fileMenu2cCrud,'            // }'."\r\n");
            fwrite($php_fileMenu2cCrud,'            // $this->_getLayerAdditionnalTabs($layer);'."\r\n");
            fwrite($php_fileMenu2cCrud,'            // $this->hookSetLayerTabsAfterDefault($layer);'."\r\n");
            fwrite($php_fileMenu2cCrud,'        }'."\r\n");
            fwrite($php_fileMenu2cCrud,"\r\n");
            fwrite($php_fileMenu2cCrud,"\r\n");
            fwrite($php_fileMenu2cCrud,'        /**'."\r\n");
            fwrite($php_fileMenu2cCrud,'         * Redefinir la fonction actionAfterAdd pour que le detail ne soit plus affiché suite à l\'ajout'."\r\n");
            fwrite($php_fileMenu2cCrud,'         *'."\r\n");
            fwrite($php_fileMenu2cCrud,'         * @return null'."\r\n");
            fwrite($php_fileMenu2cCrud,'         */'."\r\n");
            fwrite($php_fileMenu2cCrud,'         public function _actionAfterAdd()'."\r\n");
            fwrite($php_fileMenu2cCrud,'         {'."\r\n");
            fwrite($php_fileMenu2cCrud,'             // $out =  \'updateList("\' . $this->_prepareURL(array(\'action\'=>\'list\')) . \'", {contents:true});\';\''."\r\n");
            fwrite($php_fileMenu2cCrud,'             // $out .= \em_js::closeLayer($this->getLayerId());'."\r\n");
            fwrite($php_fileMenu2cCrud,'             // \em_output::echoAndExit(\em_js::getJsTag($out));'."\r\n");
            fwrite($php_fileMenu2cCrud,'         }'."\r\n");
            fwrite($php_fileMenu2cCrud,"\r\n");
            fwrite($php_fileMenu2cCrud,'         /**'."\r\n");
            fwrite($php_fileMenu2cCrud,'          * Met à jour l\'ordre des {nom de l\'entité}s après l\'enregistrement'."\r\n");
            fwrite($php_fileMenu2cCrud,'          *'."\r\n");
            fwrite($php_fileMenu2cCrud,'          */'."\r\n");
            fwrite($php_fileMenu2cCrud,'          public function _actionAfterEdit()'."\r\n");
            fwrite($php_fileMenu2cCrud,'          {'."\r\n");
            fwrite($php_fileMenu2cCrud,'              // Votre code ici'."\r\n");
            fwrite($php_fileMenu2cCrud,'              // Appel de la méthode parente'."\r\n");
            fwrite($php_fileMenu2cCrud,'              parent::_actionAfterEdit();'."\r\n");
            fwrite($php_fileMenu2cCrud,'          }'."\r\n");
            fwrite($php_fileMenu2cCrud,"\r\n");
            fwrite($php_fileMenu2cCrud,"\r\n");
            fwrite($php_fileMenu2cCrud,' }'."\r\n");

        }
             fclose($php_fileMenu2cCrud);


        //fichier hooks2C.class.php qui se trouve dans le dossier hooks avec la checkbox Menu2C avec form
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileHooksMenu2cForm = 'hook_2C.class.php';
        $php_fileHooksMenu2cForm = fopen($fileHooksMenu2cForm, 'w+');
        if (filesize($fileHooksMenu2cForm) > 0) {
            $contents = fread($php_fileHooksMenu2cForm, filesize($fileHooksMenu2cForm));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileHooksMenu2cForm, '<?php' . "\r\n");
            fwrite($php_fileHooksMenu2cForm, 'namespace Addon\\' . $identifiant . ' ;'."\r\n");
            fwrite($php_fileHooksMenu2cForm, 'require_once \em_misc::getSpecifPath() . \'addons/' . $identifiant . '/class/tools/crud/' .  $nomFormulaire . 'class.php\';' . "\r\n");
            fwrite($php_fileHooksMenu2cForm, "\r\n");
            fwrite($php_fileHooksMenu2cForm, '/**' . "\r\n");
            fwrite($php_fileHooksMenu2cForm, ' * ' . $descriptionMenu . "\r\n");
            fwrite($php_fileHooksMenu2cForm, ' *'."\r\n");
            fwrite($php_fileHooksMenu2cForm, ' * @author Medialibs' . "\r\n");
            fwrite($php_fileHooksMenu2cForm, ' *' . "\r\n");
            fwrite($php_fileHooksMenu2cForm, ' * @since ' . $creation['date_de_creation']  . "\r\n");
            fwrite($php_fileHooksMenu2cForm, ' */'."\r\n");
            fwrite($php_fileHooksMenu2cForm, ' class hook_2C extends \Emajine_Hooks'."\r\n");
            fwrite($php_fileHooksMenu2cForm, ' {'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '    /**'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     * Intervention sur les écrans en 2 colonnes (écrans de configuration)'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     *'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     * @param string $className Identifiant de l\'écran'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     * @param string $label Libellé de la zone'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     * @param array $items Items proposés dans la colonne de gauche'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     * @param string $defaultItem Item par défaut'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     *'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     * @return null'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     */'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     public function getContenersZoneParams($className, &$label, &$items, &$defaultItem)'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     {'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '         if (in_array($className, array(\'Emajine_Configuration_Notifications\'))) {'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '             $newElementLabel =' . "'" .$categorieMenu."'" . ';'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '             if (!is_array($items[$newElementLabel])) {'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '                 $items[$newElementLabel] = array();'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '             }'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '             $items[$newElementLabel][\'myCallbackName\'][\'label\'] ='. "'".$nomMenu."'".';'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '        }'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     }'."\r\n");
            fwrite($php_fileHooksMenu2cForm, "\r\n");
            fwrite($php_fileHooksMenu2cForm, '     /**'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '      * Action executé au clic sur le menu'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '      *'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '      * @return     string  contenu '."\r\n");
            fwrite($php_fileHooksMenu2cForm, '      */'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     public function actionMyCallbackName()'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     {'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '         $customForm = new'.$nomFormulaire.'();'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '         return $customForm->start();'."\r\n");
            fwrite($php_fileHooksMenu2cForm, '     }'."\r\n");
            fwrite($php_fileHooksMenu2cForm, ' }'."\r\n");

        }
        fclose($php_fileHooksMenu2cForm);





         //fichier portant le nom du formulaire.class.php via la checkbox Menu 2c avec form
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileMenu2cForm = $nomFormulaire.'.class.php';
        $php_fileMenu2cForm = fopen($fileMenu2cForm, 'w+');
        if (filesize($fileMenu2cForm) > 0) {
            $contents = fread($php_fileMenu2cForm, filesize($fileMenu2cForm));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileMenu2cForm,'<?php'."\r\n");
            fwrite($php_fileMenu2cForm,'namespace Addon\\'.$identifiant.';'."\r\n");
            fwrite($php_fileMenu2cForm,'require_once \em_misc::getClassPath() . \'/core/Emajine_API/Emajine_2C.class.php\';'."\r\n");
            fwrite($php_fileMenu2cForm,"\r\n");
            fwrite($php_fileMenu2cForm,'/**'."\r\n");
            fwrite($php_fileMenu2cForm,' * Class for custom form'."\r\n");
            fwrite($php_fileMenu2cForm,' *'."\r\n");
            fwrite($php_fileMenu2cForm,' * @author Medialibs'."\r\n");
            fwrite($php_fileMenu2cForm,' *'."\r\n");
            fwrite($php_fileMenu2cForm,' * @since 2021-06-21'."\r\n");
            fwrite($php_fileMenu2cForm,' */'."\r\n");
            fwrite($php_fileMenu2cForm,' class ' .$nomFormulaire.' extends \Emajine_2C');
            fwrite($php_fileMenu2cForm,"\r\n");
            fwrite($php_fileMenu2cForm,' {'."\r\n");
            fwrite($php_fileMenu2cForm,'     /**'."\r\n");
            fwrite($php_fileMenu2cForm,'      * Start'."\r\n");
            fwrite($php_fileMenu2cForm,'      *'."\r\n");
            fwrite($php_fileMenu2cForm,'      * @return string'."\r\n");
            fwrite($php_fileMenu2cForm,'      */'."\r\n");
            fwrite($php_fileMenu2cForm,'      public function start()'."\r\n");
            fwrite($php_fileMenu2cForm,'      {'."\r\n");
            fwrite($php_fileMenu2cForm,'           return $this->_getContentForm(\'generateForm\',\'onSave\', \'getDefaultValue\');'."\r\n");
            fwrite($php_fileMenu2cForm,'      }'."\r\n");
            fwrite($php_fileMenu2cForm,"\r\n");
            fwrite($php_fileMenu2cForm,'      /**'."\r\n");
            fwrite($php_fileMenu2cForm,'       * Enregistrement des données à la validation du formulaire'."\r\n");
            fwrite($php_fileMenu2cForm,'       *'."\r\n");
            fwrite($php_fileMenu2cForm,'       * @return null'."\r\n");
            fwrite($php_fileMenu2cForm,'       */'."\r\n");
            fwrite($php_fileMenu2cForm,'       public function onSave() {}'."\r\n");
            fwrite($php_fileMenu2cForm,"\r\n");
            fwrite($php_fileMenu2cForm,'       /**'."\r\n");
            fwrite($php_fileMenu2cForm,'        * Récupération des données par défaut en base'."\r\n");
            fwrite($php_fileMenu2cForm,'        *'."\r\n");
            fwrite($php_fileMenu2cForm,'        * @return array'."\r\n");
            fwrite($php_fileMenu2cForm,'        */'."\r\n");
            fwrite($php_fileMenu2cForm,'        public function getDefaultValue() {}'."\r\n");
            fwrite($php_fileMenu2cForm,"\r\n");
            fwrite($php_fileMenu2cForm,'        /**'."\r\n");
            fwrite($php_fileMenu2cForm,'         * Generer le formulaire'."\r\n");
            fwrite($php_fileMenu2cForm,'         */'."\r\n");
            fwrite($php_fileMenu2cForm,'         public function generateForm($form)'."\r\n");
            fwrite($php_fileMenu2cForm,'         {'."\r\n");
            fwrite($php_fileMenu2cForm,'              $form->addElement(\'fieldset\', \'Informations\');'."\r\n");
            fwrite($php_fileMenu2cForm,'              // Ajouter un champ texte'."\r\n");
            fwrite($php_fileMenu2cForm,'              $form->addElement('."\r\n");
            fwrite($php_fileMenu2cForm,'                 \'text\','."\r\n");
            fwrite($php_fileMenu2cForm,'                 \'myField\','."\r\n");
            fwrite($php_fileMenu2cForm,'                 \'Mon champ\','."\r\n");
            fwrite($php_fileMenu2cForm,'                  array(),'."\r\n");
            fwrite($php_fileMenu2cForm,'                  true,'."\r\n");
            fwrite($php_fileMenu2cForm,"                 '<div class=\'.description\'>Description ici</div>'"."\r\n");
            fwrite($php_fileMenu2cForm,'              );'."\r\n");
            fwrite($php_fileMenu2cForm,'              // Ajouter un champ date'."\r\n");
            fwrite($php_fileMenu2cForm,'              $form->addElement('."\r\n");
            fwrite($php_fileMenu2cForm,'                \'date\','."\r\n");
            fwrite($php_fileMenu2cForm,'                \'date\','."\r\n");
            fwrite($php_fileMenu2cForm,'                \'Mon champ\','."\r\n");
            fwrite($php_fileMenu2cForm,'                 array(),'."\r\n");
            fwrite($php_fileMenu2cForm,'                 true,'."\r\n");
            fwrite($php_fileMenu2cForm,"                 '<div class=\'description\'>Description ici</div>'"."\r\n");
            fwrite($php_fileMenu2cForm,'              );'."\r\n");
            fwrite($php_fileMenu2cForm,'              // Ajouter un radio'."\r\n");
            fwrite($php_fileMenu2cForm,'              $form->addElement('."\r\n");
            fwrite($php_fileMenu2cForm,'                 \'radM\','."\r\n");
            fwrite($php_fileMenu2cForm,'                 \'nom_champ\','."\r\n");
            fwrite($php_fileMenu2cForm,'                 \'libelle_champ\','."\r\n");
            fwrite($php_fileMenu2cForm,'                  array(\'values\' => array(1 => \'Oui\', 0 => \'Non\'), \'useNumericKey\' => true), true'."\r\n");
            fwrite($php_fileMenu2cForm,'              );'."\r\n");
            fwrite($php_fileMenu2cForm,'              $form->addElement(\'text\', \'myMail\', \'E-mail\', array(), true);'."\r\n");
            fwrite($php_fileMenu2cForm,'              $form->addRule(\'myMail\', array($this, \'isEmail\'));'."\r\n");
            fwrite($php_fileMenu2cForm,"\r\n");
            fwrite($php_fileMenu2cForm,'              /**************************** EXEMPLES DES CHAMPS ****************************************/'."\r\n");
            fwrite($php_fileMenu2cForm,'              /* Ajouter un checkbox'."\r\n");
            fwrite($php_fileMenu2cForm,'         $form->addElement('."\r\n");
            fwrite($php_fileMenu2cForm,'         \'mChe\','."\r\n");
            fwrite($php_fileMenu2cForm,'         \'mon_checkbox\','."\r\n");
            fwrite($php_fileMenu2cForm,'         \'libelle_champ\','."\r\n");
            fwrite($php_fileMenu2cForm,'          array(\'values\' => array(1 => \'Oui\', 0 => \'Non\'), \'checked\' => array(), \'useNumericKey\' => true), true');
            fwrite($php_fileMenu2cForm,'          );'."\r\n");
            fwrite($php_fileMenu2cForm,'          // Ajout d\'un champ de type ressource'."\r\n");
            fwrite($php_fileMenu2cForm,'          $form->addElement('."\r\n");
            fwrite($php_fileMenu2cForm,'          \'iRes\','."\r\n");
            fwrite($php_fileMenu2cForm,'          \'visual\','."\r\n");
            fwrite($php_fileMenu2cForm,'          \'Visuel\','."\r\n");
            fwrite($php_fileMenu2cForm,'          array(\'ressource\' => \'media\'), true'."\r\n");
            fwrite($php_fileMenu2cForm,'          );'."\r\n");
            fwrite($php_fileMenu2cForm,'          // Ajout d\'un champ de type ressource'."\r\n");
            fwrite($php_fileMenu2cForm,'          $form->addElement('."\r\n");
            fwrite($php_fileMenu2cForm,'         \'mRes\','."\r\n");
            fwrite($php_fileMenu2cForm,'         \'visuals\','."\r\n");
            fwrite($php_fileMenu2cForm,'         \'Visuels\','."\r\n");
            fwrite($php_fileMenu2cForm,'         array('."\r\n");
            fwrite($php_fileMenu2cForm,'         \'value\'     => $valeurParDefaut,'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'ressource\' => \'media\', //article, media, news, form, map, link, poll, …'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'linklabel\' => \'linklabel\','."\r\n");
            fwrite($php_fileMenu2cForm,'         \'js\'        => $js,'."\r\n");
            fwrite($php_fileMenu2cForm,'          ),'."\r\n");
            fwrite($php_fileMenu2cForm,'          true'."\r\n");
            fwrite($php_fileMenu2cForm,'          );'."\r\n");
            fwrite($php_fileMenu2cForm,'          // Ajouter une description'."\r\n");
            fwrite($php_fileMenu2cForm,'          $form->addElement(\'description\', \'Indiquez ici votre nom et votre prénom\');'."\r\n");
            fwrite($php_fileMenu2cForm,'          // Ajouter du javascript sur un formulaire Emajine en utilisant un champ "Description"'."\r\n");
            fwrite($php_fileMenu2cForm,'          $form->addElement(\'description\', false, \em_js::getJsTag(\'alert("test");\'));'."\r\n");
            fwrite($php_fileMenu2cForm,'          // Ajouter un champ de type select'."\r\n");
            fwrite($php_fileMenu2cForm,'          $form->addElement(\'select\', \'civility\', \'Civilité\', array(\'values\' => array(\'M\' => \'Monsieur\', \'Mme\' => \'Madamme\')), true);'."\r\n");
            fwrite($php_fileMenu2cForm,'          // Ajouter un champ de type textarea'."\r\n");
            fwrite($php_fileMenu2cForm,'          $form->addElement(\'area\', \'address\', \'Adresse\', array(\'rows\' => 4));'."\r\n");
            fwrite($php_fileMenu2cForm,"\r\n");
            fwrite($php_fileMenu2cForm,'          // Ajouter un champ de type file'."\r\n");
            fwrite($php_fileMenu2cForm,'          $form->addElement('."\r\n");
            fwrite($php_fileMenu2cForm,'         \'file\','."\r\n");
            fwrite($php_fileMenu2cForm,'         \'rapportFile\','."\r\n");
            fwrite($php_fileMenu2cForm,'         \'Importer un fichier\','."\r\n");
            fwrite($php_fileMenu2cForm,'          array(\'accept\' => \'csv\'),'."\r\n");
            fwrite($php_fileMenu2cForm,'          true,'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'<span>Veuillez sélectionner un fichier au format CSV (encodage UTF-8)<br />Les données doivent être séparées par un ","</span>\''."\r\n");
            fwrite($php_fileMenu2cForm,'          );'."\r\n");
            fwrite($php_fileMenu2cForm,'          // Ajouter un champ de selection d\'utilisateur'."\r\n");
            fwrite($php_fileMenu2cForm,'          $form->addElement('."\r\n");
            fwrite($php_fileMenu2cForm,'          "seDb", "nom_du_champ", "Titre du champ",'."\r\n");
            fwrite($php_fileMenu2cForm,'          array('."\r\n");
            fwrite($php_fileMenu2cForm,'          "dbtable"     => "acteur",'."\r\n");
            fwrite($php_fileMenu2cForm,'          "dbid"        => "id_acteur",'."\r\n");
            fwrite($php_fileMenu2cForm,'          "dblabel"     => "CONCAT(nom , \' \' , prenom)",'."\r\n");
            fwrite($php_fileMenu2cForm,'          "selected"    => $id_de_l_utilisateur_selectionne,'."\r\n");
            fwrite($php_fileMenu2cForm,'          "dbcondition" => " login IS NOT NULL ",'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'behaviour\'   => \'layer\','."\r\n");
            fwrite($php_fileMenu2cForm,'          )'."\r\n");
            fwrite($php_fileMenu2cForm,'          );'."\r\n");
            fwrite($php_fileMenu2cForm,'          // Ajouter un champ date'."\r\n");
            fwrite($php_fileMenu2cForm,'          $form->addElement(\'capT\','."\r\n");
            fwrite($php_fileMenu2cForm,'         \'Captcha\','."\r\n");
            fwrite($php_fileMenu2cForm,'         \'Mon captcha\','."\r\n");
            fwrite($php_fileMenu2cForm,'         array('."\r\n");
            fwrite($php_fileMenu2cForm,'         \'captchatype\' => \'recaptcha\', //operation,question\','."\r\n");
            fwrite($php_fileMenu2cForm,'         \'style\'       => \'clas_css\','."\r\n");
            fwrite($php_fileMenu2cForm,'         \'maxlength\'   => $maxLegth,'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'size\'        => $size,'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'value\'       => $value,'."\r\n");
            fwrite($php_fileMenu2cForm,'         ),'."\r\n");
            fwrite($php_fileMenu2cForm,'          true,'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'<div class=\'description\'>Description ici</div>\''."\r\n");
            fwrite($php_fileMenu2cForm,'         );'."\r\n");
            fwrite($php_fileMenu2cForm,'         // Ajouter un champ de type hidden'."\r\n");
            fwrite($php_fileMenu2cForm,'         $form->addElement(\'hidden\', \'champ_hidden\', \'valeur\');'."\r\n");
            fwrite($php_fileMenu2cForm,"\r\n");
            fwrite($php_fileMenu2cForm,'          // Ajouter un menu déroulant avec lecture en base de données'."\r\n");
            fwrite($php_fileMenu2cForm,'          $form->addElement(\'seDb\', \'myDbSelect\', \'Mon selection\', array('."\r\n");
            fwrite($php_fileMenu2cForm,'         \'dbtable\'     => \'addons\', // : nom de la table (accepte des jointures)'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'dbid\'        => \'id\', // : champ "identifiant"'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'dblabel\'     => \'name\', // : champ "label"'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'dbcondition\' => \'1\', // : condition MySQL à appliquer'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'dbdistinct\'  => true, // : si true, ajoute un \‘DISTINCT\’ sur le label'."\r\n");
            fwrite($php_fileMenu2cForm,'         // \'selected\'    => array(1) , // : tableau contenant les valeurs sélectionnées par défaut'."\r\n");
            fwrite($php_fileMenu2cForm,'         // \'size\'           => 20 , // : nombre d’options affichées'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'multiple\'    => false, // : est ce un champ multiple ?'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'required\'    => false, // : si true, le select ne proposera pas de valeur vide'."\r\n");
            fwrite($php_fileMenu2cForm,'         // \'js\'           => $dbJs , // : javascript pour l\'attribut onchange'."\r\n");
            fwrite($php_fileMenu2cForm,'         \'behaviour\'   => \'layer\', // : si "layer", la sélection se fera par l’intermédiaire d’un layer'."\r\n");
            fwrite($php_fileMenu2cForm,'         // \'dborder\'       => \'id DESC\' // : partie “ORDER BY” de la requête'."\r\n");
            fwrite($php_fileMenu2cForm,'         )'."\r\n");
            fwrite($php_fileMenu2cForm,'         );'."\r\n");
            fwrite($php_fileMenu2cForm,'         // Ajouter un champ de type submit'."\r\n");
            fwrite($php_fileMenu2cForm,'         $form->addElement(\'submit\', \'save\', \'Valider\', array(\'js\' => $js));'."\r\n");
            fwrite($php_fileMenu2cForm,'         // Ajouter un champ de type bouton'."\r\n");
            fwrite($php_fileMenu2cForm,'         $form->addElement(\'button\', \'update\', \'Mettre à jour\', array(\'js\' => $jsUpdate));'."\r\n");
            fwrite($php_fileMenu2cForm,'         // Ajout d’une règle de contrôle d’un champ'."\r\n");
            fwrite($php_fileMenu2cForm,'         // Intercepter la validation du formulaire'."\r\n");
            fwrite($php_fileMenu2cForm,'         if ($form->validate()) {'."\r\n");
            fwrite($php_fileMenu2cForm,'         // Ici votre code pour gerer les requetes'."\r\n");
            fwrite($php_fileMenu2cForm,'         }'."\r\n");
            fwrite($php_fileMenu2cForm,'         // Générer le string du formulaire'."\r\n");
            fwrite($php_fileMenu2cForm,'         return $form->startFormCreate();'."\r\n");
            fwrite($php_fileMenu2cForm,'         */'."\r\n");
            fwrite($php_fileMenu2cForm,'         }'."\r\n");
            fwrite($php_fileMenu2cForm,"\r\n");
            fwrite($php_fileMenu2cForm,'        /**'."\r\n");
            fwrite($php_fileMenu2cForm,'         * La données saisie est-elle une adresse email ?'."\r\n");
            fwrite($php_fileMenu2cForm,'         *'."\r\n");
            fwrite($php_fileMenu2cForm,'         * @param string $value La donnée à vérifier'."\r\n");
            fwrite($php_fileMenu2cForm,'         *'."\r\n");
            fwrite($php_fileMenu2cForm,'         * @return null'."\r\n");
            fwrite($php_fileMenu2cForm,'         */'."\r\n");
            fwrite($php_fileMenu2cForm,'         private function isEmail($value)'."\r\n");
            fwrite($php_fileMenu2cForm,'         {'."\r\n");
            fwrite($php_fileMenu2cForm,'            $regex = '."'^[-!#$%&\\'*+./0-9=?A-Z^_`a-z{|}~]+@[-!#$%&\\'*+/0-9=?A-Z^_\`a-z{|}~]+\.[-!#$%&\'*+./0-9=?A-Z^_`a-z{|}~]+$';"."\r\n");
            fwrite($php_fileMenu2cForm,'            if (ereg($regex, $value) !== 1) {'."\r\n");
            fwrite($php_fileMenu2cForm,'                   throw new Exception($value .\'  n\\\'est pas une adresse email\');'."\r\n");
            fwrite($php_fileMenu2cForm,'            }'."\r\n");
            fwrite($php_fileMenu2cForm,'         }'."\r\n");
            fwrite($php_fileMenu2cForm,' }'."\r\n");

        }
        fclose($php_fileMenu2cForm);


        //fichier menuNew.class.php qui se trouve dans le dossier Menu et dossier New via la checkbox Nouvelle section avec un crud

        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileSectionCrud = 'menuNew.class.php';
        $php_fileSectionCrud = fopen($fileSectionCrud, 'w+');
        if (filesize($fileSectionCrud) > 0) {
            $contents = fread($php_fileSectionCrud, filesize($fileSectionCrud));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileSectionCrud,'<?php'."\r\n");
            fwrite($php_fileSectionCrud,'namespace\\'.$identifiant. ';'."\r\n");
            fwrite($php_fileSectionCrud,'require_once \em_misc::getSpecifPath() . \'addons/'.$identifiant.'/class/tools/crud/test5.class.php\';'."\r\n");
            fwrite($php_fileSectionCrud,"\r\n");
            fwrite($php_fileSectionCrud,'/**'."\r\n");
            fwrite($php_fileSectionCrud,' * Création nouveau menu'."\r\n");
            fwrite($php_fileSectionCrud,' *'."\r\n");
            fwrite($php_fileSectionCrud,' * @author Medialibs'."\r\n");
            fwrite($php_fileSectionCrud,' *'."\r\n");
            fwrite($php_fileSectionCrud,' * @since ' . $creation['date_de_creation']  . "\r\n");
            fwrite($php_fileSectionCrud,' */'."\r\n");
            fwrite($php_fileSectionCrud,' class menuNew'."\r\n");
            fwrite($php_fileSectionCrud,' {'."\r\n");
            fwrite($php_fileSectionCrud,"\r\n");
            fwrite($php_fileSectionCrud,'     /**'."\r\n");
            fwrite($php_fileSectionCrud,'      * La zone à afficher par défaut'."\r\n");
            fwrite($php_fileSectionCrud,'      *'."\r\n");
            fwrite($php_fileSectionCrud,'      * @var string'."\r\n");
            fwrite($php_fileSectionCrud,'      */'."\r\n");
            fwrite($php_fileSectionCrud,'      public $_contenersZoneDefaultItem = \'new\';'."\r\n");
            fwrite($php_fileSectionCrud,'          /**'."\r\n");
            fwrite($php_fileSectionCrud,'           * Constructeur'."\r\n");
            fwrite($php_fileSectionCrud,'           */'."\r\n");
            fwrite($php_fileSectionCrud,'           public function __construct() {}'."\r\n");
            fwrite($php_fileSectionCrud,"\r\n");
            fwrite($php_fileSectionCrud,'           public function start()'."\r\n");
            fwrite($php_fileSectionCrud,'           {'."\r\n");
            fwrite($php_fileSectionCrud,'                $newCrud = new '.$nomMenuSectionCrud. '();'."\r\n");
            fwrite($php_fileSectionCrud,'                return $newCrud->start();'."\r\n");
            fwrite($php_fileSectionCrud,'           }'."\r\n");
            fwrite($php_fileSectionCrud,'}'."\r\n");
        }

            fclose($php_fileSectionCrud);



        //fichier emajine_menu.xml qui se trouve dans le dossier  class/menus/new via la checkbox menuSectionCrud
        //On crée un DomDocument
        $emajineMenu = new DOMDocument("1.0", "UTF-8");
        //Le format du fichier Xml est correct
        $emajineMenu->formatOutput= true;

        //on crée la balise parent <emajine_specif>
        $emajine_specif = $emajineMenu->createElement('emajine_specif');
        //on ferme la balise parent </data>
        $emajineMenu->appendChild($emajine_specif);


            //on crée la balise enfant <libelle>
            $libelle = $emajineMenu->createElement('libelle');
            //on créé la balise fermante </libelle> de la balise parent <emajine_specif>
            $emajine_specif->appendChild($libelle);

            //on crée la balise enfant <parent>
            $parent = $emajineMenu->createElement('parent');
            //on crée la balise fermante </name> de la balise parent <emajine_specif>
            $emajine_specif->appendChild($parent);

            //on crée la balise enfant <script>
            $script = $emajineMenu->createElement('script','addons/test/class/menus/new/'.$fileSectionCrud);
            //on crée la balise fermante </script> de la balise parent <emajine_specif>
            $emajine_specif->appendChild($script);

            // on enregiste le fichier sous le nom de emajine_menu.xml
            $emajineMenu->save('emajine_menu.xml');


            //fichier portant "le nom du crud.class.php" qui se trouve dans le dossier class/tools/crud via la checkbox Nouvelle section avec un crud
            $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
            $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
            $fileCrud = $nomCrudSection.'.class.php';
            $php_fileCrud = fopen($fileCrud, 'w+');
            if (filesize($fileCrud) > 0) {
            $contents = fread($php_fileCrud, filesize($fileCrud));

            }
            while ($creation = $datas->fetch()) {
                fwrite($php_fileCrud,'<?php'."\r\n");
                fwrite($php_fileCrud,'namespace Addon\\'.$identifiant. ';'."\r\n");
                fwrite($php_fileCrud,'require_once \em_misc::getClassPath() . \'/core/Emajine_API/Emajine_CRUD.class.php\';'."\r\n");
                fwrite($php_fileCrud,"\r\n");
                fwrite($php_fileCrud,'/**'."\r\n");
                fwrite($php_fileCrud,' * CRUD gestion des sélections du moment'."\r\n");
                fwrite($php_fileCrud,' *'."\r\n");
                fwrite($php_fileCrud,' * @author Zo de Medialibs <robson@medialibs.com>'."\r\n");
                fwrite($php_fileCrud,' *'."\r\n");
                fwrite($php_fileCrud,' * @since ' . $creation['date_de_creation']  . "\r\n");
                fwrite($php_fileCrud,' */'."\r\n");
                fwrite($php_fileCrud,' class '.$nomMenuSectionCrud. ' extends \Emajine_CRUD'."\r\n");
                fwrite($php_fileCrud,"\r\n");
                fwrite($php_fileCrud,'{'."\r\n");
                fwrite($php_fileCrud,'     private $products;'."\r\n");
                fwrite($php_fileCrud,"\r\n");
                fwrite($php_fileCrud,'    /**'."\r\n");
                fwrite($php_fileCrud,'     * Constructeur du CRUD'."\r\n");
                fwrite($php_fileCrud,'     *'."\r\n");
                fwrite($php_fileCrud,'     * @return null'."\r\n");
                fwrite($php_fileCrud,'     */'."\r\n");
                fwrite($php_fileCrud,'     public function __construct()'."\r\n");
                fwrite($php_fileCrud,'     {'."\r\n");
                fwrite($php_fileCrud,'         $this->products = array(\'tire\' => i18n(\'Pneus\'), \'vo\' => i18n(\'Véhicules d\\\'occasion\'));'."\r\n");
                fwrite($php_fileCrud,'         $this->initCrud();'."\r\n");
                fwrite($php_fileCrud,'         return parent::__construct();'."\r\n");
                fwrite($php_fileCrud,'     }'."\r\n");
                fwrite($php_fileCrud,"\r\n");
                fwrite($php_fileCrud,'   /**'."\r\n");
                fwrite($php_fileCrud,'    * Génère le formulaire d\'ajout ou de modification d\'une plaque'."\r\n");
                fwrite($php_fileCrud,'    *'."\r\n");
                fwrite($php_fileCrud,'    * @param emajine_form   $form   Un objet formulaire'."\r\n");
                fwrite($php_fileCrud,'    * @param string     $mode   Le type de formulaire. 2 valeurs possibles : add ou edit'."\r\n");
                fwrite($php_fileCrud,'    * @return null'."\r\n");
                fwrite($php_fileCrud,'    */'."\r\n");
                fwrite($php_fileCrud,'    public function _getFormDatas($form, $mode)'."\r\n");
                fwrite($php_fileCrud,'    {'."\r\n");
                fwrite($php_fileCrud,'        $form->addElement('."\r\n");
                fwrite($php_fileCrud,'           \'text\','."\r\n");
                fwrite($php_fileCrud,'           \'offer_name\','."\r\n");
                fwrite($php_fileCrud,'           \'Nom de l\\\'offre\','."\r\n");
                fwrite($php_fileCrud,'            array(), true'."\r\n");
                fwrite($php_fileCrud,'        );'."\r\n");
                fwrite($php_fileCrud,'        $form->addElement('."\r\n");
                fwrite($php_fileCrud,'           \'radM\','."\r\n");
                fwrite($php_fileCrud,'           \'offer_type\','."\r\n");
                fwrite($php_fileCrud,'           \'Type d\\\'offre\','."\r\n");
                fwrite($php_fileCrud,'            array('."\r\n");
                fwrite($php_fileCrud,'               \'values\'  => $this->products,'."\r\n");
                fwrite($php_fileCrud,'               \'checked\' => \'tire\','."\r\n");
                fwrite($php_fileCrud,'            ),'."\r\n");
                fwrite($php_fileCrud,'            true'."\r\n");
                fwrite($php_fileCrud,'        );'."\r\n");
                fwrite($php_fileCrud,'        $form->addElement('."\r\n");
                fwrite($php_fileCrud,'           \'iRes\','."\r\n");
                fwrite($php_fileCrud,'           \'visual\','."\r\n");
                fwrite($php_fileCrud,'           \'Visuel de l\\\'offre\','."\r\n");
                fwrite($php_fileCrud,'            array(\'ressource\' => \'media\'), true'."\r\n");
                fwrite($php_fileCrud,'        );'."\r\n");
                fwrite($php_fileCrud,'        $form->addElement('."\r\n");
                fwrite($php_fileCrud,'           \'text\','."\r\n");
                fwrite($php_fileCrud,'           \'offer_link\','."\r\n");
                fwrite($php_fileCrud,'           \'Lien\','."\r\n");
                fwrite($php_fileCrud,'            array(), true'."\r\n");
                fwrite($php_fileCrud,'        );'."\r\n");
                fwrite($php_fileCrud,'        $form->addElement('."\r\n");
                fwrite($php_fileCrud,'           \'select\','."\r\n");
                fwrite($php_fileCrud,'           \'plates\','."\r\n");
                fwrite($php_fileCrud,'           \'Plaques concernées par l\\\'offre\','."\r\n");
                fwrite($php_fileCrud,'            array(\'multiple\' => true, \'values\' => \em_db::assoc(\'SELECT id_plate, plate_name FROM specifs_plates ORDER BY plate_name ASC\')), true'."\r\n");
                fwrite($php_fileCrud,'        );'."\r\n");
                fwrite($php_fileCrud,"\r\n");
                fwrite($php_fileCrud,'            $this->hookGetFormDatas($form, $mode);'."\r\n");
                fwrite($php_fileCrud,'            $this->_getFormDatasActions($form, $mode);'."\r\n");
                fwrite($php_fileCrud,'    }'."\r\n");
                fwrite($php_fileCrud,"\r\n");
                fwrite($php_fileCrud,'    /**'."\r\n");
                fwrite($php_fileCrud,'     * Initialisation du crud'."\r\n");
                fwrite($php_fileCrud,'     *'."\r\n");
                fwrite($php_fileCrud,'     * @return null'."\r\n");
                fwrite($php_fileCrud,'     */'."\r\n");
                fwrite($php_fileCrud,'     private function initCrud()'."\r\n");
                fwrite($php_fileCrud,'     {'."\r\n");
                fwrite($php_fileCrud,'         $this->setListTitle(\'Title\');'."\r\n");
                fwrite($php_fileCrud,'         $this->setListDescription(\'Description\');'."\r\n");
                fwrite($php_fileCrud,'         $this->setDBTable(\'specifs_selections_offers\');'."\r\n");
                fwrite($php_fileCrud,'         $this->setDBFields(\'offer_id, offer_name, offer_type, visual, plates \');'."\r\n");
                fwrite($php_fileCrud,'         $this->setDBId(\'offer_id\');'."\r\n");
                fwrite($php_fileCrud,'         $this->setDBLabel(\'offer_name\');'."\r\n");
                fwrite($php_fileCrud,'         $this->setListDefaultSort(\'offer_name\', \'ASC\');'."\r\n");
                fwrite($php_fileCrud,'         $this->setListMap(array(\'offer_name\' => \'Nom de l\\\'offre\', \'offer_type\' => "Type d\\\'offre", \'visual\' => "Visuel de l\\\'offre", \'plates\' => "Plaques concernées par l\\\'offre"));'."\r\n");
                fwrite($php_fileCrud,'         $this->setListNewElementLinkLabel(\'Ajouter une offre\');'."\r\n");
                fwrite($php_fileCrud,'         $this->setFieldsCallBack(\'plates\', array($this, \'formatPlates\'));'."\r\n");
                fwrite($php_fileCrud,'         $this->setFieldsCallBack(\'offer_type\', array($this, \'formatType\'));'."\r\n");
                fwrite($php_fileCrud,'         $this->setListSearchCrit(array(\'Nom de l\\\'offre\' => \'offer_name\'));'."\r\n");
                fwrite($php_fileCrud,'     }'."\r\n");
                fwrite($php_fileCrud,"\r\n");
                fwrite($php_fileCrud,'      /**'."\r\n");
                fwrite($php_fileCrud,'       * Redéfinir les éléments à afficher'."\r\n");
                fwrite($php_fileCrud,'       *'."\r\n");
                fwrite($php_fileCrud,'       * @return null'."\r\n");
                fwrite($php_fileCrud,'       */'."\r\n");
                fwrite($php_fileCrud,'       public function _getDatasToDisplay()'."\r\n");
                fwrite($php_fileCrud,'       {'."\r\n");
                fwrite($php_fileCrud,'           $query = \'SELECT offer_name, offer_type, plates, visual \''."\r\n");
                fwrite($php_fileCrud,'           . \'FROM \' . $this->_dbtable . \' \''."\r\n");
                fwrite($php_fileCrud,'           . \'WHERE \' . $this->_dbid . \'=\' . intval($_GET[\'id\']);'."\r\n");
                fwrite($php_fileCrud,'           $datas               = \em_db::row($query);'."\r\n");
                fwrite($php_fileCrud,'           list(, $mediaId)     = explode(\'://\', $datas[\'visual\']);'."\r\n");
                fwrite($php_fileCrud,'           $datas[\'plates\']     = $this->formatPlates($datas[\'plates\'], intval($_GET[\'id\']));'."\r\n");
                fwrite($php_fileCrud,'           $datas[\'offer_type\'] = $this->formatType($datas[\'offer_type\'], intval($_GET[\'id\']));'."\r\n");
                fwrite($php_fileCrud,'           $datas[\'visual\']     = "<img src= \'" . getFileUrl($mediaId) . "\' alt=\'" . getFileUrl($mediaId) . "\'/>";'."\r\n");
                fwrite($php_fileCrud,'           return $datas;'."\r\n");
                fwrite($php_fileCrud,'       }'."\r\n");
                fwrite($php_fileCrud,"\r\n");
                fwrite($php_fileCrud,'       /**'."\r\n");
                fwrite($php_fileCrud,'        * format Plates'."\r\n");
                fwrite($php_fileCrud,'        * @param  string $value'."\r\n");
                fwrite($php_fileCrud,'        * @param  int    $elementId'."\r\n");
                fwrite($php_fileCrud,'        *'."\r\n");
                fwrite($php_fileCrud,'        * @return string'."\r\n");
                fwrite($php_fileCrud,'        */'."\r\n");
                fwrite($php_fileCrud,'        public function formatPlates($value, $elementId)'."\r\n");
                fwrite($php_fileCrud,'        {'."\r\n");
                fwrite($php_fileCrud,'             $query = \'SELECT plate_name \''."\r\n");
                fwrite($php_fileCrud,'             . \'FROM specifs_plates \''."\r\n");
                fwrite($php_fileCrud,'             . \'WHERE id_plate IN (\' . implode(\',\', array_filter(explode(\'!\', $value))) . \') \''."\r\n");
                fwrite($php_fileCrud,'             . \'ORDER BY plate_name\';'."\r\n");
                fwrite($php_fileCrud,'             $plates = implode(\', \', \em_db::ids($query));'."\r\n");
                fwrite($php_fileCrud,'             return $plates;'."\r\n");
                fwrite($php_fileCrud,'        }'."\r\n");
                fwrite($php_fileCrud,"\r\n");
                fwrite($php_fileCrud,'       /**'."\r\n");
                fwrite($php_fileCrud,'        * format types'."\r\n");
                fwrite($php_fileCrud,'        * @param  string $value'."\r\n");
                fwrite($php_fileCrud,'        * @param  int    $elementId'."\r\n");
                fwrite($php_fileCrud,'        *'."\r\n");
                fwrite($php_fileCrud,'        * @return string'."\r\n");
                fwrite($php_fileCrud,'        */'."\r\n");
                fwrite($php_fileCrud,'        public function formatType($value, $elementId)'."\r\n");
                fwrite($php_fileCrud,'        {'."\r\n");
                fwrite($php_fileCrud,'             return $this->products[$value];'."\r\n");
                fwrite($php_fileCrud,'        }'."\r\n");
                fwrite($php_fileCrud,' }');
            }
            fclose($php_fileCrud);



        //fichier portant le nom du menuNew.class.php qui se trouve dans le dossier class/tools/crud via la checkbox Nouvelle section avec un formulaire
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileSectionFormulaire = 'menuNew.class.php';
        $php_fileSectionFormulaire = fopen($fileSectionFormulaire, 'w+');
        if (filesize($fileSectionFormulaire) > 0) {
            $contents = fread($php_fileSectionFormulaire, filesize($fileSectionFormulaire));

        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileSectionFormulaire,'<?php'."\r\n");
            fwrite($php_fileSectionFormulaire,'namespace Addon\\'.$identifiant.';'."\r\n");
            fwrite($php_fileSectionFormulaire,'require_once \em_misc::getClassPath() . \'/core/Emajine_API/Emajine_2C.class.php\';'."\r\n");
            fwrite($php_fileSectionFormulaire,'/**'."\r\n");
            fwrite($php_fileSectionFormulaire,' * briochin'."\r\n");
            fwrite($php_fileSectionFormulaire,' * Gestion du menu specifique Briochin'."\r\n");
            fwrite($php_fileSectionFormulaire,' *'."\r\n");
            fwrite($php_fileSectionFormulaire,' *'."\r\n");
            fwrite($php_fileSectionFormulaire,' * @author Herizo de Medialibs <zo@medialibs.com>'."\r\n");
            fwrite($php_fileSectionFormulaire,' *'."\r\n");
            fwrite($php_fileSectionFormulaire,' * @since 2019-06-13 14:02 ' . "\r\n");
            fwrite($php_fileSectionFormulaire,' */'."\r\n");
            fwrite($php_fileSectionFormulaire,' class menuNew extends \Emajine_2C'."\r\n");
            fwrite($php_fileSectionFormulaire,' {'."\r\n");
            fwrite($php_fileSectionFormulaire,"\r\n");
            fwrite($php_fileSectionFormulaire,'     /**'."\r\n");
            fwrite($php_fileSectionFormulaire,'      * Les différentes zones'."\r\n");
            fwrite($php_fileSectionFormulaire,'      *'."\r\n");
            fwrite($php_fileSectionFormulaire,'      * @var array'."\r\n");
            fwrite($php_fileSectionFormulaire,'      */'."\r\n");
            fwrite($php_fileSectionFormulaire,'      public $_contenersZoneItems = array('."\r\n");
            fwrite($php_fileSectionFormulaire,'          \'SubMenu\' => array('."\r\n");
            fwrite($php_fileSectionFormulaire,'              \'first\'  => array(\'label\' => \'First\')'."\r\n");
            fwrite($php_fileSectionFormulaire,'          )'."\r\n");
            fwrite($php_fileSectionFormulaire,'      );'."\r\n");
            fwrite($php_fileSectionFormulaire,"\r\n");
            fwrite($php_fileSectionFormulaire,'      /**'."\r\n");
            fwrite($php_fileSectionFormulaire,'       * La zone à afficher par défaut'."\r\n");
            fwrite($php_fileSectionFormulaire,'       *'."\r\n");
            fwrite($php_fileSectionFormulaire,'       * @var string'."\r\n");
            fwrite($php_fileSectionFormulaire,'       */'."\r\n");
            fwrite($php_fileSectionFormulaire,'       public $_contenersZoneDefaultItem = \'my2Ccontent\';'."\r\n");
            fwrite($php_fileSectionFormulaire,"\r\n");
            fwrite($php_fileSectionFormulaire,'       /**'."\r\n");
            fwrite($php_fileSectionFormulaire,'        * Retourne la description du 2C'."\r\n");
            fwrite($php_fileSectionFormulaire,'        * Cette méthode est prévue pour être overloadé dans les classes enfants'."\r\n");
            fwrite($php_fileSectionFormulaire,'        *'."\r\n");
            fwrite($php_fileSectionFormulaire,'        * @return string'."\r\n");
            fwrite($php_fileSectionFormulaire,'        */'."\r\n");
            fwrite($php_fileSectionFormulaire,'        protected function getContentDescription()'."\r\n");
            fwrite($php_fileSectionFormulaire,'        {'."\r\n");
            fwrite($php_fileSectionFormulaire,'             return \'Ma description\';'."\r\n");
            fwrite($php_fileSectionFormulaire,'        }'."\r\n");
            fwrite($php_fileSectionFormulaire,"\r\n");
            fwrite($php_fileSectionFormulaire,'       /**'."\r\n");
            fwrite($php_fileSectionFormulaire,'        * First Item'."\r\n");
            fwrite($php_fileSectionFormulaire,'        *'."\r\n");
            fwrite($php_fileSectionFormulaire,'        * @return string'."\r\n");
            fwrite($php_fileSectionFormulaire,'        */'."\r\n");
            fwrite($php_fileSectionFormulaire,'        public function _first()'."\r\n");
            fwrite($php_fileSectionFormulaire,'        {'."\r\n");
            fwrite($php_fileSectionFormulaire,'             return $this->_getContentForm(\'generateForm\', \'onSave\', \'getDefaultValue\');'."\r\n");
            fwrite($php_fileSectionFormulaire,'        }'."\r\n");
            fwrite($php_fileSectionFormulaire,"\r\n");
            fwrite($php_fileSectionFormulaire,'       /**'."\r\n");
            fwrite($php_fileSectionFormulaire,'        * Récupérer le template à afficher'."\r\n");
            fwrite($php_fileSectionFormulaire,'        *'."\r\n");
            fwrite($php_fileSectionFormulaire,'        * @return string'."\r\n");
            fwrite($php_fileSectionFormulaire,'        */'."\r\n");
            fwrite($php_fileSectionFormulaire,'        public function generateForm($form)'."\r\n");
            fwrite($php_fileSectionFormulaire,'        {'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement(\'fieldset\', \'Title of screen\');'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement('."\r\n");
            fwrite($php_fileSectionFormulaire,'               \'text\','."\r\n");
            fwrite($php_fileSectionFormulaire,'               \'myField\','."\r\n");
            fwrite($php_fileSectionFormulaire,'               \'Mon champ\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                array(),'."\r\n");
            fwrite($php_fileSectionFormulaire,'                true,'."\r\n");
            fwrite($php_fileSectionFormulaire,'               \'<div class=\'description\'>Description ici</div>\''."\r\n");
            fwrite($php_fileSectionFormulaire,'            );'."\r\n");
            fwrite($php_fileSectionFormulaire,'            // Ajouter un champ date'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement('."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'date\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'date\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'Mon champ\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                 array(),'."\r\n");
            fwrite($php_fileSectionFormulaire,'                 true,'."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'<div class=\'description\'>Description ici</div>\''."\r\n");
            fwrite($php_fileSectionFormulaire,'            );'."\r\n");
            fwrite($php_fileSectionFormulaire,'            // Ajouter un radio'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement('."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'radM\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'nom_champ\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'libelle_champ\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                array(\'values\' => array(1 => \'Oui\',0 =>\'Non\'), \'useNumericKey\' => true), true'."\r\n");
            fwrite($php_fileSectionFormulaire,'            );'."\r\n");
            fwrite($php_fileSectionFormulaire,'            // Ajouter un checkbox'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement('."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'mChe\','."\r\n"."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'mon_checkbox\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'libelle_champ\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                array(\'values\' => array(1 => \'Oui\',0 =>\'Non\'), \'checked\' => array(), \'useNumericKey\' => true), true'."\r\n");
            fwrite($php_fileSectionFormulaire,'            );'."\r\n");
            fwrite($php_fileSectionFormulaire,'            // Ajout d\'un champ de type ressource'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement('."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'iRes\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'visual\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'Visuel\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                array(\'ressource\' => \'media\'), true'."\r\n");
            fwrite($php_fileSectionFormulaire,'            );'."\r\n");
            fwrite($php_fileSectionFormulaire,'            // Ajout d\'un champ de type ressource'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement('."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'mRes\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'visuals\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'Visuels\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                 array('."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'value\'     => $valeurParDefaut,'."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'ressource\' => \'media\', //article, media, news, form, map, link, poll, …'."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'linklabel\' => \'linklabel\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'js\'        => $js'."\r\n");
            fwrite($php_fileSectionFormulaire,'            ),'."\r\n");
            fwrite($php_fileSectionFormulaire,'            true'."\r\n");
            fwrite($php_fileSectionFormulaire,'            );'."\r\n");
            fwrite($php_fileSectionFormulaire,'            // Ajouter une description'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement(\'description\', \'Indiquez ici votre nom et votre prénom\');'."\r\n");
            fwrite($php_fileSectionFormulaire,'            // Ajouter un champ de type select'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement(\'select\', \'civility\', \'Civilité\', array(\'values\' => array(\'M\' => \'Monsieur\', \'Mme\' => \'Madamme\')), true);');
            fwrite($php_fileSectionFormulaire,'            // Ajouter un champ de type textarea'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement(\'area\', \'address\', \'Adresse\', array(\'rows\' => 4));'."\r\n");
            fwrite($php_fileSectionFormulaire,"\r\n");
            fwrite($php_fileSectionFormulaire,'            // Ajouter un champ de type file'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement('."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'file\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'rapportFile\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'Importer un fichier\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                 array(\'accept\' => \'csv\'),'."\r\n");
            fwrite($php_fileSectionFormulaire,'                 true,'."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'<span>Veuillez sélectionner un fichier au format CSV (encodage UTF-8)<br />Les données doivent être séparées par un ","</span>\''."\r\n");
            fwrite($php_fileSectionFormulaire,'            );'."\r\n");
            fwrite($php_fileSectionFormulaire,'            // Ajouter un champ de selection d\'utilisateur'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement('."\r\n");
            fwrite($php_fileSectionFormulaire,'                "seDb" , "nom_du_champ" ,"Titre du champ" ,'."\r\n");
            fwrite($php_fileSectionFormulaire,'                 array('."\r\n");
            fwrite($php_fileSectionFormulaire,'                    "dbtable"     => "acteur" ,'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    "dbid"        => "id_acteur" ,'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    "dblabel"     => "CONCAT(nom , \' \' , prenom)" ,'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    "selected"    => $id_de_l_utilisateur_selectionne ,'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    "dbcondition" => " login IS NOT NULL " ,'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'behaviour\'  => \'layer\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                 )'."\r\n");
            fwrite($php_fileSectionFormulaire,'            );'."\r\n");
            fwrite($php_fileSectionFormulaire,'            // Ajouter un champ date'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement(\'capT\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                 \'Captcha\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                 \'Mon captcha\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                 array('."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'captchatype\' => \'recaptcha\', //operation,question\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'style\'       => \'clas_css\','."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'maxlength\'   => $maxLegth,'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'size\'        => $size,'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'value\'       => $value'."\r\n");
            fwrite($php_fileSectionFormulaire,'                 ),'."\r\n");
            fwrite($php_fileSectionFormulaire,'                 true,'."\r\n");
            fwrite($php_fileSectionFormulaire,'                \'<div class=\'description\'>Description ici</div>\''."\r\n");
            fwrite($php_fileSectionFormulaire,'            );'."\r\n");
            fwrite($php_fileSectionFormulaire,'            // Ajouter un champ de type hidden'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement(\'hidden\', \'champ_hidden\', \'valeur\');'."\r\n");
            fwrite($php_fileSectionFormulaire,"\r\n");
            fwrite($php_fileSectionFormulaire,'            // Ajouter un menu déroulant avec lecture en base de données'."\r\n");
            fwrite($php_fileSectionFormulaire,'            $form->addElement(\'seDb\', \'myDbSelect\', \'Mon selection\', array('."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'dbtable\'     => \'addons\' , // : nom de la table (accepte des jointures)'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'dbid\'        => \'id\' , // : champ "identifiant"'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'dblabel\'     => \'name\' , // : champ "label"'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'dbcondition\' => \'1\', // : condition MySQL à appliquer'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'dbdistinct\'  => true , // : si true, ajoute un ‘DISTINCT’ sur le label'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    // \'selected\'    => array(1) , // : tableau contenant les valeurs sélectionnées par défaut'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    // \'size\'         => 20 , // : nombre d’options affichées'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'multiple\'    => false , // : est ce un champ multiple ?'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'required\'    => false , // : si true, le select ne proposera pas de valeur vide'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    // \'js\'           => $dbJs , // : javascript pour l\'attribut onchange'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    \'behaviour\'   => \'layer\' , // : si "layer", la sélection se fera par l’intermédiaire d’un layer'."\r\n");
            fwrite($php_fileSectionFormulaire,'                    // \'dborder\'      => \'id DESC\' // : partie “ORDER BY” de la requête'."\r\n");
            fwrite($php_fileSectionFormulaire,'                )'."\r\n");
            fwrite($php_fileSectionFormulaire,'            );'."\r\n");
            fwrite($php_fileSectionFormulaire,'       }'."\r\n");
            fwrite($php_fileSectionFormulaire,"\r\n");
            fwrite($php_fileSectionFormulaire,'     /**'."\r\n");
            fwrite($php_fileSectionFormulaire,'      * Enregistrement des données à la validation du formulaire'."\r\n");
            fwrite($php_fileSectionFormulaire,'      *'."\r\n");
            fwrite($php_fileSectionFormulaire,'      * @return null'."\r\n");
            fwrite($php_fileSectionFormulaire,'      */'."\r\n");
            fwrite($php_fileSectionFormulaire,'      public function onSave()'."\r\n");
            fwrite($php_fileSectionFormulaire,'      {'."\r\n");
            fwrite($php_fileSectionFormulaire,"\r\n");
            fwrite($php_fileSectionFormulaire,'      }'."\r\n");
            fwrite($php_fileSectionFormulaire,"\r\n");
            fwrite($php_fileSectionFormulaire,'      /**'."\r\n");
            fwrite($php_fileSectionFormulaire,'       * Récupération des données par défaut en base'."\r\n");
            fwrite($php_fileSectionFormulaire,'       *'."\r\n");
            fwrite($php_fileSectionFormulaire,'       * @return array'."\r\n");
            fwrite($php_fileSectionFormulaire,'       */'."\r\n");
            fwrite($php_fileSectionFormulaire,'       public function getDefaultValue()'."\r\n");
            fwrite($php_fileSectionFormulaire,'       {'."\r\n");
            fwrite($php_fileSectionFormulaire,"\r\n");
            fwrite($php_fileSectionFormulaire,'       }'."\r\n");
            fwrite($php_fileSectionFormulaire,' }'."\r\n");

        }
        fclose($php_fileSectionFormulaire);

        //fichier emajine_menu_new.xml qui se trouve dans le dossier class/menu/new via la checkbox "Nouvelle section avec un formulaire"

        //On crée un DomDocument
        $emajineMenu = new DOMDocument("1.0", "UTF-8");
        //Le format du fichier Xml est correct
        $emajineMenu->formatOutput= true;

        //on crée la balise parent <emajine_specif>
        $emajine_specif = $emajineMenu->createElement('emajine_specif');
        //on ferme la balise parent </data>
        $emajineMenu->appendChild($emajine_specif);

        //On crée la la balise enfant commentaire <!--
        $commentaire = $emajineMenu->createComment('@changelog 2019-10-02 [OPTIM] (Hasina) Optimisation des fonctionnalités - Générateur d\'add-ons - Medialibs');
        //On ferme la balise commentaire --> de la balise parent <emajine-specif>
        $emajine_specif->appendChild($commentaire);

        //On crée la balise <libelle> et on lui ajoute la valeur du formulire "nom du menu" de la checkbox "Nouvelle section avec formulaire"
        $libelle = $emajineMenu->createElement('libelle', $nomMenuFormulaire );
        //On ferme la balise </libelle> de la balise parent <emajine-specif>
        $emajine_specif->appendChild($libelle);

         //on crée la balise <parent> et on lui ajoute la valeur "13"
         $parent = $emajineMenu->createElement('parent',13);
         //on ferme la balise </parent> de la balise parent <emajine-specif>
         $emajine_specif->appendChild($parent);

         //on crée la balise <script> et on lui ajoute le chemin d'accés du fichier menuNew.class.php
         $script = $emajineMenu->createElement('script', 'addons/test/class/menus/new/'.$fileSectionFormulaire);
         //on ferme la balise </script> de la balise parent <emajine-specif>
         $emajine_specif->appendChild($script);

        // on enregiste le fichier sous le nom de emajine_menu_new.xml
        $emajineMenu->save('emajine_menu_new.xml');



        //fichier portant le nom du hook_publicSite.class.php qui se trouve dans le dossier class/hooks/core via la checkbox "Web Service"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileHooksWebService = 'hook_PublicSite.class.php';
        $php_fileHooksWebService = fopen($fileHooksWebService, 'w+');
        if (filesize($fileHooksWebService) > 0) {
            $contents = fread($php_fileHooksWebService, filesize($fileHooksWebService));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileHooksWebService,'<?php'."\r\n");
            fwrite($php_fileHooksWebService,'namespace Addon\\'.$identifiant.';'."\r\n");
            fwrite($php_fileHooksWebService,'require_once __DIR__ . \'/../../tools/Controller.php\';'."\r\n");
            fwrite($php_fileHooksWebService,"\r\n");
            fwrite($php_fileHooksWebService,'/**'."\r\n");
            fwrite($php_fileHooksWebService,' * Gestion des logs'."\r\n");
            fwrite($php_fileHooksWebService,' *'."\r\n");
            fwrite($php_fileHooksWebService,' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>' . "\r\n");
            fwrite($php_fileHooksWebService,' *'."\r\n");
            fwrite($php_fileHooksWebService,' * @since' . $creation['date_de_creation'] . "\r\n");
            fwrite($php_fileHooksWebService,' */'."\r\n");
            fwrite($php_fileHooksWebService,' class hook_PublicSite extends \Emajine_Hooks'."\r\n");
            fwrite($php_fileHooksWebService,' {'."\r\n");
            fwrite($php_fileHooksWebService,"\r\n");
            fwrite($php_fileHooksWebService,'     const API_ROOT = \'api\';'."\r\n");
            fwrite($php_fileHooksWebService,'     /**'."\r\n");
            fwrite($php_fileHooksWebService,'      * Intervention lors de l\'initialisation du site public'."\r\n");
            fwrite($php_fileHooksWebService,'      *'."\r\n");
            fwrite($php_fileHooksWebService,'      * @return null'."\r\n");
            fwrite($php_fileHooksWebService,'      */'."\r\n");
            fwrite($php_fileHooksWebService,'      public function onInit()'."\r\n");
            fwrite($php_fileHooksWebService,'      {'."\r\n");
            fwrite($php_fileHooksWebService,'           $url = explode(\'/\', \em_misc::ru());'."\r\n");
            fwrite($php_fileHooksWebService,'           if ($url[1] != self::API_ROOT) {'."\r\n");
            fwrite($php_fileHooksWebService,'               return;'."\r\n");
            fwrite($php_fileHooksWebService,'           }'."\r\n");
            fwrite($php_fileHooksWebService,'           $apiController = new Controller();'."\r\n");
            fwrite($php_fileHooksWebService,'           $apiController->start();'."\r\n");
            fwrite($php_fileHooksWebService,'      }'."\r\n");
            fwrite($php_fileHooksWebService,' }'."\r\n");
        }
        fclose($php_fileHooksWebService);

        //fichier portant le nom "API.class.php" qui se trouve dans le dossier class/tools/ via la checkbox "Web Service"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileAPIWebService = 'API.class.php';
        $php_fileAPIWebService = fopen($fileAPIWebService, 'w+');
        if (filesize($fileAPIWebService) > 0) {
            $contents = fread($php_fileAPIWebService, filesize($fileAPIWebService));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileAPIWebService,'<?php'."\r\n");
            fwrite($php_fileAPIWebService,'namespace Addon\\'.$identifiant. ';'."\r\n");
            fwrite($php_fileAPIWebService,'require_once \em_misc::getSpecifPath() . \'addons/'.$identifiant.'/class/tools/HTTPResponse.class.php\';'."\r\n");
            fwrite($php_fileAPIWebService,'require_once \em_misc::getSpecifPath() . \'addons/'.$identifiant.'/class/tools/ELog.php\';'."\r\n");
            fwrite($php_fileAPIWebService,"\r\n");
            fwrite($php_fileAPIWebService,'/**'."\r\n");
            fwrite($php_fileAPIWebService,' * Class API'."\r\n");
            fwrite($php_fileAPIWebService,' *'."\r\n");
            fwrite($php_fileAPIWebService,' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>' . "\r\n");
            fwrite($php_fileAPIWebService,' *'."\r\n");
            fwrite($php_fileAPIWebService,' * @since' . $creation['date_de_creation'] . "\r\n");
            fwrite($php_fileAPIWebService,' */'."\r\n");
            fwrite($php_fileAPIWebService,' class API'."\r\n");
            fwrite($php_fileAPIWebService,' {'."\r\n");
            fwrite($php_fileAPIWebService,'     protected $response;'."\r\n");
            fwrite($php_fileAPIWebService,'     protected $method = \'POST\';'."\r\n");
            fwrite($php_fileAPIWebService,'     const HTTP_STATUS_401 = 401;'."\r\n");
            fwrite($php_fileAPIWebService,'     const HTTP_STATUS_403 = 403;'."\r\n");
            fwrite($php_fileAPIWebService,"\r\n");
            fwrite($php_fileAPIWebService,'  /**'."\r\n");
            fwrite($php_fileAPIWebService,'   * methode repondre'."\r\n");
            fwrite($php_fileAPIWebService,'   *'."\r\n");
            fwrite($php_fileAPIWebService,'   * @param  int $statut              [description]'."\r\n");
            fwrite($php_fileAPIWebService,'   * @param  int $code                [description]'."\r\n");
            fwrite($php_fileAPIWebService,'   * @param  array $data              [description]'."\r\n");
            fwrite($php_fileAPIWebService,'   * @param  string $supplementMessage [description]'."\r\n");
            fwrite($php_fileAPIWebService,'   *'."\r\n");
            fwrite($php_fileAPIWebService,'   * @return null                    [description]'."\r\n");
            fwrite($php_fileAPIWebService,'   */'."\r\n");
            fwrite($php_fileAPIWebService,'   public function respond($statut, $code, array $data = null, $supplementMessage = null)'."\r\n");
            fwrite($php_fileAPIWebService,'   {'."\r\n");
            fwrite($php_fileAPIWebService,'       if (!is_null($data)) {'."\r\n");
            fwrite($php_fileAPIWebService,'           $this->response = $data;'."\r\n");
            fwrite($php_fileAPIWebService,'       } else {'."\r\n");
            fwrite($php_fileAPIWebService,'           $this->response = array("response" => (new HTTPResponse())->response($statut, $code, $supplementMessage));'."\r\n");
            fwrite($php_fileAPIWebService,'       }'."\r\n");
            fwrite($php_fileAPIWebService,'       header(\'Content-Type: application/json\');'."\r\n");
            fwrite($php_fileAPIWebService,'       ELog::create(\'responseLogs\', \em_misc::getSpecifPath() . \'logs/api/\', \'response_\' . date(\'Y-m-d\'), false, true);'."\r\n");
            fwrite($php_fileAPIWebService,'       ELog::get(\'responseLogs\')->file(\'######################\');'."\r\n");
            fwrite($php_fileAPIWebService,'       ELog::get(\'responseLogs\')->file(\'## \' . date(\'d/m/Y H:i:s\') . \' ##\');'."\r\n");
            fwrite($php_fileAPIWebService,'       ELog::get(\'responseLogs\')->file(json_encode($this->response));'."\r\n");
            fwrite($php_fileAPIWebService,"\r\n");
            fwrite($php_fileAPIWebService,'       \em_output::echoAndExit(json_encode($this->response));'."\r\n");
            fwrite($php_fileAPIWebService,'  }'."\r\n");
            fwrite($php_fileAPIWebService,"\r\n");
            fwrite($php_fileAPIWebService,'  /**'."\r\n");
            fwrite($php_fileAPIWebService,'   * Obtention de l\'id d\'un utilisateur avec l\'access token'."\r\n");
            fwrite($php_fileAPIWebService,'   *'."\r\n");
            fwrite($php_fileAPIWebService,'   * @return string'."\r\n");
            fwrite($php_fileAPIWebService,'   */'."\r\n");
            fwrite($php_fileAPIWebService,'   protected function idUser($accessToken)'."\r\n");
            fwrite($php_fileAPIWebService,'   {'."\r\n");
            fwrite($php_fileAPIWebService,'       if (!is_null($accessToken)) {'."\r\n");
            fwrite($php_fileAPIWebService,'           $query = \'SELECT \''."\r\n");
            fwrite($php_fileAPIWebService,'               . \'id_acteur \''."\r\n");
            fwrite($php_fileAPIWebService,'               . \'FROM acteur \''."\r\n");
            fwrite($php_fileAPIWebService,'               . \'WHERE MD5(CONCAT(login, passwd, date_crea, "\' . $_SESSION[self::getClientIP()][\'TOKEN_DATE\'] . \'")) = "\' . $accessToken . \'"\';'."\r\n");
            fwrite($php_fileAPIWebService,'           return \em_db::one($query);'."\r\n");
            fwrite($php_fileAPIWebService,'       }'."\r\n");
            fwrite($php_fileAPIWebService,"\r\n");
            fwrite($php_fileAPIWebService,'  }'."\r\n");
            fwrite($php_fileAPIWebService,'   /**'."\r\n");
            fwrite($php_fileAPIWebService,'    * tester si l\'access token correspond à un user'."\r\n");
            fwrite($php_fileAPIWebService,'    *'."\r\n");
            fwrite($php_fileAPIWebService,'    * @return boolean [description]'."\r\n");
            fwrite($php_fileAPIWebService,'    */'."\r\n");
            fwrite($php_fileAPIWebService,'    protected function isUserAccessToken()'."\r\n");
            fwrite($php_fileAPIWebService,'    {'."\r\n");
            fwrite($php_fileAPIWebService,'         if ($this->method == \'POST\') {'."\r\n");
            fwrite($php_fileAPIWebService,'            $accessToken = $_POST[\'access_token\'];'."\r\n");
            fwrite($php_fileAPIWebService,'         } else {'."\r\n");
            fwrite($php_fileAPIWebService,'            $accessToken = $_GET[\'access_token\'];'."\r\n");
            fwrite($php_fileAPIWebService,'         }'."\r\n");
            fwrite($php_fileAPIWebService,'         return !is_null($this->idUser($accessToken));'."\r\n");
            fwrite($php_fileAPIWebService,'    }'."\r\n");
            fwrite($php_fileAPIWebService,"\r\n");
            fwrite($php_fileAPIWebService,'  /**'."\r\n");
            fwrite($php_fileAPIWebService,'   * Tester si il y a un champ non existant'."\r\n");
            fwrite($php_fileAPIWebService,'   *'."\r\n");
            fwrite($php_fileAPIWebService,'   * @param      array  $datas      The datas'."\r\n");
            fwrite($php_fileAPIWebService,'   * @param      array  $allFields  All fields'."\r\n");
            fwrite($php_fileAPIWebService,'   *'."\r\n");
            fwrite($php_fileAPIWebService,'   *  @return null'."\r\n");
            fwrite($php_fileAPIWebService,'   */'."\r\n");
            fwrite($php_fileAPIWebService,'   public function checkIfexist($datas, $allFields)'."\r\n");
            fwrite($php_fileAPIWebService,'   {'."\r\n");
            fwrite($php_fileAPIWebService,'       foreach ($datas as $key => $value) {'."\r\n");
            fwrite($php_fileAPIWebService,'           if (!in_array($key, $allFields)) {'."\r\n");
            fwrite($php_fileAPIWebService,'               $errorCode = \'1000\' . ($key + 1);'."\r\n");
            fwrite($php_fileAPIWebService,'               $this->respond('."\r\n");
            fwrite($php_fileAPIWebService,'                         200,'."\r\n");
            fwrite($php_fileAPIWebService,'                        null,'."\r\n");
            fwrite($php_fileAPIWebService,'                   ['."\r\n");
            fwrite($php_fileAPIWebService,'                       \'errorCode\' => $errorCode,'."\r\n");
            fwrite($php_fileAPIWebService,'                       \'fieldName\' => $key,'."\r\n");
            fwrite($php_fileAPIWebService,'                       \'message\' => \'Ce champ n\\\'est pas autorisé\','."\r\n");
            fwrite($php_fileAPIWebService,'                   ]'."\r\n");
            fwrite($php_fileAPIWebService,'               );'."\r\n");
            fwrite($php_fileAPIWebService,'           }'."\r\n");
            fwrite($php_fileAPIWebService,'       }'."\r\n");
            fwrite($php_fileAPIWebService,'  }'."\r\n");
            fwrite($php_fileAPIWebService,"\r\n");
            fwrite($php_fileAPIWebService,'  /**'."\r\n");
            fwrite($php_fileAPIWebService,'   * Récuperer l\'adresse IP du client'."\r\n");
            fwrite($php_fileAPIWebService,'   *'."\r\n");
            fwrite($php_fileAPIWebService,'   * @return [type] [description]'."\r\n");
            fwrite($php_fileAPIWebService,'   */'."\r\n");
            fwrite($php_fileAPIWebService,'   public static function getClientIP()'."\r\n");
            fwrite($php_fileAPIWebService,'   {'."\r\n");
            fwrite($php_fileAPIWebService,'       $ipaddress = \'UNKNOWN\';'."\r\n");
            fwrite($php_fileAPIWebService,'       $keys = array(\'HTTP_CLIENT_IP\', \'HTTP_X_FORWARDED_FOR\', \'HTTP_X_FORWARDED\', \'HTTP_FORWARDED_FOR\', \'HTTP_FORWARDED\', \'REMOTE_ADDR\');'."\r\n");
            fwrite($php_fileAPIWebService,'       foreach ($keys as $k) {'."\r\n");
            fwrite($php_fileAPIWebService,'           if (isset($_SERVER[$k]) && !empty($_SERVER[$k]) && filter_var($_SERVER[$k], FILTER_VALIDATE_IP)) {'."\r\n");
            fwrite($php_fileAPIWebService,'               $ipaddress = $_SERVER[$k];'."\r\n");
            fwrite($php_fileAPIWebService,'               break;'."\r\n");
            fwrite($php_fileAPIWebService,'           }'."\r\n");
            fwrite($php_fileAPIWebService,'       }'."\r\n");
            fwrite($php_fileAPIWebService,'       return $ipaddress;'."\r\n");
            fwrite($php_fileAPIWebService,'   }'."\r\n");
            fwrite($php_fileAPIWebService,' }'."\r\n");

        }
        fclose($php_fileAPIWebService);




        //fichier portant le nom API.class.php qui se trouve dans le dossier class/tools/ via la checkbox "Web Service"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileControllerWebService = 'Controller.class.php';
        $php_fileControllerWebService = fopen($fileControllerWebService, 'w+');
        if (filesize($fileControllerWebService) > 0) {
            $contents = fread($php_fileControllerWebService, filesize($fileControllerWebService));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileControllerWebService,'<?php'."\r\n");
            fwrite($php_fileControllerWebService,'namespace Addon\\'.$identifiant.';'."\r\n");
            fwrite($php_fileControllerWebService,'require_once __DIR__ . \'/Factory.php\';'."\r\n");
            fwrite($php_fileControllerWebService,"\r\n");
            fwrite($php_fileControllerWebService,'/**'."\r\n");
            fwrite($php_fileControllerWebService,' * Controleur de l\'API'."\r\n");
            fwrite($php_fileControllerWebService,' *'."\r\n");
            fwrite($php_fileControllerWebService,' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>' . "\r\n");
            fwrite($php_fileControllerWebService,' *'."\r\n");
            fwrite($php_fileControllerWebService,' * @since' . $creation['date_de_creation'] . "\r\n");
            fwrite($php_fileControllerWebService,' */'."\r\n");
            fwrite($php_fileControllerWebService,'class Controller'."\r\n");
            fwrite($php_fileControllerWebService,'{'."\r\n");
            fwrite($php_fileControllerWebService,'     protected $factory;'."\r\n");
            fwrite($php_fileControllerWebService,"\r\n");
            fwrite($php_fileControllerWebService,'     /**'."\r\n");
            fwrite($php_fileControllerWebService,'      * constructeur'."\r\n");
            fwrite($php_fileControllerWebService,'      */'."\r\n");
            fwrite($php_fileControllerWebService,'      public function __construct()'."\r\n");
            fwrite($php_fileControllerWebService,'      {'."\r\n");
            fwrite($php_fileControllerWebService,'          $this->factory = new Factory();'."\r\n");
            fwrite($php_fileControllerWebService,'      }'."\r\n");
            fwrite($php_fileControllerWebService,"\r\n");
            fwrite($php_fileControllerWebService,'      /**'."\r\n");
            fwrite($php_fileControllerWebService,'       * Methode start du controleur de l\'API rest'."\r\n");
            fwrite($php_fileControllerWebService,'       *'."\r\n");
            fwrite($php_fileControllerWebService,'       * @return null [description]'."\r\n");
            fwrite($php_fileControllerWebService,'       */'."\r\n");
            fwrite($php_fileControllerWebService,'       public function start()'."\r\n");
            fwrite($php_fileControllerWebService,'       {'."\r\n");
            fwrite($php_fileControllerWebService,'           $APIRest = $this->factory->getAPIMethod();'."\r\n");
            fwrite($php_fileControllerWebService,'           if ($APIRest) {'."\r\n");
            fwrite($php_fileControllerWebService,'               $APIRest->doRequest();'."\r\n");
            fwrite($php_fileControllerWebService,'           }'."\r\n");
            fwrite($php_fileControllerWebService,'       }'."\r\n");
            fwrite($php_fileControllerWebService,'}'."\r\n");
        }
        fclose($php_fileControllerWebService);


        //fichier portant le nom de "Elog.php" qui se trouve dans le dossier class/tools via la checkbox "Web service"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileELogWebService = 'Elog.php';
        $php_fileELogWebService = fopen($fileELogWebService, 'w+');
        if (filesize($fileELogWebService) > 0) {
            $contents = fread($php_fileELogWebService, filesize($fileELogWebService));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileELogWebService,'<?php'."\r\n");
            fwrite($php_fileELogWebService,'namespace Addon\\'.$identifiant.';'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'/**'."\r\n");
            fwrite($php_fileELogWebService,' * Gestion des logs'."\r\n");
            fwrite($php_fileELogWebService,' *'."\r\n");
            fwrite($php_fileELogWebService,' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>'."\r\n");
            fwrite($php_fileELogWebService,' *'."\r\n");
            fwrite($php_fileELogWebService,' * @since' . $creation['date_de_creation'] ."\r\n");
            fwrite($php_fileELogWebService,' */'."\r\n");
            fwrite($php_fileELogWebService,' class ELog'."\r\n");
            fwrite($php_fileELogWebService,' {'."\r\n");
            fwrite($php_fileELogWebService,'     public static $userVd = array();'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'     private $rep; // répertoire du fichier de logs'."\r\n");
            fwrite($php_fileELogWebService,'     private $file; // fichier de logs'."\r\n");
            fwrite($php_fileELogWebService,'     private $mustDisplay; // affiche les logs sur la page'."\r\n");
            fwrite($php_fileELogWebService,'     private $sum; // résumé du processus'."\r\n");
            fwrite($php_fileELogWebService,'     public $is_active; // le processus de log est actif'."\r\n");
            fwrite($php_fileELogWebService,'     private $extension;'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'     private static $active = true; // Les logs sont actifs'."\r\n");
            fwrite($php_fileELogWebService,'     private static $elogs = array(); // tableau de toutes les entités ELog'."\r\n");
            fwrite($php_fileELogWebService,'     private static $currentELog; // dernier ELog utilisé'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'     /**'."\r\n");
            fwrite($php_fileELogWebService,'      *  Permet de créer une entité'."\r\n");
            fwrite($php_fileELogWebService,'      *'."\r\n");
            fwrite($php_fileELogWebService,'      * @param $id Identifiant de l\'entité, utile pour la récupération'."\r\n");
            fwrite($php_fileELogWebService,'      * @param String $log_path Répertoir des logs'."\r\n");
            fwrite($php_fileELogWebService,'      * @param String $file_name FileName'."\r\n");
            fwrite($php_fileELogWebService,'      * @param boolean $display Si true, affiche tous les logs sur la page (y compris les file)'."\r\n");
            fwrite($php_fileELogWebService,'      * @param boolean $active Si true, les logs seront actifs'."\r\n");
            fwrite($php_fileELogWebService,'      */'."\r\n");
            fwrite($php_fileELogWebService,'      public static function create($id, $log_path, $file_name, $display, $active, $extension = \'.txt\')'."\r\n");
            fwrite($php_fileELogWebService,'      {'."\r\n");
            fwrite($php_fileELogWebService,'          $elog = new ELog();'."\r\n");
            fwrite($php_fileELogWebService,'          self::makeDir($log_path);'."\r\n");
            fwrite($php_fileELogWebService,'          $elog->extension = $extension;'."\r\n");
            fwrite($php_fileELogWebService,'          $elog->rep = $log_path;'."\r\n");
            fwrite($php_fileELogWebService,'          $elog->file = $file_name . $elog->extension;'."\r\n");
            fwrite($php_fileELogWebService,'          $elog->mustDisplay = $display;'."\r\n");
            fwrite($php_fileELogWebService,'          $elog->is_active = $active;'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'          self::$currentELog = $elog;'."\r\n");
            fwrite($php_fileELogWebService,'          self::$elogs[$id] = $elog;'."\r\n");
            fwrite($php_fileELogWebService,'      }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'      public static function makeDir($log_path)'."\r\n");
            fwrite($php_fileELogWebService,'      {'."\r\n");
            fwrite($php_fileELogWebService,'          if (!is_dir($log_path)) {'."\r\n");
            fwrite($php_fileELogWebService,'              $dirs = explode(\'/\', substr($log_path, 1));'."\r\n");
            fwrite($php_fileELogWebService,'              $path = \'\';'."\r\n");
            fwrite($php_fileELogWebService,'              foreach ($dirs as $dir) {'."\r\n");
            fwrite($php_fileELogWebService,'                  $path .= \'/\' . $dir;'."\r\n");
            fwrite($php_fileELogWebService,'                  if (!is_dir($path)) {'."\r\n");
            fwrite($php_fileELogWebService,'                      mkdir($path);'."\r\n");
            fwrite($php_fileELogWebService,'                  }'."\r\n");
            fwrite($php_fileELogWebService,'              }'."\r\n");
            fwrite($php_fileELogWebService,'          }'."\r\n");
            fwrite($php_fileELogWebService,'      }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'      /**'."\r\n");
            fwrite($php_fileELogWebService,'       * Permet de récupérer l\'entité ELog définis par $id lors du ELog::create(...)'."\r\n");
            fwrite($php_fileELogWebService,'       * Si $id n\'est pas définis, on renvoie le dernier ELog utilisé.'."\r\n");
            fwrite($php_fileELogWebService,'       *'."\r\n");
            fwrite($php_fileELogWebService,'       * @param $id'."\r\n");
            fwrite($php_fileELogWebService,'       */'."\r\n");
            fwrite($php_fileELogWebService,'       public static function get($id = null)'."\r\n");
            fwrite($php_fileELogWebService,'       {'."\r\n");
            fwrite($php_fileELogWebService,'           if ($id && self::$elogs[$id]) {'."\r\n");
            fwrite($php_fileELogWebService,'               self::$currentELog = self::$elogs[$id];'."\r\n");
            fwrite($php_fileELogWebService,'               return self::$elogs[$id];'."\r\n");
            fwrite($php_fileELogWebService,'           }'."\r\n");
            fwrite($php_fileELogWebService,'           return self::$currentELog;'."\r\n");
            fwrite($php_fileELogWebService,'       }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'       /**'."\r\n");
            fwrite($php_fileELogWebService,'        * Affiche $message dans le fichier $file_name.'."\r\n");
            fwrite($php_fileELogWebService,'        * Si $file_name n\'est pas renseigné, le message est ajouté à $this->file'."\r\n");
            fwrite($php_fileELogWebService,'        *'."\r\n");
            fwrite($php_fileELogWebService,'        * @param String $message Message de logs'."\r\n");
            fwrite($php_fileELogWebService,'        * @param int $niveau Niveau d\'indentation'."\r\n");
            fwrite($php_fileELogWebService,'        * @param String $file_name Fichier où est ajouté $message'."\r\n");
            fwrite($php_fileELogWebService,'        */'."\r\n");
            fwrite($php_fileELogWebService,'        public function file($message, $niveau = 0, $file_name = null)'."\r\n");
            fwrite($php_fileELogWebService,'        {'."\r\n");
            fwrite($php_fileELogWebService,'            if ($this->is_active && self::$active) {'."\r\n");
            fwrite($php_fileELogWebService,'                if ($file_name) {'."\r\n");
            fwrite($php_fileELogWebService,'                    $file_name = (strpos($filename, '.') !== false) ? $file_name : $file_name . $this->extension;'."\r\n");
            fwrite($php_fileELogWebService,'                }'."\r\n");
            fwrite($php_fileELogWebService,'                $currentFile = ($file_name) ? $this->rep . \'/\' . $file_name : $this->rep . \'/\' . $this->file;'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'                if (!file_exists($currentFile)) {'."\r\n");
            fwrite($php_fileELogWebService,'                    $file = fopen($currentFile, "w+");'."\r\n");
            fwrite($php_fileELogWebService,'                    fclose($file);'."\r\n");
            fwrite($php_fileELogWebService,'                }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'                $this->display($this->getSpace($niveau) . $message);'."\r\n");
            fwrite($php_fileELogWebService,'                error_log($this->getSpace($niveau) . $message . "\r\n", 3, $currentFile);'."\r\n");
            fwrite($php_fileELogWebService,'            }'."\r\n");
            fwrite($php_fileELogWebService,'        }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'      /**'."\r\n");
            fwrite($php_fileELogWebService,'       * Affiche le message sur a page'."\r\n");
            fwrite($php_fileELogWebService,'       *'."\r\n");
            fwrite($php_fileELogWebService,'       * @param String $message Message à afficher'."\r\n");
            fwrite($php_fileELogWebService,'       * @param int $niveau Niveau d\'indentation'."\r\n");
            fwrite($php_fileELogWebService,'       */'."\r\n");
            fwrite($php_fileELogWebService,'       public function display($message, $niveau = 0)'."\r\n");
            fwrite($php_fileELogWebService,'       {'."\r\n");
            fwrite($php_fileELogWebService,'           if ($this->is_active && self::$active && $this->mustDisplay) {'."\r\n");
            fwrite($php_fileELogWebService,'               echo $this->getSpace($niveau) . $message . \'<br/>\';'."\r\n");
            fwrite($php_fileELogWebService,'           }'."\r\n");
            fwrite($php_fileELogWebService,'       }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'       /**'."\r\n");
            fwrite($php_fileELogWebService,'        * Ajoute une ligne au résumé'."\r\n");
            fwrite($php_fileELogWebService,'        *'."\r\n");
            fwrite($php_fileELogWebService,'        * @param String $message Message ajouté'."\r\n");
            fwrite($php_fileELogWebService,'        * @param int $niveau Niveau d\'indentation'."\r\n");
            fwrite($php_fileELogWebService,'        */'."\r\n");
            fwrite($php_fileELogWebService,'        public function sum($message, $niveau = 0)'."\r\n");
            fwrite($php_fileELogWebService,'        {'."\r\n");
            fwrite($php_fileELogWebService,'            if ($this->is_active && self::$active) {'."\r\n");
            fwrite($php_fileELogWebService,'                $this->sum .= $this->getSpace($niveau) . $message . "\r\n";'."\r\n");
            fwrite($php_fileELogWebService,'            }'."\r\n");
            fwrite($php_fileELogWebService,'        }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'        /**'."\r\n");
            fwrite($php_fileELogWebService,'         * Génère l\'indentation'."\r\n");
            fwrite($php_fileELogWebService,'         * @param int $niveau Niveau d\'indentation'."\r\n");
            fwrite($php_fileELogWebService,'         * @return Elog'."\r\n");
            fwrite($php_fileELogWebService,'         */'."\r\n");
            fwrite($php_fileELogWebService,'         public function getSpace($niveau)'."\r\n");
            fwrite($php_fileELogWebService,'         {'."\r\n");
            fwrite($php_fileELogWebService,'             $str = "";'."\r\n");
            fwrite($php_fileELogWebService,'             for ($i = 0; $i < $niveau; $i++) {'."\r\n");
            fwrite($php_fileELogWebService,'                  $str .= "\t";'."\r\n");
            fwrite($php_fileELogWebService,'             }'."\r\n");
            fwrite($php_fileELogWebService,'             return $str;'."\r\n");
            fwrite($php_fileELogWebService,'         }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'         /**'."\r\n");
            fwrite($php_fileELogWebService,'          * Génère le résumé'."\r\n");
            fwrite($php_fileELogWebService,'          */'."\r\n");
            fwrite($php_fileELogWebService,'          public function displaySum()'."\r\n");
            fwrite($php_fileELogWebService,'          {'."\r\n");
            fwrite($php_fileELogWebService,'              if ($this->is_active && self::$active) {'."\r\n");
            fwrite($php_fileELogWebService,'                  $b = $this->mustDisplay;'."\r\n");
            fwrite($php_fileELogWebService,'                  $this->mustDisplay = false;'."\r\n");
            fwrite($php_fileELogWebService,'                  $this->file("--------------------------------------------------------\r\n--------				RESUME					--------\r\n--------------------------------------------------------");'."\r\n");
            fwrite($php_fileELogWebService,'                  $this->file($this->sum);'."\r\n");
            fwrite($php_fileELogWebService,'                  $this->file("--------------------------------------------------------\r\n");'."\r\n");
            fwrite($php_fileELogWebService,'                  $this->file("\r\n");'."\r\n");
            fwrite($php_fileELogWebService,'                  $this->file("\r\n");'."\r\n");
            fwrite($php_fileELogWebService,'                  $this->file("\r\n");'."\r\n");
            fwrite($php_fileELogWebService,'                  $this->mustDisplay = $b;'."\r\n");
            fwrite($php_fileELogWebService,'                  if ($this->mustDisplay) {'."\r\n");
            fwrite($php_fileELogWebService,'                      $this->display("--------------------------------------------------------");'."\r\n");
            fwrite($php_fileELogWebService,'                      $this->display("--------				RESUME					--------");'."\r\n");
            fwrite($php_fileELogWebService,'                      $this->display("--------------------------------------------------------");'."\r\n");
            fwrite($php_fileELogWebService,'                      $this->display(str_replace(\'\t\', "&nbsp;&nbsp;&nbsp;&nbsp;", str_replace("\r\n", "<br/>", $this->sum)));'."\r\n");
            fwrite($php_fileELogWebService,'                      $this->display("--------------------------------------------------------");'."\r\n");
            fwrite($php_fileELogWebService,'                      $this->display("");'."\r\n");
            fwrite($php_fileELogWebService,'                      $this->display("");'."\r\n");
            fwrite($php_fileELogWebService,'                  }'."\r\n");
            fwrite($php_fileELogWebService,'              }'."\r\n");
            fwrite($php_fileELogWebService,'          }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'         /**'."\r\n");
            fwrite($php_fileELogWebService,'          * Ajoute le résultat d\'un var_dump dans $this->file'."\r\n");
            fwrite($php_fileELogWebService,'          * @param * $var Variable à var_dumper'."\r\n");
            fwrite($php_fileELogWebService,'          */'."\r\n");
            fwrite($php_fileELogWebService,'          public function fileVD($var)'."\r\n");
            fwrite($php_fileELogWebService,'          {'."\r\n");
            fwrite($php_fileELogWebService,'              if ($this->is_active && self::$active) {'."\r\n");
            fwrite($php_fileELogWebService,'                  ob_start();'."\r\n");
            fwrite($php_fileELogWebService,'                  var_dump($var);'."\r\n");
            fwrite($php_fileELogWebService,'                  $strVar = ob_get_contents();'."\r\n");
            fwrite($php_fileELogWebService,'                  ob_end_clean();'."\r\n");
            fwrite($php_fileELogWebService,'                  $this->file($strVar);'."\r\n");
            fwrite($php_fileELogWebService,'              }'."\r\n");
            fwrite($php_fileELogWebService,'          }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'          /**'."\r\n");
            fwrite($php_fileELogWebService,'           * Supprime le fichier de logs'."\r\n");
            fwrite($php_fileELogWebService,'           * @param String $file nom du fichier dans le répertoire $this->rep'."\r\n");
            fwrite($php_fileELogWebService,'           */'."\r\n");
            fwrite($php_fileELogWebService,'           public function reset()'."\r\n");
            fwrite($php_fileELogWebService,'           {'."\r\n");
            fwrite($php_fileELogWebService,'               if ($this->is_active && self::$active) {'."\r\n");
            fwrite($php_fileELogWebService,'                   $currentFile = ($file_name) ? $this->rep . \'/\' . $file_name . $this->extension : $this->rep . \'/\' . $this->file;'."\r\n");
            fwrite($php_fileELogWebService,'                   if (file_exists($currentFile)) {'."\r\n");
            fwrite($php_fileELogWebService,'                       unlink($currentFile);'."\r\n");
            fwrite($php_fileELogWebService,'                   }'."\r\n");
            fwrite($php_fileELogWebService,'               }'."\r\n");
            fwrite($php_fileELogWebService,'          }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'          /**'."\r\n");
            fwrite($php_fileELogWebService,'           * Active le ELog'."\r\n");
            fwrite($php_fileELogWebService,'           *'."\r\n");
            fwrite($php_fileELogWebService,'           * @param boolean $state Etat d\'activation'."\r\n");
            fwrite($php_fileELogWebService,'           */'."\r\n");
            fwrite($php_fileELogWebService,'           public function activate($state = true)'."\r\n");
            fwrite($php_fileELogWebService,'           {'."\r\n");
            fwrite($php_fileELogWebService,'                $this->is_active = $state;'."\r\n");
            fwrite($php_fileELogWebService,'           }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'          /**'."\r\n");
            fwrite($php_fileELogWebService,'           * Active tous les processus de Logs'."\r\n");
            fwrite($php_fileELogWebService,'           *'."\r\n");
            fwrite($php_fileELogWebService,'           * @param boolean $state Etat d\'activation'."\r\n");
            fwrite($php_fileELogWebService,'           */'."\r\n");
            fwrite($php_fileELogWebService,'           public static function enable($state = true)'."\r\n");
            fwrite($php_fileELogWebService,'           {'."\r\n");
            fwrite($php_fileELogWebService,'               self::$active = $state;'."\r\n");
            fwrite($php_fileELogWebService,'           }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'          /**'."\r\n");
            fwrite($php_fileELogWebService,'           * Equivalent à ELog::get()->display'."\r\n");
            fwrite($php_fileELogWebService,'           */'."\r\n");
            fwrite($php_fileELogWebService,'           public static function _display($string = null, $niveau = null)'."\r\n");
            fwrite($php_fileELogWebService,'           {'."\r\n");
            fwrite($php_fileELogWebService,'               ELog::get()->display($string, $niveau);'."\r\n");
            fwrite($php_fileELogWebService,'           }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'          /**'."\r\n");
            fwrite($php_fileELogWebService,'           * Equivalent à ELog::get()->file'."\r\n");
            fwrite($php_fileELogWebService,'           */'."\r\n");
            fwrite($php_fileELogWebService,'           public static function _file($string = null, $niveau = null)'."\r\n");
            fwrite($php_fileELogWebService,'           {'."\r\n");
            fwrite($php_fileELogWebService,'               ELog::get()->file($string, $niveau)'."\r\n");
            fwrite($php_fileELogWebService,'           }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'           /**'."\r\n");
            fwrite($php_fileELogWebService,'            * Equivalent à ELog::get()->fileVD'."\r\n");
            fwrite($php_fileELogWebService,'            */'."\r\n");
            fwrite($php_fileELogWebService,'            public static function _fileVD($string = null, $niveau = null)'."\r\n");
            fwrite($php_fileELogWebService,'            {'."\r\n");
            fwrite($php_fileELogWebService,'                ELog::get()->fileVD($string, $niveau);'."\r\n");
            fwrite($php_fileELogWebService,'            }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'           /**'."\r\n");
            fwrite($php_fileELogWebService,'            * Equivalent à ELog::get()->reset'."\r\n");
            fwrite($php_fileELogWebService,'            */'."\r\n");
            fwrite($php_fileELogWebService,'            public static function _reset()'."\r\n");
            fwrite($php_fileELogWebService,'            {'."\r\n");
            fwrite($php_fileELogWebService,'                ELog::get()->reset();'."\r\n");
            fwrite($php_fileELogWebService,'            }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'            public static function vd($data, $continue = 1)'."\r\n");
            fwrite($php_fileELogWebService,'            {'."\r\n");
            fwrite($php_fileELogWebService,'                require_once em_misc::getClassPath() . \'/core/Emajine_Debug.class.php\';'."\r\n");
            fwrite($php_fileELogWebService,'                if (in_array(em_misc::getUserId(), self::$userVd)) {'."\r\n");
            fwrite($php_fileELogWebService,'                    Emajine_Debug::forceVd();'."\r\n");
            fwrite($php_fileELogWebService,'                    vd($data, $continue);'."\r\n");
            fwrite($php_fileELogWebService,'                }'."\r\n");
            fwrite($php_fileELogWebService,'            }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'            public static function addUserIdForVD($vd)'."\r\n");
            fwrite($php_fileELogWebService,'            {'."\r\n");
            fwrite($php_fileELogWebService,'                self::$userVd[] = $vd;'."\r\n");
            fwrite($php_fileELogWebService,'            }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'            /**'."\r\n");
            fwrite($php_fileELogWebService,'             * Supprime les fichiers de logs plus vieux que $jours'."\r\n");
            fwrite($php_fileELogWebService,'             * avec la condition dans le nom'."\r\n");
            fwrite($php_fileELogWebService,'             *'."\r\n");
            fwrite($php_fileELogWebService,'             */'."\r\n");
            fwrite($php_fileELogWebService,'             public static function _deleteOldLogs($jours, $condition)'."\r\n");
            fwrite($php_fileELogWebService,'             {'."\r\n");
            fwrite($php_fileELogWebService,'                 ELog::get()->deleteOldLogs($jours, $condition);'."\r\n");
            fwrite($php_fileELogWebService,'             }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'             public function deleteOldLogs($jours, $condition)'."\r\n");
            fwrite($php_fileELogWebService,'             {'."\r\n");
            fwrite($php_fileELogWebService,'                 if ($condition) {'."\r\n");
            fwrite($php_fileELogWebService,'                     $files = glob($this->rep . \'/\' . $condition);'."\r\n");
            fwrite($php_fileELogWebService,'                 } else {'."\r\n");
            fwrite($php_fileELogWebService,'                     $files = glob($this->rep . \'/*\');'."\r\n");
            fwrite($php_fileELogWebService,'                 }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'                 foreach ($files as $file) {'."\r\n");
            fwrite($php_fileELogWebService,'                     if (is_file($file) && time() - filemtime($file) > 86400 * $jours) {'."\r\n");
            fwrite($php_fileELogWebService,'                         unlink($file);'."\r\n");
            fwrite($php_fileELogWebService,'                     }'."\r\n");
            fwrite($php_fileELogWebService,'                 }'."\r\n");
            fwrite($php_fileELogWebService,'             }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'            /**'."\r\n");
            fwrite($php_fileELogWebService,'             * Supprime les fichiers de logs plus vieux que $jours'."\r\n");
            fwrite($php_fileELogWebService,'             * avec la condition dans le nom'."\r\n");
            fwrite($php_fileELogWebService,'             *'."\r\n");
            fwrite($php_fileELogWebService,'             */'."\r\n");
            fwrite($php_fileELogWebService,'             public static function _deleteTooMuchLogs($nb, $condition = \'/*\', $includeDir = false)'."\r\n");
            fwrite($php_fileELogWebService,'             {'."\r\n");
            fwrite($php_fileELogWebService,'                 ELog::get()->deleteTooMuchLogs($nb, $condition);'."\r\n");
            fwrite($php_fileELogWebService,'             }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'             public function deleteTooMuchLogs($nb, $condition = \'/*\', $includeDir = false)'."\r\n");
            fwrite($php_fileELogWebService,'             {'."\r\n");
            fwrite($php_fileELogWebService,'                 if ($condition) {'."\r\n");
            fwrite($php_fileELogWebService,'                     $files = glob($this->rep . "/" . $condition);'."\r\n");
            fwrite($php_fileELogWebService,'                 } else {'."\r\n");
            fwrite($php_fileELogWebService,'                     $files = glob($this->rep . "/*");'."\r\n");
            fwrite($php_fileELogWebService,'                 }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'                 usort($files, array($this, \'sortByTime\'));'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'                 for ($i = $nb; $i < count($files); $i++) {'."\r\n");
            fwrite($php_fileELogWebService,'                      if ($includeDir || is_file($files[$i])) {'."\r\n");
            fwrite($php_fileELogWebService,'                          unlink($files[$i]);'."\r\n");
            fwrite($php_fileELogWebService,'                      }'."\r\n");
            fwrite($php_fileELogWebService,'                 }'."\r\n");
            fwrite($php_fileELogWebService,'             }'."\r\n");
            fwrite($php_fileELogWebService,"\r\n");
            fwrite($php_fileELogWebService,'             public function sortByTime($a, $b)'."\r\n");
            fwrite($php_fileELogWebService,'             {'."\r\n");
            fwrite($php_fileELogWebService,'                 return filemtime($a) - filemtime($b);'."\r\n");
            fwrite($php_fileELogWebService,'             }'."\r\n");
            fwrite($php_fileELogWebService,' }'."\r\n");

        }
        fclose($php_fileELogWebService);


        //fichier portant le nom de "Factory.php" qui se trouve dans le dossier class/tools via la checkbox "Web service"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileFactoryWebService = 'Factory.php';
        $php_fileFactoryWebService = fopen($fileFactoryWebService, 'w+');
        if (filesize($fileFactoryWebService) > 0) {
            $contents = fread($php_fileFactoryWebService, filesize($fileFactoryWebService));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileFactoryWebService,'<?php'."\r\n");
            fwrite($php_fileFactoryWebService,'namespace Addon\\'.$identifiant.';'."\r\n");
            fwrite($php_fileFactoryWebService,"\r\n");
            fwrite($php_fileFactoryWebService,'/**'."\r\n");
            fwrite($php_fileFactoryWebService,'* API Rest'."\r\n");
            fwrite($php_fileFactoryWebService,'* @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>'."\r\n");
            fwrite($php_fileFactoryWebService,'*'."\r\n");
            fwrite($php_fileFactoryWebService,'* @since ' . $creation['date_de_creation']."\r\n");
            fwrite($php_fileFactoryWebService,'*/'."\r\n");
            fwrite($php_fileFactoryWebService,'class Factory'."\r\n");
            fwrite($php_fileFactoryWebService,'{'."\r\n");
            fwrite($php_fileFactoryWebService,"\r\n");
            fwrite($php_fileFactoryWebService,'   /**'."\r\n");
            fwrite($php_fileFactoryWebService,'    * obtenir une instance de la methode de l\'API demandé'."\r\n");
            fwrite($php_fileFactoryWebService,'    *'."\r\n");
            fwrite($php_fileFactoryWebService,'    * @return     Object(API)  [description]'."\r\n");
            fwrite($php_fileFactoryWebService,'    */'."\r\n");
            fwrite($php_fileFactoryWebService,'    public function getApiMethod()'."\r\n");
            fwrite($php_fileFactoryWebService,'    {'."\r\n");
            fwrite($php_fileFactoryWebService,'        $method = self::getMethodName();'."\r\n");
            fwrite($php_fileFactoryWebService,'        $method = explode(\'-\', $method);'."\r\n");
            fwrite($php_fileFactoryWebService,'        $method = array_map(\'ucfirst\', $method);'."\r\n");
            fwrite($php_fileFactoryWebService,'        $method = implode(\'\', $method);'."\r\n");
            fwrite($php_fileFactoryWebService,'        if (!$this->isMethod($method)) {'."\r\n");
            fwrite($php_fileFactoryWebService,'            self::riseNotFoundMethodException();'."\r\n");
            fwrite($php_fileFactoryWebService,'        }'."\r\n");
            fwrite($php_fileFactoryWebService,'        require_once __DIR__ . \'/APIMethod/\' . $method . \'.class.php\';'."\r\n");
            fwrite($php_fileFactoryWebService,'        $classMethod = "Addon\\\test1\\\" . $method;'."\r\n");
            fwrite($php_fileFactoryWebService,'        $reflectedClass = new \ReflectionClass($classMethod);'."\r\n");
            fwrite($php_fileFactoryWebService,'        if (!$reflectedClass->IsInstantiable()) {'."\r\n");
            fwrite($php_fileFactoryWebService,'            self::riseNotFoundMethodException()'."\r\n");
            fwrite($php_fileFactoryWebService,'        }'."\r\n");
            fwrite($php_fileFactoryWebService,'        return new $classMethod();'."\r\n");
            fwrite($php_fileFactoryWebService,'    }'."\r\n");
            fwrite($php_fileFactoryWebService,"\r\n");
            fwrite($php_fileFactoryWebService,'   /**'."\r\n");
            fwrite($php_fileFactoryWebService,'    * Afficher une exception'."\r\n");
            fwrite($php_fileFactoryWebService,'    *'."\r\n");
            fwrite($php_fileFactoryWebService,'    * @return null'."\r\n");
            fwrite($php_fileFactoryWebService,'    */'."\r\n");
            fwrite($php_fileFactoryWebService,'    public static function riseNotFoundMethodException()'."\r\n");
            fwrite($php_fileFactoryWebService,'    {'."\r\n");
            fwrite($php_fileFactoryWebService,'        header(\'HTTP/1.0 401 Unauthorized\');'."\r\n");
            fwrite($php_fileFactoryWebService,'        \em_output::echoAndExit(json_encode([\'status\' => 401, \'message\' => \'Method not found\']));'."\r\n");
            fwrite($php_fileFactoryWebService,'    }'."\r\n");
            fwrite($php_fileFactoryWebService,'    /**'."\r\n");
            fwrite($php_fileFactoryWebService,'     * tester si la methode demander existe'."\r\n");
            fwrite($php_fileFactoryWebService,'     *'."\r\n");
            fwrite($php_fileFactoryWebService,'     * @param  string  $methodName [description]'."\r\n");
            fwrite($php_fileFactoryWebService,'     *'."\r\n");
            fwrite($php_fileFactoryWebService,'     * @return boolean             [description]'."\r\n");
            fwrite($php_fileFactoryWebService,'     */'."\r\n");
            fwrite($php_fileFactoryWebService,'     protected function isMethod($methodName)'."\r\n");
            fwrite($php_fileFactoryWebService,'     {'."\r\n");
            fwrite($php_fileFactoryWebService,'          $filePath = __DIR__ . \'/APIMethod/\' . $methodName;'."\r\n");
            fwrite($php_fileFactoryWebService,'          return file_exists($filePath . \'.class.php\');'."\r\n");
            fwrite($php_fileFactoryWebService,'     }'."\r\n");
            fwrite($php_fileFactoryWebService,"\r\n");
            fwrite($php_fileFactoryWebService,'    /**'."\r\n");
            fwrite($php_fileFactoryWebService,'     * Obtention du nom de la methode demandée à partir de l\'url'."\r\n");
            fwrite($php_fileFactoryWebService,'     *'."\r\n");
            fwrite($php_fileFactoryWebService,'     * @return [type] [description]'."\r\n");
            fwrite($php_fileFactoryWebService,'     */'."\r\n");
            fwrite($php_fileFactoryWebService,'     private static function getMethodName()'."\r\n");
            fwrite($php_fileFactoryWebService,'     {'."\r\n");
            fwrite($php_fileFactoryWebService,'         $uri = \em_misc::ru();'."\r\n");
            fwrite($php_fileFactoryWebService,'         if (count($_GET) > 0) {'."\r\n");
            fwrite($php_fileFactoryWebService,'             $uri = explode(\'?\', $uri)[0];'."\r\n");
            fwrite($php_fileFactoryWebService,'         }'."\r\n");
            fwrite($php_fileFactoryWebService,'         $urls = explode(\'/\', $uri);'."\r\n");
            fwrite($php_fileFactoryWebService,'         $method = "";'."\r\n");
            fwrite($php_fileFactoryWebService,'         $i = count($urls) - 1;'."\r\n");
            fwrite($php_fileFactoryWebService,'         do {'."\r\n");
            fwrite($php_fileFactoryWebService,'             if (!empty($urls[$i])) {'."\r\n");
            fwrite($php_fileFactoryWebService,'                 return ucfirst($urls[$i]);'."\r\n");
            fwrite($php_fileFactoryWebService,'             }'."\r\n");
            fwrite($php_fileFactoryWebService,'             $i--;'."\r\n");
            fwrite($php_fileFactoryWebService,'         } while ($i >= 0)'."\r\n");
            fwrite($php_fileFactoryWebService,'     }'."\r\n");
            fwrite($php_fileFactoryWebService,'}'."\r\n");
        }
            fclose($php_fileFactoryWebService);


        //fichier portant le nom de "HTTPResponse.class.php" qui se trouve dans le dossier class/tools via la checkbox "Web service"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileHttpResponseWebService = 'HTTPResponse.class.php';
        $php_fileHttpResponseWebService = fopen($fileHttpResponseWebService, 'w+');
        if (filesize($fileHttpResponseWebService) > 0) {
            $contents = fread($php_fileHttpResponseWebService, filesize($fileHttpResponseWebService));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileHttpResponseWebService,'<?php'."\r\n");
            fwrite($php_fileHttpResponseWebService,'namespace Addon\\'.$identifiant.';'."\r\n");
            fwrite($php_fileHttpResponseWebService,"\r\n"."\r\n");
            fwrite($php_fileHttpResponseWebService,'/**'."\r\n");
            fwrite($php_fileHttpResponseWebService,' * API Rest'."\r\n");
            fwrite($php_fileHttpResponseWebService,' *'."\r\n");
            fwrite($php_fileHttpResponseWebService,' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>'."\r\n");
            fwrite($php_fileHttpResponseWebService,' *'."\r\n");
            fwrite($php_fileHttpResponseWebService,' * @since ' . $creation['date_de_creation']."\r\n");
            fwrite($php_fileHttpResponseWebService,' */'."\r\n");
            fwrite($php_fileHttpResponseWebService,'class HTTPResponse'."\r\n");
            fwrite($php_fileHttpResponseWebService,'{'."\r\n");
            fwrite($php_fileHttpResponseWebService,"\r\n");
            fwrite($php_fileHttpResponseWebService,'    protected $statutMessage = array('."\r\n");
            fwrite($php_fileHttpResponseWebService,'        200 => \'OK\','."\r\n");
            fwrite($php_fileHttpResponseWebService,'        401 => \'Unauthorized\','."\r\n");
            fwrite($php_fileHttpResponseWebService,'        403 => \'Forbidden\','."\r\n");
            fwrite($php_fileHttpResponseWebService,"\r\n");
            fwrite($php_fileHttpResponseWebService,'    );'."\r\n");
            fwrite($php_fileHttpResponseWebService,"\r\n");
            fwrite($php_fileHttpResponseWebService,'    protected $codesMessage = array('."\r\n");
            fwrite($php_fileHttpResponseWebService,'        \'0001\' => \'Authentification failed\','."\r\n");
            fwrite($php_fileHttpResponseWebService,'        \'0002\' => \'Acces Forbidden\','."\r\n");
            fwrite($php_fileHttpResponseWebService,'    );'."\r\n");
            fwrite($php_fileHttpResponseWebService,"\r\n");
            fwrite($php_fileHttpResponseWebService,'   /**'."\r\n");
            fwrite($php_fileHttpResponseWebService,'    * Construction d\'une reponse'."\r\n");
            fwrite($php_fileHttpResponseWebService,'    *'."\r\n");
            fwrite($php_fileHttpResponseWebService,'    * @param  int $statut               [description]'."\r\n");
            fwrite($php_fileHttpResponseWebService,'    * @param  int $code                 [description]'."\r\n");
            fwrite($php_fileHttpResponseWebService,'    * @param  string $supplementMessage [description]'."\r\n");
            fwrite($php_fileHttpResponseWebService,'    *'."\r\n");
            fwrite($php_fileHttpResponseWebService,'    * @return array                    [description]'."\r\n");
            fwrite($php_fileHttpResponseWebService,'    */'."\r\n");
            fwrite($php_fileHttpResponseWebService,'    public function response($statut, $code = null, $supplementMessage = null)'."\r\n");
            fwrite($php_fileHttpResponseWebService,'    {'."\r\n");
            fwrite($php_fileHttpResponseWebService,'        $message = $statut . \' \' . $this->statutMessage[$statut];'."\r\n");
            fwrite($php_fileHttpResponseWebService,'        if (!is_null($supplementMessage)) {'."\r\n");
            fwrite($php_fileHttpResponseWebService,'            $message = $supplementMessage;'."\r\n");
            fwrite($php_fileHttpResponseWebService,'        }'."\r\n");
            fwrite($php_fileHttpResponseWebService,'        if (!is_null($code)) {'."\r\n");
            fwrite($php_fileHttpResponseWebService,'            $message .= \' \' . $this->codesMessage[$code];'."\r\n");
            fwrite($php_fileHttpResponseWebService,'        }'."\r\n");
            fwrite($php_fileHttpResponseWebService,'        return array("code" => $code, "message" => $message);'."\r\n");
            fwrite($php_fileHttpResponseWebService,'    }'."\r\n");
            fwrite($php_fileHttpResponseWebService,'}'."\r\n");

        }
        fclose($php_fileHttpResponseWebService);


        //fichier portant le nom de "Manager.class.php" qui se trouve dans le dossier class/tools/abstract via la checkbox "Web service"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileManagerWebService = 'Manager.class.php';
        $php_fileManagerWebService = fopen($fileManagerWebService, 'w+');
        if (filesize($fileManagerWebService) > 0) {
            $contents = fread($php_fileManagerWebService, filesize($fileManagerWebService));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileManagerWebService,'<?php'."\r\n");
            fwrite($php_fileManagerWebService,"\r\n");
            fwrite($php_fileManagerWebService,'namespace Addon\\'.$identifiant.';'."\r\n");
            fwrite($php_fileManagerWebService,"\r\n");
            fwrite($php_fileManagerWebService,'/**'."\r\n");
            fwrite($php_fileManagerWebService,' * Classe métier'."\r\n");
            fwrite($php_fileManagerWebService,' *'."\r\n");
            fwrite($php_fileManagerWebService,' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>'."\r\n");
            fwrite($php_fileManagerWebService,' *'."\r\n");
            fwrite($php_fileManagerWebService,' * @since ' . $creation['date_de_creation']."\r\n");
            fwrite($php_fileManagerWebService,' */'."\r\n");
            fwrite($php_fileManagerWebService,'class Manager'."\r\n");
            fwrite($php_fileManagerWebService,'{'."\r\n");
            fwrite($php_fileManagerWebService,"\r\n");
            fwrite($php_fileManagerWebService,'   /**'."\r\n");
            fwrite($php_fileManagerWebService,'    * Mettre à jour'."\r\n");
            fwrite($php_fileManagerWebService,'    *'."\r\n");
            fwrite($php_fileManagerWebService,'    * @param      array   $datas      The datas'."\r\n");
            fwrite($php_fileManagerWebService,'    * @param      string  $table      The table'."\r\n");
            fwrite($php_fileManagerWebService,'    * @param      string  $condition  The condition'."\r\n");
            fwrite($php_fileManagerWebService,'    *'."\r\n");
            fwrite($php_fileManagerWebService,'    * @return     null'."\r\n");
            fwrite($php_fileManagerWebService,'    */'."\r\n");
            fwrite($php_fileManagerWebService,'    public static function update($datas, $table, $condition)'."\r\n");
            fwrite($php_fileManagerWebService,'    {'."\r\n");
            fwrite($php_fileManagerWebService,'        \em_db::exec(createUpdateQuery($datas, $table, $condition));'."\r\n");
            fwrite($php_fileManagerWebService,'    }'."\r\n");
            fwrite($php_fileManagerWebService,"\r\n");
            fwrite($php_fileManagerWebService,'   /**'."\r\n");
            fwrite($php_fileManagerWebService,'    * Recupère le hsCode'."\r\n");
            fwrite($php_fileManagerWebService,'    *'."\r\n");
            fwrite($php_fileManagerWebService,'    * @param      int  $id     The identifier'."\r\n");
            fwrite($php_fileManagerWebService,'    *'."\r\n");
            fwrite($php_fileManagerWebService,'    * @return     string'."\r\n");
            fwrite($php_fileManagerWebService,'    */'."\r\n");
            fwrite($php_fileManagerWebService,'    public static function gethsCode($id, $table, $primary)'."\r\n");
            fwrite($php_fileManagerWebService,'    {'."\r\n");
            fwrite($php_fileManagerWebService,'        if ($id == 0) {'."\r\n");
            fwrite($php_fileManagerWebService,'            return;'."\r\n");
            fwrite($php_fileManagerWebService,'        }'."\r\n");
            fwrite($php_fileManagerWebService,'        return \em_db::one(\'SELECT specif_hsCode FROM \' . $table . \' WHERE \' . $primary . \' = \' . $id);'."\r\n");
            fwrite($php_fileManagerWebService,'    }'."\r\n");
            fwrite($php_fileManagerWebService,"\r\n");
            fwrite($php_fileManagerWebService,'    /**'."\r\n");
            fwrite($php_fileManagerWebService,'     * Recupérer les urls des docs'."\r\n");
            fwrite($php_fileManagerWebService,'     *'."\r\n");
            fwrite($php_fileManagerWebService,'     * @param      int  $orderId       The order identifier'."\r\n");
            fwrite($php_fileManagerWebService,'     *'."\r\n");
            fwrite($php_fileManagerWebService,'     * @return     string'."\r\n");
            fwrite($php_fileManagerWebService,'     */'."\r\n");
            fwrite($php_fileManagerWebService,'     public static function getDocumetnsPaths($orderId)'."\r\n");
            fwrite($php_fileManagerWebService,'     {'."\r\n");
            fwrite($php_fileManagerWebService,'         return \em_db::row(\'SELECT specif_etiquette_path, specif_cn23_path, specif_delivery_slip FROM cat_commande WHERE id_commande = \' . $orderId);'."\r\n");
            fwrite($php_fileManagerWebService,'     }'."\r\n");
            fwrite($php_fileManagerWebService,"\r\n");
            fwrite($php_fileManagerWebService,'    /**'."\r\n");
            fwrite($php_fileManagerWebService,'     * Recupérer les suivis'."\r\n");
            fwrite($php_fileManagerWebService,'     *'."\r\n");
            fwrite($php_fileManagerWebService,'     * @param      int  $orderId       The order identifier'."\r\n");
            fwrite($php_fileManagerWebService,'     *'."\r\n");
            fwrite($php_fileManagerWebService,'     * @return     string'."\r\n");
            fwrite($php_fileManagerWebService,'     */'."\r\n");
            fwrite($php_fileManagerWebService,'     public static function getPackageTrackingStories($orderId)'."\r\n");
            fwrite($php_fileManagerWebService,'     {'."\r\n");
            fwrite($php_fileManagerWebService,'         return \em_db::all(\'SELECT * FROM specif_colissimo_package_tracking WHERE orderId = \' . $orderId . \' ORDER BY 	eventDate DESC\');'."\r\n");
            fwrite($php_fileManagerWebService,'     }'."\r\n");
            fwrite($php_fileManagerWebService,'}'."\r\n");

        }
        fclose($php_fileManagerWebService);

        //fichier portant le nom de "Method1.class.php" qui se trouve dans le dossier class/tools/APIMethod via la checkbox "Web service"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileMethod1WebService = 'Method1.class.php';
        $php_fileMethod1WebService = fopen($fileMethod1WebService, 'w+');
        if (filesize($fileMethod1WebService) > 0) {
            $contents = fread($php_fileMethod1WebService, filesize($fileMethod1WebService));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileMethod1WebService,'<?php'."\r\n");
            fwrite($php_fileMethod1WebService,'namespace Addon\\'.$identifiant. ';'."\r\n");
            fwrite($php_fileMethod1WebService,"\r\n");
            fwrite($php_fileMethod1WebService,'/**'."\r\n");
            fwrite($php_fileMethod1WebService,' * API Rest'."\r\n");
            fwrite($php_fileMethod1WebService,' *'."\r\n");
            fwrite($php_fileMethod1WebService,' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>'."\r\n");
            fwrite($php_fileMethod1WebService,' *'."\r\n");
            fwrite($php_fileMethod1WebService,' * @since ' . $creation['date_de_creation']."\r\n");
            fwrite($php_fileMethod1WebService,' */'."\r\n");
            fwrite($php_fileMethod1WebService,'require_once \em_misc::getSpecifPath() . \'addons/test1/class/tools/API.class.php\';'."\r\n");
            fwrite($php_fileMethod1WebService,'class Login extends API'."\r\n");
            fwrite($php_fileMethod1WebService,'{'."\r\n");
            fwrite($php_fileMethod1WebService,'    /**'."\r\n");
            fwrite($php_fileMethod1WebService,'     * Execution de la requete demandée'."\r\n");
            fwrite($php_fileMethod1WebService,'     *'."\r\n");
            fwrite($php_fileMethod1WebService,'     * @return     null'."\r\n");
            fwrite($php_fileMethod1WebService,'     */'."\r\n");
            fwrite($php_fileMethod1WebService,'     public function doRequest()'."\r\n");
            fwrite($php_fileMethod1WebService,'     {'."\r\n");
            fwrite($php_fileMethod1WebService,'         //  Traitement ...'."\r\n");
            fwrite($php_fileMethod1WebService,'         if ($ok) {'."\r\n");
            fwrite($php_fileMethod1WebService,'             $this->respond(200, null, array("key" => "value"));'."\r\n");
            fwrite($php_fileMethod1WebService,'         } else {'."\r\n");
            fwrite($php_fileMethod1WebService,'             $this->respond(401, null, array("key" => "value"));'."\r\n");
            fwrite($php_fileMethod1WebService,'         }'."\r\n");
            fwrite($php_fileMethod1WebService,'     }'."\r\n");
            fwrite($php_fileMethod1WebService,'}'."\r\n");
        }
        fclose($php_fileMethod1WebService);

        //fichier portant le nom de "GetMapCoord.php" qui se trouve dans le dossier class/actions/public via la checkbox "Interactive map"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileGetMapCoordInteractivMap = 'GetMapCoord.php';
        $php_fileGetMapCoordInteractivMap = fopen($fileGetMapCoordInteractivMap, 'w+');
        if (filesize($fileGetMapCoordInteractivMap) > 0) {
            $contents = fread($php_fileGetMapCoordInteractivMap, filesize($fileGetMapCoordInteractivMap));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileGetMapCoordInteractivMap,'<?php'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'namespace Addon\\'.$identifiant.';'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'require_once \em_misc::getSpecifPath() . \'addons/'.$identifiant.'/class/tools/MapManager.class.php\';'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,"\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'/**'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,' *'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,' * Get Map coord'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,' *'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,' *'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,' * @since '. $creation['date_de_creation']."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,' */'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'class GetMapCoord'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'{'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,"\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'     private $entryTypes;'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'     private $searchedCoord;'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'     private $area;'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'    /**'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'     * Retourne le type de l\'action'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'     *'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'     * @return string'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'     */'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'     public function type()'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'     {'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'         return \'GetMapCoord\';'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'     }'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,"\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'     /**'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      * Retourne si l\'action est active'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      *'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      * @return boolean'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      */'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      public function isEnabled()'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      {'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'          return true;'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      }'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,"\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'     /**'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      * Fonction automatiquement appelée à l\'appel de l\'action'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      *'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      * @param'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      *'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      * @return string'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      */'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      public function start()'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      {'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'          if (empty($_POST[\'action\'])) {'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'              \em_output::echoAndExit(\'\');'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'          }'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'          switch ($_POST[\'action\']) {'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'              case \'getEntryList\':'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  $data = array();'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  $area = urldecode($_POST[\'area\']);'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  $address = urldecode($_POST[\'city\']);'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  $_SESSION[\'entries\'][\'area\'] = $area;'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  $_SESSION[\'entries\'][\'city\'] = $address;'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  $searchedCoord = null;'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  $entries = MapManager::search($area, $searchedCoord, $address);'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  // Conversion du rayon en mètre'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  \em_output::echoAndExit(json_encode(array(\'ids\' => array_values($entries), \'area\' => $area * 1000, \'coord\' => $searchedCoord)));'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  break;'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'              case \'reset\':'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  unset($_SESSION[\'entries\']);'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  unset($_SESSION[\'mapPoints\']);'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  unset($_SESSION[\'positions\']);'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  \em_output::echoAndExit(json_encode(array(\'state\' => \'RESETED\')));'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  break;'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'              default:'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  \em_output::echoAndExit(\'\');'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'                  break;'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'          }'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'      }'."\r\n");
            fwrite($php_fileGetMapCoordInteractivMap,'}'."\r\n");

        }
        fclose($php_fileGetMapCoordInteractivMap);

        //fichier portant le nom de "GetProductStores.php" qui se trouve dans le dossier class/actions/public via la checkbox "Interactive map"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileGetProductStoresInteractivMap = 'GetProductStores.php';
        $php_fileGetProductStoresInteractivMap = fopen($fileGetProductStoresInteractivMap, 'w+');
        if (filesize($fileGetProductStoresInteractivMap) > 0) {
            $contents = fread($php_fileGetProductStoresInteractivMap, filesize($fileGetProductStoresInteractivMap));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileGetProductStoresInteractivMap,'<?php'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'namespace Addon\\'.$identifiant.';'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,"\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'require_once \em_misc::getSpecifPath() . \'addons/'.$identifiant.'/class/tools/MapManager.class.php\';'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'/**'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,' * Récupération de la liste des points de ventes'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,' *'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,' * @author Robson <Robson@medialibs.com>'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,' *'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,' * @since 2019-03-12'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,' */'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,' class GetProductStores'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,' {'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,"\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'     private $entryTypes;'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'     private $searchedCoord;'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'     private $area;'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'     /**'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      * Retourne le type de l\'action'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      *'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      * @return string'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      */'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      public function type()'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      {'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'          return \'actionName\';'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      }'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,"\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'     /**'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      * Retourne si l\'action est active'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      *'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      * @return boolean'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      */'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      public function isEnabled()'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      {'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'          return true;'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      }'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,"\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'     /**'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      * Fonction automatiquement appelée à l\'appel de l\'action'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      *'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      * @param'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      *'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      * @return string'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      */'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      public function start()'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      {'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'          if (empty($_POST[\'action\'])) {'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'              \em_output::echoAndExit(\'\');'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'          }'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'          switch ($_POST[\'action\']) {'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'              case \'getEntryList\':'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  $data = array();'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  $area = urldecode($_POST[\'area\']);'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  $address = urldecode($_POST[\'city\']);'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  $_SESSION[\'entries\'][\'area\'] = $area;'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  $_SESSION[\'entries\'][\'city\'] = $address;'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  $searchedCoord = null;'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  $entries = MapManager::search($area, $searchedCoord, $address);'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  // Conversion du rayon en mètre'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  \em_output::echoAndExit(json_encode(array(\'ids\' => array_values($entries),  \'area\' => $area * 1000, \'coord\' => $searchedCoord)));'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  break;'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'              case \'reset\':'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  unset($_SESSION[\'entries\']);'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  unset($_SESSION[\'salePoints\']);'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  unset($_SESSION[\'positions\']);'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  \em_output::echoAndExit(json_encode(array(\'state\' => \'RESETED\')));'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  break;'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'              default:'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                  \em_output::echoAndExit(\'\');'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'                   break;'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'          }'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'      }'."\r\n");
            fwrite($php_fileGetProductStoresInteractivMap,'}'."\r\n");

        }
        fclose($php_fileGetProductStoresInteractivMap);

        //fichier portant le nom de "methodManageinteractivMap.class.php" qui se trouve dans le dossier class/publication_methods/interactiveMap via la checkbox "Interactive map"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileMethodeManageInteractivMap = 'methodManageInteractivMap.class.php';
        $php_fileMethodeManageInteractivMap = fopen($fileMethodeManageInteractivMap, 'w+');
        if (filesize($fileMethodeManageInteractivMap) > 0) {
            $contents = fread($php_fileMethodeManageInteractivMap, filesize($fileMethodeManageInteractivMap));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileMethodeManageInteractivMap,'<?php'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'namespace Addon\\'.$identifiant.';'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,"\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'/**'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,' * MethodManageInteractiveMap'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,' *'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,' *'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,' * @since '. $creation['date_de_creation']."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,' */'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'class methodManageInteractivMap'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'{'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'    /**'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'     * Constructeur'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'     */'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'     public function __construct()'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'     {'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'     }'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,"\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'     /**'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      * Retourne la description de la rubrique'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      *'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      * @return string'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      */'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      public function getDescription()'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      {'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'           return \'\';'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      }'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,"\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'     /**'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      * Gestion de la configuration'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      *'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      * @return string'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      */'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      public function start()'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      {'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'          require_once \em_misc::getClassPath() . \'/core/Emajine_Specif/Emajine_Specif_PublicationMethod.class.php\';'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,"\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'          $form = \Emajine_API::form();'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'          $form->addElement(\'text\', \'label\', \'Titre\');'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,"\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'          $obj = new \Emajine_Specif_PublicationMethod($form);'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'          return $obj->start();'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'      }'."\r\n");
            fwrite($php_fileMethodeManageInteractivMap,'}'."\r\n");

        }
        fclose($php_fileMethodeManageInteractivMap);


        //fichier portant le nom de "methodPublicinteractivMap.class.php" qui se trouve dans le dossier class/publication_methods/interactiveMap via la checkbox "Interactive map"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileMethodePublicInteractivMap = 'methodPublicInteractivMap.class.php';
        $php_fileMethodePublicInteractivMap = fopen($fileMethodePublicInteractivMap, 'w+');
        if (filesize($fileMethodePublicInteractivMap) > 0) {
            $contents = fread($php_fileMethodePublicInteractivMap, filesize($fileMethodePublicInteractivMap));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileMethodePublicInteractivMap,'<?php'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'namespace Addon\\'.$identifiant.';'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'require_once \em_misc::getSpecifPath() . \'addons/'.$identifiant.'/class/tools/MapManager.class.php\';'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,"\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'/**'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,' * MethodPublicInteractivMap'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,' *'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,' *'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,' * @since '. $creation['date_de_creation']."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,' */'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'class methodPublicInteractivMap'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'{'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,"\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    // Titre de la méthode de publication'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    private $title = \'\';'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    private $mx;'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    const APIKEY = \'\';'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,"\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'   /**'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    * Constructeur'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    *'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    * Vous aurez autant d\'argument à l\'appel de cette méthode que de champs défini lors de la configuration.'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    * Ainsi, par exemple, si vous définissez un nom et un nombre d\'élément à afficher, vous pourrez récupérer'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    * ces éléments via la définition suivante :'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    * public function __construct($title = false, $nbElements = 10)'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    */'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    public function __construct($title = false)'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    {'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'        $this->title = $title;'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    }'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,"\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    /**'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     * Gestion et affichage du contenu'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     *'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     * @return string'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     */'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     public function start()'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     {'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'         $mx = \em_mx::initMx(\em_misc::getSpecifPath() . \'addons/'.$identifiant.'/templates/publication_methods/searchAreaResult.html\');'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'         $this->display($mx);'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'         return \em_mx::write($mx);'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     }'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,"\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'    /**'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     * Affiche le résultat de la recherche'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     *'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     * @param  modeliXe $mx              Objet permettant de gérer le template'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     * @param  array    $elements        Liste des identifiants des fiches annuaire'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     *'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     * @return null'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     */'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     public function display($mx)'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     {'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'         \em_mx::text('."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'             $mx,'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'             \'results.api\','."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'             \'<script src="https://maps.googleapis.com/maps/api/js?v=3.4&libraries=geometry,drawing,weather,visualization&language=fr&key=\' . self::APIKEY . \'"></script>\''."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'         );'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'        // Préremplire les champs recherchés pour la dernière fois'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'        \em_mx::attr($mx, \'areaselected\' . $_SESSION[\'entries\'][\'area\'], $_SESSION[\'entries\'][\'area\']);'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'        \em_mx::attr($mx, \'cityvalue\', $_SESSION[\'entries\'][\'city\']);'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'        $elements = [];'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'        \em_mx::text($mx, \'results.js\', \em_js::getJsTag(\' var entryIds = \' . json_encode($elements) . \'; var allEntries = [\' . implode(\',\', $elements) . \'];\'));'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'        if (!empty($_SESSION[\'entries\'][\'area\'])) {'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'            $area = $_SESSION[\'entries\'][\'area\'];'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'        } else {'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'            $area = 200;'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'        }'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'        \em_mx::text($mx, \'areaLoading\', \'var areaLoading = \' . $area . \';\');'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'     }'."\r\n");
            fwrite($php_fileMethodePublicInteractivMap,'}'."\r\n");
        }
        fclose($php_fileMethodePublicInteractivMap);




        //fichier method_interactivMap.xml qui se trouve dans le dossier  class/publication_methods/interactiveMap via la checkbox menuSectionCrud
        //On crée un DomDocument
        $interactivMap = new DOMDocument("1.0", "UTF-8");
        //Le format du fichier Xml est correct
        $interactivMap->formatOutput= true;

        //on crée la balise parent <Method>
        $method = $interactivMap->createElement('Method');
        //on ferme la balise parent </data>
        $interactivMap->appendChild($method);


        //on crée la balise enfant <config>
        $config = $interactivMap->createElement('config');
        //on créé la balise fermante </config> de la balise parent <method>
        $method->appendChild($config);

        //on crée la balise enfant <name>
        $name = $interactivMap->createElement('name','Carte interactive des producteurs');
        //on crée la balise fermante </name> de la balise parent <config>
        $config->appendChild($name);

        //on crée la balise enfant <methodset>
        $methodset = $interactivMap->createElement('methodset','_specifique_');
        //on crée la balise fermante </methodset> de la balise parent <config>
        $config->appendChild($methodset);

        //on crée la balise enfant <scriptmanage>
        $scriptmanage = $interactivMap->createElement('scriptmanage',$fileMethodeManageInteractivMap);
        //on crée la balise fermante </scriptmanage> de la balise parent <config>
        $config->appendChild($scriptmanage);

        //on crée la balise enfant <scriptpublic>
        $scriptpublic = $interactivMap->createElement('scriptpublic',$fileMethodePublicInteractivMap);
        //on crée la balise fermante </scriptmanage> de la balise parent <config>
        $config->appendChild($scriptpublic);



        // on enregiste le fichier sous le nom de emajine_menu.xml
        $emajineMenu->save('method_interactivMap.xml');

        
        
        
        
        

        //fichier portant le nom de "MapManager.class.php" qui se trouve dans le dossier class/tools via la checkbox "Interactive map"
        $bd = new PDO("mysql:host = localhost;dbname=generateur", "root", "salut");
        $datas = $bd->query('SELECT * FROM generateur ORDER BY id DESC LIMIT 1');
        $fileMapManagerInteractivMap = 'MapManager.class.php';
        $php_fileMapManagerInteractivMap = fopen($fileMapManagerInteractivMap, 'w+');
        if (filesize($fileMapManagerInteractivMap) > 0) {
            $contents = fread($php_fileMapManagerInteractivMap, filesize($fileMapManagerInteractivMap));
        }
        while ($creation = $datas->fetch()) {
            fwrite($php_fileMapManagerInteractivMap,'<?php'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'namespace Addon\\'.$identifiant.';'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'require_once \em_misc::getClassPath() . \'/mods/catalogue/Emajine_Catalog_Product.class.php\';'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,"\r\n");
            fwrite($php_fileMapManagerInteractivMap,'/**'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,' * Map manager'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,' *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,' * @author  [' . $creation['nom_developpeur'] . ']  <[name]@Medialibs.com>'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,' *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,' * @since '. $creation['date_de_creation']."\r\n");
            fwrite($php_fileMapManagerInteractivMap,' */'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'class MapManager'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'{'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    const GOOGLE_MAPS_API = \'https://maps.googleapis.com/maps/api/geocode/json?sensor=false&address={LOC},+FR&key=\';'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    const APIKEY = \'\'; // Clé API'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    const REQUEST_TIMEOUT = 10;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    const REQUEST_CONNECTTIMEOUT = 2;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    const DEG_TO_KM = 111.13384;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    const DEFAULT_AREA = 200;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    private static $searchedCoord;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    private static $area;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    public static $positions;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,"\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    /**'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     * Supprimer les signes avec un `_`'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     * @param      string  $line   La ligne'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     * @return     string  La ligne sans `_`'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     */'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     public function deleteFromArray($line)'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          if ($line == \'_\') {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'              return;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          return $line;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,"\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    /**'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     * Retourne les informations relatives à un point de vente'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     * @return array'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     */'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     public static function getData()'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'         // Récuperation de toutes les informations à afficher sur le map ...'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,"\r\n");
            fwrite($php_fileMapManagerInteractivMap,'        // Données de retour à titre d\'exemple ...'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'        return ['."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'            ['."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'                \'coord\' => self::latLngDecode(\'45.77486|5.801936\'), // coord: {lat: 45.77486, lng: 5.801936}'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'                \'latlong\' => \'45.77486|5.801936\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'                \'product_name\' => \'TEST1\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'                \'address\' => \'ADRESS1\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'                \'url\' => \'url1\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'            ],'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'            ['."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'coord\' => self::latLngDecode(\'45.87486|5.801936\'),'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'latlong\' => \'45.87486|5.801936\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'product_name\' => \'TEST2\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'address\' => \'ADRESS2\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'url\' => \'url2\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'            ],'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'            ['."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'coord\' => self::latLngDecode(\'45.57486|5.801936\'),'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'latlong\' => \'45.57486|5.801936\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'product_name\' => \'TEST3\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'address\' => \'ADRESS3\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'url\' => \'url3\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'            ],'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'            ['."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'coord\' => self::latLngDecode(\'40.57486|5.801936\'),'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'latlong\' => \'40.57486|5.801936\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'product_name\' => \'TEST3\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'address\' => \'ADRESS3\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'               \'url\' => \'url3\','."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'            ]'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'        ];'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'   /**'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    * Décode Les latitudes et longitudes stockées en BDD'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    * @param  string $latLng Coordonnées stockées en BDD'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    * @return array'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    */'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    public static function latLngDecode($latLng)'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'        $tmp = explode(\'|\', $latLng);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'        if (count($tmp) < 2) {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'            $tmp = explode(\',\', $latLng);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'        }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'        foreach ($tmp as $key => &$value) {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'           $value = str_replace(\',\', \'.\', $value);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'        }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'        return array(\'lat\' => (float) $tmp[0], \'lng\' => (float) $tmp[1]);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,"\r\n");
            fwrite($php_fileMapManagerInteractivMap,'    /**'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     * Récupère la distance entre 2 points'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     * @param  array $coord1  coordonnées du premier point'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     * @param  array $coord2  coordonnées du second point'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     * @return float distance entre les deux points en km'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     */'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     public static function getDistance($coord1, $coord2)'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'         return rad2deg('."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'             acos(('."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'                 sin(deg2rad($coord1[\'lat\'])) * sin(deg2rad($coord2[\'lat\'])))'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'                 + (cos(deg2rad($coord1[\'lat\'])) * cos(deg2rad($coord2[\'lat\'])) * cos(deg2rad($coord1[\'lng\'] - ($coord2[\'lng\']))))'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'             )'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'         ) * self::DEG_TO_KM;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,"\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     /**'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * Recupère les données avec filtre'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * @param      int     $area           The area'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * @param      array   $searchedCoord  The searched coordinate'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * @param      string  $address        The address'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * @return     array'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      */'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      public static function search(&$area, &$searchedCoord, $address)'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          if (empty($area)) {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'              $area = self::DEFAULT_AREA;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          self::$area = $area;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $cacheKey = md5($area . $address);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,"\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          if (!empty($_SESSION[\'mapPoints\'][$cacheKey])) {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'              if (!empty($address)) {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'                   $searchedCoord = $_SESSION[\'positions\'][md5(urlencode($address) . self::$area)];'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'              }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'              return $_SESSION[\'mapPoints\'][$cacheKey];'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $entries = self::getData();'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          if (!empty($address)) {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'              $searchedCoord = self::getSearchedCoord($address);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'              self::$searchedCoord = $searchedCoord;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'              $entries = self::completeDatas($entries, \'filterCoord\');'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $entries = array_values(array_filter($entries));'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $_SESSION[\'mapPoints\'][$cacheKey] = $entries;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          return $entries;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,"\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     /**'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * Compléter les données'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * @param   array  $entries'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * @param   string $method'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * @return array'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      */'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      public static function completeDatas($entries, $method)'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $fullEntries = [];'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          for ($i = 0; $i < count($entries); $i++) {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'              $fullEntries[$i] = self::$method($entries[$i]);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          return $fullEntries;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,"\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     /**'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * Callback d\'ajout coordonnée et type_id dans une ligne'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * @param array $entry'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * @return array'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      */'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      private static function filterCoord($entry)'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $elementCoord = self::latLngDecode($entry[\'latlong\']);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $distance = self::getDistance(self::$searchedCoord, $elementCoord);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          if ($distance > floatval(self::$area)) {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'              return;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          return $entry;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,"\r\n");
            fwrite($php_fileMapManagerInteractivMap,'     /**'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * Récupère les coordonnées correspondant à un code postal'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * @param  string $state   Code postal'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      *'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      * @return array'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      */'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      public static function getSearchedCoord($state)'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          if (empty($state)) {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'              return;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $loc = \'\';'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $condition = \'\';'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $loc = urlencode($state);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          if (!empty($_SESSION[\'positions\'][md5($loc . self::$area)])) {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'              return $_SESSION[\'positions\'][md5($loc . self::$area)];'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $request = curl_init();'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          curl_setopt($request, CURLOPT_URL, str_replace(\'{LOC}\', $loc, self::GOOGLE_MAPS_API . self::APIKEY));'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          curl_setopt($request, CURLOPT_RETURNTRANSFER, true);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          curl_setopt($request, CURLOPT_CONNECTTIMEOUT, self::REQUEST_CONNECTTIMEOUT);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          curl_setopt($request, CURLOPT_TIMEOUT, self::REQUEST_TIMEOUT);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,"\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $result = json_decode(curl_exec($request), true);'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          if (empty($result) || $result[\'status\'] != \'OK\' || empty($result[\'results\'][0])) {'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'              return;'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $data = $result[\'results\'][0];'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          $_SESSION[\'positions\'][md5($loc . self::$area)] = $data[\'geometry\'][\'location\'];'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'          return $data[\'geometry\'][\'location\'];'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'      }'."\r\n");
            fwrite($php_fileMapManagerInteractivMap,'}'."\r\n");

        }
        fclose($php_fileMapManagerInteractivMap);












        //ZipArchive
        //on crée un zipArchive
        $zip = new ZipArchive();
        //on crée le zip qui aura pour nom le nom de l\'identifiant de l\'add_on dans le formulaire
        $identifiantAddOn = $identifiant . ".zip";
        //si le zip est ouvert et creer
        if ($zip->open($identifiantAddOn, ZipArchive::CREATE) == TRUE) {
            //le zip est ouvert
            echo '&quot;Zip.zip&quot; ouvert';
            //on creer les dossier specifs/hooks/actions/manage et les chemin d'acces
            $zip->addEmptyDir('specifs/hooks/actions/manage');
            //on crée le dossier dont le nom sera celui de  identifiantAddOn inserer dans le formulaire et on décrit le chemin d'accés de ce dossier
            $zip->addEmptyDir('specifs/addons/' . $identifiant . '/class/manageTpls');
            $zip->addEmptyDir('specifs/addons/' . $identifiant . '/class/tools/abstracts');
          //  $zip->addEmptyDir('specifs/addons/' . $identifiant . '/class/' . $valeur);
            //on crée le chemin d'acces du fichier database.sql qui se trouve dans le dossier identifiantAddOn
            $zip->addFromString('specifs/addons/' . $identifiant . '/database.sql', $worked);
            //on crée le chemin d'acces du fichier data.xml qui se trouve dans le dossier identifiantAddOn
            $zip->addFromString('specifs/addons/' . $identifiant . '/data.xml', $xmlFile->saveXML());
            //on crée le chemin d'accés du fichier readme.pdf qui se trouve dans le dossier identifiantAddOn
            $zip->addFile('C:/wamp64/www/generateur/public/pdf/readme.pdf', 'specifs/addons/' . $identifiant . '/readme.pdf');
            //on crée le chemin d'accés du fichier nom de l'identifiant.class.php qui se trouve dans le dossier class
            $zip->addFromString('specifs/addons/' . $identifiant . '/class/' . $filename, file_get_contents($filename));
            //on crée le chemin d'accés du fichier nom de l'identifiant add-on.php qui se trouve dans le dossier manage
            $zip->addFromString('specifs/hooks/actions/manage/'. $file1, file_get_contents($file1));
            //on crée le chemin d'accés du fichier mxToolsAbstract.class.php qui se trouve dans le dossier abstracts
            $zip->addFromString('specifs/addons/' . $identifiant . '/class/tools/abstracts/' . $fileMxToolsAbstract, file_get_contents($fileMxToolsAbstract));
            //on crée le chemin d'accés du fichier InterfaceInstallation.class.php qui se trouve dans le dossier interface
            $zip->addFromString('specifs/addons/' . $identifiant . '/class/tools/interface/' . $fileInterfaceInstallation, file_get_contents($fileInterfaceInstallation));
            //on crée le chemin d'accés du fichier InterfaceScreen.class.php qui se trouve dans le dossier interface
            $zip->addFromString('specifs/addons/' . $identifiant . '/class/tools/interface/' . $fileInterfaceScreen, file_get_contents($fileInterfaceScreen));
            //on créé le chemin d'accés du fichier InterfaceTools.class.php qui se trouve dans le dossier interface
            $zip->addFromString('specifs/addons/' . $identifiant . '/class/tools/interface/' . $fileInterfaceTools, file_get_contents($fileInterfaceTools));
            //on crée le chemin d'accés du fichier InterfaceScreen.html qui se trouve dans le dossier manageTpls
            $zip->addFromString('specifs/addons/' . $identifiant . '/class/manageTpls/InterfaceScreen.html', $html);


            foreach($checkbox as $valeur){
                if ($valeur == 'actions'){
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/' . $valeur . '/' . $choixtypeAction . '/' . $fileAction, file_get_contents($fileAction));
           }
           if ($valeur == 'notification') {
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/' . $valeur . '/' . $fileNotifications, file_get_contents($fileNotifications));
           }
           if ($valeur == 'crons') {
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/' . $valeur . '/' . $fileCrons, file_get_contents($fileCrons));
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/cron_class.php', "");
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/menus/monitoring/emajine_menu_monitoring.xml', $emajineMenuMonitoring->saveXML());
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/menus/monitoring/' . $fileMenuMonitoring, file_get_contents($fileMenuMonitoring));
           }
           if ($valeur == 'mxTags') {
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/' . $valeur . '/' . $identifiantMxTags . '/' . $fileMxTags, file_get_contents($fileMxTags));
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/' . $valeur . '/' . $identifiantMxTags . '/' . $fileMxTags1, file_get_contents($fileMxTags1));
           }
           if ($valeur == 'publication_methods') {
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/' . $valeur . '/' . $identifiantmethodPublication . '/' . $fileMethodePublication, file_get_contents($fileMethodePublication));
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/' . $valeur . '/' . $identifiantmethodPublication . '/' . $fileMethodePublicationCrud, file_get_contents($fileMethodePublicationCrud));
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/'.$valeur.'/'.$identifiantmethodPublication.'/method_'.$identifiantmethodPublication.'.xml',$publicationMethode->saveXML());
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/templates/'.$valeur .'/crudList.html', $crudListHtml);
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/templates/'.$valeur .'/search.html', $search);
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/tools/'. $fileManager, file_get_contents($fileManager));
           }
           if($valeur == 'widgets') {
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/' . $valeur . '/' . $identifiantWidget . '/' . $fileWidgetManage, file_get_contents($fileWidgetManage));
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/' . $valeur . '/' . $identifiantWidget . '/' . $fileWidgetTest, file_get_contents($fileWidgetTest));
               $zip->addFromString('specifs/addons/' . $identifiant . '/class/' . $valeur . '/' . $identifiantWidget . '/specifbox_'.$identifiantWidget.'.xml',$specifboxWidget->saveXML());
           }
           if($valeur == 'Menu 2c avec crud'){
               $zip->addFromString('specifs/addons/'. $identifiant .'/class/hooks/core/'.$fileHooksMenu2cCrud, file_get_contents($fileHooksMenu2cCrud));
               $zip->addFromString('specifs/addons/'. $identifiant .'/class/tools/crud/'.$fileMenu2cCrud, file_get_contents($fileMenu2cCrud));

           }
           if($valeur == 'Menu 2c avec form'){
               $zip->addFromString('specifs/addons/'. $identifiant .'/class/hooks/core/'.$fileHooksMenu2cForm, file_get_contents($fileHooksMenu2cForm));
               $zip->addFromString('specifs/addons/'. $identifiant .'/class/tools/form/'.$fileMenu2cForm, file_get_contents($fileMenu2cForm));
           }
           if($valeur == 'Nouvelle section avec un crud'){
               $zip->addFromString('specifs/addons/'. $identifiant .'/class/menus/new/'.$fileSectionCrud, file_get_contents($fileSectionCrud));
               $zip->addFromString('specifs/addons/'. $identifiant .'/class/menus/new/emajine_menu.xml',$emajineMenu->saveXML());
               $zip->addFromString('specifs/addons/'. $identifiant .'/class/tools/crud/'.$fileCrud, file_get_contents($fileCrud));
           }
           if($valeur == 'Nouvelle section avec un formulaire'){
               $zip->addFromString('specifs/addons/'. $identifiant .'/class/menus/new/'.$fileSectionFormulaire, file_get_contents($fileSectionFormulaire));
               $zip->addFromString('specifs/addons/'. $identifiant .'/class/menus/new/emajine_menu_new.xml',$emajineMenu->saveXML());
           }
           if($valeur == 'catalog/orderActions'){
                    $zip->addEmptyDir('specifs/addons/'. $identifiant .'/class/'.$valeur);
                }
           if($valeur == 'catalog/orderActionsConditions'){
                    $zip->addEmptyDir('specifs/addons/'. $identifiant .'/class/'.$valeur);
                }
           if($valeur == 'community'){
                    $zip->addEmptyDir('specifs/addons/'. $identifiant .'/class/'.$valeur);
                }
           if($valeur == 'CustomPaymentModules'){
                    $zip->addEmptyDir('specifs/addons/'. $identifiant .'/class/'.$valeur);
                }
           if($valeur == 'hooks'){
                   $zip->addEmptyDir('specifs/addons/'. $identifiant .'/class/'.$valeur);
               }
           if($valeur == 'Web service'){
                   $zip->addFromString('specifs/addons/'. $identifiant .'/class/hooks/core/'.$fileHooksWebService, file_get_contents($fileHooksWebService));
                   $zip->addFromString('specifs/addons/'. $identifiant .'/class/tools/'.$fileAPIWebService, file_get_contents($fileAPIWebService));
                   $zip->addFromString('specifs/addons/'. $identifiant .'/class/tools/'.$fileControllerWebService, file_get_contents($fileControllerWebService));
                   $zip->addFromString('specifs/addons/'. $identifiant .'/class/tools/'.$fileELogWebService, file_get_contents($fileELogWebService));
                   $zip->addFromString('specifs/addons/'. $identifiant .'/class/tools/'.$fileFactoryWebService, file_get_contents($fileFactoryWebService));
                   $zip->addFromString('specifs/addons/'. $identifiant .'/class/tools/'.$fileHttpResponseWebService, file_get_contents($fileHttpResponseWebService));
                   $zip->addFromString('specifs/addons/' .$identifiant . '/class/tools/abstracts/'.$fileManagerWebService, file_get_contents($fileManagerWebService));
                   $zip->addFromString('specifs/addons/'.$identifiant . '/class/tools/APIMEthod/'.$fileMethod1WebService, file_get_contents($fileMethod1WebService));
                }
           if($valeur == 'Interactive map'){
                   $zip->addFile('C:/wamp64/www/generateur/public/images/_m1.png','specifs/addons/'.$identifiant . '/assets/images/markercluster/_m1.png');
                   $zip->addFile('C:/wamp64/www/generateur/public/images/m1.png','specifs/addons/'.$identifiant . '/assets/images/markercluster/m2.png');
                   $zip->addFile('C:/wamp64/www/generateur/public/images/m2.png','specifs/addons/'.$identifiant . '/assets/images/markercluster/m3.png');
                   $zip->addFile('C:/wamp64/www/generateur/public/images/m3.png','specifs/addons/'.$identifiant . '/assets/images/markercluster/m4.png');
                   $zip->addFile('C:/wamp64/www/generateur/public/images/m4.png','specifs/addons/'.$identifiant . '/assets/images/markercluster/m5.png');
                   $zip->addFile('C:/wamp64/www/generateur/public/images/m5.png','specifs/addons/'.$identifiant . '/assets/images/markercluster/m6.png');
                   $zip->addFile('C:/wamp64/www/generateur/public/images/m7.png','specifs/addons/'.$identifiant . '/assets/images/markercluster/m7.png');
                   $zip->addEmptyDir('specifs/addons/'.$identifiant . '/assets/modeles');
                   $zip->addFile('C:/wamp64/www/generateur/public/js/infobubble.js','specifs/addons/'.$identifiant . '/assets/scripts/js/googlemaps/infobubble.js');
                   $zip->addFile('C:/wamp64/www/generateur/public/js/InteractivMap.js','specifs/addons/'.$identifiant . '/assets/scripts/InteractivMap.js');
                   $zip->addFile('C:/wamp64/www/generateur/public/js/markerclusterer.js','specifs/addons/'.$identifiant . '/assets/scripts/markerclusterer.js');
                   $zip->addFromString('specifs/addons/'.$identifiant . '/class/actions/public/'.$fileGetMapCoordInteractivMap,file_get_contents($fileGetMapCoordInteractivMap));
                   $zip->addFromString('specifs/addons/'.$identifiant . '/class/actions/public/'.$fileGetProductStoresInteractivMap,file_get_contents($fileGetProductStoresInteractivMap));
                   $zip->addFromString('specifs/addons/'.$identifiant . '/class/publication_methods/interactivMap/'.$fileMethodeManageInteractivMap,file_get_contents($fileMethodeManageInteractivMap));
                   $zip->addFromString('specifs/addons/'.$identifiant . '/class/publication_methods/interactivMap/'.$fileMethodePublicInteractivMap,file_get_contents($fileMethodePublicInteractivMap));
                   $zip->addFromString('specifs/addons/'. $identifiant .'/class/publication_methods/interactivMap/method_interactivMap.xml',$interactivMap->saveXML());
                   $zip->addFromString('specifs/addons/'.$identifiant . '/class/tools/'.$fileMapManagerInteractivMap,file_get_contents($fileMapManagerInteractivMap));
           }
            }



            $zip->close();


            //on propose le téléchargement

            header('Content-Disposition:attachment; filename="' . $identifiantAddOn . '"');
            //header('Content_Length:' . filesize($identifiantAddOn));


            readfile('pdf/readme.pdf');
            readfile($identifiantAddOn);
            unlink($identifiantAddOn);


            //on ferme le zip
            exit();

        } else {
            echo 'Impossible d&#039; ouvrir &quot;Zip.zip&quot;';
        }


    }


}