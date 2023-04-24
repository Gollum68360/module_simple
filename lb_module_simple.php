<?php



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
        return parent::install();
    }

    public function uninstall()
    {
        return parent::uninstall();
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
                    'label' => $this->l('Prix minimum'),
                    'name' => 'MAX',  // convention majuscule
                    'required' => true,
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

        return $helper->generateForm($form_configuration);
    }
}
