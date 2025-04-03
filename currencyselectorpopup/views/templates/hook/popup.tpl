{**
 * Currency Selector Popup Module for PrestaShop
 * 
 * @author    Developed for client
 * @copyright 2023
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *}

<div id="currency-selector-popup" class="currency-selector-popup" style="display: none;">
    <div class="currency-popup-overlay"></div>
    <div class="currency-popup-container">
        <button type="button" class="currency-popup-close">
            <span class="material-icons">close</span>
        </button>
        
        <div class="currency-popup-header">
            <div class="currency-popup-logo">
                <img src="{$urls.base_url}img/{Configuration::get('PS_LOGO')}" alt="{$shop.name}" />
            </div>
            <h2>{l s='Select your currency' mod='currencyselectorpopup'}</h2>
        </div>
        
        <div class="currency-popup-content">
            <ul class="currency-list">
                {foreach from=$currencies item=currency}
                    <li class="currency-item {if $currency.id_currency == $current_currency.id}selected{/if}" data-currency-id="{$currency.id_currency}">
                        <span class="currency-symbol">{$currency.symbol}</span>
                        <span class="currency-name">{$currency.name}</span>
                    </li>
                {/foreach}
            </ul>
            
            <div class="currency-popup-remember">
                <label class="currency-remember-label">
                    <input type="checkbox" id="currency-remember-choice" class="currency-remember-checkbox" />
                    <span>{l s='Remember my choice' mod='currencyselectorpopup'}</span>
                </label>
            </div>
        </div>
        
        <div class="currency-popup-footer">
            <button type="button" class="currency-popup-btn currency-popup-btn-primary" id="currency-popup-confirm">
                {l s='Confirm' mod='currencyselectorpopup'}
            </button>
            <button type="button" class="currency-popup-btn currency-popup-btn-secondary" id="currency-popup-cancel">
                {l s='Cancel' mod='currencyselectorpopup'}
            </button>
        </div>
    </div>
</div>
