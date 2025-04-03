<?php
/**
 * Currency Selector Popup Module for PrestaShop
 * 
 * AJAX controller to handle currency change
 * 
 * @author    Developed for client
 * @copyright 2023
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class CurrencySelectorPopupAjaxModuleFrontController extends ModuleFrontController
{
    /**
     * Handle AJAX requests for currency change
     */
    public function postProcess()
    {
        // Set header for JSON response
        header('Content-Type: application/json');
        
        // Check if AJAX request
        if (!$this->isXmlHttpRequest()) {
            $this->ajaxDie(json_encode([
                'success' => false,
                'message' => 'Invalid request'
            ]));
            return;
        }
        
        // Get currency ID from request
        $id_currency = (int)Tools::getValue('id_currency');
        if (!$id_currency) {
            $this->ajaxDie(json_encode([
                'success' => false,
                'message' => 'Invalid currency ID'
            ]));
            return;
        }
        
        // Get remember choice option
        $remember = (bool)Tools::getValue('remember', false);
        
        // Set currency cookie
        $this->context->cookie->id_currency = $id_currency;
        
        // If remember choice is true, set a cookie to not show the popup again
        if ($remember) {
            $this->context->cookie->__set('currency_selector_seen', true);
        }
        
        // Save the cookie
        $this->context->cookie->write();
        
        // Return success response
        $this->ajaxDie(json_encode([
            'success' => true,
            'message' => 'Currency changed successfully',
            'id_currency' => $id_currency
        ]));
    }
}
