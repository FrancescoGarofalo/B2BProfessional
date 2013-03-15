<?php
/**
 * Sitewards_B2BProfessional_Model_Observer
 * 	- Observer to catch the following actions
 *
 * @category    Sitewards
 * @package     Sitewards_B2BProfessional
 * @copyright   Copyright (c) 2013 Sitewards GmbH (http://www.sitewards.com/)
 */
class Sitewards_B2BProfessional_Model_Observer {
	/**
	 * Check if the site requires login to work
	 * 	- Add notice,
	 * 	- Redirect to the home page,
	 *
	 * @param Varien_Event_Observer $oObserver
	 */
	public function onControllerActionPreDispatch(Varien_Event_Observer $oObserver) {
		/* @var $oHelper Sitewards_B2BProfessional_Helper_Data */
		$oHelper = Mage::helper('b2bprofessional');
		if($oHelper->checkGlobalActive() == true) {
			if($oHelper->checkRequireLogin() == true) {
				/* @var $oControllerAction Mage_Core_Controller_Front_Action */
				$oControllerAction = $oObserver->getData('controller_action');
				/*
				 * Check to see if the controller is Cms related
				 */
				if(!$oControllerAction instanceof Mage_Cms_IndexController && $oControllerAction instanceof Mage_Core_Controller_Front_Action){
					// Redirect to the homepage
					/* @var $oResponse Mage_Core_Controller_Response_Http */
					$oResponse = $oControllerAction->getResponse();
					$oResponse->setRedirect(Mage::getUrl('/'));

					/*
					 * Add message to the session
					 * 	- Note:
					 * 		We need session_write_close otherwise the messages get lots in redirect
					 */
					/* @var $oSession Mage_Core_Model_Session */
					$oSession = Mage::getSingleton('core/session');
					$oSession->addNotice('You do not have access to view this shop');
					session_write_close();
				}
			}
		}
	}
}