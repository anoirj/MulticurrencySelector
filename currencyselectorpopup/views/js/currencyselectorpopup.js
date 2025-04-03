/**
 * Currency Selector Popup Module for PrestaShop
 * 
 * @author    Developed for client
 * @copyright 2023
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the currency selector popup
    initCurrencySelectorPopup();
});

/**
 * Initialize the currency selector popup
 */
function initCurrencySelectorPopup() {
    const popup = document.getElementById('currency-selector-popup');
    
    // If popup doesn't exist, return
    if (!popup) return;
    
    // Check if the user has already seen the popup
    const hasSeenPopup = getCookie('currency_selector_seen');
    
    // Only show popup if the user hasn't seen it before
    if (!hasSeenPopup) {
        setTimeout(function() {
            showPopup();
        }, 1000);
    }
    
    // Add event listeners
    const closeButton = popup.querySelector('.currency-popup-close');
    const cancelButton = document.getElementById('currency-popup-cancel');
    const confirmButton = document.getElementById('currency-popup-confirm');
    const currencyItems = popup.querySelectorAll('.currency-item');
    const overlay = popup.querySelector('.currency-popup-overlay');
    
    // Close button event
    if (closeButton) {
        closeButton.addEventListener('click', hidePopup);
    }
    
    // Cancel button event
    if (cancelButton) {
        cancelButton.addEventListener('click', hidePopup);
    }
    
    // Overlay click event
    if (overlay) {
        overlay.addEventListener('click', hidePopup);
    }
    
    // Currency item click events
    if (currencyItems.length) {
        currencyItems.forEach(function(item) {
            item.addEventListener('click', function() {
                // Remove selected class from all items
                currencyItems.forEach(function(i) {
                    i.classList.remove('selected');
                });
                
                // Add selected class to clicked item
                this.classList.add('selected');
            });
        });
    }
    
    // Confirm button event
    if (confirmButton) {
        confirmButton.addEventListener('click', function() {
            const selectedCurrency = popup.querySelector('.currency-item.selected');
            
            if (selectedCurrency) {
                const currencyId = selectedCurrency.getAttribute('data-currency-id');
                const rememberChoice = document.getElementById('currency-remember-choice').checked;
                
                // Change currency via AJAX
                changeCurrency(currencyId, rememberChoice);
            }
        });
    }
    
    /**
     * Show the popup
     */
    function showPopup() {
        popup.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }
    
    /**
     * Hide the popup
     */
    function hidePopup() {
        popup.style.display = 'none';
        document.body.style.overflow = '';
    }
    
    /**
     * Change the currency
     *
     * @param {number} currencyId
     * @param {boolean} remember
     */
    function changeCurrency(currencyId, remember) {
        // Create the request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', currencySelectorAjaxUrl, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        // Define what happens on successful data submission
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    
                    if (response.success) {
                        // Hide the popup
                        hidePopup();
                        
                        // Reload the page to apply the new currency
                        window.location.reload();
                    } else {
                        console.error('Currency change failed:', response.message);
                    }
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                }
            } else {
                console.error('Request failed:', xhr.statusText);
            }
        };
        
        // Define what happens in case of error
        xhr.onerror = function() {
            console.error('Network error occurred');
        };
        
        // Send the request
        xhr.send('id_currency=' + currencyId + '&remember=' + (remember ? 1 : 0));
    }
    
    /**
     * Get a cookie value by name
     *
     * @param {string} name
     * @return {string|null}
     */
    function getCookie(name) {
        const cookieArr = document.cookie.split(";");
        
        for (let i = 0; i < cookieArr.length; i++) {
            const cookiePair = cookieArr[i].split("=");
            
            if (name === cookiePair[0].trim()) {
                return decodeURIComponent(cookiePair[1]);
            }
        }
        
        return null;
    }
}
