/*jshint browser:true jquery:true*/
/*global alert*/
define([
    "jquery",
    "mage/utils/wrapper",
    "Magento_Checkout/js/model/quote",
], function ($, wrapper, quote) {
    "use strict";

    return function (setShippingInformationAction) {
        return wrapper.wrap(
            setShippingInformationAction,
            function (originalAction) {
                var shippingAddress = quote.shippingAddress();
                var billingAddress = quote.billingAddress();
                if (shippingAddress["extension_attributes"] === undefined) {
                    shippingAddress["extension_attributes"] = {};
                }
                if (billingAddress["extension_attributes"] === undefined) {
                    billingAddress["extension_attributes"] = {};
                }

                shippingAddress["extension_attributes"]["delivery_note"] =
                    quote.shippingAddress().extensionAttributes.delivery_note;

                billingAddress["extension_attributes"]["delivery_note"] =
                    quote.billingAddress().extensionAttributes.delivery_note;

                // pass execution to original action ('Magento_Checkout/js/action/set-shipping-information')
                return originalAction();
            }
        );
    };
});
