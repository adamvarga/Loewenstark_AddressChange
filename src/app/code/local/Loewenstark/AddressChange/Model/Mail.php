<?php

class Loewenstark_AddressChange_Model_Mail {

    public function sendMail($customer_address, $customer_orig_address) {

        $send = false;
        $customer_id = $customer_address->getCustomerId();

        /*
         * Get original addresse from customer
         */

        $firstname = $customer_orig_address->getFirstname();
        $lastname = $customer_orig_address->getLastname();
        $company = $customer_orig_address->getCompany();
        foreach ($customer_orig_address->getStreet() as $street_details) {
            $street .= $street_details . ' ';
        }
        $city = $customer_orig_address->getCity();
        $country = $customer_orig_address->getCountryId();
        $postcode = $customer_orig_address->getPostcode();
        $telephone = $customer_orig_address->getTelephone();
        $fax = $customer_orig_address->getFax();

        /*
         * Get new addresse from customer
         */

        $firstname_new = $customer_address->getFirstname();
        $lastname_new = $customer_address->getLastname();
        $company_new = $customer_address->getCompany();
        foreach ($customer_address->getStreet() as $street_details_new) {
            $street_new .= $street_details_new . ' ';
        }
        $city_new = $customer_address->getCity();
        $country_new = $customer_address->getCountryId();
        $postcode_new = $customer_address->getPostcode();
        $telephone_new = $customer_address->getTelephone();
        $fax_new = $customer_address->getFax();

        /*
         * Check if the data was changed
         */

        if ($customer_address->getFirstname() !== $customer_orig_address->getFirstname()) {
            $msg = Mage::helper('ls_addresschange')->__('Firstname was changed to %s', $firstname_new) . '<br>';
            $send = true;
        }

        if ($customer_address->getLastname() !== $customer_orig_address->getLastname()) {
            $msg .= Mage::helper('ls_addresschange')->__('Lastname was changed to %s', $lastname_new) . '<br>';
            $send = true;
        }

        if ($customer_address->getCompany() !== $customer_orig_address->getCompany()) {
            $msg .= Mage::helper('ls_addresschange')->__('Company was changed to %s', $company_new) . '<br>';
            $send = true;
        }

        if ($customer_address->getStreet() !== $customer_orig_address->getStreet()) {
            $msg .= Mage::helper('ls_addresschange')->__('Street was changed to %s', $street_new) . '<br>';
            $send = true;
        }

        if ($customer_address->getCity() !== $customer_orig_address->getCity()) {
            $msg .= Mage::helper('ls_addresschange')->__('City was changed to %s', $city_new) . '<br>';
            $send = true;
        }

        if ($customer_address->getCountryId() !== $customer_orig_address->getCountryId()) {
            $msg .= Mage::helper('ls_addresschange')->__('Country was changed to %s', $country_new) . '<br>';
            $send = true;
        }

        if ($customer_address->getPostcode() !== $customer_orig_address->getPostcode()) {
            $msg .= Mage::helper('ls_addresschange')->__('Postcode was changed to %s', $postcode_new) . '<br>';
            $send = true;
        }

        if ($customer_address->getTelephone() !== $customer_orig_address->getTelephone()) {
            $msg .= Mage::helper('ls_addresschange')->__('Telephone was changed to %s', $telephone_new) . '<br>';
            $send = true;
        }

        if ($customer_address->getFax() !== $customer_orig_address->getFax()) {
            $msg .= Mage::helper('ls_addresschange')->__('Fax was changed to %s', $fax_new) . '<br>';
            $send = true;
        }

        /*
         * Setup email template and send
         */

        $template_id = 'addresschange_result';
        $mail = Mage::getModel('core/email_template')->loadDefault($template_id);
        $mail_from = Mage::getStoreConfig('trans_email/ident_general/email');
        $mail_to = Mage::getStoreConfig('trans_email/ident_custom1/email');
        $customer_name = Mage::getStoreConfig('trans_email/ident_general/name');
        $mail_subject = Mage::helper('ls_addresschange')->__('Address was changed by %s', $firstname . ' ' . $lastname . ' - ' . $company);
        $mail_name = Mage::getStoreConfig('trans_email/ident_general/name');
        $mail->setSenderName($mail_name);
        $mail->setSenderEmail($mail_to);

        $email_template_variables = array(
            'msg' => $msg,
            'customer_name' => $customer_name,
            'customer_id' => $customer_id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'company' => $company,
            'street' => $street,
            'city' => $city,
            'country' => $country,
            'postcode' => $postcode,
            'telephone' => $telephone,
            'fax' => $fax
        );
        $mail->setTemplateSubject(trim($mail_subject));
        $mail->setFromEmail($mail_from);
        $mail->setFromName($mail_name);
        $mail->setType('html');
        if ($send) {
            try {
                $mail->send($mail_to, $customer_name, $email_template_variables);
            } catch (Exception $e) {
                Mage::log($e, 'exception.log', true);
            }
        }
    }

}
