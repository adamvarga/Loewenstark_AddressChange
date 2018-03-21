<?php

class Loewenstark_AddressChange_Model_Observer extends Mage_Core_Model_Abstract {

    public function beforeAddressSave($observer) {

        /*
         * Get original and new customer data and check if the plugin is active
         */

        $customer_address = $observer->getEvent()->getCustomerAddress();
        $customer_orig_address = Mage::getModel('customer/address')->load($customer_address->getId());

        if (!Mage::getStoreConfigFlag('ls_addresschange/general/active')) {
            return '';
        } else {
            $mailer = Mage::getModel('ls_addresschange/mail');
            return $mailer->sendMail($customer_address, $customer_orig_address);
        }
    }

}
