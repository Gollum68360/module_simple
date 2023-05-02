<?php

if (!defined('_PS_VERSION_')) {
    exit;
}


class Lb_Module_Simple extends Module
{
    public function __construct()
    {
        $this->displayName = $this->l('Module simple');
        $this->name = 'lb_module_simple';
        $this->description = 'prix aléatoire';
        $this->version = '1.0.0';
        $this->author = 'Lucien Bruzzese';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();
    }

    public function install()
    {

        if (!parent::install() || !$this->registerHook('displayHome')) {
            return false;
        }

        return true;
    }

    public function hookDisplayHome()
    {


        //déclarer un objet de type catégorie déjà hydraté
        $categorie1 = new Category(Configuration::get('KAWA_INFO_CATETGORY_1'), $this->context->language->id);



        //Récupère las infos de la table ps configuration et envoie à smarty

        $this->context->smarty->assign(array(
            'kawa_text' => Configuration::get('KAWA_INFO_CAT'), //clé : nom de ma rariable smarty /sa valeur
            'categorie1' => $categorie1,
        ));

        //mon fichier tpl doit se trouver dans le dossier views/templates/hook
        return $this->display(__FILE__, "home.tpl");
    }




    public function getContent()
    {

        if (Tools::isSubmit("submit_lb_module_simple")) { //vérifie si formulaire envoyé avec la name de mon bouton

            // Tools::dieObject('test');  permet d'arrêter l'execution de la page et d'afficher une variable comme dd symfony

            $message = Tools::getValue('MIN'); //getValue permet de récupérer des données envoyé en post ou get
            //updateValue à 2 paramètres : le nom de mon champ et la valeur
            Configuration::updateValue('MIN', $message); //Inert ou update la table de config en fonction du nom du champ
        }

        return $this->displayForm();
    }

    public function displayForm()
    {

        $categories = Category::getAllCategoriesName();

        //déclare au tableau avec les infos du formulaire
        $form_configuration['0']['form'] = [
            'legend' => [
                'title' => $this->l('settings'), //la function l permet de gérer les traductions
            ],
            'input' => [
                [
                    'type' => 'text', // typede champ : text, select, radio, etc...
                    'label' => $this->l('Prix minimum'),
                    'name' => 'MIN',  // convention majuscule
                    'required' => true,
                ],

                [
                    'type' => 'text', // typede champ : text, select, radio, etc...
                    'label' => $this->l('Prix maximum'),
                    'name' => 'MAX',  // convention majuscule
                    'required' => true,
                ],

                [
                    'type' => 'select',
                    'label' => 'choisir une categorie',
                    'name' => 'LB_CAT',
                    'required' => true,
                    'options' => array(
                        'query' => $categories,
                        'id' => 'id_category',
                        'name' => 'name'
                    )
                ],



            ],


            'submit' => [
                'title' => $this->l('enregistrons ce truc'),
                'class' => 'btn btn-defaults pull-right',
            ]
        ];

        $helper = new HelperForm();

        $helper->module = $this; // instance du module
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules'); //récupère le token de la page module

        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name; //configurer l'atttribut action du formulaire
        $helper->default_form_language = (int)configuration::get('PS_LANG_DEFAULT');
        $helper->title = $this->displayName;
        $helper->submit_action = "submit_" . $this->name;  //ajoute un attribut name a mon bouton

        $helper->fields_value['MIN'] = Tools::getValue('MIN', Configuration::get('MIN'));
        $helper->fields_value['MAX'] = Tools::getValue('MAX', Configuration::get('MAX'));
        $helper->fields_value['LB_CAT'] = Tools::getValue('LB_CAT', Configuration::get('LB_CAT'));

        return $helper->generateForm($form_configuration);
    }
}
