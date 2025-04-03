<?php
/**
 * Currency Selector Popup Module for PrestaShop
 *
 * @author    Developed for client
 * @copyright 2023
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class CurrencySelectorPopup extends Module
{
    public function __construct()
    {
        $this->name = 'currencyselectorpopup';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'PrestaShop Developer';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Currency Selector Popup');
        $this->description = $this->l('Display a modern popup for currency selection with session persistence');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    /**
     * Module installation
     *
     * @return bool
     */
    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayFooter');
    }

    /**
     * Module uninstallation
     *
     * @return bool
     */
    public function uninstall()
    {
        return parent::uninstall();
    }

    /**
     * Hook to header to add CSS and JS
     *
     * @param array $params
     * @return string
     */
    public function hookDisplayHeader($params)
    {
        // Load CSS
        $this->context->controller->addCSS($this->_path . 'views/css/currencyselectorpopup.css');
        
        // Load JS
        $this->context->controller->addJS($this->_path . 'views/js/currencyselectorpopup.js');
        
        // Add module JS variables
        Media::addJsDef(array(
            'currencySelectorAjaxUrl' => $this->context->link->getModuleLink($this->name, 'ajax', array(), true),
            'currencySelectorShopLogo' => _PS_IMG_DIR_ . Configuration::get('PS_LOGO'),
            'currencySelectorTranslations' => array(
                'title' => $this->l('Select your currency'),
                'save_choice' => $this->l('Remember my choice'),
                'close' => $this->l('Close')
            )
        ));
    }

    /**
     * Hook to footer to display the popup
     *
     * @param array $params
     * @return string
     */
    public function hookDisplayFooter($params)
    {
        // Get available currencies
        $currencies = Currency::getCurrencies(false, true);
        $current_currency = $this->context->currency;
        
        // Check if the popup has been shown before (stored in cookies)
        $cookie_name = 'currency_selector_seen';
        $cookie_saved = $this->context->cookie->__get($cookie_name);
        
        $this->context->smarty->assign(array(
            'currencies' => $currencies,
            'current_currency' => $current_currency,
            'cookie_saved' => $cookie_saved,
            'shop_logo' => _PS_IMG_DIR_ . Configuration::get('PS_LOGO'),
            'module_dir' => $this->_path
        ));
        
        return $this->display(__FILE__, 'views/templates/hook/popup.tpl');
    }
}
