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
}
