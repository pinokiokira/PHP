/*
SQLyog Community v13.1.2 (64 bit)
MySQL - 5.7.25-log : Database - softpointlive_030419
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`softpointlive_030419` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `softpointlive_030419`;

/*Table structure for table `ab_locations_import` */

DROP TABLE IF EXISTS `ab_locations_import`;

CREATE TABLE `ab_locations_import` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `tag_name` varchar(100) DEFAULT NULL,
  `restaurantTitle` varchar(100) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(64) NOT NULL,
  `state` int(4) NOT NULL,
  `zip` varchar(16) NOT NULL,
  `country` int(4) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `phone_formatted` varchar(32) NOT NULL,
  `website` varchar(200) NOT NULL,
  `hours` varchar(400) NOT NULL,
  `price_info` varchar(64) NOT NULL,
  `rating` varchar(64) NOT NULL,
  `cuisine_details` varchar(400) NOT NULL,
  `additional_details` text NOT NULL,
  `alcoholInfo` varchar(64) NOT NULL,
  `alcohol` enum('Yes','No') NOT NULL DEFAULT 'No',
  `import_filename` varchar(64) NOT NULL,
  `collate_error` enum('Yes','No') NOT NULL DEFAULT 'No',
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ab_locations_name_ph_zip_idx` (`name`,`phone`,`zip`),
  KEY `ab_locations_seach1_idx` (`name`,`phone`,`zip`,`address`),
  KEY `ab_locations_seach2_idx` (`phone`,`zip`,`address`,`state`,`country`,`website`,`rating`,`additional_details`(1000)),
  KEY `ab_locations_name_idx` (`name`),
  KEY `ab_locations_phone_idx` (`phone`),
  KEY `ab_locations_state_zip_idx` (`state`,`zip`),
  KEY `ab_locations_zip_idx` (`zip`),
  KEY `ab_locations_state_idx` (`state`)
) ENGINE=InnoDB AUTO_INCREMENT=737381 DEFAULT CHARSET=latin1 COMMENT='General information about an AB location';

/*Table structure for table `ads_groups` */

DROP TABLE IF EXISTS `ads_groups`;

CREATE TABLE `ads_groups` (
  `ads_groups_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `name` varchar(45) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ads_groups_id`),
  UNIQUE KEY `ads_groups_id_UNIQUE` (`ads_groups_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `ads_groups_ads` */

DROP TABLE IF EXISTS `ads_groups_ads`;

CREATE TABLE `ads_groups_ads` (
  `ads_groups_ads_id` int(11) NOT NULL AUTO_INCREMENT,
  `ads_images_id` int(11) DEFAULT NULL,
  `ads_groups_id` int(11) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ads_groups_ads_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `ads_images` */

DROP TABLE IF EXISTS `ads_images`;

CREATE TABLE `ads_images` (
  `ads_images_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `name` varchar(45) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `image` varchar(64) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ads_images_id`),
  UNIQUE KEY `ads_images_id_UNIQUE` (`ads_images_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `ads_temp_location` */

DROP TABLE IF EXISTS `ads_temp_location`;

CREATE TABLE `ads_temp_location` (
  `ads_temp_location_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `name` varchar(64) NOT NULL,
  `address` varchar(64) DEFAULT NULL,
  `address2` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) DEFAULT NULL,
  `country` int(4) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `last_by` varchar(45) NOT NULL,
  `last_on` varchar(45) NOT NULL,
  `last_datetime` datetime NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`ads_temp_location_id`),
  UNIQUE KEY `ads_temp_location_id_UNIQUE` (`ads_temp_location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `ads_terminals` */

DROP TABLE IF EXISTS `ads_terminals`;

CREATE TABLE `ads_terminals` (
  `ads_terminals_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `name` varchar(45) NOT NULL,
  `mac_address` varchar(256) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `ads_temp_location_id` int(11) DEFAULT NULL,
  `display_menu` enum('yes','no') DEFAULT 'no',
  `display_menu_order` enum('yes','no') DEFAULT 'no',
  `display_server` enum('yes','no') DEFAULT 'no',
  `display_pay` enum('yes','no') DEFAULT 'no',
  `display_pay_paypal` enum('yes','no') NOT NULL DEFAULT 'no',
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ads_terminals_id`),
  UNIQUE KEY `ads_terminals_id_UNIQUE` (`ads_terminals_id`),
  UNIQUE KEY `mac_address_UNIQUE` (`mac_address`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `aio_apps` */

DROP TABLE IF EXISTS `aio_apps`;

CREATE TABLE `aio_apps` (
  `aio_apps_id` int(11) NOT NULL AUTO_INCREMENT,
  `xtms_id` int(11) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT NULL,
  `app_name` varchar(64) DEFAULT NULL,
  `app_version` varchar(12) DEFAULT NULL,
  `app_versioncode` varchar(12) DEFAULT NULL,
  `app_author` varchar(64) DEFAULT NULL,
  `app_requirements` text,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`aio_apps_id`),
  UNIQUE KEY `aio_apps_id_UNIQUE` (`aio_apps_id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=latin1;

/*Table structure for table `aio_devices` */

DROP TABLE IF EXISTS `aio_devices`;

CREATE TABLE `aio_devices` (
  `aio_devices_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Active','Inactive') NOT NULL,
  `terminal_id` varchar(64) NOT NULL,
  `location_id` int(8) NOT NULL,
  `longitude` varchar(12) DEFAULT NULL,
  `latitude` varchar(12) DEFAULT NULL,
  `comm_type` enum('WiFi','4G') DEFAULT NULL,
  `tcp_ip` varchar(64) DEFAULT NULL,
  `port` varchar(10) DEFAULT NULL,
  `private_ip` varchar(45) DEFAULT NULL,
  `mac_address` varchar(45) DEFAULT NULL,
  `physically_located` varchar(64) DEFAULT NULL,
  `color` varchar(12) DEFAULT NULL,
  `sold_by` varchar(45) DEFAULT NULL,
  `card_not_present` enum('Yes','No') DEFAULT 'No',
  `firmware_version` varchar(45) DEFAULT NULL,
  `integrator_version` varchar(45) DEFAULT NULL,
  `integrator_amount` enum('Yes','No') DEFAULT 'No',
  `integrator_pinpad` enum('Yes','No') DEFAULT 'No',
  `integrator_payment_selector` enum('Yes','No') DEFAULT 'No',
  `integrator_manual` enum('Yes','No') DEFAULT 'No',
  `integrator_signature` enum('Yes','No') DEFAULT 'No',
  `integrator_receipt` enum('Yes','No') DEFAULT 'No',
  `integrator_camera` enum('Yes','No') DEFAULT 'No',
  `integrator_debug` enum('Yes','No') DEFAULT 'No',
  `integrator_accept_debit` enum('Yes','No') DEFAULT 'No',
  `integrator_offline_pin` enum('Yes','No') DEFAULT 'No',
  `integrator_quick_chip` enum('Yes','No') DEFAULT 'No',
  `display_debug_status` enum('Yes','No') DEFAULT 'No',
  `MF_accountid` varchar(45) DEFAULT NULL,
  `host_URL` varchar(64) DEFAULT NULL,
  `omnivore_terminal_id` varchar(45) DEFAULT NULL,
  `omnivore_tender_type_id` varchar(45) DEFAULT NULL,
  `channel_id` varchar(45) DEFAULT NULL,
  `request_log` enum('Yes','No') DEFAULT 'No',
  `requested_log_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`aio_devices_id`),
  UNIQUE KEY `aio_devices_id_UNIQUE` (`aio_devices_id`),
  UNIQUE KEY `terminal_id_UNIQUE` (`terminal_id`),
  KEY `aio_devices_loc_idx` (`location_id`),
  KEY `aio_devices_loc_tid_idx` (`location_id`,`terminal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10070 DEFAULT CHARSET=latin1;

/*Table structure for table `aio_devices_xtms_log` */

DROP TABLE IF EXISTS `aio_devices_xtms_log`;

CREATE TABLE `aio_devices_xtms_log` (
  `aio_devices_xtms_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `aio_devices_id` int(11) NOT NULL,
  `aio_apps_id` int(11) NOT NULL,
  `schedule_id` varchar(45) DEFAULT NULL,
  `log_removed` enum('Yes','No') NOT NULL DEFAULT 'No',
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `xtms_begin` datetime NOT NULL,
  `xtms_end` datetime NOT NULL,
  PRIMARY KEY (`aio_devices_xtms_log_id`),
  UNIQUE KEY `aio_devices_xtms_log_id_UNIQUE` (`aio_devices_xtms_log_id`),
  KEY `aio_devices_xtms_log_created_dt_idk` (`created_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=6639 DEFAULT CHARSET=latin1;

/*Table structure for table `aio_epx_payments` */

DROP TABLE IF EXISTS `aio_epx_payments`;

CREATE TABLE `aio_epx_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('Retail','Restaurant') DEFAULT 'Restaurant',
  `request_url` longtext,
  `request` text,
  `response` text,
  `created_by` int(11) DEFAULT NULL,
  `created_on` varchar(55) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=747 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `aio_integrated_payments` */

DROP TABLE IF EXISTS `aio_integrated_payments`;

CREATE TABLE `aio_integrated_payments` (
  `aio_integrated_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `aio_device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `split` enum('No','Yes') DEFAULT 'No',
  `transactionNo` varchar(45) DEFAULT NULL,
  `client_order_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `omnivore_tickets_id` varchar(45) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `seat` int(11) DEFAULT '99',
  `name` varchar(45) DEFAULT NULL,
  `subtotal` varchar(45) DEFAULT NULL,
  `tax` varchar(45) DEFAULT NULL,
  `tip` varchar(45) DEFAULT NULL,
  `payment` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `changedue` varchar(45) DEFAULT NULL,
  `aio_tsys_payment_id` int(11) DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending','Failure','Wh_Error','Pre_Auth','Sending') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `async_status` enum('No','Processing','Finished','Failed') DEFAULT 'No',
  `payment_error` varchar(45) DEFAULT NULL,
  `show_retry_popup` enum('Yes','No') DEFAULT 'No',
  `make_a_payment` text,
  `make_payment_url` text,
  `response_auth_code` varchar(45) DEFAULT NULL,
  `response_ref_num` varchar(45) DEFAULT NULL,
  `response_entry_mode` varchar(45) DEFAULT NULL,
  `response_card_type` varchar(45) DEFAULT NULL,
  `response_card_holder` varchar(45) DEFAULT NULL,
  `response_bogus_account_num` varchar(45) DEFAULT NULL,
  `response_href` varchar(45) DEFAULT NULL,
  `response_result_code` varchar(45) DEFAULT NULL,
  `response_result_message` varchar(400) DEFAULT NULL,
  `receiptEmvTagMap` varchar(1000) DEFAULT NULL,
  `signature_image` varchar(255) DEFAULT NULL,
  `camera_image` varchar(255) DEFAULT NULL,
  `MF_accountid` varchar(45) DEFAULT NULL,
  `cc_token` varchar(100) DEFAULT NULL,
  `gc_number` varchar(45) DEFAULT NULL,
  `id_pay` int(11) DEFAULT NULL,
  `refunded_transactionNo` varchar(45) DEFAULT NULL,
  `refunded_amount` varchar(45) DEFAULT NULL,
  `refunded_employee_id` varchar(45) DEFAULT NULL,
  `refunded_aio_device_id` varchar(45) DEFAULT NULL,
  `refunded_datetime` datetime DEFAULT NULL,
  `surcharge` varchar(45) DEFAULT NULL,
  `surchargeCardType` varchar(45) DEFAULT NULL,
  `surcharge_item_id` varchar(45) DEFAULT NULL,
  `processed_on` enum('DataPoint','Payment Matcher') DEFAULT 'DataPoint',
  `processed_on_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`aio_integrated_payments_id`),
  KEY `aio_integrated_payments_employee_id_idk` (`employee_id`),
  KEY `aio_integrated_payments_location_id_idk` (`location_id`),
  KEY `aio_integrated_payments_opened_at_idk` (`opened_at`),
  KEY `aio_integrated_payments_processed_idk` (`processed`),
  KEY `aio_integrated_payments_ticket_idk` (`ticket`),
  KEY `aio_integrated_payments_location_dt_idk` (`location_id`,`created_datetime`),
  KEY `aio_integrated_payments_created_dt_idk` (`created_datetime`),
  KEY `aio_integrated_payments_location_ov_tik_idk` (`location_id`,`omnivore_tickets_id`),
  KEY `aio_integrated_payments_location_ov_tik_stat_idk` (`location_id`,`omnivore_tickets_id`,`status`),
  KEY `aio_integrated_payments_location_tranNo_idk` (`location_id`,`transactionNo`),
  KEY `aio_integrated_payments_acc_tranNo_idk` (`transactionNo`,`MF_accountid`)
) ENGINE=InnoDB AUTO_INCREMENT=1803506 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `aio_integrated_payments_pending` */

DROP TABLE IF EXISTS `aio_integrated_payments_pending`;

CREATE TABLE `aio_integrated_payments_pending` (
  `aio_integrated_payments_pending_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `split` enum('No','Yes') DEFAULT 'No',
  `tender_name` varchar(45) DEFAULT NULL,
  `transactionNo` int(11) DEFAULT NULL,
  `client_order_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `omnivore_tickets_id` varchar(45) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `seat` int(11) DEFAULT '99',
  `name` varchar(45) DEFAULT NULL,
  `subtotal` varchar(45) DEFAULT NULL,
  `tax` varchar(45) DEFAULT NULL,
  `tip` varchar(45) DEFAULT NULL,
  `payment` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `changedue` varchar(45) DEFAULT NULL,
  `id_pay` int(11) DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending','Failure') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `make_a_payment` text,
  `request` text,
  `response` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`aio_integrated_payments_pending_id`),
  KEY `aio_integrated_payments_pending_employee_id_idk` (`employee_id`),
  KEY `aio_integrated_payments_pending_location_id_idk` (`location_id`),
  KEY `aio_integrated_payments_pending_opened_at_idk` (`opened_at`),
  KEY `aio_integrated_payments_pending_processed_idk` (`processed`),
  KEY `aio_integrated_payments_pending_ticket_idk` (`ticket`),
  KEY `aio_integrated_payments_pending_location_dt_idk` (`location_id`,`created_datetime`),
  KEY `aio_integrated_payments_pending_created_dt_idk` (`created_datetime`),
  KEY `aio_integrated_payments_pending_upd_idk` (`location_id`,`employee_id`,`device_id`,`type`,`omnivore_tickets_id`,`tip`,`payment`),
  KEY `aio_integrated_payments_pending_loc_tik_idk` (`location_id`,`omnivore_tickets_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1920745 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `aio_mf_payments` */

DROP TABLE IF EXISTS `aio_mf_payments`;

CREATE TABLE `aio_mf_payments` (
  `aio_mf_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `mf_request` text,
  `mf_response` text,
  `created_datetime` datetime DEFAULT NULL,
  `responded_datetime` datetime DEFAULT NULL,
  `transactionID` varchar(45) DEFAULT NULL,
  `transactionType` varchar(45) DEFAULT NULL,
  `baseAmount` varchar(45) DEFAULT NULL,
  `tipAmount` varchar(45) DEFAULT NULL,
  `cashbackAmount` varchar(45) DEFAULT NULL,
  `resultCode` varchar(45) DEFAULT NULL,
  `hostMessage` varchar(400) DEFAULT NULL,
  `hostResponse` text,
  `cardNumber` varchar(45) DEFAULT NULL,
  `cardIssuer` varchar(45) DEFAULT NULL,
  `cardHolder` varchar(45) DEFAULT NULL,
  `cardDataEntry` varchar(45) DEFAULT NULL,
  `referenceNumber` varchar(45) DEFAULT NULL,
  `authorizationCode` varchar(45) DEFAULT NULL,
  `cc_token` varchar(100) DEFAULT NULL,
  `accountId` varchar(45) DEFAULT NULL,
  `ticketId` varchar(45) DEFAULT NULL,
  `integratorVersion` varchar(45) DEFAULT NULL,
  `FWVersion` varchar(45) DEFAULT NULL,
  `signature_image` varchar(45) DEFAULT NULL,
  `terminalSerialNo` varchar(45) DEFAULT NULL,
  `originalSaleTransId` varchar(45) DEFAULT NULL,
  `responseToCaller` text,
  PRIMARY KEY (`aio_mf_payment_id`),
  UNIQUE KEY `aio_mf_payment_id_UNIQUE` (`aio_mf_payment_id`),
  KEY `aio_mf_payments_location_id_idk` (`location_id`),
  KEY `aio_mf_payments_transactionNo_idk` (`transactionID`),
  KEY `aio_mf_payments_location_create_dt_idk` (`created_datetime`),
  KEY `aio_mf_payments_location_respond_dt_idk` (`responded_datetime`),
  KEY `aio_mf_payments_location_id_dt_idk` (`location_id`,`created_datetime`),
  KEY `aio_mf_payments_location_id_trans_idk` (`location_id`,`transactionID`),
  KEY `aio_mf_payments_ck_ticket_idk` (`location_id`,`transactionType`,`resultCode`,`responded_datetime`,`transactionID`,`ticketId`),
  KEY `aio_mf_payments_ck_ticket_tr_idk` (`location_id`,`transactionType`,`resultCode`,`responded_datetime`,`transactionID`,`ticketId`,`originalSaleTransId`)
) ENGINE=InnoDB AUTO_INCREMENT=2277811 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `aio_nonintegrated_payments` */

DROP TABLE IF EXISTS `aio_nonintegrated_payments`;

CREATE TABLE `aio_nonintegrated_payments` (
  `aio_nonintegrated_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `transactionNo` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `customer` varchar(45) DEFAULT NULL,
  `doctor` varchar(45) DEFAULT NULL,
  `reason` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `tip` decimal(10,2) DEFAULT NULL,
  `payment` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `response_result_code` varchar(45) DEFAULT NULL,
  `response_result_message` varchar(400) DEFAULT NULL,
  `receiptEmvTagMap` varchar(1000) DEFAULT NULL,
  `response_result_txt` varchar(45) DEFAULT NULL,
  `response_auth_code` varchar(45) DEFAULT NULL,
  `response_avs_response` varchar(45) DEFAULT NULL,
  `response_bogus_account_num` varchar(45) DEFAULT NULL,
  `response_card_type` varchar(45) DEFAULT NULL,
  `response_card_holder` varchar(45) DEFAULT NULL,
  `response_cv_response` varchar(45) DEFAULT NULL,
  `response_host_code` varchar(45) DEFAULT NULL,
  `response_host_response` varchar(45) DEFAULT NULL,
  `response_message` varchar(45) DEFAULT NULL,
  `response_ref_num` varchar(45) DEFAULT NULL,
  `response_raw_response` varchar(45) DEFAULT NULL,
  `response_remaining_balance` decimal(10,2) DEFAULT NULL,
  `response_extra_balance` decimal(10,2) DEFAULT NULL,
  `response_requested_amt` decimal(10,2) DEFAULT NULL,
  `response_timestamp` datetime DEFAULT NULL,
  `response_href` varchar(45) DEFAULT NULL,
  `response_entry_mode` varchar(45) DEFAULT NULL,
  `signature_image` varchar(225) DEFAULT NULL,
  `camera_image` varchar(225) DEFAULT NULL,
  `MF_accountid` varchar(45) DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `refunded_transactionNo` varchar(45) DEFAULT NULL,
  `refunded_amount` varchar(45) DEFAULT NULL,
  `refunded_employee_id` varchar(45) DEFAULT NULL,
  `refunded_aio_device_id` varchar(45) DEFAULT NULL,
  `refunded_datetime` datetime DEFAULT NULL,
  `surcharge` decimal(10,2) DEFAULT NULL,
  `surchargeCardType` varchar(45) DEFAULT NULL,
  `custom_fields` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`aio_nonintegrated_payments_id`),
  KEY `aio_nonintegrated_payments_employee_id_idk` (`employee_id`),
  KEY `aio_nonintegrated_payments_location_id_idk` (`location_id`),
  KEY `aio_nonintegrated_payments_opened_at_idk` (`opened_at`),
  KEY `aio_nonintegrated_payments_processed_idk` (`processed`),
  KEY `aio_nonintegrated_payments_ticket_idk` (`ticket`),
  KEY `aio_nonintegrated_payments_location_dt_idk` (`location_id`,`created_datetime`),
  KEY `aio_nonintegrated_payments_created_dt_idk` (`created_datetime`),
  KEY `aio_nonintegrated_payments_acc_tranNo_idk` (`transactionNo`,`MF_accountid`)
) ENGINE=InnoDB AUTO_INCREMENT=6013 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `aio_rapid_payments` */

DROP TABLE IF EXISTS `aio_rapid_payments`;

CREATE TABLE `aio_rapid_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('Retail','Restaurent') DEFAULT 'Retail',
  `request_url` longtext,
  `request` text,
  `response` text,
  `created_by` int(11) DEFAULT NULL,
  `created_on` varchar(55) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=632 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `aio_refunded_transactions` */

DROP TABLE IF EXISTS `aio_refunded_transactions`;

CREATE TABLE `aio_refunded_transactions` (
  `aio_refunded_transactions_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) DEFAULT NULL,
  `aio_integrated_payments_id` int(11) DEFAULT NULL,
  `aio_nonintegrated_payments_id` int(11) DEFAULT NULL,
  `pay_type` enum('ip','nip') DEFAULT 'ip',
  `amount` varchar(45) DEFAULT NULL,
  `transactionNo` varchar(45) DEFAULT NULL,
  `employee_id` varchar(45) DEFAULT NULL,
  `aio_device_id` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`aio_refunded_transactions_id`),
  KEY `aio_refunded_transactions_loc_trans_idk` (`location_id`,`transactionNo`),
  KEY `aio_refunded_transactions_loc_ip_idk` (`location_id`,`aio_integrated_payments_id`),
  KEY `aio_refunded_transactions_loc_nip_idk` (`location_id`,`aio_nonintegrated_payments_id`)
) ENGINE=InnoDB AUTO_INCREMENT=955 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `aio_sales` */

DROP TABLE IF EXISTS `aio_sales`;

CREATE TABLE `aio_sales` (
  `aio_sales_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_satus` varchar(45) NOT NULL,
  `customer` varchar(45) NOT NULL,
  `location_id` int(8) DEFAULT NULL,
  `corporate_id` int(8) DEFAULT NULL,
  `color` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `estimated_current_qtr` int(11) NOT NULL,
  `estimated_next_qrt` int(11) NOT NULL,
  `notes` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`aio_sales_id`),
  UNIQUE KEY `aio_sales_id_UNIQUE` (`aio_sales_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `aio_tsys_payments` */

DROP TABLE IF EXISTS `aio_tsys_payments`;

CREATE TABLE `aio_tsys_payments` (
  `aio_tsys_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `tsys_request` text,
  `tsys_response` text,
  `created_datetime` datetime DEFAULT NULL,
  `responded_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`aio_tsys_payment_id`),
  UNIQUE KEY `aio_tsys_payment_id_UNIQUE` (`aio_tsys_payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `aireus_log` */

DROP TABLE IF EXISTS `aireus_log`;

CREATE TABLE `aireus_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) DEFAULT NULL,
  `api_type` varchar(45) DEFAULT NULL,
  `ticket_id` varchar(45) DEFAULT NULL,
  `payment_id` varchar(45) DEFAULT NULL,
  `url` text,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `elapsed_time` decimal(10,4) DEFAULT NULL,
  `status` enum('Processing','Successful','Failed') DEFAULT NULL,
  `reason` varchar(60) DEFAULT NULL,
  `request` text,
  `response` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `aireus_log_created_dt_idk` (`created_datetime`),
  KEY `aireus_log_location_idk` (`location_id`),
  KEY `aireus_log_loc_dt_idk` (`location_id`,`created_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=15358048 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `amp_devices` */

DROP TABLE IF EXISTS `amp_devices`;

CREATE TABLE `amp_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `terminal_id` varchar(64) NOT NULL,
  `location_id` int(8) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `mac_address` varchar(45) DEFAULT NULL,
  `omnivore_terminal_id` varchar(45) DEFAULT NULL,
  `omnivore_tender_type_id` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_by` varchar(45) NOT NULL,
  `last_on` varchar(45) NOT NULL,
  `last_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idamp_devices_UNIQUE` (`id`),
  UNIQUE KEY `terminal_id_UNIQUE` (`terminal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `announcements` */

DROP TABLE IF EXISTS `announcements`;

CREATE TABLE `announcements` (
  `Employee_master_announcements_id` int(11) NOT NULL AUTO_INCREMENT,
  `Status` enum('Active','Inactive') NOT NULL,
  `Product` enum('TeamPanel','AdminPanel','BusinessPanel','ClientPanel','CorpPanel') NOT NULL,
  `Message` text NOT NULL,
  `Subject` varchar(45) NOT NULL,
  `Start_date` date NOT NULL,
  `End_date` date NOT NULL,
  `Action` enum('None','Referral','Link','Team') NOT NULL,
  `Url_link` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`Employee_master_announcements_id`),
  KEY `announcements_prod_ann_idx` (`Status`,`Product`,`Start_date`,`End_date`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Used to store announcements for the Dashboards on the Panel';

/*Table structure for table `announcements_referrals` */

DROP TABLE IF EXISTS `announcements_referrals`;

CREATE TABLE `announcements_referrals` (
  `announcements_referrals_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('New','Bad Referral','Followed Up','Good Referral') NOT NULL,
  `product` enum('TeamPanel','AdminPanel','BusinessPanel','ClientPanel','CorpPanel') NOT NULL,
  `from_employee_master` int(11) DEFAULT NULL,
  `from_location_id` int(11) DEFAULT NULL,
  `from_location_emp_id` int(11) DEFAULT NULL,
  `from_client_id` int(11) DEFAULT NULL,
  `from_corporate_id` int(11) DEFAULT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `Refer_Location` varchar(64) DEFAULT NULL,
  `Refer_Location_Contact` varchar(64) DEFAULT NULL,
  `Refer_phone` varchar(32) DEFAULT NULL,
  `Refer_email` varchar(64) DEFAULT NULL,
  `refer_locarion_link` int(8) DEFAULT NULL,
  `refer_team_name` varchar(45) DEFAULT NULL,
  `refer_team_email` varchar(45) DEFAULT NULL,
  `refer_team_employee_master_link` int(11) DEFAULT NULL,
  `SoftPoint_notes` varchar(45) DEFAULT NULL,
  `SoftPoint_by` int(11) DEFAULT NULL,
  `SoftPoint_datetime` datetime DEFAULT NULL,
  `Created_on` varchar(45) NOT NULL,
  `Created_by` varchar(45) NOT NULL,
  `Created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`announcements_referrals_id`),
  KEY `from_client_id_fk_idx` (`from_client_id`),
  KEY `from_employee_master_id_fk_idx` (`from_employee_master`),
  KEY `from_location_emp_id_fk_idx` (`from_location_emp_id`),
  KEY `from_corporate_id_fk_idx` (`from_corporate_id`),
  KEY `from_location_id_fk_idx` (`from_location_id`),
  KEY `from_user_id_fk_idx` (`from_user_id`),
  CONSTRAINT `from_client_id_fk` FOREIGN KEY (`from_client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `from_corporate_id_fk` FOREIGN KEY (`from_corporate_id`) REFERENCES `corporate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `from_employee_master_id_fk` FOREIGN KEY (`from_employee_master`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `from_location_emp_id_fk` FOREIGN KEY (`from_location_emp_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `from_location_id_fk` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `from_user_id_fk` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores referral information from the panel accouncements';

/*Table structure for table `api` */

DROP TABLE IF EXISTS `api`;

CREATE TABLE `api` (
  `api_id` int(11) NOT NULL AUTO_INCREMENT,
  `market` enum('All','Restaurant','Hotel','Retail','Medical','Other') NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` text,
  `version` varchar(45) DEFAULT NULL,
  `type` enum('BOTH','GET','POST') DEFAULT NULL,
  `api_to_call` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`api_id`),
  UNIQUE KEY `ap_id_UNIQUE` (`api_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `api_corporate_api_allowed` */

DROP TABLE IF EXISTS `api_corporate_api_allowed`;

CREATE TABLE `api_corporate_api_allowed` (
  `api_corporate_api_allowed_id` int(11) NOT NULL,
  `corporate_id` int(8) NOT NULL,
  `api_id` int(11) NOT NULL,
  PRIMARY KEY (`api_corporate_api_allowed_id`),
  UNIQUE KEY `api_corporate_api_allowed_id_UNIQUE` (`api_corporate_api_allowed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `api_corporate_locations` */

DROP TABLE IF EXISTS `api_corporate_locations`;

CREATE TABLE `api_corporate_locations` (
  `api_corporate_locations_id` int(11) NOT NULL AUTO_INCREMENT,
  `corporate_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `3rd_party` enum('POSPoint','Omnivore','eThor','RegisterPoint') NOT NULL,
  PRIMARY KEY (`api_corporate_locations_id`),
  UNIQUE KEY `api_corporate_locations_id_UNIQUE` (`api_corporate_locations_id`),
  KEY `api_corporate_locations_loc_idx` (`location_id`),
  KEY `api_corporate_locations_corp_idx` (`corporate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `api_fields` */

DROP TABLE IF EXISTS `api_fields`;

CREATE TABLE `api_fields` (
  `api_fields_id` int(11) NOT NULL AUTO_INCREMENT,
  `api_id` int(11) NOT NULL,
  `name_of_field` varchar(45) NOT NULL,
  `type_of_field` longtext NOT NULL,
  `description_of_field` text,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `required` enum('Yes','No') DEFAULT 'No',
  `required_input` enum('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`api_fields_id`),
  UNIQUE KEY `api_fields_id_UNIQUE` (`api_fields_id`)
) ENGINE=InnoDB AUTO_INCREMENT=879 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `api_log` */

DROP TABLE IF EXISTS `api_log`;

CREATE TABLE `api_log` (
  `api_log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` enum('Started','Finished','Failed','Not Completed') NOT NULL,
  `api_key` varchar(128) NOT NULL,
  `location_id` int(8) DEFAULT NULL,
  `terminal_id` varchar(64) DEFAULT NULL,
  `api` int(11) NOT NULL,
  `finished_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`api_log_id`),
  UNIQUE KEY `api_log_id_UNIQUE` (`api_log_id`),
  KEY `api_log_created_dt_idk` (`created_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=4043917 DEFAULT CHARSET=latin1 COMMENT='Hold the logs of the api called';

/*Table structure for table `app_api_log` */

DROP TABLE IF EXISTS `app_api_log`;

CREATE TABLE `app_api_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) DEFAULT NULL,
  `status` enum('Processing','Successful','Failed') DEFAULT NULL,
  `app` enum('SoftPoint','PrepPoint','ControlPoint') DEFAULT NULL,
  `reason` text,
  `device_id` varchar(45) DEFAULT NULL,
  `version` varchar(45) DEFAULT NULL,
  `api_type` varchar(255) DEFAULT NULL,
  `call_type` enum('API','APP') DEFAULT 'API',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `elapsed_time` decimal(10,1) DEFAULT NULL,
  `num_tickets` varchar(45) DEFAULT NULL,
  `ticket_id` varchar(45) DEFAULT NULL,
  `num_items` varchar(45) DEFAULT NULL,
  `num_payments` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_api_log_api_type_idk` (`api_type`),
  KEY `app_api_log_created_datetime_idk` (`created_datetime`),
  KEY `app_api_log_location_id_idk` (`location_id`),
  KEY `app_api_log_elapsed_time_idk` (`elapsed_time`),
  KEY `app_api_log_status_idk` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3374000 DEFAULT CHARSET=latin1;

/*Table structure for table `cities` */

DROP TABLE IF EXISTS `cities`;

CREATE TABLE `cities` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `city` varchar(64) NOT NULL,
  `state` int(4) NOT NULL,
  `country` int(4) NOT NULL,
  `zipcode` char(10) NOT NULL,
  `longitude` varchar(12) NOT NULL,
  `latitude` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_fk_idx` (`country`),
  KEY `state_fk_idx` (`state`),
  KEY `cities_city_idx` (`city`),
  CONSTRAINT `country_fk` FOREIGN KEY (`country`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `state_fk` FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2382 DEFAULT CHARSET=utf8 COMMENT='Used to store global cities';

/*Table structure for table `client_addresses` */

DROP TABLE IF EXISTS `client_addresses`;

CREATE TABLE `client_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `address_type` enum('Home','Business','Other') NOT NULL,
  `primary_address` enum('Yes','No') NOT NULL,
  `country` int(4) DEFAULT NULL,
  `addresses` varchar(64) DEFAULT NULL,
  `addresses2` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) NOT NULL,
  `neighborhood` varchar(45) DEFAULT NULL,
  `longitude` varchar(12) DEFAULT NULL,
  `latitude` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key_client_address_country_idx` (`country`),
  KEY `key_client_address_state_idx` (`state`),
  KEY `client_fk_idx` (`client_id`),
  CONSTRAINT `client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `key_client_address_country` FOREIGN KEY (`country`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `key_client_address_state` FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=372 DEFAULT CHARSET=latin1 COMMENT='Used to store addresses that a client has entered';

/*Table structure for table `client_addresses_temp` */

DROP TABLE IF EXISTS `client_addresses_temp`;

CREATE TABLE `client_addresses_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `address_type` enum('Home','Business','Other') NOT NULL,
  `primary_address` enum('Yes','No') NOT NULL,
  `country` int(4) NOT NULL,
  `addresses` varchar(64) DEFAULT NULL,
  `addresses2` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COMMENT='Used to temporarily store addresses. Needed for HotelPoint';

/*Table structure for table `client_allergent` */

DROP TABLE IF EXISTS `client_allergent`;

CREATE TABLE `client_allergent` (
  `client_allergent_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `location_id` int(8) NOT NULL,
  `location_menu_article_modifiers_default_type` varchar(45) NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`client_allergent_id`),
  UNIQUE KEY `client_allergent_id_UNIQUE` (`client_allergent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=latin1;

/*Table structure for table `client_attributes` */

DROP TABLE IF EXISTS `client_attributes`;

CREATE TABLE `client_attributes` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `client_id` int(8) NOT NULL,
  `status` enum('A','I') NOT NULL DEFAULT 'A',
  `type` enum('D','T','N') NOT NULL,
  `attribute` varchar(18) NOT NULL,
  `share` enum('N','Y') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `client_attributes_fk` (`client_id`),
  CONSTRAINT `client_attributes_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Used to store user-input attributes for Neult';

/*Table structure for table `client_chat` */

DROP TABLE IF EXISTS `client_chat`;

CREATE TABLE `client_chat` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `client_to` int(11) DEFAULT NULL,
  `client_from` int(11) DEFAULT NULL,
  `text` text,
  `status` enum('U','R') DEFAULT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_to_fk_idx` (`client_to`),
  KEY `client_from_fk_idx` (`client_from`),
  CONSTRAINT `client_from_fk` FOREIGN KEY (`client_from`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_to_fk` FOREIGN KEY (`client_to`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Used to store client to client messages';

/*Table structure for table `client_delivery` */

DROP TABLE IF EXISTS `client_delivery`;

CREATE TABLE `client_delivery` (
  `delivery_Id` int(8) NOT NULL AUTO_INCREMENT,
  `Location_id` int(8) DEFAULT NULL,
  `client_id` int(8) DEFAULT NULL,
  `client_order_id` bigint(12) DEFAULT NULL,
  `status` enum('Ordered','Ready','Assign','Inroute','Delivered','Incident') DEFAULT NULL,
  `ordered_datetime` datetime DEFAULT NULL,
  `ordered_by_employee_id` int(12) DEFAULT NULL,
  `client_requesttime` datetime DEFAULT NULL,
  `describe_order` text,
  `client_addresses_id` int(11) DEFAULT NULL,
  `delivery_address` longtext,
  `delivery_address2` longtext,
  `delivery_city` varchar(64) DEFAULT NULL,
  `delivery_state` int(8) DEFAULT NULL,
  `delivery_country` int(8) DEFAULT NULL,
  `delivery_zipcode` varchar(16) DEFAULT NULL,
  `delivery_phone` varchar(32) DEFAULT NULL,
  `delivery_neighborhood` varchar(64) DEFAULT NULL,
  `special_instructions` varchar(45) DEFAULT NULL,
  `ready_datetime` datetime DEFAULT NULL,
  `Assign_accepted_by` int(8) DEFAULT NULL,
  `Assign_accepted_datetime` datetime DEFAULT NULL,
  `Inroute_datetime` datetime DEFAULT NULL,
  `delivered_datetime` datetime DEFAULT NULL,
  `delivery_comments` text,
  `Incident_datetime` datetime DEFAULT NULL,
  `incident_comments` text,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`delivery_Id`),
  KEY `assign_empmast_id_fk_idx` (`Assign_accepted_by`),
  KEY `delivery_client_fk_idx` (`client_id`),
  KEY `delivery_client_order_fk_idx` (`client_order_id`),
  KEY `delivery_location_fk_idx` (`Location_id`),
  KEY `delivery_ordered_emp_fk_idx` (`ordered_by_employee_id`),
  CONSTRAINT `assign_empmast_id_fk` FOREIGN KEY (`Assign_accepted_by`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `delivery_client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `delivery_location_fk` FOREIGN KEY (`Location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `delivery_ordered_emp_fk` FOREIGN KEY (`ordered_by_employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1 COMMENT='Used to store orders that have been added to Dispatch';

/*Table structure for table `client_delivery_employee` */

DROP TABLE IF EXISTS `client_delivery_employee`;

CREATE TABLE `client_delivery_employee` (
  `delivery_emp_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_id` int(8) NOT NULL,
  `Location_id` int(8) NOT NULL,
  `client_id` int(8) NOT NULL,
  `empmaster_id` int(8) NOT NULL,
  `assign_datetime` datetime NOT NULL,
  `assign_expired_datetime` datetime NOT NULL,
  `assign_accepted` enum('Yes','No') DEFAULT NULL,
  `assign_accepted_datetime` datetime DEFAULT NULL,
  `Assign_declined_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`delivery_emp_id`),
  KEY `delivery_emp_client_fk_idx` (`client_id`),
  KEY `delivery_emp_delivery_fk_idx` (`delivery_id`),
  KEY `delivery_emp_empmaster_fk_idx` (`empmaster_id`),
  KEY `delivery_emp_location_fk_idx` (`Location_id`),
  CONSTRAINT `delivery_emp_client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `delivery_emp_delivery_fk` FOREIGN KEY (`delivery_id`) REFERENCES `client_delivery` (`delivery_Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `delivery_emp_empmaster_fk` FOREIGN KEY (`empmaster_id`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `delivery_emp_location_fk` FOREIGN KEY (`Location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COMMENT='Used to store employees assigned to a delivery order';

/*Table structure for table `client_emailaddress` */

DROP TABLE IF EXISTS `client_emailaddress`;

CREATE TABLE `client_emailaddress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `primary_email` enum('Yes','No') NOT NULL,
  `emailtype` enum('Personal','Home','Work','Other') NOT NULL,
  `email` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_client_emails_clients_idx` (`client_id`),
  CONSTRAINT `key_client_emails_clients` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=78369 DEFAULT CHARSET=latin1 COMMENT='Used to store client-entered email addresses';

/*Table structure for table `client_emailaddress_temp` */

DROP TABLE IF EXISTS `client_emailaddress_temp`;

CREATE TABLE `client_emailaddress_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `primary_email` enum('Yes','No') NOT NULL,
  `emailtype` enum('Personal','Home','Work','Other') NOT NULL,
  `email` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1 COMMENT='Temporarily store email addresses. Used in HotelPoint';

/*Table structure for table `client_emails` */

DROP TABLE IF EXISTS `client_emails`;

CREATE TABLE `client_emails` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `location_email_formats_id` int(8) NOT NULL,
  `client_id` int(8) NOT NULL,
  `client_email_sent_address` varchar(64) NOT NULL,
  `sent_status` varchar(45) NOT NULL,
  `sent_datetime` datetime NOT NULL,
  `email_id` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_emails_fk` (`client_id`),
  KEY `client_emails_loc_fk` (`location_id`),
  KEY `client_email_id_fk_idx` (`email_id`),
  CONSTRAINT `client_email_id_fk` FOREIGN KEY (`email_id`) REFERENCES `client_emailaddress` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_emails_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `client_emails_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Stores a record of emails between clients and locations';

/*Table structure for table `client_events` */

DROP TABLE IF EXISTS `client_events`;

CREATE TABLE `client_events` (
  `client_event_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(8) NOT NULL,
  `Status` enum('A','I') DEFAULT 'A',
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `event_name` varchar(64) NOT NULL,
  `event_shortname` varchar(12) DEFAULT NULL,
  `event_logo` varchar(128) DEFAULT NULL,
  `event_photo` varchar(128) DEFAULT NULL,
  `location_id` int(8) NOT NULL,
  `Venue` varchar(64) DEFAULT NULL,
  `location_description` text,
  `directions` text,
  `description` varchar(256) DEFAULT NULL,
  `internal_description` varchar(256) DEFAULT NULL,
  `event_website` varchar(128) DEFAULT NULL,
  `event_facebook` varchar(128) DEFAULT NULL,
  `private` enum('y','n') DEFAULT 'n',
  `tickets` enum('y','n') DEFAULT 'n',
  `get_ticket_link` varchar(128) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`client_event_id`),
  KEY `client_event_client_fk_idx` (`client_id`),
  KEY `client_event_location_fk_idx` (`location_id`),
  CONSTRAINT `client_event_client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_event_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Used to store client-d events at a location';

/*Table structure for table `client_expensetab_accounts` */

DROP TABLE IF EXISTS `client_expensetab_accounts`;

CREATE TABLE `client_expensetab_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `status` enum('active','suspended','cancelled','pending') NOT NULL DEFAULT 'pending',
  `state` int(4) NOT NULL,
  `country` int(4) DEFAULT NULL,
  `address` varchar(64) NOT NULL,
  `address2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `zip` varchar(16) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `secondary_guarantee` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `activated_datetime` datetime NOT NULL,
  `created_datetime` datetime NOT NULL,
  `identifier_hash` varchar(100) DEFAULT NULL,
  `hash_expire` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_et_client_fk_idx` (`client_id`),
  KEY `client_et_country_fk_idx` (`country`),
  KEY `client_et_state_fk_idx` (`state`),
  CONSTRAINT `client_et_client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_et_country_fk` FOREIGN KEY (`country`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=100105 DEFAULT CHARSET=latin1 COMMENT='Used to store the clients expensetab account information';

/*Table structure for table `client_favorite_locations` */

DROP TABLE IF EXISTS `client_favorite_locations`;

CREATE TABLE `client_favorite_locations` (
  `client_fav_loc` int(11) NOT NULL AUTO_INCREMENT,
  `clients_id` int(11) DEFAULT NULL,
  `locations_id` int(11) DEFAULT NULL,
  `created_on` varchar(250) NOT NULL,
  `created_by` varchar(250) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`client_fav_loc`),
  UNIQUE KEY `client_fav_loc_UNIQUE` (`client_fav_loc`),
  KEY `client_fav_loc_client_fk_idx` (`clients_id`),
  KEY `client_fav_loc_fk_idx` (`locations_id`),
  CONSTRAINT `client_fav_loc_client_fk` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_fav_loc_fk` FOREIGN KEY (`locations_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1 COMMENT='Stores locations that clients marked as favorites in Neult';

/*Table structure for table `client_log` */

DROP TABLE IF EXISTS `client_log`;

CREATE TABLE `client_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(8) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `type` enum('signin','signout','signinfailure') NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `created_on` varchar(50) NOT NULL,
  `created_by` varchar(20) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_log_fk` (`client_id`),
  CONSTRAINT `client_log_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 COMMENT='Stores sign in and sign out records for clients';

/*Table structure for table `client_membership` */

DROP TABLE IF EXISTS `client_membership`;

CREATE TABLE `client_membership` (
  `client_membership_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `membership_type` enum('OOA','Other') DEFAULT NULL,
  `membership_name` varchar(64) NOT NULL,
  `membership_id_number` varchar(16) NOT NULL,
  `status` enum('Active','Inactive','Suspended','Cancelled') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `Renewable` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `individual_child` enum('Yes','No') DEFAULT NULL,
  `individual_adult` enum('Yes','No') DEFAULT NULL,
  `2_people_dual` enum('Yes','No') DEFAULT NULL,
  `6_people_family` enum('Yes','No') DEFAULT NULL,
  `9_people_family` enum('Yes','No') DEFAULT NULL,
  `client_id2` int(11) DEFAULT NULL,
  `client_id3` int(11) DEFAULT NULL,
  `adult` int(1) DEFAULT NULL,
  `senior` int(1) DEFAULT NULL,
  `child_5_to_12` int(1) DEFAULT NULL,
  `child_4_to_5` int(1) DEFAULT NULL,
  `sold_by` varchar(45) DEFAULT NULL,
  `newsletter` enum('Yes','No') DEFAULT 'No',
  `guest_option` enum('Yes','No') DEFAULT 'No',
  `gift_option` enum('Yes','No') DEFAULT NULL,
  `gift_buyer_client_id` int(11) DEFAULT NULL,
  `payment_type` varchar(45) DEFAULT NULL,
  `default_location_id` int(8) DEFAULT NULL,
  `default_location_menu_articles_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `client_sales_id_names` tinytext,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`client_membership_id`),
  UNIQUE KEY `client_membership_id_UNIQUE` (`client_membership_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1 COMMENT='Stores the various memberships the client belongs too';

/*Table structure for table `client_messages` */

DROP TABLE IF EXISTS `client_messages`;

CREATE TABLE `client_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(8) NOT NULL,
  `datetime` datetime NOT NULL,
  `message` longtext NOT NULL,
  `image` text,
  `to_client_id` int(8) NOT NULL,
  `read` enum('N','Y') DEFAULT NULL,
  `read_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_messages_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores messages sent between clients';

/*Table structure for table `client_order_clients` */

DROP TABLE IF EXISTS `client_order_clients`;

CREATE TABLE `client_order_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `datetime_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_order_clients_order_fk` (`order_id`),
  KEY `client_order_clients_client_fk` (`client_id`),
  CONSTRAINT `client_order_clients_client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `client_order_clients_order_fk` FOREIGN KEY (`order_id`) REFERENCES `client_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=640537 DEFAULT CHARSET=latin1 COMMENT='This table links multiple clients to a client order.';

/*Table structure for table `client_order_items` */

DROP TABLE IF EXISTS `client_order_items`;

CREATE TABLE `client_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `location_id` int(8) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(8) NOT NULL,
  `menu_group` int(8) NOT NULL,
  `menu_item_id` int(8) NOT NULL,
  `drink_sold` varchar(1) DEFAULT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `quantity` int(8) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `seat` varchar(60) NOT NULL DEFAULT '99',
  `itemsorder` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `print_time` time DEFAULT NULL,
  `print_status` enum('inque','printed','displayed','completed') DEFAULT 'inque',
  `print_status_direct` enum('inque','printed','displayed','completed') DEFAULT 'inque',
  `printer_id` varchar(60) NOT NULL DEFAULT 'none',
  `time_completed` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fire_order_it` enum('First','Second','Last') NOT NULL DEFAULT 'First',
  `message_chef` varchar(250) DEFAULT NULL,
  `seat_payment` varchar(45) NOT NULL DEFAULT '99',
  `void` enum('no','yes') NOT NULL DEFAULT 'no',
  `void_emp` int(11) DEFAULT NULL,
  `tax1_name` varchar(45) DEFAULT NULL,
  `tax1_type` varchar(45) DEFAULT NULL,
  `tax1_percentage` decimal(10,4) DEFAULT NULL,
  `tax1_amount` decimal(14,5) DEFAULT NULL,
  `tax2_name` varchar(45) DEFAULT NULL,
  `tax2_type` varchar(45) DEFAULT NULL,
  `tax2_percentage` decimal(10,4) DEFAULT NULL,
  `tax2_amount` decimal(14,5) DEFAULT NULL,
  `is_taxexempt` enum('N','Y') NOT NULL DEFAULT 'N',
  `weight_quantity` decimal(10,3) NOT NULL DEFAULT '0.000',
  `has_modifier` enum('N','Y') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `client_order_items_menu_fk` (`menu_id`),
  KEY `client_order_items_menu_group_fk` (`menu_group`),
  KEY `client_order_items_item_fk` (`menu_item_id`),
  KEY `client_order_items_fk` (`order_id`),
  KEY `client_order_items_client_fk_idx` (`client_id`),
  KEY `client_order_items_loc_fk_idx` (`location_id`),
  KEY `client_order_items_emp_idx` (`emp_id`),
  KEY `client_order_items_void_emp_idx` (`void_emp`),
  KEY `client_order_items_quantity_idx` (`quantity`),
  KEY `client_order_items_datetime` (`datetime`),
  KEY `client_order_items_group` (`location_id`,`print_status`,`void`),
  KEY `client_order_items_itemsorder` (`itemsorder`),
  KEY `client_order_items_tax_idx` (`tax1_amount`,`tax2_amount`,`order_id`),
  CONSTRAINT `client_order_items_client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_order_items_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_order_items_fk` FOREIGN KEY (`order_id`) REFERENCES `client_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `client_order_items_item_fk` FOREIGN KEY (`menu_item_id`) REFERENCES `location_menu_articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `client_order_items_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_order_items_menu_fk` FOREIGN KEY (`menu_id`) REFERENCES `location_menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `client_order_items_menu_group_fk` FOREIGN KEY (`menu_group`) REFERENCES `location_menu_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `client_order_items_void_emp_fk` FOREIGN KEY (`void_emp`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=860714 DEFAULT CHARSET=latin1 COMMENT='Stores the items added to an Order in POS';

/*Table structure for table `client_order_items_modifier` */

DROP TABLE IF EXISTS `client_order_items_modifier`;

CREATE TABLE `client_order_items_modifier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `location_id` int(8) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(8) NOT NULL,
  `menu_item_id` int(8) NOT NULL,
  `itemorder` int(11) NOT NULL COMMENT 'This is what links the modifier to client_order_items. It is the id for each item within an order.',
  `modifier` varchar(32) NOT NULL,
  `special_instruction` longtext NOT NULL,
  `quantity` int(8) NOT NULL,
  `modifier_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `seat` varchar(45) NOT NULL DEFAULT '99',
  `void` enum('yes','no') NOT NULL DEFAULT 'no',
  `void_emp` int(11) DEFAULT NULL,
  `section` varchar(45) NOT NULL DEFAULT '',
  `total` decimal(10,2) NOT NULL,
  `print_status` enum('inque','printed','displayed','completed') DEFAULT NULL,
  `time_completed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_modifier_order_fk` (`order_id`),
  KEY `order_items_modifier_menu_fk` (`menu_id`),
  KEY `order_items_modifier_menu_item_fk` (`menu_item_id`),
  KEY `order_items_modifier_loc_fk_idx` (`location_id`),
  KEY `order_items_modifier_client_fk_idx` (`client_id`),
  KEY `order_items_modifier_void_emp_idx` (`void_emp`),
  KEY `order_items_modifier_modifier` (`modifier`),
  KEY `order_items_modifier_group` (`itemorder`,`menu_item_id`,`seat`,`order_id`),
  CONSTRAINT `order_items_modifier_client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `order_items_modifier_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `order_items_modifier_menu_fk` FOREIGN KEY (`menu_id`) REFERENCES `location_menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_items_modifier_menu_item_fk` FOREIGN KEY (`menu_item_id`) REFERENCES `location_menu_articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_items_modifier_order_fk` FOREIGN KEY (`order_id`) REFERENCES `client_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_items_modifier_void_emp` FOREIGN KEY (`void_emp`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=329422 DEFAULT CHARSET=latin1 COMMENT='This table stores the modifiers added to an Item in an Order';

/*Table structure for table `client_order_payments` */

DROP TABLE IF EXISTS `client_order_payments`;

CREATE TABLE `client_order_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `location_id` int(8) NOT NULL,
  `order_id` int(11) NOT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `manager_approval` int(11) DEFAULT NULL,
  `payment_type` int(8) NOT NULL,
  `payment_code` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `received` float(10,2) DEFAULT NULL,
  `changedue` decimal(14,2) NOT NULL,
  `processor` enum('PayPal','First Data','Authorize.Net','XCharge','Braintree','Global','SaferPay','First Data Payeezy') DEFAULT NULL,
  `processor_transaction_id` mediumtext,
  `location_cc_batches_id` int(11) DEFAULT NULL COMMENT 'Link to the location CC batch table',
  `cc_name` varchar(64) NOT NULL,
  `cc_number` varchar(64) NOT NULL,
  `cc_exp` varchar(8) NOT NULL,
  `ccsecurity` varchar(30) NOT NULL,
  `cc_autho` varchar(50) NOT NULL,
  `cc_autho_date` varchar(20) NOT NULL,
  `cc_autho_time` varchar(20) NOT NULL,
  `first_name_cc` varchar(50) DEFAULT NULL,
  `last_name_cc` varchar(50) DEFAULT NULL,
  `street_address_cc` varchar(200) DEFAULT NULL,
  `city_cc` varchar(50) DEFAULT NULL,
  `state_cc` varchar(50) DEFAULT NULL,
  `country_cc` varchar(50) DEFAULT NULL,
  `zip_cc` varchar(50) DEFAULT NULL,
  `currency_code_cc` varchar(50) DEFAULT NULL,
  `autho_amount` decimal(10,2) NOT NULL,
  `autho_date` datetime NOT NULL,
  `autho_emp` varchar(40) NOT NULL,
  `autho_amount_gratuity` decimal(14,2) NOT NULL,
  `autho_manual` varchar(45) DEFAULT NULL,
  `cc_debit_pin` int(8) NOT NULL,
  `cc_card_entry` enum('Swiped','Typed','EMV','VT','Unknown') DEFAULT NULL,
  `paypal_email` varchar(100) DEFAULT NULL,
  `gift_certificate` varchar(40) DEFAULT NULL,
  `expensetab_paid` enum('yes','no') NOT NULL DEFAULT 'no',
  `Et_pending_req_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `hotel_location_id` int(8) DEFAULT NULL,
  `hotel_account_id` int(10) DEFAULT NULL,
  `room_number` varchar(45) NOT NULL DEFAULT '',
  `guest_name` varchar(155) NOT NULL DEFAULT '',
  `company_id` int(11) DEFAULT NULL,
  `reason` varchar(64) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `reduce` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `printed` enum('yes','no') NOT NULL DEFAULT 'no',
  `refund` enum('yes','no') NOT NULL DEFAULT 'no',
  `id_item` varchar(45) NOT NULL,
  `id_pay` int(11) DEFAULT NULL,
  `is_autocharge` enum('yes','no') DEFAULT 'no',
  `clover_order_id` varchar(45) DEFAULT NULL,
  `clover_payment_id` varchar(45) DEFAULT NULL,
  `clover_refund_id` varchar(45) DEFAULT NULL,
  `transactionNo` varchar(45) DEFAULT NULL,
  `clover_mid` varchar(45) DEFAULT NULL,
  `clover_ref` varchar(45) DEFAULT NULL,
  `clover_cvm` varchar(45) DEFAULT NULL,
  `split` varchar(45) DEFAULT NULL,
  `seat` varchar(10) NOT NULL DEFAULT '99',
  `seat_payment_details` varchar(100) DEFAULT NULL,
  `seat_payment_tax_details` varchar(100) DEFAULT NULL,
  `pax_integrated_payments_id` int(11) DEFAULT NULL,
  `clover_integrated_payments_id` int(11) DEFAULT NULL,
  `aio_integrated_payments_id` int(11) DEFAULT NULL,
  `poynt_integrated_payments_id` int(11) DEFAULT NULL,
  `pay_datetime` datetime NOT NULL,
  `created_on` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_order_payments_order_fk` (`order_id`),
  KEY `client_order_payments_client_fk_idx` (`client_id`),
  KEY `client_order_payments_loc_fk_idx` (`location_id`),
  KEY `client_order_payments_emp_idx` (`emp_id`),
  KEY `client_order_payments_type_idx` (`payment_type`),
  KEY `client_order_payment_clover_order_id_idk` (`clover_order_id`),
  KEY `client_order_payment_clover_payment_id_idk` (`clover_payment_id`),
  KEY `client_order_payments_idpay` (`id_pay`),
  CONSTRAINT `client_order_payments_client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_order_payments_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_order_payments_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_order_payments_order_fk` FOREIGN KEY (`order_id`) REFERENCES `client_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `client_order_payments_type` FOREIGN KEY (`payment_type`) REFERENCES `location_payments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=341237 DEFAULT CHARSET=latin1 COMMENT='This table stores the payments a client made on an order';

/*Table structure for table `client_orders` */

DROP TABLE IF EXISTS `client_orders`;

CREATE TABLE `client_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `location_id` int(8) NOT NULL,
  `check_number` int(11) NOT NULL,
  `daily_account_number` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `order_status` enum('completed','started','ordered','closed','printed','cancelled','reopened') NOT NULL,
  `visual_status` varchar(255) DEFAULT NULL,
  `source_of_business` enum('Dine In','Walk In','Phone','Website','Mobile','Fax','External') DEFAULT NULL,
  `order_date` date NOT NULL DEFAULT '0000-00-00',
  `order_time` time NOT NULL,
  `togo` enum('yes','no') NOT NULL,
  `togo_time` time NOT NULL,
  `delivery` enum('yes','no') NOT NULL,
  `delivery_time` time DEFAULT NULL,
  `client_addresses_id` int(11) DEFAULT NULL,
  `delivery_address` longtext,
  `delivery_address2` longtext,
  `delivery_city` varchar(64) DEFAULT NULL,
  `delivery_state` int(8) DEFAULT NULL,
  `delivery_country` int(8) DEFAULT NULL,
  `delivery_zipcode` varchar(16) DEFAULT NULL,
  `delivery_phone` varchar(32) DEFAULT NULL,
  `order_subtotal` decimal(10,2) NOT NULL,
  `order_tax` decimal(10,2) NOT NULL,
  `tip` decimal(10,2) DEFAULT NULL,
  `order_delivery_surcharge` varchar(32) NOT NULL,
  `order_payments` decimal(10,2) DEFAULT NULL,
  `order_adjustments` decimal(10,2) DEFAULT NULL,
  `order_total` decimal(10,2) NOT NULL,
  `order_payment_type` int(8) NOT NULL,
  `order_togo_structure` varchar(32) NOT NULL,
  `location_table` int(11) DEFAULT NULL,
  `covers` int(11) NOT NULL DEFAULT '1',
  `table_flag` varchar(255) DEFAULT NULL,
  `togoval` varchar(255) DEFAULT NULL,
  `dev_val` varchar(255) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `citystate` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `minibar` enum('Yes','No') NOT NULL DEFAULT 'No',
  `mode_chair` enum('yes','no') NOT NULL DEFAULT 'yes',
  `assigned_server` varchar(30) DEFAULT NULL,
  `type_print` varchar(30) NOT NULL DEFAULT 'Table',
  `equally_covers` int(11) DEFAULT NULL,
  `fast` enum('yes','no') NOT NULL DEFAULT 'no',
  `expensetab_manual` enum('yes','no') NOT NULL DEFAULT 'no',
  `multiple_client` enum('yes','no') NOT NULL DEFAULT 'no' COMMENT 'If multiple_client = yes, then join client_order_clients to get all of the clients for this order.',
  `receipt_image` text COMMENT 'Image of receipt uploaded on expensetab business "sent tab to client" popup',
  `ready_time` time DEFAULT NULL,
  `currency_id` int(8) DEFAULT NULL,
  `bar_x` float DEFAULT NULL,
  `bar_y` float DEFAULT NULL,
  `bar_type` enum('Man','Woman','Couple','Group','Table') DEFAULT NULL,
  `review_id` int(11) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `closed_time` time DEFAULT NULL,
  `completed_time` datetime DEFAULT NULL,
  `details` enum('yes','no') NOT NULL DEFAULT 'no',
  `clover_order_id` varchar(45) DEFAULT NULL,
  `clover_taxtype_id` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_orders_fk` (`client_id`),
  KEY `client_orders_loc_fk` (`location_id`),
  KEY `client_orders_emp_fk_idx` (`employee_id`),
  KEY `client_orders_clover_order_id_idk` (`clover_order_id`),
  KEY `idx_combo` (`location_id`,`order_date`),
  KEY `idx_loc_tbl` (`location_table`),
  KEY `created_datetime` (`created_datetime`),
  CONSTRAINT `client_orders_emp_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_orders_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `client_orders_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=584710 DEFAULT CHARSET=latin1 COMMENT='This table stores all the orders in POS';

/*Table structure for table `client_payments` */

DROP TABLE IF EXISTS `client_payments`;

CREATE TABLE `client_payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(8) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive','pending') NOT NULL DEFAULT 'active',
  `payment_type` enum('Credit Card','PayPal','Debit Card','ExpenseTAB Direct','Bank Account','ExpenseTAB Personal') NOT NULL,
  `cc_type` varchar(30) DEFAULT NULL,
  `cc_cardholder_name` varchar(32) DEFAULT NULL,
  `cc_number` varchar(64) DEFAULT NULL,
  `cc_last4` char(4) DEFAULT NULL,
  `cc_cvs` varchar(6) DEFAULT NULL,
  `cc_expiration` varchar(30) DEFAULT NULL,
  `cc_expiration_month` smallint(6) DEFAULT NULL,
  `cc_expiration_year` smallint(6) DEFAULT NULL,
  `cc_billing_address` varchar(64) DEFAULT NULL,
  `cc_country` varchar(30) DEFAULT NULL,
  `cc_city` varchar(32) DEFAULT NULL,
  `cc_state` varchar(32) DEFAULT NULL,
  `cc_zip` varchar(16) DEFAULT NULL,
  `cc_image` varchar(250) NOT NULL,
  `cc_token` varchar(100) DEFAULT NULL,
  `cc_phone` varchar(32) DEFAULT NULL,
  `paypal_email` varchar(32) DEFAULT NULL,
  `expensetab_use` enum('yes','no') NOT NULL DEFAULT 'no',
  `expensetab_account_id` int(11) DEFAULT NULL,
  `expensetab_primary` enum('yes','no') NOT NULL DEFAULT 'no',
  `bank_name` varchar(45) DEFAULT NULL,
  `bank_account_type` enum('checking','savings','business_checking') DEFAULT NULL,
  `bank_routing_number` varchar(45) DEFAULT NULL,
  `bank_account` varchar(45) DEFAULT NULL,
  `bank_phone` varchar(45) DEFAULT NULL,
  `location_directbill_client_id` int(11) DEFAULT NULL COMMENT 'links to location_expensetab_directbill_clients.id',
  `is_default` enum('yes','no') NOT NULL DEFAULT 'no',
  `currency_id` int(8) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `client_payments_fk` (`client_id`),
  KEY `client_payments_directbill_fk_idx` (`location_directbill_client_id`),
  CONSTRAINT `client_payments_directbill_fk` FOREIGN KEY (`location_directbill_client_id`) REFERENCES `location_expensetab_directbill_clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_payments_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16305 DEFAULT CHARSET=latin1 COMMENT='This table stores the payment sources for a client';

/*Table structure for table `client_pings` */

DROP TABLE IF EXISTS `client_pings`;

CREATE TABLE `client_pings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(8) NOT NULL,
  `longitude` varchar(12) NOT NULL,
  `latitude` varchar(12) NOT NULL,
  `device_id` varchar(255) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `longitude` (`longitude`,`latitude`),
  KEY `client_pings_fk` (`client_id`),
  CONSTRAINT `client_pings_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='This table stores the clients physical location for Neult';

/*Table structure for table `client_reservations` */

DROP TABLE IF EXISTS `client_reservations`;

CREATE TABLE `client_reservations` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `location_id` int(8) NOT NULL,
  `num_of_guest` int(8) NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `special_request` varchar(255) NOT NULL,
  `arrived` enum('yes','no') DEFAULT NULL,
  `arrival_time` time NOT NULL,
  `post_comment` longtext NOT NULL,
  `status` enum('R','N','C','A','W','WA') DEFAULT NULL,
  `slot` int(11) NOT NULL,
  `cancel_email` datetime NOT NULL,
  `cancelled_on` varchar(32) DEFAULT NULL,
  `cancelled_datetime` datetime DEFAULT NULL,
  `review_id` int(11) NOT NULL,
  `dispute_noshow` varchar(255) NOT NULL,
  `arrived_email` datetime NOT NULL,
  `reminder_email` datetime DEFAULT NULL,
  `noshow_email` datetime NOT NULL,
  `noshow_client_status` enum('Dispute','Accepted','Declined') DEFAULT NULL,
  `noshow_client_reason` varchar(255) DEFAULT NULL,
  `noshow_client_status_email` datetime DEFAULT NULL,
  `noshow_client_location_employee` int(11) DEFAULT NULL,
  `wait_time` time DEFAULT NULL,
  `table` int(11) DEFAULT NULL,
  `server` int(11) DEFAULT NULL,
  `status_order` enum('OPEN','CLOSED') DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `client_order_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_on` text NOT NULL,
  `created_by` text NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_reservations_fk` (`client_id`),
  KEY `client_reservations_loc_fk` (`location_id`),
  KEY `client_reservations_table_idx` (`table`),
  KEY `client_reservations_server_idx` (`server`),
  KEY `client_reservations_stat_order_idx` (`status_order`),
  KEY `client_reservations_loc_order_stat_idx` (`location_id`,`client_order_id`,`status_order`),
  CONSTRAINT `client_reservations_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `client_reservations_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1839 DEFAULT CHARSET=latin1 COMMENT='This table stores reservations made by a client';

/*Table structure for table `client_reservations_audit` */

DROP TABLE IF EXISTS `client_reservations_audit`;

CREATE TABLE `client_reservations_audit` (
  `client_reservations_audit_id` int(15) NOT NULL AUTO_INCREMENT,
  `id` int(15) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `location_id` int(8) DEFAULT NULL,
  `num_of_guest` int(8) DEFAULT NULL,
  `reservation_date` date DEFAULT NULL,
  `reservation_time` time DEFAULT NULL,
  `special_request` varchar(255) DEFAULT NULL,
  `arrived` enum('yes','no') DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `post_comment` longtext,
  `status` enum('R','N','C','A','W','WA') DEFAULT NULL,
  `slot` int(11) DEFAULT NULL,
  `cancel_email` datetime DEFAULT NULL,
  `cancelled_on` varchar(32) DEFAULT NULL,
  `cancelled_datetime` datetime DEFAULT NULL,
  `review_id` int(11) DEFAULT NULL,
  `dispute_noshow` varchar(255) DEFAULT NULL,
  `arrived_email` datetime DEFAULT NULL,
  `reminder_email` datetime DEFAULT NULL,
  `noshow_email` datetime DEFAULT NULL,
  `noshow_client_status` enum('Dispute','Accepted','Declined') DEFAULT NULL,
  `noshow_client_reason` varchar(255) DEFAULT NULL,
  `noshow_client_status_email` datetime DEFAULT NULL,
  `noshow_client_location_employee` int(11) DEFAULT NULL,
  `wait_time` time DEFAULT NULL,
  `table` int(11) DEFAULT NULL,
  `server` int(11) DEFAULT NULL,
  `status_order` enum('OPEN','CLOSED') DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `client_order_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_on` text,
  `created_by` text,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`client_reservations_audit_id`),
  KEY `from_locations_id_fk_idx` (`location_id`),
  KEY `client_reservations_audit_id_idx` (`id`),
  KEY `client_reservations_audit_last_datetime_idx` (`last_datetime`),
  CONSTRAINT `client_reservations_audit_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4085 DEFAULT CHARSET=utf8 COMMENT='This table holds all the changes made to a reservation';

/*Table structure for table `client_review_clicks` */

DROP TABLE IF EXISTS `client_review_clicks`;

CREATE TABLE `client_review_clicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `review_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `viewed_by_client_id` int(11) NOT NULL,
  `created_on` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_review_clicks_fk` (`review_id`),
  KEY `client_review_clients_viewed_fk_idx` (`viewed_by_client_id`),
  CONSTRAINT `client_review_clicks_fk` FOREIGN KEY (`review_id`) REFERENCES `client_reviews` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `client_review_clients_viewed_fk` FOREIGN KEY (`viewed_by_client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Keeps a record of every time a review is viewed';

/*Table structure for table `client_reviews` */

DROP TABLE IF EXISTS `client_reviews`;

CREATE TABLE `client_reviews` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `client_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `client_reservation_id` int(11) DEFAULT NULL,
  `client_sales` int(11) DEFAULT NULL,
  `client_orders` int(11) DEFAULT NULL,
  `location_hotelacct` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `title` tinytext NOT NULL,
  `description` longtext NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `rating` int(8) NOT NULL,
  `rating_quality` int(4) DEFAULT NULL,
  `rating_service` int(4) DEFAULT NULL,
  `rating_price` int(4) DEFAULT NULL,
  `employeemaster_id` int(11) DEFAULT NULL,
  `employeemaster_description` longtext,
  `employeemaster_timeliness` varchar(45) DEFAULT NULL,
  `employeemaster_customer_service` varchar(45) DEFAULT NULL,
  `employeemaster_professionalism` varchar(45) DEFAULT NULL,
  `employeemaster_politeness` varchar(45) DEFAULT NULL,
  `employeemaster_order_correct` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_reviews_loc_fk` (`location_id`),
  KEY `client_reviews_client_fk_idx` (`client_id`),
  CONSTRAINT `client_reviews_client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_reviews_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Hold the reviews of locations made by clients';

/*Table structure for table `client_sales` */

DROP TABLE IF EXISTS `client_sales`;

CREATE TABLE `client_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) NOT NULL COMMENT 'sales_id is not a global unique id. It is a local receipt id only unique for the associated location.',
  `location_id` int(8) NOT NULL,
  `client_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `status` enum('open','closed','cancelled') NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `subtotal` varchar(11) NOT NULL,
  `tax_total` varchar(11) NOT NULL,
  `discount_total` varchar(11) NOT NULL,
  `adjustment_amt` varchar(11) NOT NULL,
  `payments_amt` varchar(11) NOT NULL,
  `balance` varchar(11) NOT NULL,
  `printed` enum('yes','no') NOT NULL DEFAULT 'no',
  `check_starttime` datetime NOT NULL,
  `check_endtime` datetime NOT NULL,
  `rand_id` int(11) DEFAULT NULL,
  `currency_id` int(8) DEFAULT NULL,
  `review_id` int(11) NOT NULL,
  `clover_order_id` varchar(45) DEFAULT NULL,
  `invoice_notes` text,
  `demographic` varchar(64) DEFAULT NULL,
  `created_on` varchar(50) NOT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_sales_loc_fk` (`location_id`),
  KEY `client_sales_emp_fk` (`employee_id`),
  KEY `client_sales_currency_fk_idx` (`currency_id`),
  KEY `client_sales_sales_id` (`sales_id`),
  KEY `created_datetime` (`created_datetime`),
  CONSTRAINT `client_sales_currency_fk` FOREIGN KEY (`currency_id`) REFERENCES `global_currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_sales_emp_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_sales_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7980 DEFAULT CHARSET=latin1 COMMENT='This table stores all the retail orders';

/*Table structure for table `client_sales_check_temp` */

DROP TABLE IF EXISTS `client_sales_check_temp`;

CREATE TABLE `client_sales_check_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rand_id` int(10) NOT NULL,
  `location_id` int(8) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `client_ids` varchar(100) NOT NULL,
  `client_names` varchar(255) DEFAULT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `client_sales_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_sales_check_temp_location_fk_idx` (`location_id`),
  CONSTRAINT `client_sales_check_temp_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8827 DEFAULT CHARSET=latin1 COMMENT='Temporarily stores check information before it is added';

/*Table structure for table `client_sales_client` */

DROP TABLE IF EXISTS `client_sales_client`;

CREATE TABLE `client_sales_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) DEFAULT NULL,
  `location_id` int(8) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `created_on` varchar(50) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_sales_client_location_fk_idx` (`location_id`),
  KEY `client_sales_client_client_idx` (`client_id`),
  KEY `client_sales_client_emp_idx` (`employee_id`),
  KEY `client_sales_client_sales_idx` (`sales_id`),
  CONSTRAINT `client_sales_client_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_sales_client_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_sales_client_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3913 DEFAULT CHARSET=latin1 COMMENT='This table stores the clients associated with retail orders';

/*Table structure for table `client_sales_items` */

DROP TABLE IF EXISTS `client_sales_items`;

CREATE TABLE `client_sales_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `sales_id` int(11) NOT NULL COMMENT 'sales_id = client_sales.sales_id',
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `qty` float(10,2) NOT NULL,
  `menu_item_id` int(15) NOT NULL,
  `price` varchar(12) NOT NULL,
  `subtotal` varchar(12) NOT NULL,
  `tax` varchar(12) NOT NULL,
  `additional_tax` varchar(12) DEFAULT NULL,
  `inclusive_tax` varchar(12) DEFAULT NULL,
  `tax1` int(10) DEFAULT NULL,
  `tax2` int(10) DEFAULT NULL,
  `discount_amt` float(10,2) NOT NULL,
  `discount` varchar(12) NOT NULL,
  `amount` varchar(12) NOT NULL,
  `is_reverse` enum('Y','N') DEFAULT NULL,
  `is_taxexempt` enum('Y','N') DEFAULT NULL,
  `is_extra` enum('Y','N') DEFAULT 'N',
  `ext_item_id` int(11) DEFAULT NULL,
  `promotion` enum('No','Yes') DEFAULT 'No',
  `promotion_req_qty` int(11) DEFAULT NULL,
  `promotion_continued` enum('No','Yes') DEFAULT NULL,
  `promotion_price` varchar(12) NOT NULL,
  `pay_id` int(11) DEFAULT NULL,
  `ticketDate` varchar(15) DEFAULT NULL,
  `ticketTime` varchar(15) DEFAULT NULL,
  `is_included` enum('Y','N') DEFAULT 'N',
  `technician_id` int(11) DEFAULT NULL,
  `created_on` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_sales_items_loc_fk_idx` (`location_id`),
  KEY `client_sales_items_menu_item_fk_idx` (`menu_item_id`),
  KEY `client_sales_items_emp_fk` (`employee_id`),
  KEY `client_sales_items_client_sales_idx` (`location_id`,`sales_id`),
  CONSTRAINT `client_sales_items_emp_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_sales_items_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_sales_items_menu_item_fk` FOREIGN KEY (`menu_item_id`) REFERENCES `location_menu_articles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17366 DEFAULT CHARSET=latin1 COMMENT='This table stores the items associated with a retail order';

/*Table structure for table `client_sales_items_scan` */

DROP TABLE IF EXISTS `client_sales_items_scan`;

CREATE TABLE `client_sales_items_scan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `temp_unique_id` int(10) DEFAULT NULL,
  `item_id` int(10) DEFAULT NULL,
  `item_name` varchar(200) DEFAULT NULL,
  `qty` int(10) DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COMMENT='Temporarily stores items to be added to a check via scanner';

/*Table structure for table `client_sales_items_temp` */

DROP TABLE IF EXISTS `client_sales_items_temp`;

CREATE TABLE `client_sales_items_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `qty` float(10,2) NOT NULL,
  `menu_item_id` varchar(45) NOT NULL,
  `price` varchar(12) NOT NULL,
  `subtotal` varchar(12) NOT NULL,
  `tax` varchar(12) NOT NULL,
  `additional_tax` varchar(12) DEFAULT NULL,
  `inclusive_tax` varchar(12) DEFAULT NULL,
  `tax1` int(10) DEFAULT NULL,
  `tax2` int(10) DEFAULT NULL,
  `discount_amt` float(10,2) NOT NULL,
  `discount` varchar(12) NOT NULL,
  `item_discount` varchar(12) DEFAULT NULL,
  `item_discount_tax` varchar(12) DEFAULT NULL,
  `amount` varchar(12) NOT NULL,
  `created_on` varchar(50) NOT NULL,
  `is_reverse` enum('Y','N') DEFAULT NULL,
  `is_extra` enum('Y','N') DEFAULT 'N',
  `ext_item_id` int(11) DEFAULT NULL,
  `is_taxexempt` enum('Y','N') DEFAULT NULL,
  `promotion` enum('No','Yes') DEFAULT 'No',
  `promotion_req_qty` int(11) DEFAULT NULL,
  `promotion_continued` enum('No','Yes') DEFAULT NULL,
  `promotion_price` varchar(12) DEFAULT NULL,
  `ticketDate` varchar(15) DEFAULT NULL,
  `ticketTime` varchar(15) DEFAULT NULL,
  `is_included` enum('Y','N') DEFAULT 'N',
  `technician_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6615 DEFAULT CHARSET=latin1 COMMENT='Temporarily stores items to be added to a check';

/*Table structure for table `client_sales_payments` */

DROP TABLE IF EXISTS `client_sales_payments`;

CREATE TABLE `client_sales_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `location_id` int(8) NOT NULL COMMENT 'Location ID from locations table',
  `sales_id` int(11) NOT NULL COMMENT 'sales_id = client_sales.id',
  `employee_id` int(11) NOT NULL,
  `payment_type` int(8) NOT NULL,
  `payment_code` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `adjustment_amt` float(10,2) NOT NULL,
  `payment_amt` float(10,2) NOT NULL,
  `image` text NOT NULL,
  `processor` enum('PayPal','First Data','Authorize.Net','XCharge','Braintree','Global','SaferPay') DEFAULT NULL,
  `processor_transaction_id` mediumtext NOT NULL,
  `location_cc_batches_id` int(11) DEFAULT NULL COMMENT 'Link to the location cc batches table',
  `cc_name` varchar(45) NOT NULL,
  `cc_firstname` varchar(45) NOT NULL,
  `cc_lastname` varchar(45) NOT NULL,
  `cc_number` varchar(64) NOT NULL,
  `cc_exp` varchar(12) NOT NULL,
  `cc_cvv` int(11) NOT NULL,
  `cc_blob` text NOT NULL,
  `notes` varchar(255) NOT NULL,
  `ccsecurity` varchar(30) NOT NULL,
  `authorization` varchar(50) NOT NULL DEFAULT '',
  `cc_autho` varchar(50) NOT NULL,
  `cc_autho_date` date NOT NULL,
  `cc_autho_time` time NOT NULL,
  `autho_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `autho_date` datetime NOT NULL,
  `autho_emp` varchar(40) NOT NULL,
  `gratuity` float(10,2) NOT NULL DEFAULT '0.00',
  `received` float(10,2) NOT NULL DEFAULT '0.00',
  `changedue` decimal(14,2) DEFAULT NULL,
  `client` varchar(100) NOT NULL DEFAULT '',
  `debit_totalamount` float(10,2) NOT NULL DEFAULT '0.00',
  `sale_type` varchar(15) NOT NULL,
  `reverse_id` int(10) NOT NULL,
  `id_pay` int(11) NOT NULL,
  `cc_card_entry` enum('Swiped','Typed','EMV','VT','Unknown') NOT NULL,
  `id_item` int(10) DEFAULT NULL,
  `expensetab_client_id` int(10) DEFAULT NULL,
  `hotel_location_id` int(10) DEFAULT NULL,
  `hotel_account_id` int(10) DEFAULT NULL,
  `hotel_client_id` int(10) DEFAULT NULL,
  `expensetab_paid` enum('Yes','No') DEFAULT 'No',
  `is_discount` enum('Yes','No') DEFAULT 'No',
  `hotel_client_name` varchar(50) DEFAULT NULL,
  `hotel_room_number` varchar(5) DEFAULT NULL,
  `is_reverse` enum('Yes','No') DEFAULT 'No',
  `gift_certificate` int(11) DEFAULT NULL,
  `non_integrated_gc` varchar(50) DEFAULT NULL,
  `printed` enum('yes','no') NOT NULL DEFAULT 'no',
  `company_id` int(10) DEFAULT NULL,
  `item_discount_qty` int(10) DEFAULT NULL,
  `item_discount_type` varchar(25) DEFAULT NULL,
  `item_discount_amt` float(10,2) DEFAULT NULL,
  `discount_tax` enum('Yes','No') DEFAULT 'Yes',
  `tax` float(10,2) NOT NULL,
  `is_cc_success` enum('No','Yes') DEFAULT 'Yes',
  `purchased_gc_id` int(11) DEFAULT NULL,
  `payment_signature` varchar(255) DEFAULT NULL,
  `payment_pin` varchar(8) DEFAULT NULL,
  `technician_id` int(11) DEFAULT NULL,
  `clover_order_id` varchar(45) DEFAULT NULL,
  `clover_payment_id` varchar(45) DEFAULT NULL,
  `clover_refund_id` varchar(45) DEFAULT NULL,
  `transactionNo` varchar(45) DEFAULT NULL,
  `clover_mid` varchar(45) DEFAULT NULL,
  `clover_ref` varchar(45) DEFAULT NULL,
  `clover_cvm` varchar(45) DEFAULT NULL,
  `check_number` varchar(25) DEFAULT NULL,
  `pax_integrated_payments_id` int(11) DEFAULT NULL,
  `clover_integrated_payments_id` int(11) DEFAULT NULL,
  `aio_integrated_payments_id` int(11) DEFAULT NULL,
  `poynt_integrated_payments_id` int(11) DEFAULT NULL,
  `created_on` varchar(50) NOT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_sales_payments_fk` (`sales_id`),
  KEY `client_sales_payments_location_fk_idx` (`location_id`),
  KEY `client_sales_payments_ptype_idx` (`payment_type`),
  CONSTRAINT `client_sales_payments_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8005 DEFAULT CHARSET=latin1 COMMENT='Stores all the payment records made for a retail order';

/*Table structure for table `client_sales_services` */

DROP TABLE IF EXISTS `client_sales_services`;

CREATE TABLE `client_sales_services` (
  `client_sales_services_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `client_id` int(11) NOT NULL,
  `services_id` varchar(12) NOT NULL,
  `status` enum('Received','Scheduled','Started','Waiting For Parts','In Progress','Completed','Picked Up') NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `estimated_date` date NOT NULL,
  `description_of_issue` text,
  `work_required` text,
  `work_performed` text,
  `estimated_cost` decimal(14,2) DEFAULT NULL,
  `service_fee` decimal(14,2) DEFAULT NULL,
  `final_cost` decimal(14,2) DEFAULT NULL,
  `technician_employee_id` int(11) DEFAULT NULL,
  `image_before` varchar(255) DEFAULT NULL,
  `image_inprogress` varchar(255) DEFAULT NULL,
  `image_completed` varchar(255) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`client_sales_services_id`),
  KEY `client_sales_services_location_fk_idx` (`location_id`),
  KEY `client_sales_services_client_sales_fk_idx` (`location_id`,`client_sales_id`),
  CONSTRAINT `client_sales_services_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `clients` */

DROP TABLE IF EXISTS `clients`;

CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('A','N','I','S') NOT NULL COMMENT 'A - ACTIVE\nN - Not Confirmed\nI - INACTIVE\nS - Suspended',
  `email` varchar(64) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `name_title` enum('Mr.','Mrs.','Ms.','Dr.','Prof') DEFAULT NULL,
  `name_first` varchar(32) DEFAULT NULL,
  `name_last` varchar(32) DEFAULT NULL,
  `name_suffix` enum('I','II','III','Jr.','Sr.') DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `address2` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) DEFAULT NULL,
  `country` int(4) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `longitude` varchar(12) DEFAULT NULL,
  `latitude` varchar(12) DEFAULT NULL,
  `neighborhood` varchar(45) DEFAULT NULL,
  `image` longtext,
  `sex` enum('M','F') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `primarydinning` varchar(50) DEFAULT NULL,
  `primaryschool` varchar(50) DEFAULT NULL,
  `Signedup` varchar(50) DEFAULT 'N',
  `facebook` enum('N','Y') NOT NULL DEFAULT 'N',
  `facebook_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `facebook_id` varchar(32) DEFAULT NULL,
  `twitter` varchar(45) DEFAULT NULL,
  `twitter_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `twitter_image` varchar(255) DEFAULT NULL,
  `linkedin_id` varchar(45) DEFAULT NULL,
  `linkedin_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `linkedin_image` varchar(255) DEFAULT NULL,
  `google_id` varchar(45) DEFAULT NULL,
  `google_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `google_image` varchar(255) DEFAULT NULL,
  `ping` enum('N','Y') NOT NULL DEFAULT 'N',
  `access_edu2bsales` enum('yes','no') NOT NULL DEFAULT 'no',
  `email_notifications` enum('N','Y') NOT NULL DEFAULT 'N',
  `push_notificiations` enum('N','Y') NOT NULL DEFAULT 'N',
  `specialIns` varchar(45) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `smoker` enum('Yes','No') DEFAULT NULL,
  `handicap` enum('Yes','No') DEFAULT NULL,
  `id_type` enum('Drivers License','License','Passport','Identity','Other') DEFAULT NULL,
  `id_number` varchar(40) DEFAULT NULL,
  `id_country` int(11) DEFAULT NULL,
  `country_birth` int(11) DEFAULT NULL,
  `document_type` enum('Country Permit','EU Permit','Other') DEFAULT NULL,
  `document_issue_date` date DEFAULT NULL,
  `document_country` int(11) DEFAULT NULL,
  `client_expensetab_account_id` int(11) DEFAULT NULL COMMENT 'expensetab id',
  `profile_image` varchar(255) DEFAULT NULL,
  `national_favorite_team` varchar(255) DEFAULT NULL,
  `share_location` enum('Yes','No') DEFAULT NULL,
  `job_title` varchar(45) DEFAULT NULL,
  `job_company` varchar(45) DEFAULT NULL,
  `currency_id` int(8) DEFAULT NULL,
  `StylistFN` enum('Yes','No') DEFAULT NULL,
  `StylistFN_Description` text,
  `StylistFN_Style` text,
  `clover_customer_id` varchar(45) DEFAULT NULL,
  `mealplan` enum('Yes','No') DEFAULT 'No',
  `mealplan_reoccuring` enum('Daily','Weekly','Monthly','Never') DEFAULT NULL,
  `mealplan_amount` decimal(12,2) DEFAULT NULL,
  `roomnumber` varchar(8) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_on` text NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Status` (`status`),
  KEY `client_currency_fk_idx` (`currency_id`),
  KEY `client_state_fk_idx` (`state`),
  KEY `client_country_fk` (`country`),
  KEY `client_name_idk` (`name`),
  KEY `client_email_idk` (`email`),
  KEY `client_phone_idk` (`phone`),
  KEY `clients_name_combo_idk` (`name`,`name_first`,`name_last`),
  KEY `clients_client_combo_idk` (`name`,`name_first`,`name_last`,`email`),
  KEY `clients_contact_idx` (`name`,`name_first`,`name_last`,`phone`),
  CONSTRAINT `client_country_fk` FOREIGN KEY (`country`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_currency_fk` FOREIGN KEY (`currency_id`) REFERENCES `global_currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `client_state_fk` FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=212452 DEFAULT CHARSET=latin1 COMMENT='Stores client information';

/*Table structure for table `clients_audit` */

DROP TABLE IF EXISTS `clients_audit`;

CREATE TABLE `clients_audit` (
  `client_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `status` enum('A','N','I','S') DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `name_title` enum('Mr.','Mrs.','Ms.','Dr.','Prof') DEFAULT NULL,
  `name_first` varchar(32) DEFAULT NULL,
  `name_last` varchar(32) DEFAULT NULL,
  `name_suffix` enum('I','II','III','Jr.','Sr.') DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `address2` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) DEFAULT NULL,
  `country` int(4) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `longitude` varchar(12) DEFAULT NULL,
  `latitude` varchar(12) DEFAULT NULL,
  `neighborhood` varchar(45) DEFAULT NULL,
  `image` longtext,
  `sex` enum('M','F') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `primarydinning` varchar(50) DEFAULT NULL,
  `primaryschool` varchar(50) DEFAULT NULL,
  `Signedup` varchar(50) DEFAULT NULL,
  `facebook` enum('N','Y') DEFAULT NULL,
  `facebook_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `facebook_id` varchar(32) DEFAULT NULL,
  `twitter` varchar(45) DEFAULT NULL,
  `twitter_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `twitter_image` varchar(255) DEFAULT NULL,
  `linkedin_id` varchar(45) DEFAULT NULL,
  `linkedin_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `linkedin_image` varchar(255) DEFAULT NULL,
  `google_id` varchar(45) DEFAULT NULL,
  `google_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `google_image` varchar(255) DEFAULT NULL,
  `ping` enum('N','Y') DEFAULT NULL,
  `access_edu2bsales` enum('yes','no') DEFAULT NULL,
  `email_notifications` enum('N','Y') DEFAULT NULL,
  `push_notificiations` enum('N','Y') DEFAULT NULL,
  `specialIns` varchar(45) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `smoker` enum('Yes','No') DEFAULT NULL,
  `handicap` enum('Yes','No') DEFAULT NULL,
  `id_type` enum('Drivers License','License','Passport','Identity','Other') DEFAULT NULL,
  `id_number` varchar(40) DEFAULT NULL,
  `id_country` int(11) DEFAULT NULL,
  `country_birth` int(11) DEFAULT NULL,
  `document_type` enum('Country Permit','EU Permit','Other') DEFAULT NULL,
  `document_issue_date` date DEFAULT NULL,
  `document_country` int(11) DEFAULT NULL,
  `client_expensetab_account_id` int(11) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `national_favorite_team` varchar(255) DEFAULT NULL,
  `share_location` enum('Yes','No') DEFAULT NULL,
  `job_title` varchar(45) DEFAULT NULL,
  `job_company` varchar(45) DEFAULT NULL,
  `currency_id` int(8) DEFAULT NULL,
  `StylistFN` enum('Yes','No') DEFAULT NULL,
  `StylistFN_Description` text,
  `StylistFN_Style` text,
  `clover_customer_id` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_on` text,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`client_audit_id`),
  KEY `clients_audit_client_id_idx` (`id`),
  KEY `clients_audit_last_datetime` (`last_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=39854 DEFAULT CHARSET=latin1 COMMENT='Used to store records of all changes made to client accounts';

/*Table structure for table `clients_phones` */

DROP TABLE IF EXISTS `clients_phones`;

CREATE TABLE `clients_phones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `phone_type` enum('Home','Business','Mobile','Fax','Other') NOT NULL,
  `primary_phone` enum('Yes','No') NOT NULL,
  `country_code` varchar(20) NOT NULL,
  `number` varchar(32) NOT NULL,
  `device_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key_clients_id_idx` (`client_id`),
  CONSTRAINT `key_clients_id` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5001 DEFAULT CHARSET=latin1 COMMENT='Stores the phone numbers of a client';

/*Table structure for table `clients_phones_temp` */

DROP TABLE IF EXISTS `clients_phones_temp`;

CREATE TABLE `clients_phones_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `phone_type` enum('Home','Business','Mobile','Fax','Other') NOT NULL,
  `primary_phone` enum('Yes','No') NOT NULL,
  `country_code` varchar(4) NOT NULL,
  `number` varchar(32) NOT NULL,
  `device_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key_clients_id_idx` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1 COMMENT='Temporarily stores the phone number of a client in registerp';

/*Table structure for table `clover_api_calls_log` */

DROP TABLE IF EXISTS `clover_api_calls_log`;

CREATE TABLE `clover_api_calls_log` (
  `clover_api_calls_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) DEFAULT NULL,
  `merchant_id` varchar(45) DEFAULT NULL,
  `status` enum('Processing','Successful','Failed') DEFAULT NULL,
  `reason` varchar(64) DEFAULT NULL,
  `device_id` varchar(45) DEFAULT NULL,
  `version` varchar(45) DEFAULT NULL,
  `api_type` varchar(255) DEFAULT NULL,
  `call_type` enum('API','APP') DEFAULT 'API',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `elapsed_time` decimal(10,1) DEFAULT NULL,
  `num_tickets` varchar(45) DEFAULT NULL,
  `ticket_id` varchar(45) DEFAULT NULL,
  `num_items` varchar(45) DEFAULT NULL,
  `num_payments` varchar(45) DEFAULT NULL,
  `due` varchar(45) DEFAULT NULL,
  `request_url` text,
  `omnivore_time_taken` decimal(10,1) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`clover_api_calls_log_id`),
  KEY `clover_api_calls_log_merchant_id_idk` (`merchant_id`),
  KEY `clover_api_calls_log_api_type_idk` (`api_type`),
  KEY `clover_api_calls_log_created_datetime_idk` (`created_datetime`),
  KEY `clover_api_calls_log_location_id_idk` (`location_id`),
  KEY `clover_api_calls_log_elapsed_time_idk` (`elapsed_time`),
  KEY `clover_api_calls_log_status_idk` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=153265281 DEFAULT CHARSET=latin1;

/*Table structure for table `clover_apps` */

DROP TABLE IF EXISTS `clover_apps`;

CREATE TABLE `clover_apps` (
  `clover_apps_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(45) NOT NULL,
  `app_id` varchar(45) NOT NULL,
  `app_name` varchar(45) NOT NULL,
  `app_secret` varchar(45) NOT NULL,
  `integrated` varchar(45) DEFAULT NULL,
  `package_name` varchar(45) NOT NULL,
  `app_market_link` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`clover_apps_id`),
  KEY `app_id_idk` (`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `clover_devices` */

DROP TABLE IF EXISTS `clover_devices`;

CREATE TABLE `clover_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Live','Sandbox','Inactive') DEFAULT NULL,
  `omnivore_status` enum('Offline','Online','Not Used','Degraded') DEFAULT 'Not Used',
  `manufacturer` enum('Clover','PAX','Exadigm','Anywhere Commerce','Ingenico','Verifone','Idtech','Other','PAXPoint','Poynt','PAX MF') DEFAULT 'Clover',
  `3rd_party` enum('SoftPoint','Omnivore','eThor','Aireus','Focus','Posera','PowerCard') DEFAULT 'Omnivore',
  `location_id` varchar(45) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `version` varchar(45) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `omnivore_version` varchar(4) DEFAULT '1.0',
  `ov_key` enum('DEVELOPMENT','PRODUCTION','DEMO') DEFAULT 'PRODUCTION',
  `ov_webhook_mode` enum('Disabled','ServerPing','WebSocket','DirectPort') DEFAULT 'Disabled',
  `webhook_timer` varchar(45) DEFAULT '30',
  `ov_offline_mode` enum('Yes','No') DEFAULT 'No',
  `revenue_center_id` varchar(45) DEFAULT NULL,
  `ordering_rev_center_default` int(10) DEFAULT NULL,
  `ordering_order_type_default` int(10) DEFAULT NULL,
  `ordering_table_default` int(10) DEFAULT NULL,
  `ordering_name_max` int(10) DEFAULT '14',
  `ordering_ov_name_max` int(10) DEFAULT '14',
  `ordering_show_prices` enum('Yes','No') DEFAULT 'Yes',
  `ordering_show_new_ticket_popup` enum('Yes','No') DEFAULT 'Yes',
  `ordering_input_employee_name_tab` enum('Yes','No') DEFAULT 'No',
  `ordering_menu_category_display` enum('Full','Submenu','Categories') NOT NULL DEFAULT 'Full',
  `ordering_input_guest_count_required` enum('Yes','No') DEFAULT 'No',
  `merchant_id` varchar(45) DEFAULT NULL,
  `clover_token` varchar(45) DEFAULT NULL,
  `quickpoint_clover_employee` varchar(45) DEFAULT NULL,
  `qp_send_log_level` int(2) NOT NULL DEFAULT '5',
  `device_id` varchar(45) DEFAULT NULL,
  `ethor_storeid` varchar(45) DEFAULT NULL,
  `poynt_business_id` varchar(45) DEFAULT NULL,
  `owner` enum('SoftPoint','PayVida') DEFAULT 'SoftPoint',
  `Physically_Located` varchar(45) DEFAULT NULL,
  `last_clover_payment_modified` varchar(45) DEFAULT NULL,
  `last_call_to_ov` varchar(45) DEFAULT NULL,
  `call_ov_within` varchar(45) DEFAULT '30',
  `call_ov_opened_at_within` varchar(45) DEFAULT NULL,
  `timeout` varchar(45) DEFAULT '180000',
  `refresh` varchar(45) DEFAULT '30000',
  `disconnect_timer` varchar(45) DEFAULT '15',
  `pay_elapse_time` int(10) NOT NULL DEFAULT '5000',
  `webhook` enum('Yes','No') DEFAULT 'Yes',
  `apps` enum('No','Integrated','Non Integrated') DEFAULT 'No',
  `apps_type` enum('DataPoint For Market','DataPoint For POS','DataPoint For RP','DataPoint For HP','RestaurantPay','HotelPay','RetailPay','MedPay','StayPoint') DEFAULT NULL,
  `app_ordering` enum('Yes','No') DEFAULT 'No',
  `cash` enum('Yes','No') DEFAULT 'No',
  `split` enum('Yes','No') DEFAULT 'Yes',
  `quick_service` enum('Yes','No') DEFAULT 'No',
  `subtotal_tip` enum('Yes','No') DEFAULT 'No',
  `one_tap_tip` enum('Yes','No') DEFAULT 'No',
  `tips` enum('Before','After','Both','No') DEFAULT 'Before',
  `tip_selections` varchar(45) DEFAULT '15,18,20,30',
  `tip_additional` enum('Yes','No','Custom') DEFAULT 'No',
  `tip_additional_custom_selections` varchar(45) DEFAULT '18,20',
  `hotel_room_charge` enum('Yes','No') DEFAULT 'No',
  `hotel_room_charge_settle` enum('Settle','Dont Settle') DEFAULT 'Dont Settle',
  `split_number` varchar(255) DEFAULT '7',
  `print` enum('Yes','No') DEFAULT 'Yes',
  `print_cash_receipt` enum('Yes','No') DEFAULT 'No',
  `print_address_receipt` enum('Yes','No') DEFAULT 'Yes',
  `print_emv_tags_receipt` enum('Yes','No') DEFAULT 'No',
  `auto_print` enum('Yes','No') DEFAULT 'No',
  `receipt_name_text` varchar(64) DEFAULT NULL,
  `footer_text` longtext,
  `footer_text_line_1` varchar(200) DEFAULT NULL,
  `footer_text_line_2` varchar(200) DEFAULT NULL,
  `footer_text_line_3` varchar(200) DEFAULT NULL,
  `receipt_logo` longtext,
  `logo` longtext,
  `logo_updated` varchar(45) DEFAULT NULL,
  `alert` enum('Past Due','None') DEFAULT 'None',
  `run_missing_payment_cleanup_cron` enum('Yes','No') DEFAULT 'No',
  `aio_emp_id` varchar(45) DEFAULT NULL,
  `aio_password` varchar(45) DEFAULT NULL,
  `show_thank_you` enum('Before','After','No') DEFAULT 'Before',
  `show_thank_you_require_pin` enum('Yes','No') DEFAULT 'No',
  `thank_you_sound` enum('Yes','No') DEFAULT 'No',
  `thank_you_duration` int(11) unsigned DEFAULT '0',
  `thank_you_failure_sound` enum('Yes','No') DEFAULT 'No',
  `thank_you_failure_duration` int(11) unsigned DEFAULT '0',
  `thank_you_failure_retry` enum('Yes','No') DEFAULT 'Yes',
  `thank_you_failure_retry_limit` int(11) unsigned DEFAULT '0',
  `show_zero_balance` enum('Yes','No') DEFAULT 'Yes',
  `show_tickets_past_24_hours` enum('Yes','No') DEFAULT 'Yes',
  `show_receipt_items` enum('Yes','No') DEFAULT 'Yes',
  `show_receipt_nonconfigured_items` enum('Yes','No') DEFAULT 'Yes',
  `show_receipt_zero_balance_items` enum('Yes','No') DEFAULT 'Yes',
  `show_receipt_pos_payments` enum('Yes','No') DEFAULT 'No',
  `show_receipt_signature` enum('Yes','No') DEFAULT 'Yes',
  `show_receipt_printer` enum('Yes','No') DEFAULT 'No',
  `show_table_splits` enum('Yes','No') DEFAULT 'Yes',
  `load_check_details` enum('Yes','No') DEFAULT 'Yes',
  `print_cc_autho_receipt_items` enum('Yes','No') DEFAULT 'Yes',
  `auto_print_tip_adjustment` enum('Yes','No') DEFAULT 'Yes',
  `show_tip_suggestions` enum('Yes','No') DEFAULT 'Yes',
  `quick_ticket` enum('Yes','No') DEFAULT 'No',
  `one_tap_receipt` enum('Yes','No') DEFAULT 'No',
  `receipt_option_print` enum('Yes','No') DEFAULT 'Yes',
  `receipt_option_email` enum('Yes','No') DEFAULT 'Yes',
  `receipt_option_text` enum('Yes','No') DEFAULT 'Yes',
  `show_receipt_page_duration` int(11) unsigned NOT NULL DEFAULT '0',
  `email_sales_app_alert` enum('Yes','No') DEFAULT 'Yes',
  `email_send_error_report` enum('Yes','No') DEFAULT 'Yes' COMMENT 'Should Error Emails be sent to SoftPoint',
  `fabric_reporting` enum('Yes','No') DEFAULT 'Yes',
  `qsr_check_details` enum('Yes','No') DEFAULT 'Yes',
  `print_tax_on_receipt` enum('No','Yes') DEFAULT 'No',
  `receipts_show_ov_payments` enum('No','Yes') DEFAULT 'No',
  `email_display_receipt` enum('Yes','No') DEFAULT 'No',
  `tip_off_grand_total` enum('No','Yes') DEFAULT 'No',
  `tip_require_manager_pin` enum('Yes','No','Amount','Percentage') DEFAULT 'No',
  `tip_require_manager_pin_custom_selection` decimal(14,2) DEFAULT '0.00',
  `show_adjust_surcharge` enum('Yes','No') DEFAULT 'No',
  `surcharge` enum('Yes','No') NOT NULL DEFAULT 'No',
  `surcharge_item_id` varchar(45) DEFAULT NULL,
  `surcharge_cc_pct` decimal(10,2) NOT NULL DEFAULT '0.00',
  `surcharge_debit_pct` decimal(10,2) NOT NULL DEFAULT '0.00',
  `surcharge_percentage` decimal(10,2) NOT NULL DEFAULT '0.00',
  `print_surcharge_on_receipt` enum('Yes','No') DEFAULT 'No',
  `ordering_quantity_popup` enum('Yes','No') DEFAULT 'No',
  `send_zero_dollar_tickets` enum('Yes','No') DEFAULT 'Yes',
  `card_not_present_revenue_center_ids` varchar(45) DEFAULT '',
  `printer_width` varchar(45) DEFAULT '250',
  `process2for1` enum('Yes','No') DEFAULT 'No',
  `allow_split_cash` enum('Yes','No') DEFAULT 'Yes',
  `omnivore_menus` longtext,
  `eod_time` varchar(45) DEFAULT NULL,
  `eod_lasttime` varchar(45) DEFAULT NULL,
  `eod_lastelapse` varchar(45) DEFAULT NULL,
  `post_auto_gratuity` varchar(15) DEFAULT NULL,
  `MF_UserName` varchar(45) DEFAULT NULL,
  `MF_Password` varchar(45) DEFAULT NULL,
  `MF_UserID` varchar(45) DEFAULT NULL,
  `MF_LinkID` varchar(45) DEFAULT NULL,
  `MF_accountid` varchar(45) DEFAULT NULL,
  `host_URL` text,
  `MF_Deviceid` varchar(45) DEFAULT NULL,
  `MF_GateWayId` varchar(45) DEFAULT NULL,
  `RP_TPP_Id` varchar(45) DEFAULT NULL,
  `RP_Merchant_Id` varchar(45) DEFAULT NULL,
  `RP_Group_Id` varchar(45) DEFAULT NULL,
  `RP_Terminal_Id` varchar(45) DEFAULT NULL,
  `RP_last_DID` varchar(45) DEFAULT NULL,
  `RP_STAN` varchar(45) DEFAULT NULL,
  `TS_merchant_id` varchar(45) DEFAULT NULL,
  `TS_userid` varchar(45) DEFAULT NULL,
  `TS_password` varchar(45) DEFAULT NULL,
  `TS_DeveloperId` varchar(45) DEFAULT NULL,
  `TS_DeviceID` varchar(45) DEFAULT NULL,
  `NAB_Cust_Nbr` varchar(45) DEFAULT NULL,
  `NAB_Merch_Number` varchar(45) DEFAULT NULL,
  `NAB_DBA_Nbr` varchar(45) DEFAULT NULL,
  `NAB_Terminal_Nbr` varchar(45) DEFAULT NULL,
  `NAB_Mac` varchar(45) DEFAULT NULL,
  `NAB_Host_URL` varchar(45) DEFAULT NULL,
  `gc` enum('Yes','No') DEFAULT 'No',
  `gc_type` varchar(45) DEFAULT NULL,
  `gc_user` varchar(45) DEFAULT NULL,
  `gc_password` varchar(45) DEFAULT NULL,
  `gc_company_id` varchar(45) DEFAULT NULL,
  `gc_application_id` varchar(100) DEFAULT NULL,
  `gc_store_id` varchar(45) DEFAULT NULL,
  `loyalty` enum('Yes','No') DEFAULT 'No',
  `loyalty_type` enum('EatLocal','COMO') DEFAULT NULL,
  `como` enum('Yes','No') DEFAULT 'No',
  `como_api_key` varchar(45) DEFAULT NULL,
  `como_branch_id` varchar(45) DEFAULT NULL,
  `como_pos_id` varchar(45) DEFAULT NULL,
  `como_gift_card` enum('Yes','No') DEFAULT 'No',
  `como_wallet` enum('Yes','No') DEFAULT 'No',
  `como_wallet_credit` enum('Yes','No') DEFAULT 'No',
  `como_wallet_mobile` enum('Yes','No') DEFAULT 'No',
  `como_require_sms` enum('Yes','No') DEFAULT 'No',
  `como_disc_id` varchar(45) DEFAULT NULL,
  `processor` enum('Merchant First','TSYS','Chase','Rapid Connect','EPX') DEFAULT NULL,
  `confirmation_receipt` enum('Yes','No') NOT NULL DEFAULT 'No',
  `idle_logo` enum('Yes','No') NOT NULL DEFAULT 'No',
  `create_tickets_wo_items` enum('Yes','No') NOT NULL DEFAULT 'No',
  `send_items_individually` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `auto_send_to_pos` enum('Yes','No') DEFAULT 'Yes',
  `payment_auto_close` enum('Yes','No') DEFAULT 'Yes',
  `auth_allow` enum('Yes','No') DEFAULT 'No',
  `auth_default_amount` varchar(45) DEFAULT NULL,
  `aireus_storeid` varchar(45) DEFAULT NULL,
  `aireus_api_key` varchar(45) DEFAULT NULL,
  `aireus_host_url` varchar(255) DEFAULT NULL,
  `aireus_token` varchar(45) DEFAULT NULL,
  `aireus_token_time` varchar(45) DEFAULT NULL,
  `aireus_terminal` varchar(45) DEFAULT NULL,
  `focus_url` varchar(255) DEFAULT NULL,
  `eatlocal_mid` varchar(45) DEFAULT NULL,
  `eatlocal_lid` varchar(45) DEFAULT NULL,
  `paypal_merchant_id` varchar(200) DEFAULT NULL,
  `paypal_merchant_secret_key` varchar(200) DEFAULT NULL,
  `paypal` enum('Yes','No') DEFAULT 'No',
  `eye` enum('Yes','No') DEFAULT 'No',
  `eye_restaurant_id` varchar(45) DEFAULT NULL,
  `rounditup` enum('Yes','No') DEFAULT 'No',
  `rounditup_item` varchar(100) DEFAULT NULL,
  `rounditup_nearest_dollar` enum('Yes','No') DEFAULT 'No',
  `rounditup_add_amount` decimal(10,2) DEFAULT NULL,
  `rounditup_account` varchar(45) DEFAULT NULL,
  `send_tip_adjust_failure_email` varchar(64) DEFAULT NULL,
  `tip_adjust_robot` enum('Yes','No') DEFAULT 'No',
  `tip_adjust_auto_send_zero` enum('Yes','No') DEFAULT 'No',
  `tip_adjust_eod_time` varchar(45) DEFAULT NULL,
  `tip_adjust_eod_lasttime` varchar(45) DEFAULT NULL,
  `tip_adjust_eod_lastelapse` varchar(45) DEFAULT NULL,
  `login_require_clock_in` enum('Yes','No') DEFAULT 'No',
  `employees_see_all_checks` enum('Yes','No') DEFAULT 'No',
  `processor_payment_catcher` enum('Yes','No') DEFAULT 'Yes' COMMENT 'Determines if the api that looks for missing transactions can run or not',
  `ov_payment_catcher` enum('Yes','No') DEFAULT 'Yes',
  `ov_wh_payment_catcher` enum('Yes','No') DEFAULT 'No',
  `ov_wh_restrict_based_on_due` enum('Yes','No') DEFAULT 'No',
  `ov_wh_card_not_present` enum('Yes','No') DEFAULT 'No',
  `send_payment_async` enum('Yes','No') NOT NULL DEFAULT 'No',
  `pos_payment_status_robot` enum('Yes','No') DEFAULT 'No',
  `pos_payment_status_robot_time` varchar(45) DEFAULT NULL,
  `pos_remove_payment_gateway_void` enum('Yes','No','Custom') DEFAULT 'No',
  `pos_remove_payment_gateway_void_custom_amount` int(11) DEFAULT '0',
  `offline_payment` enum('Yes','No') DEFAULT 'No',
  `show_item_notes` enum('Yes','No') DEFAULT 'Yes',
  `kiosk_price_check` enum('Yes','No') DEFAULT 'Yes',
  `last_livemenu_upd` datetime DEFAULT NULL,
  `loading_popup_debug` enum('On','Off') NOT NULL DEFAULT 'Off',
  `interceptor_retrieve_ticket` enum('Yes','No') DEFAULT 'Yes',
  `interceptor_error_followup_make_payment` enum('Yes','No') DEFAULT 'No',
  `allow_partial_approval` enum('Yes','No') DEFAULT 'Yes',
  `partial_approval_skip_remaining` enum('Yes','No') DEFAULT 'No',
  `run_abi_cron` enum('Yes','No') DEFAULT 'No',
  `run_open_tickets_cron` enum('Yes','No') DEFAULT 'No',
  `wake_lock_duration` int(11) NOT NULL DEFAULT '10800000',
  `primary_server` varchar(100) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clover_devices_status_idk` (`status`),
  KEY `clover_devices_merchant_id_idk` (`merchant_id`),
  KEY `clover_devices_Location_id_idk` (`location_id`),
  KEY `clover_devices_last_clover_payment_modified_idk` (`last_clover_payment_modified`),
  KEY `clover_devices_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `clover_devices_3rd_party` (`3rd_party`),
  KEY `clover_devices_apps_type` (`apps_type`),
  KEY `clover_devices_cleanup_idk` (`manufacturer`,`apps_type`,`processor`,`run_missing_payment_cleanup_cron`),
  KEY `clover_devices_tipadj_idx` (`3rd_party`,`tips`,`tip_adjust_robot`,`ov_offline_mode`,`processor`),
  KEY `clover_devices_eod_idx` (`eod_time`),
  KEY `clover_devices_proc_idx` (`processor`,`MF_accountid`),
  KEY `clover_devices_eye_idx` (`eye`),
  KEY `clover_devices_otik_idx` (`run_open_tickets_cron`,`status`,`3rd_party`),
  KEY `clover_devices_loc_stat_name_idx` (`location_id`,`status`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1140 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `clover_integrated_payments` */

DROP TABLE IF EXISTS `clover_integrated_payments`;

CREATE TABLE `clover_integrated_payments` (
  `clover_integrated_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` varchar(45) NOT NULL,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `clover_employee_id` varchar(45) DEFAULT NULL,
  `clover_order_id` varchar(45) NOT NULL,
  `clover_payment_id` varchar(45) DEFAULT NULL,
  `clover_tender_type` varchar(45) DEFAULT NULL,
  `tender_name` varchar(45) DEFAULT NULL,
  `device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `split` enum('No','Yes') DEFAULT 'No',
  `last4` varchar(45) DEFAULT NULL,
  `transactionNo` int(11) DEFAULT NULL,
  `authCode` varchar(45) DEFAULT NULL,
  `referenceId` varchar(45) DEFAULT NULL,
  `entryType` varchar(45) DEFAULT NULL,
  `cvmResult` varchar(45) DEFAULT NULL,
  `gc_number` varchar(45) DEFAULT NULL,
  `omnivore_tickets_id` int(11) DEFAULT NULL,
  `client_order_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `id_pay` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `seat` int(11) DEFAULT '99',
  `name` varchar(45) DEFAULT NULL,
  `subtotal` varchar(45) DEFAULT NULL,
  `tax` varchar(45) DEFAULT NULL,
  `tip` varchar(45) DEFAULT NULL,
  `payment` varchar(45) DEFAULT NULL,
  `changedue` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `processed_on` enum('DataPoint','Payment Matcher') DEFAULT 'DataPoint',
  `processed_on_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending','Failure','Wh_Error','Pre_Auth','Sending') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `async_status` enum('No','Processing','Finished','Failed') DEFAULT 'No',
  `payment_error` varchar(45) DEFAULT NULL,
  `show_retry_popup` enum('Yes','No') DEFAULT 'No',
  `make_a_payment` text,
  `make_payment_url` text,
  `signature_image` varchar(255) DEFAULT NULL,
  `softpoint_initials` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`clover_integrated_payments_id`),
  KEY `clover_integrated_payments_location_id_idk` (`location_id`),
  KEY `clover_integrated_payments_clover_order_id_idk` (`clover_order_id`),
  KEY `clover_integrated_payments_employee_id_idk` (`employee_id`),
  KEY `clover_integrated_payments_ticket_idk` (`ticket`),
  KEY `clover_integrated_payments_processed_idk` (`processed`),
  KEY `clover_integrated_payments_opened_at_idk` (`opened_at`),
  KEY `idx_combo` (`omnivore_tickets_id`,`status`(9)),
  KEY `idx_merchant` (`merchant_id`(13),`created_datetime`),
  KEY `clover_integrated_payments_clover_payment_id_idk` (`clover_payment_id`),
  KEY `clover_integrated_payments_status_idk` (`status`),
  KEY `clover_integrated_payments_payment` (`payment`),
  KEY `clover_integrated_payments_location_dt_idk` (`location_id`,`created_datetime`),
  KEY `clover_integrated_payments_created_dt_idk` (`created_datetime`),
  KEY `clover_integrated_payments_location_ov_tik_idk` (`location_id`,`omnivore_tickets_id`),
  KEY `clover_integrated_payments_location_ov_tik_stat_idk` (`location_id`,`omnivore_tickets_id`,`status`),
  KEY `clover_integrated_payments_location_tranNo_idk` (`location_id`,`transactionNo`)
) ENGINE=InnoDB AUTO_INCREMENT=3708943 DEFAULT CHARSET=utf8;

/*Table structure for table `clover_integrated_payments_audit` */

DROP TABLE IF EXISTS `clover_integrated_payments_audit`;

CREATE TABLE `clover_integrated_payments_audit` (
  `clover_integrated_payments_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `merchant_id` varchar(55) DEFAULT NULL,
  `clover_order_id` varchar(55) DEFAULT NULL,
  `clover_payment_id` varchar(55) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `transactionNo` varchar(55) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `tax` float(10,2) DEFAULT NULL,
  `tip` float(10,2) DEFAULT NULL,
  `status` enum('SUCCESS','FAIL') DEFAULT NULL,
  PRIMARY KEY (`clover_integrated_payments_audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68199 DEFAULT CHARSET=latin1;

/*Table structure for table `clover_integrated_payments_log` */

DROP TABLE IF EXISTS `clover_integrated_payments_log`;

CREATE TABLE `clover_integrated_payments_log` (
  `clover_integrated_payments_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `location_id` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `data` longtext CHARACTER SET latin1,
  `type` enum('Pending','Paid','Webhook') CHARACTER SET latin1 NOT NULL,
  `created_by` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `created_on` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`clover_integrated_payments_log_id`),
  UNIQUE KEY `clover_integrated_payments_log_UNIQUE` (`clover_integrated_payments_log_id`),
  KEY `clover_integrated_payments_log_created_dt_idk` (`created_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=6817263 DEFAULT CHARSET=utf8;

/*Table structure for table `clover_integrated_payments_pending` */

DROP TABLE IF EXISTS `clover_integrated_payments_pending`;

CREATE TABLE `clover_integrated_payments_pending` (
  `clover_integrated_payments_pending_id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` varchar(45) NOT NULL,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `clover_employee_id` varchar(45) DEFAULT NULL,
  `clover_order_id` varchar(45) NOT NULL,
  `clover_payment_id` varchar(45) DEFAULT NULL,
  `clover_tender_type` varchar(45) DEFAULT NULL,
  `tender_name` varchar(45) DEFAULT NULL,
  `device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `split` enum('No','Yes') DEFAULT 'No',
  `transactionNo` int(11) DEFAULT NULL,
  `omnivore_tickets_id` int(11) DEFAULT NULL,
  `client_order_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `seat` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `subtotal` varchar(45) DEFAULT NULL,
  `tax` varchar(45) DEFAULT NULL,
  `tip` varchar(45) DEFAULT NULL,
  `payment` varchar(45) DEFAULT NULL,
  `changedue` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `id_pay` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `make_a_payment` text,
  `request` text,
  `response` text,
  `softpoint_initials` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`clover_integrated_payments_pending_id`),
  KEY `clover_integrated_payments_pending_location_id_idk` (`location_id`),
  KEY `clover_integrated_payments_pending_clover_order_id_idk` (`clover_order_id`),
  KEY `clover_integrated_payments_pending_employee_id_idk` (`employee_id`),
  KEY `clover_integrated_payments_pending_ticket_idk` (`ticket`),
  KEY `clover_integrated_payments_pending_processed_idk` (`processed`),
  KEY `clover_integrated_payments_pending_opened_at_idk` (`opened_at`),
  KEY `clover_integrated_payments_pending_merchant_id_idk` (`merchant_id`),
  KEY `clover_integrated_payments_pending_location_dt_idk` (`location_id`,`created_datetime`),
  KEY `clover_integrated_payments_pending_created_dt_idk` (`created_datetime`),
  KEY `clover_integrated_payments_pending_upd_idk` (`location_id`,`employee_id`,`transactionNo`,`type`,`omnivore_tickets_id`,`tip`,`payment`),
  KEY `clover_integrated_payments_pending_loc_tik_idk` (`location_id`,`omnivore_tickets_id`),
  KEY `clover_integrated_payments_pending_merchant_trans_idk` (`merchant_id`,`transactionNo`)
) ENGINE=InnoDB AUTO_INCREMENT=3873911 DEFAULT CHARSET=utf8;

/*Table structure for table `clover_logs` */

DROP TABLE IF EXISTS `clover_logs`;

CREATE TABLE `clover_logs` (
  `clover_logs_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(64) DEFAULT NULL,
  `log` text,
  `error_code` varchar(150) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `employee` varchar(45) DEFAULT NULL,
  `location_id` varchar(45) DEFAULT NULL,
  `ov_location_id` varchar(45) DEFAULT NULL,
  `merchant_id` varchar(45) DEFAULT NULL,
  `ticket_id` varchar(45) DEFAULT NULL,
  `clover_order_id` varchar(45) DEFAULT NULL,
  `clover_payment_id` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`clover_logs_id`),
  KEY `clover_logs_id_type_idk` (`type`),
  KEY `clover_logs_location_id_idk` (`location_id`),
  KEY `clover_logs_ov_location_id_idk` (`ov_location_id`),
  KEY `clover_logs_merchant_id_idk` (`merchant_id`),
  KEY `clover_logs_clover_order_id_idk` (`clover_order_id`),
  KEY `clover_logs_clover_payment_id_idk` (`clover_payment_id`),
  KEY `clover_logs_created_dt_idk` (`created_datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `clover_logs_apps` */

DROP TABLE IF EXISTS `clover_logs_apps`;

CREATE TABLE `clover_logs_apps` (
  `clover_logs_apps_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(64) DEFAULT NULL,
  `log` text,
  `error_code` varchar(150) DEFAULT NULL,
  `location_id` varchar(45) DEFAULT NULL,
  `ov_location_id` varchar(45) DEFAULT NULL,
  `merchant_id` varchar(45) DEFAULT NULL,
  `ticket_id` varchar(45) DEFAULT NULL,
  `clover_order_id` varchar(45) DEFAULT NULL,
  `clover_payment_id` varchar(45) DEFAULT NULL,
  `transactionNo` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`clover_logs_apps_id`),
  KEY `clover_logs_apps_id_type_idk` (`type`),
  KEY `clover_logs_apps_location_id_idk` (`location_id`),
  KEY `clover_logs_apps_ov_location_id_idk` (`ov_location_id`),
  KEY `clover_logs_apps_merchant_id_idk` (`merchant_id`),
  KEY `clover_logs_apps_clover_order_id_idk` (`clover_order_id`),
  KEY `clover_logs_apps_clover_payment_id_idk` (`clover_payment_id`),
  KEY `clover_logs_apps_created_dt_idk` (`created_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=1301702 DEFAULT CHARSET=utf8;

/*Table structure for table `clover_nonintegrated_orders` */

DROP TABLE IF EXISTS `clover_nonintegrated_orders`;

CREATE TABLE `clover_nonintegrated_orders` (
  `clover_nonintegrated_orders_id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` varchar(45) NOT NULL,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `clover_employee_id` varchar(45) DEFAULT NULL,
  `clover_order_id` varchar(45) NOT NULL,
  `clover_payment_id` varchar(45) DEFAULT NULL,
  `clover_authorization_id` varchar(45) DEFAULT NULL,
  `clover_tender_type` varchar(45) DEFAULT NULL,
  `tender_name` varchar(45) DEFAULT NULL,
  `last4` varchar(45) DEFAULT NULL,
  `transactionNo` int(11) DEFAULT NULL,
  `authCode` varchar(45) DEFAULT NULL,
  `referenceId` varchar(45) DEFAULT NULL,
  `entryType` varchar(45) DEFAULT NULL,
  `cvmResult` varchar(45) DEFAULT NULL,
  `device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `subtotal` varchar(45) DEFAULT NULL,
  `tax` varchar(45) DEFAULT NULL,
  `tip` varchar(45) DEFAULT NULL,
  `payment` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `authorization_amount` varchar(45) DEFAULT NULL,
  `customer` varchar(45) DEFAULT NULL,
  `doctor` varchar(45) DEFAULT NULL,
  `reason` varchar(45) DEFAULT NULL,
  `car_contract` varchar(45) DEFAULT NULL,
  `car_agent` varchar(45) DEFAULT NULL,
  `car_end_datetime` datetime DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `make_a_payment` text,
  `softpoint_initials` varchar(2) DEFAULT NULL,
  `custom_fields` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`clover_nonintegrated_orders_id`),
  KEY `clover_nonintegrated_orders_location_id_idk` (`location_id`),
  KEY `clover_nonintegrated_orders_clover_order_id_idk` (`clover_order_id`),
  KEY `clover_nonintegrated_orders_employee_id_idk` (`employee_id`),
  KEY `clover_nonintegrated_orders_ticket_idk` (`ticket`),
  KEY `clover_nonintegrated_orders_processed_idk` (`processed`),
  KEY `clover_nonintegrated_orders_opened_at_idk` (`opened_at`),
  KEY `clover_nonintegrated_orders_created_dt_idk` (`created_datetime`),
  KEY `clover_nonintegrated_orders_location_dt_idk` (`location_id`,`created_datetime`),
  KEY `clover_nonintegrated_orders_pay_id_idk` (`clover_payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40710 DEFAULT CHARSET=utf8;

/*Table structure for table `clover_omnivore_payment` */

DROP TABLE IF EXISTS `clover_omnivore_payment`;

CREATE TABLE `clover_omnivore_payment` (
  `clover_omnivore_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` text,
  `merchant_id` varchar(45) DEFAULT NULL,
  `clover_device_id` varchar(45) DEFAULT NULL,
  `clover_order_id` varchar(45) DEFAULT NULL,
  `clover_order_type` varchar(45) DEFAULT NULL,
  `clover_payment_id` varchar(45) DEFAULT NULL,
  `payment_data_pay` text,
  `attempts` int(11) DEFAULT NULL,
  `omnivore_make_payment` text,
  `omnivore_ticket_id` text,
  `omnivore_type` varchar(45) DEFAULT NULL,
  `omnivore_tender_type` varchar(45) DEFAULT NULL,
  `omnivore_amount` decimal(14,2) DEFAULT NULL,
  `failed` varchar(4) DEFAULT NULL,
  `not_found_in_payment` varchar(4) DEFAULT NULL,
  `payment_datetime` datetime DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`clover_omnivore_payment_id`),
  KEY `clover_omnivore_payments_location_id_idk` (`location_id`),
  KEY `clover_omnivore_payments_merchant_id_idk` (`merchant_id`),
  KEY `clover_omnivore_payments_clover_order_id_idk` (`clover_order_id`),
  KEY `clover_omnivore_payments_clover_payment_id_idk` (`clover_payment_id`),
  KEY `clover_omnivore_payments_created_datetime_idk` (`created_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=20579 DEFAULT CHARSET=utf8;

/*Table structure for table `clover_softpoint_payment` */

DROP TABLE IF EXISTS `clover_softpoint_payment`;

CREATE TABLE `clover_softpoint_payment` (
  `clover_omnivore_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `clover_device_id` varchar(45) DEFAULT NULL,
  `merchant_id` varchar(45) DEFAULT NULL,
  `clover_order_id` varchar(45) DEFAULT NULL,
  `clover_order_type` varchar(45) DEFAULT NULL,
  `clover_payment_id` varchar(45) DEFAULT NULL,
  `attempts` int(11) DEFAULT NULL,
  `client_order_id` varchar(45) DEFAULT NULL,
  `client_sales_id` varchar(45) DEFAULT NULL,
  `payment_type` varchar(45) DEFAULT NULL,
  `amount` decimal(14,2) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`clover_omnivore_payment_id`),
  KEY `clover_softpoint_payment_location_id_idk` (`location_id`),
  KEY `clover_softpoint_payment_merchant_id_idk` (`merchant_id`),
  KEY `clover_softpoint_payment_clover_order_id_idk` (`clover_order_id`),
  KEY `clover_softpoint_payment_clover_payment_id_idk` (`clover_payment_id`),
  KEY `clover_softpoint_payment_client_order_id_idk` (`client_order_id`),
  KEY `clover_softpoint_payment_client_sales_id_idk` (`client_sales_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1012 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `clover_terminals` */

DROP TABLE IF EXISTS `clover_terminals`;

CREATE TABLE `clover_terminals` (
  `clover_terminals_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Active','Inactive') NOT NULL,
  `device_type` enum('Flex','Mobile','Mini','Mini 2') NOT NULL DEFAULT 'Flex',
  `terminal_id` varchar(64) NOT NULL,
  `location_id` int(8) NOT NULL,
  `physically_located` varchar(64) DEFAULT NULL,
  `omnivore_terminal_id` varchar(45) DEFAULT NULL,
  `omnivore_tender_type_id` varchar(45) DEFAULT NULL,
  `request_log` enum('Yes','No') DEFAULT 'No',
  `requested_log_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`clover_terminals_id`),
  UNIQUE KEY `clover_terminals_id_UNIQUE` (`clover_terminals_id`),
  UNIQUE KEY `terminal_id_UNIQUE` (`terminal_id`),
  KEY `clover_terminals_findter_idx` (`location_id`,`omnivore_terminal_id`,`omnivore_tender_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

/*Table structure for table `clover_webhook` */

DROP TABLE IF EXISTS `clover_webhook`;

CREATE TABLE `clover_webhook` (
  `clover_webhook_id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` varchar(45) NOT NULL,
  `webhook` longtext,
  `call_process_payment` longtext,
  `clover_order_id` varchar(45) DEFAULT NULL,
  `clover_payment_id` varchar(45) DEFAULT NULL,
  `payment_processed` enum('Yes','No','Not Found','Skipped') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `softpoint_initials` varchar(2) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `omnivore_ticket_id` varchar(45) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`clover_webhook_id`),
  KEY `clover_webhook_merchant_id_idk` (`merchant_id`),
  KEY `clover_webhook_clover_order_id_idk` (`clover_order_id`),
  KEY `clover_webhook_clover_payment_id_idk` (`clover_payment_id`),
  KEY `clover_webhook_payment_processed_idk` (`payment_processed`),
  KEY `clover_webhook_created_datetime_idk` (`created_datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `clover_webhooks_apps` */

DROP TABLE IF EXISTS `clover_webhooks_apps`;

CREATE TABLE `clover_webhooks_apps` (
  `clover_webhook_id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` varchar(45) NOT NULL,
  `webhook` longtext,
  `call_process_payment` longtext,
  `clover_order_id` varchar(45) DEFAULT NULL,
  `clover_payment_id` varchar(45) DEFAULT NULL,
  `payment_processed` enum('Yes','No','Not Found','Skipped','Error','Failure','Unfixable','Open Terminal','Sale','Clover Error') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `softpoint_initials` varchar(2) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `ethor_store_id` varchar(45) DEFAULT NULL,
  `omnivore_ticket_id` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `request_url` longtext,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`clover_webhook_id`),
  KEY `clover_webhook_merchant_id_idk` (`merchant_id`),
  KEY `clover_webhook_clover_order_id_idk` (`clover_order_id`),
  KEY `clover_webhook_clover_payment_id_idk` (`clover_payment_id`),
  KEY `clover_webhook_payment_processed_idk` (`payment_processed`),
  KEY `id_loc` (`location_id`),
  KEY `idx_date` (`created_datetime`),
  KEY `idx_webhk` (`webhook`(255)),
  KEY `webhook_created_datetitme_combo` (`created_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=5725108 DEFAULT CHARSET=utf8;

/*Table structure for table `clover_webhooks_apps_skipped` */

DROP TABLE IF EXISTS `clover_webhooks_apps_skipped`;

CREATE TABLE `clover_webhooks_apps_skipped` (
  `clover_webhook_id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` varchar(45) NOT NULL,
  `webhook` longtext,
  `call_process_payment` longtext,
  `clover_order_id` varchar(45) DEFAULT NULL,
  `clover_payment_id` varchar(45) DEFAULT NULL,
  `payment_processed` enum('Yes','No','Not Found','Skipped','Error','Failure') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `softpoint_initials` varchar(2) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `omnivore_ticket_id` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`clover_webhook_id`),
  KEY `clover_webhook_merchant_id_idk` (`merchant_id`),
  KEY `clover_webhook_clover_order_id_idk` (`clover_order_id`),
  KEY `clover_webhook_clover_payment_id_idk` (`clover_payment_id`),
  KEY `clover_webhook_payment_processed_idk` (`payment_processed`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `companies` */

DROP TABLE IF EXISTS `companies`;

CREATE TABLE `companies` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `representative` varchar(32) NOT NULL,
  `representative_title` varchar(45) DEFAULT NULL,
  `affi_type` enum('company','group','travel agency','internet agency','wholesaler','other','receivables') NOT NULL,
  `business_type` int(11) DEFAULT NULL,
  `email` varchar(64) NOT NULL,
  `country` int(4) NOT NULL,
  `addresses` varchar(64) NOT NULL,
  `addresses2` varchar(64) DEFAULT NULL,
  `city` varchar(64) NOT NULL,
  `state` int(4) NOT NULL,
  `zip` varchar(16) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `fax` varchar(14) DEFAULT NULL,
  `website` varchar(64) DEFAULT NULL,
  `ein_vat` varchar(16) DEFAULT NULL,
  `timezone` varchar(6) DEFAULT NULL COMMENT 'Based on GMT',
  `images` varchar(255) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`company_id`),
  KEY `key_companies_country_idx` (`country`),
  KEY `key_companies_state_idx` (`state`),
  KEY `companies_type_fk_idx` (`business_type`),
  CONSTRAINT `comapnies` FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `companies_country_fk` FOREIGN KEY (`country`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `companies_type_fk` FOREIGN KEY (`business_type`) REFERENCES `location_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Stores companies and affiliations. Used for receivables';

/*Table structure for table `companies_audit` */

DROP TABLE IF EXISTS `companies_audit`;

CREATE TABLE `companies_audit` (
  `companies_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `representative` varchar(32) DEFAULT NULL,
  `representative_title` varchar(45) DEFAULT NULL,
  `affi_type` enum('company','group','travel agency','internet agency','wholesaler','other','receivables') DEFAULT NULL,
  `business_type` int(11) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `country` int(4) DEFAULT NULL,
  `addresses` varchar(64) DEFAULT NULL,
  `addresses2` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `fax` varchar(14) DEFAULT NULL,
  `website` varchar(64) DEFAULT NULL,
  `ein_vat` varchar(16) DEFAULT NULL,
  `timezone` varchar(6) DEFAULT NULL,
  `images` varchar(255) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`companies_audit_id`),
  KEY `companies_audit_company_id_idx` (`company_id`),
  KEY `companies_audit_last_datetime_idx` (`last_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `companies_locations` */

DROP TABLE IF EXISTS `companies_locations`;

CREATE TABLE `companies_locations` (
  `companies_location_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `location_id` int(8) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `receivables` enum('Yes','No') NOT NULL DEFAULT 'No',
  `terms` varchar(45) DEFAULT NULL,
  `credit_limit` decimal(14,2) DEFAULT NULL,
  `interest` enum('Yes','No') NOT NULL DEFAULT 'No',
  `interest_percentage` decimal(4,2) unsigned zerofill NOT NULL,
  `after_number_of_days` decimal(3,0) unsigned zerofill NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`companies_location_id`),
  KEY `companies_locations_location_fk_idx` (`location_id`),
  KEY `companies_locations_company_idx` (`company_id`),
  CONSTRAINT `companies_locations_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='Preauthorized comapnies that work with locations';

/*Table structure for table `companies_locations_groups` */

DROP TABLE IF EXISTS `companies_locations_groups`;

CREATE TABLE `companies_locations_groups` (
  `companies_locations_groups_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `location_id` int(8) NOT NULL,
  `status` enum('Shopping','Tentative','Definite','Cancelled','Completed') NOT NULL,
  `group_name` varchar(64) NOT NULL,
  `arrival` date NOT NULL,
  `departure` date NOT NULL,
  `ratetype` int(11) DEFAULT NULL,
  `mealplan` int(11) DEFAULT NULL,
  `number_of_account` int(11) DEFAULT NULL,
  `number_of_adults` int(11) DEFAULT NULL,
  `number_of_children` int(11) DEFAULT NULL,
  `notes` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`companies_locations_groups_id`),
  KEY `companies_locations_groups_location_fk_idx` (`location_id`),
  CONSTRAINT `companies_locations_groups_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Holds the group details for a location group';

/*Table structure for table `companies_locations_groups_inventory` */

DROP TABLE IF EXISTS `companies_locations_groups_inventory`;

CREATE TABLE `companies_locations_groups_inventory` (
  `companies_locations_groups_inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `companies_locations_groups_id` int(11) NOT NULL,
  `locations_id` int(8) NOT NULL,
  `date` date NOT NULL,
  `roomtype` int(11) NOT NULL,
  `block` int(11) DEFAULT NULL,
  `reserved` int(11) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`companies_locations_groups_inventory_id`),
  KEY `companies_locations_groups_inventory_location_fk_idx` (`locations_id`),
  CONSTRAINT `companies_locations_groups_inventory_location_fk` FOREIGN KEY (`locations_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Hold the groups inventory and rates to use';

/*Table structure for table `companies_receivables` */

DROP TABLE IF EXISTS `companies_receivables`;

CREATE TABLE `companies_receivables` (
  `company_receivables_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `location_employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `hotelacct_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `client_orders_id` int(11) DEFAULT NULL,
  `amount` decimal(14,2) NOT NULL,
  `payment` decimal(14,2) NOT NULL,
  `balance` decimal(14,2) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) NOT NULL,
  `last_by` varchar(45) NOT NULL,
  `last_datetime` datetime NOT NULL,
  PRIMARY KEY (`company_receivables_id`),
  KEY `comp_receivables_sales_idx` (`client_sales_id`),
  KEY `comp_receivables_hotelacct_idx` (`hotelacct_id`),
  KEY `comp_receivables_emp_fk_idx` (`location_employee_id`),
  KEY `comp_receivables_comp_fk_idx` (`company_id`),
  KEY `comp_receivables_location_id_idx` (`location_id`),
  KEY `comp_receivables_order_fk_idx` (`client_orders_id`),
  CONSTRAINT `comp_receivables_comp_fk` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `comp_receivables_emp_fk` FOREIGN KEY (`location_employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `comp_receivables_location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='Stores the receivables records for companies and locations';

/*Table structure for table `companies_receivables_payments` */

DROP TABLE IF EXISTS `companies_receivables_payments`;

CREATE TABLE `companies_receivables_payments` (
  `company_receivables_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `companies_receivables_id` int(11) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `location_employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `payment` decimal(14,2) NOT NULL DEFAULT '0.00',
  `settled_payment` decimal(14,2) DEFAULT '0.00',
  `paymenttype` int(11) DEFAULT NULL,
  `Balance` decimal(14,2) DEFAULT '0.00',
  `payment_code` int(11) DEFAULT NULL,
  `cc_firstname` varchar(100) DEFAULT NULL,
  `cc_lastname` varchar(100) DEFAULT NULL,
  `cc_number` varchar(64) DEFAULT NULL,
  `cc_name` varchar(64) DEFAULT NULL,
  `cc_exp` varchar(10) DEFAULT NULL,
  `cc_cvv` varchar(10) DEFAULT NULL,
  `ccsecurity` varchar(30) DEFAULT NULL,
  `authorization` varchar(20) DEFAULT NULL,
  `cc_autho` varchar(50) DEFAULT NULL,
  `cc_autho_date` date DEFAULT NULL,
  `cc_autho_time` time DEFAULT NULL,
  `autho_amount` decimal(10,2) DEFAULT NULL,
  `autho_date` datetime DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`company_receivables_payments_id`),
  KEY `comp_recv_payment_comp_fk_idx` (`company_id`),
  KEY `comp_recv_payment_emp_fk_idx` (`location_employee_id`),
  KEY `comp_resv_payment_loc_fk_idx` (`location_id`),
  KEY `comp_recv_payment_comp_recv_fk_idx` (`companies_receivables_id`),
  CONSTRAINT `comp_recv_payment_comp_fk` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `comp_recv_payment_comp_recv_fk` FOREIGN KEY (`companies_receivables_id`) REFERENCES `companies_receivables` (`company_receivables_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `comp_recv_payment_emp_fk` FOREIGN KEY (`location_employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `comp_resv_payment_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the payment records for receivable accounts';

/*Table structure for table `companies_receivables_settlements` */

DROP TABLE IF EXISTS `companies_receivables_settlements`;

CREATE TABLE `companies_receivables_settlements` (
  `companies_receivables_settlements_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_receivables_payments_id` int(11) DEFAULT NULL,
  `company_receivables_id` int(11) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`companies_receivables_settlements_id`),
  KEY `companies_receivables_settlements_ibfk_1_idx` (`company_receivables_id`),
  KEY `company_receivables_payments_id_idx` (`company_receivables_payments_id`),
  CONSTRAINT `companies_receivables_settlements_ibfk_1` FOREIGN KEY (`company_receivables_id`) REFERENCES `companies_receivables` (`company_receivables_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `company_receivables_payments_id` FOREIGN KEY (`company_receivables_payments_id`) REFERENCES `companies_receivables_payments` (`company_receivables_payments_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table holds the reference of what a particular receivab';

/*Table structure for table `competitors` */

DROP TABLE IF EXISTS `competitors`;

CREATE TABLE `competitors` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `company` varchar(45) CHARACTER SET latin1 NOT NULL,
  `product` varchar(45) CHARACTER SET latin1 NOT NULL,
  `type` enum('Restaurant POS','Retail POS','Hotel PMS') CHARACTER SET latin1 NOT NULL,
  `license_requirement` varchar(45) NOT NULL,
  `integration_available` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='List of competitors and product names.';

/*Table structure for table `corporate` */

DROP TABLE IF EXISTS `corporate`;

CREATE TABLE `corporate` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `status` enum('active','inactive','closed') NOT NULL DEFAULT 'active',
  `primary_type` enum('Hotel','Restaurant','Retail','Other','Reseller') NOT NULL,
  `sales_status` enum('Not Yet Contacted','Contacted','Emailed','Surveyed','Asleep','Interested','Proposal','Contract','Declined','Registered','Boarding','Integrated','On Hold - Do Not Bill','Lab','Installed','Suspended','Cancelled','Terminated By SoftPoint') NOT NULL DEFAULT 'Not Yet Contacted' COMMENT 'Registered: Location has registered for SoftPoint products but not installed.\nEmailed: Location has been emailed.\nContacted: Location has been contacted.\nNot Yet Contacted: \nLocation has not been contacted.\nSurveyed: Survey has been sent to location.\nDeclined: Location is not interested in SoftPoint.\nSuspended: SoftPoint has suspended usage of products.\n\nCancelled: Location has cancelled services with SoftPoint.\nProposal: SoftPoint has sent merchant proposal.\nContract: SoftPoint has sent merchant contract.\nInstalled: \nLocation is active and installed with progress.\nInterested: Location is interested and having dialog with SoftPoint.',
  `sales_status_date` date DEFAULT NULL,
  `sales_user` int(8) DEFAULT NULL,
  `reseller_type` enum('Bank','Dealer','Distributer','ISO','ISV','ITV','Other','Processor') DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `key_corp` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `Representative` varchar(45) NOT NULL,
  `Representative_title` varchar(45) DEFAULT NULL,
  `Representative_phone` varchar(45) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `address2` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) DEFAULT NULL,
  `country` int(4) DEFAULT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `toll_free` varchar(14) DEFAULT NULL,
  `website` varchar(64) DEFAULT NULL,
  `longitude` varchar(12) DEFAULT NULL,
  `latitude` varchar(12) DEFAULT NULL,
  `franchise` enum('no','yes') DEFAULT NULL,
  `establishments` varchar(65) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `corporate_header_image` varchar(255) DEFAULT NULL,
  `currency_id` int(8) DEFAULT NULL,
  `description` text,
  `integrated_cc_fees` decimal(10,4) DEFAULT NULL,
  `nonintegrated_cc_fees` decimal(10,4) DEFAULT NULL,
  `current_cc_processer` enum('Chase Paymentech','Elavon','First Data','Global Payments','Heartland','TransFirst','TSYS','WorldPay') DEFAULT NULL,
  `integrated_cc_processing` enum('Yes','No') DEFAULT 'No',
  `pos_pms_system` enum('Yes','No') DEFAULT 'No',
  `current_pos_pms_system` varchar(100) DEFAULT NULL,
  `pos_version_number` varchar(45) DEFAULT NULL,
  `facebook` varchar(70) DEFAULT NULL,
  `twitter` varchar(70) DEFAULT NULL,
  `google_plus` varchar(70) DEFAULT NULL,
  `linked_in` varchar(70) DEFAULT NULL,
  `instagram` varchar(70) DEFAULT NULL,
  `access_quality_department` enum('Yes','No') DEFAULT 'Yes',
  `timeout` varchar(45) DEFAULT '15',
  `api_key` varchar(128) DEFAULT NULL,
  `access_quality_due_date` enum('Yes','No') DEFAULT 'Yes',
  `access_quality_status` enum('Yes','No') DEFAULT 'Yes',
  `access_quality_over_budget` enum('Yes','No') DEFAULT 'No',
  `agreement_nda` enum('Yes','No') DEFAULT 'No',
  `agreement_nda_pdf` varchar(255) DEFAULT NULL,
  `agreement_type` enum('Referral','Reseller','Mutual Referral','None') DEFAULT 'None',
  `agreement_type_pdf` varchar(255) DEFAULT NULL,
  `agreement_type_pct` int(3) DEFAULT NULL,
  `min_amt_bfr_cmsn` decimal(10,2) DEFAULT NULL,
  `ov_corp_sync` enum('Yes','No') DEFAULT 'No',
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `corporate_currency_id` (`currency_id`),
  KEY `coprorate_country_id_idx` (`country`),
  KEY `corporate_state_id_idx` (`state`),
  KEY `corporate_api_key_idk` (`api_key`),
  KEY `corporate_ptype` (`primary_type`,`name`),
  KEY `corporate_name_stat_idx` (`name`,`sales_status`),
  CONSTRAINT `corporate_country_id` FOREIGN KEY (`country`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `corporate_currency_id` FOREIGN KEY (`currency_id`) REFERENCES `global_currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `corporate_state_id` FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3658 DEFAULT CHARSET=latin1 COMMENT='Stores corporate business information';

/*Table structure for table `corporate_ads` */

DROP TABLE IF EXISTS `corporate_ads`;

CREATE TABLE `corporate_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `corporate_id` int(11) NOT NULL,
  `Status` enum('active','pending','inactive') NOT NULL,
  `Description` varchar(60) NOT NULL,
  `priority` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `consumer_large_image` enum('yes','no') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `corporate_ads_fk` (`corporate_id`),
  CONSTRAINT `corporate_ads_fk` FOREIGN KEY (`corporate_id`) REFERENCES `corporate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores ads for a corporation';

/*Table structure for table `corporate_associations` */

DROP TABLE IF EXISTS `corporate_associations`;

CREATE TABLE `corporate_associations` (
  `corporate_association_id` int(11) NOT NULL AUTO_INCREMENT,
  `association_type` enum('Location','Corporate','Reseller','sub_reseller','Other') DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `corporate_id` int(11) DEFAULT NULL,
  `associated_corporate_id` int(11) DEFAULT NULL,
  `other_name` varchar(50) DEFAULT NULL,
  `other_type` varchar(50) DEFAULT NULL,
  `other_contact` varchar(50) DEFAULT NULL,
  `other_country` int(11) DEFAULT NULL,
  `other_state` int(11) DEFAULT NULL,
  `other_city` varchar(50) DEFAULT NULL,
  `other_phone` varchar(50) DEFAULT NULL,
  `notes` text,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`corporate_association_id`),
  KEY `corporate_associations_corp_idx` (`corporate_id`),
  KEY `corporate_associations_loc_type_idx` (`location_id`,`association_type`)
) ENGINE=InnoDB AUTO_INCREMENT=880 DEFAULT CHARSET=latin1;

/*Table structure for table `corporate_audit` */

DROP TABLE IF EXISTS `corporate_audit`;

CREATE TABLE `corporate_audit` (
  `corporate_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(8) DEFAULT NULL,
  `status` enum('active','inactive','closed') DEFAULT NULL,
  `primary_type` enum('Hotel','Restaurant','Retail','Other','Reseller') DEFAULT NULL,
  `sales_status` enum('Not Yet Contacted','Contacted','Emailed','Surveyed','Asleep','Interested','Proposal','Contract','Declined','Registered','Boarding','Integrated','On Hold - Do Not Bill','Lab','Installed','Suspended','Cancelled','Terminated By SoftPoint') DEFAULT NULL,
  `sales_status_date` date DEFAULT NULL,
  `sales_user` int(8) DEFAULT NULL,
  `reseller_type` enum('Bank','Dealer','Distributer','ISO','ISV','ITV','Other','Processor') DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `key_corp` varchar(12) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `Representative` varchar(45) DEFAULT NULL,
  `Representative_title` varchar(45) DEFAULT NULL,
  `Representative_phone` varchar(45) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `address2` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) DEFAULT NULL,
  `country` int(4) DEFAULT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `toll_free` varchar(14) DEFAULT NULL,
  `website` varchar(64) DEFAULT NULL,
  `longitude` varchar(12) DEFAULT NULL,
  `latitude` varchar(12) DEFAULT NULL,
  `franchise` enum('no','yes') DEFAULT NULL,
  `establishments` varchar(65) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `corporate_header_image` varchar(255) DEFAULT NULL,
  `currency_id` int(8) DEFAULT NULL,
  `description` text,
  `integrated_cc_fees` decimal(10,4) DEFAULT NULL,
  `nonintegrated_cc_fees` decimal(10,4) DEFAULT NULL,
  `current_cc_processer` enum('Chase Paymentech','Elavon','First Data','Global Payments','Heartland','TransFirst','TSYS','WorldPay') DEFAULT NULL,
  `integrated_cc_processing` enum('Yes','No') DEFAULT NULL,
  `pos_pms_system` enum('Yes','No') DEFAULT NULL,
  `current_pos_pms_system` varchar(100) DEFAULT NULL,
  `pos_version_number` varchar(45) DEFAULT NULL,
  `facebook` varchar(70) DEFAULT NULL,
  `twitter` varchar(70) DEFAULT NULL,
  `google_plus` varchar(70) DEFAULT NULL,
  `linked_in` varchar(70) DEFAULT NULL,
  `instagram` varchar(70) DEFAULT NULL,
  `access_quality_department` enum('Yes','No') DEFAULT NULL,
  `timeout` varchar(45) DEFAULT NULL,
  `api_key` varchar(128) DEFAULT NULL,
  `access_quality_due_date` enum('Yes','No') DEFAULT NULL,
  `access_quality_status` enum('Yes','No') DEFAULT NULL,
  `access_quality_over_budget` enum('Yes','No') DEFAULT NULL,
  `agreement_nda` enum('Yes','No') DEFAULT NULL,
  `agreement_nda_pdf` varchar(255) DEFAULT NULL,
  `agreement_type` enum('Referral','Reseller','Mutual Referral','None') DEFAULT NULL,
  `agreement_type_pdf` varchar(255) DEFAULT NULL,
  `agreement_type_pct` int(3) DEFAULT NULL,
  `ov_corp_sync` enum('Yes','No') DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`corporate_audit_id`),
  KEY `corporate_audit_last_datetime_idx` (`last_datetime`),
  KEY `corporate_audit_id_idx` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6151 DEFAULT CHARSET=latin1 COMMENT='Stores any changes made to the corporate account information';

/*Table structure for table `corporate_brands` */

DROP TABLE IF EXISTS `corporate_brands`;

CREATE TABLE `corporate_brands` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `corporate_id` int(8) NOT NULL,
  `brand_type` varchar(32) NOT NULL,
  `brand_image` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `corporate_brand_corp` (`corporate_id`),
  KEY `corporate_brand_type` (`brand_type`),
  CONSTRAINT `corporate_brand_corp_fk` FOREIGN KEY (`corporate_id`) REFERENCES `corporate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2521 DEFAULT CHARSET=latin1 COMMENT='Stores the different brands of a corporation';

/*Table structure for table `corporate_employee_master` */

DROP TABLE IF EXISTS `corporate_employee_master`;

CREATE TABLE `corporate_employee_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `corp_id` int(11) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `employee_master_id` int(11) DEFAULT NULL,
  `location_level_emp_id` varchar(12) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `pin` varchar(10) DEFAULT NULL,
  `position` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_corporate_empmaster_id` (`corp_id`,`employee_master_id`),
  KEY `corporate_empmaster_fk_idx` (`employee_master_id`),
  CONSTRAINT `corporate_empmaster_fk` FOREIGN KEY (`employee_master_id`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=latin1 COMMENT='Used to link employee masters with corporate accounts';

/*Table structure for table `corporate_equipment_description` */

DROP TABLE IF EXISTS `corporate_equipment_description`;

CREATE TABLE `corporate_equipment_description` (
  `corporate_equipment_description_id` int(11) NOT NULL AUTO_INCREMENT,
  `corporate_id` int(11) NOT NULL,
  `description` varchar(32) NOT NULL,
  PRIMARY KEY (`corporate_equipment_description_id`),
  UNIQUE KEY `corporate_equipment_description_id_UNIQUE` (`corporate_equipment_description_id`)
) ENGINE=InnoDB AUTO_INCREMENT=321 DEFAULT CHARSET=latin1;

/*Table structure for table `corporate_faq` */

DROP TABLE IF EXISTS `corporate_faq`;

CREATE TABLE `corporate_faq` (
  `corporate_faq_id` int(8) NOT NULL AUTO_INCREMENT,
  `corporate_id` int(8) DEFAULT NULL,
  `product` varchar(45) DEFAULT NULL,
  `module` varchar(45) DEFAULT NULL,
  `questions` varchar(64) DEFAULT NULL,
  `answer` text,
  `answer_by` varchar(45) DEFAULT NULL,
  `commemts` text,
  `image1` tinytext,
  `image2` tinytext,
  `image3` tinytext,
  `video` tinytext,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datatime` datetime DEFAULT NULL,
  PRIMARY KEY (`corporate_faq_id`),
  UNIQUE KEY `corporate_faq_id_UNIQUE` (`corporate_faq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This table store all known Corporate FAQ';

/*Table structure for table `corporate_forecast_labor` */

DROP TABLE IF EXISTS `corporate_forecast_labor`;

CREATE TABLE `corporate_forecast_labor` (
  `corporate_forecast_labor_id` int(11) NOT NULL AUTO_INCREMENT,
  `corporate_id` int(8) NOT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `sales_amount` decimal(14,2) NOT NULL,
  `base` decimal(4,2) DEFAULT NULL,
  `arizona` decimal(4,2) DEFAULT NULL,
  `texas` decimal(4,2) DEFAULT NULL,
  `indiana` decimal(4,2) DEFAULT NULL,
  `kentucky` decimal(4,2) DEFAULT NULL,
  `tennessee` decimal(4,2) DEFAULT NULL,
  `missouri` decimal(4,2) DEFAULT NULL,
  `louisville_metro` decimal(4,2) DEFAULT NULL,
  `illinois` decimal(4,2) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`corporate_forecast_labor_id`),
  KEY `corporate_forecast_labor_corporate_id_idx` (`corporate_id`),
  KEY `corporate_forecast_labor_startdate_idx` (`startdate`),
  KEY `corporate_forecast_labor_sales_amount_idx` (`sales_amount`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=latin1;

/*Table structure for table `corporate_forms` */

DROP TABLE IF EXISTS `corporate_forms`;

CREATE TABLE `corporate_forms` (
  `corporate_forms_id` int(11) NOT NULL AUTO_INCREMENT,
  `corporate_id` int(8) NOT NULL,
  `global_employee_forms_id` int(11) DEFAULT NULL,
  `code` varchar(45) DEFAULT NULL,
  `description` text NOT NULL,
  `employee_status` varchar(128) NOT NULL,
  `scanned_copy` varchar(100) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`corporate_forms_id`),
  UNIQUE KEY `corporate_forms_id_UNIQUE` (`corporate_forms_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `corporate_incidents` */

DROP TABLE IF EXISTS `corporate_incidents`;

CREATE TABLE `corporate_incidents` (
  `corporate_incidents_id` int(11) NOT NULL AUTO_INCREMENT,
  `corporate_id` int(11) DEFAULT NULL,
  `type_of_incident` varchar(10) DEFAULT NULL,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `store` varchar(10) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `region` varchar(10) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `ssn` varchar(50) DEFAULT NULL,
  `emp_dob` date DEFAULT NULL,
  `marital_status` varchar(50) DEFAULT NULL,
  `emp_dependent` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `address2` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `states` varchar(5) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `incident` text,
  `property_loss` text,
  `notified` varchar(50) DEFAULT NULL,
  `occur` varchar(50) DEFAULT NULL,
  `employee_doing` varchar(50) DEFAULT NULL,
  `validity` varchar(50) DEFAULT NULL,
  `witness` varchar(50) DEFAULT NULL,
  `wearing` varchar(50) DEFAULT NULL,
  `shift` varchar(50) DEFAULT NULL,
  `object` varchar(50) DEFAULT NULL,
  `miss_work` varchar(50) DEFAULT NULL,
  `emp_transfered` varchar(50) DEFAULT NULL,
  `weather` varchar(50) DEFAULT NULL,
  `completed_firstname` varchar(50) DEFAULT NULL,
  `completed_lastname` varchar(50) DEFAULT NULL,
  `damage` varchar(50) DEFAULT NULL,
  `food` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `police` varchar(50) DEFAULT NULL,
  `guest_emp` varchar(50) DEFAULT NULL,
  `insurance` varchar(50) DEFAULT NULL,
  `info` varchar(50) DEFAULT NULL,
  `behaviour` varchar(50) DEFAULT NULL,
  `response` varchar(50) DEFAULT NULL,
  `api` varchar(255) DEFAULT NULL,
  `incident_date` datetime DEFAULT NULL,
  `employee_hire_date` date DEFAULT NULL,
  `incident_image` varchar(255) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`corporate_incidents_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `corporate_internal_billing_payments` */

DROP TABLE IF EXISTS `corporate_internal_billing_payments`;

CREATE TABLE `corporate_internal_billing_payments` (
  `corporate_internal_billing_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `corporate_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Payment','Credit','Declined') NOT NULL,
  `payment_amount` decimal(14,2) DEFAULT NULL,
  `credit_amount` decimal(14,2) DEFAULT NULL,
  `bank_account_type` enum('checking','savings','business_checking') DEFAULT NULL,
  `bank_name` varchar(45) DEFAULT NULL,
  `bank_account_name` varchar(45) DEFAULT NULL,
  `bank_account` varchar(45) DEFAULT NULL,
  `bank_routing` varchar(45) DEFAULT NULL,
  `bank_memo` varchar(255) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`corporate_internal_billing_payments_id`),
  KEY `corporate_internal_billing_payments_corporate_fk_idx` (`corporate_id`),
  CONSTRAINT `corporate_internal_billing_payments_corporate_fk` FOREIGN KEY (`corporate_id`) REFERENCES `corporate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Payments made from a SoftPoint to a Corporate Distributor';

/*Table structure for table `corporate_locations` */

DROP TABLE IF EXISTS `corporate_locations`;

CREATE TABLE `corporate_locations` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `corporate_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `priority` int(4) NOT NULL DEFAULT '0',
  `status` enum('A','S','I') NOT NULL,
  `corp_brand_id` int(8) DEFAULT NULL,
  `store_unit` varchar(45) DEFAULT NULL,
  `store_number` varchar(45) DEFAULT NULL,
  `territory` varchar(45) DEFAULT NULL,
  `territory_vp` varchar(45) DEFAULT NULL,
  `region` varchar(45) DEFAULT NULL,
  `region_manager` varchar(45) DEFAULT NULL,
  `24hours` enum('Yes','No') DEFAULT NULL,
  `matrix` enum('Base','Missouri','Illinois','Louisville Metro','Arizona','Texas','Indiana','Kentucky','Tennessee') DEFAULT NULL,
  `tablet` enum('Yes','No') DEFAULT 'No',
  `tablet_date` date DEFAULT NULL,
  `camera_url` varchar(64) DEFAULT NULL,
  `camera_user` varchar(32) DEFAULT NULL,
  `camera_password` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `corporate_locations_loc_idx` (`location_id`),
  KEY `corporate_locations_idx` (`corporate_id`),
  KEY `corporate_locations_brand_idx` (`corp_brand_id`),
  CONSTRAINT `corporate_locations_brand_fk` FOREIGN KEY (`corp_brand_id`) REFERENCES `corporate_brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `corporate_locations_fk` FOREIGN KEY (`corporate_id`) REFERENCES `corporate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `corporate_locations_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=888 DEFAULT CHARSET=latin1 COMMENT='Used to link locations with corporations';

/*Table structure for table `corporate_logs` */

DROP TABLE IF EXISTS `corporate_logs`;

CREATE TABLE `corporate_logs` (
  `corp_logs_id` int(15) NOT NULL AUTO_INCREMENT,
  `corp_id` int(8) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `status` enum('signin','signinfailure','signout') NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(20) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`corp_logs_id`),
  KEY `corp_logs_corp_fk_idx` (`corp_id`),
  CONSTRAINT `corp_logs_corp_fk` FOREIGN KEY (`corp_id`) REFERENCES `corporate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4668 DEFAULT CHARSET=utf8 COMMENT='Stores log information for corporate accounts';

/*Table structure for table `corporate_messages` */

DROP TABLE IF EXISTS `corporate_messages`;

CREATE TABLE `corporate_messages` (
  `corp_msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `corporate_id` int(8) NOT NULL,
  `type_of_message` enum('message','email','surveyed','manual','Call','Visit','PMB','contract','webinar','proposal') NOT NULL,
  `email_type` varchar(60) NOT NULL,
  `subject` text NOT NULL,
  `message` text,
  `status` enum('read','unread') NOT NULL DEFAULT 'read',
  `reminder_date` date DEFAULT NULL,
  `read_by_client` int(8) DEFAULT NULL,
  `read_by_admin_id` int(8) DEFAULT NULL,
  `read_by_employee_id` int(8) DEFAULT NULL,
  `read_datetime` datetime DEFAULT NULL,
  `created_by` int(8) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `file_loc` text,
  `direct_link` text,
  `direct_link_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`corp_msg_id`),
  KEY `corp_messages_corp_fk_idx` (`corporate_id`),
  CONSTRAINT `corp_messages_corp_fk` FOREIGN KEY (`corporate_id`) REFERENCES `corporate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=776 DEFAULT CHARSET=utf8 COMMENT='Stores messages sent to a corporate account';

/*Table structure for table `corporate_pictures` */

DROP TABLE IF EXISTS `corporate_pictures`;

CREATE TABLE `corporate_pictures` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `corporate_id` int(30) NOT NULL,
  `status` enum('active','pending','inactive') NOT NULL,
  `datatime` datetime DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `from_number` varchar(45) DEFAULT NULL,
  `client_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `corporate_pictures_fk` (`corporate_id`),
  KEY `corporate_pictures_client_id_idx` (`client_id`),
  CONSTRAINT `corporate_pictures_client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `corporate_pictures_fk` FOREIGN KEY (`corporate_id`) REFERENCES `corporate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=latin1 COMMENT='Stores pictures associated with a corporation';

/*Table structure for table `corporate_tasks` */

DROP TABLE IF EXISTS `corporate_tasks`;

CREATE TABLE `corporate_tasks` (
  `corporate_tasks_id` int(11) NOT NULL AUTO_INCREMENT,
  `corporate_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `status` enum('Active','Completed') NOT NULL,
  `task_type` tinytext,
  `task_name` varchar(64) DEFAULT NULL,
  `task_color` varchar(45) DEFAULT NULL,
  `description` tinytext,
  `duedate` datetime DEFAULT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `reoccurring` tinytext,
  `assigned_to` varchar(45) DEFAULT NULL,
  `assigned_datetime` datetime DEFAULT NULL,
  `dependencies` tinytext,
  `progress_notes` text,
  `verified_status` enum('Not Required','No','Yes') DEFAULT NULL,
  `verified_datetime` datetime DEFAULT NULL,
  `verified_by` varchar(45) DEFAULT NULL,
  `completion_notes` text,
  `completion_date` datetime DEFAULT NULL,
  `completion_by` varchar(45) DEFAULT NULL,
  `estimated_time` varchar(8) DEFAULT NULL,
  `actual_time` varchar(8) DEFAULT NULL,
  `worked_time` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`corporate_tasks_id`),
  UNIQUE KEY `corporate_tasks_id_UNIQUE` (`corporate_tasks_id`),
  KEY `corporate_tasks_cloc_idx` (`location_id`,`duedate`,`corporate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12692 DEFAULT CHARSET=latin1;

/*Table structure for table `corporate_tasks_logs` */

DROP TABLE IF EXISTS `corporate_tasks_logs`;

CREATE TABLE `corporate_tasks_logs` (
  `corporate_tasks_logs_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `corporate_tasks_id` int(11) NOT NULL,
  `starttime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  PRIMARY KEY (`corporate_tasks_logs_id`),
  UNIQUE KEY `corporate_tasks_logs_id_UNIQUE` (`corporate_tasks_logs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `countries` */

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `code` varchar(8) NOT NULL,
  `description` text NOT NULL,
  `is_default` enum('yes','no') NOT NULL DEFAULT 'no',
  `status` enum('A','S') NOT NULL,
  `iso3` varchar(3) NOT NULL,
  `numcode` int(11) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '2',
  `calling_code` varchar(18) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `countries_name` (`name`),
  KEY `countries_list_idx` (`status`,`is_default`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=297 DEFAULT CHARSET=latin1 COMMENT='Used to store global countries';

/*Table structure for table `crs_conversation` */

DROP TABLE IF EXISTS `crs_conversation`;

CREATE TABLE `crs_conversation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to` varchar(64) NOT NULL,
  `text` varchar(256) NOT NULL,
  `read` enum('Yes','No') NOT NULL,
  `created_by` enum('Employee','Client') NOT NULL,
  `created_on` varchar(50) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores conversations between a client and the crs system';

/*Table structure for table `crs_queue` */

DROP TABLE IF EXISTS `crs_queue`;

CREATE TABLE `crs_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('New','Processing','Cancelled','Completed') DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `session_id` varchar(32) DEFAULT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `finish_datetime` datetime DEFAULT NULL,
  `admin_user` varchar(45) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `mobile_number` varchar(32) DEFAULT NULL,
  `latitude` varchar(32) DEFAULT NULL,
  `longitude` varchar(32) DEFAULT NULL,
  `device_name` varchar(64) DEFAULT NULL,
  `device_platform` varchar(32) DEFAULT NULL,
  `device_uuid` varchar(64) DEFAULT NULL,
  `device_version` varchar(32) DEFAULT NULL,
  `ip_address` varchar(16) DEFAULT NULL,
  `voice_recording_file` varchar(64) DEFAULT NULL,
  `session_id_reload` varchar(32) DEFAULT NULL,
  `created_on` varchar(32) NOT NULL,
  `created_request` enum('Shopping','Reservation','Inquiry','Togo','Delivery','Unknown') NOT NULL,
  `created_type` enum('Phone','Mobile','Browser','Other') NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_crs_queue_client_idx` (`client_id`),
  KEY `crs_queue_location_fk_idx` (`location_id`),
  CONSTRAINT `crs_queue_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `key_crs_queue_clients` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores the queue information for a crs inquiry';

/*Table structure for table `crs_session` */

DROP TABLE IF EXISTS `crs_session`;

CREATE TABLE `crs_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(60) DEFAULT NULL,
  `status` enum('New','Executed') NOT NULL,
  `datetime` datetime NOT NULL,
  `type_of_action` varchar(8) NOT NULL,
  `instruction` varchar(256) NOT NULL,
  `created_on` varchar(50) NOT NULL,
  `created_by` enum('User','Client') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores session information between clients and adminpanel';

/*Table structure for table `cuisine_types` */

DROP TABLE IF EXISTS `cuisine_types`;

CREATE TABLE `cuisine_types` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=latin1 COMMENT='Stores all local cuisine types you can add to a location';

/*Table structure for table `employee_details` */

DROP TABLE IF EXISTS `employee_details`;

CREATE TABLE `employee_details` (
  `employee_details_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT NULL,
  `type` enum('LOA','Certificate','Review','Incident','Warning','Accident','Promotion','Position','Termination','Separation','Warning-Verbal','Warning-Written','Warning-Final') DEFAULT NULL,
  `approved` enum('Yes','No') DEFAULT 'No',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` text,
  `comments` varchar(255) DEFAULT NULL,
  `scanned_copy1` varchar(100) DEFAULT NULL,
  `scanned_copy2` varchar(100) DEFAULT NULL,
  `reminder_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`employee_details_id`),
  KEY `employee_details_emp_fk_idx` (`employee_id`),
  KEY `employee_details_loc_idx` (`location_id`),
  CONSTRAINT `employee_details_emp_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `employee_details_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1534 DEFAULT CHARSET=latin1 COMMENT='Stores important employee events (pomotion, review, etc)';

/*Table structure for table `employee_forms` */

DROP TABLE IF EXISTS `employee_forms`;

CREATE TABLE `employee_forms` (
  `employee_forms_id` int(11) NOT NULL AUTO_INCREMENT,
  `locaition_id` int(8) NOT NULL,
  `employee_id` int(8) NOT NULL,
  `status` enum('Issued','Received','Verified','Pending','Completed') NOT NULL,
  `type` int(11) DEFAULT NULL,
  `comments` varchar(256) DEFAULT NULL,
  `received_by` int(11) DEFAULT NULL,
  `received_notes` varchar(256) DEFAULT NULL,
  `verified_by` int(11) DEFAULT NULL,
  `verified_notes` varchar(256) DEFAULT NULL,
  `reminder_notes` varchar(256) DEFAULT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `located` varchar(256) DEFAULT NULL,
  `image_front` tinytext,
  `image_back` tinytext,
  `exp_date_doc` datetime DEFAULT NULL,
  `scanned_copy` varchar(80) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`employee_forms_id`),
  UNIQUE KEY `employee_forms_id_UNIQUE` (`employee_forms_id`),
  KEY `employee_forms_locemp_idx` (`locaition_id`,`employee_id`),
  KEY `employee_forms_loc_idx` (`locaition_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `employee_lastlogin` */

DROP TABLE IF EXISTS `employee_lastlogin`;

CREATE TABLE `employee_lastlogin` (
  `el_id` int(11) NOT NULL AUTO_INCREMENT,
  `el_location_id` int(8) NOT NULL,
  `el_emp_id` int(11) DEFAULT NULL,
  `el_ip` varchar(20) NOT NULL,
  `type` enum('Sign In','Sign Out','Sign In Failure') NOT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `longitude` varchar(12) DEFAULT NULL,
  `latitude` varchar(12) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(20) DEFAULT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`el_id`),
  KEY `employee_lastlogin_loc_fk_idx` (`el_location_id`),
  KEY `employee_lastlogin_emp_idx` (`el_emp_id`),
  KEY `employee_lastlogin_ip_idx` (`el_ip`),
  KEY `employee_lastlogin_emp_ip_idx` (`el_ip`,`el_emp_id`),
  CONSTRAINT `employee_lastlogin_emp` FOREIGN KEY (`el_emp_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `employee_lastlogin_loc_fk` FOREIGN KEY (`el_location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=682871 DEFAULT CHARSET=latin1 COMMENT='Stores records of every time an employee logs in';

/*Table structure for table `employee_master_chefedin_services` */

DROP TABLE IF EXISTS `employee_master_chefedin_services`;

CREATE TABLE `employee_master_chefedin_services` (
  `chefedin_services_id` int(11) NOT NULL AUTO_INCREMENT,
  `empmaster_id` int(11) NOT NULL,
  `status` enum('Active','Inactive','Expired') NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `service_type` varchar(45) NOT NULL,
  `rates` varchar(64) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`chefedin_services_id`),
  KEY `emp_master_chefedin_srv_empmaster_fk_idx` (`empmaster_id`),
  CONSTRAINT `emp_master_chefedin_srv_empmaster_fk` FOREIGN KEY (`empmaster_id`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='Services provided by an employee master for ChefedIN';

/*Table structure for table `employee_master_job_inquiries` */

DROP TABLE IF EXISTS `employee_master_job_inquiries`;

CREATE TABLE `employee_master_job_inquiries` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `sent_by_type` enum('Location','Employee Master','Corporate') NOT NULL,
  `emp_master_id` int(11) NOT NULL,
  `location_id` int(8) DEFAULT NULL,
  `location_employee_id` int(11) DEFAULT NULL,
  `corporate_id` int(8) DEFAULT NULL,
  `sent_datetime` datetime NOT NULL,
  `subject` varchar(64) NOT NULL,
  `location_job_id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `read` enum('Yes','No') NOT NULL,
  `read_date` date NOT NULL,
  `read_time` time NOT NULL,
  `reply` enum('Yes','No') DEFAULT 'No',
  `share_resume` enum('Yes','No') DEFAULT 'No',
  `share_image` enum('Yes','No') DEFAULT 'No',
  `share_address` enum('Yes','No') DEFAULT 'No',
  `share_sex` enum('Yes','No') DEFAULT 'No',
  `share_dob` enum('Yes','No') DEFAULT 'No',
  `share_neighborhood` enum('Yes','No') DEFAULT 'No',
  `share_telephone` enum('Yes','No') DEFAULT 'No',
  `share_mobile` enum('Yes','No') DEFAULT 'No',
  `share_activites` enum('Yes','No') DEFAULT 'No',
  `share_education` enum('Yes','No') DEFAULT 'No',
  `share_competencies` enum('Yes','No') DEFAULT 'No',
  `share_languages` enum('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`id`),
  KEY `empmast_job_inq_empmaster_fk_idx` (`emp_master_id`),
  KEY `empmast_job_inq_emp_fk_idx` (`location_employee_id`),
  KEY `empmast_job_inq_loc_fk_idx` (`location_id`),
  KEY `empmast_job_inq_loc_job_fk_idx` (`location_job_id`),
  CONSTRAINT `empmast_job_inq_emp_fk` FOREIGN KEY (`location_employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `empmast_job_inq_empmaster_fk` FOREIGN KEY (`emp_master_id`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `empmast_job_inq_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `empmast_job_inq_loc_job_fk` FOREIGN KEY (`location_job_id`) REFERENCES `location_jobs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Inquiries sent by an emp master for a locations job posting';

/*Table structure for table `employee_master_location_chefedin` */

DROP TABLE IF EXISTS `employee_master_location_chefedin`;

CREATE TABLE `employee_master_location_chefedin` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `sent_by_type` enum('Location','Employee Master') NOT NULL,
  `emp_master_id` int(11) NOT NULL,
  `location_id` int(8) NOT NULL,
  `location_employee_id` int(11) DEFAULT NULL,
  `sent_datetime` datetime NOT NULL,
  `subject` varchar(64) NOT NULL,
  `message` longtext NOT NULL,
  `read` enum('Yes','No') NOT NULL,
  `read_date` date NOT NULL,
  `read_time` time NOT NULL,
  `reply` enum('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`id`),
  KEY `empmaster_lob_chef_empmaster_fk_idx` (`emp_master_id`),
  KEY `empmaster_loc_chef_emp_fk_idx` (`location_employee_id`),
  KEY `empmaster_loc_chef_loc_fk_idx` (`location_id`),
  CONSTRAINT `empmaster_lob_chef_empmaster_fk` FOREIGN KEY (`emp_master_id`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `empmaster_loc_chef_emp_fk` FOREIGN KEY (`location_employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `empmaster_loc_chef_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Chefedin messages sent between an emp master and a location';

/*Table structure for table `employee_master_location_storepoint` */

DROP TABLE IF EXISTS `employee_master_location_storepoint`;

CREATE TABLE `employee_master_location_storepoint` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `sent_by_type` enum('Location','Employee Master') NOT NULL,
  `emp_master_id` int(11) NOT NULL,
  `location_id` int(8) NOT NULL,
  `location_employee_id` int(11) DEFAULT NULL,
  `sent_datetime` datetime NOT NULL,
  `subject` varchar(64) NOT NULL,
  `message` longtext NOT NULL,
  `read` enum('Yes','No') NOT NULL,
  `read_date` date NOT NULL,
  `read_time` time NOT NULL,
  `reply` enum('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`id`),
  KEY `empmaster_loc_store_empmaster_fk_idx` (`emp_master_id`),
  KEY `empmaster_loc_store_emp_fk_idx` (`location_employee_id`),
  KEY `empmaster_loc_store_loc_fk_idx` (`location_id`),
  CONSTRAINT `empmaster_loc_store_emp_fk` FOREIGN KEY (`location_employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `empmaster_loc_store_empmaster_fk` FOREIGN KEY (`emp_master_id`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `empmaster_loc_store_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1 COMMENT='StorePoint messsages sent between emp master and a location';

/*Table structure for table `employee_master_location_stylistfn` */

DROP TABLE IF EXISTS `employee_master_location_stylistfn`;

CREATE TABLE `employee_master_location_stylistfn` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `sent_by_type` enum('Client','Employee Master','Location') NOT NULL,
  `emp_master_id` int(11) NOT NULL,
  `location_id` int(8) DEFAULT NULL,
  `location_employee_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `sent_datetime` datetime NOT NULL,
  `subject` varchar(64) NOT NULL,
  `message` longtext,
  `read` enum('Yes','No') NOT NULL DEFAULT 'No',
  `read_date` date DEFAULT NULL,
  `read_time` time DEFAULT NULL,
  `reply` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`),
  KEY `empmaster_loc_styl_empmaster_fk_idx` (`emp_master_id`),
  KEY `empmaster_loc_styl_emp_fk_idx` (`location_employee_id`),
  KEY `empmaster_loc_styl_loc_fk_idx` (`location_id`),
  CONSTRAINT `empmaster_loc_styl_emp_fk` FOREIGN KEY (`location_employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `empmaster_loc_styl_empmaster_fk` FOREIGN KEY (`emp_master_id`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `empmaster_loc_styl_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='StylistFN messages sent between an emp master and a location';

/*Table structure for table `employee_master_locations` */

DROP TABLE IF EXISTS `employee_master_locations`;

CREATE TABLE `employee_master_locations` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `empmaster_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `Status` enum('Active','Inactive') DEFAULT 'Active',
  `Created_by` varchar(255) NOT NULL,
  `Created_on` varchar(45) NOT NULL,
  `Created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_master_locations_loc_fk` (`location_id`),
  KEY `employee_master_locations_emp_fk` (`empmaster_id`),
  CONSTRAINT `employee_master_locations_emp_fk` FOREIGN KEY (`empmaster_id`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employee_master_locations_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Locations that an employee master is linked with';

/*Table structure for table `employee_master_ping` */

DROP TABLE IF EXISTS `employee_master_ping`;

CREATE TABLE `employee_master_ping` (
  `ping_Id` int(8) NOT NULL AUTO_INCREMENT,
  `Empmaster_id` int(8) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `Ping_type` enum('Signin','Signout','Refresh','Signinfailure') DEFAULT NULL,
  `Datetime` datetime DEFAULT NULL,
  `Longitude` varchar(12) DEFAULT NULL,
  `Latitude` varchar(12) DEFAULT NULL,
  `Created_on` varchar(45) NOT NULL,
  `created_by` varchar(20) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`ping_Id`),
  KEY `empmaster_ping_id_idx` (`Empmaster_id`),
  CONSTRAINT `empmaster_ping_id` FOREIGN KEY (`Empmaster_id`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9230 DEFAULT CHARSET=latin1 COMMENT='Last log activity for an employee';

/*Table structure for table `employee_messages` */

DROP TABLE IF EXISTS `employee_messages`;

CREATE TABLE `employee_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(12) NOT NULL,
  `omnivore_employees_id` varchar(12) DEFAULT NULL,
  `location_id` int(8) NOT NULL,
  `Message_type` varchar(45) NOT NULL,
  `entered_by_emp_id` varchar(60) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `Subject` varchar(45) NOT NULL,
  `message` text NOT NULL,
  `seen_date` date NOT NULL,
  `seen_time` time NOT NULL,
  `readd` enum('yes','no') NOT NULL DEFAULT 'no',
  `order_id` int(11) NOT NULL,
  `Request_off_status` enum('Approved','Declined') DEFAULT NULL,
  `Request_Reason_declined` varchar(60) DEFAULT NULL,
  `Request_manager_employee_id` int(11) DEFAULT NULL,
  `Request_processed_datetime` datetime DEFAULT NULL,
  `priority` varchar(45) NOT NULL DEFAULT 'Low',
  `entered_by_corp_id` varchar(60) DEFAULT NULL,
  `thread_id` int(11) DEFAULT NULL,
  `sent_by_client` int(11) DEFAULT NULL,
  `sent_to_client` int(11) DEFAULT NULL,
  `sent_to_corp` int(11) DEFAULT NULL,
  `message_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_messages_loc_fk` (`location_id`),
  KEY `employee_messages_csent_idx` (`sent_to_corp`,`readd`),
  CONSTRAINT `employee_messages_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25907 DEFAULT CHARSET=utf8 COMMENT='Messages sent to or from an employee';

/*Table structure for table `employee_payroll` */

DROP TABLE IF EXISTS `employee_payroll`;

CREATE TABLE `employee_payroll` (
  `Employee_Payroll_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `emp_id` varchar(12) NOT NULL,
  `Check_date` date DEFAULT NULL,
  `Check_number` varchar(45) DEFAULT NULL,
  `check_amount` decimal(14,2) DEFAULT NULL,
  `check_payroll_period` enum('Monthly','Semi-Monthly','Bi-Weekly','Weekly','Daily') NOT NULL,
  `check_payroll_thru_date` date DEFAULT NULL,
  `check_status` enum('Paid','Cancelled','Reconciled','Pending') NOT NULL,
  `check_source` varchar(80) NOT NULL,
  `check_process_type` enum('Manual','Electronic') DEFAULT 'Manual',
  `check_deduction` decimal(14,2) DEFAULT NULL,
  `check_taxes` decimal(14,2) DEFAULT NULL,
  `tips_commision` decimal(14,2) DEFAULT NULL,
  `total_regular` decimal(14,2) DEFAULT NULL,
  `total_break` decimal(14,2) DEFAULT NULL,
  `total_overtime1` decimal(14,2) DEFAULT NULL,
  `total_overtime2` decimal(14,2) DEFAULT NULL,
  `total_sick` decimal(14,2) DEFAULT NULL,
  `total_vacation` decimal(14,2) DEFAULT NULL,
  `total_pto` decimal(14,2) DEFAULT NULL,
  `total_PRM` decimal(14,2) DEFAULT NULL,
  `gross_amount` decimal(14,2) DEFAULT NULL,
  `tax_federal_withhold` decimal(14,2) DEFAULT NULL,
  `tax_social_security_withhold` decimal(14,2) DEFAULT NULL,
  `tax_medicare_withhold` decimal(14,2) DEFAULT NULL,
  `tax_state_withhold` decimal(14,2) DEFAULT NULL,
  `other_deductions` decimal(14,2) DEFAULT NULL,
  `total_tips` decimal(14,2) DEFAULT NULL,
  `reconciled_datetime` datetime DEFAULT NULL,
  `reconciled_employee_id` int(11) DEFAULT NULL,
  `cancelled_datetime` datetime DEFAULT NULL,
  `cancelled_employee_id` varchar(45) DEFAULT NULL,
  `created_by` int(8) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`Employee_Payroll_id`),
  KEY `emp_payroll_emp_idx` (`employee_id`),
  KEY `emp_payroll_loc_idx` (`location_id`),
  CONSTRAINT `emp_payroll_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `emp_payroll_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=latin1 COMMENT='Stores past pay dates and amounts for an employee';

/*Table structure for table `employee_payroll_details` */

DROP TABLE IF EXISTS `employee_payroll_details`;

CREATE TABLE `employee_payroll_details` (
  `employee_payroll_details_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_payroll_id` int(11) NOT NULL,
  `type_of_payroll` enum('Pay','Deduction','Taxes','Tips','tips_commision','check_deduction','check_taxes') NOT NULL,
  `hours` float DEFAULT NULL,
  `original_hours` float DEFAULT NULL,
  `rate` decimal(14,2) DEFAULT NULL,
  `amount` decimal(14,2) DEFAULT NULL,
  `pay_type` enum('Regular','Overtime','Overtime2','Vacation','Sick','Suspended With Pay','PTO','PRM','BRV','HOL','Jury Duty') DEFAULT NULL,
  `pay_department` varchar(64) DEFAULT NULL,
  `pay_position` varchar(32) DEFAULT NULL,
  `deduction_description` varchar(45) DEFAULT NULL,
  `tax_description` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`employee_payroll_details_id`)
) ENGINE=InnoDB AUTO_INCREMENT=267 DEFAULT CHARSET=utf8;

/*Table structure for table `employee_payroll_verification_temp` */

DROP TABLE IF EXISTS `employee_payroll_verification_temp`;

CREATE TABLE `employee_payroll_verification_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `status` varchar(45) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `department` varchar(45) DEFAULT NULL,
  `period` varchar(45) DEFAULT NULL,
  `salary` varchar(45) DEFAULT NULL,
  `reguler` varchar(45) DEFAULT NULL,
  `other_time` varchar(45) DEFAULT NULL,
  `overtime` varchar(80) DEFAULT NULL,
  `sick` varchar(80) DEFAULT NULL,
  `pto` varchar(80) DEFAULT NULL,
  `vacation` varchar(80) DEFAULT NULL,
  `total` varchar(45) DEFAULT NULL,
  `hourly_rate` varchar(45) DEFAULT NULL,
  `rate` varchar(80) DEFAULT NULL,
  `prm` varchar(45) DEFAULT NULL,
  `tips` varchar(45) DEFAULT NULL,
  `deduction` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `check_number` varchar(45) DEFAULT NULL,
  `paycheck` varchar(45) DEFAULT NULL,
  `paid_amount` varchar(45) DEFAULT NULL,
  `amount_due` varchar(45) DEFAULT NULL,
  `check_status` varchar(45) DEFAULT NULL,
  `prmhrs` varchar(45) DEFAULT NULL,
  `rate_avi` enum('Yes','No') DEFAULT 'No',
  `last_datetime` datetime DEFAULT NULL,
  `department_data` longtext,
  `payroll_other_data` longtext,
  `brv_total` varchar(45) DEFAULT NULL,
  `hol_total` varchar(45) DEFAULT NULL,
  `jd_total` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=390 DEFAULT CHARSET=latin1;

/*Table structure for table `employee_rates` */

DROP TABLE IF EXISTS `employee_rates`;

CREATE TABLE `employee_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `start_date` varchar(45) DEFAULT NULL,
  `end_date` varchar(45) DEFAULT NULL,
  `payroll_period` enum('Monthly','Semi-Monthly','Bi-Weekly','Weekly','Daily') DEFAULT NULL,
  `position` varchar(32) DEFAULT NULL,
  `hourly_rate` float DEFAULT NULL,
  `monthly_rate` float DEFAULT NULL,
  `annual_rate` float DEFAULT NULL,
  `type_of_pay` enum('Hourly','Salary','Commission','Other') DEFAULT 'Hourly',
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `emp_rates_emp_idx` (`emp_id`),
  CONSTRAINT `emp_rates_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=844 DEFAULT CHARSET=latin1 COMMENT='Stores salary information for an employee';

/*Table structure for table `employee_request` */

DROP TABLE IF EXISTS `employee_request`;

CREATE TABLE `employee_request` (
  `emp_request_id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `request_type` enum('Dayoff','Leave','Sick','Vacation','Other') NOT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `startdate` date NOT NULL,
  `starttime` time NOT NULL,
  `enddate` date NOT NULL,
  `endtime` time NOT NULL,
  `subject` varchar(80) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `thread_id` int(11) DEFAULT NULL,
  `read` enum('Yes','No') NOT NULL,
  `all_day_flexible` enum('Yes','No') DEFAULT 'No',
  `read_by_emp_id` int(11) DEFAULT NULL,
  `seen_datetime` datetime DEFAULT NULL,
  `request_off_status` enum('Pending','Accepted','Declined','Cancelled','Modified') DEFAULT NULL,
  `request_reason_declined` varchar(255) DEFAULT NULL,
  `request_processed_by_emp` int(11) DEFAULT NULL,
  `request_processed_datetime` datetime DEFAULT NULL,
  `loc_type` enum('AllLoc','Single') DEFAULT 'Single',
  `request_dow` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`emp_request_id`),
  KEY `emp_request_emp_idx` (`emp_id`),
  KEY `emp_request_location_idx` (`location_id`),
  KEY `emp_request_read_by_emp_idx` (`read_by_emp_id`),
  KEY `emp_request_processed_emp_idx` (`request_processed_by_emp`),
  CONSTRAINT `emp_request_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `emp_request_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `emp_request_processed_emp` FOREIGN KEY (`request_processed_by_emp`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `emp_request_read_by_emp` FOREIGN KEY (`read_by_emp_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=287 DEFAULT CHARSET=utf8 COMMENT='Stores all employee requests related to time and attendance';

/*Table structure for table `employee_request_allotment` */

DROP TABLE IF EXISTS `employee_request_allotment`;

CREATE TABLE `employee_request_allotment` (
  `employee_request_allotment_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `department` int(8) NOT NULL,
  `allotment_date` date NOT NULL,
  `original_allotment` int(4) NOT NULL,
  `allotment` int(4) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`employee_request_allotment_id`),
  UNIQUE KEY `employee_request_allotment_id_UNIQUE` (`employee_request_allotment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='This table hold the allotments defined by the location';

/*Table structure for table `employees` */

DROP TABLE IF EXISTS `employees`;

CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(12) NOT NULL,
  `external_id` varchar(32) DEFAULT NULL COMMENT 'Reference to External ID like QuickBooks ID.',
  `location_id` int(8) NOT NULL,
  `status` enum('A','S','D','L','T','Applicant','Interviewed','Not Hired','Boarding','Do Not Hire','Probation','Inactive','Transfer','lay-off') NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `nickname` varchar(45) DEFAULT NULL,
  `password` varchar(32) NOT NULL,
  `pin` varchar(10) NOT NULL,
  `email` varchar(64) NOT NULL,
  `telephone` varchar(32) NOT NULL,
  `country` int(4) DEFAULT NULL,
  `address` varchar(64) NOT NULL,
  `address2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) NOT NULL,
  `image` tinytext NOT NULL,
  `date_started` date NOT NULL,
  `department` varchar(64) NOT NULL,
  `position` varchar(32) NOT NULL,
  `manager` enum('Yes','No') NOT NULL DEFAULT 'No',
  `manager_name` varchar(50) DEFAULT NULL,
  `is_supervisor` enum('Yes','No') DEFAULT 'No',
  `supervisor` varchar(32) NOT NULL,
  `ssn` varchar(11) DEFAULT NULL,
  `payroll_period` enum('Daily','Weekly','Bi-Weekly','Monthly','Semi-Monthly','Quarterly','Semi-Annually','Annually','Miscellaneous','Non-paid') NOT NULL,
  `type_of_pay` enum('Hourly','Salary','Commission','Other') DEFAULT 'Hourly',
  `hourly_rate` float NOT NULL,
  `monthly_rate` float NOT NULL,
  `annual_rate` float NOT NULL,
  `position2` varchar(32) DEFAULT NULL,
  `position2_rate` float DEFAULT NULL,
  `position2_department` varchar(64) DEFAULT NULL,
  `position3` varchar(32) DEFAULT NULL,
  `position3_department` varchar(64) DEFAULT NULL,
  `position3_rate` float DEFAULT NULL,
  `position4` varchar(32) DEFAULT NULL,
  `position4_department` varchar(64) DEFAULT NULL,
  `position4_rate` float DEFAULT NULL,
  `sex` enum('M','F') NOT NULL,
  `dob` date DEFAULT NULL,
  `id_type` varchar(32) NOT NULL,
  `id_number` varchar(32) NOT NULL,
  `employment_type` enum('Full Time','Contractor','Part Time','Intern','Seasonal/Temp') NOT NULL,
  `provider` enum('','Allegiance Staffing','Express Employment','On Target Staffing') DEFAULT '',
  `contract` enum('yes','no') NOT NULL DEFAULT 'no',
  `contract_type` varchar(32) NOT NULL,
  `contract_start_date` date NOT NULL,
  `contract_end_date` date NOT NULL,
  `termination_date` date DEFAULT NULL,
  `termination_reason` varchar(256) DEFAULT NULL,
  `contact_person` varchar(32) NOT NULL,
  `contact_phone` varchar(32) NOT NULL,
  `employee_form` enum('yes','no') NOT NULL DEFAULT 'no',
  `background_check` enum('yes','no') NOT NULL DEFAULT 'no',
  `medical_report` enum('yes','no') NOT NULL DEFAULT 'no',
  `work_attestation` enum('yes','no') NOT NULL DEFAULT 'no',
  `salary_attestation` enum('yes','no') NOT NULL DEFAULT 'no',
  `pos_update_other_server` enum('yes','no') NOT NULL DEFAULT 'no',
  `pos_view_other_server` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_pay_fields` enum('yes','no') DEFAULT 'no',
  `access_profile` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_employees_setup` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_pos` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_pos_admin` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_pos_reports` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_pos_payments` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_pos_cash` enum('yes','no') NOT NULL DEFAULT 'no',
  `pos_server` enum('yes','no') NOT NULL DEFAULT 'no',
  `pos_cashier` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_restaurant_register` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_restaurant_zones` enum('Yes','No') NOT NULL DEFAULT 'No',
  `Allow_adjustment` enum('yes','no') NOT NULL DEFAULT 'yes',
  `Allow_discount` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_void` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_restaurant_finances` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_handheld` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_winepad` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_discount` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_petty_cash` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_cash_mngt_cashout` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_cash_mngt_NI_mngt` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_visualprep` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_digital` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_barpoint` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_tablereview` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_restaurant_setup` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_reservation_setup` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_reservations` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_hr_management` enum('Yes','No') DEFAULT 'No',
  `access_hotel` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_hotel_pms` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_hotel_reports` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_hotel_setup` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_hotel_admin` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_hotel_reservation` enum('Yes','No') NOT NULL DEFAULT 'No',
  `housekeeper` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_minibar` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_quick_setup` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_register` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_retail_reports` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_retail_setup` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_retail_admin` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_timeattendance` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_ta_punching` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_ta_planning` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_ta_report` varchar(255) NOT NULL,
  `access_ta_admin` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_ta_schedule` enum('yes','no') NOT NULL DEFAULT 'no',
  `view_ta_schedule` enum('yes','no') DEFAULT 'no',
  `access_ta_payroll` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_ta_controller` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_ta_messaging` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_ta_emp_schedule` enum('yes','no') NOT NULL DEFAULT 'no',
  `department_allowed` varchar(500) DEFAULT NULL,
  `access_backoffice` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_bo_purchases` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_bo_inventory` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_bo_payables` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_bo_orders` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_bo_data_collector` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_bo_reports` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_bo_dashboard` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_bo_check_list` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_bo_gift_card` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_bo_receivables` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_bo_general_ledger` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_bo_setup` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_chef` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_inventory` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_expensetab` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_meetingroom` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_crm` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_crm_mailing` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_customer_setup` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_quality` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_concierge` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_crs` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_event_setup` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_advertisement` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_dispatch` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_drivers_setup` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_training` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_training_setup` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_staffpoint` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_storepoint` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_chefedin` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_stylistfn` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_expensetab_setup` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_spa` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_spa_setup` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_datapoint` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_datapoint_setup` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_datapoint_setup_devices` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_lounges` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_lounges_admin` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_lounges_reports` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_lounges_virtra` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_lounges_service` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_business_intelligence` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_manual_payment` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_sales_register` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_retail_adjust_surcharge` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_retail_commisions` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_retail_item_breakdown` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_retail_inventory_link` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_ticketing` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_controlpoint` enum('Yes','No') NOT NULL DEFAULT 'No',
  `use_availability` enum('Yes','No') DEFAULT 'No',
  `spa_service_provider` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_kioskpoint` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_olopoint` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_tablepoint` enum('Yes','No') DEFAULT 'No',
  `Rfid_status` enum('Active','Inactive') DEFAULT 'Inactive',
  `Rfid_number` varchar(16) DEFAULT NULL,
  `Rfid_terminal` int(11) DEFAULT NULL,
  `Rfid_created_by` int(11) DEFAULT NULL,
  `Rfid_created_on` varchar(45) DEFAULT NULL,
  `Rfid_created_datetime` datetime DEFAULT NULL,
  `retail_commission` enum('Yes','No') DEFAULT 'No',
  `retail_commission_pct` decimal(4,2) DEFAULT NULL,
  `retail_commission_quota` decimal(14,2) DEFAULT NULL,
  `payroll_marital_status` enum('Single or Dual Income Married','Married (One Income)','Head of Household') DEFAULT NULL,
  `payroll_num_of_allowances` tinyint(4) DEFAULT NULL,
  `payroll_alloted_break_and_lunch_period` enum('0:15','0:30','0:45','1:00','1:15','1:30') DEFAULT NULL,
  `payroll_punch_grace_period` enum('05','08','15','18','30') DEFAULT NULL,
  `payroll_pto_manage` enum('Yes','No') DEFAULT NULL,
  `pto_start_use_date` date DEFAULT NULL,
  `payroll_starting_pto` varchar(10) DEFAULT NULL,
  `payroll_starting_pto_date` datetime DEFAULT NULL,
  `payroll_pto_accrual` varchar(10) DEFAULT NULL,
  `payroll_pto_cap` varchar(10) DEFAULT NULL,
  `payroll_pto_rollover_max` varchar(10) DEFAULT NULL,
  `pto_years1` varchar(50) DEFAULT NULL,
  `pto_years2` varchar(50) DEFAULT NULL,
  `pto_years3` varchar(50) DEFAULT NULL,
  `pto_basis1` varchar(50) DEFAULT NULL,
  `pto_basis2` varchar(50) DEFAULT NULL,
  `pto_basis3` varchar(50) DEFAULT NULL,
  `payroll_vacation_manage` enum('Yes','No') DEFAULT NULL,
  `vacation_start_use_date` date DEFAULT NULL,
  `payroll_starting_vacation` varchar(10) DEFAULT NULL,
  `payroll_vacation_accrual` varchar(45) DEFAULT NULL,
  `payroll_vacation_cap` varchar(10) DEFAULT NULL,
  `payroll_vacation_rollover_max` varchar(10) DEFAULT NULL,
  `starting_vacation_date` date DEFAULT NULL,
  `payroll_sick_manage` enum('Yes','No') DEFAULT NULL,
  `sick_start_use_date` date DEFAULT NULL,
  `payroll_starting_sick` varchar(10) DEFAULT NULL,
  `payroll_sick_accrual` varchar(10) DEFAULT NULL,
  `payroll_sick_cap` varchar(10) DEFAULT NULL,
  `payroll_sick_rollover_max` varchar(10) DEFAULT NULL,
  `payroll_night_pay` enum('Yes','No') DEFAULT 'No',
  `payroll_night_pay_rate` float DEFAULT NULL,
  `payroll_night_start_time` time DEFAULT NULL,
  `payroll_night_end_time` time DEFAULT NULL,
  `payroll_night_pay_include_for_sick` enum('Yes','No') DEFAULT 'No',
  `clover_employee_id` varchar(45) DEFAULT NULL,
  `clover_employee_pin` varchar(45) DEFAULT NULL,
  `clover_role` enum('EMPLOYEE','MANAGER','ADMIN') DEFAULT 'EMPLOYEE',
  `hr_apply_date` datetime DEFAULT NULL,
  `hr_apply_review_date` datetime DEFAULT NULL,
  `hr_apply_review_by` int(11) DEFAULT NULL,
  `hr_apply_notes` tinytext,
  `hr_considered` enum('Yes','No') DEFAULT NULL,
  `hr_interview1_date` datetime DEFAULT NULL,
  `hr_interview1_by` int(11) DEFAULT NULL,
  `hr_interview1_notes` tinytext,
  `hr_interview1_approved` enum('Yes','No') DEFAULT NULL,
  `hr_interview2_date` datetime DEFAULT NULL,
  `hr_interview2_by` int(11) DEFAULT NULL,
  `hr_interview2_notes` tinytext,
  `hr_interview2_approved` enum('Yes','No') DEFAULT NULL,
  `hr_offer` enum('Yes','No') DEFAULT NULL,
  `hr_offer_amount` varchar(64) DEFAULT NULL,
  `hr_offer_notes` tinytext,
  `hr_applicant_accept` enum('Yes','No') DEFAULT NULL,
  `hr_applicant_startdate` datetime DEFAULT NULL,
  `boarding_hired_by` int(11) DEFAULT NULL,
  `boarding_datetime` datetime DEFAULT NULL,
  `boarding_notes` varchar(256) DEFAULT NULL,
  `boarding_validate_id` enum('Yes','No') DEFAULT NULL,
  `boarding_validate_ss` enum('Yes','No') DEFAULT NULL,
  `boarding_ic_verify` enum('Yes','No') DEFAULT NULL,
  `boarding_ic_verify_date` datetime DEFAULT NULL,
  `boarding_temp_date` datetime DEFAULT NULL,
  `boarding_hr_reminder` datetime DEFAULT NULL,
  `boarding_hr_emp` int(11) DEFAULT NULL,
  `allow_view_pay` enum('view_hourly','view_all') DEFAULT NULL,
  `holidays` varchar(500) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created__on` varchar(100) NOT NULL,
  `created__datetime` datetime NOT NULL,
  `last_on` varchar(45) NOT NULL,
  `last_by` varchar(45) NOT NULL,
  `last_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employees_loc_fk` (`location_id`),
  KEY `employees_country_idx` (`country`),
  KEY `clover_employee_id` (`clover_employee_id`),
  KEY `emp_first_name_idx` (`first_name`),
  KEY `emp_last_name_idx` (`last_name`),
  KEY `emp_emp_id_idx` (`emp_id`),
  KEY `emp_manager_idx` (`manager`),
  KEY `emp_access_timeattandence_idx` (`access_timeattendance`),
  KEY `emp_access_pos_idx` (`access_pos`),
  KEY `emp_access_pos_admin_idx` (`access_pos_admin`),
  KEY `emp_access_pos_reports_idx` (`access_pos_reports`),
  KEY `emp_access_pos_payments_idx` (`access_pos_payments`),
  KEY `emp_access_pos_cash_idx` (`access_pos_cash`),
  KEY `emp_pos_server_idx` (`pos_server`),
  KEY `emp_pos_cashier_idx` (`pos_cashier`),
  KEY `emp_allow_adjustment_idx` (`Allow_adjustment`),
  KEY `emp_allow_discount_idx` (`Allow_discount`),
  KEY `emp_allow_void_idx` (`allow_void`),
  KEY `emp_access_register_idx` (`access_register`),
  KEY `emp_access_retail_reports_idx` (`access_retail_reports`),
  KEY `emp_access_retail_admin_idx` (`access_retail_admin`),
  KEY `employees_pin_idx` (`pin`),
  CONSTRAINT `employees_country` FOREIGN KEY (`country`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `employees_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13212 DEFAULT CHARSET=latin1 COMMENT='Main profile information for an employee';

/*Table structure for table `employees_audit` */

DROP TABLE IF EXISTS `employees_audit`;

CREATE TABLE `employees_audit` (
  `employees_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) DEFAULT NULL,
  `emp_id` varchar(12) DEFAULT NULL,
  `external_id` varchar(32) DEFAULT NULL COMMENT 'Reference to External ID like QuickBooks ID.',
  `location_id` int(8) DEFAULT NULL,
  `status` enum('A','S','D','L','T','Applicant','Interviewed','Not Hired','Boarding','Do Not Hire','Probation','Inactive','Transfer','lay-off') DEFAULT NULL,
  `first_name` varchar(32) DEFAULT NULL,
  `last_name` varchar(32) DEFAULT NULL,
  `nickname` varchar(45) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `pin` varchar(10) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `country` int(4) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `address2` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) DEFAULT NULL,
  `image` tinytext,
  `date_started` date DEFAULT NULL,
  `department` varchar(64) DEFAULT NULL,
  `position` varchar(32) DEFAULT NULL,
  `manager` enum('Yes','No') DEFAULT NULL,
  `manager_name` varchar(50) DEFAULT NULL,
  `is_supervisor` enum('Yes','No') DEFAULT NULL,
  `supervisor` varchar(32) DEFAULT NULL,
  `ssn` varchar(11) DEFAULT NULL,
  `payroll_period` enum('Daily','Weekly','Bi-Weekly','Monthly','Semi-Monthly','Quarterly','Semi-Annually','Annually','Miscellaneous','Non-paid') DEFAULT NULL,
  `type_of_pay` enum('Hourly','Salary','Commission','Other') DEFAULT NULL,
  `hourly_rate` float DEFAULT NULL,
  `monthly_rate` float DEFAULT NULL,
  `annual_rate` float DEFAULT NULL,
  `position2` varchar(32) DEFAULT NULL,
  `position2_rate` float DEFAULT NULL,
  `position2_department` varchar(64) DEFAULT NULL,
  `position3` varchar(32) DEFAULT NULL,
  `position3_department` varchar(64) DEFAULT NULL,
  `position3_rate` float DEFAULT NULL,
  `position4` varchar(32) DEFAULT NULL,
  `position4_department` varchar(64) DEFAULT NULL,
  `position4_rate` float DEFAULT NULL,
  `sex` enum('M','F') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `id_type` varchar(32) DEFAULT NULL,
  `id_number` varchar(32) DEFAULT NULL,
  `employment_type` enum('Full Time','Contractor','Part Time','Intern','Seasonal/Temp') DEFAULT NULL,
  `contract` enum('yes','no') DEFAULT NULL,
  `contract_type` varchar(32) DEFAULT NULL,
  `contract_start_date` date DEFAULT NULL,
  `contract_end_date` date DEFAULT NULL,
  `termination_date` date DEFAULT NULL,
  `termination_reason` varchar(256) DEFAULT NULL,
  `contact_person` varchar(32) DEFAULT NULL,
  `contact_phone` varchar(32) DEFAULT NULL,
  `employee_form` enum('yes','no') DEFAULT NULL,
  `background_check` enum('yes','no') DEFAULT NULL,
  `medical_report` enum('yes','no') DEFAULT NULL,
  `work_attestation` enum('yes','no') DEFAULT NULL,
  `salary_attestation` enum('yes','no') DEFAULT NULL,
  `pos_update_other_server` enum('yes','no') DEFAULT NULL,
  `pos_view_other_server` enum('yes','no') DEFAULT NULL,
  `access_pay_fields` enum('yes','no') DEFAULT NULL,
  `access_profile` enum('yes','no') DEFAULT NULL,
  `access_employees_setup` enum('yes','no') DEFAULT NULL,
  `access_pos` enum('yes','no') DEFAULT NULL,
  `access_pos_admin` enum('yes','no') DEFAULT NULL,
  `access_pos_reports` enum('yes','no') DEFAULT NULL,
  `access_pos_payments` enum('yes','no') DEFAULT NULL,
  `access_pos_cash` enum('yes','no') DEFAULT NULL,
  `pos_server` enum('yes','no') DEFAULT NULL,
  `pos_cashier` enum('yes','no') DEFAULT NULL,
  `access_restaurant_register` enum('Yes','No') DEFAULT NULL,
  `access_restaurant_zones` enum('Yes','No') DEFAULT NULL,
  `Allow_adjustment` enum('yes','no') DEFAULT NULL,
  `Allow_discount` enum('yes','no') DEFAULT NULL,
  `allow_void` enum('yes','no') DEFAULT NULL,
  `access_restaurant_finances` enum('Yes','No') DEFAULT NULL,
  `access_handheld` enum('yes','no') DEFAULT NULL,
  `access_winepad` enum('yes','no') DEFAULT NULL,
  `access_discount` enum('Yes','No') DEFAULT NULL,
  `access_petty_cash` enum('Yes','No') DEFAULT NULL,
  `access_cash_mngt_cashout` enum('Yes','No') DEFAULT NULL,
  `access_cash_mngt_NI_mngt` enum('Yes','No') DEFAULT NULL,
  `access_visualprep` enum('yes','no') DEFAULT NULL,
  `access_digital` enum('yes','no') DEFAULT NULL,
  `access_barpoint` enum('yes','no') DEFAULT NULL,
  `access_tablereview` enum('yes','no') DEFAULT NULL,
  `access_restaurant_setup` enum('yes','no') DEFAULT NULL,
  `access_reservation_setup` enum('yes','no') DEFAULT NULL,
  `access_reservations` enum('yes','no') DEFAULT NULL,
  `access_hr_management` enum('Yes','No') DEFAULT NULL,
  `access_hotel` enum('Yes','No') DEFAULT NULL,
  `access_hotel_pms` enum('Yes','No') DEFAULT NULL,
  `access_hotel_reports` enum('Yes','No') DEFAULT NULL,
  `access_hotel_setup` enum('yes','no') DEFAULT NULL,
  `access_hotel_admin` enum('Yes','No') DEFAULT NULL,
  `access_hotel_reservation` enum('Yes','No') DEFAULT NULL,
  `housekeeper` enum('Yes','No') DEFAULT NULL,
  `access_minibar` enum('yes','no') DEFAULT NULL,
  `access_quick_setup` enum('Yes','No') DEFAULT NULL,
  `access_register` enum('yes','no') DEFAULT NULL,
  `access_retail_reports` enum('Yes','No') DEFAULT NULL,
  `access_retail_setup` enum('yes','no') DEFAULT NULL,
  `access_retail_admin` enum('Yes','No') DEFAULT NULL,
  `access_timeattendance` enum('yes','no') DEFAULT NULL,
  `access_ta_punching` enum('yes','no') DEFAULT NULL,
  `access_ta_planning` enum('yes','no') DEFAULT NULL,
  `access_ta_report` varchar(255) DEFAULT NULL,
  `access_ta_admin` enum('yes','no') DEFAULT NULL,
  `access_ta_schedule` enum('yes','no') DEFAULT NULL,
  `view_ta_schedule` enum('yes','no') DEFAULT NULL,
  `access_ta_payroll` enum('yes','no') DEFAULT NULL,
  `access_ta_controller` enum('Yes','No') DEFAULT NULL,
  `access_ta_messaging` enum('yes','no') DEFAULT NULL,
  `access_ta_emp_schedule` enum('yes','no') DEFAULT NULL,
  `department_allowed` varchar(500) DEFAULT NULL,
  `access_backoffice` enum('yes','no') DEFAULT NULL,
  `access_bo_purchases` enum('yes','no') DEFAULT NULL,
  `access_bo_inventory` enum('yes','no') DEFAULT NULL,
  `access_bo_payables` enum('yes','no') DEFAULT NULL,
  `access_bo_orders` enum('yes','no') DEFAULT NULL,
  `access_bo_data_collector` enum('Yes','No') DEFAULT NULL,
  `access_bo_reports` enum('yes','no') DEFAULT NULL,
  `access_bo_dashboard` enum('Yes','No') DEFAULT NULL,
  `access_bo_check_list` enum('Yes','No') DEFAULT NULL,
  `access_bo_gift_card` enum('Yes','No') DEFAULT NULL,
  `access_bo_receivables` enum('Yes','No') DEFAULT NULL,
  `access_bo_general_ledger` enum('Yes','No') DEFAULT NULL,
  `access_bo_setup` enum('yes','no') DEFAULT NULL,
  `access_chef` enum('Yes','No') DEFAULT NULL,
  `access_inventory` enum('Yes','No') DEFAULT NULL,
  `access_expensetab` enum('yes','no') DEFAULT NULL,
  `access_meetingroom` enum('yes','no') DEFAULT NULL,
  `access_crm` enum('yes','no') DEFAULT NULL,
  `access_crm_mailing` enum('Yes','No') DEFAULT NULL,
  `access_customer_setup` enum('yes','no') DEFAULT NULL,
  `access_quality` enum('yes','no') DEFAULT NULL,
  `access_concierge` enum('Yes','No') DEFAULT NULL,
  `access_crs` enum('Yes','No') DEFAULT NULL,
  `access_event_setup` enum('yes','no') DEFAULT NULL,
  `access_advertisement` enum('yes','no') DEFAULT NULL,
  `access_dispatch` enum('Yes','No') DEFAULT NULL,
  `access_drivers_setup` enum('yes','no') DEFAULT NULL,
  `access_training` enum('yes','no') DEFAULT NULL,
  `access_training_setup` enum('yes','no') DEFAULT NULL,
  `access_staffpoint` enum('Yes','No') DEFAULT NULL,
  `access_storepoint` enum('Yes','No') DEFAULT NULL,
  `access_chefedin` enum('Yes','No') DEFAULT NULL,
  `access_stylistfn` enum('Yes','No') DEFAULT NULL,
  `access_expensetab_setup` enum('yes','no') DEFAULT NULL,
  `access_spa` enum('Yes','No') DEFAULT NULL,
  `access_spa_setup` enum('Yes','No') DEFAULT NULL,
  `access_datapoint` enum('Yes','No') DEFAULT NULL,
  `access_datapoint_setup` enum('Yes','No') DEFAULT NULL,
  `access_datapoint_setup_devices` enum('Yes','No') DEFAULT NULL,
  `access_lounges` enum('Yes','No') DEFAULT NULL,
  `access_lounges_admin` enum('Yes','No') DEFAULT NULL,
  `access_lounges_reports` enum('Yes','No') DEFAULT NULL,
  `access_lounges_virtra` enum('Yes','No') DEFAULT NULL,
  `access_lounges_service` enum('Yes','No') DEFAULT NULL,
  `access_business_intelligence` enum('Yes','No') DEFAULT NULL,
  `access_sales_register` enum('Yes','No') DEFAULT NULL,
  `access_retail_adjust_surcharge` enum('Yes','No') DEFAULT NULL,
  `access_retail_commisions` enum('Yes','No') DEFAULT NULL,
  `access_retail_item_breakdown` enum('Yes','No') DEFAULT NULL,
  `access_retail_inventory_link` enum('Yes','No') DEFAULT NULL,
  `access_ticketing` enum('Yes','No') DEFAULT NULL,
  `access_controlpoint` enum('Yes','No') DEFAULT NULL,
  `use_availability` enum('Yes','No') DEFAULT NULL,
  `spa_service_provider` enum('Yes','No') DEFAULT NULL,
  `access_kioskpoint` enum('Yes','No') DEFAULT NULL,
  `access_olopoint` enum('Yes','No') DEFAULT NULL,
  `Rfid_status` enum('Active','Inactive') DEFAULT NULL,
  `Rfid_number` varchar(16) DEFAULT NULL,
  `Rfid_terminal` int(11) DEFAULT NULL,
  `Rfid_created_by` int(11) DEFAULT NULL,
  `Rfid_created_on` varchar(45) DEFAULT NULL,
  `Rfid_created_datetime` datetime DEFAULT NULL,
  `retail_commission` enum('Yes','No') DEFAULT NULL,
  `retail_commission_pct` decimal(4,2) DEFAULT NULL,
  `retail_commission_quota` decimal(14,2) DEFAULT NULL,
  `payroll_marital_status` enum('Single or Dual Income Married','Married (One Income)','Head of Household') DEFAULT NULL,
  `payroll_num_of_allowances` tinyint(4) DEFAULT NULL,
  `payroll_alloted_break_and_lunch_period` enum('0:15','0:30','0:45','1:00','1:15','1:30') DEFAULT NULL,
  `payroll_punch_grace_period` enum('05','08','15','18','30') DEFAULT NULL,
  `payroll_pto_manage` enum('Yes','No') DEFAULT NULL,
  `pto_start_use_date` date DEFAULT NULL,
  `payroll_starting_pto` varchar(10) DEFAULT NULL,
  `payroll_starting_pto_date` datetime DEFAULT NULL,
  `payroll_pto_accrual` varchar(10) DEFAULT NULL,
  `payroll_pto_cap` varchar(10) DEFAULT NULL,
  `payroll_pto_rollover_max` varchar(10) DEFAULT NULL,
  `pto_years1` varchar(50) DEFAULT NULL,
  `pto_years2` varchar(50) DEFAULT NULL,
  `pto_years3` varchar(50) DEFAULT NULL,
  `pto_basis1` varchar(50) DEFAULT NULL,
  `pto_basis2` varchar(50) DEFAULT NULL,
  `pto_basis3` varchar(50) DEFAULT NULL,
  `payroll_vacation_manage` enum('Yes','No') DEFAULT NULL,
  `vacation_start_use_date` date DEFAULT NULL,
  `payroll_starting_vacation` varchar(10) DEFAULT NULL,
  `payroll_vacation_accrual` varchar(45) DEFAULT NULL,
  `payroll_vacation_cap` varchar(10) DEFAULT NULL,
  `payroll_vacation_rollover_max` varchar(10) DEFAULT NULL,
  `starting_vacation_date` date DEFAULT NULL,
  `payroll_sick_manage` enum('Yes','No') DEFAULT NULL,
  `sick_start_use_date` date DEFAULT NULL,
  `payroll_starting_sick` varchar(10) DEFAULT NULL,
  `payroll_sick_accrual` varchar(10) DEFAULT NULL,
  `payroll_sick_cap` varchar(10) DEFAULT NULL,
  `payroll_sick_rollover_max` varchar(10) DEFAULT NULL,
  `payroll_night_pay` enum('Yes','No') DEFAULT NULL,
  `payroll_night_pay_rate` float DEFAULT NULL,
  `payroll_night_start_time` time DEFAULT NULL,
  `payroll_night_end_time` time DEFAULT NULL,
  `payroll_night_pay_include_for_sick` enum('Yes','No') DEFAULT NULL,
  `clover_employee_id` varchar(45) DEFAULT NULL,
  `clover_employee_pin` varchar(45) DEFAULT NULL,
  `clover_role` enum('EMPLOYEE','MANAGER','ADMIN') DEFAULT NULL,
  `hr_apply_date` datetime DEFAULT NULL,
  `hr_apply_review_date` datetime DEFAULT NULL,
  `hr_apply_review_by` int(11) DEFAULT NULL,
  `hr_apply_notes` tinytext,
  `hr_considered` enum('Yes','No') DEFAULT NULL,
  `hr_interview1_date` datetime DEFAULT NULL,
  `hr_interview1_by` int(11) DEFAULT NULL,
  `hr_interview1_notes` tinytext,
  `hr_interview1_approved` enum('Yes','No') DEFAULT NULL,
  `hr_interview2_date` datetime DEFAULT NULL,
  `hr_interview2_by` int(11) DEFAULT NULL,
  `hr_interview2_notes` tinytext,
  `hr_interview2_approved` enum('Yes','No') DEFAULT NULL,
  `hr_offer` enum('Yes','No') DEFAULT NULL,
  `hr_offer_amount` varchar(64) DEFAULT NULL,
  `hr_offer_notes` tinytext,
  `hr_applicant_accept` enum('Yes','No') DEFAULT NULL,
  `hr_applicant_startdate` datetime DEFAULT NULL,
  `boarding_hired_by` int(11) DEFAULT NULL,
  `boarding_datetime` datetime DEFAULT NULL,
  `boarding_notes` varchar(256) DEFAULT NULL,
  `boarding_validate_id` enum('Yes','No') DEFAULT NULL,
  `boarding_validate_ss` enum('Yes','No') DEFAULT NULL,
  `boarding_ic_verify` enum('Yes','No') DEFAULT NULL,
  `boarding_ic_verify_date` datetime DEFAULT NULL,
  `boarding_temp_date` datetime DEFAULT NULL,
  `boarding_hr_reminder` datetime DEFAULT NULL,
  `boarding_hr_emp` int(11) DEFAULT NULL,
  `allow_view_pay` enum('view_hourly','view_all') DEFAULT NULL,
  `holidays` varchar(500) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created__on` varchar(100) DEFAULT NULL,
  `created__datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`employees_audit_id`),
  KEY `employees_audit_location_fk_idx` (`location_id`),
  KEY `employees_audit_id_idx` (`id`),
  KEY `employees_audit_last_datetime_idx` (`last_datetime`),
  KEY `employees_audit_emp_lastdt_idx` (`id`,`emp_id`,`password`,`last_datetime`),
  CONSTRAINT `employees_audit_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=50293 DEFAULT CHARSET=latin1 COMMENT='Audit information based on changes made to an employee';

/*Table structure for table `employees_commitment` */

DROP TABLE IF EXISTS `employees_commitment`;

CREATE TABLE `employees_commitment` (
  `idEmployees_commitment` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `Mon` enum('Yes','No') DEFAULT NULL,
  `Tue` enum('Yes','No') DEFAULT NULL,
  `Wed` enum('Yes','No') DEFAULT NULL,
  `Thu` enum('Yes','No') DEFAULT NULL,
  `Fri` enum('Yes','No') DEFAULT NULL,
  `Sat` enum('Yes','No') DEFAULT NULL,
  `Sun` enum('Yes','No') DEFAULT NULL,
  PRIMARY KEY (`idEmployees_commitment`),
  UNIQUE KEY `idEmployees_commitment_UNIQUE` (`idEmployees_commitment`),
  KEY `employee_commitment_emp_fk_idx` (`employee_id`),
  CONSTRAINT `employee_commitment_emp_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=398 DEFAULT CHARSET=latin1 COMMENT='Entered commitment information provided by an employee';

/*Table structure for table `employees_entry` */

DROP TABLE IF EXISTS `employees_entry`;

CREATE TABLE `employees_entry` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `employee_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `emp_id` varchar(12) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `image` tinytext NOT NULL,
  `punch_type` enum('in','out') DEFAULT NULL,
  `manual` enum('add','edit','delete','Sick','Vacation','Suspended With Pay','Approved Time Off With Pay','Time Off Without Pay','BRV','HOP','HOL','Jury Duty') NOT NULL,
  `all_day` enum('Yes','No') NOT NULL DEFAULT 'No',
  `modified_date` date NOT NULL,
  `modified_time` time NOT NULL,
  `modified_by` varchar(45) DEFAULT NULL,
  `modified_on` varchar(45) DEFAULT NULL,
  `modified_datetime` datetime DEFAULT NULL,
  `total_regular` decimal(4,2) DEFAULT NULL,
  `total_break` decimal(4,2) DEFAULT NULL,
  `total_overtime1` decimal(4,2) DEFAULT NULL,
  `total_overtime2` decimal(4,2) DEFAULT NULL,
  `total_sick` decimal(4,2) DEFAULT NULL,
  `total_vacation` decimal(4,2) DEFAULT NULL,
  `total_pto` decimal(4,2) DEFAULT NULL,
  `position` varchar(32) DEFAULT NULL,
  `department` varchar(64) DEFAULT NULL,
  `hourly_rate` float DEFAULT NULL,
  `Payroll_id` int(11) DEFAULT NULL,
  `break` enum('Yes','No') DEFAULT 'No',
  `no_break_reason` varchar(64) DEFAULT NULL,
  `tip_estimate` decimal(11,2) DEFAULT NULL,
  `tip_declaration` decimal(11,2) DEFAULT NULL,
  `occurence` varchar(45) DEFAULT NULL,
  `points` decimal(11,2) DEFAULT NULL,
  `callout` tinyint(4) DEFAULT '0' COMMENT '1- Callout',
  `Created_on` varchar(45) NOT NULL,
  `Created_by` varchar(45) NOT NULL,
  `Created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employees_entry_fk` (`employee_id`),
  KEY `employees_entry_loc_fk_idx` (`location_id`),
  KEY `employees_entry_punch_type_idx` (`punch_type`),
  CONSTRAINT `employees_entry_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employees_entry_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=181594 DEFAULT CHARSET=latin1 COMMENT='Clock In and Out information for Employees';

/*Table structure for table `employees_master` */

DROP TABLE IF EXISTS `employees_master`;

CREATE TABLE `employees_master` (
  `empmaster_id` int(8) NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `password` varchar(32) NOT NULL,
  `status` enum('A','N','I') NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `salutation` varchar(10) NOT NULL,
  `title` varchar(32) DEFAULT NULL,
  `country` int(4) DEFAULT NULL,
  `address` varchar(64) NOT NULL,
  `address2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) NOT NULL,
  `region` varchar(32) NOT NULL,
  `neighborhood` varchar(60) DEFAULT NULL,
  `telephone` varchar(32) NOT NULL,
  `fax` varchar(32) NOT NULL,
  `Mobile` varchar(32) DEFAULT NULL,
  `sex` enum('M','F') NOT NULL,
  `dob` date NOT NULL,
  `image` longtext NOT NULL,
  `resume` tinytext NOT NULL,
  `activities` tinytext NOT NULL,
  `education` tinytext NOT NULL,
  `competencies` tinytext NOT NULL,
  `languages` varchar(45) NOT NULL,
  `viewable` enum('Y','N') NOT NULL,
  `employment_type` varchar(250) NOT NULL,
  `emp_position1` varchar(60) NOT NULL,
  `emp_position3` varchar(60) NOT NULL,
  `emp_position2` varchar(60) NOT NULL,
  `verify_number` int(10) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `facebook` enum('N','Y') DEFAULT NULL,
  `facebook_status` enum('Inactive','Linked','Unlinked') NOT NULL,
  `facebook_id` varchar(32) DEFAULT NULL,
  `google_id` varchar(45) DEFAULT NULL,
  `google_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `google_image` varchar(255) DEFAULT NULL,
  `linkedin_id` varchar(45) DEFAULT NULL,
  `linkedin_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `linkedin_image` varchar(255) DEFAULT NULL,
  `twitter_id` varchar(45) DEFAULT NULL,
  `twitter_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `twitter_image` varchar(255) DEFAULT NULL,
  `StorePoint` enum('Yes','No') DEFAULT NULL,
  `StorePoint_vendor_Id` int(11) DEFAULT NULL,
  `schedules` longtext,
  `ChefedIN` enum('Yes','No') DEFAULT NULL,
  `ChefedIN_Business_Name` varchar(64) DEFAULT NULL,
  `ChefedIN_image` longtext,
  `ChefedIN_Introduction` longtext,
  `ChefedIN_Services` longtext,
  `ChefedIN_experience` longtext,
  `ChefedIN_market` text,
  `ChefedIN_website` text,
  `ChefedIN_reference` text,
  `ChefedIN_rate` text,
  `StylistFN` enum('Yes','No') DEFAULT NULL,
  `StylistFN_Company` varchar(64) DEFAULT NULL,
  `StylistFN_Description` text,
  `StylistFN_Style` text,
  `StylistFN_Located` varchar(64) DEFAULT NULL,
  `StylistFN_location_id` int(8) DEFAULT NULL,
  `DeliveryPoint` enum('Yes','No') DEFAULT NULL,
  `Delivery_activated_datetime` datetime DEFAULT NULL,
  `Delivery_trasporation` enum('Car','Truck','Motorcylce','Bicycle') DEFAULT NULL,
  `Delivery_payment_method` varchar(45) DEFAULT NULL,
  `currency_id` int(8) DEFAULT NULL,
  `previousjob1_company` text,
  `previousjob1_title` text,
  `previousjob1_location` text,
  `previousjob1_startdate` date DEFAULT NULL,
  `previousjob1_enddate` date DEFAULT NULL,
  `previousjob1_description` text,
  `previousjob2_company` text,
  `previousjob2_title` text,
  `previousjob2_location` text,
  `previousjob2_startdate` date DEFAULT NULL,
  `previousjob2_enddate` date DEFAULT NULL,
  `previousjob2_description` text,
  `previousjob3_company` text,
  `previousjob3_title` text,
  `previousjob3_location` text,
  `previousjob3_startdate` date DEFAULT NULL,
  `previousjob3_enddate` date DEFAULT NULL,
  `previousjob3_description` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`empmaster_id`,`facebook_status`),
  KEY `emp_master_country_idx` (`country`),
  KEY `emp_master_state_idx` (`state`),
  KEY `emp_master_stylist_loc_idx` (`StylistFN_location_id`),
  KEY `emp_master_storepoint_ven_idx` (`StorePoint_vendor_Id`),
  KEY `employees_master_email_idx` (`email`),
  CONSTRAINT `emp_master_country` FOREIGN KEY (`country`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `emp_master_state` FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `emp_master_storepoint_ven` FOREIGN KEY (`StorePoint_vendor_Id`) REFERENCES `vendors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `emp_master_stylist_loc` FOREIGN KEY (`StylistFN_location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=401 DEFAULT CHARSET=latin1 COMMENT='Main profile information for an employee master';

/*Table structure for table `employees_master_audit` */

DROP TABLE IF EXISTS `employees_master_audit`;

CREATE TABLE `employees_master_audit` (
  `employees_master_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `empmaster_id` int(8) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `status` enum('A','N','I') DEFAULT NULL,
  `first_name` varchar(32) DEFAULT NULL,
  `last_name` varchar(32) DEFAULT NULL,
  `salutation` varchar(10) DEFAULT NULL,
  `country` int(4) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `address2` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) DEFAULT NULL,
  `region` varchar(32) DEFAULT NULL,
  `neighborhood` varchar(60) DEFAULT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `fax` varchar(32) DEFAULT NULL,
  `Mobile` varchar(32) DEFAULT NULL,
  `sex` enum('M','F') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `image` longtext,
  `resume` tinytext,
  `activities` tinytext,
  `education` tinytext,
  `competencies` tinytext,
  `languages` varchar(45) DEFAULT NULL,
  `viewable` enum('Y','N') DEFAULT NULL,
  `employment_type` varchar(250) DEFAULT NULL,
  `emp_position1` varchar(60) DEFAULT NULL,
  `emp_position3` varchar(60) DEFAULT NULL,
  `emp_position2` varchar(60) DEFAULT NULL,
  `verify_number` int(10) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `facebook` enum('N','Y') DEFAULT NULL,
  `facebook_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `facebook_id` varchar(32) DEFAULT NULL,
  `google_id` varchar(45) DEFAULT NULL,
  `google_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `google_image` varchar(255) DEFAULT NULL,
  `linkedin_id` varchar(45) DEFAULT NULL,
  `linkedin_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `linkedin_image` varchar(255) DEFAULT NULL,
  `twitter_id` varchar(45) DEFAULT NULL,
  `twitter_status` enum('Inactive','Linked','Unlinked') DEFAULT NULL,
  `twitter_image` varchar(255) DEFAULT NULL,
  `StorePoint` enum('Yes','No') DEFAULT NULL,
  `StorePoint_vendor_Id` int(11) DEFAULT NULL,
  `schedules` longtext,
  `ChefedIN` enum('Yes','No') DEFAULT NULL,
  `ChefedIN_Business_Name` varchar(64) DEFAULT NULL,
  `ChefedIN_image` longtext,
  `ChefedIN_Introduction` longtext,
  `ChefedIN_Services` longtext,
  `ChefedIN_experience` longtext,
  `ChefedIN_market` text,
  `ChefedIN_website` text,
  `ChefedIN_reference` text,
  `ChefedIN_rate` text,
  `StylistFN` enum('Yes','No') DEFAULT NULL,
  `StylistFN_Company` varchar(64) DEFAULT NULL,
  `StylistFN_Description` text,
  `StylistFN_Style` text,
  `StylistFN_Located` varchar(64) DEFAULT NULL,
  `StylistFN_location_id` int(8) DEFAULT NULL,
  `DeliveryPoint` enum('Yes','No') DEFAULT NULL,
  `Delivery_activated_datetime` datetime DEFAULT NULL,
  `Delivery_trasporation` enum('Car','Truck','Motorcylce','Bicycle') DEFAULT NULL,
  `Delivery_payment_method` varchar(45) DEFAULT NULL,
  `currency_id` int(8) DEFAULT NULL,
  `previousjob1_company` text,
  `previousjob1_title` text,
  `previousjob1_location` text,
  `previousjob1_startdate` date DEFAULT NULL,
  `previousjob1_enddate` date DEFAULT NULL,
  `previousjob1_description` text,
  `previousjob2_company` text,
  `previousjob2_title` text,
  `previousjob2_location` text,
  `previousjob2_startdate` date DEFAULT NULL,
  `previousjob2_enddate` date DEFAULT NULL,
  `previousjob2_description` text,
  `previousjob3_company` text,
  `previousjob3_title` text,
  `previousjob3_location` text,
  `previousjob3_startdate` date DEFAULT NULL,
  `previousjob3_enddate` date DEFAULT NULL,
  `previousjob3_description` text,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`employees_master_audit_id`),
  KEY `employees_master_audit_location_fk_idx` (`StylistFN_location_id`),
  KEY `employees_master_audit_last_datetime_idx` (`last_datetime`),
  KEY `employees_master_audit_empmaster_id_idx` (`empmaster_id`),
  CONSTRAINT `employees_master_audit_location_fk` FOREIGN KEY (`StylistFN_location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2591 DEFAULT CHARSET=latin1 COMMENT='Audit information based on changes made to an employee maste';

/*Table structure for table `employees_master_availability` */

DROP TABLE IF EXISTS `employees_master_availability`;

CREATE TABLE `employees_master_availability` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empmaster_id` int(8) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `sunday` enum('Y','N') NOT NULL,
  `monday` enum('Y','N') NOT NULL,
  `tuesday` enum('Y','N') NOT NULL,
  `wednesday` enum('Y','N') NOT NULL,
  `thursday` enum('Y','N') NOT NULL,
  `friday` enum('Y','N') NOT NULL,
  `saturday` enum('Y','N') NOT NULL,
  `availability_type` enum('Available','Request Off','Request Vacation','Vacation','Sick','Unavailable') NOT NULL DEFAULT 'Available',
  `Request_datetime` date DEFAULT NULL,
  `Request_location_id` int(8) DEFAULT NULL,
  `Request_message` text,
  `Created_by` varchar(45) NOT NULL,
  `Created_on` varchar(45) NOT NULL,
  `Created_datetime` datetime NOT NULL,
  `Last_by` varchar(45) DEFAULT NULL,
  `Last_on` varchar(45) DEFAULT NULL,
  `Last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employees_master_availability_fk` (`empmaster_id`),
  KEY `employees_master_loc_idx` (`Request_location_id`),
  CONSTRAINT `employees_master_availability_fk` FOREIGN KEY (`empmaster_id`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employees_master_loc` FOREIGN KEY (`Request_location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Entered scheduled availability for an employee master';

/*Table structure for table `employees_register_log` */

DROP TABLE IF EXISTS `employees_register_log`;

CREATE TABLE `employees_register_log` (
  `employees_register_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `type` enum('Sale','No Sale') DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `cash_received` decimal(10,2) DEFAULT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `open_by` int(11) DEFAULT NULL,
  `open_on` varchar(60) DEFAULT NULL,
  `open_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`employees_register_log_id`),
  KEY `employees_register_log_location_fk_idx` (`location_id`),
  CONSTRAINT `employees_register_log_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=104759 DEFAULT CHARSET=latin1 COMMENT='Logs for an employee opening a register at a location';

/*Table structure for table `employees_schedules` */

DROP TABLE IF EXISTS `employees_schedules`;

CREATE TABLE `employees_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `employee_id` int(8) NOT NULL,
  `status` enum('Active','Inactive','temp') DEFAULT NULL,
  `department` varchar(40) NOT NULL,
  `date` date NOT NULL,
  `schedule_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employees_schedules_fk` (`employee_id`),
  KEY `employees_schedules_loc_fk` (`location_id`),
  CONSTRAINT `employees_schedules_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `emplyoees_schedule_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7517 DEFAULT CHARSET=utf8 COMMENT='Schedule information for an employee at a location';

/*Table structure for table `expensetab_payment_disputes` */

DROP TABLE IF EXISTS `expensetab_payment_disputes`;

CREATE TABLE `expensetab_payment_disputes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expensetab_payment_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `reason` enum('fraudulent','wrong amount','other') NOT NULL,
  `comments` text NOT NULL,
  `image` text COMMENT 'Image uploaded from "tab popup"; typically will be a receipt to show the incorrect charge',
  `created_datetime` datetime NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `reviewed` enum('no','yes') NOT NULL DEFAULT 'no',
  `reviewed_datetime` datetime DEFAULT NULL,
  `review_comments` text,
  PRIMARY KEY (`id`),
  KEY `expensetab_payment_disputes_payment_FK` (`expensetab_payment_id`),
  KEY `expensetab_payment_dispute_admin_idx` (`admin_id`),
  KEY `expensetab_payment_dispute_client_idx` (`client_id`),
  KEY `expensetab_payment_disputes_loc_fk_idx` (`location_id`),
  CONSTRAINT `expensetab_payment_dispute_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `expensetab_payment_dispute_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `expensetab_payment_disputes_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `expensetab_payment_disputes_payment_FK` FOREIGN KEY (`expensetab_payment_id`) REFERENCES `expensetab_payments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Dispute information for expensetab payments';

/*Table structure for table `expensetab_payments` */

DROP TABLE IF EXISTS `expensetab_payments`;

CREATE TABLE `expensetab_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expensetab_client_account` int(11) NOT NULL,
  `expensetab_location_account` int(11) DEFAULT NULL,
  `to_expensetab_client_account` int(11) DEFAULT NULL COMMENT 'the client_expensetab_account id that is recieving the payment in a client to client transaction(when from_type == client)',
  `status` enum('complete','pending','disputed','active') NOT NULL DEFAULT 'pending',
  `from_type` enum('pos','retail','hotel','client') DEFAULT NULL,
  `client_order_id` int(11) DEFAULT NULL COMMENT 'link to client_orders\n\nonly used when from_type == pos',
  `client_sales_id` int(11) DEFAULT NULL COMMENT 'link to client_sales\n\nonly used when from_type == retail',
  `location_hotelacct_id` int(11) DEFAULT NULL COMMENT 'link to location_hotell acct\n\nonly used when from_type == hotel',
  `datetime` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `settle_by` int(11) NOT NULL COMMENT 'links to client_payments.id\n\nThis field is to set the default payment method for this expensetab payment',
  `expensetab_settled_id` int(11) DEFAULT NULL,
  `parent_expensetab_payment_id` int(11) DEFAULT NULL COMMENT 'this is the expensetab_payments.id for the master record in a client to client transaction. this record should be used as the amount credited to this records expensetab_client_account',
  `currency_id` int(8) DEFAULT NULL,
  `client_sales_id_location` int(8) DEFAULT NULL,
  `location_hotelacct_id_location` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `et_payment_order_fk_idx` (`client_order_id`),
  KEY `et_payment_currency_fk_idx` (`currency_id`),
  KEY `et_payment_et_client_fk_idx` (`expensetab_client_account`),
  KEY `et_payment_et_loc_fk_idx` (`expensetab_location_account`),
  KEY `et_payment_to_et_client_fk_idx` (`to_expensetab_client_account`),
  KEY `et_payment_sales_fk_idx` (`client_sales_id`),
  KEY `et_payment_hotelacct_fk_idx` (`location_hotelacct_id`),
  KEY `et_payment_location_fk_idx` (`location_hotelacct_id_location`),
  CONSTRAINT `et_payment_currency_fk` FOREIGN KEY (`currency_id`) REFERENCES `global_currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `et_payment_et_client_fk` FOREIGN KEY (`expensetab_client_account`) REFERENCES `client_expensetab_accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `et_payment_et_loc_fk` FOREIGN KEY (`expensetab_location_account`) REFERENCES `location_expensetab_accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `et_payment_hotelacct_fk` FOREIGN KEY (`location_hotelacct_id`) REFERENCES `location_hotelacct` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `et_payment_location_fk` FOREIGN KEY (`location_hotelacct_id_location`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `et_payment_order_fk` FOREIGN KEY (`client_order_id`) REFERENCES `client_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `et_payment_sales_fk` FOREIGN KEY (`client_sales_id`) REFERENCES `client_sales` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `et_payment_to_et_client_fk` FOREIGN KEY (`to_expensetab_client_account`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Payment information for an expensetab purchase';

/*Table structure for table `expensetab_settlement_details` */

DROP TABLE IF EXISTS `expensetab_settlement_details`;

CREATE TABLE `expensetab_settlement_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expensetab_settlement_id` int(11) NOT NULL,
  `client_payment_id` int(11) NOT NULL,
  `settlement_type` enum('credit card','debit card','paypal','expensetab direct','bank account') DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `processed` enum('yes','no') NOT NULL DEFAULT 'no',
  `currency_id` int(8) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `expensetab_settlement_details_settle_FK` (`expensetab_settlement_id`),
  KEY `expensetab_settlement_details_currency_idx` (`currency_id`),
  KEY `expensetab_settlement_details_client_payment_idx` (`client_payment_id`),
  CONSTRAINT `expensetab_settlement_details_client_payment` FOREIGN KEY (`client_payment_id`) REFERENCES `client_payments` (`payment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `expensetab_settlement_details_currency` FOREIGN KEY (`currency_id`) REFERENCES `global_currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `expensetab_settlement_details_settle_FK` FOREIGN KEY (`expensetab_settlement_id`) REFERENCES `expensetab_settlements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Specific details of an expensetab settlement';

/*Table structure for table `expensetab_settlements` */

DROP TABLE IF EXISTS `expensetab_settlements`;

CREATE TABLE `expensetab_settlements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expensetab_account_id` int(11) NOT NULL COMMENT 'Can be expensetab client OR location account id',
  `made_by` enum('client','location') DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `created_by` enum('Self','Auto') NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `currency_id` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `et_settlement_currency_idx` (`currency_id`),
  CONSTRAINT `et_settlement_currency` FOREIGN KEY (`currency_id`) REFERENCES `global_currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Records of expensetab settlements for client and location';

/*Table structure for table `faq` */

DROP TABLE IF EXISTS `faq`;

CREATE TABLE `faq` (
  `faq_id` int(8) NOT NULL AUTO_INCREMENT,
  `product` varchar(45) DEFAULT NULL,
  `module` varchar(45) DEFAULT NULL,
  `questions` text,
  `answer` text,
  `answer_by` varchar(45) DEFAULT NULL,
  `commemts` text,
  `image1` tinytext,
  `image2` tinytext,
  `image3` tinytext,
  `video` tinytext,
  `reveiwed_by` varchar(45) DEFAULT NULL,
  `reviewed_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datatime` datetime DEFAULT NULL,
  PRIMARY KEY (`faq_id`),
  UNIQUE KEY `faq_id_UNIQUE` (`faq_id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=latin1 COMMENT='This table store all known FAQ';

/*Table structure for table `forecast_budgets` */

DROP TABLE IF EXISTS `forecast_budgets`;

CREATE TABLE `forecast_budgets` (
  `forecast_budgets_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `year` int(4) DEFAULT NULL,
  `description` text,
  `priority` int(11) DEFAULT NULL,
  `chart_of_account` varchar(45) DEFAULT NULL,
  `type` enum('Budget','Actual') DEFAULT NULL,
  `january` float(11,2) DEFAULT NULL,
  `january_pct` varchar(12) DEFAULT NULL,
  `february` float(11,2) DEFAULT NULL,
  `february_pct` varchar(12) DEFAULT NULL,
  `march` float(11,2) DEFAULT NULL,
  `march_pct` varchar(12) DEFAULT NULL,
  `april` float(11,2) DEFAULT NULL,
  `april_pct` varchar(12) DEFAULT NULL,
  `may` float(11,2) DEFAULT NULL,
  `may_pct` varchar(12) DEFAULT NULL,
  `june` float(11,2) DEFAULT NULL,
  `june_pct` varchar(12) DEFAULT NULL,
  `july` float(11,2) DEFAULT NULL,
  `july_pct` varchar(12) DEFAULT NULL,
  `august` float(11,2) DEFAULT NULL,
  `august_pct` varchar(12) DEFAULT NULL,
  `september` float(11,2) DEFAULT NULL,
  `september_pct` varchar(12) DEFAULT NULL,
  `october` float(11,2) DEFAULT NULL,
  `october_pct` varchar(12) DEFAULT NULL,
  `november` float(11,2) DEFAULT NULL,
  `november_pct` varchar(12) DEFAULT NULL,
  `december` float(11,2) DEFAULT NULL,
  `december_pct` varchar(12) DEFAULT NULL,
  `total` float(11,2) DEFAULT NULL,
  `total_pct` varchar(12) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`forecast_budgets_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `forecast_entries` */

DROP TABLE IF EXISTS `forecast_entries`;

CREATE TABLE `forecast_entries` (
  `forecast_entries_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `forecast_processed_id` int(11) NOT NULL,
  `forecast_structure_id` int(11) NOT NULL,
  `entry1` text,
  `entry2` text,
  `entry3` text,
  `entry4` text,
  `entry5` text,
  `entry6` text,
  `entry7` text,
  `entry8` text,
  `entry9` text,
  `entry10` text,
  `entry11` text,
  `entry12` text,
  `entry13` text,
  `entry14` text,
  `entry15` text,
  `entry16` text,
  `entry17` text,
  `entry18` text,
  `entry19` text,
  `entry20` text,
  `entry21` text,
  `entry22` text,
  `entry23` text,
  `entry24` text,
  `entry25` text,
  `entry26` text,
  `entry27` text,
  `entry28` text,
  `entry29` text,
  `entry30` text,
  `entry31` text,
  `entry32` text,
  `entry33` text,
  `entry34` text,
  `entry35` text,
  `entry36` text,
  `entry37` text,
  `entry38` text,
  `entry39` text,
  `entry40` text,
  `entry41` text,
  `entry42` text,
  `entry43` text,
  `entry44` text,
  `entry45` text,
  `entry46` text,
  `entry47` text,
  `entry48` text,
  `entry49` text,
  `entry50` text,
  `entry51` text,
  `entry52` text,
  `entry53` text,
  `entry54` text,
  `entry55` text,
  `entry56` text,
  `entry57` text,
  `entry58` text,
  `entry59` text,
  `entry60` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`forecast_entries_id`),
  KEY `forecast_entries_location_id_idk` (`location_id`),
  KEY `forecast_entries_forecast_processed_id_idk` (`forecast_processed_id`),
  KEY `forecast_entries_forecast_structure_id_idk` (`forecast_structure_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66018 DEFAULT CHARSET=utf8;

/*Table structure for table `forecast_processed` */

DROP TABLE IF EXISTS `forecast_processed`;

CREATE TABLE `forecast_processed` (
  `forecast_processed_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `status` enum('Started','Finished') NOT NULL,
  `forecast_type` varchar(45) DEFAULT NULL,
  `forecast_date` date DEFAULT NULL,
  `forecast_period_from` datetime DEFAULT NULL,
  `forecast_period_to` datetime DEFAULT NULL,
  `started` datetime DEFAULT NULL,
  `finished` datetime DEFAULT NULL,
  `duration` varchar(12) DEFAULT NULL,
  `notes` text,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_dateiitme` datetime DEFAULT NULL,
  PRIMARY KEY (`forecast_processed_id`),
  KEY `forecast_processed_location_id_idk` (`location_id`),
  KEY `forecast_processed_employee_id_idk` (`employee_id`),
  KEY `forecast_processed_status_idk` (`status`),
  KEY `forecast_processed_forecast_type_idk` (`forecast_type`),
  KEY `forecast_processed_forecast_date_idk` (`forecast_date`)
) ENGINE=InnoDB AUTO_INCREMENT=2005 DEFAULT CHARSET=utf8;

/*Table structure for table `forecast_structure` */

DROP TABLE IF EXISTS `forecast_structure`;

CREATE TABLE `forecast_structure` (
  `forecast_structure_id` int(11) NOT NULL AUTO_INCREMENT,
  `forecasts_id` int(8) NOT NULL,
  `group_name` varchar(45) NOT NULL,
  `group_priority` int(11) NOT NULL DEFAULT '1',
  `field_name` varchar(45) NOT NULL,
  `field_priority` int(11) NOT NULL DEFAULT '1',
  `entry_type1` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type1_formula` text,
  `entry_type1_minmax` varchar(12) DEFAULT NULL,
  `entry_type2` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type2_formula` text,
  `entry_type2_minmax` varchar(12) DEFAULT NULL,
  `entry_type3` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type3_formula` text,
  `entry_type3_minmax` varchar(12) DEFAULT NULL,
  `entry_type4` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type4_formula` text,
  `entry_type4_minmax` varchar(12) DEFAULT NULL,
  `entry_type5` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type5_formula` text,
  `entry_type5_minmax` varchar(12) DEFAULT NULL,
  `entry_type6` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type6_formula` text,
  `entry_type6_minmax` varchar(12) DEFAULT NULL,
  `entry_type7` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type7_formula` text,
  `entry_type7_minmax` varchar(12) DEFAULT NULL,
  `entry_type8` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type8_formula` text,
  `entry_type8_minmax` varchar(12) DEFAULT NULL,
  `entry_type9` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type9_formula` text,
  `entry_type9_minmax` varchar(12) DEFAULT NULL,
  `entry_type10` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type10_formula` text,
  `entry_type10_minmax` varchar(12) DEFAULT NULL,
  `entry_type11` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type11_formula` text,
  `entry_type11_minmax` varchar(12) DEFAULT NULL,
  `entry_type12` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type12_formula` text,
  `entry_type12_minmax` varchar(12) DEFAULT NULL,
  `entry_type13` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type13_formula` text,
  `entry_type13_minmax` varchar(12) DEFAULT NULL,
  `entry_type14` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type14_formula` text,
  `entry_type14_minmax` varchar(12) DEFAULT NULL,
  `entry_type15` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type15_formula` text,
  `entry_type15_minmax` varchar(12) DEFAULT NULL,
  `entry_type16` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type16_formula` text,
  `entry_type16_minmax` varchar(12) DEFAULT NULL,
  `entry_type17` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type17_formula` text,
  `entry_type17_minmax` varchar(12) DEFAULT NULL,
  `entry_type18` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type18_formula` text,
  `entry_type18_minmax` varchar(12) DEFAULT NULL,
  `entry_type19` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type19_formula` text,
  `entry_type19_minmax` varchar(12) DEFAULT NULL,
  `entry_type20` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type20_formula` text,
  `entry_type20_minmax` varchar(12) DEFAULT NULL,
  `entry_type21` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type21_formula` text,
  `entry_type21_minmax` varchar(12) DEFAULT NULL,
  `entry_type22` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type22_formula` text,
  `entry_type22_minmax` varchar(12) DEFAULT NULL,
  `entry_type23` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type23_formula` text,
  `entry_type23_minmax` varchar(12) DEFAULT NULL,
  `entry_type24` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type24_formula` text,
  `entry_type24_minmax` varchar(12) DEFAULT NULL,
  `entry_type25` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type25_formula` text,
  `entry_type25_minmax` varchar(12) DEFAULT NULL,
  `entry_type26` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type26_formula` text,
  `entry_type26_minmax` varchar(12) DEFAULT NULL,
  `entry_type27` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type27_formula` text,
  `entry_type27_minmax` varchar(12) DEFAULT NULL,
  `entry_type28` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type28_formula` text,
  `entry_type28_minmax` varchar(12) DEFAULT NULL,
  `entry_type29` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type29_formula` text,
  `entry_type29_minmax` varchar(12) DEFAULT NULL,
  `entry_type30` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type30_formula` text,
  `entry_type30_minmax` varchar(12) DEFAULT NULL,
  `entry_type31` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type31_formula` text,
  `entry_type31_minmax` varchar(12) DEFAULT NULL,
  `entry_type32` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type32_formula` text,
  `entry_type32_minmax` varchar(12) DEFAULT NULL,
  `entry_type33` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type33_formula` text,
  `entry_type33_minmax` varchar(12) DEFAULT NULL,
  `entry_type34` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type34_formula` text,
  `entry_type34_minmax` varchar(12) DEFAULT NULL,
  `entry_type35` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type35_formula` text,
  `entry_type35_minmax` varchar(12) DEFAULT NULL,
  `entry_type36` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type36_formula` text,
  `entry_type36_minmax` varchar(12) DEFAULT NULL,
  `entry_type37` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type37_formula` text,
  `entry_type37_minmax` varchar(12) DEFAULT NULL,
  `entry_type38` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type38_formula` text,
  `entry_type38_minmax` varchar(12) DEFAULT NULL,
  `entry_type39` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type39_formula` text,
  `entry_type39_minmax` varchar(12) DEFAULT NULL,
  `entry_type40` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type40_formula` text,
  `entry_type40_minmax` varchar(12) DEFAULT NULL,
  `entry_type41` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type41_formula` text,
  `entry_type41_minmax` varchar(12) DEFAULT NULL,
  `entry_type42` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type42_formula` text,
  `entry_type42_minmax` varchar(12) DEFAULT NULL,
  `entry_type43` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type43_formula` text,
  `entry_type43_minmax` varchar(12) DEFAULT NULL,
  `entry_type44` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type44_formula` text,
  `entry_type44_minmax` varchar(12) DEFAULT NULL,
  `entry_type45` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type45_formula` text,
  `entry_type45_minmax` varchar(12) DEFAULT NULL,
  `entry_type46` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type46_formula` text,
  `entry_type46_minmax` varchar(12) DEFAULT NULL,
  `entry_type47` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type47_formula` text,
  `entry_type47_minmax` varchar(12) DEFAULT NULL,
  `entry_type48` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type48_formula` text,
  `entry_type48_minmax` varchar(12) DEFAULT NULL,
  `entry_type49` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type49_formula` text,
  `entry_type49_minmax` varchar(12) DEFAULT NULL,
  `entry_type50` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type50_formula` text,
  `entry_type50_minmax` varchar(12) DEFAULT NULL,
  `entry_type51` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type51_formula` text,
  `entry_type51_minmax` varchar(12) DEFAULT NULL,
  `entry_type52` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type52_formula` text,
  `entry_type52_minmax` varchar(12) DEFAULT NULL,
  `entry_type53` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type53_formula` text,
  `entry_type53_minmax` varchar(12) DEFAULT NULL,
  `entry_type54` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type54_formula` text,
  `entry_type54_minmax` varchar(12) DEFAULT NULL,
  `entry_type55` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type55_formula` text,
  `entry_type55_minmax` varchar(12) DEFAULT NULL,
  `entry_type56` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type56_formula` text,
  `entry_type56_minmax` varchar(12) DEFAULT NULL,
  `entry_type57` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type57_formula` text,
  `entry_type57_minmax` varchar(12) DEFAULT NULL,
  `entry_type58` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type58_formula` text,
  `entry_type58_minmax` varchar(12) DEFAULT NULL,
  `entry_type59` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type59_formula` text,
  `entry_type59_minmax` varchar(12) DEFAULT NULL,
  `entry_type60` enum('','Number','Money','Text','Formula','Time') DEFAULT NULL,
  `entry_type60_formula` text,
  `entry_type60_minmax` varchar(12) DEFAULT NULL,
  `total_line` enum('','Yes','No') NOT NULL DEFAULT 'No',
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`forecast_structure_id`),
  KEY `forecast_structure_forecasts_id_idx` (`forecasts_id`),
  KEY `forecast_structure_group_name_idk` (`group_name`),
  KEY `forecast_structure_group_priority_idk` (`group_priority`),
  KEY `forecast_structure_field_name_idk` (`field_name`),
  KEY `forecast_structure_field_priority_idk` (`field_priority`)
) ENGINE=InnoDB AUTO_INCREMENT=480 DEFAULT CHARSET=utf8;

/*Table structure for table `forecast_structure_columns` */

DROP TABLE IF EXISTS `forecast_structure_columns`;

CREATE TABLE `forecast_structure_columns` (
  `forecast_structure_columns_id` int(11) NOT NULL AUTO_INCREMENT,
  `forecast_structure_id` int(11) NOT NULL,
  `group_name` varchar(45) NOT NULL,
  `how_many_columns` int(11) NOT NULL,
  `column1_name` varchar(45) NOT NULL,
  `column2_name` varchar(45) DEFAULT NULL,
  `column3_name` varchar(45) DEFAULT NULL,
  `column4_name` varchar(45) DEFAULT NULL,
  `column5_name` varchar(45) DEFAULT NULL,
  `column6_name` varchar(45) DEFAULT NULL,
  `column7_name` varchar(45) DEFAULT NULL,
  `column8_name` varchar(45) DEFAULT NULL,
  `column9_name` varchar(45) DEFAULT NULL,
  `column10_name` varchar(45) DEFAULT NULL,
  `column11_name` varchar(45) DEFAULT NULL,
  `column12_name` varchar(45) DEFAULT NULL,
  `column13_name` varchar(45) DEFAULT NULL,
  `column14_name` varchar(45) DEFAULT NULL,
  `column15_name` varchar(45) DEFAULT NULL,
  `column16_name` varchar(45) DEFAULT NULL,
  `column17_name` varchar(45) DEFAULT NULL,
  `column18_name` varchar(45) DEFAULT NULL,
  `column19_name` varchar(45) DEFAULT NULL,
  `column20_name` varchar(45) DEFAULT NULL,
  `column21_name` varchar(45) DEFAULT NULL,
  `column22_name` varchar(45) DEFAULT NULL,
  `column23_name` varchar(45) DEFAULT NULL,
  `column24_name` varchar(45) DEFAULT NULL,
  `column25_name` varchar(45) DEFAULT NULL,
  `column26_name` varchar(45) DEFAULT NULL,
  `column27_name` varchar(45) DEFAULT NULL,
  `column28_name` varchar(45) DEFAULT NULL,
  `column29_name` varchar(45) DEFAULT NULL,
  `column30_name` varchar(45) DEFAULT NULL,
  `column31_name` varchar(45) DEFAULT NULL,
  `column32_name` varchar(45) DEFAULT NULL,
  `column33_name` varchar(45) DEFAULT NULL,
  `column34_name` varchar(45) DEFAULT NULL,
  `column35_name` varchar(45) DEFAULT NULL,
  `column36_name` varchar(45) DEFAULT NULL,
  `column37_name` varchar(45) DEFAULT NULL,
  `column38_name` varchar(45) DEFAULT NULL,
  `column39_name` varchar(45) DEFAULT NULL,
  `column40_name` varchar(45) DEFAULT NULL,
  `column41_name` varchar(45) DEFAULT NULL,
  `column42_name` varchar(45) DEFAULT NULL,
  `column43_name` varchar(45) DEFAULT NULL,
  `column44_name` varchar(45) DEFAULT NULL,
  `column45_name` varchar(45) DEFAULT NULL,
  `column46_name` varchar(45) DEFAULT NULL,
  `column47_name` varchar(45) DEFAULT NULL,
  `column48_name` varchar(45) DEFAULT NULL,
  `column49_name` varchar(45) DEFAULT NULL,
  `column50_name` varchar(45) DEFAULT NULL,
  `column51_name` varchar(45) DEFAULT NULL,
  `column52_name` varchar(45) DEFAULT NULL,
  `column53_name` varchar(45) DEFAULT NULL,
  `column54_name` varchar(45) DEFAULT NULL,
  `column55_name` varchar(45) DEFAULT NULL,
  `column56_name` varchar(45) DEFAULT NULL,
  `column57_name` varchar(45) DEFAULT NULL,
  `column58_name` varchar(45) DEFAULT NULL,
  `column59_name` varchar(45) DEFAULT NULL,
  `column60_name` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`forecast_structure_columns_id`),
  KEY `forecast_structure_columns_forecast_structure_id_idx` (`forecast_structure_id`),
  KEY `forecast_structure_columns_group_name_idx` (`group_name`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;

/*Table structure for table `forecasts` */

DROP TABLE IF EXISTS `forecasts`;

CREATE TABLE `forecasts` (
  `forecasts_id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `market` enum('All','Hotel','Retail','Restaurant','Other') NOT NULL,
  `frequency` enum('Daily','Weekly','Monthly','Quarterly','Annually','Period') NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`forecasts_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Table structure for table `forecasts_location` */

DROP TABLE IF EXISTS `forecasts_location`;

CREATE TABLE `forecasts_location` (
  `forecasts_location_id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `forecasts_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`forecasts_location_id`),
  KEY `forecasts_location_fid_idx` (`forecasts_id`)
) ENGINE=InnoDB AUTO_INCREMENT=912 DEFAULT CHARSET=utf8;

/*Table structure for table `global_boarding_checklist` */

DROP TABLE IF EXISTS `global_boarding_checklist`;

CREATE TABLE `global_boarding_checklist` (
  `global_boarding_checklist_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `priority` varchar(10) DEFAULT NULL,
  `description` text,
  `required` enum('Yes','No') DEFAULT 'Yes',
  `details_required` enum('Yes','No') DEFAULT 'Yes',
  `info` text,
  `reseller_task` enum('editable','uneditable') DEFAULT 'editable',
  `responsibility` enum('Sales','Billing','Install') DEFAULT 'Sales',
  PRIMARY KEY (`global_boarding_checklist_id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;

/*Table structure for table `global_bookingtypes` */

DROP TABLE IF EXISTS `global_bookingtypes`;

CREATE TABLE `global_bookingtypes` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1 COMMENT='Booking types used by any locations for Hotel Bookings';

/*Table structure for table `global_chart_of_account` */

DROP TABLE IF EXISTS `global_chart_of_account`;

CREATE TABLE `global_chart_of_account` (
  `global_chart_of_account_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `code_type` enum('Assets','Liabilities','Equity','Revenue','Cost of Goods Sold','Payroll','Other Expenses','Fixed Charges') DEFAULT NULL,
  `description` longtext NOT NULL,
  `reporting_type` enum('Balance Sheet','Profit & Loss') DEFAULT NULL,
  PRIMARY KEY (`global_chart_of_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=338 DEFAULT CHARSET=utf8 COMMENT='Global chart of account types';

/*Table structure for table `global_currency` */

DROP TABLE IF EXISTS `global_currency`;

CREATE TABLE `global_currency` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(3) CHARACTER SET utf8 NOT NULL,
  `description` varchar(64) CHARACTER SET utf8 NOT NULL,
  `symbol` varchar(3) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `global_currency_code_idx` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='All available currencies for any user to use';

/*Table structure for table `global_departments` */

DROP TABLE IF EXISTS `global_departments`;

CREATE TABLE `global_departments` (
  `global_department_id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`global_department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;

/*Table structure for table `global_employee_forms` */

DROP TABLE IF EXISTS `global_employee_forms`;

CREATE TABLE `global_employee_forms` (
  `global_employee_forms_if` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `employees_status` enum('A','S','D','L','T','Applicant','Interviewed','Not Hired','Boarding','Do not Hire','Probation','A-4','W-4','1B-Illness') DEFAULT NULL,
  PRIMARY KEY (`global_employee_forms_if`),
  UNIQUE KEY `global_employee_forms_if_UNIQUE` (`global_employee_forms_if`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Table structure for table `global_guarantee` */

DROP TABLE IF EXISTS `global_guarantee`;

CREATE TABLE `global_guarantee` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  `cc` enum('Yes','No') DEFAULT NULL,
  `guarantees_booking` enum('Yes','No') DEFAULT NULL,
  `requires_deposit` enum('Yes','No') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COMMENT='Guarantee types used by any location for Hotel Bookings';

/*Table structure for table `global_guesttypes` */

DROP TABLE IF EXISTS `global_guesttypes`;

CREATE TABLE `global_guesttypes` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COMMENT='Guest types used by any location for Hotel Bookings';

/*Table structure for table `global_languages` */

DROP TABLE IF EXISTS `global_languages`;

CREATE TABLE `global_languages` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1 COMMENT='Global Languages that can be used by any user';

/*Table structure for table `global_markettypes` */

DROP TABLE IF EXISTS `global_markettypes`;

CREATE TABLE `global_markettypes` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COMMENT='Market Types used by any location for Hotel Bookings';

/*Table structure for table `global_mealtypes` */

DROP TABLE IF EXISTS `global_mealtypes`;

CREATE TABLE `global_mealtypes` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COMMENT='Meal Types used by any location for Hotel Bookings';

/*Table structure for table `global_postingtypes` */

DROP TABLE IF EXISTS `global_postingtypes`;

CREATE TABLE `global_postingtypes` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  `type` enum('Food & Beverage','Laundry','Other','Other Revenue','Payments','Rooms','Service Charge','Spa','Taxes','Telephone','Transportation') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1 COMMENT='Posting Types used by any location for Hotel Bookings';

/*Table structure for table `global_products` */

DROP TABLE IF EXISTS `global_products`;

CREATE TABLE `global_products` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

/*Table structure for table `global_qc_description` */

DROP TABLE IF EXISTS `global_qc_description`;

CREATE TABLE `global_qc_description` (
  `global_qc_description_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  PRIMARY KEY (`global_qc_description_id`),
  UNIQUE KEY `global_qc_description_id_UNIQUE` (`global_qc_description_id`)
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=latin1 COMMENT='Holds the various description code';

/*Table structure for table `global_ratetypes` */

DROP TABLE IF EXISTS `global_ratetypes`;

CREATE TABLE `global_ratetypes` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COMMENT='Rate Types used by any location for Hotel Bookings';

/*Table structure for table `global_roomtypes` */

DROP TABLE IF EXISTS `global_roomtypes`;

CREATE TABLE `global_roomtypes` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1 COMMENT='Room Types used by any location for Hotel Bookings';

/*Table structure for table `global_taxtype` */

DROP TABLE IF EXISTS `global_taxtype`;

CREATE TABLE `global_taxtype` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Tax Types used by any location for Hotel Bookings';

/*Table structure for table `global_teams` */

DROP TABLE IF EXISTS `global_teams`;

CREATE TABLE `global_teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `country_id` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Global sports teams used for Out2B';

/*Table structure for table `global_turnaway` */

DROP TABLE IF EXISTS `global_turnaway`;

CREATE TABLE `global_turnaway` (
  `global_turnaway_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`global_turnaway_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='Global codes to be used for turnaway business in HotelPoint';

/*Table structure for table `help` */

DROP TABLE IF EXISTS `help`;

CREATE TABLE `help` (
  `help_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('read','unread') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ticket_status` enum('Completed','Open') DEFAULT NULL,
  `Ticket` varchar(25) NOT NULL,
  `product` varchar(255) DEFAULT NULL,
  `from_type` enum('Admin','Location','Client','Corp','Team') DEFAULT NULL,
  `from_admin` int(11) DEFAULT NULL,
  `from_location` int(11) DEFAULT NULL,
  `from_employee` int(11) DEFAULT NULL,
  `from_client` int(11) DEFAULT NULL,
  `from_corp` int(11) DEFAULT NULL,
  `from_employee_master` int(11) DEFAULT NULL,
  `sent_datetime` datetime DEFAULT NULL,
  `topic` varchar(64) DEFAULT NULL,
  `message` text,
  `caller` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `solution` text,
  `duration` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `to_type` enum('Admin','Location','Client','Corp','Team') DEFAULT NULL,
  `to_admin` int(11) DEFAULT NULL,
  `to_location` int(11) DEFAULT NULL,
  `to_employee` int(11) DEFAULT NULL,
  `to_client` int(11) DEFAULT NULL,
  `to_corp` int(11) DEFAULT NULL,
  `to_employee_master` int(11) DEFAULT NULL,
  `read_datetime` datetime DEFAULT NULL,
  `read_by_type` enum('Admin','Location','Client','Corp','Team') DEFAULT NULL,
  `read_by_admin` int(11) DEFAULT NULL,
  `read_by_location` int(11) DEFAULT NULL,
  `read_by_employee` int(11) DEFAULT NULL,
  `read_by_client` int(11) DEFAULT NULL,
  `read_by_corp` int(11) DEFAULT NULL,
  `read_by_employee_master` int(11) DEFAULT NULL,
  `ticket_type` enum('Incident','Note','Question','Problem','Task') DEFAULT NULL,
  `ticket_priority` enum('Low','Normal','High','Urgent') DEFAULT NULL,
  `ticket_internal` enum('Yes','No') DEFAULT NULL,
  `client_source` varchar(255) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`help_id`),
  KEY `help_fr_admin_idx` (`from_admin`),
  KEY `help_fr_client_idx` (`from_client`),
  KEY `help_fr_corp_idx` (`from_corp`),
  KEY `help_fr_emp_idx` (`from_employee`),
  KEY `help_fr_empmaster_idx` (`from_employee_master`),
  KEY `help_to_admin_idx` (`to_admin`),
  KEY `help_to_client_idx` (`to_client`),
  KEY `help_to_corp_idx` (`to_corp`),
  KEY `help_to_emp_idx` (`to_employee`),
  KEY `help_to_empmaster_idx` (`to_employee_master`),
  KEY `help_to_loc_idx` (`to_location`),
  KEY `help_read_by_admin_idx` (`read_by_admin`),
  KEY `help_read_by_client_idx` (`read_by_client`),
  KEY `help_read_by_corp_idx` (`read_by_corp`),
  KEY `help_read_by_emp_idx` (`read_by_employee`),
  KEY `help_read_by_empmaster_idx` (`read_by_employee_master`),
  KEY `help_read_by_loc_idx` (`read_by_location`),
  KEY `help_fr_loc` (`from_location`),
  KEY `help_stat_combo_idx` (`to_type`,`status`),
  KEY `help_tstatus_idx` (`ticket_status`),
  KEY `help_sentdt_idx` (`sent_datetime`),
  KEY `help_ticket_dt_idx` (`Ticket`,`sent_datetime`),
  KEY `help_ticket_idx` (`Ticket`),
  CONSTRAINT `help_fr_admin` FOREIGN KEY (`from_admin`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_fr_client` FOREIGN KEY (`from_client`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_fr_corp` FOREIGN KEY (`from_corp`) REFERENCES `corporate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_fr_emp` FOREIGN KEY (`from_employee`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_fr_empmaster` FOREIGN KEY (`from_employee_master`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_fr_loc` FOREIGN KEY (`from_location`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_read_by_admin` FOREIGN KEY (`read_by_admin`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_read_by_client` FOREIGN KEY (`read_by_client`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_read_by_corp` FOREIGN KEY (`read_by_corp`) REFERENCES `corporate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_read_by_emp` FOREIGN KEY (`read_by_employee`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_read_by_empmaster` FOREIGN KEY (`read_by_employee_master`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_read_by_loc` FOREIGN KEY (`read_by_location`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_to_admin` FOREIGN KEY (`to_admin`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_to_client` FOREIGN KEY (`to_client`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_to_corp` FOREIGN KEY (`to_corp`) REFERENCES `corporate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_to_emp` FOREIGN KEY (`to_employee`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_to_empmaster` FOREIGN KEY (`to_employee_master`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `help_to_loc` FOREIGN KEY (`to_location`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4023 DEFAULT CHARSET=latin1 COMMENT='Help messages sent to and from all panels to Administrator';

/*Table structure for table `help_images` */

DROP TABLE IF EXISTS `help_images`;

CREATE TABLE `help_images` (
  `help_images_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `help_id` int(11) DEFAULT NULL,
  `image_name` text,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`help_images_id`),
  UNIQUE KEY `help_images_id_UNIQUE` (`help_images_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `help_messages` */

DROP TABLE IF EXISTS `help_messages`;

CREATE TABLE `help_messages` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `help_id` int(11) DEFAULT NULL,
  `message` text,
  `created_datetime` datetime DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_on` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `help_messages_hid_idx` (`help_id`)
) ENGINE=InnoDB AUTO_INCREMENT=274 DEFAULT CHARSET=latin1;

/*Table structure for table `hotels` */

DROP TABLE IF EXISTS `hotels`;

CREATE TABLE `hotels` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `email` varchar(32) NOT NULL,
  `password` varchar(16) NOT NULL,
  `name` varchar(64) NOT NULL,
  `address` varchar(64) NOT NULL,
  `address2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `state` int(4) NOT NULL,
  `zip` varchar(16) NOT NULL,
  `country` int(4) NOT NULL,
  `telephone` varchar(32) NOT NULL,
  `longitude` varchar(12) NOT NULL,
  `latitude` varchar(12) NOT NULL,
  `hotel_image` text NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `email` (`email`),
  KEY `hotels_loc_fk` (`location_id`),
  CONSTRAINT `hotels_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Hotel records used for ConciergePoint';

/*Table structure for table `hotels_audit` */

DROP TABLE IF EXISTS `hotels_audit`;

CREATE TABLE `hotels_audit` (
  `hotels_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(8) DEFAULT NULL,
  `location_id` int(8) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT NULL,
  `email` varchar(32) DEFAULT NULL,
  `password` varchar(16) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `address2` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) DEFAULT NULL,
  `country` int(4) DEFAULT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `longitude` varchar(12) DEFAULT NULL,
  `latitude` varchar(12) DEFAULT NULL,
  `hotel_image` text,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`hotels_audit_id`),
  KEY `hotels_audit_location_fk_idx` (`location_id`),
  KEY `hotels_audit_last_datetime_idx` (`last_datetime`),
  KEY `hotels_audit_id_idx` (`id`),
  CONSTRAINT `hotels_audit_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Audit records of any changes made to a Hotel account';

/*Table structure for table `hotels_locations` */

DROP TABLE IF EXISTS `hotels_locations`;

CREATE TABLE `hotels_locations` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(8) NOT NULL,
  `location_id` int(8) DEFAULT NULL,
  `priority` int(4) NOT NULL DEFAULT '0',
  `status` enum('A','S') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hotels_locations_fk` (`hotel_id`),
  KEY `hotels_loctions_loc_fk` (`location_id`),
  CONSTRAINT `hotels_locations_fk` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hotels_loctions_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Locations linked with the a Hotel';

/*Table structure for table `ingenico_integrated_payments` */

DROP TABLE IF EXISTS `ingenico_integrated_payments`;

CREATE TABLE `ingenico_integrated_payments` (
  `ingenico_integrated_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `split` enum('No','Yes') DEFAULT 'No',
  `transactionNo` varchar(50) DEFAULT NULL,
  `client_order_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `omnivore_tickets_id` varchar(45) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `seat` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `subtotal` varchar(45) DEFAULT NULL,
  `tax` varchar(45) DEFAULT NULL,
  `tip` varchar(45) DEFAULT NULL,
  `payment` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `changedue` varchar(45) DEFAULT NULL,
  `response_result_code` varchar(45) DEFAULT NULL,
  `response_result_text` varchar(45) DEFAULT NULL,
  `response_auth_code` varchar(45) DEFAULT NULL,
  `response_avs_response` varchar(45) DEFAULT NULL,
  `response_bogus_account_num` varchar(45) DEFAULT NULL,
  `response_card_type` varchar(45) DEFAULT NULL,
  `response_cv_response` varchar(45) DEFAULT NULL,
  `response_host_code` varchar(45) DEFAULT NULL,
  `response_host_response` varchar(45) DEFAULT NULL,
  `response_message` varchar(45) DEFAULT NULL,
  `response_ref_num` varchar(45) DEFAULT NULL,
  `response_raw_response` varchar(45) DEFAULT NULL,
  `response_remaining_balance` decimal(10,2) DEFAULT NULL,
  `response_extra_balance` decimal(10,2) DEFAULT NULL,
  `response_requested_amt` decimal(10,2) DEFAULT NULL,
  `response_timestamp` datetime DEFAULT NULL,
  `response_entry_mode` varchar(45) DEFAULT NULL,
  `response_href` varchar(45) DEFAULT NULL,
  `gc_number` varchar(45) DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending','Failure','Wh_Error','Pre_Auth','Sending') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `async_status` enum('No','Processing','Finished','Failed') DEFAULT 'No',
  `payment_error` varchar(45) DEFAULT NULL,
  `show_retry_popup` enum('Yes','No') DEFAULT 'No',
  `make_a_payment` text,
  `make_payment_url` text,
  `id_pay` varchar(45) DEFAULT NULL,
  `processed_on` enum('DataPoint','Payment Matcher') DEFAULT 'DataPoint',
  `processed_on_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ingenico_integrated_payments_id`),
  KEY `ingenico_integrated_payments_employee_id_idk` (`employee_id`),
  KEY `ingenico_integrated_payments_location_id_idk` (`location_id`),
  KEY `ingenico_integrated_payments_opened_at_idk` (`opened_at`),
  KEY `ingenico_integrated_payments_processed_idk` (`processed`),
  KEY `ingenico_integrated_payments_ticket_idk` (`ticket`),
  KEY `ingenico_integrated_payments_location_ov_tik_idk` (`location_id`,`omnivore_tickets_id`),
  KEY `ingenico_integrated_payments_location_ov_tik_stat_idk` (`location_id`,`omnivore_tickets_id`,`status`),
  KEY `ingenico_integrated_payments_location_tranNo_idk` (`location_id`,`transactionNo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `ingenico_integrated_payments_pending` */

DROP TABLE IF EXISTS `ingenico_integrated_payments_pending`;

CREATE TABLE `ingenico_integrated_payments_pending` (
  `ingenico_integrated_payments_pending_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `split` enum('No','Yes') DEFAULT 'No',
  `tender_name` varchar(45) DEFAULT NULL,
  `transactionNo` int(11) DEFAULT NULL,
  `client_order_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `omnivore_tickets_id` varchar(45) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `seat` int(11) DEFAULT '99',
  `name` varchar(45) DEFAULT NULL,
  `subtotal` varchar(45) DEFAULT NULL,
  `tax` varchar(45) DEFAULT NULL,
  `tip` varchar(45) DEFAULT NULL,
  `payment` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `changedue` varchar(45) DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending','Failure') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `make_a_payment` text,
  `id_pay` varchar(45) DEFAULT NULL,
  `request` text,
  `response` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ingenico_integrated_payments_pending_id`),
  KEY `ingenico_integrated_payments_pending_employee_id_idk` (`employee_id`),
  KEY `ingenico_integrated_payments_pending_location_id_idk` (`location_id`),
  KEY `ingenico_integrated_payments_pending_opened_at_idk` (`opened_at`),
  KEY `ingenico_integrated_payments_pending_processed_idk` (`processed`),
  KEY `ingenico_integrated_payments_pending_ticket_idk` (`ticket`),
  KEY `ingenico_integrated_payments_pending_loc_tik_idk` (`location_id`,`omnivore_tickets_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `ingenico_nonintegrated_payments` */

DROP TABLE IF EXISTS `ingenico_nonintegrated_payments`;

CREATE TABLE `ingenico_nonintegrated_payments` (
  `ingenico_nonintegrated_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `customer` varchar(45) DEFAULT NULL,
  `doctor` varchar(45) DEFAULT NULL,
  `reason` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `tip` decimal(10,2) DEFAULT NULL,
  `payment` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `response_result_code` varchar(45) DEFAULT NULL,
  `response_result_txt` varchar(45) DEFAULT NULL,
  `response_auth_code` varchar(45) DEFAULT NULL,
  `response_avs_response` varchar(45) DEFAULT NULL,
  `response_bogus_account_num` varchar(45) DEFAULT NULL,
  `response_card_type` varchar(45) DEFAULT NULL,
  `response_cv_response` varchar(45) DEFAULT NULL,
  `response_host_code` varchar(45) DEFAULT NULL,
  `response_host_response` varchar(45) DEFAULT NULL,
  `response_message` varchar(45) DEFAULT NULL,
  `response_ref_num` varchar(45) DEFAULT NULL,
  `response_raw_response` varchar(45) DEFAULT NULL,
  `response_remaining_balance` decimal(10,2) DEFAULT NULL,
  `response_extra_balance` decimal(10,2) DEFAULT NULL,
  `response_requested_amt` decimal(10,2) DEFAULT NULL,
  `response_timestamp` datetime DEFAULT NULL,
  `response_href` varchar(45) DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ingenico_nonintegrated_payments_id`),
  KEY `ingenico_nonintegrated_payments_employee_id_idk` (`employee_id`),
  KEY `ingenico_nonintegrated_payments_location_id_idk` (`location_id`),
  KEY `ingenico_nonintegrated_payments_opened_at_idk` (`opened_at`),
  KEY `ingenico_nonintegrated_payments_processed_idk` (`processed`),
  KEY `ingenico_nonintegrated_payments_ticket_idk` (`ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `inventory_groups` */

DROP TABLE IF EXISTS `inventory_groups`;

CREATE TABLE `inventory_groups` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `group_id` varchar(32) NOT NULL,
  `priority` int(8) NOT NULL,
  `description` text NOT NULL,
  `Market` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=utf8 COMMENT='Global groups used for inventory items';

/*Table structure for table `inventory_item_unittype` */

DROP TABLE IF EXISTS `inventory_item_unittype`;

CREATE TABLE `inventory_item_unittype` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `unit_type` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `conversion_group` enum('weight','volume','package') DEFAULT NULL,
  `factor` varchar(35) DEFAULT NULL COMMENT 'Conversion factor of base unit.\nBase units are:\nweight - ounce\nvolume - fluid ounce\npackage - ???',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=latin1 COMMENT='Global unit types used for inventory items';

/*Table structure for table `inventory_items` */

DROP TABLE IF EXISTS `inventory_items`;

CREATE TABLE `inventory_items` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `item_id` varchar(25) NOT NULL,
  `inv_group_id` int(8) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `description` text NOT NULL,
  `notes` text,
  `manufacturer` varchar(64) DEFAULT NULL,
  `model_number` varchar(64) DEFAULT NULL,
  `brand` varchar(64) DEFAULT NULL,
  `manufacturer_barcode` varchar(64) DEFAULT NULL,
  `unit_type` int(8) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `taxable` enum('yes','no') DEFAULT 'yes',
  `color` varchar(30) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `type` varchar(25) DEFAULT NULL,
  `vendor_default` int(11) DEFAULT NULL,
  `vendor_id` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_dt` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `Last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_items_group_fk` (`inv_group_id`),
  KEY `inventory_items_unittype_fk_idx` (`unit_type`),
  CONSTRAINT `inventory_items_group_fk` FOREIGN KEY (`inv_group_id`) REFERENCES `inventory_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_items_unittype_fk` FOREIGN KEY (`unit_type`) REFERENCES `inventory_item_unittype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7263 DEFAULT CHARSET=utf8 COMMENT='Global inventory items used by vendors and locations';

/*Table structure for table `inventory_items_color` */

DROP TABLE IF EXISTS `inventory_items_color`;

CREATE TABLE `inventory_items_color` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `code` varchar(12) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `inventory_items_size` */

DROP TABLE IF EXISTS `inventory_items_size`;

CREATE TABLE `inventory_items_size` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `code` varchar(12) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `inventory_items_type` */

DROP TABLE IF EXISTS `inventory_items_type`;

CREATE TABLE `inventory_items_type` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `code` varchar(12) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `inventory_market` */

DROP TABLE IF EXISTS `inventory_market`;

CREATE TABLE `inventory_market` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Table structure for table `job_type` */

DROP TABLE IF EXISTS `job_type`;

CREATE TABLE `job_type` (
  `job_id` int(8) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `description` tinytext NOT NULL,
  `business_type` int(10) DEFAULT NULL,
  PRIMARY KEY (`job_id`),
  KEY `job_type_business_type_idx` (`business_type`),
  CONSTRAINT `job_type_business_type` FOREIGN KEY (`business_type`) REFERENCES `location_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3733 DEFAULT CHARSET=latin1 COMMENT='Global types used for job categorization in StaffPoint';

/*Table structure for table `keywords` */

DROP TABLE IF EXISTS `keywords`;

CREATE TABLE `keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(45) NOT NULL,
  `count` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Used to hold keywords for various booking websites';

/*Table structure for table `location_ads` */

DROP TABLE IF EXISTS `location_ads`;

CREATE TABLE `location_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `ads_temp_location_id` int(11) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT NULL,
  `type` enum('Coupon','Video','Spotlight','Reservation Incentive','Order Incentive','Booking Incentive') DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `no_of_views` int(11) DEFAULT NULL,
  `description` text,
  `page` varchar(255) DEFAULT NULL,
  `interactive` enum('Yes','No') DEFAULT 'No',
  `video` text,
  `image` text,
  `qrcode` text,
  `duration` int(11) unsigned NOT NULL DEFAULT '8',
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `dow` text,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `corporate_image` text,
  `rect_ads_image` text,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `Last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `location_ads_fk` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=902 DEFAULT CHARSET=utf8 COMMENT='Ads put out by a location with media';

/*Table structure for table `location_ads_clicks` */

DROP TABLE IF EXISTS `location_ads_clicks`;

CREATE TABLE `location_ads_clicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `temp_location_id` int(11) DEFAULT NULL,
  `mac_address` varchar(45) NOT NULL,
  `ads_id` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `clicks` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `viewed_by_client_id` int(11) NOT NULL,
  `created_on` varchar(50) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_ads_clicks_fk` (`ads_id`),
  KEY `location_ads_clicks_loc_fk` (`location_id`),
  KEY `location_ads_client_fk_idx` (`viewed_by_client_id`),
  CONSTRAINT `location_ads_clicks_fk` FOREIGN KEY (`ads_id`) REFERENCES `location_ads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_ads_client_fk` FOREIGN KEY (`viewed_by_client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `locations_ads_clicks_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=858 DEFAULT CHARSET=utf8 COMMENT='Records of each time a user views a location_ads';

/*Table structure for table `location_boarding_checklist` */

DROP TABLE IF EXISTS `location_boarding_checklist`;

CREATE TABLE `location_boarding_checklist` (
  `location_boarding_checklist_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `status` enum('Completed','Not Completed') DEFAULT 'Completed',
  `description` text,
  `global_checklist_id` int(11) DEFAULT NULL,
  `details` text,
  `perform_status` enum('Failed','Successful') DEFAULT NULL,
  `technician` varchar(100) DEFAULT NULL,
  `technician_3rd_party` int(11) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `conference_number` varchar(255) DEFAULT NULL,
  `conference_url` varchar(255) DEFAULT NULL,
  `ov_line` varchar(255) DEFAULT NULL,
  `ov_acct_email` varchar(100) DEFAULT NULL,
  `merchant_line` varchar(255) DEFAULT NULL,
  `windows_user` varchar(100) DEFAULT NULL,
  `windows_password` varchar(100) DEFAULT NULL,
  `pos_manager_user` varchar(100) DEFAULT NULL,
  `pos_manager_password` varchar(100) DEFAULT NULL,
  `firewall` enum('Whitelisted','Blocked') DEFAULT NULL,
  `cmc_account` varchar(100) DEFAULT NULL,
  `traking_number` varchar(100) DEFAULT NULL,
  `hardware_req` enum('Yes','No') DEFAULT 'No',
  `hardware_type` varchar(100) DEFAULT NULL,
  `hardware_purchaser` varchar(100) DEFAULT NULL,
  `hardware_transaction_no` varchar(100) DEFAULT NULL,
  `installation_type` enum('Remote','Onsite') DEFAULT NULL,
  `event_type` int(2) DEFAULT NULL,
  `type` enum('Payments','Ordering','Payments & Ordering','Interceptor') DEFAULT NULL,
  `created_on` varchar(100) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(100) DEFAULT NULL,
  `last_by` varchar(50) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_boarding_checklist_id`),
  UNIQUE KEY `location_boarding_checklist_id_UNIQUE` (`location_boarding_checklist_id`),
  KEY `location_boarding_checklist_loc_stat_idx` (`location_id`,`global_checklist_id`,`perform_status`)
) ENGINE=InnoDB AUTO_INCREMENT=9864 DEFAULT CHARSET=latin1;

/*Table structure for table `location_boarding_schedule` */

DROP TABLE IF EXISTS `location_boarding_schedule`;

CREATE TABLE `location_boarding_schedule` (
  `location_boarding_schedule_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `global_checklist_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `stime` time DEFAULT NULL,
  `etime` time DEFAULT NULL,
  `details` text,
  `description` varchar(255) DEFAULT NULL,
  `event_type` enum('Precheck','Integration','Installation & Training','Support','Additional Training') DEFAULT NULL,
  `type` text,
  `conference_number` varchar(255) DEFAULT NULL,
  `conference_url` varchar(255) DEFAULT NULL,
  `perform_status` enum('Failed','Successful') DEFAULT NULL,
  `technician` varchar(100) DEFAULT NULL,
  `technician_3rd_party` varchar(100) DEFAULT NULL,
  `ov_acct_email` varchar(100) DEFAULT NULL,
  `ov_line` varchar(255) DEFAULT NULL,
  `merchant_line` varchar(255) DEFAULT NULL,
  `sType` varchar(255) DEFAULT NULL,
  `created_on` varchar(100) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(100) DEFAULT NULL,
  `last_by` varchar(50) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_boarding_schedule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;

/*Table structure for table `location_booking` */

DROP TABLE IF EXISTS `location_booking`;

CREATE TABLE `location_booking` (
  `book_ID` int(11) NOT NULL AUTO_INCREMENT,
  `location` int(11) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `room_type` int(11) DEFAULT NULL,
  `adult` int(11) DEFAULT NULL,
  `ar_date` varchar(45) DEFAULT NULL,
  `dr_date` varchar(45) DEFAULT NULL,
  `rate` varchar(45) DEFAULT NULL,
  `taxprice` varchar(45) DEFAULT NULL,
  `rooms` int(11) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `children` int(11) DEFAULT NULL,
  `country` int(4) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `postal_code` int(11) DEFAULT NULL,
  `address1` varchar(64) DEFAULT NULL,
  `requirement` varchar(256) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `transaction_id` mediumtext,
  `promotion_code` varchar(45) DEFAULT NULL,
  `amount_paid` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`book_ID`),
  KEY `location_booking_loc_fk_idx` (`location`),
  CONSTRAINT `location_booking_loc_fk` FOREIGN KEY (`location`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=315 DEFAULT CHARSET=latin1 COMMENT='Temporarily stores booking information for external API';

/*Table structure for table `location_cash_bank` */

DROP TABLE IF EXISTS `location_cash_bank`;

CREATE TABLE `location_cash_bank` (
  `location_cash_bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `bank_name` varchar(32) NOT NULL,
  `started` datetime NOT NULL,
  `started_amount` decimal(14,2) DEFAULT NULL,
  `ended` datetime DEFAULT NULL,
  `ended_amount` decimal(14,2) DEFAULT NULL,
  `ended_amount_details` text,
  `paidout` decimal(14,2) DEFAULT NULL,
  `pettycash` decimal(14,2) DEFAULT NULL,
  `other` decimal(14,2) DEFAULT NULL,
  `overandshort` decimal(14,2) DEFAULT NULL,
  `difference` decimal(14,2) DEFAULT NULL,
  `verified_by_employee_id` int(11) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_cash_bank_id`),
  KEY `location_cash_bank_location_id_fk_idx` (`location_id`),
  CONSTRAINT `location_cash_bank_location_id_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `location_cc_batches` */

DROP TABLE IF EXISTS `location_cc_batches`;

CREATE TABLE `location_cc_batches` (
  `location_cc_batches_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('Inprogress','Processed') NOT NULL,
  `batch_date` datetime DEFAULT NULL,
  `batch_num_sales` int(11) DEFAULT NULL,
  `batch_num_refunds` int(11) DEFAULT NULL,
  `batch_total` decimal(14,2) DEFAULT NULL,
  `processed_id` varchar(64) DEFAULT NULL,
  `processed_total` decimal(14,2) DEFAULT NULL,
  `processed_by` varchar(45) DEFAULT NULL,
  `processed_on` varchar(45) DEFAULT NULL,
  `processed_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_cc_batches_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `location_chart_of_account` */

DROP TABLE IF EXISTS `location_chart_of_account`;

CREATE TABLE `location_chart_of_account` (
  `location_chart_of_account_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `global_chart_account_id` int(11) NOT NULL,
  `code` varchar(32) DEFAULT NULL,
  `type` enum('Assets','Liabilities','Equity','Revenue','Cost of Goods Sold','Payroll','Other Expenses','Fixed Charges') DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `description` longtext,
  `reporting_type` enum('Balance Sheet','Profit & Loss') DEFAULT NULL,
  PRIMARY KEY (`location_chart_of_account_id`),
  KEY `location_chart_of_account_loc_fk_idx` (`location_id`),
  CONSTRAINT `location_chart_of_account_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='All public accounts for a location, used in general ledger';

/*Table structure for table `location_chat` */

DROP TABLE IF EXISTS `location_chat`;

CREATE TABLE `location_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `message` text,
  `mobile` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_chat_loc_fk` (`location_id`),
  KEY `location_chat_fk` (`client_id`),
  CONSTRAINT `location_chat_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_chat_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Records of real-time business information from clients';

/*Table structure for table `location_checklist` */

DROP TABLE IF EXISTS `location_checklist`;

CREATE TABLE `location_checklist` (
  `checklist_id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `checklist_name` varchar(32) NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  `dow_mon` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `dow_tue` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `dow_wed` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `dow_thr` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `dow_fri` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `dow_sat` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `dow_sun` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `department` varchar(64) DEFAULT NULL,
  `dow_mon_employe_id` int(11) DEFAULT NULL,
  `dow_tue_employe_id` int(11) DEFAULT NULL,
  `dow_wed_employe_id` int(11) DEFAULT NULL,
  `dow_thr_employe_id` int(11) DEFAULT NULL,
  `dow_fri_employe_id` int(11) DEFAULT NULL,
  `dow_sat_employe_id` int(11) DEFAULT NULL,
  `dow_sun_employe_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) NOT NULL,
  `last_on` varchar(45) NOT NULL,
  `last_datetime` datetime NOT NULL,
  PRIMARY KEY (`checklist_id`),
  KEY `location_checklist_loc_fk` (`location_id`),
  CONSTRAINT `location_checklist_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=422 DEFAULT CHARSET=latin1 COMMENT='Main checklist information for a location';

/*Table structure for table `location_checklist_check` */

DROP TABLE IF EXISTS `location_checklist_check`;

CREATE TABLE `location_checklist_check` (
  `checklistchecks_id` int(8) NOT NULL AUTO_INCREMENT,
  `checklist_id` int(8) NOT NULL,
  `checklistdetails_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `datetime` datetime NOT NULL,
  `answer` varchar(64) NOT NULL,
  `status` enum('Open','Closed') DEFAULT 'Closed',
  `created_by` varchar(32) NOT NULL,
  `created_on` varchar(32) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`checklistchecks_id`),
  KEY `location_checklist_check_checklist_details_fk` (`checklistdetails_id`),
  KEY `location_checklist_check_fk_idx` (`checklist_id`),
  KEY `location_checklist_check_loc_idx` (`location_id`),
  CONSTRAINT `location_checklist_check_checklist_details_fk` FOREIGN KEY (`checklistdetails_id`) REFERENCES `location_checklist_details` (`checklistdetails_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_checklist_check_fk` FOREIGN KEY (`checklist_id`) REFERENCES `location_checklist` (`checklist_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_checklist_check_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=36450 DEFAULT CHARSET=latin1 COMMENT='Details of a specific check done at a location';

/*Table structure for table `location_checklist_details` */

DROP TABLE IF EXISTS `location_checklist_details`;

CREATE TABLE `location_checklist_details` (
  `checklistdetails_id` int(8) NOT NULL AUTO_INCREMENT,
  `checklist_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `description` varchar(64) NOT NULL,
  `instructions` text NOT NULL,
  `priority` int(4) NOT NULL,
  `type` enum('Checkmark','Answer','Category') NOT NULL,
  `required` enum('Yes','No') NOT NULL,
  `created_by` varchar(32) NOT NULL,
  `created_on` varchar(32) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`checklistdetails_id`),
  KEY `location_checklist_details_checklist_fk` (`checklist_id`),
  KEY `location_checklist_loc_idx` (`location_id`),
  CONSTRAINT `location_checklist_details_checklist_fk` FOREIGN KEY (`checklist_id`) REFERENCES `location_checklist` (`checklist_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_checklist_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20081 DEFAULT CHARSET=latin1 COMMENT='Steps that need to be done for a checklist at a location';

/*Table structure for table `location_clicks` */

DROP TABLE IF EXISTS `location_clicks`;

CREATE TABLE `location_clicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `viewd_by_client_id` int(11) DEFAULT NULL,
  `created_on` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_clicks_fk` (`location_id`),
  KEY `location_clicks_client_fk_idx` (`viewd_by_client_id`),
  CONSTRAINT `location_clicks_client_fk` FOREIGN KEY (`viewd_by_client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_clicks_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8 COMMENT='Records of each time a user views the location';

/*Table structure for table `location_crs_text` */

DROP TABLE IF EXISTS `location_crs_text`;

CREATE TABLE `location_crs_text` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `greeting_text` varchar(256) DEFAULT NULL,
  `reservation_text` varchar(256) DEFAULT NULL,
  `order_text` varchar(256) DEFAULT NULL,
  `payment_text` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`location_id`),
  CONSTRAINT `key_crs_text_locations` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Text to display for a location in the AdminPanel CRS module';

/*Table structure for table `location_departments` */

DROP TABLE IF EXISTS `location_departments`;

CREATE TABLE `location_departments` (
  `location_departments_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `global_departments_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `chart_of_account` int(11) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_departments_id`),
  UNIQUE KEY `idlocation_departments_UNIQUE` (`location_departments_id`),
  KEY `location_departments_loc_idx` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=301 DEFAULT CHARSET=utf8;

/*Table structure for table `location_display` */

DROP TABLE IF EXISTS `location_display`;

CREATE TABLE `location_display` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `created_on` varchar(30) NOT NULL,
  `viewd_by_client` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_display_fk` (`location_id`),
  KEY `location_display_viewed_by_client_idx` (`viewd_by_client`),
  CONSTRAINT `location_display_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_display_viewed_by_client` FOREIGN KEY (`viewd_by_client`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1802 DEFAULT CHARSET=utf8 COMMENT='Records of a location being displayed on booking sites';

/*Table structure for table `location_dm_images` */

DROP TABLE IF EXISTS `location_dm_images`;

CREATE TABLE `location_dm_images` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `priority` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `glass_add` enum('yes','no') DEFAULT NULL,
  `menu_id` int(10) DEFAULT NULL,
  `menu_group` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_dm_images_fk` (`location_id`),
  KEY `location_dm_images_menu_group_idx` (`menu_group`),
  KEY `location_dm_images_menus_idx` (`menu_id`),
  CONSTRAINT `location_dm_images_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_dm_images_menu_group` FOREIGN KEY (`menu_group`) REFERENCES `location_menu_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_dm_images_menus` FOREIGN KEY (`menu_id`) REFERENCES `location_menus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='Stores images to be displayed on digital menu app';

/*Table structure for table `location_emails` */

DROP TABLE IF EXISTS `location_emails`;

CREATE TABLE `location_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `location_emails_formats` */

DROP TABLE IF EXISTS `location_emails_formats`;

CREATE TABLE `location_emails_formats` (
  `email_id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `campaign` enum('annual','birthday','monthly','new client','other','quarterly','anniversary') NOT NULL,
  `describe_other` varchar(45) DEFAULT NULL,
  `description` text NOT NULL,
  `subject` varchar(78) NOT NULL,
  `footer` text NOT NULL,
  `footer_image` text NOT NULL,
  `body` text NOT NULL,
  `body_image` text NOT NULL,
  `header` text NOT NULL,
  `header_image` text NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`email_id`),
  KEY `location_emails_formats_fk` (`location_id`),
  CONSTRAINT `location_emails_formats_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Formats for emails that are sent out by a location';

/*Table structure for table `location_events` */

DROP TABLE IF EXISTS `location_events`;

CREATE TABLE `location_events` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('A','S') NOT NULL,
  `event_name` tinytext NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL COMMENT 'This is event start date',
  `event_enddate` date DEFAULT NULL,
  `event_starttime` time NOT NULL,
  `event_endtime` time DEFAULT NULL,
  `image` text NOT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `location_events_fk` (`location_id`),
  CONSTRAINT `location_events_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Events created by clients and locations for a location';

/*Table structure for table `location_events_clicks` */

DROP TABLE IF EXISTS `location_events_clicks`;

CREATE TABLE `location_events_clicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(8) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location_id` int(8) NOT NULL,
  `viewed_by_client_id` int(11) NOT NULL,
  `created_on` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_events_clicks_fk` (`event_id`),
  KEY `location_events_location_id_idx` (`location_id`),
  KEY `locations_events_client_fk_idx` (`viewed_by_client_id`),
  CONSTRAINT `location_events_clicks_fk` FOREIGN KEY (`event_id`) REFERENCES `location_events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_events_location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `locations_events_client_fk` FOREIGN KEY (`viewed_by_client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Records of each time a client views the event';

/*Table structure for table `location_expensetab_accounts` */

DROP TABLE IF EXISTS `location_expensetab_accounts`;

CREATE TABLE `location_expensetab_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `status` enum('active','suspended','cancelled','pending','inactive') NOT NULL,
  `directbill` enum('yes','no') NOT NULL DEFAULT 'no',
  `directbill_default_terms` varchar(45) DEFAULT NULL,
  `directbill_default_limit` varchar(45) DEFAULT NULL,
  `account_type` enum('checking','savings','business_checking') DEFAULT NULL,
  `bank_account_name` varchar(45) DEFAULT NULL,
  `bank_name` varchar(45) DEFAULT NULL,
  `bank_routing` varchar(45) DEFAULT NULL,
  `bank_account` varchar(45) DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_et_acct_loc_idx` (`location_id`),
  CONSTRAINT `location_et_acct_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COMMENT='Expensetab Account information for a Location';

/*Table structure for table `location_expensetab_banks` */

DROP TABLE IF EXISTS `location_expensetab_banks`;

CREATE TABLE `location_expensetab_banks` (
  `loc_expensetab_banks_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `bank_type` enum('Checking','Savings','Other') NOT NULL DEFAULT 'Checking',
  `bank_short_name` varchar(45) NOT NULL,
  `bank_account` varchar(45) NOT NULL,
  `bank_currency_id` int(8) NOT NULL,
  `bank_name` varchar(45) NOT NULL,
  `bank_address` varchar(64) DEFAULT NULL,
  `bank_address2` varchar(64) DEFAULT NULL,
  `bank_country` int(4) NOT NULL,
  `bank_city` varchar(64) DEFAULT NULL,
  `bank_state` int(4) DEFAULT NULL,
  `bank_zip` varchar(16) DEFAULT NULL,
  `bank_telephone` varchar(32) DEFAULT NULL,
  `bank_representative` varchar(32) DEFAULT NULL,
  `bank_routing` varchar(45) DEFAULT NULL,
  `used_for_expensetab` enum('No','Yes') NOT NULL DEFAULT 'No',
  `used_for_deposits` enum('No','Yes') NOT NULL DEFAULT 'No',
  `uesd_for_receivables` enum('No','Yes') NOT NULL DEFAULT 'No',
  `used_for_payroll` enum('No','Yes') NOT NULL DEFAULT 'No',
  `used_for_payments` enum('Yes','No') NOT NULL DEFAULT 'No',
  `bank_starting_balance` decimal(14,2) NOT NULL DEFAULT '0.00',
  `bank_startdate` date NOT NULL,
  `bank_current_balance` decimal(14,2) DEFAULT NULL,
  `chart_of_account` int(11) DEFAULT NULL,
  `use_cc_or_bank` enum('CC','Bank') DEFAULT NULL,
  `cc_debit` enum('Yes','No') DEFAULT NULL,
  `cc_type` enum('Visa','MasterCard','American Express','Discover Card') DEFAULT NULL,
  `cc_number` varchar(64) DEFAULT NULL,
  `cc_exp` varchar(12) DEFAULT NULL,
  `cc_name` varchar(45) DEFAULT NULL,
  `cc_cvv` int(11) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`loc_expensetab_banks_id`),
  KEY `loc_et_bank_cur_fk_idx` (`bank_currency_id`),
  KEY `loc_et_bank_loc_fk_idx` (`location_id`),
  CONSTRAINT `loc_et_bank_cur_fk` FOREIGN KEY (`bank_currency_id`) REFERENCES `global_currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `loc_et_bank_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='List the various bankaccounts of a location';

/*Table structure for table `location_expensetab_banks_transactions` */

DROP TABLE IF EXISTS `location_expensetab_banks_transactions`;

CREATE TABLE `location_expensetab_banks_transactions` (
  `loc_expensetab_bank_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `bank_chart_of_account` int(11) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `type` enum('Entry','Expense','Deposit','Payment','Adjustment','Other') NOT NULL,
  `date` date NOT NULL,
  `chart_of_account_id` int(11) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `reference` varchar(128) DEFAULT NULL,
  `amount` decimal(14,2) NOT NULL,
  `reconciled` enum('Yes','No') DEFAULT 'No',
  `reconciled_datetime` datetime DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`loc_expensetab_bank_detail_id`),
  KEY `loc_et_bank_trans_bank_fk_idx` (`bank_id`),
  KEY `loc_et_bank_trans_loc_fk_idx` (`location_id`),
  CONSTRAINT `loc_et_bank_trans_bank_fk` FOREIGN KEY (`bank_id`) REFERENCES `location_expensetab_banks` (`loc_expensetab_banks_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `loc_et_bank_trans_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Contains the transaction of the bank account';

/*Table structure for table `location_expensetab_directbill` */

DROP TABLE IF EXISTS `location_expensetab_directbill`;

CREATE TABLE `location_expensetab_directbill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `direct_billing` enum('yes','no') NOT NULL DEFAULT 'no',
  `terms` varchar(45) DEFAULT NULL,
  `limit` varchar(45) DEFAULT NULL,
  `account_type` enum('checking','savings','business_checking') DEFAULT NULL,
  `bank_account_name` varchar(45) DEFAULT NULL,
  `bank_name` varchar(45) DEFAULT NULL,
  `bank_routing` varchar(45) DEFAULT NULL,
  `bank_account` varchar(45) DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_et_directbill_comp_idx` (`company_id`),
  KEY `location_et_directbill_loc_idx` (`location_id`),
  CONSTRAINT `location_et_directbill_comp` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_et_directbill_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Expensetab DirectBill information for a location';

/*Table structure for table `location_expensetab_directbill_clients` */

DROP TABLE IF EXISTS `location_expensetab_directbill_clients`;

CREATE TABLE `location_expensetab_directbill_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `directbill_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `balance` decimal(10,2) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_et_directbill_client_fk_idx` (`client_id`),
  KEY `location_et_directbill_client_directbill_fk_idx` (`directbill_id`),
  CONSTRAINT `location_et_directbill_client_directbill_fk` FOREIGN KEY (`directbill_id`) REFERENCES `location_expensetab_directbill` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_et_directbill_client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Clients approved to use a locations directbill account';

/*Table structure for table `location_form_folio` */

DROP TABLE IF EXISTS `location_form_folio`;

CREATE TABLE `location_form_folio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(10) DEFAULT NULL,
  `folio_style` enum('Standard','Standard 2','Europe 1','Europe 2','Brazil') DEFAULT NULL,
  `header_image` varchar(100) DEFAULT NULL,
  `header_text` text,
  `footer_image` varchar(100) DEFAULT NULL,
  `footer_text` text,
  `from_email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_form_folio_location_idx` (`location_id`),
  CONSTRAINT `location_form_folio_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores general formatting information for a Hotel folio';

/*Table structure for table `location_form_hotelconfirmation` */

DROP TABLE IF EXISTS `location_form_hotelconfirmation`;

CREATE TABLE `location_form_hotelconfirmation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(10) DEFAULT NULL,
  `header_image` varchar(100) DEFAULT NULL,
  `header_text` text,
  `footer_image` varchar(100) DEFAULT NULL,
  `footer_text` text,
  `from_email` varchar(45) DEFAULT NULL,
  `send_to_email` varchar(45) DEFAULT NULL,
  `pay_email` varchar(255) DEFAULT NULL,
  `room_type` varchar(255) DEFAULT NULL,
  `available` varchar(255) DEFAULT NULL,
  `reservation` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `pay_url` varchar(255) DEFAULT NULL,
  `api_username` varchar(255) DEFAULT NULL,
  `api_password` varchar(255) DEFAULT NULL,
  `api_signature` varchar(255) DEFAULT NULL,
  `api_endpoint` varchar(255) DEFAULT NULL,
  `booking_down` enum('Yes','No') DEFAULT 'Yes',
  `confirmation_style` enum('Standard','Standard 2') DEFAULT 'Standard',
  `confirmation_color` enum('Black','Blue','Green','Orange','Red','Violet','Yellow') DEFAULT 'Black',
  `deposit_amount` enum('First Night','All Nights','50% Deposit') DEFAULT 'First Night',
  PRIMARY KEY (`id`),
  KEY `location_form_conf_location_idx` (`location_id`),
  CONSTRAINT `location_form_conf_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Stores confirmation format and external API information';

/*Table structure for table `location_form_regcard` */

DROP TABLE IF EXISTS `location_form_regcard`;

CREATE TABLE `location_form_regcard` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(10) DEFAULT NULL,
  `regcard_style` enum('Standard','European','Brazil') DEFAULT NULL,
  `header_image` varchar(100) DEFAULT NULL,
  `header_text` text,
  `footer_image` varchar(100) DEFAULT NULL,
  `footer_text` text,
  `from_email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_form_regcard_location_idx` (`location_id`),
  CONSTRAINT `location_form_regcard_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores formatting information for a hotels registration card';

/*Table structure for table `location_giftcard_transactions` */

DROP TABLE IF EXISTS `location_giftcard_transactions`;

CREATE TABLE `location_giftcard_transactions` (
  `GC_trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `GC_id` int(11) NOT NULL,
  `Type` enum('Payment','Refund','Deposit','Writeoff') CHARACTER SET latin1 NOT NULL,
  `Date` date NOT NULL,
  `Sales_id` int(11) DEFAULT NULL,
  `Order_id` int(11) DEFAULT NULL,
  `Hotelacct_id` int(11) DEFAULT NULL,
  `Amount` decimal(14,2) NOT NULL,
  `Created_by` int(11) NOT NULL,
  `Created_on` varchar(45) CHARACTER SET latin1 NOT NULL,
  `Created_datetime` datetime NOT NULL,
  PRIMARY KEY (`GC_trans_id`),
  KEY `location_gc_trans_order_fk_idx` (`Order_id`),
  KEY `location_gn_trans_sales_fk_idx` (`Sales_id`),
  KEY `location_gc_trans_hotelacct_idx` (`Hotelacct_id`),
  KEY `location_gc_trans_gc_fk_idx` (`GC_id`),
  CONSTRAINT `location_gc_trans_gc_fk` FOREIGN KEY (`GC_id`) REFERENCES `location_giftcards` (`GC_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_gc_trans_hotelacct` FOREIGN KEY (`Hotelacct_id`) REFERENCES `location_hotelacct` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_gc_trans_order_fk` FOREIGN KEY (`Order_id`) REFERENCES `client_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_gc_trans_sales_fk` FOREIGN KEY (`Sales_id`) REFERENCES `client_sales` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 COMMENT='Client Gift Card transactions made at a location';

/*Table structure for table `location_giftcards` */

DROP TABLE IF EXISTS `location_giftcards`;

CREATE TABLE `location_giftcards` (
  `GC_id` int(11) NOT NULL AUTO_INCREMENT,
  `Location_id` int(11) NOT NULL,
  `GC_type` enum('Paid','Reward','Incentive','Promotion','Store Credit') CHARACTER SET latin1 NOT NULL,
  `GC_number` varchar(45) CHARACTER SET latin1 NOT NULL,
  `GC_status` enum('Active','Inactive','Suspended') NOT NULL,
  `GC_start_date` date NOT NULL,
  `GC_end_date` date NOT NULL,
  `GC_amount` decimal(14,2) NOT NULL,
  `GC_balance` decimal(14,2) NOT NULL,
  `Issued_by` varchar(45) NOT NULL,
  `Issued_reason` text CHARACTER SET latin1 NOT NULL,
  `Paid_by` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `payment_type` int(8) NOT NULL,
  `payment_code` varchar(50) NOT NULL,
  `cc_name` varchar(45) NOT NULL,
  `cc_firstname` varchar(45) NOT NULL,
  `cc_lastname` varchar(45) NOT NULL,
  `cc_number` varchar(64) NOT NULL,
  `cc_exp` varchar(12) NOT NULL,
  `cc_cvv` int(11) NOT NULL,
  `ccsecurity` varchar(30) NOT NULL,
  `authorization` varchar(50) NOT NULL,
  `exp_client_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `Created_by` int(11) NOT NULL,
  `Created_on` varchar(45) CHARACTER SET latin1 NOT NULL,
  `Created_datetime` datetime NOT NULL,
  `Last_by` int(11) DEFAULT NULL,
  `Last_on` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `Last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`GC_id`),
  KEY `location_giftcard_loc_idx` (`Location_id`),
  CONSTRAINT `location_giftcard_loc` FOREIGN KEY (`Location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 COMMENT='Client Gift Card information for a location';

/*Table structure for table `location_guest_minibar` */

DROP TABLE IF EXISTS `location_guest_minibar`;

CREATE TABLE `location_guest_minibar` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `room_id` int(8) NOT NULL,
  `menu_id` int(8) NOT NULL,
  `menu_item` int(8) NOT NULL,
  `last_inventory` date NOT NULL,
  `last_quantity` int(11) NOT NULL,
  `last_emp_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_guest_minibar_fk` (`location_id`,`menu_item`),
  KEY `location_guest_minibar_room_fk` (`room_id`),
  KEY `location_guest_minibar_item_fk` (`menu_item`),
  KEY `location_guest_minibar_last_emp_idx` (`last_emp_id`),
  KEY `location_guest_minibar_menu_idx` (`menu_id`),
  CONSTRAINT `location_guest_minibar_item_fk` FOREIGN KEY (`menu_item`) REFERENCES `location_menu_articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_guest_minibar_last_emp` FOREIGN KEY (`last_emp_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_guest_minibar_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_guest_minibar_menu` FOREIGN KEY (`menu_id`) REFERENCES `location_menus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_guest_minibar_room_fk` FOREIGN KEY (`room_id`) REFERENCES `location_rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Minibar records for a guest room';

/*Table structure for table `location_guest_minibar_movements` */

DROP TABLE IF EXISTS `location_guest_minibar_movements`;

CREATE TABLE `location_guest_minibar_movements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `minibar_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `employee_id` int(8) NOT NULL,
  `status` enum('started','closed') NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `quantity_counted` int(11) NOT NULL,
  `quantity_changed` int(11) NOT NULL,
  `quantity_added` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_guest_minibar_movements_fk` (`minibar_id`),
  KEY `location_guest_minibar_movements_emp_fk` (`employee_id`),
  KEY `location_guest_minibar_location_id` (`location_id`),
  CONSTRAINT `location_guest_minibar_location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_guest_minibar_movements_emp_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_guest_minibar_movements_fk` FOREIGN KEY (`minibar_id`) REFERENCES `location_guest_minibar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores changes in inventory for a minibar in a room';

/*Table structure for table `location_holidays` */

DROP TABLE IF EXISTS `location_holidays`;

CREATE TABLE `location_holidays` (
  `location_holidays_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `holidate_name` varchar(45) NOT NULL,
  `holiday_date` date NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_holidays_id`),
  UNIQUE KEY `location_holidays_id_UNIQUE` (`location_holidays_id`),
  KEY `location_holidays_loc_idx` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `location_hotel_bookingtype` */

DROP TABLE IF EXISTS `location_hotel_bookingtype`;

CREATE TABLE `location_hotel_bookingtype` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `global_bookingtype` int(11) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `location_hotel_bookingtype_idx` (`location_id`),
  KEY `global_bookingtype_hotel_bookingtype_idx` (`global_bookingtype`),
  CONSTRAINT `global_bookingtype_hotel_bookingtype` FOREIGN KEY (`global_bookingtype`) REFERENCES `global_bookingtypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotel_bookingtype` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1 COMMENT='Booking Types registered with a hotel location';

/*Table structure for table `location_hotel_guarantee` */

DROP TABLE IF EXISTS `location_hotel_guarantee`;

CREATE TABLE `location_hotel_guarantee` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `global_guarantee` int(11) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  `cc` enum('Yes','No') NOT NULL,
  `guarantees_booking` enum('Yes','No') NOT NULL,
  `requires_deposit` enum('Yes','No') NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `location_hotel_guarantee_idx` (`location_id`),
  KEY `global_guarantee_hotel_guarantee_idx` (`global_guarantee`),
  CONSTRAINT `global_guarantee_hotel_guarantee` FOREIGN KEY (`global_guarantee`) REFERENCES `global_guarantee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotel_guarantee` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1 COMMENT='Guarantee Types registered with a hotel location';

/*Table structure for table `location_hotel_guesttype` */

DROP TABLE IF EXISTS `location_hotel_guesttype`;

CREATE TABLE `location_hotel_guesttype` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `global_guesttype` int(11) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `location_hotel_guesttype_idx` (`location_id`),
  KEY `global_guesttype_hotel_guesttype_idx` (`global_guesttype`),
  CONSTRAINT `global_guesttype_hotel_guesttype` FOREIGN KEY (`global_guesttype`) REFERENCES `global_guesttypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotel_guesttype` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1 COMMENT='Guest Types registered with a hotel location';

/*Table structure for table `location_hotel_inventory_controls` */

DROP TABLE IF EXISTS `location_hotel_inventory_controls`;

CREATE TABLE `location_hotel_inventory_controls` (
  `location_inv_control_id` int(11) NOT NULL AUTO_INCREMENT,
  `Location_id` int(8) NOT NULL,
  `Date` date NOT NULL,
  `RoomType` int(11) DEFAULT NULL,
  `Min_rate` decimal(14,2) DEFAULT NULL,
  `Req_guarantee` enum('Yes','No') DEFAULT NULL,
  `Min_length_stay` int(2) DEFAULT NULL,
  `Rate_tier` enum('Regular','High','Low') NOT NULL DEFAULT 'Regular',
  `High_rate_type_add` enum('Fixed','Percentage') DEFAULT NULL,
  `High_rate_amount_add` decimal(14,2) DEFAULT NULL,
  `Low_rate_type_subtract` enum('Fixed','Percentage') DEFAULT NULL,
  `Low_rate_amount_sub` decimal(14,2) DEFAULT NULL,
  `Created_on` varchar(45) NOT NULL,
  `Created_by` varchar(45) NOT NULL,
  `Created_datetime` varchar(45) NOT NULL,
  PRIMARY KEY (`location_inv_control_id`),
  KEY `location_hotel_inventory_controls_loc_fk_idx` (`Location_id`),
  CONSTRAINT `location_hotel_inventory_controls_loc_fk` FOREIGN KEY (`Location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `location_hotel_inventory_restrictions` */

DROP TABLE IF EXISTS `location_hotel_inventory_restrictions`;

CREATE TABLE `location_hotel_inventory_restrictions` (
  `location_hotel_restrictions_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `date` date NOT NULL,
  `ratetype` int(11) DEFAULT NULL,
  `min_rate` decimal(14,2) DEFAULT NULL,
  `req_guarantee` enum('Yes','No') DEFAULT NULL,
  `min_lenght_stay` int(2) DEFAULT NULL,
  `available_count` int(3) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`location_hotel_restrictions_id`),
  KEY `location_hotel_inventory_restrictions_loc_fk_idx` (`location_id`),
  CONSTRAINT `location_hotel_inventory_restrictions_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `location_hotel_markettype` */

DROP TABLE IF EXISTS `location_hotel_markettype`;

CREATE TABLE `location_hotel_markettype` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `global_markettype` int(11) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `location_hotel_markettype_idx` (`location_id`),
  KEY `global_markettype_hotel_markettype_idx` (`global_markettype`),
  CONSTRAINT `global_markettype_hotel_markettype` FOREIGN KEY (`global_markettype`) REFERENCES `global_markettypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotel_markettype` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1 COMMENT='Market Types registered with a hotel location';

/*Table structure for table `location_hotel_mealrates` */

DROP TABLE IF EXISTS `location_hotel_mealrates`;

CREATE TABLE `location_hotel_mealrates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `mealtype` int(11) NOT NULL,
  `adult_price` decimal(12,2) DEFAULT NULL,
  `child1_price` decimal(12,2) DEFAULT NULL,
  `child2_price` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `keyLocation_idx` (`location_id`),
  KEY `keyMealtype` (`mealtype`),
  CONSTRAINT `keyLocation` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `keyMealtype` FOREIGN KEY (`mealtype`) REFERENCES `location_hotel_mealtype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Rate Plan information for a meal plan at a location';

/*Table structure for table `location_hotel_mealtype` */

DROP TABLE IF EXISTS `location_hotel_mealtype`;

CREATE TABLE `location_hotel_mealtype` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `global_mealtype` int(11) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  `hotel_postingtype` int(10) DEFAULT NULL COMMENT 'location_hotel_postingtype ID#',
  PRIMARY KEY (`Id`),
  KEY `location_hotel_mealtype_idx` (`location_id`),
  KEY `global_mealtype_hotel_mealtype_idx` (`global_mealtype`),
  KEY `location_hotel_mealtype_posting_idx` (`hotel_postingtype`),
  KEY `location_hotel_mealtype_loc_gtype_idx` (`location_id`,`global_mealtype`,`code`),
  CONSTRAINT `global_mealtype_hotel_mealtype` FOREIGN KEY (`global_mealtype`) REFERENCES `global_mealtypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotel_mealtype` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_hotel_mealtype_posting` FOREIGN KEY (`hotel_postingtype`) REFERENCES `location_hotel_postingtype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Meal Types registered with a hotel location';

/*Table structure for table `location_hotel_postingtype` */

DROP TABLE IF EXISTS `location_hotel_postingtype`;

CREATE TABLE `location_hotel_postingtype` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `global_postingtype` int(11) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  `auto_tax` enum('Yes','No') DEFAULT NULL,
  `taxtype_id` int(10) DEFAULT NULL,
  `charge_auto` enum('Yes','No') DEFAULT NULL,
  `charge_default_amount` float(10,2) DEFAULT NULL,
  `charge_typically` enum('Daily','On Arrival','On Departure') DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `location_hotel_postingtype_idx` (`location_id`),
  KEY `global_postingtype_hotel_postingtype_idx` (`global_postingtype`),
  KEY `location_hotel_postingtype_ch_idx` (`location_id`,`charge_auto`,`code`),
  CONSTRAINT `global_postingtype_hotel_postingtype` FOREIGN KEY (`global_postingtype`) REFERENCES `global_postingtypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotel_postingtype` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1 COMMENT='Posting Types registered with a hotel location';

/*Table structure for table `location_hotel_rates` */

DROP TABLE IF EXISTS `location_hotel_rates`;

CREATE TABLE `location_hotel_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `ratetype` int(11) NOT NULL,
  `roomtype` int(11) NOT NULL,
  `taxtype` int(11) NOT NULL,
  `mealtype` int(11) DEFAULT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `single_price` decimal(12,2) DEFAULT NULL,
  `double_price` decimal(12,2) DEFAULT NULL,
  `extra_adult_price` decimal(12,2) DEFAULT NULL,
  `child1_price` decimal(12,2) DEFAULT NULL,
  `child2_price` decimal(12,2) DEFAULT NULL,
  `currency_id` int(8) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `keyRatetype_idx` (`ratetype`),
  KEY `keyRoomtype_idx` (`roomtype`),
  KEY `location_hotel_rates_loc_fk` (`location_id`),
  KEY `location_hotel_rates_currency_idx` (`currency_id`),
  CONSTRAINT `keyRatetype` FOREIGN KEY (`ratetype`) REFERENCES `location_hotel_ratetype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `keyRoomtype` FOREIGN KEY (`roomtype`) REFERENCES `location_hotel_roomtype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotel_rates_currency` FOREIGN KEY (`currency_id`) REFERENCES `global_currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotel_rates_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=latin1 COMMENT='Rate plan information for a rate type at a location';

/*Table structure for table `location_hotel_ratetype` */

DROP TABLE IF EXISTS `location_hotel_ratetype`;

CREATE TABLE `location_hotel_ratetype` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `global_ratetype` int(11) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  `hotel_postingtype` int(10) DEFAULT NULL COMMENT 'location_hotel_postingtype ID#',
  PRIMARY KEY (`Id`),
  KEY `location_hotel_ratetype_idx` (`location_id`),
  KEY `global_ratetype_hotel_ratetype_idx` (`global_ratetype`),
  KEY `location_hotel_ratetype_posting_idx` (`hotel_postingtype`),
  CONSTRAINT `global_ratetype_hotel_ratetype` FOREIGN KEY (`global_ratetype`) REFERENCES `global_ratetypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotel_ratetype` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_hotel_ratetype_posting` FOREIGN KEY (`hotel_postingtype`) REFERENCES `location_hotel_postingtype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1 COMMENT='Rate Type registered with a hotel location';

/*Table structure for table `location_hotel_roomtype` */

DROP TABLE IF EXISTS `location_hotel_roomtype`;

CREATE TABLE `location_hotel_roomtype` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `global_roomtype` int(11) DEFAULT NULL,
  `priority` mediumint(8) NOT NULL,
  `code` varchar(32) DEFAULT NULL,
  `image` longtext,
  `short_description` varchar(255) DEFAULT NULL,
  `max_adult` int(8) DEFAULT NULL,
  `max_children` int(8) DEFAULT NULL,
  `single_price` decimal(12,2) DEFAULT NULL,
  `double_price` decimal(12,2) DEFAULT NULL,
  `extra_adult_price` decimal(12,2) DEFAULT NULL,
  `child1_price` decimal(12,2) DEFAULT NULL,
  `child2_price` decimal(12,2) DEFAULT NULL,
  `promo_description` text,
  `features` text,
  `available_online` enum('Yes','No') DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_datetime` varchar(255) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` int(11) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `location_hotel_roomtype_idx` (`location_id`),
  KEY `global_roomtype_hotel_roomtype_idx` (`global_roomtype`),
  CONSTRAINT `global_roomtype_hotel_roomtype` FOREIGN KEY (`global_roomtype`) REFERENCES `global_roomtypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotel_roomtype` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1 COMMENT='Room Types registered with a hotel location';

/*Table structure for table `location_hotel_taxtype` */

DROP TABLE IF EXISTS `location_hotel_taxtype`;

CREATE TABLE `location_hotel_taxtype` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `global_taxtype` int(11) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  `type` enum('Person','Rate') DEFAULT NULL,
  `Amount` decimal(10,4) DEFAULT NULL,
  `posting_type` int(10) DEFAULT NULL,
  `Status` enum('Active','Inactive') DEFAULT NULL,
  `Tax_Type` enum('Additional','VAT') DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `location_hotel_taxtype_idx` (`location_id`),
  KEY `global_taxtype_hotel_taxtype_idx` (`global_taxtype`),
  KEY `location_hotel_taxtype_posting_idx` (`posting_type`),
  CONSTRAINT `global_taxtype_hotel_taxtype` FOREIGN KEY (`global_taxtype`) REFERENCES `global_taxtype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotel_taxtype` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_hotel_taxtype_posting` FOREIGN KEY (`posting_type`) REFERENCES `location_hotel_postingtype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COMMENT='Tax Types registered with a hotel location';

/*Table structure for table `location_hotel_turnaway` */

DROP TABLE IF EXISTS `location_hotel_turnaway`;

CREATE TABLE `location_hotel_turnaway` (
  `location_hotel_turnaway_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `global_turnaway_id` int(11) NOT NULL,
  `code` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`location_hotel_turnaway_id`),
  KEY `location_turnaway_id_idx` (`global_turnaway_id`),
  KEY `location_turnaway_loc_id_idx` (`location_id`),
  CONSTRAINT `location_turnaway_id` FOREIGN KEY (`global_turnaway_id`) REFERENCES `global_turnaway` (`global_turnaway_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_turnaway_loc_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Codes used by a location for turnaway business';

/*Table structure for table `location_hotelacct` */

DROP TABLE IF EXISTS `location_hotelacct`;

CREATE TABLE `location_hotelacct` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `status` enum('Shopping','Reserved','Cancelled','Inhouse','Noshow','Checkout','Permanent') DEFAULT NULL,
  `location_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT '0',
  `primary_client_id` int(11) DEFAULT NULL,
  `perm_name` varchar(45) DEFAULT NULL,
  `room` int(11) DEFAULT NULL,
  `markettype` int(11) DEFAULT NULL,
  `guesttype` int(11) DEFAULT NULL,
  `roomtype` int(11) DEFAULT NULL,
  `ratetype` int(11) DEFAULT NULL,
  `mealplan` int(11) DEFAULT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  `meal_rate` decimal(10,2) DEFAULT NULL,
  `tax_type` int(11) DEFAULT NULL,
  `tax_rate` decimal(10,2) DEFAULT NULL,
  `bookingtype` int(11) DEFAULT NULL,
  `booking_account` varchar(16) DEFAULT NULL,
  `group` varchar(32) DEFAULT NULL,
  `arrival` date DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `nights` int(3) DEFAULT NULL,
  `departure` date DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `adults` int(11) DEFAULT NULL,
  `child1` int(11) DEFAULT NULL,
  `child2` int(11) DEFAULT NULL,
  `nopost` enum('Yes','No') DEFAULT NULL,
  `isvalid` varchar(1) NOT NULL DEFAULT 'N',
  `turnaway` int(11) DEFAULT NULL,
  `random_id` int(11) DEFAULT NULL,
  `isoverride` enum('Yes','No') DEFAULT 'No' COMMENT 'This is used to check if account is overrided if Yes then it will not display popup again',
  `isovercontrol` enum('Yes','No') DEFAULT 'No',
  `isoverrestriction` enum('Yes','No') DEFAULT 'No',
  `early_charge` enum('Yes','No') DEFAULT NULL,
  `review_id` int(11) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key_location_h_markettype` (`markettype`),
  KEY `key_location_h_guesttype` (`guesttype`),
  KEY `key_location_h_roomtype` (`roomtype`),
  KEY `key_location_h_ratetype` (`ratetype`),
  KEY `hotelacct_loc_fk` (`location_id`),
  KEY `location_hotelacct_bookingtype_idx` (`bookingtype`),
  KEY `location_hotelacct_emp_idx` (`employee_id`),
  KEY `location_hotelacct_taxtype_idx` (`tax_type`),
  KEY `location_hotelacct_mealplan_idx` (`mealplan`),
  KEY `location_hotelacct_id` (`account_id`),
  KEY `location_hotelacct_status` (`status`),
  KEY `last_datetime_idx` (`last_datetime`),
  CONSTRAINT `hotelacct_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `key_location_h_guesttype` FOREIGN KEY (`guesttype`) REFERENCES `location_hotel_guesttype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `key_location_h_markettype` FOREIGN KEY (`markettype`) REFERENCES `location_hotel_markettype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `key_location_h_ratetype` FOREIGN KEY (`ratetype`) REFERENCES `location_hotel_ratetype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `key_location_h_roomtype` FOREIGN KEY (`roomtype`) REFERENCES `location_hotel_roomtype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_bookingtype` FOREIGN KEY (`bookingtype`) REFERENCES `location_hotel_bookingtype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22298 DEFAULT CHARSET=latin1 COMMENT='Main details of a hotel account';

/*Table structure for table `location_hotelacct_affiliate` */

DROP TABLE IF EXISTS `location_hotelacct_affiliate`;

CREATE TABLE `location_hotelacct_affiliate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `affiliatetype` enum('company','group','travel agency','internet agency','wholesaler','other','receivables') DEFAULT NULL,
  `companies_locations_groups_id` int(11) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_on` varchar(50) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_hotelacct_affiliate_loc_fk` (`location_id`),
  KEY `location_hotelacct_affiliate_comp_idx` (`company_id`),
  KEY `location_hotel_acct_affiliate_emp_idx` (`employee_id`),
  KEY `location_hotelacct_affiliate_loc_acc_idx` (`location_id`,`account_id`),
  CONSTRAINT `location_hotel_acct_affiliate_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_affiliate_comp` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_affiliate_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 COMMENT='Affiliate (companies) associated with a hotel account';

/*Table structure for table `location_hotelacct_audit` */

DROP TABLE IF EXISTS `location_hotelacct_audit`;

CREATE TABLE `location_hotelacct_audit` (
  `location_hotelacct_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `status` enum('Shopping','Reserved','Cancelled','Inhouse','Noshow','Checkout','Permanent') DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `primary_client_id` int(11) DEFAULT NULL,
  `perm_name` varchar(45) DEFAULT NULL,
  `room` int(11) DEFAULT NULL,
  `markettype` int(11) DEFAULT NULL,
  `guesttype` int(11) DEFAULT NULL,
  `roomtype` int(11) DEFAULT NULL,
  `ratetype` int(11) DEFAULT NULL,
  `mealplan` int(11) DEFAULT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  `meal_rate` decimal(10,2) DEFAULT NULL,
  `tax_type` int(11) DEFAULT NULL,
  `tax_rate` decimal(10,2) DEFAULT NULL,
  `bookingtype` int(11) DEFAULT NULL,
  `booking_account` varchar(16) DEFAULT NULL,
  `group` varchar(32) DEFAULT NULL,
  `arrival` date DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `nights` int(3) DEFAULT NULL,
  `departure` date DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `adults` int(11) DEFAULT NULL,
  `child1` int(11) DEFAULT NULL,
  `child2` int(11) DEFAULT NULL,
  `nopost` enum('Yes','No') DEFAULT NULL,
  `isvalid` varchar(1) DEFAULT NULL,
  `turnaway` int(11) DEFAULT NULL,
  `random_id` int(11) DEFAULT NULL,
  `isoverride` enum('Yes','No') DEFAULT NULL,
  `isovercontrol` enum('Yes','No') DEFAULT NULL,
  `isoverrestriction` enum('Yes','No') DEFAULT NULL,
  `early_charge` enum('Yes','No') DEFAULT NULL,
  `review_id` int(11) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_hotelacct_audit_id`),
  KEY `location_hotelacct_audit_location_fk_idx` (`location_id`),
  KEY `last_datetime_idx` (`last_datetime`),
  KEY `location_hotelacct_audit_id_idx` (`id`),
  CONSTRAINT `location_hotelacct_audit_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7263328 DEFAULT CHARSET=latin1 COMMENT='Records of any changes made to a hotel account';

/*Table structure for table `location_hotelacct_client` */

DROP TABLE IF EXISTS `location_hotelacct_client`;

CREATE TABLE `location_hotelacct_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `created_on` varchar(50) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_hotelacct_client_loc_fk` (`location_id`),
  KEY `location_hotelacct_client_client_idx` (`client_id`),
  KEY `location_hotelacct_client_emp_idx` (`employee_id`),
  KEY `idx_acc` (`account_id`),
  CONSTRAINT `location_hotelacct_client_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_client_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_client_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=81844 DEFAULT CHARSET=latin1 COMMENT='Client records linked with a hotel account';

/*Table structure for table `location_hotelacct_folio` */

DROP TABLE IF EXISTS `location_hotelacct_folio`;

CREATE TABLE `location_hotelacct_folio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `type` enum('Payment','Posting') DEFAULT NULL,
  `paymenttype` int(8) DEFAULT NULL,
  `postingtype` int(11) DEFAULT NULL,
  `description` varchar(64) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `payment_code` varchar(50) DEFAULT NULL,
  `processor` enum('PayPal','First Data','Authorize.Net','XCharge','Braintree','Global','SaferPay') DEFAULT NULL,
  `processor_transaction_id` mediumtext NOT NULL,
  `location_cc_batches_id` int(11) DEFAULT NULL COMMENT 'Link to the location cc batches table',
  `cc_firstname` varchar(100) NOT NULL,
  `cc_lastname` varchar(100) NOT NULL,
  `cc_number` varchar(35) NOT NULL,
  `cc_name` varchar(64) NOT NULL,
  `cc_exp` varchar(10) NOT NULL,
  `cc_cvv` varchar(10) NOT NULL,
  `ccsecurity` varchar(30) NOT NULL,
  `cc_paid_amt` decimal(10,2) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `gratuity` float(10,2) NOT NULL DEFAULT '0.00',
  `received` float(10,2) NOT NULL DEFAULT '0.00',
  `authorisation` varchar(20) NOT NULL,
  `cc_autho` varchar(50) NOT NULL,
  `cc_autho_date` varchar(20) NOT NULL,
  `cc_autho_time` varchar(20) NOT NULL,
  `autho_amount` decimal(10,2) NOT NULL,
  `autho_date` datetime NOT NULL,
  `adjustment` float(10,2) NOT NULL DEFAULT '0.00',
  `exp_client_id` int(10) DEFAULT '0',
  `exp_client_name` varchar(50) NOT NULL,
  `sale_type` varchar(5) NOT NULL,
  `client_order_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `id_pay` int(11) NOT NULL,
  `autho_emp` varchar(40) NOT NULL,
  `cc_card_entry` enum('Swiped','Typed') NOT NULL,
  `posting_tax_id` int(10) DEFAULT '0' COMMENT 'folio.id to know which record of tax for which posting',
  `reverse_id` int(10) DEFAULT '0',
  `expensetab_paid` enum('Yes','No') DEFAULT 'No',
  `currency_type` varchar(10) DEFAULT NULL,
  `currency_rate` float(10,2) DEFAULT NULL,
  `currency_amount` float(10,2) DEFAULT NULL,
  `order_location_id` int(10) DEFAULT NULL COMMENT 'When interface payment then needs to add location id from where it submitted',
  `gift_certificate` int(11) DEFAULT NULL,
  `moved_already` enum('Yes','No') NOT NULL DEFAULT 'No',
  `affiliate_id` int(11) DEFAULT NULL,
  `is_taxexempt` enum('Y','N') DEFAULT NULL,
  `is_autocharge` enum('Y','N') DEFAULT 'N',
  `image` longtext,
  `client_sales_pay_id` int(11) DEFAULT NULL,
  `client_order_pay_id` int(11) DEFAULT NULL,
  `is_processed` enum('Yes','No') DEFAULT 'No',
  `created_on` varchar(50) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `created_from` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_hotelacct_folio_loc_fk` (`location_id`),
  KEY `location_hotelacct_folio_affiliate_idx` (`affiliate_id`),
  KEY `location_hotelacct_client_idx` (`client_id`),
  KEY `location_hotelacct_giftcard_idx` (`gift_certificate`),
  KEY `location_hotelacct_client_sales_idx` (`client_sales_id`),
  KEY `location_hotelacct_client_orders_idx` (`client_order_id`),
  KEY `location_hotelacct_folio_paymenttype_idx` (`paymenttype`),
  KEY `location_hotelacct_folio_postingtype_idx` (`postingtype`),
  KEY `location_hotelacct_account_id` (`account_id`),
  KEY `location_hotelacct_type` (`type`),
  KEY `location_hotelacct_date` (`date`),
  KEY `location_hotelacct_folio_location_account_idk` (`location_id`,`account_id`),
  KEY `location_hotelacct_folio_posting_tax_id` (`posting_tax_id`),
  CONSTRAINT `location_hotelacct_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_folio_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=176532 DEFAULT CHARSET=latin1 COMMENT='Payments and Postings made on a hotel account';

/*Table structure for table `location_hotelacct_folio_temp_fastpost` */

DROP TABLE IF EXISTS `location_hotelacct_folio_temp_fastpost`;

CREATE TABLE `location_hotelacct_folio_temp_fastpost` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `type` enum('Payment','Posting') DEFAULT NULL,
  `paymenttype` int(11) NOT NULL,
  `postingtype` int(11) NOT NULL,
  `description` varchar(64) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `departure` date DEFAULT NULL,
  `room` int(11) DEFAULT NULL,
  `payment_code` int(10) DEFAULT NULL,
  `cc_number` varchar(20) NOT NULL,
  `cc_exp` varchar(10) NOT NULL,
  `cc_cvv` varchar(10) NOT NULL,
  `exp_client_id` int(10) DEFAULT NULL,
  `exp_client_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_hotelacct_folio_loc_fk` (`location_id`),
  CONSTRAINT `location_hotelacct_folio_temp_fastpost_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Temporary table for BusinessPanel Hotel Fast Posting';

/*Table structure for table `location_hotelacct_guarantee` */

DROP TABLE IF EXISTS `location_hotelacct_guarantee`;

CREATE TABLE `location_hotelacct_guarantee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `guaranteetype` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `client_payment_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `primary_guarantee` enum('Yes','No') DEFAULT NULL,
  `created_on` varchar(50) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_location_h_guarantee` (`guaranteetype`),
  KEY `hotelacct_guarantee_loc_FK` (`location_id`),
  KEY `location_hotelacct_guarantee_client_idx` (`client_id`),
  KEY `location_hotelacct_guarantee_emp_idx` (`employee_id`),
  KEY `idx_acc` (`account_id`),
  CONSTRAINT `hotelacct_guarantee_loc_FK` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `key_location_h_guarantee` FOREIGN KEY (`guaranteetype`) REFERENCES `location_hotel_guarantee` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_guarantee_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_guarantee_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21874 DEFAULT CHARSET=latin1 COMMENT='Guarantees added on a location hotel account';

/*Table structure for table `location_hotelacct_notes` */

DROP TABLE IF EXISTS `location_hotelacct_notes`;

CREATE TABLE `location_hotelacct_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `active` enum('Active','Inactive') DEFAULT NULL,
  `notetype` enum('Note','Message','Reminder') DEFAULT NULL,
  `note` varchar(256) DEFAULT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `completed` enum('Yes','No') DEFAULT NULL,
  `completed_date` datetime DEFAULT NULL,
  `created_on` varchar(50) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_hotelacct_notes_loc_fk` (`location_id`),
  KEY `location_hotelacct_notes_emp_idx` (`employee_id`),
  KEY `location_hotelacct_notes_accloc_stat_idx` (`account_id`,`location_id`,`active`,`completed`),
  CONSTRAINT `location_hotelacct_notes_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_notes_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9294 DEFAULT CHARSET=latin1 COMMENT='Notes or reminders added to a hotel account';

/*Table structure for table `location_hotelacct_rates` */

DROP TABLE IF EXISTS `location_hotelacct_rates`;

CREATE TABLE `location_hotelacct_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `roomtype` int(11) NOT NULL,
  `ratetype` int(11) NOT NULL,
  `mealtype` int(11) DEFAULT NULL,
  `taxtype` int(11) NOT NULL,
  `room` int(11) NOT NULL,
  `from_date` datetime DEFAULT NULL,
  `to_date` datetime DEFAULT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  `meal_rate` varchar(45) DEFAULT NULL,
  `tax_rate` decimal(10,2) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` varchar(50) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_hotelacct_rates` (`location_id`),
  KEY `location_hotelacct_ratetype_idx` (`ratetype`),
  KEY `location_hotelacct_rates_room_idx` (`room`),
  KEY `location_hotelacct_rates_roomtype_idx` (`roomtype`),
  KEY `location_hotelacct_rates_mealtype_idx` (`mealtype`),
  KEY `location_hotelacct_rates_taxtype_idx` (`taxtype`),
  KEY `idx_acc` (`account_id`),
  CONSTRAINT `location_hotelacct_rates` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_hotelacct_rates_mealtype` FOREIGN KEY (`mealtype`) REFERENCES `location_hotel_mealtype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_rates_roomtype` FOREIGN KEY (`roomtype`) REFERENCES `location_hotel_roomtype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_rates_taxtype` FOREIGN KEY (`taxtype`) REFERENCES `location_hotel_taxtype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_ratetype` FOREIGN KEY (`ratetype`) REFERENCES `location_hotel_ratetype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=33521573 DEFAULT CHARSET=latin1 COMMENT='Rate information for a hotel account';

/*Table structure for table `location_hotelacct_specials` */

DROP TABLE IF EXISTS `location_hotelacct_specials`;

CREATE TABLE `location_hotelacct_specials` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(10) NOT NULL,
  `account_id` int(10) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `client_id` int(10) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT NULL,
  `when_charged` enum('Daily','On Arrival','On Departure') DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `postingtype_id` int(10) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `is_charged` enum('Y','N') DEFAULT 'N',
  `created_by` int(11) NOT NULL,
  `created_on` varchar(25) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_hotelacct_specials_client_idx` (`client_id`),
  KEY `location_hotelacct_special_postingtype_idx` (`postingtype_id`),
  KEY `location_hotelacct_special_account_idx` (`account_id`),
  KEY `location_hotelacct_specials_loc_idx` (`location_id`),
  CONSTRAINT `location_hotelacct_special_postingtype` FOREIGN KEY (`postingtype_id`) REFERENCES `location_hotel_postingtype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_specials_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_hotelacct_specials_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1769 DEFAULT CHARSET=latin1 COMMENT='Specials information for a hotel account';

/*Table structure for table `location_images` */

DROP TABLE IF EXISTS `location_images`;

CREATE TABLE `location_images` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `priority` int(8) NOT NULL,
  `image` tinytext NOT NULL,
  `description` varchar(256) NOT NULL,
  `usein_bes` enum('yes','no') DEFAULT NULL,
  `usein_corporate` enum('yes','no') DEFAULT NULL,
  `general_image` enum('yes','no') DEFAULT NULL,
  `consumer_home` enum('yes','no') DEFAULT NULL,
  `consumer_reservation` enum('yes','no') DEFAULT NULL,
  `consumer_menus` enum('yes','no') DEFAULT NULL,
  `consumer_events` enum('yes','no') DEFAULT NULL,
  `consumer_orders` enum('yes','no') DEFAULT NULL,
  `consumer_wine` enum('yes','no') DEFAULT NULL,
  `consumer_about` enum('yes','no') DEFAULT NULL,
  `consumer_title` enum('yes','no') DEFAULT NULL,
  `sppay_logo` enum('Yes','No') DEFAULT 'No',
  `sppay_home` enum('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`id`),
  KEY `location_images_fk` (`location_id`),
  CONSTRAINT `location_images_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=965 DEFAULT CHARSET=latin1 COMMENT='Secondary images added to a location';

/*Table structure for table `location_installation` */

DROP TABLE IF EXISTS `location_installation`;

CREATE TABLE `location_installation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `status` enum('New','Profile','Employee','Operations','Finance','Installed') DEFAULT NULL,
  `temporary_password` varchar(100) DEFAULT NULL,
  `step_profile` datetime DEFAULT NULL,
  `step_employee` datetime DEFAULT NULL,
  `step_operations` datetime DEFAULT NULL,
  `step_fiannce` datetime DEFAULT NULL,
  `Restaurant` enum('Yes','No') DEFAULT NULL,
  `Retail` enum('Yes','No') DEFAULT NULL,
  `Hotel` enum('Yes','No') DEFAULT NULL,
  `Other` enum('Yes','No') DEFAULT NULL,
  `send_registration_email` enum('Yes','No') DEFAULT NULL,
  `Created_by` varchar(45) NOT NULL,
  `Created_on` varchar(45) NOT NULL,
  `Created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_installation_loc_idx` (`location_id`),
  CONSTRAINT `location_installation_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=948 DEFAULT CHARSET=latin1 COMMENT='Stores the process of a locations installation';

/*Table structure for table `location_internal_billing` */

DROP TABLE IF EXISTS `location_internal_billing`;

CREATE TABLE `location_internal_billing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `month` varchar(3) NOT NULL,
  `year` varchar(4) NOT NULL,
  `number of products` int(11) NOT NULL,
  `description` text,
  `quantity` int(11) DEFAULT NULL,
  `rate` decimal(12,2) DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT NULL,
  `reason` text,
  `invoice_number` varchar(45) DEFAULT NULL,
  `reseller1` int(11) DEFAULT NULL,
  `reseller1_pct` varchar(3) DEFAULT NULL,
  `reseller1_cost` decimal(11,2) DEFAULT NULL,
  `reseller2` int(11) DEFAULT NULL,
  `reseller2_pct` varchar(3) DEFAULT NULL,
  `reseller2_cost` decimal(11,2) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_internal_billing_location_idx` (`location_id`),
  KEY `location_internal_billing_my_idx` (`month`,`year`),
  CONSTRAINT `location_internal_billing_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8401 DEFAULT CHARSET=latin1 COMMENT='General billing information for a location from SoftPoint';

/*Table structure for table `location_internal_billing_details` */

DROP TABLE IF EXISTS `location_internal_billing_details`;

CREATE TABLE `location_internal_billing_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_internal_billing_id` int(11) NOT NULL,
  `status` enum('Active','Cancelled') NOT NULL,
  `location_id` int(11) NOT NULL,
  `quantity` int(3) DEFAULT NULL,
  `reason` text,
  `product` varchar(45) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) NOT NULL,
  `last_on` varchar(45) NOT NULL,
  `last_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `location_internal_billing_loc_idx` (`location_id`),
  KEY `location_internal_billing_details_billing_id_idx` (`location_internal_billing_id`),
  CONSTRAINT `location_internal_billing_details_billing_id` FOREIGN KEY (`location_internal_billing_id`) REFERENCES `location_internal_billing` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_internal_billing_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=74151 DEFAULT CHARSET=latin1 COMMENT='Detailed billing information for a lcoation from SoftPoint';

/*Table structure for table `location_internal_billing_payments` */

DROP TABLE IF EXISTS `location_internal_billing_payments`;

CREATE TABLE `location_internal_billing_payments` (
  `location_internal_billing_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Payment','Credit','Declined') NOT NULL,
  `payment_amount` decimal(14,2) DEFAULT NULL,
  `credit_amount` decimal(14,2) DEFAULT NULL,
  `payment_type` enum('Debit Card','Credit Card','Check','Transfer') NOT NULL,
  `cc_type` enum('Discover Card','American Express','MasterCard','Visa') DEFAULT NULL,
  `cc_name` varchar(100) DEFAULT NULL,
  `cc_number` varchar(30) DEFAULT NULL,
  `cc_exp` varchar(12) DEFAULT NULL,
  `cc_cvv` int(11) DEFAULT NULL,
  `cc_authorization` varchar(50) DEFAULT NULL,
  `cc_transactionid` varchar(50) DEFAULT NULL,
  `bank_account_type` enum('checking','savings','business_checking') DEFAULT NULL,
  `bank_name` varchar(45) DEFAULT NULL,
  `bank_account_name` varchar(45) DEFAULT NULL,
  `bank_account` varchar(45) DEFAULT NULL,
  `bank_routing` varchar(45) DEFAULT NULL,
  `check_number` varchar(12) DEFAULT NULL,
  `check_name` varchar(64) DEFAULT NULL,
  `processor_name` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`location_internal_billing_payments_id`),
  KEY `location_internal_billing_payments_location_fk_idx` (`location_id`),
  CONSTRAINT `location_internal_billing_payments_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1798 DEFAULT CHARSET=utf8 COMMENT='Payments made from a location to SoftPoint';

/*Table structure for table `location_internal_eula` */

DROP TABLE IF EXISTS `location_internal_eula`;

CREATE TABLE `location_internal_eula` (
  `location_internal_eula_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `customer_first_name` varchar(250) DEFAULT NULL,
  `customer_last_name` varchar(250) DEFAULT NULL,
  `customer_address` varchar(250) DEFAULT NULL,
  `customer_address_secondary` varchar(250) DEFAULT NULL,
  `customer_country` varchar(250) DEFAULT NULL,
  `customer_zip_code` varchar(45) DEFAULT NULL,
  `customer_email` varchar(45) DEFAULT NULL,
  `customer_phone` varchar(45) DEFAULT NULL,
  `customer_state` varchar(250) DEFAULT NULL,
  `customer_tax_id` varchar(45) DEFAULT NULL,
  `customer_business_type` varchar(45) DEFAULT NULL,
  `customer_pos_type` varchar(45) DEFAULT NULL,
  `customer_pos_version_number` varchar(45) DEFAULT NULL,
  `customer_txt_processor` varchar(45) DEFAULT NULL,
  `eula_order_step` tinyint(4) DEFAULT '0',
  `eula_temporary_code` varchar(200) DEFAULT NULL,
  `quick_service_chb` enum('Active','Inactive') DEFAULT NULL,
  `quick_service_qty` int(11) DEFAULT NULL,
  `pay_at_table_chb` enum('Active','Inactive') DEFAULT NULL,
  `pay_at_table_qty` int(11) DEFAULT NULL,
  `tip_adjustment_chb` enum('Active','Inactive') DEFAULT NULL,
  `tip_adjustment_qty` int(11) DEFAULT NULL,
  `delivery_off_premise_chb` enum('Active','Inactive') DEFAULT NULL,
  `delivery_qty` int(11) DEFAULT NULL,
  `exadigm_wifi_black_chb` enum('Active','Inactive') DEFAULT NULL,
  `exadigm_wifi_white_chb` enum('Active','Inactive') DEFAULT NULL,
  `exadigm_wifi_white_qty` int(11) DEFAULT NULL,
  `exadigm_wifi_black_qty` int(11) DEFAULT NULL,
  `exadigm_4gwifi_black_chb` enum('Active','Inactive') DEFAULT NULL,
  `exadigm_4gwifi_white_chb` enum('Active','Inactive') DEFAULT NULL,
  `exadigm_4gwifi_white_qty` int(11) DEFAULT NULL,
  `exadigm_4gwifi_black_qty` int(11) DEFAULT NULL,
  `charging_base_chb` enum('Active','Inactive') DEFAULT NULL,
  `charging_base_qty` int(11) DEFAULT NULL,
  `hot_spot_chb` enum('Active','Inactive') DEFAULT NULL,
  `hot_spot_qty` int(11) DEFAULT NULL,
  `screen_protector_chb` enum('Active','Inactive') DEFAULT NULL,
  `screen_protector_qty` int(11) DEFAULT NULL,
  `mountain_pole_chb` enum('Active','Inactive') DEFAULT NULL,
  `mountain_pole_qty` int(11) DEFAULT NULL,
  `docking_station_chb` enum('Active','Inactive') DEFAULT NULL,
  `docking_station_qty` int(11) DEFAULT NULL,
  `shipping_chb` enum('Active','Inactive') DEFAULT NULL,
  `terminal_shipping_qty` int(11) DEFAULT NULL,
  `installation_type` enum('onsite_install','remote_install') DEFAULT NULL,
  `installation_number_of_days` int(11) DEFAULT NULL,
  `installation_date` varchar(45) DEFAULT NULL,
  `installation_time` varchar(45) DEFAULT NULL,
  `installation_onsite_amount` decimal(10,2) DEFAULT NULL,
  `installation_remote_amount` decimal(10,2) DEFAULT NULL,
  `customer_city` varchar(45) DEFAULT NULL,
  `customer_shipping_city` varchar(45) DEFAULT NULL,
  `payment_type` enum('CreditCard','CheckingAccount') DEFAULT NULL,
  `payment_card_number` varchar(45) DEFAULT NULL,
  `payment_card_company` varchar(45) DEFAULT NULL,
  `payment_card_holdername` varchar(45) DEFAULT NULL,
  `payment_card_expiration_date` varchar(45) DEFAULT NULL,
  `payment_card_number_cvv` varchar(45) DEFAULT NULL,
  `payment_account_number` varchar(45) DEFAULT NULL,
  `payment_account_routing_number` varchar(45) DEFAULT NULL,
  `operating_system` varchar(45) DEFAULT NULL,
  `shipping_addressline_one` varchar(45) DEFAULT NULL,
  `shipping_addressline_two` varchar(45) DEFAULT NULL,
  `shipping_country` varchar(45) DEFAULT NULL,
  `shipping_state` varchar(45) DEFAULT NULL,
  `shipping_zip_code` varchar(45) DEFAULT NULL,
  `signature` varchar(250) DEFAULT NULL,
  `customer_fax` varchar(20) DEFAULT NULL,
  `customer_skype` varchar(45) DEFAULT NULL,
  `lease_agreement_document_path` varchar(255) DEFAULT NULL,
  `voided_check_or_bank_letter_document_path` varchar(255) DEFAULT NULL,
  `customer_driver_license_or_passport_document_path` varchar(255) DEFAULT NULL,
  `created_datetime` datetime NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`location_internal_eula_id`),
  UNIQUE KEY `location_internal_eula_id_UNIQUE` (`location_internal_eula_id`),
  KEY `location_internal_eula_tmp_cod_idx` (`eula_temporary_code`)
) ENGINE=InnoDB AUTO_INCREMENT=968 DEFAULT CHARSET=latin1;

/*Table structure for table `location_internal_products` */

DROP TABLE IF EXISTS `location_internal_products`;

CREATE TABLE `location_internal_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Active','Inactive') DEFAULT 'Inactive',
  `location_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `trial_30_days` enum('Yes','No') DEFAULT 'No',
  `send_email_invoice` enum('Yes','No') DEFAULT 'No',
  `bill_to` varchar(255) DEFAULT NULL,
  `preferred_email` varchar(100) DEFAULT NULL,
  `pos` enum('Yes','No') DEFAULT 'No',
  `pos_amount` decimal(11,2) DEFAULT '129.99',
  `hotel` enum('Yes','No') DEFAULT 'No',
  `hotel_amount` decimal(11,2) DEFAULT NULL,
  `retail` enum('Yes','No') DEFAULT 'No',
  `retail_amount` decimal(11,2) DEFAULT NULL,
  `bar` enum('Yes','No') DEFAULT 'No',
  `bar_amount` decimal(11,2) DEFAULT NULL,
  `reservations` enum('Yes','No') DEFAULT 'No',
  `reservations_amount` decimal(11,2) DEFAULT NULL,
  `time` enum('Yes','No') DEFAULT 'No',
  `time_amount` decimal(11,2) DEFAULT NULL,
  `concierge` enum('Yes','No') DEFAULT 'No',
  `concierge_amount` decimal(11,2) DEFAULT NULL,
  `corporate` enum('Yes','No') DEFAULT 'No',
  `corporate_amount` decimal(11,2) DEFAULT NULL,
  `control` enum('Yes','No') DEFAULT 'No',
  `control_amount` decimal(11,2) DEFAULT NULL,
  `crs` enum('Yes','No') DEFAULT 'No',
  `crs_amount` decimal(11,2) DEFAULT NULL,
  `crm` enum('Yes','No') DEFAULT 'No',
  `crm_amount` decimal(11,2) DEFAULT NULL,
  `delivery` enum('Yes','No') DEFAULT 'No',
  `delivery_amount` decimal(11,2) DEFAULT NULL,
  `event` enum('Yes','No') DEFAULT 'No',
  `event_amount` decimal(11,2) DEFAULT NULL,
  `manage` enum('Yes','No') DEFAULT 'No',
  `manage_amount` decimal(11,2) DEFAULT NULL,
  `menu` enum('Yes','No') DEFAULT 'No',
  `menu_amount` decimal(11,2) DEFAULT NULL,
  `olo` enum('Yes','No') DEFAULT 'No',
  `olo_amount` decimal(11,2) DEFAULT NULL,
  `prep` enum('Yes','No') DEFAULT 'No',
  `prep_amount` decimal(11,2) DEFAULT NULL,
  `quality` enum('Yes','No') DEFAULT 'No',
  `quality_amount` decimal(11,2) DEFAULT NULL,
  `quick` enum('Yes','No') DEFAULT 'No',
  `quick_amount` decimal(11,2) DEFAULT NULL,
  `como` enum('Yes','No') DEFAULT 'No',
  `como_amount` decimal(11,2) DEFAULT NULL,
  `datapoint` enum('Yes','No') DEFAULT 'No',
  `datapoint_base_price` decimal(11,2) DEFAULT NULL,
  `datapoint_num_of_term` int(11) DEFAULT NULL,
  `datapoint_extra_term_price` decimal(11,2) DEFAULT NULL,
  `datapoint_num_of_extra_term` int(11) DEFAULT NULL,
  `datapoint_ordering` enum('Yes','No') DEFAULT 'No',
  `datapoint_ordering_amount` decimal(11,2) DEFAULT NULL,
  `datapoint_ordering_num_of_term` decimal(11,2) DEFAULT NULL,
  `datapoint_ordering_extra_term` decimal(11,2) DEFAULT NULL,
  `datapoint_ordering_extra_term_price` decimal(11,2) DEFAULT NULL,
  `datapoint_bi` enum('Yes','No') DEFAULT 'No',
  `datapoint_bi_amount` decimal(11,2) DEFAULT NULL,
  `datapoint_hotels` enum('Yes','No') DEFAULT 'No',
  `datapoint_hotels_amount` decimal(11,2) DEFAULT NULL,
  `datapoint_unlimited_terminals` enum('Yes','No') DEFAULT NULL,
  `datapoint_unlimited_terminals_price` decimal(11,2) DEFAULT NULL,
  `kiosk` enum('Yes','No') DEFAULT NULL,
  `kiosk_amount` decimal(11,2) DEFAULT NULL,
  `kiosk_with_pay` enum('Yes','No') DEFAULT NULL,
  `kiosk_with_pay_amount` decimal(11,2) DEFAULT NULL,
  `GiftPoint` enum('Yes','No') DEFAULT NULL,
  `GiftPoint_amount` decimal(11,2) DEFAULT NULL,
  `kiosk_extra_terminal_amount` decimal(11,2) DEFAULT NULL,
  `kiosk_extra_terminal` int(11) DEFAULT NULL,
  `datapoint_total` decimal(11,2) DEFAULT NULL,
  `datapoint_minus_omnivore` enum('Yes','No') DEFAULT 'No',
  `datapoint_minus_ncr` enum('Yes','No') DEFAULT 'No',
  `paypoint_fd` enum('Yes','No') DEFAULT 'No',
  `paypoint_fd_amount` decimal(11,2) DEFAULT NULL,
  `paypoint_fd_quantity` int(11) DEFAULT NULL,
  `paypoint_fd_per_unit_amount` decimal(11,2) DEFAULT NULL,
  `onsite_install` enum('Yes','No') DEFAULT 'No',
  `onsite_install_days` int(11) DEFAULT NULL,
  `onsite_install_per_day_amount` decimal(11,2) DEFAULT NULL,
  `corporate_discount` enum('Yes','No') DEFAULT NULL,
  `corporate_discount_amount` decimal(11,2) DEFAULT NULL,
  `purchased_exadigm_n5` enum('Yes','No') DEFAULT NULL,
  `purchased_pax_a920` enum('Yes','No') DEFAULT NULL,
  `hardware_purchased_price` decimal(11,2) DEFAULT NULL,
  `hardware_purchased_terminal` int(11) DEFAULT NULL,
  `device_color` varchar(25) DEFAULT NULL,
  `device_type` varchar(25) DEFAULT NULL,
  `hot_spot_price` decimal(11,2) DEFAULT NULL,
  `hot_spot_items` int(11) DEFAULT NULL,
  `screen_protector_price` decimal(11,2) DEFAULT NULL,
  `screen_protector_items` int(11) DEFAULT NULL,
  `holster_price` decimal(11,2) DEFAULT NULL,
  `holster_items` int(11) DEFAULT NULL,
  `mounting_pole_price` decimal(11,2) DEFAULT NULL,
  `mounting_pole_items` int(11) DEFAULT NULL,
  `shipping_price` decimal(11,2) DEFAULT NULL,
  `shipping_items` int(11) DEFAULT NULL,
  `hardware_total_price` decimal(11,2) DEFAULT NULL,
  `leased_exadigm_n5` enum('Yes','No') DEFAULT NULL,
  `hardware_leased_price` decimal(11,2) DEFAULT NULL,
  `hardware_leased_terminal` int(11) DEFAULT NULL,
  `monthly_hardware_total_price` decimal(11,2) DEFAULT NULL,
  `leased_tablepoint` enum('Yes','No') DEFAULT NULL,
  `leased_tablepoint_price` decimal(11,2) DEFAULT NULL,
  `leased_tablepoint_terminal` int(11) DEFAULT NULL,
  `leased_tablepoint_total_price` decimal(11,2) DEFAULT NULL,
  `remote_install` enum('Yes','No') DEFAULT NULL,
  `remote_install_amount` decimal(11,2) DEFAULT NULL,
  `tablepoint` enum('Yes','No') DEFAULT 'No',
  `tablepoint_amount` decimal(11,2) DEFAULT NULL,
  `tablepoint_slides` decimal(11,2) DEFAULT NULL,
  `reseller1` int(11) DEFAULT NULL,
  `reseller1_pct` varchar(3) DEFAULT NULL,
  `reseller1_cost` decimal(11,2) DEFAULT NULL,
  `reseller2` int(11) DEFAULT NULL,
  `reseller2_pct` varchar(3) DEFAULT NULL,
  `reseller2_cost` decimal(11,2) DEFAULT NULL,
  `cc_surcharge` decimal(11,2) DEFAULT NULL,
  `note` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_internal_products_loc_idx` (`location_id`),
  CONSTRAINT `location_internal_products_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=531 DEFAULT CHARSET=latin1 COMMENT='Products and prices settled on for a business';

/*Table structure for table `location_inventory_counts` */

DROP TABLE IF EXISTS `location_inventory_counts`;

CREATE TABLE `location_inventory_counts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `storeroom_id` int(8) NOT NULL,
  `inv_item_id` int(8) NOT NULL,
  `Type` enum('Purchase','Count','Movement','Start','Sale') NOT NULL,
  `unit_type` int(11) NOT NULL,
  `employee_id` int(8) NOT NULL,
  `date_counted` date NOT NULL,
  `time_counted` time NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `storeroom_id_origin` int(8) DEFAULT NULL,
  `reference` int(11) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_inventory_counts_storeroom_fk` (`storeroom_id`),
  KEY `location_inventory_counts_item_fk` (`inv_item_id`),
  KEY `location_inventory_counts_emp_fk_idx` (`employee_id`),
  KEY `location_inventory_counts_location_id_idx` (`location_id`),
  KEY `location_inventory_counts_origin_storeroom_fk_idx` (`storeroom_id_origin`),
  CONSTRAINT `location_inventory_counts_emp_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inventory_counts_item_fk` FOREIGN KEY (`inv_item_id`) REFERENCES `location_inventory_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_inventory_counts_location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inventory_counts_origin_storeroom_fk` FOREIGN KEY (`storeroom_id_origin`) REFERENCES `location_inventory_storerooms` (`storeroom_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inventory_counts_storeroom_fk` FOREIGN KEY (`storeroom_id`) REFERENCES `location_inventory_storerooms` (`storeroom_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16734 DEFAULT CHARSET=utf8 COMMENT='Counts made on inventory items at a location for a storeroom';

/*Table structure for table `location_inventory_items` */

DROP TABLE IF EXISTS `location_inventory_items`;

CREATE TABLE `location_inventory_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `inv_item_id` int(15) DEFAULT NULL COMMENT 'This referes to the inventory Items for global items',
  `priority` int(11) NOT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8 NOT NULL,
  `type` enum('global','local','prep') CHARACTER SET utf8 NOT NULL,
  `local_item_id` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `local_group_id` int(8) DEFAULT NULL,
  `local_item_desc` text CHARACTER SET utf8,
  `local_item_notes` text CHARACTER SET utf8,
  `local_item_image` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `local_unit_type` int(8) DEFAULT NULL,
  `local_unit_type_qty` int(8) DEFAULT NULL,
  `local_produces_portions` int(8) DEFAULT NULL,
  `local_produces_unit_type` int(8) DEFAULT NULL,
  `taxable` enum('yes','no') CHARACTER SET utf8 DEFAULT 'yes',
  `ecommerce` enum('yes','no') DEFAULT NULL,
  `default_manufacturer` varchar(64) DEFAULT NULL,
  `default_brand` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `default_vendor` int(11) DEFAULT NULL,
  `default_price` decimal(14,2) DEFAULT NULL,
  `default_cost_price` decimal(14,2) DEFAULT NULL,
  `manufacturer_barcode` varchar(64) DEFAULT NULL,
  `total_count` decimal(10,2) NOT NULL,
  `count_unittype` int(8) DEFAULT NULL,
  `total_needed` decimal(10,2) NOT NULL,
  `needed_unittype` int(8) DEFAULT NULL,
  `menu_articles_id` int(11) DEFAULT NULL,
  `low_alert_unittype` int(8) DEFAULT NULL,
  `low_alert_count` decimal(10,2) DEFAULT NULL,
  `default_storeroom_id` int(11) NOT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_inventory_items_loc_fk` (`location_id`),
  KEY `location_inv_items_count_unit_idx` (`count_unittype`),
  KEY `location_inv_items_inv_item_idx` (`inv_item_id`),
  KEY `location_inv_items_needed_unit_idx` (`needed_unittype`),
  CONSTRAINT `location_inv_items_count_unit` FOREIGN KEY (`count_unittype`) REFERENCES `inventory_item_unittype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inv_items_inv_item` FOREIGN KEY (`inv_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inv_items_needed_unit` FOREIGN KEY (`needed_unittype`) REFERENCES `inventory_item_unittype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inventory_items_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10068 DEFAULT CHARSET=latin1 COMMENT='Items used at a location';

/*Table structure for table `location_inventory_items_prep_details` */

DROP TABLE IF EXISTS `location_inventory_items_prep_details`;

CREATE TABLE `location_inventory_items_prep_details` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `inv_item_id` int(8) NOT NULL,
  `ingredient_item_id` int(10) NOT NULL,
  `location_id` int(8) NOT NULL,
  `priority` int(8) NOT NULL,
  `unit_type` int(8) NOT NULL,
  `quantity` int(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_inv_item_prep_det_inv_item_idx` (`inv_item_id`),
  KEY `location_inv_item_prep_det_loc_idx` (`location_id`),
  KEY `location_inv_item_prep_det_unit_type_idx` (`unit_type`),
  CONSTRAINT `location_inv_item_prep_det_ingredient` FOREIGN KEY (`inv_item_id`) REFERENCES `location_inventory_items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inv_item_prep_det_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inv_item_prep_det_unit_type` FOREIGN KEY (`unit_type`) REFERENCES `inventory_item_unittype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Prep Details for a specific inventory item at a location';

/*Table structure for table `location_inventory_line_items` */

DROP TABLE IF EXISTS `location_inventory_line_items`;

CREATE TABLE `location_inventory_line_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inv_item_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `storeroom_id` int(8) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `priority` int(10) NOT NULL,
  `area` varchar(16) NOT NULL,
  `shelflife` varchar(32) NOT NULL,
  `storage_unit` varchar(32) NOT NULL,
  `par_unit_type` int(8) NOT NULL,
  `par` int(11) NOT NULL,
  `quality_spec` varchar(32) NOT NULL,
  `temp_req` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_inventory_line_items_item_fk` (`inv_item_id`),
  KEY `location_inventory_line_items_loc_fk` (`location_id`),
  KEY `location_inventory_line_items_stroom_fk` (`storeroom_id`),
  KEY `location_inventory_live_items_par_unit_idx` (`par_unit_type`),
  CONSTRAINT `location_inventory_line_items_item_fk` FOREIGN KEY (`inv_item_id`) REFERENCES `location_inventory_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_inventory_line_items_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_inventory_line_items_stroom_fk` FOREIGN KEY (`storeroom_id`) REFERENCES `location_inventory_storerooms` (`storeroom_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_inventory_live_items_par_unit` FOREIGN KEY (`par_unit_type`) REFERENCES `inventory_item_unittype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Items and Specifications for a line in a kitchen';

/*Table structure for table `location_inventory_line_items_verify` */

DROP TABLE IF EXISTS `location_inventory_line_items_verify`;

CREATE TABLE `location_inventory_line_items_verify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `line_item_id` int(11) NOT NULL,
  `employee_id` int(8) NOT NULL,
  `datetime` datetime NOT NULL,
  `quantity_unit_type` int(8) NOT NULL,
  `quantity` int(10) NOT NULL,
  `temp_verified` varchar(16) NOT NULL,
  `quality` varchar(32) NOT NULL,
  `comments` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_inventory_line_items_verify_fk` (`line_item_id`),
  KEY `location_inventory_line_items_verify_emp_idx` (`employee_id`),
  KEY `location_inventory_line_items_verify_unit_idx` (`quantity_unit_type`),
  CONSTRAINT `location_inventory_line_items_verify_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inventory_line_items_verify_fk` FOREIGN KEY (`line_item_id`) REFERENCES `location_inventory_line_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_inventory_line_items_verify_unit` FOREIGN KEY (`quantity_unit_type`) REFERENCES `inventory_item_unittype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Records made when a line is checked for required items';

/*Table structure for table `location_inventory_order_needed` */

DROP TABLE IF EXISTS `location_inventory_order_needed`;

CREATE TABLE `location_inventory_order_needed` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `inv_item_id` int(8) NOT NULL,
  `priority` enum('needed','required','urgent','none') NOT NULL,
  `datetime` datetime NOT NULL,
  `employee_id` int(8) NOT NULL,
  `unit_type` int(8) NOT NULL,
  `required_quantity` decimal(10,2) NOT NULL,
  `type` enum('order_needed','purchase') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_inv_order_needed.emp_idx` (`employee_id`),
  KEY `location_inv_order_needed_inv_item_idx` (`inv_item_id`),
  KEY `location_inv_order_needed_loc_idx` (`location_id`),
  KEY `location_inv_order_needed_unittype_idx` (`unit_type`),
  CONSTRAINT `location_inv_order_needed.emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inv_order_needed_inv_item` FOREIGN KEY (`inv_item_id`) REFERENCES `location_inventory_items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inv_order_needed_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inv_order_needed_unittype` FOREIGN KEY (`unit_type`) REFERENCES `inventory_item_unittype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1 COMMENT='Items that are marked as needed for order by a location';

/*Table structure for table `location_inventory_recipe` */

DROP TABLE IF EXISTS `location_inventory_recipe`;

CREATE TABLE `location_inventory_recipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `menu_article_id` int(8) NOT NULL,
  `recipe_name` varchar(64) NOT NULL,
  `recipe_description` text NOT NULL,
  `recipe_author` varchar(32) NOT NULL,
  `instructions` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `yield` varchar(45) NOT NULL,
  `date_reviewed` date NOT NULL,
  `shelf_life` varchar(45) NOT NULL,
  `total_oz` decimal(10,2) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `estimated_cost` decimal(10,2) NOT NULL,
  `type` enum('recipe','cost','item') NOT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_inventory_recipe_loc_fk` (`location_id`),
  KEY `location_inventory_recipe_menu_article_idx` (`menu_article_id`),
  CONSTRAINT `location_inventory_recipe_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_inventory_recipe_menu_article` FOREIGN KEY (`menu_article_id`) REFERENCES `location_menu_articles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2057 DEFAULT CHARSET=latin1 COMMENT='Main information for a specific recipe at a location';

/*Table structure for table `location_inventory_recipe_details` */

DROP TABLE IF EXISTS `location_inventory_recipe_details`;

CREATE TABLE `location_inventory_recipe_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipe_id` int(8) NOT NULL,
  `inv_item_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `priority` int(8) NOT NULL,
  `unit_type` int(8) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `special` varchar(64) NOT NULL,
  `TEMP_unit_cost` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_inventory_recipe_details_fk` (`recipe_id`),
  KEY `location_inventory_recipe_item_idx` (`inv_item_id`),
  KEY `location_inventory_recipe_details_loc_idx` (`location_id`),
  KEY `location_inventory_recipe_details_unittype_idx` (`unit_type`),
  CONSTRAINT `location_inventory_recipe_details_fk` FOREIGN KEY (`recipe_id`) REFERENCES `location_inventory_recipe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_inventory_recipe_details_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inventory_recipe_details_unittype` FOREIGN KEY (`unit_type`) REFERENCES `inventory_item_unittype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inventory_recipe_item` FOREIGN KEY (`inv_item_id`) REFERENCES `location_inventory_items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6051 DEFAULT CHARSET=latin1 COMMENT='Detailed items needed for a recipe at a location';

/*Table structure for table `location_inventory_storeroom_items` */

DROP TABLE IF EXISTS `location_inventory_storeroom_items`;

CREATE TABLE `location_inventory_storeroom_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `storeroom_id` int(8) NOT NULL,
  `inv_item_id` int(8) NOT NULL,
  `priority` decimal(8,4) NOT NULL,
  `group_item_priority` decimal(8,4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_inv_storeroom_items_item_idx` (`inv_item_id`),
  KEY `location_inv_storeroom_items_loc_idx` (`location_id`),
  KEY `location_inv_storeroom_items_storeroom_idx` (`storeroom_id`),
  CONSTRAINT `location_inv_storeroom_items_item` FOREIGN KEY (`inv_item_id`) REFERENCES `location_inventory_items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inv_storeroom_items_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_inv_storeroom_items_storeroom` FOREIGN KEY (`storeroom_id`) REFERENCES `location_inventory_storerooms` (`storeroom_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4597 DEFAULT CHARSET=latin1 COMMENT='Items registered with a storeroom at a location';

/*Table structure for table `location_inventory_storerooms` */

DROP TABLE IF EXISTS `location_inventory_storerooms`;

CREATE TABLE `location_inventory_storerooms` (
  `storeroom_id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `stroom_id` varchar(32) NOT NULL,
  `priority` int(4) NOT NULL,
  `description` text NOT NULL,
  `line` enum('yes','no') NOT NULL DEFAULT 'no',
  `located` varchar(32) NOT NULL,
  `access` varchar(32) NOT NULL,
  PRIMARY KEY (`storeroom_id`),
  KEY `location_inventory_storerooms_fk` (`location_id`),
  CONSTRAINT `location_inventory_storerooms_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=333 DEFAULT CHARSET=utf8 COMMENT='Storerooms reigstered with a location';

/*Table structure for table `location_jobs` */

DROP TABLE IF EXISTS `location_jobs`;

CREATE TABLE `location_jobs` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `job_id` int(8) NOT NULL COMMENT 'Links to ''job_type'' table',
  `location_id` int(8) DEFAULT NULL,
  `corporate_id` int(11) DEFAULT NULL,
  `status` enum('active','expired','inactive') NOT NULL,
  `job` varchar(60) NOT NULL,
  `description` longtext NOT NULL,
  `requirements` longtext NOT NULL,
  `posted_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `contact` varchar(32) NOT NULL,
  `type` enum('Full Time','Contractor','Part Time','Intern','Seasonal/Temp') NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `days_from` varchar(250) NOT NULL,
  `days_to` varchar(250) NOT NULL,
  `salary` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_jobs_fk` (`location_id`),
  KEY `location_jobs_corporate_id_fk_idx` (`corporate_id`),
  CONSTRAINT `location_jobs_corporate_id_fk` FOREIGN KEY (`corporate_id`) REFERENCES `corporate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_jobs_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=latin1 COMMENT='Job listings for a location';

/*Table structure for table `location_ledger` */

DROP TABLE IF EXISTS `location_ledger`;

CREATE TABLE `location_ledger` (
  `location_ledger_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `entry_type` enum('Manual','Automatic') DEFAULT NULL,
  `date` date NOT NULL,
  `location_chart_of_account_id` int(11) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `created_on` varchar(32) NOT NULL,
  `created_by` varchar(32) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`location_ledger_id`),
  KEY `location_ledger_loc_fk_idx` (`location_id`),
  CONSTRAINT `location_ledger_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the individual records for a chart of accounts';

/*Table structure for table `location_logbook` */

DROP TABLE IF EXISTS `location_logbook`;

CREATE TABLE `location_logbook` (
  `location_logbook_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `log_text` text NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`location_logbook_id`),
  UNIQUE KEY `location_logbook_UNIQUE` (`location_logbook_id`),
  KEY `location_logbook_loc_dt_idx` (`location_id`,`created_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1 COMMENT='Contains all of the entries about a location';

/*Table structure for table `location_mailing_list` */

DROP TABLE IF EXISTS `location_mailing_list`;

CREATE TABLE `location_mailing_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `corporate_id` int(11) DEFAULT NULL,
  `status` enum('Subscribed','Unsubscribed') NOT NULL DEFAULT 'Subscribed',
  `name` varchar(64) DEFAULT NULL,
  `email` varchar(60) NOT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `groups` varchar(255) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_mailing_list_corporate_idx` (`corporate_id`),
  KEY `location_mailing_list_location_fk_idx` (`location_id`),
  CONSTRAINT `location_mailing_list_corporate` FOREIGN KEY (`corporate_id`) REFERENCES `corporate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_mailing_list_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=60899 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT COMMENT='Subscribed mailing list of clients from Location Website.';

/*Table structure for table `location_memberships` */

DROP TABLE IF EXISTS `location_memberships`;

CREATE TABLE `location_memberships` (
  `location_memberships_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `membership_type` enum('OOA','Other') NOT NULL DEFAULT 'OOA',
  `defaut_menu_group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`location_memberships_id`),
  UNIQUE KEY `location_memberships_id_UNIQUE` (`location_memberships_id`),
  KEY `location_memberships_loc_idx` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Holds the Memberships types accepted by the Location	';

/*Table structure for table `location_menu_article_modifiers` */

DROP TABLE IF EXISTS `location_menu_article_modifiers`;

CREATE TABLE `location_menu_article_modifiers` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `item_id` int(8) NOT NULL,
  `status` enum('Active','Inactive','86') NOT NULL DEFAULT 'Active',
  `modifier` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  `plu` int(11) DEFAULT NULL COMMENT 'Product look up #',
  `price` float(10,2) NOT NULL,
  `taxable` enum('yes','no') NOT NULL,
  `max_quantity` tinyint(3) NOT NULL,
  `delivery` enum('yes','no') NOT NULL,
  `togo` enum('yes','no') NOT NULL,
  `type` enum('SIDES','PREPARATION','TEMPERATURE','EXTRA','DIVIDE','SIZE','TOPPINGS','DOUBLE','COMBO','ALLERGEN') NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `lastchange` datetime DEFAULT NULL,
  `included` enum('yes','no') NOT NULL DEFAULT 'no',
  `priority` int(11) NOT NULL DEFAULT '99',
  `size_fractional` enum('Full','Half','Quarter') DEFAULT NULL,
  `size_price_type` enum('Additional','Fixed') DEFAULT NULL,
  `size_price` decimal(10,2) DEFAULT NULL,
  `livemenu_sp` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_kiosk` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_kiosk_priority` int(11) DEFAULT NULL,
  `kiosk_menu_modifier_name` varchar(64) DEFAULT NULL,
  `livemenu_olo` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_olo_priority` int(11) DEFAULT NULL,
  `olo_menu_modifier_name` varchar(64) DEFAULT NULL,
  `livemenu_tablepoint` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_tablepoint_priority` int(11) DEFAULT NULL,
  `tablepoint_menu_modifier_name` varchar(64) DEFAULT NULL,
  `toppings_included` enum('yes','no') DEFAULT NULL,
  `topping_half_price` decimal(10,2) DEFAULT NULL,
  `topping_quarter_price` decimal(10,2) DEFAULT NULL,
  `prep_shortname` varchar(14) DEFAULT NULL,
  `prep_add` enum('Yes','No') DEFAULT 'No',
  `prep_alergy` enum('Yes','No') DEFAULT 'No',
  `prep_side` enum('Yes','No') DEFAULT 'No',
  `prep_only` enum('Yes','No') DEFAULT 'No',
  `prep_less` enum('Yes','No') DEFAULT 'No',
  `prep_extra` enum('Yes','No') DEFAULT 'No',
  `prep_extra_charge` enum('Yes','No') DEFAULT 'No',
  `prep_remove` enum('Yes','No') DEFAULT 'No',
  `prep_sub` enum('Yes','No') DEFAULT 'No',
  `printer` int(11) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` varchar(45) NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_menu_article_modifiers_item_fk` (`item_id`),
  KEY `location_menu_article_modifier_loc_idx` (`location_id`),
  KEY `location_menu_article_modifiers_printer` (`printer`),
  CONSTRAINT `location_menu_article_modifier_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_menu_article_modifiers_item_fk` FOREIGN KEY (`item_id`) REFERENCES `location_menu_articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=244071 DEFAULT CHARSET=latin1 COMMENT='Modifiers applied to a specific menu article for a location';

/*Table structure for table `location_menu_article_modifiers_default` */

DROP TABLE IF EXISTS `location_menu_article_modifiers_default`;

CREATE TABLE `location_menu_article_modifiers_default` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `modifier` varchar(32) NOT NULL,
  `modifier_groups` int(8) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `description` longtext NOT NULL,
  `article_type` enum('Food','Beverage','Bar','Dessert','Other') DEFAULT NULL,
  `included` enum('yes','no') NOT NULL DEFAULT 'no',
  `priority` int(8) NOT NULL,
  `plu` varchar(20) DEFAULT NULL COMMENT 'Product look up #',
  `price` float NOT NULL,
  `taxable` enum('yes','no') NOT NULL,
  `max_quantity` tinyint(3) NOT NULL,
  `delivery` enum('yes','no') NOT NULL,
  `togo` enum('yes','no') NOT NULL,
  `type` enum('SIDES','PREPARATION','TEMPERATURE','EXTRA','DIVIDE','SIZE','TOPPINGS','DOUBLE','COMBO','ALLERGEN') DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `livemenu_sp` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_kiosk` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_kiosk_priority` int(11) DEFAULT NULL,
  `kiosk_menu_modifier_name` varchar(64) DEFAULT NULL,
  `livemenu_olo` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_olo_priority` int(11) DEFAULT NULL,
  `olo_menu_modifier_name` varchar(64) DEFAULT NULL,
  `livemenu_tablepoint` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_tablepoint_priority` int(11) DEFAULT NULL,
  `tablepoint_menu_modifier_name` varchar(64) DEFAULT NULL,
  `size_fractional` enum('Full','Half','Quarter') DEFAULT NULL,
  `size_price_type` enum('Additional','Fixed') DEFAULT NULL,
  `size_price` decimal(10,2) DEFAULT NULL,
  `toppings_included` enum('yes','no') DEFAULT NULL,
  `topping_half_price` decimal(10,2) DEFAULT NULL,
  `topping_quarter_price` decimal(10,2) DEFAULT NULL,
  `prep_shortname` varchar(14) DEFAULT NULL,
  `prep_add` enum('Yes','No') DEFAULT 'Yes',
  `prep_alergy` enum('Yes','No') DEFAULT 'Yes',
  `prep_side` enum('Yes','No') DEFAULT 'Yes',
  `prep_only` enum('Yes','No') DEFAULT 'Yes',
  `prep_less` enum('Yes','No') DEFAULT 'Yes',
  `prep_extra` enum('Yes','No') DEFAULT 'Yes',
  `prep_extra_charge` enum('Yes','No') DEFAULT 'Yes',
  `prep_remove` enum('Yes','No') DEFAULT 'Yes',
  `prep_sub` enum('Yes','No') DEFAULT 'Yes',
  `printer` int(11) DEFAULT NULL,
  `location_menu_article_modifiers_groups_id` varchar(128) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_menu_article_modifiers_default_fk` (`location_id`),
  KEY `printer_idx` (`printer`),
  CONSTRAINT `location_menu_article_modifiers_default_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30736 DEFAULT CHARSET=latin1 COMMENT='Default modifiers for a location. Used for quick add';

/*Table structure for table `location_menu_article_modifiers_groups` */

DROP TABLE IF EXISTS `location_menu_article_modifiers_groups`;

CREATE TABLE `location_menu_article_modifiers_groups` (
  `menu_article_modifier_group_id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `modifier_group` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  `livemenu_sp` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_kiosk` enum('Y','N') NOT NULL DEFAULT 'Y',
  `kiosk_menu_modifier_group_name` varchar(64) DEFAULT NULL,
  `livemenu_olo` enum('Y','N') NOT NULL DEFAULT 'Y',
  `olo_menu_modifier_group_name` varchar(64) DEFAULT NULL,
  `livemenu_tablepoint` enum('Y','N') NOT NULL DEFAULT 'Y',
  `tablepoint_menu_modifier_group_name` varchar(64) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` varchar(45) NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`menu_article_modifier_group_id`),
  KEY `location_menu_article_modifiers_group_loc_idx` (`location_id`),
  CONSTRAINT `location_menu_article_modifiers_group_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=latin1 COMMENT='Modifiers groups used to group modifiers';

/*Table structure for table `location_menu_articles` */

DROP TABLE IF EXISTS `location_menu_articles`;

CREATE TABLE `location_menu_articles` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `Status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `location_id` int(8) NOT NULL,
  `item` varchar(64) NOT NULL,
  `priority` mediumint(8) NOT NULL,
  `description` longtext NOT NULL,
  `article_type` enum('Food','Beverage','Bar','Dessert','Other','Beer','Wine','Liquor','Retail') DEFAULT NULL,
  `plu` varchar(20) DEFAULT NULL COMMENT 'Product look up #',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ounces_per_price` varchar(20) DEFAULT NULL,
  `taxable` enum('yes','no') DEFAULT NULL,
  `Tax1` int(11) DEFAULT NULL,
  `Tax2` int(11) DEFAULT NULL,
  `tax_name` varchar(32) DEFAULT NULL,
  `tax_percent` decimal(10,4) DEFAULT NULL,
  `max_quantity` int(3) NOT NULL,
  `livemenu_sp` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_kiosk` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_kiosk_priority` int(11) DEFAULT NULL,
  `kiosk_menu_item_name` varchar(64) DEFAULT NULL,
  `livemenu_olo` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_olo_priority` int(11) DEFAULT NULL,
  `olo_menu_item_name` varchar(64) DEFAULT NULL,
  `livemenu_tablepoint` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_tablepoint_priority` int(11) DEFAULT NULL,
  `tablepoint_menu_item_name` varchar(64) DEFAULT NULL,
  `delivery` enum('yes','no') NOT NULL,
  `togo` enum('yes','no') NOT NULL,
  `image` varchar(255) NOT NULL,
  `require_temperature` enum('yes','no') DEFAULT NULL,
  `require_modifier_to_display` enum('yes','no') DEFAULT 'no',
  `modifiers_use_defaults` enum('Yes','No') DEFAULT 'No',
  `refills` int(1) NOT NULL,
  `sides` int(1) NOT NULL,
  `sides_limit` enum('Yes','No') DEFAULT 'No' COMMENT 'Limit number of sides a item has.',
  `sides_required` enum('Yes','No') DEFAULT 'No',
  `modifier_combo` int(1) NOT NULL,
  `modifier_combo_required` enum('Yes','No') DEFAULT 'No',
  `modifier_double` int(1) NOT NULL,
  `modifier_double_required` enum('Yes','No') DEFAULT 'No',
  `printer_id` int(11) NOT NULL,
  `autoprint2` enum('Yes','No') NOT NULL DEFAULT 'No',
  `printer_id2` int(11) NOT NULL,
  `printer_id3` int(11) NOT NULL,
  `printer_id4` int(11) NOT NULL,
  `printer_id5` int(11) NOT NULL,
  `printer_id6` int(11) NOT NULL,
  `drink` enum('yes','no') NOT NULL,
  `glass` enum('yes','no') DEFAULT NULL,
  `glass_name` varchar(100) DEFAULT NULL,
  `glass_price` decimal(10,2) DEFAULT NULL,
  `ounces_per_glass` varchar(20) DEFAULT NULL,
  `glass_name2` varchar(100) DEFAULT NULL,
  `glass_price2` decimal(10,2) DEFAULT NULL,
  `ounces_per_glass2` varchar(20) DEFAULT NULL,
  `divide` enum('yes','no') DEFAULT NULL,
  `max_divide` int(11) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `fire_order` enum('First','Second','Last') DEFAULT NULL,
  `specialty` enum('yes','no') DEFAULT NULL,
  `barcode` varchar(20) DEFAULT NULL,
  `manual_code` varchar(10) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT '0.00',
  `designer` varchar(50) DEFAULT NULL,
  `custom` enum('yes','no') NOT NULL DEFAULT 'no',
  `size` enum('yes','no') NOT NULL DEFAULT 'no',
  `toppings` enum('yes','no') NOT NULL DEFAULT 'no',
  `toppings_extra_free` int(11) DEFAULT NULL,
  `toppings_max` int(11) DEFAULT NULL,
  `retail` enum('Yes','No') NOT NULL DEFAULT 'No',
  `discount_type` varchar(45) DEFAULT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `discount_default_code` int(11) DEFAULT NULL,
  `Weight` enum('Yes','No') DEFAULT 'No' COMMENT 'Used to determine if the article is a weight type charging field',
  `Weight_Type` enum('Kilo','Lbs') DEFAULT NULL COMMENT 'If the article is a weight = yes then this field determines the method to use for weight',
  `prep_shortname` varchar(24) DEFAULT NULL,
  `inventory` varchar(45) DEFAULT NULL,
  `defaut_menu_group_id` int(11) DEFAULT NULL,
  `print_individual_chit` enum('Yes','No') NOT NULL DEFAULT 'No',
  `modifier_group_position_1` enum('SIDES','PREPARATION','TEMPERATURE','EXTRA','DIVIDE','SIZE','TOPPINGS','DOUBLE','COMBO') NOT NULL DEFAULT 'PREPARATION',
  `modifier_group_position_2` enum('SIDES','PREPARATION','TEMPERATURE','EXTRA','DIVIDE','SIZE','TOPPINGS','DOUBLE','COMBO') NOT NULL DEFAULT 'SIDES',
  `modifier_group_position_3` enum('SIDES','PREPARATION','TEMPERATURE','EXTRA','DIVIDE','SIZE','TOPPINGS','DOUBLE','COMBO') NOT NULL DEFAULT 'EXTRA',
  `modifier_group_position_4` enum('SIDES','PREPARATION','TEMPERATURE','EXTRA','DIVIDE','SIZE','TOPPINGS','DOUBLE','COMBO') NOT NULL DEFAULT 'TEMPERATURE',
  `modifier_group_position_5` enum('SIDES','PREPARATION','TEMPERATURE','EXTRA','DIVIDE','SIZE','TOPPINGS','DOUBLE','COMBO') NOT NULL DEFAULT 'TOPPINGS',
  `modifier_group_position_6` enum('SIDES','PREPARATION','TEMPERATURE','EXTRA','DIVIDE','SIZE','TOPPINGS','DOUBLE','COMBO') NOT NULL DEFAULT 'DOUBLE',
  `modifier_group_position_7` enum('SIDES','PREPARATION','TEMPERATURE','EXTRA','DIVIDE','SIZE','TOPPINGS','DOUBLE','COMBO') NOT NULL DEFAULT 'COMBO',
  `modifier_group_position_8` enum('SIDES','PREPARATION','TEMPERATURE','EXTRA','DIVIDE','SIZE','TOPPINGS','DOUBLE','COMBO') NOT NULL DEFAULT 'SIZE',
  `modifier_group_position_9` enum('SIDES','PREPARATION','TEMPERATURE','EXTRA','DIVIDE','SIZE','TOPPINGS','DOUBLE','COMBO') NOT NULL DEFAULT 'DIVIDE',
  `get_technician_id` enum('Yes','No') DEFAULT 'No',
  `cleaning_fee` decimal(14,2) DEFAULT NULL,
  `commission_deduction` decimal(14,2) DEFAULT NULL,
  `ticket` enum('Yes','No') DEFAULT 'No',
  `ticket_alert_on_sale` text,
  `ticket_age_min` int(2) DEFAULT NULL,
  `ticket_age_max` int(2) DEFAULT NULL,
  `ticket_includes_admission` enum('Yes','No') DEFAULT NULL,
  `ticket_includes_other1` int(8) DEFAULT NULL,
  `ticket_includes_other2` int(8) DEFAULT NULL,
  `ticket_includes_other3` int(8) DEFAULT NULL,
  `ticket_counts_min` int(2) DEFAULT NULL,
  `ticket_counts_max` int(2) DEFAULT NULL,
  `ticket_counts_req_qty` int(2) DEFAULT NULL,
  `ticket_counts_cutoff` int(2) DEFAULT NULL,
  `ticket_restrict_id_required` enum('Yes','No') DEFAULT NULL,
  `ticket_restrict_valid_period` int(3) DEFAULT NULL,
  `ticket_restrict_resv_req` enum('Yes','No') DEFAULT NULL,
  `ticket_restrict_cut_off` int(2) DEFAULT NULL,
  `ticket_avail_time_before` varchar(5) DEFAULT NULL,
  `ticket_avail_showtime` text,
  `ticket_avail_duration` varchar(5) DEFAULT NULL,
  `ticket_avail_time_start` time DEFAULT NULL,
  `ticket_avail_time_end` time DEFAULT NULL,
  `ticket_avail_dow` varchar(32) DEFAULT NULL,
  `ticket_avail_days` text,
  `ticket_avail_closed` text,
  `ticket_comm` enum('Yes','No') DEFAULT NULL,
  `ticket_comm_restrictions` text,
  `ticket_comm_exceptions` text,
  `tixket_comm_internal` decimal(4,2) DEFAULT NULL,
  `ticket_comm_external` decimal(4,2) DEFAULT NULL,
  `ticket_comm_quota` decimal(14,2) DEFAULT NULL,
  `ticket_print` enum('No','Ticket','Bracelet') DEFAULT 'No',
  `ticket_part_of` int(11) DEFAULT NULL,
  `ticket_one_time_show` enum('Yes','No') DEFAULT 'No',
  `membership` enum('Yes','No') DEFAULT 'No',
  `membership_adults` int(2) DEFAULT NULL,
  `membership_adults_req` int(2) DEFAULT NULL,
  `membership_childs` int(2) DEFAULT NULL,
  `membership_child_req` int(2) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Item` (`item`),
  KEY `priority` (`priority`),
  KEY `location_menu_articles_fk` (`location_id`),
  KEY `lma_printer_id` (`printer_id`),
  KEY `lma_printer_id2` (`printer_id2`),
  KEY `lma_printer_id3` (`printer_id3`),
  KEY `lma_printer_id4` (`printer_id4`),
  KEY `lma_printer_id5` (`printer_id5`),
  KEY `lma_printer_id6` (`printer_id6`),
  KEY `lma_discount_defaul_code` (`discount_default_code`),
  CONSTRAINT `location_menu_articles_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43534 DEFAULT CHARSET=latin1 COMMENT='Menu articles at a location';

/*Table structure for table `location_menu_articles_taxes` */

DROP TABLE IF EXISTS `location_menu_articles_taxes`;

CREATE TABLE `location_menu_articles_taxes` (
  `Tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `Location_id` int(8) NOT NULL,
  `Tax_name` varchar(45) NOT NULL,
  `Tax_type` enum('Additional','VAT') NOT NULL DEFAULT 'Additional',
  `Tax_percentage` decimal(10,4) NOT NULL,
  PRIMARY KEY (`Tax_id`),
  KEY `location_menu_article_taxes_loc_idx` (`Location_id`),
  CONSTRAINT `location_menu_article_taxes_loc` FOREIGN KEY (`Location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8 COMMENT='Stores tax information for a menu article to calculate price';

/*Table structure for table `location_menu_group` */

DROP TABLE IF EXISTS `location_menu_group`;

CREATE TABLE `location_menu_group` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `market` enum('POS','Retail') DEFAULT 'POS',
  `menu_group` varchar(64) NOT NULL,
  `priority` mediumint(8) NOT NULL,
  `description` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `fire_priority` enum('first','second','last') DEFAULT NULL,
  `livemenu_sp` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_kiosk` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_kiosk_priority` int(11) DEFAULT NULL,
  `kiosk_menu_group_name` varchar(64) DEFAULT NULL,
  `livemenu_olo` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_olo_priority` int(11) DEFAULT NULL,
  `olo_menu_group_name` varchar(64) DEFAULT NULL,
  `livemenu_tablepoint` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_tablepoint_priority` int(11) DEFAULT NULL,
  `tablepoint_menu_group_name` varchar(64) DEFAULT NULL,
  `lastchange` datetime DEFAULT NULL,
  `one_item_per_ticket` enum('Yes','No') NOT NULL DEFAULT 'No',
  `restrict_groups_from_gift_card` enum('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`id`),
  KEY `menu_group` (`menu_group`),
  KEY `location_menu_group_fk` (`location_id`),
  CONSTRAINT `location_menu_group_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2904 DEFAULT CHARSET=latin1 COMMENT='Menu groups for a specific menu for a location';

/*Table structure for table `location_menu_items` */

DROP TABLE IF EXISTS `location_menu_items`;

CREATE TABLE `location_menu_items` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `menu_id` int(8) NOT NULL,
  `item_id` int(8) NOT NULL,
  `menu_group` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `status` enum('Active','Inactive','86') DEFAULT 'Active',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `glass_price` decimal(10,2) DEFAULT '0.00',
  `glass_price2` decimal(10,2) DEFAULT '0.00',
  `priority` int(11) DEFAULT NULL,
  `livemenu_sp` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_kiosk` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_kiosk_priority` int(11) DEFAULT NULL,
  `livemenu_olo` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_olo_priority` int(11) DEFAULT NULL,
  `livemenu_tablepoint` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_tablepoint_priority` int(11) DEFAULT NULL,
  `allergen` enum('Yes','No') DEFAULT 'No',
  `promotion` enum('Yes','No') DEFAULT 'No',
  `promotion_type` enum('Fixed Amount','Percentage') DEFAULT NULL,
  `promotion_amount` decimal(14,2) DEFAULT NULL,
  `promotion_percentage` decimal(5,2) DEFAULT NULL,
  `promotion_req_qty` int(9) DEFAULT NULL,
  `promotion_continued` enum('Yes','No') DEFAULT NULL,
  `promotion_dow` text,
  `promotion_starttime` time DEFAULT NULL,
  `promotion_endtime` time DEFAULT NULL,
  `promotion_percentage_round` enum('Up','Down') DEFAULT NULL,
  `promotion_percentage_roundto` enum('.01','.10','1','10') DEFAULT NULL,
  `open_price` enum('Yes','No') DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_menu_items_fk` (`menu_id`),
  KEY `location_menu_items_item_fk` (`item_id`),
  KEY `location_menu_items_group_fk` (`menu_group`),
  KEY `location_menu_items_loc_idx` (`location_id`),
  CONSTRAINT `location_menu_items_fk` FOREIGN KEY (`menu_id`) REFERENCES `location_menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_menu_items_group_fk` FOREIGN KEY (`menu_group`) REFERENCES `location_menu_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_menu_items_item_fk` FOREIGN KEY (`item_id`) REFERENCES `location_menu_articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_menu_items_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=48217 DEFAULT CHARSET=latin1 COMMENT='Links menu_articles to a specific menu group and menu';

/*Table structure for table `location_menus` */

DROP TABLE IF EXISTS `location_menus`;

CREATE TABLE `location_menus` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `menu` varchar(64) NOT NULL,
  `image` text NOT NULL,
  `description` longtext NOT NULL,
  `priority` mediumint(8) DEFAULT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  `type` enum('POS','Retail','Quick') DEFAULT NULL,
  `print_check_images` varchar(255) NOT NULL,
  `print_check_top_text` text NOT NULL,
  `print_check_bottom_text` text NOT NULL,
  `print_check_bottom_image` varchar(255) NOT NULL,
  `print_receipt_images` varchar(255) NOT NULL,
  `print_receipt_top_text` text NOT NULL,
  `print_receipt_bottom_text` text NOT NULL,
  `print_receipt_bottom_image` varchar(255) NOT NULL,
  `livemenu_sp` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_kiosk` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_kiosk_priority` int(11) DEFAULT NULL,
  `kiosk_menu_name` varchar(64) DEFAULT NULL,
  `livemenu_olo` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_olo_priority` int(11) DEFAULT NULL,
  `olo_menu_name` varchar(64) DEFAULT NULL,
  `livemenu_tablepoint` enum('Y','N') NOT NULL DEFAULT 'Y',
  `livemenu_tablepoint_priority` int(11) DEFAULT NULL,
  `tablepoint_menu_name` varchar(64) DEFAULT NULL,
  `printer_id` int(11) NOT NULL,
  `lastchange` datetime DEFAULT NULL,
  `hotel_location_id` int(8) DEFAULT NULL,
  `hotel_posting_code` int(11) DEFAULT NULL,
  `check_style` enum('Standard','Arabic','Hotel','Europe','Brazil','Salon') NOT NULL DEFAULT 'Standard',
  `printer_required_check` enum('Yes','No') DEFAULT NULL,
  `promotion` enum('Yes','No') DEFAULT 'No',
  `promotion_type` enum('Fixed Amount','Percentage') DEFAULT NULL,
  `promotion_amount` decimal(14,2) DEFAULT NULL,
  `promotion_percentage` decimal(5,2) DEFAULT NULL,
  `promotion_dow` text,
  `promotion_starttime` time DEFAULT NULL,
  `promotion_endtime` time DEFAULT NULL,
  `promotion_percentage_round` enum('Up','Down') DEFAULT NULL,
  `promotion_percentage_roundto` enum('.01','.10','1','10') DEFAULT NULL,
  `available_dow` text,
  `hours_operation` enum('Yes','No') DEFAULT 'Yes',
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Menu` (`menu`),
  KEY `location_menus_fk` (`location_id`),
  KEY `location_menus_hotel_postingcode_idx` (`hotel_posting_code`),
  KEY `location_menus_printer_idx` (`printer_id`),
  CONSTRAINT `location_menus_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_menus_hotel_postingcode` FOREIGN KEY (`hotel_posting_code`) REFERENCES `location_hotel_postingtype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=885 DEFAULT CHARSET=latin1 COMMENT='Menus registered with the location';

/*Table structure for table `location_messages` */

DROP TABLE IF EXISTS `location_messages`;

CREATE TABLE `location_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `type_of_message` enum('message','email','pmb','Surveyed','manual','call','visit','contract','webinar','proposal') NOT NULL,
  `email_type` varchar(60) NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `status` enum('read','unread') NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `sent_by` int(8) NOT NULL,
  `sent_by_employee_id` int(8) DEFAULT NULL,
  `read_datetime` datetime DEFAULT NULL,
  `read_by_employee_id` int(8) DEFAULT NULL,
  `read_by_admin_id` int(8) DEFAULT NULL,
  `sent_by_client` int(8) DEFAULT NULL,
  `read_by_client` int(8) DEFAULT NULL,
  `reminder_date` date DEFAULT NULL,
  `message_image` varchar(255) DEFAULT NULL,
  `file_loc` text,
  `direct_link` tinytext,
  `direct_link_name` varchar(100) DEFAULT NULL,
  `email_cc` text,
  PRIMARY KEY (`id`),
  KEY `location_messages_fk` (`location_id`),
  KEY `location_messages_read_by_admin_idx` (`read_by_admin_id`),
  KEY `location_messages_read_by_client_idx` (`read_by_client`),
  KEY `location_messages_read_byemp_idx` (`read_by_employee_id`),
  KEY `location_messages_sent_by_client_idx` (`sent_by_client`),
  KEY `location_messages_sent_by_emp_idx` (`sent_by_employee_id`),
  CONSTRAINT `location_messages_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_messages_read_by_admin` FOREIGN KEY (`read_by_admin_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_messages_read_by_client` FOREIGN KEY (`read_by_client`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_messages_read_by_emp` FOREIGN KEY (`read_by_employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_messages_sent_by_client` FOREIGN KEY (`sent_by_client`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_messages_sent_by_emp` FOREIGN KEY (`sent_by_employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1100 DEFAULT CHARSET=latin1 COMMENT='Messages to and from a location';

/*Table structure for table `location_olo_peak_preptime` */

DROP TABLE IF EXISTS `location_olo_peak_preptime`;

CREATE TABLE `location_olo_peak_preptime` (
  `location_olo_peak_preptime_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `minimum` varchar(45) DEFAULT NULL,
  `maximum` varchar(45) DEFAULT NULL,
  `start_time` varchar(15) DEFAULT NULL,
  `stop_time` varchar(15) DEFAULT NULL,
  `open_mon` enum('Yes','No') DEFAULT NULL,
  `open_tue` enum('Yes','No') DEFAULT NULL,
  `open_wed` enum('Yes','No') DEFAULT NULL,
  `open_thu` enum('Yes','No') DEFAULT NULL,
  `open_fri` enum('Yes','No') DEFAULT NULL,
  `open_sat` enum('Yes','No') DEFAULT NULL,
  `open_sun` enum('Yes','No') DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_olo_peak_preptime_id`),
  KEY `location_olo_peak_preptime_location_id_idk` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;

/*Table structure for table `location_olo_preptime` */

DROP TABLE IF EXISTS `location_olo_preptime`;

CREATE TABLE `location_olo_preptime` (
  `location_olo_preptime_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `minimum` varchar(45) DEFAULT NULL,
  `maximum` varchar(45) DEFAULT NULL,
  `prep_time` varchar(15) DEFAULT NULL,
  `peak_time_id` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_olo_preptime_id`),
  KEY `location_olo_preptime_location_id_idk` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=latin1;

/*Table structure for table `location_olopoint_closed_schedule` */

DROP TABLE IF EXISTS `location_olopoint_closed_schedule`;

CREATE TABLE `location_olopoint_closed_schedule` (
  `location_olopoint_closed_schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `close_date` date NOT NULL,
  `re_open_date` date NOT NULL,
  `close_message` text,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_olopoint_closed_schedule_id`),
  KEY `location_olopoint_closed_schedule_location_id_idk` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Table structure for table `location_olopoint_settings` */

DROP TABLE IF EXISTS `location_olopoint_settings`;

CREATE TABLE `location_olopoint_settings` (
  `location_olopoint_settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `open_mon` enum('yes','no') DEFAULT NULL,
  `open_mon_starttime` varchar(5) DEFAULT NULL,
  `open_mon_endtime` varchar(5) DEFAULT NULL,
  `open_tue` enum('yes','no') DEFAULT NULL,
  `open_tue_starttime` varchar(5) DEFAULT NULL,
  `open_tue_endtime` varchar(5) DEFAULT NULL,
  `open_wed` enum('yes','no') DEFAULT NULL,
  `open_wed_starttime` varchar(5) DEFAULT NULL,
  `open_wed_endtime` varchar(5) DEFAULT NULL,
  `open_thu` enum('yes','no') DEFAULT NULL,
  `open_thu_starttime` varchar(5) DEFAULT NULL,
  `open_thu_endtime` varchar(5) DEFAULT NULL,
  `open_fri` enum('yes','no') DEFAULT NULL,
  `open_fri_starttime` varchar(5) DEFAULT NULL,
  `open_fri_endtime` varchar(5) DEFAULT NULL,
  `open_sat` enum('yes','no') DEFAULT NULL,
  `open_sat_starttime` varchar(5) DEFAULT NULL,
  `open_sat_endtime` varchar(5) DEFAULT NULL,
  `open_sun` enum('yes','no') DEFAULT NULL,
  `open_sun_starttime` varchar(5) DEFAULT NULL,
  `open_sun_endtime` varchar(5) DEFAULT NULL,
  `confirmation_email` varchar(64) DEFAULT NULL,
  `phone_number_on_receipt` enum('Yes','No') DEFAULT 'No',
  `scheduled_orders` enum('Yes','No') DEFAULT 'No',
  `MF_UserName` varchar(45) DEFAULT NULL,
  `MF_Password` varchar(45) DEFAULT NULL,
  `MF_UserID` varchar(45) DEFAULT NULL,
  `MF_LinkID` varchar(45) DEFAULT NULL,
  `MF_accountid` varchar(45) DEFAULT NULL,
  `MF_Deviceid` varchar(45) DEFAULT NULL,
  `MF_GateWayId` varchar(45) DEFAULT NULL,
  `MF_host_URL` text,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_olopoint_settings_id`),
  KEY `location_olopoint_settings_location_id_idk` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Table structure for table `location_olopoint_special_schedule` */

DROP TABLE IF EXISTS `location_olopoint_special_schedule`;

CREATE TABLE `location_olopoint_special_schedule` (
  `location_olopoint_special_schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `open_starttime` varchar(5) DEFAULT NULL,
  `open_endtime` varchar(5) DEFAULT NULL,
  `close_message` text,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_olopoint_special_schedule_id`),
  KEY `location_olopoint_special_schedule_location_id_idk` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Table structure for table `location_orders_deposits` */

DROP TABLE IF EXISTS `location_orders_deposits`;

CREATE TABLE `location_orders_deposits` (
  `location_orders_deposits_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `source` enum('hotel','restaurant','retail') NOT NULL,
  `payment_type` int(11) NOT NULL,
  `payment_code` varchar(100) NOT NULL,
  `deposited` varchar(45) NOT NULL DEFAULT '0',
  `difference` varchar(45) DEFAULT '0',
  `total` varchar(45) NOT NULL DEFAULT '0',
  `reason_for_diff` text,
  `created_on` varchar(45) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`location_orders_deposits_id`),
  UNIQUE KEY `location_orders_deposits_loc_date_pt_source_idx` (`location_id`,`date`,`payment_type`,`source`),
  KEY `fk_location_orders_deposits_payment_type` (`payment_type`),
  CONSTRAINT `fk_location_orders_deposits_payment_type` FOREIGN KEY (`payment_type`) REFERENCES `location_payments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_orders_deposits_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=latin1 COMMENT='Deposits made from the location to a bank or other';

/*Table structure for table `location_payments` */

DROP TABLE IF EXISTS `location_payments`;

CREATE TABLE `location_payments` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `priority` int(11) DEFAULT '1',
  `payment_type` enum('Adjustments','Advance Deposit','Cash','Check','Credit Card','Debit Card','ExpenseTAB','Gift Certificate','Gratuity','Interface','Surcharge','Receivables','Clover','Pax','Ingenico') NOT NULL,
  `payment_code` varchar(50) NOT NULL,
  `local_code` varchar(45) NOT NULL,
  `adjustment_discount_type` enum('Fixed','Percentage') DEFAULT NULL,
  `adjustment_discount_rate` decimal(10,4) DEFAULT NULL,
  `taxable` enum('Yes','No') DEFAULT 'Yes',
  `usedin_pos` enum('Yes','No') DEFAULT NULL,
  `usedin_register` enum('Yes','No') DEFAULT NULL,
  `usedin_hotel` enum('Yes','No') DEFAULT NULL,
  `usedin_receivables` enum('Yes','No') DEFAULT NULL,
  `usedin_giftcertificates` enum('Yes','No') DEFAULT NULL,
  `hotel_charge` enum('Yes','No') NOT NULL DEFAULT 'No',
  `hotel_automated` enum('Yes','No') DEFAULT 'No',
  `hotel_location_id` int(11) DEFAULT NULL,
  `hotel_location_postingtype` int(11) DEFAULT NULL,
  `processor` enum('Yes','No') DEFAULT 'No',
  `processor_use_cc_batch` enum('Yes','No') DEFAULT 'No',
  `validate_card_number` enum('Yes','No') DEFAULT 'No',
  `company` enum('Paypal','First Data','Authorize.Net','XCharge','Braintree','Global','SaferPay','First Data Payeezy') DEFAULT NULL,
  `account` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `signature` varchar(100) NOT NULL,
  `Braintree_merchantid` varchar(200) DEFAULT NULL,
  `Braintree_publickey` varchar(200) DEFAULT NULL,
  `Braintree_privatekey` varchar(200) DEFAULT NULL,
  `Xcharge_XWebID` varchar(200) DEFAULT NULL,
  `Xcharge_TerminalID` varchar(200) DEFAULT NULL,
  `Xcharge_AuthKey` varchar(200) DEFAULT NULL,
  `Xcharge_Industry` enum('RETAIL','RESTAURANT','ECOMMERCE','MOTO') DEFAULT NULL,
  `global_host` varchar(200) DEFAULT NULL,
  `global_port` varchar(200) DEFAULT NULL,
  `global_user` varchar(200) DEFAULT NULL,
  `global_password` varchar(200) DEFAULT NULL,
  `firstdata_gateway_api_login` varchar(200) DEFAULT NULL,
  `firstdata_gateway_api_password` varchar(200) DEFAULT NULL,
  `firstdata_reporting_token` varchar(200) DEFAULT NULL,
  `fd_payeezy_key` varchar(200) DEFAULT NULL,
  `fd_payeezy_secret` varchar(200) DEFAULT NULL,
  `fd_payeezy_token` varchar(200) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `tax_amount` decimal(10,4) DEFAULT NULL,
  `tax_id2` int(11) DEFAULT NULL,
  `tax2_amount` decimal(10,4) DEFAULT NULL,
  `print_receipt_after_cc_payment` enum('Yes','No') DEFAULT 'No',
  `print_authorization_slip_for_non_cc_processors` enum('Yes','No') DEFAULT 'No',
  `payment_req_signature` enum('Yes','No') DEFAULT 'No',
  `payment_req_pin` enum('Yes','No') DEFAULT 'No',
  `omnivore_payment_id` int(11) DEFAULT NULL,
  `verify_gift_card` enum('Yes','No') DEFAULT 'Yes',
  `show_payment_tax_on_receipt` enum('Yes','No') DEFAULT 'No',
  `discount_minimum` decimal(10,2) DEFAULT NULL,
  `discount_verification` enum('Text','Code','None') NOT NULL DEFAULT 'None',
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_payments_fk` (`location_id`),
  KEY `location_payments_hotel_idx` (`hotel_location_id`),
  KEY `location_payments_hotel_posting_idx` (`hotel_location_postingtype`),
  CONSTRAINT `location_payments_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_payments_hotel` FOREIGN KEY (`hotel_location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_payments_hotel_posting` FOREIGN KEY (`hotel_location_postingtype`) REFERENCES `location_hotel_postingtype` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5264 DEFAULT CHARSET=latin1 COMMENT='Payments types associated with the location';

/*Table structure for table `location_petty_cash` */

DROP TABLE IF EXISTS `location_petty_cash`;

CREATE TABLE `location_petty_cash` (
  `location_petty_cash_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `type` enum('General','R&M','Food','Utilities','Payroll','Other') NOT NULL DEFAULT 'General',
  `date` varchar(45) DEFAULT NULL,
  `time` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `reason` text,
  `company` varchar(64) DEFAULT NULL,
  `comapny_tax_id` varchar(16) DEFAULT NULL,
  `dateofservice` date DEFAULT NULL,
  `invoice_number` varchar(16) DEFAULT NULL,
  `source` varchar(45) DEFAULT NULL,
  `image` text,
  `created_on` varchar(45) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`location_petty_cash_id`),
  KEY `location_petty_cash_loc_idx` (`location_id`),
  KEY `location_petty_cash_emp_idx` (`emp_id`),
  CONSTRAINT `location_petty_cash_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_petty_cash_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=latin1 COMMENT='Petty Cash Transactions between an employee and a location';

/*Table structure for table `location_printers` */

DROP TABLE IF EXISTS `location_printers`;

CREATE TABLE `location_printers` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `printer_name` varchar(50) NOT NULL,
  `printer_model` varchar(45) DEFAULT NULL,
  `path` varchar(500) NOT NULL,
  `printer_address` varchar(255) NOT NULL,
  `printer_backup` int(11) NOT NULL,
  `print_type` enum('Text','Image') DEFAULT 'Text',
  `font_a_columns` int(11) DEFAULT '-1',
  `font_b_columns` int(11) DEFAULT '-1',
  `print` enum('yes','no') NOT NULL,
  `print_direct` enum('Yes','No') NOT NULL DEFAULT 'No',
  `auto_cut` enum('Yes','No') DEFAULT 'Yes',
  `printer_group_items` enum('Yes','No') DEFAULT 'Yes',
  `printer1_type` enum('58mm','76mm','80mm','80mmB','80mmC','Fiscal Brazil') NOT NULL DEFAULT '80mm',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `connection_type` enum('USB','Ethernet','Wireless') DEFAULT NULL,
  `printer_IP` varchar(45) DEFAULT NULL,
  `connected_directly_PP` enum('Yes','No') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_printers_fk` (`location_id`),
  KEY `language_id_idx` (`language_id`),
  CONSTRAINT `location_printers_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=232 DEFAULT CHARSET=latin1 COMMENT='Printers registered with the location';

/*Table structure for table `location_products` */

DROP TABLE IF EXISTS `location_products`;

CREATE TABLE `location_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `global_product_id` int(11) NOT NULL,
  `description` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_products_loc_idx` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=584 DEFAULT CHARSET=latin1;

/*Table structure for table `location_quality` */

DROP TABLE IF EXISTS `location_quality`;

CREATE TABLE `location_quality` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('unassigned','assigned','corrected','on_hold') NOT NULL,
  `over_budget` text,
  `priority` enum('required','urgent','emergency') NOT NULL,
  `description` text NOT NULL,
  `notes` text NOT NULL,
  `report_date` datetime DEFAULT NULL,
  `report_employee` int(8) DEFAULT NULL,
  `area` int(11) DEFAULT NULL,
  `area_x` decimal(10,4) DEFAULT NULL,
  `area_y` decimal(10,4) DEFAULT NULL,
  `department` varchar(32) NOT NULL,
  `due_date` date DEFAULT NULL,
  `assigned_type` enum('Employee','Vendor','RVP','VP','Manager','Cheef leader') DEFAULT NULL,
  `assigned_date` datetime DEFAULT NULL,
  `assigned_employee` int(8) DEFAULT NULL,
  `assigned_company` varchar(64) DEFAULT NULL,
  `assigned_company_rep` varchar(32) DEFAULT NULL,
  `assigned_company_phone` varchar(32) DEFAULT NULL,
  `assigned_company_duedate` datetime DEFAULT NULL,
  `assigend_company_estimate` varchar(64) DEFAULT NULL,
  `corrected_date` datetime DEFAULT NULL,
  `corrected_employee` int(8) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `progress_notes` text,
  `completion_notes` text,
  `location_petty_cash_id` int(11) DEFAULT NULL,
  `last_by` varchar(45) NOT NULL,
  `last_on` varchar(45) NOT NULL,
  `last_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_quality_fk` (`location_id`),
  KEY `location_quality_area_fk_idx` (`area`),
  KEY `location_quality_assigned_emp_idx` (`assigned_employee`),
  KEY `location_quality_corrected_employee_idx` (`corrected_employee`),
  KEY `location_quality_report_emp_idx` (`report_employee`),
  CONSTRAINT `location_quality_area_fk` FOREIGN KEY (`area`) REFERENCES `location_quality_areas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_quality_assigned_emp` FOREIGN KEY (`assigned_employee`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_quality_corrected_employee` FOREIGN KEY (`corrected_employee`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_quality_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_quality_report_emp` FOREIGN KEY (`report_employee`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2250 DEFAULT CHARSET=latin1 COMMENT='Quality Control records for a location';

/*Table structure for table `location_quality_areas` */

DROP TABLE IF EXISTS `location_quality_areas`;

CREATE TABLE `location_quality_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `area` varchar(200) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_qulity_areas_fk` (`location_id`),
  CONSTRAINT `location_qulity_areas_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1715 DEFAULT CHARSET=utf8 COMMENT='Areas that can be linked to a quality control record';

/*Table structure for table `location_quality_images` */

DROP TABLE IF EXISTS `location_quality_images`;

CREATE TABLE `location_quality_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `quality_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_quality_images_loc_idx` (`location_id`),
  KEY `location_quality_images_quality_idx` (`quality_id`),
  CONSTRAINT `location_quality_images_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_quality_images_quality` FOREIGN KEY (`quality_id`) REFERENCES `location_quality` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1671 DEFAULT CHARSET=latin1 COMMENT='Images linked with a quality control record';

/*Table structure for table `location_quality_images_temp` */

DROP TABLE IF EXISTS `location_quality_images_temp`;

CREATE TABLE `location_quality_images_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `quality_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lqit_location_fk_idx` (`location_id`),
  CONSTRAINT `lqit_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=340 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `location_reservation_slots` */

DROP TABLE IF EXISTS `location_reservation_slots`;

CREATE TABLE `location_reservation_slots` (
  `reservation_slots_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `internal` enum('00','15','30','45') NOT NULL DEFAULT '15',
  `quantity_of_slots` int(2) NOT NULL DEFAULT '1',
  `type` enum('Add Slot','Delete Slot','Cancel Slot') NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`reservation_slots_id`),
  KEY `location_reservation_slots_location_fk_idx` (`location_id`),
  CONSTRAINT `location_reservation_slots_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Store reservation slots to be used/not used by date & time';

/*Table structure for table `location_rooms` */

DROP TABLE IF EXISTS `location_rooms`;

CREATE TABLE `location_rooms` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `type` enum('guest','meeting','other','spa') NOT NULL,
  `priority` int(8) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(128) NOT NULL,
  `image` varchar(250) NOT NULL,
  `area` varchar(32) NOT NULL,
  `size` varchar(32) NOT NULL,
  `rental_rate` decimal(10,2) NOT NULL,
  `max_capacity` tinyint(3) NOT NULL,
  `roomtype` int(11) NOT NULL,
  `use_status` enum('Clean','Dirty','Out of Order') NOT NULL,
  `max_adult` int(2) NOT NULL,
  `max_child` int(2) NOT NULL,
  `display_height` int(3) NOT NULL DEFAULT '50',
  `display_width` int(3) NOT NULL DEFAULT '50',
  `display_x` int(3) NOT NULL,
  `display_y` int(3) NOT NULL,
  `angle` decimal(10,2) NOT NULL,
  `current_status` enum('Occupied','Vacant') NOT NULL,
  `current_hotelacct` int(11) DEFAULT NULL,
  `maid_inspection` enum('Vacant Clean','Vacant Dirty','Occupied Clean','Occupied Dirty') NOT NULL,
  `beds` int(10) NOT NULL,
  `last_cleaned` datetime NOT NULL,
  `employee_assigned` int(10) DEFAULT NULL,
  `handicap` enum('Yes','No') NOT NULL,
  `smoking` enum('Yes','No') NOT NULL,
  `non_inventory_room` enum('Yes','No') NOT NULL,
  `use_count` int(10) NOT NULL,
  `connect_left` varchar(10) NOT NULL,
  `connect_right` varchar(10) NOT NULL,
  `floor_number` int(10) NOT NULL,
  `view` varchar(50) NOT NULL,
  `building` varchar(50) NOT NULL,
  `extension1` varchar(50) NOT NULL,
  `extension2` varchar(50) NOT NULL,
  `notes` text NOT NULL,
  `room_temp_taken` datetime DEFAULT NULL,
  `account_temp_taken` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_rooms_fk` (`location_id`),
  KEY `location_rooms_current_acct_idx` (`current_hotelacct`),
  KEY `location_rooms_emp_assigned_idx` (`employee_assigned`),
  KEY `location_rooms_loc_rtype_idx` (`location_id`,`type`,`roomtype`),
  KEY `location_rooms_noninvroom_idx` (`status`,`type`,`non_inventory_room`,`location_id`),
  KEY `location_rooms_avroom_idx` (`status`,`type`,`current_status`,`non_inventory_room`,`use_status`,`location_id`),
  CONSTRAINT `location_rooms_current_acct` FOREIGN KEY (`current_hotelacct`) REFERENCES `location_hotelacct` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_rooms_emp_assigned` FOREIGN KEY (`employee_assigned`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_rooms_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=407 DEFAULT CHARSET=utf8 COMMENT='Rooms registered with a location';

/*Table structure for table `location_rooms_inventory` */

DROP TABLE IF EXISTS `location_rooms_inventory`;

CREATE TABLE `location_rooms_inventory` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `room_id` int(8) NOT NULL,
  `status` enum('reserved','cancelled','noshow','definite','tentative','arrived','completed') NOT NULL,
  `date` date NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `event_name` varchar(64) NOT NULL,
  `notes` text,
  `contact` varchar(32) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `rental_rate` decimal(14,2) DEFAULT NULL,
  `order_id` int(8) DEFAULT NULL,
  `quantity` int(8) NOT NULL,
  `arrived_datetime` datetime DEFAULT NULL,
  `arrived_employee_id` int(11) DEFAULT NULL,
  `completed_datetime` datetime DEFAULT NULL,
  `completed_employee_id` int(11) DEFAULT NULL,
  `no_show_datetime` datetime DEFAULT NULL,
  `image` longtext,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_rooms_inventory_fk` (`room_id`),
  KEY `location_rooms_loc_idx` (`location_id`),
  CONSTRAINT `location_rooms_inventory_fk` FOREIGN KEY (`room_id`) REFERENCES `location_rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_rooms_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='Non-guest room reservations for a hotel';

/*Table structure for table `location_rooms_inventory_members` */

DROP TABLE IF EXISTS `location_rooms_inventory_members`;

CREATE TABLE `location_rooms_inventory_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_rooms_inventory_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `member_fname` varchar(50) DEFAULT NULL,
  `member_lname` varchar(50) DEFAULT NULL,
  `member_email` varchar(50) DEFAULT NULL,
  `member_nickname` varchar(50) DEFAULT NULL,
  `member_dob` varchar(50) DEFAULT NULL,
  `member_gender` char(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=latin1;

/*Table structure for table `location_rooms_maintenance` */

DROP TABLE IF EXISTS `location_rooms_maintenance`;

CREATE TABLE `location_rooms_maintenance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Room_id` int(8) NOT NULL,
  `Location_id` int(8) NOT NULL,
  `Location_quality_id` int(11) NOT NULL,
  `room_status` enum('On Request','Started','Completed','Cancelled') NOT NULL DEFAULT 'On Request',
  `description` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `Created_on` varchar(45) NOT NULL,
  `Created_by` int(8) NOT NULL,
  `created_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_rooms_maintenance_loc_idx` (`Location_id`),
  KEY `location_rooms_maintenance_room_idx` (`Room_id`),
  KEY `location_rooms_maintenance_loc_quality_idx` (`Location_quality_id`),
  CONSTRAINT `location_rooms_maintenance_loc` FOREIGN KEY (`Location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_rooms_maintenance_loc_quality` FOREIGN KEY (`Location_quality_id`) REFERENCES `location_quality` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_rooms_maintenance_room` FOREIGN KEY (`Room_id`) REFERENCES `location_rooms` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Maintenance records for a location';

/*Table structure for table `location_schedules` */

DROP TABLE IF EXISTS `location_schedules`;

CREATE TABLE `location_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('A','I','E') NOT NULL,
  `shift_quantity` int(20) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `department` varchar(40) NOT NULL,
  `dow` tinytext,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_schedules_fk` (`location_id`),
  KEY `location_schedules_stats_idk` (`id`,`status`),
  CONSTRAINT `location_schedules_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=938 DEFAULT CHARSET=utf8 COMMENT='Scheduling information for a location and employees';

/*Table structure for table `location_sections` */

DROP TABLE IF EXISTS `location_sections`;

CREATE TABLE `location_sections` (
  `section_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `section_name` varchar(32) NOT NULL,
  `section_floor_plan` varchar(255) DEFAULT NULL,
  `fullpage` enum('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`section_id`),
  KEY `location_section_loc_fk_idx` (`location_id`),
  CONSTRAINT `location_section_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT COMMENT='Defines the subsections of a location layout';

/*Table structure for table `location_sections_tables` */

DROP TABLE IF EXISTS `location_sections_tables`;

CREATE TABLE `location_sections_tables` (
  `section_table_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `section_id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `tx` varchar(10) DEFAULT NULL,
  `ty` varchar(10) DEFAULT NULL,
  `size_x` varchar(60) DEFAULT NULL,
  `size_y` varchar(60) DEFAULT NULL,
  `angle` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`section_table_id`),
  KEY `section_table_loc_fk_idx` (`location_id`),
  KEY `section_tables_section_fk_idx` (`section_id`),
  KEY `section_tables_table_fk_idx` (`table_id`),
  CONSTRAINT `section_tables_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `section_tables_section_fk` FOREIGN KEY (`section_id`) REFERENCES `location_sections` (`section_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `section_tables_table_fk` FOREIGN KEY (`table_id`) REFERENCES `location_tables` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=539 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT COMMENT='Defines the tables associated with a section in a location';

/*Table structure for table `location_shifts` */

DROP TABLE IF EXISTS `location_shifts`;

CREATE TABLE `location_shifts` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') CHARACTER SET latin1 DEFAULT NULL,
  `shift_name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `dow` text CHARACTER SET latin1,
  `reservation_allowed` enum('Yes','No') NOT NULL,
  `reservation_slots` varchar(2) DEFAULT NULL,
  `reservation_intervals` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `location_shifts_location_fk_idx` (`location_id`),
  CONSTRAINT `location_shifts_location_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Table structure for table `location_survey` */

DROP TABLE IF EXISTS `location_survey`;

CREATE TABLE `location_survey` (
  `survey_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `question_1` varchar(45) NOT NULL,
  `question_2` varchar(45) NOT NULL,
  `question_3` varchar(45) NOT NULL,
  `question_4` varchar(45) NOT NULL,
  `question_5` varchar(45) NOT NULL,
  `question_6` varchar(45) NOT NULL,
  `question_7` varchar(45) NOT NULL,
  `question_8` varchar(45) NOT NULL,
  `question_9` varchar(45) NOT NULL,
  `General` text NOT NULL,
  `created_by` text NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`survey_id`),
  KEY `location_survery_loc_idx` (`location_id`),
  CONSTRAINT `location_survery_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='SoftPoint survery results for a location';

/*Table structure for table `location_tables` */

DROP TABLE IF EXISTS `location_tables`;

CREATE TABLE `location_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `priority` int(8) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL,
  `minimum_covers` varchar(50) NOT NULL,
  `maximum_covers` varchar(50) NOT NULL,
  `tx` varchar(10) NOT NULL,
  `ty` varchar(10) NOT NULL,
  `size_x` varchar(60) NOT NULL,
  `size_y` varchar(60) NOT NULL,
  `image` varchar(10) NOT NULL,
  `angle` decimal(10,2) DEFAULT NULL,
  `visualstatus` varchar(60) NOT NULL DEFAULT 'Ready',
  `use_seat_table` enum('yes','no') NOT NULL,
  `serverstatus` varchar(60) NOT NULL DEFAULT 'Ready',
  `zone_id` int(8) DEFAULT NULL,
  `omnivore_table_id` int(8) DEFAULT NULL,
  `created_on` varchar(50) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_tables_fk` (`location_id`),
  KEY `location_tables_zone_idx` (`zone_id`),
  CONSTRAINT `location_tables_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_tables_zone` FOREIGN KEY (`zone_id`) REFERENCES `location_tables_zone` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1162 DEFAULT CHARSET=latin1 COMMENT='Table information for a configure location table';

/*Table structure for table `location_tables_zone` */

DROP TABLE IF EXISTS `location_tables_zone`;

CREATE TABLE `location_tables_zone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `terminal_id` int(8) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `code` varchar(6) NOT NULL,
  `description` varchar(60) NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` int(8) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_tables_zone_loc_fk` (`location_id`),
  KEY `location_tables_zone_terminal_fk` (`terminal_id`),
  KEY `location_tables_zone_emp_fk` (`employee_id`),
  CONSTRAINT `location_table_sone_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_tables_zone_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_tables_zone_terminal_fk` FOREIGN KEY (`terminal_id`) REFERENCES `location_terminals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1 COMMENT='Configured table zones for a location';

/*Table structure for table `location_terminals` */

DROP TABLE IF EXISTS `location_terminals`;

CREATE TABLE `location_terminals` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `softpoint_type` enum('Restaurant','Retail') DEFAULT 'Restaurant',
  `terminal_type` enum('Bar','POS','Register','Hotel','Other') NOT NULL DEFAULT 'POS',
  `terminal_name` varchar(250) NOT NULL,
  `menu_view` enum('List','Gallery','Slot 2','Slot 3','Slot 4') NOT NULL DEFAULT 'Gallery',
  `path` varchar(500) DEFAULT NULL,
  `physical_location_description` varchar(255) DEFAULT NULL,
  `cashier_bank` enum('yes','no') NOT NULL DEFAULT 'no',
  `card_reader` enum('yes','no') NOT NULL DEFAULT 'no',
  `rfid_reader` enum('yes','no') NOT NULL DEFAULT 'no',
  `section_id` int(11) DEFAULT NULL,
  `terminal_configured` enum('yes','no') NOT NULL DEFAULT 'no',
  `serial` varchar(200) NOT NULL,
  `ip_address` varchar(32) DEFAULT NULL,
  `kiosk_address` varchar(50) DEFAULT NULL,
  `device_id` varchar(32) DEFAULT NULL,
  `operating_system` enum('Windows 7','Windows 8','Windows 10','OS X','Android','Other') DEFAULT NULL,
  `geo_location` varchar(255) DEFAULT NULL,
  `default_printer_id` int(11) DEFAULT NULL,
  `language` int(11) DEFAULT NULL,
  `timeout_period` enum('Never','1 min','3 min','5 min','10 min') DEFAULT '3 min',
  `pos_fast_posting` enum('Yes','No') DEFAULT 'No',
  `pos_fast_posting_new_check` enum('Yes','No') DEFAULT 'No',
  `timeout_popup` enum('Yes','No') DEFAULT 'Yes',
  `refresh_time` int(11) DEFAULT '30',
  `app_printing` enum('USB','Network') DEFAULT 'USB',
  `app_lan_printing` enum('Yes','No') DEFAULT 'No',
  `quickpoint` enum('Yes','No') DEFAULT 'No',
  `quickpoint_term_1` varchar(64) DEFAULT NULL,
  `quickpoint_term_2` varchar(64) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_terminals_fk` (`location_id`),
  CONSTRAINT `location_terminals_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3800 DEFAULT CHARSET=latin1 COMMENT='Configured register terminals for a location';

/*Table structure for table `location_tickets` */

DROP TABLE IF EXISTS `location_tickets`;

CREATE TABLE `location_tickets` (
  `location_tickets_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `ticket_number` varchar(16) NOT NULL,
  `status` enum('Active','Used','Cancelled','Expired','Notused') NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) NOT NULL,
  `client_sales_items_id` int(11) NOT NULL,
  `location_menu_articles_id` int(8) NOT NULL,
  `price` decimal(14,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `expires` date DEFAULT NULL,
  `date_used` date DEFAULT NULL,
  `time_used` time DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_tickets_id`),
  UNIQUE KEY `location_tickets_id_UNIQUE` (`location_tickets_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7109 DEFAULT CHARSET=latin1 COMMENT='Store the tickets sold form a location';

/*Table structure for table `location_tipout` */

DROP TABLE IF EXISTS `location_tipout`;

CREATE TABLE `location_tipout` (
  `location_tipout_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `tipout_date` datetime NOT NULL,
  `am_bar_percent` decimal(13,2) NOT NULL,
  `am_host_percent` decimal(13,2) NOT NULL,
  `pm_bar_percent` decimal(13,2) NOT NULL,
  `pm_host_percent` decimal(13,2) NOT NULL,
  `status` varchar(45) NOT NULL DEFAULT 'active',
  `transfer_datetime` datetime DEFAULT NULL,
  `transfer_id` int(11) DEFAULT NULL,
  `transfer_manager` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_tipout_id`),
  UNIQUE KEY `location_tipout_id_UNIQUE` (`location_tipout_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

/*Table structure for table `location_tipout_employee` */

DROP TABLE IF EXISTS `location_tipout_employee`;

CREATE TABLE `location_tipout_employee` (
  `location_tipout_employee_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_tipout_id` int(8) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `employee_type` varchar(45) NOT NULL,
  `hours` int(11) DEFAULT NULL,
  `additional` decimal(13,2) DEFAULT NULL,
  `reason` varchar(256) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_tipout_employee_id`),
  UNIQUE KEY `location_tipout_employee_id_UNIQUE` (`location_tipout_employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;

/*Table structure for table `location_tipout_server` */

DROP TABLE IF EXISTS `location_tipout_server`;

CREATE TABLE `location_tipout_server` (
  `location_tipout_server_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_tipout_id` int(8) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `bar_name` varchar(45) DEFAULT NULL,
  `employee_type` varchar(45) NOT NULL,
  `subtotal` decimal(13,2) NOT NULL,
  `pol_5` decimal(13,2) NOT NULL,
  `run_5` decimal(13,2) NOT NULL,
  `cash_intake` decimal(13,2) NOT NULL,
  `adjustments` decimal(13,2) NOT NULL,
  `additional` decimal(13,2) DEFAULT NULL,
  `reason` varchar(256) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`location_tipout_server_id`),
  UNIQUE KEY `location_tipout_server_id_UNIQUE` (`location_tipout_server_id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=latin1;

/*Table structure for table `location_types` */

DROP TABLE IF EXISTS `location_types`;

CREATE TABLE `location_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `subtype` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subtype` (`subtype`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 COMMENT='Location types that can be used to categorize a location';

/*Table structure for table `locations` */

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `status` enum('active','inactive','pending','closed') NOT NULL COMMENT 'Active is Active locations to display, Inactive is when location requested to be removed from SP, Pending is there pending approval, and Closed is when a location has closed there business.',
  `sales_status` enum('Not Yet Contacted','Contacted','Emailed','Surveyed','Asleep','Interested','Proposal','Contract','Declined','Registered','Boarding','Integrated','On Hold - Do Not Bill','Lab','Installed','Suspended','Cancelled','Terminated By SoftPoint') NOT NULL DEFAULT 'Not Yet Contacted' COMMENT 'Registered: Location has registered for SoftPoint products but not installed.\nEmailed: Location has been emailed.\nContacted: Location has been contacted.\nNot Yet Contacted: \nLocation has not been contacted.\nSurveyed: Survey has been sent to location.\nDeclined: Location is not interested in SoftPoint.\nSuspended: SoftPoint has suspended usage of products.\n\nCancelled: Location has cancelled services with SoftPoint.\nProposal: SoftPoint has sent merchant proposal.\nContract: SoftPoint has sent merchant contract.\nInstalled: \nLocation is active and installed with progress.\nInterested: Location is interested and having dialog with SoftPoint.',
  `sales_status_date` date NOT NULL,
  `sales_user` int(8) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `business_retail_name` varchar(64) DEFAULT NULL,
  `business_rest_name` varchar(64) DEFAULT NULL,
  `business_hotel_name` varchar(64) DEFAULT NULL,
  `email` varchar(64) NOT NULL,
  `receipt_email` varchar(64) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `primary_type` int(11) NOT NULL,
  `address` varchar(64) NOT NULL,
  `address2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `state` int(4) NOT NULL,
  `zip` varchar(16) NOT NULL,
  `country` int(4) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `fax` varchar(32) NOT NULL,
  `website` tinytext NOT NULL,
  `notes` text NOT NULL,
  `image` longtext NOT NULL,
  `video` longtext,
  `longitude` varchar(12) NOT NULL,
  `latitude` varchar(12) NOT NULL,
  `rating` varchar(64) NOT NULL,
  `reviews` decimal(3,2) DEFAULT NULL,
  `average_price` varchar(16) NOT NULL,
  `cuisine` int(8) NOT NULL,
  `cuisine_details` varchar(255) NOT NULL,
  `reservation` enum('yes','no') NOT NULL DEFAULT 'no',
  `reservation_starttime` varchar(5) NOT NULL,
  `reservation_starttime_mode` text NOT NULL,
  `reservation_endtime` varchar(5) NOT NULL,
  `reservation_endtime_mode` text NOT NULL,
  `reservation_intervals` varchar(2) DEFAULT NULL,
  `reservation_slots` varchar(2) DEFAULT NULL,
  `reservation_minparty` int(2) DEFAULT '1',
  `reservation_maxparty` int(2) DEFAULT '20',
  `reservation_email_notification` enum('Yes','No') DEFAULT 'No',
  `reservation_group` enum('yes','no') DEFAULT 'yes',
  `togo` enum('yes','no') NOT NULL DEFAULT 'no',
  `togo_starttime` varchar(5) DEFAULT NULL,
  `togo_starttime_mode` text,
  `togo_endtime` varchar(5) DEFAULT NULL,
  `togo_endtime_mode` text,
  `togo_surcharge` decimal(10,2) DEFAULT NULL,
  `delivery` enum('yes','no') NOT NULL DEFAULT 'no',
  `delivery_starttime` varchar(5) DEFAULT NULL,
  `delivery_starttime_mode` text,
  `delivery_endtime` varchar(5) DEFAULT NULL,
  `delivery_endtime_mode` text,
  `delivery_dispatch_miles` decimal(10,2) DEFAULT '5.00',
  `delivery_surcharge` decimal(10,2) DEFAULT NULL,
  `hours` text NOT NULL,
  `open_mon` enum('yes','no') DEFAULT NULL,
  `open_mon_starttime` varchar(5) DEFAULT NULL,
  `open_mon_endtime` varchar(5) DEFAULT NULL,
  `open_tue` enum('yes','no') DEFAULT NULL,
  `open_tue_starttime` varchar(5) DEFAULT NULL,
  `open_tue_endtime` varchar(5) DEFAULT NULL,
  `open_wed` enum('yes','no') DEFAULT NULL,
  `open_wed_starttime` varchar(5) DEFAULT NULL,
  `open_wed_endtime` varchar(5) DEFAULT NULL,
  `open_thu` enum('yes','no') DEFAULT NULL,
  `open_thu_starttime` varchar(5) DEFAULT NULL,
  `open_thu_endtime` varchar(5) DEFAULT NULL,
  `open_fri` enum('yes','no') DEFAULT NULL,
  `open_fri_starttime` varchar(5) DEFAULT NULL,
  `open_fri_endtime` varchar(5) DEFAULT NULL,
  `open_sat` enum('yes','no') DEFAULT NULL,
  `open_sat_starttime` varchar(5) DEFAULT NULL,
  `open_sat_endtime` varchar(5) DEFAULT NULL,
  `open_sun` enum('yes','no') DEFAULT NULL,
  `open_sun_starttime` varchar(5) DEFAULT NULL,
  `open_sun_endtime` varchar(5) DEFAULT NULL,
  `representative` varchar(128) NOT NULL,
  `representative_title` varchar(128) NOT NULL,
  `communication_employee_id` int(8) DEFAULT NULL,
  `alcohol` varchar(128) NOT NULL,
  `bar` varchar(128) NOT NULL,
  `valet` varchar(128) NOT NULL,
  `pos_require_covers` enum('yes','no') DEFAULT NULL,
  `table_dinning` enum('yes','no') DEFAULT NULL,
  `dinning_style` varchar(128) NOT NULL,
  `neighborhoods` varchar(128) NOT NULL,
  `major_crosstreet` varchar(128) NOT NULL,
  `payment_options` varchar(128) NOT NULL,
  `dress_code` varchar(128) NOT NULL,
  `accept_walks_in` enum('yes','no') DEFAULT NULL,
  `aditional_details` text,
  `parking` enum('yes','no') DEFAULT NULL,
  `parking_details` varchar(128) NOT NULL,
  `entertainment` varchar(256) NOT NULL,
  `special_events` varchar(256) NOT NULL,
  `executive_chef` varchar(128) NOT NULL,
  `price_info` varchar(100) NOT NULL,
  `facebook` varchar(70) NOT NULL,
  `facebook_page` varchar(255) DEFAULT NULL,
  `twitter` varchar(70) NOT NULL,
  `twitter_access_token` varchar(255) DEFAULT NULL,
  `twitter_token_secret` varchar(255) DEFAULT NULL,
  `linkedin_token_secret` varchar(255) DEFAULT NULL,
  `linkedin_token` varchar(255) DEFAULT NULL,
  `edu2b` enum('yes','no') NOT NULL DEFAULT 'no',
  `image_big` varchar(255) DEFAULT NULL,
  `image_table` varchar(255) DEFAULT NULL,
  `image_edu2b` varchar(255) DEFAULT NULL,
  `image_hotel_layout` varchar(255) DEFAULT NULL,
  `digitalmenu_header` varchar(255) DEFAULT NULL,
  `maxcovers` int(11) NOT NULL,
  `tax_percentage` float NOT NULL,
  `membership` enum('yes','no') DEFAULT NULL,
  `default_gratuity` int(11) DEFAULT NULL,
  `location_expensetab_account` int(11) DEFAULT NULL,
  `allow_reviews` enum('yes','no') DEFAULT NULL,
  `pos_require_seat` enum('yes','no') DEFAULT NULL,
  `pos_fire_order` enum('yes','no') DEFAULT NULL,
  `messageprint` varchar(255) NOT NULL,
  `transportation` enum('yes','no') DEFAULT NULL,
  `access_websites` enum('yes','no') NOT NULL DEFAULT 'yes',
  `access_pos` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_handheld` enum('yes','no') NOT NULL DEFAULT 'no',
  `pos_menu_item_distribution_report_deduct_adjustment` enum('Yes','No') DEFAULT 'Yes',
  `Allow_server_add_global_modifier` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_server_add_global_modifier_posting` int(11) NOT NULL,
  `POS_autocharge_numofcovers` int(3) DEFAULT NULL,
  `POS_autocharge_pctorfix` enum('Fixed','Percentage') DEFAULT NULL,
  `POS_autocharge_amount` decimal(10,2) DEFAULT NULL,
  `POS_autocharge_paymentcode` int(3) DEFAULT NULL,
  `POS_menu_item_disctribution_report_display_details` enum('Yes','No') DEFAULT 'No',
  `pos_claim_tips` enum('yes','no') NOT NULL DEFAULT 'no',
  `app_printing` enum('USB','Network') DEFAULT 'USB',
  `require_break` enum('Yes','No') DEFAULT 'No',
  `togo_accept_cash` enum('no','yes') DEFAULT 'yes',
  `delivery_accept_cash` enum('no','yes') DEFAULT 'yes',
  `gratuity_calculator` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_digitalmenu` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_barpoint` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_winepad` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_visualprep` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_tablereview` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_register` enum('yes','no') NOT NULL DEFAULT 'no',
  `register_services` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_register_order` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_retail_fast_posting` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_retail_fast_posting_new_check` enum('Yes','No') DEFAULT 'No',
  `access_hotel` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_disptach` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_room_service` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_crm` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_crs` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_concierge` enum('yes','no') DEFAULT 'no',
  `access_advertisement` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_tablepoint` enum('Yes','No') DEFAULT 'No',
  `access_quality` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_timeattendance` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_timeattendance_require_break` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_reservation` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_backoffice` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_inventory` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_minibar` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_meetingroom` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_training` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_expensetab` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_staffpoint` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_petty_cash` enum('yes','no') NOT NULL DEFAULT 'no',
  `access_stylistfn` enum('Yes','No') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `access_chefedin` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No',
  `access_chef` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_spa` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_datapoint` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_datapoint_devices` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_lounges` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_business_intelligence` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_manual_payment` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_storepoint` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_ticketing` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_controlpoint` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_kioskpoint` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_olopoint` enum('Yes','No') NOT NULL DEFAULT 'No',
  `use_availability` enum('Yes','No') DEFAULT 'No',
  `single_platform_id` varchar(100) DEFAULT NULL COMMENT 'Default value is null.\n\nIf value == 0, phone is in an incorrect format. It must be xxx-xxx-xxxx\n\nIf value == 1, phone was in correct format but did not get any results.',
  `payroll_processor` enum('ADP','AmCheck') DEFAULT NULL,
  `Payroll_period` enum('Daily','Weekly','Bi-Weekly','Monthly','Semi-Monthly','Quarterly','Semi-Annually','Annually','Miscellaneous') DEFAULT NULL,
  `payroll_time_type` enum('Actual','Round To Nearest 05','Round To Nearest 10','Round To Nearest 15','Round To Nearest 20','Round To Nearest 30') DEFAULT NULL,
  `payroll_overtime_based_on` enum('Daily','Weekly') DEFAULT 'Daily',
  `payroll_overtime_after_how_many_hours` decimal(2,0) DEFAULT '8',
  `payroll_overtime_rate` decimal(3,2) DEFAULT '1.50',
  `payroll_overtime2_after_how_many_hours` decimal(2,0) DEFAULT '12',
  `payroll_overtime2_rate` decimal(3,2) DEFAULT '2.00',
  `payroll_start_of_week_day` enum('Mon','Wed','Tue','Thu','Fri','Sat','Sun') DEFAULT NULL,
  `payroll_start_date` date DEFAULT NULL,
  `holiday_probation` varchar(3) DEFAULT NULL,
  `vacation_accrual` enum('use_employee','use_formula') DEFAULT 'use_employee',
  `length_of_service_full_time` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_full_time` varchar(10) DEFAULT NULL,
  `vacation_hours_earned_per_year_full_time` varchar(10) DEFAULT NULL,
  `max_hours_earned_full_time` varchar(10) DEFAULT NULL,
  `length_of_service_full_time2` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_full_time2` varchar(10) DEFAULT NULL,
  `vacation_hrs_earned_per_pay_period_full_time2` varchar(10) DEFAULT NULL,
  `max_hours_earned_full_time2` varchar(10) DEFAULT NULL,
  `length_of_service_full_time3` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_full_time3` varchar(10) DEFAULT NULL,
  `vacation_hrs_earned_per_pay_period_full_time3` varchar(10) DEFAULT NULL,
  `max_hours_earned_full_time3` varchar(10) DEFAULT NULL,
  `length_of_service_full_time4` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_full_time4` varchar(10) DEFAULT NULL,
  `vacation_hrs_earned_per_pay_period_full_time4` varchar(10) DEFAULT NULL,
  `max_hours_earned_full_time4` varchar(10) DEFAULT NULL,
  `length_of_service_part_time` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_part_time` varchar(10) DEFAULT NULL,
  `vacation_hours_earned_per_year_part_time` varchar(10) DEFAULT NULL,
  `max_hours_earned_part_time` varchar(10) DEFAULT NULL,
  `length_of_service_part_time2` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_part_time2` varchar(10) DEFAULT NULL,
  `vacation_hrs_earned_per_pay_period_part_time2` varchar(10) DEFAULT NULL,
  `max_hours_earned_part_time2` varchar(10) DEFAULT NULL,
  `length_of_service_part_time3` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_part_time3` varchar(10) DEFAULT NULL,
  `vacation_hrs_earned_per_pay_period_part_time3` varchar(10) DEFAULT NULL,
  `max_hours_earned_part_time3` varchar(10) DEFAULT NULL,
  `length_of_service_part_time4` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_part_time4` varchar(10) DEFAULT NULL,
  `vacation_hrs_earned_per_pay_period_part_time4` varchar(10) DEFAULT NULL,
  `max_hours_earned_part_time4` varchar(10) DEFAULT NULL,
  `Bereavement` enum('use_sick_time','no_sick_time') DEFAULT 'use_sick_time',
  `GMT` varchar(6) NOT NULL DEFAULT '-7',
  `ein_vat` varchar(16) DEFAULT NULL,
  `old_id` int(8) DEFAULT NULL,
  `newold` int(11) DEFAULT NULL,
  `dup` varchar(45) DEFAULT NULL,
  `currency_id` int(8) DEFAULT NULL,
  `currency` varchar(32) DEFAULT 'USD',
  `refund_period` int(20) DEFAULT NULL,
  `currency_symbol` varchar(3) CHARACTER SET utf8 DEFAULT NULL,
  `refund_policy` varchar(255) DEFAULT NULL,
  `Manager_req_adjustment` enum('yes','no') DEFAULT 'yes',
  `Manager_req_discount` enum('yes','no') DEFAULT 'yes',
  `hp_req_admin_page` enum('Yes','No') DEFAULT 'No',
  `early_arrival_charge` enum('Full Day','Half Day','No Charge') DEFAULT 'No Charge',
  `new_client_req_email` enum('Yes','No') DEFAULT 'No',
  `new_client_req_address` enum('Yes','No') DEFAULT 'No',
  `new_client_req_phone` enum('Yes','No') DEFAULT 'No',
  `hotel_commercial_description` varchar(128) DEFAULT NULL,
  `hotel_checkin_time` time DEFAULT NULL,
  `hotel_checkout_time` time DEFAULT NULL,
  `hotel_stars` varchar(1) DEFAULT NULL,
  `hotel_chain_franchise_info` int(11) DEFAULT NULL,
  `hotel_flagship` varchar(64) DEFAULT NULL,
  `hotel_affiliations` text,
  `hotel_location_description` tinytext,
  `hotel_features_description` tinytext,
  `hotel_features` text,
  `hotel_amenities` text,
  `hotel_policies` text,
  `hotel_family_amenities` text,
  `hotel_room_count` int(4) DEFAULT NULL,
  `hotel_room_description` tinytext,
  `hotel_room_amenities` text,
  `hotel_accessibilities` text,
  `hotel_dining_recommendations` text,
  `hotel_nearby_things` text,
  `hotel_need_to_know` text,
  `hotel_fees` text,
  `hotel_special_mentions` text,
  `hotel_languages_spoken` text,
  `default_rate_type` int(11) DEFAULT NULL,
  `hp_req_admin_reversal` enum('Yes','No') DEFAULT 'No',
  `hp_req_admin_image` enum('Yes','No') DEFAULT 'No',
  `hotel_update_start` datetime DEFAULT NULL,
  `hotel_update_finish` datetime DEFAULT NULL,
  `spa_open_time` time DEFAULT NULL,
  `spa_close_time` time DEFAULT NULL,
  `spa_dow` text,
  `POS_print_zero_cash_checks` enum('Yes','No') NOT NULL DEFAULT 'No',
  `take_image_when_cash_payment` enum('Yes','No') DEFAULT 'No',
  `lasttime_printer` datetime DEFAULT NULL,
  `serial_last_printer` varchar(45) DEFAULT NULL,
  `primary_printer_language` int(8) DEFAULT NULL,
  `facebook_status` varchar(50) DEFAULT NULL,
  `facebook_token` varchar(255) DEFAULT NULL,
  `facebook_extend_token` varchar(20) DEFAULT NULL,
  `linkedin_status` varchar(50) DEFAULT NULL,
  `twitter_status` varchar(50) DEFAULT NULL,
  `instagram_status` varchar(50) DEFAULT NULL,
  `yelp_link` varchar(255) DEFAULT NULL,
  `google_status` varchar(50) DEFAULT NULL,
  `google_access_token` varchar(2000) DEFAULT NULL,
  `google_refresh_token` varchar(255) DEFAULT NULL,
  `tripadvisor_link` varchar(255) DEFAULT NULL,
  `pinterest_username` varchar(255) DEFAULT NULL,
  `instagram_token` varchar(255) DEFAULT NULL,
  `youtube_status` varchar(50) DEFAULT NULL,
  `business_retail_description` text,
  `business_rest_description` text,
  `business_hotel_description` text,
  `omnivore_status` enum('Active','Inactive','Not Used') DEFAULT 'Not Used',
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `ethor_status` enum('Active','Inactive','Not Used') DEFAULT 'Not Used',
  `ethor_store_id` varchar(45) DEFAULT NULL,
  `aireus_status` enum('Active','Inactive','Not Used') DEFAULT 'Not Used',
  `aireus_store_id` varchar(45) DEFAULT NULL,
  `aireus_api_key` varchar(255) DEFAULT NULL,
  `focus_status` enum('Active','Inactive','Not Used') DEFAULT 'Not Used',
  `posera_status` enum('Active','Inactive','Not Used') DEFAULT 'Not Used',
  `print_technician_copy` enum('Yes','No') DEFAULT 'No',
  `current_cc_processer` enum('Chase Paymentech','Elavon','First Data','Global Payments','Heartland','NAB','TransFirst','TSYS','Vantiv','WorldPay') DEFAULT NULL,
  `integrated_cc_processing` enum('Yes','No') DEFAULT 'No',
  `pos_pms_system` enum('Yes','No') DEFAULT 'No',
  `current_pos_pms_system` varchar(100) DEFAULT NULL,
  `pos_include_tax` enum('Yes','No') DEFAULT 'Yes',
  `pos_version_number` varchar(45) DEFAULT NULL,
  `register_receipt_options` enum('Yes','No') DEFAULT 'No',
  `surcharge_tax_display` enum('Yes','No') DEFAULT 'No',
  `internal_store_number` varchar(255) DEFAULT NULL,
  `kiosk_emp_id` int(11) DEFAULT NULL,
  `olo_emp_id` int(11) DEFAULT NULL,
  `kiosk_item` int(11) DEFAULT NULL,
  `olo_item` int(11) DEFAULT NULL,
  `kiosk_revenue_center_id` int(11) DEFAULT NULL,
  `kiosk_order_type_id` int(11) DEFAULT NULL,
  `kiosk_table_id` int(11) DEFAULT NULL,
  `kiosk_close_ticket` enum('Yes','No') DEFAULT 'Yes',
  `kiosk_take_order_number` enum('Yes','No') DEFAULT 'No',
  `kiosk_take_order_number_required` enum('Yes','No') DEFAULT 'No',
  `kiosk_discount_type` enum('No','Discount','Generic Tender') NOT NULL DEFAULT 'No',
  `olo_online_ordering_status` enum('Online','Offline') DEFAULT 'Offline',
  `olo_minimum_prep_time` varchar(64) DEFAULT NULL,
  `olo_display_nested_mod` enum('Yes','No') DEFAULT 'Yes',
  `disclaimer_olo` text,
  `integration_license` varchar(255) DEFAULT NULL,
  `operating_system` enum('Windows 10','Windows Server 2016','Windows 8.1','Windows Server 2012 R2','Windows Server 2012','Windows 8','Windows Server 2008 R2','Windows 7','Windows Server 2008 - DO NOT SUPPORT','Windows Vista - DO NOT SUPPORT','Windows Server 2003 R2 - DO NOT SUPPORT','Windows Server 2003 - DO NOT SUPPORT','Windows XP - DO NOT SUPPORT','OS X','Android','Other') DEFAULT NULL,
  `foh_operating_system` enum('Windows 10','Windows Server 2016','Windows 8.1','Windows Server 2012 R2','Windows Server 2012','Windows 8','Windows Server 2008 R2','Windows 7','Windows Server 2008 - DO NOT SUPPORT','Windows Vista - DO NOT SUPPORT','Windows Server 2003 R2 - DO NOT SUPPORT','Windows Server 2003 - DO NOT SUPPORT','Windows XP - DO NOT SUPPORT','OS X','Android','Other') DEFAULT NULL,
  `olo_take_cash` enum('Yes','No') DEFAULT 'No',
  `remote_access_type` enum('Aloha Command Center','LogMeln','TeamViewer') DEFAULT NULL,
  `remote_access_id` varchar(45) DEFAULT NULL,
  `remote_access_pin` varchar(45) DEFAULT NULL,
  `abi` enum('Yes','No') DEFAULT 'No',
  `abi_run_cron` enum('Yes','No') DEFAULT 'No',
  `abi_last_date` date DEFAULT NULL,
  `createdon` varchar(45) NOT NULL,
  `date_added` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_mobile` varchar(255) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `name` (`name`),
  KEY `city` (`city`(8)),
  KEY `primary_type` (`primary_type`),
  KEY `status` (`status`),
  KEY `longitude` (`longitude`),
  KEY `latitude` (`latitude`),
  KEY `cuisine` (`cuisine`),
  KEY `locations_currency_idx` (`currency_id`),
  KEY `Created_by_idk` (`created_by`),
  KEY `omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_location_status_idk` (`omnivore_status`),
  KEY `locations_state_idk` (`state`),
  KEY `locations_country_idk` (`country`),
  KEY `locations_comm_emp_idx` (`communication_employee_id`),
  KEY `locations_expensetab_account_idx` (`location_expensetab_account`),
  KEY `image` (`image`(250)),
  KEY `sales_status_idx` (`sales_status`),
  KEY `abi_idx` (`abi`),
  CONSTRAINT `locations_comm_emp` FOREIGN KEY (`communication_employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `locations_currency` FOREIGN KEY (`currency_id`) REFERENCES `global_currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `locations_expensetab_account` FOREIGN KEY (`location_expensetab_account`) REFERENCES `location_expensetab_accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `locations_primary_type` FOREIGN KEY (`primary_type`) REFERENCES `location_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=274688 DEFAULT CHARSET=latin1 COMMENT='General information about a location';

/*Table structure for table `locations_audit` */

DROP TABLE IF EXISTS `locations_audit`;

CREATE TABLE `locations_audit` (
  `locations_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(8) DEFAULT NULL,
  `status` enum('active','inactive','pending','closed') DEFAULT NULL,
  `sales_status` enum('Not Yet Contacted','Contacted','Emailed','Surveyed','Asleep','Interested','Proposal','Contract','Declined','Registered','Boarding','Integrated','On Hold - Do Not Bill','Lab','Installed','Suspended','Cancelled','Terminated By SoftPoint') DEFAULT NULL,
  `sales_status_date` date DEFAULT NULL,
  `sales_user` int(8) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `business_retail_name` varchar(64) DEFAULT NULL,
  `business_rest_name` varchar(64) DEFAULT NULL,
  `business_hotel_name` varchar(64) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `receipt_email` varchar(64) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `primary_type` int(11) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `address2` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) DEFAULT NULL,
  `country` int(4) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `fax` varchar(32) DEFAULT NULL,
  `website` tinytext,
  `notes` text,
  `image` longtext,
  `video` longtext,
  `longitude` varchar(12) DEFAULT NULL,
  `latitude` varchar(12) DEFAULT NULL,
  `rating` varchar(64) DEFAULT NULL,
  `reviews` decimal(3,2) DEFAULT NULL,
  `average_price` varchar(16) DEFAULT NULL,
  `cuisine` int(8) DEFAULT NULL,
  `cuisine_details` varchar(255) DEFAULT NULL,
  `reservation` enum('yes','no') DEFAULT NULL,
  `reservation_starttime` varchar(5) DEFAULT NULL,
  `reservation_starttime_mode` text,
  `reservation_endtime` varchar(5) DEFAULT NULL,
  `reservation_endtime_mode` text,
  `reservation_intervals` varchar(2) DEFAULT NULL,
  `reservation_slots` varchar(2) DEFAULT NULL,
  `reservation_minparty` int(2) DEFAULT NULL,
  `reservation_maxparty` int(2) DEFAULT NULL,
  `reservation_email_notification` enum('Yes','No') DEFAULT 'No',
  `reservation_group` enum('yes','no') DEFAULT NULL,
  `togo` enum('yes','no') DEFAULT NULL,
  `togo_starttime` varchar(5) DEFAULT NULL,
  `togo_starttime_mode` text,
  `togo_endtime` varchar(5) DEFAULT NULL,
  `togo_endtime_mode` text,
  `togo_surcharge` decimal(10,2) DEFAULT NULL,
  `delivery` enum('yes','no') DEFAULT NULL,
  `delivery_starttime` varchar(5) DEFAULT NULL,
  `delivery_starttime_mode` text,
  `delivery_endtime` varchar(5) DEFAULT NULL,
  `delivery_endtime_mode` text,
  `delivery_surcharge` decimal(10,2) DEFAULT NULL,
  `hours` text,
  `open_mon` enum('yes','no') DEFAULT NULL,
  `open_mon_starttime` varchar(5) DEFAULT NULL,
  `open_mon_endtime` varchar(5) DEFAULT NULL,
  `open_tue` enum('yes','no') DEFAULT NULL,
  `open_tue_starttime` varchar(5) DEFAULT NULL,
  `open_tue_endtime` varchar(5) DEFAULT NULL,
  `open_wed` enum('yes','no') DEFAULT NULL,
  `open_wed_starttime` varchar(5) DEFAULT NULL,
  `open_wed_endtime` varchar(5) DEFAULT NULL,
  `open_thu` enum('yes','no') DEFAULT NULL,
  `open_thu_starttime` varchar(5) DEFAULT NULL,
  `open_thu_endtime` varchar(5) DEFAULT NULL,
  `open_fri` enum('yes','no') DEFAULT NULL,
  `open_fri_starttime` varchar(5) DEFAULT NULL,
  `open_fri_endtime` varchar(5) DEFAULT NULL,
  `open_sat` enum('yes','no') DEFAULT NULL,
  `open_sat_starttime` varchar(5) DEFAULT NULL,
  `open_sat_endtime` varchar(5) DEFAULT NULL,
  `open_sun` enum('yes','no') DEFAULT NULL,
  `open_sun_starttime` varchar(5) DEFAULT NULL,
  `open_sun_endtime` varchar(5) DEFAULT NULL,
  `representative` varchar(128) DEFAULT NULL,
  `representative_title` varchar(128) DEFAULT NULL,
  `communication_employee_id` int(8) DEFAULT NULL,
  `alcohol` varchar(128) DEFAULT NULL,
  `bar` varchar(128) DEFAULT NULL,
  `valet` varchar(128) DEFAULT NULL,
  `pos_require_covers` enum('yes','no') DEFAULT NULL,
  `table_dinning` enum('yes','no') DEFAULT NULL,
  `dinning_style` varchar(128) DEFAULT NULL,
  `neighborhoods` varchar(128) DEFAULT NULL,
  `major_crosstreet` varchar(128) DEFAULT NULL,
  `payment_options` varchar(128) DEFAULT NULL,
  `dress_code` varchar(128) DEFAULT NULL,
  `accept_walks_in` enum('yes','no') DEFAULT NULL,
  `aditional_details` text,
  `parking` enum('yes','no') DEFAULT NULL,
  `parking_details` varchar(128) DEFAULT NULL,
  `entertainment` varchar(256) DEFAULT NULL,
  `special_events` varchar(256) DEFAULT NULL,
  `executive_chef` varchar(128) DEFAULT NULL,
  `price_info` varchar(100) DEFAULT NULL,
  `facebook` varchar(70) DEFAULT NULL,
  `facebook_page` varchar(255) DEFAULT NULL,
  `twitter` varchar(70) DEFAULT NULL,
  `twitter_access_token` varchar(255) DEFAULT NULL,
  `twitter_token_secret` varchar(255) DEFAULT NULL,
  `linkedin_token_secret` varchar(255) DEFAULT NULL,
  `linkedin_token` varchar(255) DEFAULT NULL,
  `edu2b` enum('yes','no') DEFAULT NULL,
  `image_big` varchar(255) DEFAULT NULL,
  `image_table` varchar(255) DEFAULT NULL,
  `image_edu2b` varchar(255) DEFAULT NULL,
  `image_hotel_layout` varchar(255) DEFAULT NULL,
  `digitalmenu_header` varchar(255) DEFAULT NULL,
  `maxcovers` int(11) DEFAULT NULL,
  `tax_percentage` float DEFAULT NULL,
  `membership` enum('yes','no') DEFAULT NULL,
  `default_gratuity` int(11) DEFAULT NULL,
  `location_expensetab_account` int(11) DEFAULT NULL,
  `allow_reviews` enum('yes','no') DEFAULT NULL,
  `pos_require_seat` enum('yes','no') DEFAULT NULL,
  `pos_fire_order` enum('yes','no') DEFAULT NULL,
  `messageprint` varchar(255) DEFAULT NULL,
  `transportation` enum('yes','no') DEFAULT NULL,
  `access_websites` enum('yes','no') DEFAULT NULL,
  `access_pos` enum('yes','no') DEFAULT NULL,
  `access_handheld` enum('yes','no') DEFAULT NULL,
  `pos_menu_item_distribution_report_deduct_adjustment` enum('Yes','No') DEFAULT 'Yes',
  `Allow_server_add_global_modifier` enum('yes','no') DEFAULT NULL,
  `allow_server_add_global_modifier_posting` int(11) DEFAULT NULL,
  `POS_autocharge_numofcovers` int(3) DEFAULT NULL,
  `POS_autocharge_pctorfix` enum('Fixed','Percentage') DEFAULT NULL,
  `POS_autocharge_amount` decimal(10,2) DEFAULT NULL,
  `POS_autocharge_paymentcode` int(3) DEFAULT NULL,
  `POS_menu_item_disctribution_report_display_details` enum('Yes','No') DEFAULT 'No',
  `pos_claim_tips` enum('yes','no') DEFAULT 'no',
  `app_printing` enum('USB','Network') DEFAULT 'USB',
  `require_break` enum('Yes','No') DEFAULT NULL,
  `togo_accept_cash` enum('no','yes') DEFAULT NULL,
  `delivery_accept_cash` enum('no','yes') DEFAULT NULL,
  `gratuity_calculator` enum('yes','no') DEFAULT NULL,
  `access_digitalmenu` enum('yes','no') DEFAULT NULL,
  `access_barpoint` enum('yes','no') DEFAULT NULL,
  `access_winepad` enum('yes','no') DEFAULT NULL,
  `access_visualprep` enum('yes','no') DEFAULT NULL,
  `access_tablereview` enum('yes','no') DEFAULT NULL,
  `access_register` enum('yes','no') DEFAULT NULL,
  `register_services` enum('Yes','No') DEFAULT NULL,
  `access_register_order` enum('Yes','No') DEFAULT NULL,
  `access_retail_fast_posting` enum('Yes','No') DEFAULT NULL,
  `access_retail_fast_posting_new_check` enum('Yes','No') DEFAULT NULL,
  `access_hotel` enum('yes','no') DEFAULT NULL,
  `access_disptach` enum('yes','no') DEFAULT NULL,
  `access_room_service` enum('yes','no') DEFAULT NULL,
  `access_crm` enum('yes','no') DEFAULT NULL,
  `access_crs` enum('yes','no') DEFAULT NULL,
  `access_concierge` enum('yes','no') DEFAULT NULL,
  `access_advertisement` enum('yes','no') DEFAULT NULL,
  `access_tablepoint` enum('Yes','No') DEFAULT NULL,
  `access_quality` enum('yes','no') DEFAULT NULL,
  `access_timeattendance` enum('yes','no') DEFAULT NULL,
  `access_timeattendance_require_break` enum('Yes','No') DEFAULT NULL,
  `access_reservation` enum('yes','no') DEFAULT NULL,
  `access_backoffice` enum('yes','no') DEFAULT NULL,
  `access_inventory` enum('yes','no') DEFAULT NULL,
  `access_minibar` enum('yes','no') DEFAULT NULL,
  `access_meetingroom` enum('yes','no') DEFAULT NULL,
  `access_training` enum('yes','no') DEFAULT NULL,
  `access_expensetab` enum('yes','no') DEFAULT NULL,
  `access_staffpoint` enum('Yes','No') DEFAULT NULL,
  `access_petty_cash` enum('yes','no') DEFAULT NULL,
  `access_stylistfn` enum('Yes','No') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `access_chefedin` enum('Yes','No') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `access_chef` enum('Yes','No') DEFAULT NULL,
  `access_spa` enum('Yes','No') DEFAULT NULL,
  `access_datapoint` enum('Yes','No') DEFAULT NULL,
  `access_datapoint_devices` enum('Yes','No') DEFAULT NULL,
  `access_lounges` enum('Yes','No') DEFAULT NULL,
  `access_business_intelligence` enum('Yes','No') DEFAULT NULL,
  `access_storepoint` enum('Yes','No') DEFAULT NULL,
  `access_ticketing` enum('Yes','No') DEFAULT NULL,
  `access_controlpoint` enum('Yes','No') DEFAULT NULL,
  `access_kioskpoint` enum('Yes','No') DEFAULT 'No',
  `access_olopoint` enum('Yes','No') DEFAULT NULL,
  `use_availability` enum('Yes','No') DEFAULT 'No',
  `single_platform_id` varchar(100) DEFAULT NULL,
  `Payroll_period` enum('Daily','Weekly','Bi-Weekly','Monthly','Semi-Monthly','Quarterly','Semi-Annually','Annually','Miscellaneous') DEFAULT NULL,
  `payroll_time_type` enum('Actual','Round To Nearest 05','Round To Nearest 10','Round To Nearest 15','Round To Nearest 20','Round To Nearest 30') DEFAULT NULL,
  `payroll_overtime_based_on` enum('Daily','Weekly') DEFAULT NULL,
  `payroll_overtime_after_how_many_hours` decimal(2,0) DEFAULT NULL,
  `payroll_overtime_rate` decimal(3,2) DEFAULT NULL,
  `payroll_overtime2_after_how_many_hours` decimal(2,0) DEFAULT NULL,
  `payroll_overtime2_rate` decimal(3,2) DEFAULT NULL,
  `payroll_start_of_week_day` enum('Mon','Wed','Tue','Thu','Fri','Sat','Sun') DEFAULT NULL,
  `payroll_start_date` date DEFAULT NULL,
  `vacation_accrual` enum('use_employee','use_formula') DEFAULT NULL,
  `length_of_service_full_time` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_full_time` varchar(10) DEFAULT NULL,
  `vacation_hours_earned_per_year_full_time` varchar(10) DEFAULT NULL,
  `max_hours_earned_full_time` varchar(10) DEFAULT NULL,
  `length_of_service_full_time2` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_full_time2` varchar(10) DEFAULT NULL,
  `vacation_hrs_earned_per_pay_period_full_time2` varchar(10) DEFAULT NULL,
  `max_hours_earned_full_time2` varchar(10) DEFAULT NULL,
  `length_of_service_full_time3` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_full_time3` varchar(10) DEFAULT NULL,
  `vacation_hrs_earned_per_pay_period_full_time3` varchar(10) DEFAULT NULL,
  `max_hours_earned_full_time3` varchar(10) DEFAULT NULL,
  `length_of_service_full_time4` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_full_time4` varchar(10) DEFAULT NULL,
  `vacation_hrs_earned_per_pay_period_full_time4` varchar(10) DEFAULT NULL,
  `max_hours_earned_full_time4` varchar(10) DEFAULT NULL,
  `length_of_service_part_time` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_part_time` varchar(10) DEFAULT NULL,
  `vacation_hours_earned_per_year_part_time` varchar(10) DEFAULT NULL,
  `max_hours_earned_part_time` varchar(10) DEFAULT NULL,
  `length_of_service_part_time2` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_part_time2` varchar(10) DEFAULT NULL,
  `vacation_hrs_earned_per_pay_period_part_time2` varchar(10) DEFAULT NULL,
  `max_hours_earned_part_time2` varchar(10) DEFAULT NULL,
  `length_of_service_part_time3` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_part_time3` varchar(10) DEFAULT NULL,
  `vacation_hrs_earned_per_pay_period_part_time3` varchar(10) DEFAULT NULL,
  `max_hours_earned_part_time3` varchar(10) DEFAULT NULL,
  `length_of_service_part_time4` varchar(10) DEFAULT NULL,
  `weeks_earned_per_year_part_time4` varchar(10) DEFAULT NULL,
  `vacation_hrs_earned_per_pay_period_part_time4` varchar(10) DEFAULT NULL,
  `max_hours_earned_part_time4` varchar(10) DEFAULT NULL,
  `Bereavement` enum('use_sick_time','no_sick_time') DEFAULT NULL,
  `GMT` varchar(6) DEFAULT NULL,
  `ein_vat` varchar(16) DEFAULT NULL,
  `old_id` int(8) DEFAULT NULL,
  `newold` int(11) DEFAULT NULL,
  `dup` varchar(45) DEFAULT NULL,
  `currency_id` int(8) DEFAULT NULL,
  `currency` varchar(32) DEFAULT NULL,
  `currency_symbol` varchar(3) DEFAULT NULL,
  `refund_period` int(20) DEFAULT NULL,
  `refund_policy` varchar(255) DEFAULT NULL,
  `Manager_req_adjustment` enum('yes','no') DEFAULT NULL,
  `Manager_req_discount` enum('yes','no') DEFAULT NULL,
  `hp_req_admin_page` enum('Yes','No') DEFAULT NULL,
  `early_arrival_charge` enum('Full Day','Half Day','No Charge') DEFAULT NULL,
  `new_client_req_email` enum('Yes','No') DEFAULT NULL,
  `new_client_req_address` enum('Yes','No') DEFAULT NULL,
  `new_client_req_phone` enum('Yes','No') DEFAULT NULL,
  `hotel_commercial_description` varchar(128) DEFAULT NULL,
  `hotel_checkin_time` time DEFAULT NULL,
  `hotel_checkout_time` time DEFAULT NULL,
  `hotel_stars` varchar(1) DEFAULT NULL,
  `hotel_chain_franchise_info` int(11) DEFAULT NULL,
  `hotel_flagship` varchar(64) DEFAULT NULL,
  `hotel_affiliations` text,
  `hotel_location_description` tinytext,
  `hotel_features_description` tinytext,
  `hotel_features` text,
  `hotel_amenities` text,
  `hotel_policies` text,
  `hotel_family_amenities` text,
  `hotel_room_count` int(4) DEFAULT NULL,
  `hotel_room_description` tinytext,
  `hotel_room_amenities` text,
  `hotel_accessibilities` text,
  `hotel_dining_recommendations` text,
  `hotel_nearby_things` text,
  `hotel_need_to_know` text,
  `hotel_fees` text,
  `hotel_special_mentions` text,
  `hotel_languages_spoken` text,
  `default_rate_type` int(11) DEFAULT NULL,
  `hp_req_admin_reversal` enum('Yes','No') DEFAULT NULL,
  `hp_req_admin_image` enum('Yes','No') DEFAULT NULL,
  `hotel_update_start` datetime DEFAULT NULL,
  `hotel_update_finish` datetime DEFAULT NULL,
  `spa_open_time` time DEFAULT NULL,
  `spa_close_time` time DEFAULT NULL,
  `spa_dow` text,
  `POS_print_zero_cash_checks` enum('Yes','No') DEFAULT NULL,
  `take_image_when_cash_payment` enum('Yes','No') DEFAULT NULL,
  `lasttime_printer` datetime DEFAULT NULL,
  `serial_last_printer` varchar(45) DEFAULT NULL,
  `primary_printer_language` int(8) DEFAULT NULL,
  `facebook_status` varchar(50) DEFAULT NULL,
  `facebook_token` varchar(255) DEFAULT NULL,
  `facebook_extend_token` varchar(20) DEFAULT NULL,
  `linkedin_status` varchar(50) DEFAULT NULL,
  `twitter_status` varchar(50) DEFAULT NULL,
  `instagram_status` varchar(50) DEFAULT NULL,
  `yelp_link` varchar(255) DEFAULT NULL,
  `google_status` varchar(50) DEFAULT NULL,
  `google_access_token` varchar(2000) DEFAULT NULL,
  `google_refresh_token` varchar(255) DEFAULT NULL,
  `tripadvisor_link` varchar(255) DEFAULT NULL,
  `pinterest_username` varchar(255) DEFAULT NULL,
  `instagram_token` varchar(255) DEFAULT NULL,
  `youtube_status` varchar(50) DEFAULT NULL,
  `business_retail_description` text,
  `business_rest_description` text,
  `business_hotel_description` text,
  `omnivore_status` enum('Active','Inactive','Not Used') DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `ethor_status` enum('Active','Inactive','Not Used') DEFAULT NULL,
  `ethor_store_id` varchar(45) DEFAULT NULL,
  `aireus_status` enum('Active','Inactive','Not Used') DEFAULT NULL,
  `aireus_store_id` varchar(45) DEFAULT NULL,
  `aireus_api_key` varchar(255) DEFAULT NULL,
  `focus_status` enum('Active','Inactive','Not Used') DEFAULT NULL,
  `posera_status` enum('Active','Inactive','Not Used') DEFAULT NULL,
  `print_technician_copy` enum('Yes','No') DEFAULT NULL,
  `current_cc_processer` enum('Chase Paymentech','Elavon','First Data','Global Payments','Heartland','TransFirst','TSYS','Vantiv','WorldPay') DEFAULT NULL,
  `integrated_cc_processing` enum('Yes','No') DEFAULT NULL,
  `pos_pms_system` enum('Yes','No') DEFAULT NULL,
  `current_pos_pms_system` varchar(100) DEFAULT NULL,
  `pos_include_tax` enum('Yes','No') DEFAULT NULL,
  `pos_version_number` varchar(45) DEFAULT NULL,
  `register_receipt_options` enum('Yes','No') DEFAULT NULL,
  `surcharge_tax_display` enum('Yes','No') DEFAULT NULL,
  `internal_store_number` varchar(255) DEFAULT NULL,
  `kiosk_emp_id` int(11) DEFAULT NULL,
  `olo_emp_id` int(11) DEFAULT NULL,
  `kiosk_item` int(11) DEFAULT NULL,
  `olo_item` int(11) DEFAULT NULL,
  `kiosk_revenue_center_id` int(11) DEFAULT NULL,
  `kiosk_order_type_id` int(11) DEFAULT NULL,
  `kiosk_table_id` int(11) DEFAULT NULL,
  `kiosk_close_ticket` enum('Yes','No') DEFAULT NULL,
  `kiosk_take_order_number` enum('Yes','No') DEFAULT NULL,
  `kiosk_take_order_number_required` enum('Yes','No') DEFAULT NULL,
  `kiosk_discount_type` enum('No','Discount','Generic Tender') DEFAULT NULL,
  `olo_online_ordering_status` enum('Online','Offline') DEFAULT NULL,
  `olo_minimum_prep_time` varchar(64) DEFAULT NULL,
  `olo_display_nested_mod` enum('Yes','No') DEFAULT NULL,
  `disclaimer_olo` text,
  `integration_license` varchar(255) DEFAULT NULL,
  `operating_system` enum('Windows 7','Windows 8','Windows 8.1','Windows 10','OS X','Android','Other') DEFAULT NULL,
  `olo_take_cash` enum('Yes','No') DEFAULT NULL,
  `remote_access_type` enum('Aloha Command Center','LogMeln','TeamViewer') DEFAULT NULL,
  `remote_access_id` varchar(45) DEFAULT NULL,
  `remote_access_pin` varchar(45) DEFAULT NULL,
  `createdon` varchar(45) DEFAULT NULL,
  `date_added` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_mobile` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`locations_audit_id`),
  KEY `locations_audit_ind_idx` (`id`),
  KEY `locations_audit_last_datetime_idx` (`last_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=80282184 DEFAULT CHARSET=latin1 COMMENT='Stores any changes made to the locations table';

/*Table structure for table `locations_type_items` */

DROP TABLE IF EXISTS `locations_type_items`;

CREATE TABLE `locations_type_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `location_id` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  KEY `locations_type_items_fk` (`location_id`),
  CONSTRAINT `location_type_items_type` FOREIGN KEY (`type_id`) REFERENCES `location_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `locations_type_items_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=308752 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_categories` */

DROP TABLE IF EXISTS `omnivore_categories`;

CREATE TABLE `omnivore_categories` (
  `omnivore_categories_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `category_id` varchar(45) DEFAULT NULL,
  `pos_id` varchar(15) DEFAULT NULL,
  `level` varchar(15) DEFAULT NULL,
  `embedded_parent_category` varchar(200) DEFAULT NULL,
  `v1_id` varchar(50) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `kiosk_menu_category_name` varchar(45) DEFAULT NULL,
  `dp_menu_category_name` varchar(45) DEFAULT NULL,
  `submenu` enum('Yes','No') NOT NULL DEFAULT 'No',
  `embedded_items` int(10) DEFAULT NULL,
  `discount` enum('Yes','No') DEFAULT 'No',
  `percent` double DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `time_start` varchar(45) DEFAULT NULL,
  `time_end` varchar(45) DEFAULT NULL,
  `available_dow` varchar(100) DEFAULT NULL,
  `job_id` varchar(45) DEFAULT NULL,
  `livemenu` enum('Y','N') DEFAULT 'N',
  `livemenu_dp` enum('Y','N') DEFAULT 'Y',
  `livemenu_dp_priority` int(11) DEFAULT NULL,
  `livemenu_kiosk` enum('Y','N') DEFAULT 'Y',
  `livemenu_kiosk_priority` int(11) DEFAULT NULL,
  `image` varchar(400) DEFAULT NULL,
  `image_submenu` varchar(400) DEFAULT NULL,
  `tax` float(10,2) DEFAULT NULL,
  `category_id_int` int(15) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_categories_id`),
  KEY `omnivore_categories_location_id_idk` (`location_id`),
  KEY `omnivore_categories_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_categories_category_id_idk` (`category_id`),
  KEY `omnivore_categories_discount_idk` (`discount`),
  KEY `omnivore_categories_name_idk` (`name`),
  KEY `omnivore_categories_loc_status_idk` (`location_id`,`status`),
  KEY `omnivore_categories_status_idk` (`status`),
  KEY `omnivore_categories_submenu_idk` (`submenu`),
  KEY `omnivore_categories_dp_idk` (`livemenu_dp`),
  KEY `omnivore_categories_job_idk` (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59835 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `omnivore_categories_and_items` */

DROP TABLE IF EXISTS `omnivore_categories_and_items`;

CREATE TABLE `omnivore_categories_and_items` (
  `omnivore_categories_and_items_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `omnivore_location_id` varchar(45) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `category_id` varchar(45) NOT NULL,
  `cat_v1_id` varchar(45) DEFAULT NULL,
  `cat_pos_id` varchar(45) DEFAULT NULL,
  `menu_item_id` varchar(45) NOT NULL,
  `item_v1_id` varchar(45) DEFAULT NULL,
  `item_pos_id` varchar(45) DEFAULT NULL,
  `job_id` varchar(45) DEFAULT NULL,
  `livemenu_dp` enum('Y','N') DEFAULT 'Y',
  `livemenu_dp_priority` int(11) DEFAULT NULL,
  `livemenu_kiosk` enum('Y','N') DEFAULT 'Y',
  `livemenu_kiosk_priority` int(11) DEFAULT NULL,
  `category_id_int` int(15) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_categories_and_items_id`),
  KEY `omnivore_categories_and_items_location_id_idk` (`location_id`),
  KEY `omnivore_categories_and_items_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_categories_and_items_category_id_idk` (`category_id`),
  KEY `omnivore_categories_and_items_menu_item_id_idk` (`menu_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2305980 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_discounts` */

DROP TABLE IF EXISTS `omnivore_discounts`;

CREATE TABLE `omnivore_discounts` (
  `omnivore_discounts_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `discount_id` varchar(45) DEFAULT NULL,
  `v1_id` varchar(45) DEFAULT NULL,
  `pos_id` varchar(45) DEFAULT NULL,
  `applies_to_ticket` varchar(5) DEFAULT NULL,
  `applies_to_item` varchar(5) DEFAULT NULL,
  `available` varchar(5) DEFAULT NULL,
  `max_value` varchar(10) DEFAULT NULL,
  `min_ticket_total` varchar(10) DEFAULT NULL,
  `min_value` varchar(10) DEFAULT NULL,
  `min_amount` varchar(15) DEFAULT NULL,
  `min_percent` varchar(15) DEFAULT NULL,
  `max_amount` varchar(15) DEFAULT NULL,
  `max_percent` varchar(15) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `open` varchar(10) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `value` varchar(20) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_discounts_id`),
  KEY `omnivore_discounts_location_id_idk` (`location_id`),
  KEY `omnivore_discounts_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_discounts_discount_id_idk` (`discount_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57391 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `omnivore_employees` */

DROP TABLE IF EXISTS `omnivore_employees`;

CREATE TABLE `omnivore_employees` (
  `omnivore_employees_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `employee_id` varchar(45) DEFAULT NULL,
  `v1_id` varchar(45) DEFAULT NULL,
  `pos_id` varchar(45) DEFAULT NULL,
  `employee_check_name` varchar(45) DEFAULT NULL,
  `employee_first_name` varchar(45) DEFAULT NULL,
  `employee_last_name` varchar(45) DEFAULT NULL,
  `employee_login` varchar(10) DEFAULT NULL,
  `clover_employee_id` varchar(45) DEFAULT NULL,
  `clover_employee_pin` varchar(45) DEFAULT NULL,
  `clover_role` enum('EMPLOYEE','MANAGER','ADMIN') DEFAULT 'EMPLOYEE',
  `can_order` enum('Yes','No') DEFAULT 'No',
  `employee_job_code` varchar(45) DEFAULT NULL,
  `view_all_job_codes_tickets` enum('Yes','No') DEFAULT 'No',
  `see_all_checks` enum('Yes','No','NA') DEFAULT 'NA',
  `mag_card` enum('Yes','No') DEFAULT 'No',
  `mag_card_num` varchar(100) DEFAULT NULL,
  `mag_card_track1` varchar(100) DEFAULT NULL,
  `mag_card_track1_status` enum('NotUsed','Successful','Failed') DEFAULT 'NotUsed',
  `mag_card_track2` varchar(100) DEFAULT NULL,
  `mag_card_track2_status` enum('NotUsed','Successful','Failed') DEFAULT 'NotUsed',
  `mag_card_track3` varchar(100) DEFAULT NULL,
  `mag_card_track3_status` enum('NotUsed','Successful','Failed') DEFAULT 'NotUsed',
  `employee_run_sync` enum('Yes','No') DEFAULT 'No',
  `auth_allow` enum('Yes','No') DEFAULT 'No',
  `primary_location` enum('Yes','No','NotUsed') NOT NULL DEFAULT 'NotUsed' COMMENT 'For corp locations that share the same employees, indicates for which location an employee is enabled',
  `secondary_location_id` int(8) DEFAULT NULL,
  `image` longtext NOT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_employees_id`),
  KEY `omnivore_employees_location_id_idk` (`location_id`),
  KEY `omnivore_employees_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_employees_employee_id_idk` (`employee_id`),
  KEY `omnivore_employees_clover_employee_id_idk` (`clover_employee_id`),
  KEY `omnivore_employees_clover_employee_pin_idk` (`clover_employee_pin`)
) ENGINE=InnoDB AUTO_INCREMENT=117724 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_employees_pay_rates` */

DROP TABLE IF EXISTS `omnivore_employees_pay_rates`;

CREATE TABLE `omnivore_employees_pay_rates` (
  `omnivore_employees_pay_rates_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `employee_id` varchar(45) DEFAULT NULL,
  `pay_rate_id` varchar(45) DEFAULT NULL,
  `job_id` varchar(45) DEFAULT NULL,
  `rate` varchar(10) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_employees_pay_rates_id`),
  KEY `omnivore_employees_pay_rates_location_id_idk` (`location_id`),
  KEY `omnivore_employees_pay_rates_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_employees_pay_rates_employee_id_idk` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=107023 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_items_and_modifier_groups` */

DROP TABLE IF EXISTS `omnivore_items_and_modifier_groups`;

CREATE TABLE `omnivore_items_and_modifier_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `menu_item_id` varchar(45) DEFAULT NULL,
  `item_pos_id` varchar(45) DEFAULT NULL,
  `item_v1_id` varchar(45) DEFAULT NULL,
  `modifier_group_id` varchar(45) DEFAULT NULL,
  `mod_v1_id` varchar(45) DEFAULT NULL,
  `mod_pos_id` varchar(45) DEFAULT NULL,
  `maximum` int(11) DEFAULT NULL,
  `minimum` int(11) DEFAULT NULL,
  `required` enum('Yes','No') DEFAULT 'No',
  `livemenu_dp` enum('Y','N') DEFAULT 'Y',
  `livemenu_dp_priority` int(11) DEFAULT NULL,
  `livemenu_kiosk` enum('Y','N') DEFAULT 'Y',
  `livemenu_kiosk_priority` int(11) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `omnivore_items_and_modifier_groups_group` (`omnivore_location_id`,`menu_item_id`),
  KEY `omnivore_items_and_modifier_groups_loc_item_idx` (`location_id`,`menu_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=913251 DEFAULT CHARSET=latin1;

/*Table structure for table `omnivore_job_codes` */

DROP TABLE IF EXISTS `omnivore_job_codes`;

CREATE TABLE `omnivore_job_codes` (
  `omnivore_job_codes_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `job_id` varchar(45) DEFAULT NULL,
  `pos_id` varchar(15) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `show_in_livemenu` enum('Y','N') DEFAULT 'Y',
  `employees_see_all_checks` enum('Yes','No') DEFAULT 'No',
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_job_codes_id`),
  KEY `omnivore_job_codes_location_id_idk` (`location_id`),
  KEY `omnivore_job_codes_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_job_codes_job_id_idk` (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8519 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_menu_items` */

DROP TABLE IF EXISTS `omnivore_menu_items`;

CREATE TABLE `omnivore_menu_items` (
  `omnivore_menu_items_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `description` text,
  `menu_item_id` varchar(45) DEFAULT NULL,
  `barcode` varchar(45) DEFAULT NULL,
  `in_stock` varchar(5) DEFAULT NULL,
  `modifier_groups_count` int(10) DEFAULT NULL,
  `v1_id` varchar(45) DEFAULT NULL,
  `pos_id` varchar(45) DEFAULT NULL,
  `price_per_unit` varchar(45) DEFAULT NULL,
  `price_level_name` varchar(50) DEFAULT NULL,
  `embedded_price_levels` varchar(200) DEFAULT NULL,
  `embedded_menu_categories` varchar(200) DEFAULT NULL,
  `embedded_menu_categories_ints` varchar(200) DEFAULT NULL,
  `embedded_option_sets` varchar(200) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `kiosk_menu_item_name` varchar(45) DEFAULT NULL,
  `dp_menu_item_name` varchar(45) DEFAULT NULL,
  `open` varchar(10) DEFAULT NULL,
  `price` decimal(14,2) DEFAULT NULL,
  `price_levels` text,
  `livemenu_dp` enum('Y','N') DEFAULT 'Y',
  `livemenu_dp_priority` int(11) DEFAULT NULL,
  `livemenu_kiosk` enum('Y','N') DEFAULT 'Y',
  `livemenu_kiosk_priority` int(11) DEFAULT NULL,
  `show_no_req_mods` enum('Yes','No') DEFAULT 'Yes',
  `tax` float(10,2) DEFAULT NULL,
  `image` varchar(400) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `sp_internal_promotion` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`omnivore_menu_items_id`),
  KEY `omnivore_menu_items_location_id_idk` (`location_id`),
  KEY `omnivore_menu_items_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_menu_items_menu_item_id` (`menu_item_id`),
  KEY `omnivore_menu_items_sp_internal_promotion_idk` (`sp_internal_promotion`)
) ENGINE=InnoDB AUTO_INCREMENT=543611 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_modifier_groups` */

DROP TABLE IF EXISTS `omnivore_modifier_groups`;

CREATE TABLE `omnivore_modifier_groups` (
  `omnivore_modifier_groups_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `modifier_group_id` varchar(45) DEFAULT NULL,
  `pos_id` varchar(15) DEFAULT NULL,
  `embedded_modifiers` varchar(200) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `kiosk_modifier_group_name` varchar(45) DEFAULT NULL,
  `dp_modifier_group_name` varchar(45) DEFAULT NULL,
  `livemenu_dp` enum('Y','N') DEFAULT 'Y',
  `livemenu_kiosk` enum('Y','N') DEFAULT 'Y',
  `maximum` varchar(10) DEFAULT NULL,
  `minimum` varchar(10) DEFAULT NULL,
  `free_quantity` int(11) DEFAULT '0',
  `required` varchar(10) DEFAULT NULL,
  `embedded_options` int(10) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_modifier_groups_id`),
  KEY `omnivore_modifier_groups_location_id_idk` (`location_id`),
  KEY `omnivore_modifier_groups_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_modifier_groups_modifier_group_id_idk` (`modifier_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57127 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_modifier_groups_and_modifiers` */

DROP TABLE IF EXISTS `omnivore_modifier_groups_and_modifiers`;

CREATE TABLE `omnivore_modifier_groups_and_modifiers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `modifier_group_id` varchar(45) DEFAULT NULL,
  `modg_pos_id` varchar(45) DEFAULT NULL,
  `modg_v1_id` varchar(45) DEFAULT NULL,
  `modifier_id` varchar(45) DEFAULT NULL,
  `mod_pos_id` varchar(45) DEFAULT NULL,
  `mod_v1_id` varchar(45) DEFAULT NULL,
  `livemenu_dp` enum('Y','N') DEFAULT 'Y',
  `livemenu_dp_priority` int(11) DEFAULT NULL,
  `livemenu_kiosk` enum('Y','N') DEFAULT 'Y',
  `livemenu_kiosk_priority` int(11) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_combo` (`modifier_group_id`,`modifier_id`),
  KEY `omnivore_modifier_groups_and_modifiers_status_idk` (`status`),
  KEY `omnivore_modifier_groups_and_modifiers_omnivore_location_id_idk` (`omnivore_location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=970324 DEFAULT CHARSET=latin1;

/*Table structure for table `omnivore_modifiers` */

DROP TABLE IF EXISTS `omnivore_modifiers`;

CREATE TABLE `omnivore_modifiers` (
  `omnivore_modifiers_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `modifier_id` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `kiosk_modifier_name` varchar(45) DEFAULT NULL,
  `dp_modifier_name` varchar(45) DEFAULT NULL,
  `open` varchar(10) DEFAULT NULL,
  `v1_id` varchar(15) DEFAULT NULL,
  `pos_id` varchar(15) DEFAULT NULL,
  `embedded_price_levels` varchar(200) DEFAULT NULL,
  `embedded_menu_categories` varchar(200) DEFAULT NULL,
  `embedded_menu_categories_ints` varchar(200) DEFAULT NULL,
  `embedded_option_sets` varchar(200) DEFAULT NULL,
  `price_levels` text,
  `price_per_unit` decimal(14,2) DEFAULT NULL,
  `price` decimal(14,2) DEFAULT NULL,
  `tax` decimal(14,2) DEFAULT NULL,
  `custom_price` varchar(45) DEFAULT NULL,
  `livemenu_dp` enum('Y','N') DEFAULT 'Y',
  `livemenu_kiosk` enum('Y','N') DEFAULT 'Y',
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_modifiers_id`),
  KEY `omnivore_modifiers_location_id_idk` (`location_id`),
  KEY `omnivore_modifiers_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_modifiers_modifier_id_idk` (`modifier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=331277 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_modifiers_and_modifier_groups` */

DROP TABLE IF EXISTS `omnivore_modifiers_and_modifier_groups`;

CREATE TABLE `omnivore_modifiers_and_modifier_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `modifier_id` varchar(45) DEFAULT NULL,
  `mod_pos_id` varchar(45) DEFAULT NULL,
  `mod_v1_id` varchar(45) DEFAULT NULL,
  `modifier_group_id` varchar(45) DEFAULT NULL,
  `mod_group_v1_id` varchar(45) DEFAULT NULL,
  `mod_group_pos_id` varchar(45) DEFAULT NULL,
  `maximum` int(11) DEFAULT NULL,
  `minimum` int(11) DEFAULT NULL,
  `required` enum('Yes','No') DEFAULT 'No',
  `livemenu_dp` enum('Y','N') DEFAULT 'Y',
  `livemenu_dp_priority` int(11) DEFAULT NULL,
  `livemenu_kiosk` enum('Y','N') DEFAULT 'Y',
  `livemenu_kiosk_priority` int(11) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `omnivore_modifiers_and_modifier_groups_group` (`omnivore_location_id`,`modifier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=208168 DEFAULT CHARSET=latin1;

/*Table structure for table `omnivore_order_types` */

DROP TABLE IF EXISTS `omnivore_order_types`;

CREATE TABLE `omnivore_order_types` (
  `omnivore_order_types_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `order_type_id` varchar(45) DEFAULT NULL,
  `v1_id` varchar(15) DEFAULT NULL,
  `pos_id` varchar(15) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `available` varchar(10) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_order_types_id`),
  KEY `omnivore_order_types_location_id_idk` (`location_id`),
  KEY `omnivore_order_types_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_order_types_order_type_id_idk` (`order_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2107 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_revenue_centers` */

DROP TABLE IF EXISTS `omnivore_revenue_centers`;

CREATE TABLE `omnivore_revenue_centers` (
  `omnivore_revenue_centers_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `revenue_center_id` varchar(45) DEFAULT NULL,
  `revenue_center_default` varchar(5) DEFAULT NULL,
  `v1_id` varchar(11) DEFAULT NULL,
  `pos_id` varchar(11) DEFAULT NULL,
  `links_open_tickets` varchar(200) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `embedded_open_tickets` varchar(5) DEFAULT NULL,
  `embedded_tables` varchar(5) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_revenue_centers_id`),
  KEY `omnivore_revenue_centers_location_id_idk` (`location_id`),
  KEY `omnivore_revenue_centers_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_revenue_centers_revenue_center_id_idk` (`revenue_center_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3473 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_tables` */

DROP TABLE IF EXISTS `omnivore_tables`;

CREATE TABLE `omnivore_tables` (
  `omnivore_tables_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `table_id` varchar(45) DEFAULT NULL,
  `available` varchar(5) DEFAULT NULL,
  `v1_id` varchar(11) DEFAULT NULL,
  `pos_id` varchar(11) DEFAULT NULL,
  `links_open_tickets` text,
  `name` varchar(45) DEFAULT NULL,
  `dp_name` varchar(45) DEFAULT NULL,
  `number` varchar(10) DEFAULT NULL,
  `seats` varchar(10) DEFAULT NULL,
  `embedded_revenue_centers_default` varchar(5) DEFAULT NULL,
  `embedded_revenue_centers_id` varchar(45) DEFAULT NULL,
  `embedded_revenue_centers_name` varchar(45) DEFAULT NULL,
  `embedded_open_tickets` int(11) DEFAULT NULL,
  `priority` int(8) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_tables_id`),
  KEY `omnivore_tables_location_id_idk` (`location_id`),
  KEY `omnivore_tables_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_tables_table_id_idk` (`table_id`),
  KEY `created_datetime` (`created_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=175833 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_tender_types` */

DROP TABLE IF EXISTS `omnivore_tender_types`;

CREATE TABLE `omnivore_tender_types` (
  `omnivore_tender_types_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `tender_type_id` varchar(45) DEFAULT NULL,
  `v1_id` varchar(11) DEFAULT NULL,
  `pos_id` varchar(11) DEFAULT NULL,
  `allows_tips` varchar(15) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_tender_types_id`),
  KEY `omnivore_tender_types_location_id_idk` (`location_id`),
  KEY `omnivore_tender_types_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_tender_types_tender_type_id_idk` (`tender_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13539 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_terminals` */

DROP TABLE IF EXISTS `omnivore_terminals`;

CREATE TABLE `omnivore_terminals` (
  `omnivore_terminals_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `terminal_id` varchar(45) DEFAULT NULL,
  `pos_id` varchar(15) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `receipt_name` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_terminals_id`),
  KEY `omnivore_terminals_location_id_idk` (`location_id`),
  KEY `omnivore_terminals_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_terminals_terminal_id_idk` (`terminal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3779 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_tickets` */

DROP TABLE IF EXISTS `omnivore_tickets`;

CREATE TABLE `omnivore_tickets` (
  `omnivore_tickets_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `omnivore_location_id` varchar(45) NOT NULL,
  `ticket_id` varchar(45) NOT NULL,
  `v1_id` varchar(45) DEFAULT NULL,
  `pos_id` varchar(45) NOT NULL,
  `auto_send` varchar(5) DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `guest_count` int(11) DEFAULT NULL,
  `name` text,
  `open` varchar(5) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `voided` varchar(5) DEFAULT NULL,
  `ticket_number` int(11) DEFAULT NULL,
  `sub_total` decimal(14,2) DEFAULT NULL,
  `service_charges` decimal(14,2) DEFAULT NULL,
  `tax` decimal(14,2) DEFAULT NULL,
  `total` decimal(14,2) DEFAULT NULL,
  `other_charges` decimal(14,2) DEFAULT NULL,
  `grand_total` decimal(14,2) DEFAULT NULL,
  `payment` decimal(14,2) DEFAULT NULL,
  `clover_payment` decimal(14,2) DEFAULT NULL,
  `due` decimal(14,2) DEFAULT NULL,
  `enbedded_employee_check_name` text,
  `enbedded_employee_first_name` text,
  `enbedded_employee_id` text,
  `enbedded_employee_last_name` text,
  `enbedded_employee_login` text,
  `embedded_order_type_available` varchar(5) DEFAULT NULL,
  `embedded_order_type_id` text,
  `embedded_order_type_name` text,
  `embedded_revenue_centers_default` varchar(5) DEFAULT NULL,
  `embedded_revenue_centers_id` text,
  `embedded_revenue_centers_name` text,
  `embedded_table_available` varchar(5) DEFAULT NULL,
  `embedded_table_id` text,
  `embedded_table_name` text,
  `embedded_table_number` int(11) DEFAULT NULL,
  `embedded_table_seats` int(11) DEFAULT NULL,
  `aireus_tray` varchar(45) DEFAULT NULL,
  `enbedded_discounts` int(11) DEFAULT NULL,
  `embedded_items` int(11) DEFAULT NULL,
  `embedded_payments` int(11) DEFAULT NULL,
  `embedded_voided_items` int(11) DEFAULT NULL,
  `embedded_service_charges` text,
  `totals_other_charges` varchar(15) DEFAULT NULL,
  `totals_items` varchar(15) DEFAULT NULL,
  `totals_tips` varchar(15) DEFAULT NULL,
  `totals_discounts` varchar(15) DEFAULT NULL,
  `clover_order_id` varchar(45) DEFAULT NULL,
  `clover_taxtype_id` varchar(45) DEFAULT NULL,
  `removed_in_clover` datetime DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending','Failure','Item Error','Payment Error') DEFAULT 'Yes',
  `processed_datetime` datetime DEFAULT NULL,
  `pickup_time` datetime DEFAULT NULL,
  `last_ov_call_datetime` datetime DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_tickets_id`),
  KEY `omnivore_tickets_open_idk` (`open`),
  KEY `omnivore_tickets_location_id_idk` (`location_id`),
  KEY `omnivore_tickets_clover_order_id_idk` (`clover_order_id`),
  KEY `omnivore_tickets_ticket_id` (`ticket_id`),
  KEY `omnivore_tickets_omnivore_location_id` (`omnivore_location_id`),
  KEY `omnivore_tickets_opened_at` (`opened_at`),
  KEY `omnivore_tickets_closed_at` (`closed_at`),
  KEY `omnivore_tickets_removed_in_clover_idk` (`removed_in_clover`),
  KEY `idx_combo` (`omnivore_location_id`,`open`,`opened_at`),
  KEY `omnivore_tickets_created_datetime_idk` (`created_datetime`),
  KEY `omnivore_tickets_ticket_number_idk` (`ticket_number`),
  KEY `omnivore_ov_ticket_id_created_datetime_combo` (`omnivore_tickets_id`,`created_datetime`),
  KEY `omnivore_ticket_id_created_datetime_combo` (`ticket_id`,`created_datetime`),
  KEY `omnivore_ticket_id_opened_at_combo` (`ticket_id`,`opened_at`),
  KEY `omnivore_tickets_status_by_date` (`location_id`,`open`,`opened_at`),
  KEY `ov_ticket_id_created_datetime_combo` (`omnivore_tickets_id`,`created_datetime`),
  KEY `ticket_id_created_datetime_combo` (`ticket_id`,`created_datetime`),
  KEY `ticket_id_opened_at_combo` (`ticket_id`,`opened_at`)
) ENGINE=InnoDB AUTO_INCREMENT=13095092 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_tickets_discounts` */

DROP TABLE IF EXISTS `omnivore_tickets_discounts`;

CREATE TABLE `omnivore_tickets_discounts` (
  `omnivore_tickets_items_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `ticket_id` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `menu_discount_id` varchar(45) DEFAULT NULL,
  `discount_id` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  `clover_discount_id` varchar(45) DEFAULT NULL,
  `omnivore_tickets_id` int(11) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_tickets_items_id`),
  KEY `omnivore_tickets_discounts_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_tickets_discounts_location_id_idk` (`location_id`),
  KEY `omnivore_tickets_discounts_ticket_id_idk` (`ticket_id`),
  KEY `omnivore_tickets_discounts_discount_id_idk` (`discount_id`),
  KEY `omnivore_tickets_discounts_omnivore_tickets_id_idk` (`omnivore_tickets_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1057427 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_tickets_items` */

DROP TABLE IF EXISTS `omnivore_tickets_items`;

CREATE TABLE `omnivore_tickets_items` (
  `omnivore_tickets_items_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `ticket_id` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `sent_time` datetime DEFAULT NULL,
  `category_id` varchar(45) DEFAULT NULL,
  `menu_item_id` varchar(45) DEFAULT NULL,
  `sp_internal_promotion` enum('Y','N') DEFAULT 'N',
  `item_id` varchar(45) DEFAULT NULL,
  `item_name` varchar(45) DEFAULT NULL,
  `item_comment` varchar(200) DEFAULT NULL,
  `price_level` varchar(45) DEFAULT NULL,
  `original_amount` decimal(14,2) DEFAULT NULL,
  `price_per_unit` decimal(14,2) DEFAULT NULL,
  `quantity` int(10) DEFAULT NULL,
  `aireus_quantity` float DEFAULT NULL,
  `item_total` decimal(14,2) DEFAULT NULL,
  `void_item_id` varchar(45) DEFAULT NULL,
  `is_void` varchar(4) DEFAULT NULL,
  `sent` varchar(45) DEFAULT NULL,
  `split` varchar(15) DEFAULT NULL,
  `price` varchar(15) DEFAULT NULL,
  `clover_item_id` varchar(45) DEFAULT NULL,
  `omnivore_tickets_id` int(11) DEFAULT NULL,
  `category_id_int` int(15) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_tickets_items_id`),
  KEY `omnivore_tickets_items_ticket_id_IDK` (`ticket_id`),
  KEY `omnivore_tickets_items_item_id_IDK` (`item_id`),
  KEY `omnivore_tickets_items_location_id_IDK` (`location_id`),
  KEY `omnivore_tickets_items_omnivore_location_id_IDK` (`omnivore_location_id`),
  KEY `omnivore_tickets_items_menu_item_id_IDK` (`menu_item_id`),
  KEY `omnivore_tickets_items_sp_promotion_IDK` (`sp_internal_promotion`),
  KEY `omnivore_tickets_items_is_void_IDK` (`is_void`),
  KEY `omnivore_tickets_items_clover_item_id_IDK` (`clover_item_id`),
  KEY `omnivore_tickets_items_omnivore_tickets_id_IDK` (`omnivore_tickets_id`),
  KEY `omnivore_tickets_items_omnivore_tickets_quantity_IDK` (`quantity`),
  KEY `omnivore_tickets_items_omnivore_tickets_item_total_IDK` (`item_total`),
  KEY `omnivore_tickets_items_omnivore_tickets_items_created_dt_IDK` (`created_datetime`),
  KEY `omnivore_tickets_items_isent_idk` (`location_id`,`opened_at`,`sent`,`is_void`)
) ENGINE=InnoDB AUTO_INCREMENT=57092664 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_tickets_items_discounts` */

DROP TABLE IF EXISTS `omnivore_tickets_items_discounts`;

CREATE TABLE `omnivore_tickets_items_discounts` (
  `omnivore_tickets_items_discounts_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `ticket_id` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `menu_discount_id` varchar(45) DEFAULT NULL,
  `item_id` varchar(45) DEFAULT NULL,
  `discount_id` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  `clover_item_discount_id` varchar(45) DEFAULT NULL,
  `omnivore_tickets_id` int(11) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_tickets_items_discounts_id`),
  KEY `omnivore_tickets_items_discounts_omnivore_location_id_idk` (`omnivore_location_id`),
  KEY `omnivore_tickets_items_discounts_location_id_idk` (`location_id`),
  KEY `omnivore_tickets_items_discounts_ticket_id_idk` (`ticket_id`),
  KEY `omnivore_tickets_items_discounts_discount_id_idk` (`discount_id`),
  KEY `omnivore_tickets_items_discounts_omnivore_tickets_id_idk` (`omnivore_tickets_id`)
) ENGINE=InnoDB AUTO_INCREMENT=520191 DEFAULT CHARSET=latin1;

/*Table structure for table `omnivore_tickets_items_modifiers` */

DROP TABLE IF EXISTS `omnivore_tickets_items_modifiers`;

CREATE TABLE `omnivore_tickets_items_modifiers` (
  `omnivore_tickets_items_modifiers_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `ticket_id` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `menu_modifier_id` varchar(45) DEFAULT NULL,
  `item_id` varchar(45) DEFAULT NULL,
  `modifier_id` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `price` varchar(15) DEFAULT NULL,
  `price_level` text,
  `price_per_unit` decimal(14,2) DEFAULT NULL,
  `quantity` int(10) DEFAULT NULL,
  `clover_item_modifier_id` varchar(45) DEFAULT NULL,
  `omnivore_tickets_id` int(11) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_tickets_items_modifiers_id`),
  KEY `omnivore_tickets_items_modifiers_location_id_IDK` (`location_id`),
  KEY `omnivore_tickets_items_modifiers_omnivore_location_id_IDK` (`omnivore_location_id`),
  KEY `omnivore_tickets_items_modifiers_ticket_id_IDK` (`ticket_id`),
  KEY `omnivore_tickets_items_modifiers_item_id_IDK` (`item_id`),
  KEY `omnivore_tickets_items_modifiers_modifier_id_IDK` (`modifier_id`),
  KEY `omnivore_tickets_items_omnivore_tickets_id_IDK` (`omnivore_tickets_id`),
  KEY `omnivore_tickets_items_modifiers_created_dt_IDK` (`created_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=37776775 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_tickets_items_modifiers_mods` */

DROP TABLE IF EXISTS `omnivore_tickets_items_modifiers_mods`;

CREATE TABLE `omnivore_tickets_items_modifiers_mods` (
  `omnivore_tickets_items_modifiers_mods_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(8) DEFAULT NULL,
  `omnivore_location_id` varchar(45) DEFAULT NULL,
  `ticket_id` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `menu_modifier_id` varchar(45) DEFAULT NULL,
  `item_id` varchar(45) DEFAULT NULL,
  `parent_modifier_id` varchar(45) DEFAULT NULL,
  `modifier_id` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `price` varchar(15) DEFAULT NULL,
  `price_level` text,
  `price_per_unit` decimal(14,2) DEFAULT NULL,
  `quantity` int(10) DEFAULT NULL,
  `clover_item_modifier_mod_id` varchar(45) DEFAULT NULL,
  `omnivore_tickets_id` int(11) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_tickets_items_modifiers_mods_id`),
  KEY `omnivore_tickets_items_modifiers_mods_location_id_IDK` (`location_id`),
  KEY `omnivore_tickets_items_modifiers_mods_omnivore_location_id_IDK` (`omnivore_location_id`),
  KEY `omnivore_tickets_items_modifiers_mods_ticket_id_IDK` (`ticket_id`),
  KEY `omnivore_tickets_items_modifiers_mods_item_id_IDK` (`item_id`),
  KEY `omnivore_tickets_items_modifiers_mods_modifier_id_IDK` (`modifier_id`),
  KEY `omnivore_tickets_items_omnivore_tickets_id_IDK` (`omnivore_tickets_id`)
) ENGINE=InnoDB AUTO_INCREMENT=795274 DEFAULT CHARSET=latin1;

/*Table structure for table `omnivore_tickets_payments` */

DROP TABLE IF EXISTS `omnivore_tickets_payments`;

CREATE TABLE `omnivore_tickets_payments` (
  `omnivore_tickets_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `omnivore_location_id` varchar(45) NOT NULL,
  `omnivore_tender_type` varchar(50) DEFAULT NULL,
  `status` enum('Active','Removed','Refunded') DEFAULT 'Active',
  `ticket_id` varchar(45) NOT NULL,
  `opened_at` datetime DEFAULT NULL,
  `payment_id` varchar(45) DEFAULT NULL,
  `amount` decimal(14,2) DEFAULT NULL,
  `received` decimal(14,2) DEFAULT NULL,
  `change` decimal(14,2) DEFAULT NULL,
  `adjusted_amount` decimal(14,2) DEFAULT '0.00',
  `last4` varchar(4) DEFAULT NULL,
  `tip` int(10) DEFAULT NULL,
  `paid` decimal(14,2) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `tender_type_name` varchar(45) DEFAULT NULL,
  `comment` varchar(45) DEFAULT NULL,
  `clover_order_id` varchar(45) DEFAULT NULL,
  `clover_payment_id` varchar(45) DEFAULT NULL,
  `clover_discount_id` varchar(45) DEFAULT NULL,
  `transactionNo` varchar(45) DEFAULT NULL,
  `clover_created_datetime` datetime DEFAULT NULL,
  `clover_mid` varchar(45) DEFAULT NULL,
  `clover_auth` varchar(45) DEFAULT NULL,
  `clover_ref` varchar(45) DEFAULT NULL,
  `clover_cvm` varchar(45) DEFAULT NULL,
  `card_entry` varchar(255) DEFAULT NULL,
  `omnivore_tickets_id` int(11) DEFAULT NULL,
  `clover_refund_id` varchar(45) DEFAULT NULL,
  `refunded_amount` decimal(14,2) DEFAULT NULL,
  `refunded_datetime` datetime DEFAULT NULL,
  `removed` enum('Yes','No') DEFAULT 'No',
  `omnivore_employee_id` varchar(45) DEFAULT NULL,
  `pax_integrated_payments_id` varchar(45) DEFAULT NULL,
  `clover_integrated_payments_id` int(11) DEFAULT NULL,
  `aio_integrated_payments_id` int(11) DEFAULT NULL,
  `poynt_integrated_payments_id` int(11) DEFAULT NULL,
  `ingenico_integrated_payments_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `balance_due` varchar(45) DEFAULT NULL,
  `hotel_room_number` varchar(45) DEFAULT NULL,
  `hotel_guest_name` varchar(45) DEFAULT NULL,
  `discount_code_value` varchar(45) DEFAULT NULL,
  `tip_adjust_datetime` datetime DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`omnivore_tickets_payments_id`),
  KEY `omnivore_tickets_location_id_IDK` (`location_id`),
  KEY `omnivore_tickets_omnivore_location_id_IDK` (`omnivore_location_id`),
  KEY `omnivore_tickets_omnivore_tickets_payments_IDK` (`omnivore_tender_type`),
  KEY `omnivore_tickets_ticket_id_IDK` (`ticket_id`),
  KEY `omnivore_tickets_payment_id_IDK` (`payment_id`),
  KEY `omnivore_tickets_clover_order_id_idk` (`clover_order_id`),
  KEY `omnivore_tickets_clover_payment_id_idk` (`clover_payment_id`),
  KEY `omnivore_tickets_omnivore_tickets_id_idk` (`omnivore_tickets_id`),
  KEY `omnivore_tickets_clover_discount_id_idk` (`clover_discount_id`),
  KEY `idx_combo` (`ticket_id`,`omnivore_location_id`),
  KEY `omnivore_tickets_payments_client_id_idx` (`client_id`),
  KEY `ticket_created_on_combo` (`ticket_id`,`created_datetime`),
  KEY `Payment_created_combo` (`omnivore_tickets_payments_id`,`created_datetime`),
  KEY `omnivore_tickets_payments_aio_id_idx` (`aio_integrated_payments_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12288052 DEFAULT CHARSET=utf8;

/*Table structure for table `omnivore_webhook` */

DROP TABLE IF EXISTS `omnivore_webhook`;

CREATE TABLE `omnivore_webhook` (
  `omnivore_webhook_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `omnivore_location_id` varchar(45) NOT NULL,
  `status` enum('New','Processed','Failed','Pending','Duplicated','Cancelled') DEFAULT NULL,
  `data_type` varchar(45) DEFAULT NULL,
  `webhook_id` varchar(45) DEFAULT NULL,
  `retries` int(11) DEFAULT NULL,
  `triggered_at` datetime DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `version` varchar(45) DEFAULT NULL,
  `webhook` longtext,
  `omnivore_ticket_id` varchar(45) DEFAULT NULL,
  `omnivore_payment_id` varchar(45) DEFAULT NULL,
  `omnivore_terminal_id` varchar(45) DEFAULT NULL,
  `omnivore_tender_type_id` varchar(45) DEFAULT NULL,
  `employee_id` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `tip` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `unique_ticket_id` varchar(45) DEFAULT NULL,
  `payment_catcher_webhook_id` int(11) DEFAULT NULL,
  `failure_response` varchar(400) DEFAULT NULL,
  `failure_datetime` datetime DEFAULT NULL,
  `sent_datetime` datetime DEFAULT NULL,
  `recieved_datetime` datetime DEFAULT NULL,
  `error_datetime` datetime DEFAULT NULL,
  `error_message` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`omnivore_webhook_id`),
  KEY `omnivore_webhook_created_datetime_idk` (`created_datetime`),
  KEY `omnivore_webhook_location_id_idk` (`location_id`),
  KEY `omnivore_webhook_omnivore_location_id_idk` (`omnivore_location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=253082 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `open_drawer` */

DROP TABLE IF EXISTS `open_drawer`;

CREATE TABLE `open_drawer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_local` varchar(20) DEFAULT NULL,
  `ip_internet` varchar(20) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43279 DEFAULT CHARSET=utf8 COMMENT='Stores records of any time a cash register is opened';

/*Table structure for table `pax_devices` */

DROP TABLE IF EXISTS `pax_devices`;

CREATE TABLE `pax_devices` (
  `pax_devices_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `pax_id` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `pax_ip` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `pax_port` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `pax_serialport` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `pax_commtype` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `pax_timeout` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `pax_located` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `device_type` enum('S80','S300','D210') DEFAULT NULL,
  `manufacturer` enum('PAX','Exidigm','Anywhere Commerce') DEFAULT 'PAX',
  `card_not_present` enum('Yes','No') DEFAULT 'No',
  `cash` enum('Yes','No') DEFAULT 'No',
  `split` enum('Yes','No') DEFAULT 'No',
  `split_number` varchar(45) DEFAULT '7',
  `tips` enum('Yes','No') DEFAULT 'No',
  `tip_selections` varchar(45) DEFAULT '10,18,20',
  `debit` enum('Yes','No') DEFAULT 'No',
  `tip_adjust` enum('Yes','No') DEFAULT 'No',
  `subtotal_tip` enum('Yes','No') DEFAULT 'No',
  `tip_additional` enum('Yes','No') DEFAULT 'No',
  `print` enum('Yes','No') DEFAULT 'No',
  `printer` longtext,
  `printer1_type` varchar(45) DEFAULT '250',
  `auto_print` enum('Yes','No') DEFAULT 'No',
  `print_cash_receipt` enum('Yes','No') DEFAULT 'No',
  `quick_service` enum('Yes','No') DEFAULT 'No',
  `onscreen_signature` enum('Yes','No') DEFAULT 'No',
  `refund_receipt` enum('Yes','No') DEFAULT 'Yes',
  `thankYou_title` varchar(20) DEFAULT 'Thank You',
  `thankYou_msg1` varchar(20) DEFAULT NULL,
  `thankYou_msg2` varchar(20) DEFAULT 'For Shopping With Us',
  `omnivore_terminal_id` varchar(45) DEFAULT NULL,
  `omnivore_tender_type_id` varchar(45) DEFAULT NULL,
  `request_log` enum('Yes','No') DEFAULT 'No',
  `requested_log_datetime` datetime DEFAULT NULL,
  `created_on` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `created_by` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`pax_devices_id`),
  KEY `pax_devices_paxid_idx` (`pax_id`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8;

/*Table structure for table `pax_integrated_payments` */

DROP TABLE IF EXISTS `pax_integrated_payments`;

CREATE TABLE `pax_integrated_payments` (
  `pax_integrated_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` varchar(45) NOT NULL,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `split` enum('No','Yes') DEFAULT 'No',
  `transactionNo` int(11) DEFAULT NULL,
  `client_order_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `omnivore_tickets_id` varchar(45) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `seat` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `subtotal` varchar(45) DEFAULT NULL,
  `tax` varchar(45) DEFAULT NULL,
  `tip` varchar(45) DEFAULT NULL,
  `payment` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `changedue` varchar(45) DEFAULT NULL,
  `response_result_code` varchar(45) DEFAULT NULL,
  `response_result_text` varchar(45) DEFAULT NULL,
  `response_auth_code` varchar(45) DEFAULT NULL,
  `response_avs_response` varchar(45) DEFAULT NULL,
  `response_bogus_account_num` varchar(45) DEFAULT NULL,
  `response_card_type` varchar(45) DEFAULT NULL,
  `response_debit` enum('Yes','No') DEFAULT 'No',
  `response_cv_response` varchar(45) DEFAULT NULL,
  `response_aid` varchar(45) DEFAULT NULL,
  `response_tc` varchar(45) DEFAULT NULL,
  `response_entry` varchar(45) DEFAULT NULL,
  `response_host_code` varchar(45) DEFAULT NULL,
  `response_host_response` varchar(45) DEFAULT NULL,
  `response_message` varchar(45) DEFAULT NULL,
  `response_ref_num` varchar(45) DEFAULT NULL,
  `response_raw_response` varchar(45) DEFAULT NULL,
  `response_remaining_balance` decimal(10,2) DEFAULT NULL,
  `response_extra_balance` decimal(10,2) DEFAULT NULL,
  `response_requested_amt` decimal(10,2) DEFAULT NULL,
  `response_timestamp` datetime DEFAULT NULL,
  `response_entry_mode` varchar(45) DEFAULT NULL,
  `response_href` varchar(45) DEFAULT NULL,
  `signature_image` varchar(255) DEFAULT NULL,
  `gc_number` varchar(45) DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending','Failure','Wh_Error','Pre_Auth','Sending') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `async_status` enum('No','Processing','Finished','Failed') DEFAULT 'No',
  `payment_error` varchar(45) DEFAULT NULL,
  `show_retry_popup` enum('Yes','No') DEFAULT 'No',
  `make_a_payment` text,
  `make_payment_url` text,
  `id_pay` varchar(45) DEFAULT NULL,
  `softpoint_initials` varchar(2) DEFAULT NULL,
  `processed_on` enum('DataPoint','Payment Matcher') DEFAULT 'DataPoint',
  `processed_on_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`pax_integrated_payments_id`),
  KEY `pax_integrated_payments_location_id_idk` (`location_id`),
  KEY `pax_integrated_payments_employee_id_idk` (`employee_id`),
  KEY `pax_integrated_payments_ticket_idk` (`ticket`),
  KEY `pax_integrated_payments_processed_idk` (`processed`),
  KEY `pax_integrated_payments_opened_at_idk` (`opened_at`),
  KEY `pax_integrated_payments_location_ov_tik_idk` (`location_id`,`omnivore_tickets_id`),
  KEY `pax_integrated_payments_location_ov_tik_stat_idk` (`location_id`,`omnivore_tickets_id`,`status`),
  KEY `pax_integrated_payments_location_tranNo_idk` (`location_id`,`transactionNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `pax_integrated_payments_log` */

DROP TABLE IF EXISTS `pax_integrated_payments_log`;

CREATE TABLE `pax_integrated_payments_log` (
  `pax_integrated_payments_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) DEFAULT NULL,
  `data` longtext,
  `type` enum('Pending','Paid','Webhook') NOT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`pax_integrated_payments_log_id`),
  UNIQUE KEY `pax_integrated_payments_log_UNIQUE` (`pax_integrated_payments_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `pax_integrated_payments_pending` */

DROP TABLE IF EXISTS `pax_integrated_payments_pending`;

CREATE TABLE `pax_integrated_payments_pending` (
  `pax_integrated_payments_pending_id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` varchar(45) NOT NULL,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `split` enum('No','Yes') DEFAULT 'No',
  `tender_name` varchar(45) DEFAULT NULL,
  `transactionNo` int(11) DEFAULT NULL,
  `client_order_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `omnivore_tickets_id` varchar(45) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `seat` int(11) DEFAULT '99',
  `name` varchar(45) DEFAULT NULL,
  `subtotal` varchar(45) DEFAULT NULL,
  `tax` varchar(45) DEFAULT NULL,
  `tip` varchar(45) DEFAULT NULL,
  `payment` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `changedue` varchar(45) DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending','Failure') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `make_a_payment` text,
  `id_pay` varchar(45) DEFAULT NULL,
  `request` text,
  `response` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`pax_integrated_payments_pending_id`),
  KEY `pax_integrated_payments_pending_employee_id_idk` (`employee_id`),
  KEY `pax_integrated_payments_pending_location_id_idk` (`location_id`),
  KEY `pax_integrated_payments_pending_opened_at_idk` (`opened_at`),
  KEY `pax_integrated_payments_pending_processed_idk` (`processed`),
  KEY `pax_integrated_payments_pending_ticket_idk` (`ticket`),
  KEY `pax_integrated_payments_pending_loc_tik_idk` (`location_id`,`omnivore_tickets_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `pax_nonintegrated_payments` */

DROP TABLE IF EXISTS `pax_nonintegrated_payments`;

CREATE TABLE `pax_nonintegrated_payments` (
  `pax_nonintegrated_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` varchar(45) NOT NULL,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `customer` varchar(45) DEFAULT NULL,
  `doctor` varchar(45) DEFAULT NULL,
  `reason` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `tip` decimal(10,2) DEFAULT NULL,
  `payment` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `response_result_code` varchar(45) DEFAULT NULL,
  `response_result_txt` varchar(45) DEFAULT NULL,
  `response_auth_code` varchar(45) DEFAULT NULL,
  `response_avs_response` varchar(45) DEFAULT NULL,
  `response_bogus_account_num` varchar(45) DEFAULT NULL,
  `response_card_type` varchar(45) DEFAULT NULL,
  `response_cv_response` varchar(45) DEFAULT NULL,
  `response_host_code` varchar(45) DEFAULT NULL,
  `response_host_response` varchar(45) DEFAULT NULL,
  `response_message` varchar(45) DEFAULT NULL,
  `response_ref_num` varchar(45) DEFAULT NULL,
  `response_raw_response` varchar(45) DEFAULT NULL,
  `response_remaining_balance` decimal(10,2) DEFAULT NULL,
  `response_extra_balance` decimal(10,2) DEFAULT NULL,
  `response_requested_amt` decimal(10,2) DEFAULT NULL,
  `response_timestamp` datetime DEFAULT NULL,
  `response_href` varchar(45) DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `softpoint_initials` varchar(2) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`pax_nonintegrated_payments_id`),
  KEY `pax_nonintegrated_payments_employee_id_idk` (`employee_id`),
  KEY `pax_nonintegrated_payments_location_id_idk` (`location_id`),
  KEY `pax_nonintegrated_payments_opened_at_idk` (`opened_at`),
  KEY `pax_nonintegrated_payments_processed_idk` (`processed`),
  KEY `pax_nonintegrated_payments_ticket_idk` (`ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `payment_codes` */

DROP TABLE IF EXISTS `payment_codes`;

CREATE TABLE `payment_codes` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `payment_type` enum('Adjustments','Advance Deposit','Cash','Check','Credit Card','Debit Card','ExpenseTAB','Gift Certificate','Gratuity','Interface','Surcharge','Receivables','Clover','Pax') NOT NULL,
  `payment_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_codes_type_code_idx` (`payment_type`,`payment_code`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1 COMMENT='Global payment codes that can be used by any user';

/*Table structure for table `poynt_integrated_payments` */

DROP TABLE IF EXISTS `poynt_integrated_payments`;

CREATE TABLE `poynt_integrated_payments` (
  `poynt_integrated_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `poynt_device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `split` enum('No','Yes') DEFAULT 'No',
  `transactionNo` varchar(50) DEFAULT NULL,
  `client_order_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `omnivore_tickets_id` varchar(45) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `seat` int(11) DEFAULT '99',
  `name` varchar(45) DEFAULT NULL,
  `subtotal` varchar(45) DEFAULT NULL,
  `tax` varchar(45) DEFAULT NULL,
  `tip` varchar(45) DEFAULT NULL,
  `payment` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `changedue` varchar(45) DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending','Failure','Wh_Error','Pre_Auth','Sending') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `async_status` enum('No','Processing','Finished','Failed') DEFAULT 'No',
  `payment_error` varchar(45) DEFAULT NULL,
  `show_retry_popup` enum('Yes','No') DEFAULT 'No',
  `make_a_payment` text,
  `make_payment_url` text,
  `response_auth_code` varchar(45) DEFAULT NULL,
  `response_ref_num` varchar(45) DEFAULT NULL,
  `response_entry_mode` varchar(45) DEFAULT NULL,
  `response_card_type` varchar(45) DEFAULT NULL,
  `response_bogus_account_num` varchar(45) DEFAULT NULL,
  `response_href` varchar(45) DEFAULT NULL,
  `signature_image` varchar(255) DEFAULT NULL,
  `gc_number` varchar(45) DEFAULT NULL,
  `id_pay` varchar(45) DEFAULT NULL,
  `processed_on` enum('DataPoint','Payment Matcher') DEFAULT 'DataPoint',
  `processed_on_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`poynt_integrated_payments_id`),
  UNIQUE KEY `poynt_integrated_payments_id_UNIQUE` (`poynt_integrated_payments_id`),
  KEY `poynt_integrated_payments_employee_id_idk` (`employee_id`),
  KEY `poynt_integrated_payments_location_id_idk` (`location_id`),
  KEY `poynt_integrated_payments_opened_at_idk` (`opened_at`),
  KEY `poynt_integrated_payments_processed_idk` (`processed`),
  KEY `poynt_integrated_payments_ticket_idk` (`ticket`),
  KEY `poynt_integrated_payments_location_ov_tik_idk` (`location_id`,`omnivore_tickets_id`),
  KEY `poynt_integrated_payments_location_ov_tik_stat_idk` (`location_id`,`omnivore_tickets_id`,`status`),
  KEY `poynt_integrated_payments_location_tranNo_idk` (`location_id`,`transactionNo`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `poynt_integrated_payments_pending` */

DROP TABLE IF EXISTS `poynt_integrated_payments_pending`;

CREATE TABLE `poynt_integrated_payments_pending` (
  `poynt_integrated_payments_pending_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `split` enum('No','Yes') DEFAULT 'No',
  `tender_name` varchar(45) DEFAULT NULL,
  `transactionNo` int(11) DEFAULT NULL,
  `client_order_id` int(11) DEFAULT NULL,
  `client_sales_id` int(11) DEFAULT NULL,
  `omnivore_tickets_id` varchar(45) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `seat` int(11) DEFAULT '99',
  `name` varchar(45) DEFAULT NULL,
  `subtotal` varchar(45) DEFAULT NULL,
  `tax` varchar(45) DEFAULT NULL,
  `tip` varchar(45) DEFAULT NULL,
  `payment` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `changedue` varchar(45) DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending','Failure') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `make_a_payment` text,
  `id_pay` varchar(45) DEFAULT NULL,
  `request` text,
  `response` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`poynt_integrated_payments_pending_id`),
  KEY `poynt_integrated_payments_pending_employee_id_idk` (`employee_id`),
  KEY `poynt_integrated_payments_pending_location_id_idk` (`location_id`),
  KEY `poynt_integrated_payments_pending_opened_at_idk` (`opened_at`),
  KEY `poynt_integrated_payments_pending_processed_idk` (`processed`),
  KEY `poynt_integrated_payments_pending_ticket_idk` (`ticket`),
  KEY `poynt_integrated_payments_pending_loc_tik_idk` (`location_id`,`omnivore_tickets_id`)
) ENGINE=InnoDB AUTO_INCREMENT=177 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `poynt_nonintegrated_payments` */

DROP TABLE IF EXISTS `poynt_nonintegrated_payments`;

CREATE TABLE `poynt_nonintegrated_payments` (
  `poynt_nonintegrated_payments_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) NOT NULL,
  `employee_id` varchar(45) NOT NULL,
  `device_id` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `ticket` varchar(45) DEFAULT NULL,
  `transactionNo` varchar(50) DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `table` varchar(45) DEFAULT NULL,
  `server` varchar(45) DEFAULT NULL,
  `folio` varchar(45) DEFAULT NULL,
  `room` varchar(45) DEFAULT NULL,
  `customer` varchar(45) DEFAULT NULL,
  `doctor` varchar(45) DEFAULT NULL,
  `reason` varchar(45) DEFAULT NULL,
  `cashier` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `tip` decimal(10,2) DEFAULT NULL,
  `payment` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `response_auth_code` varchar(45) DEFAULT NULL,
  `response_bogus_account_num` varchar(45) DEFAULT NULL,
  `response_card_type` varchar(45) DEFAULT NULL,
  `response_ref_num` varchar(45) DEFAULT NULL,
  `response_href` varchar(45) DEFAULT NULL,
  `response_entry_mode` varchar(45) DEFAULT NULL,
  `processed` enum('No','Yes','Error','Pending') DEFAULT 'No',
  `processed_datetime` datetime DEFAULT NULL,
  `custom_fields` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`poynt_nonintegrated_payments_id`),
  KEY `poynt_nonintegrated_payments_employee_id_idk` (`employee_id`),
  KEY `poynt_nonintegrated_payments_location_id_idk` (`location_id`),
  KEY `poynt_nonintegrated_payments_opened_at_idk` (`opened_at`),
  KEY `poynt_nonintegrated_payments_processed_idk` (`processed`),
  KEY `poynt_nonintegrated_payments_ticket_idk` (`ticket`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `preferences` */

DROP TABLE IF EXISTS `preferences`;

CREATE TABLE `preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` longtext COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='SoftPoint Preferences hold global site information';

/*Table structure for table `preferences_audit` */

DROP TABLE IF EXISTS `preferences_audit`;

CREATE TABLE `preferences_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utility_audit_delete_date` date DEFAULT NULL,
  `utility_audit_delete_key` varchar(100) DEFAULT NULL,
  `utility_audit_delete_last_run` datetime DEFAULT NULL,
  `utility_audit_delete_last_run_message` enum('completed','not completed') DEFAULT 'not completed',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

/*Table structure for table `preferences_database` */

DROP TABLE IF EXISTS `preferences_database`;

CREATE TABLE `preferences_database` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(200) DEFAULT NULL,
  `last_unique_field` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `printlist` */

DROP TABLE IF EXISTS `printlist`;

CREATE TABLE `printlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `terminal_id` int(11) DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `type` enum('print','receipt','CC Reprint') NOT NULL,
  `printed` enum('yes','no') NOT NULL DEFAULT 'no',
  `lastchange` datetime DEFAULT NULL,
  `seat` varchar(60) NOT NULL,
  `split` varchar(45) DEFAULT NULL,
  `hotel_account_id` int(10) DEFAULT NULL,
  `created_on` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `printlist_loc_fk` (`location_id`),
  KEY `idx_combo` (`location_id`,`printed`,`order_id`),
  KEY `printlist_lastchange_idx` (`order_id`,`lastchange`),
  CONSTRAINT `printlist_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=185404 DEFAULT CHARSET=utf8 COMMENT='Stores records of all needed print jobs from Retail and POS';

/*Table structure for table `purchase_items` */

DROP TABLE IF EXISTS `purchase_items`;

CREATE TABLE `purchase_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(8) NOT NULL,
  `inv_item_id` int(8) NOT NULL COMMENT 'Foregin key to vendor_items.id\n\nIt is NOT inventory_item.id!',
  `location_id` int(8) NOT NULL,
  `vendor_id` int(10) NOT NULL,
  `ordered_quantity` decimal(10,2) NOT NULL,
  `ordered_pack_size` int(11) NOT NULL,
  `ordered_pack_unittype` int(11) NOT NULL,
  `ordered_qty_in_pack` int(11) DEFAULT NULL,
  `ordered_qty_in_pack_unittype` int(11) DEFAULT NULL,
  `ordered_price` decimal(10,2) NOT NULL,
  `ordered_tax_percentage` decimal(10,2) NOT NULL,
  `received` enum('yes','no') NOT NULL DEFAULT 'no',
  `received_quantity` decimal(10,2) DEFAULT NULL,
  `received_pack_size` int(11) DEFAULT NULL,
  `received_pack_unittype` int(11) DEFAULT NULL,
  `received_qty_in_pack` int(11) DEFAULT NULL,
  `received_qty_in_pack_unittype` int(11) DEFAULT NULL,
  `received_price` decimal(10,2) DEFAULT NULL,
  `received_tax_percentage` decimal(10,2) DEFAULT NULL,
  `shipped` enum('yes','no') DEFAULT 'no',
  `shipped_quantity` decimal(10,2) DEFAULT NULL,
  `shipped_pack_size` int(11) DEFAULT NULL,
  `shipped_pack_unittype` int(11) DEFAULT NULL,
  `shipped_qty_in_pack` int(11) DEFAULT NULL,
  `shipped_qty_in_pack_unittype` int(11) DEFAULT NULL,
  `shipped_price` decimal(10,2) DEFAULT NULL,
  `shipped_tax_percentage` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_items_fk` (`purchase_id`),
  KEY `purchase_items_item_fk` (`inv_item_id`),
  KEY `purchase_items_loc_fk` (`location_id`),
  KEY `purchase_items_order_pack_unit_idx` (`ordered_pack_unittype`),
  KEY `purchase_items_order_qty_in_pack_unit_idx` (`ordered_qty_in_pack_unittype`),
  KEY `purchase_items_received_pack_unit_idx` (`received_pack_unittype`),
  KEY `purchase_items_received_qty_in_pack_unit_idx` (`received_qty_in_pack_unittype`),
  CONSTRAINT `purchase_items_fk` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_items_item_fk` FOREIGN KEY (`inv_item_id`) REFERENCES `vendor_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_items_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_items_order_pack_unit` FOREIGN KEY (`ordered_pack_unittype`) REFERENCES `inventory_item_unittype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `purchase_items_order_qty_in_pack_unit` FOREIGN KEY (`ordered_qty_in_pack_unittype`) REFERENCES `inventory_item_unittype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `purchase_items_received_pack_unit` FOREIGN KEY (`received_pack_unittype`) REFERENCES `inventory_item_unittype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `purchase_items_received_qty_in_pack_unit` FOREIGN KEY (`received_qty_in_pack_unittype`) REFERENCES `inventory_item_unittype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8 COMMENT='Items linked with a purchase made by a location';

/*Table structure for table `purchases` */

DROP TABLE IF EXISTS `purchases`;

CREATE TABLE `purchases` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `vendor_invoice_num` varchar(45) DEFAULT NULL,
  `status` enum('Shopping','Cancelled','Ordered','Completed','Shipped') NOT NULL,
  `po` varchar(32) NOT NULL,
  `terms` text NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax_total` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `applied_amount` decimal(10,2) NOT NULL,
  `comments` text NOT NULL,
  `shopping_datetime` datetime DEFAULT NULL,
  `shopping_employee_id` int(11) DEFAULT NULL,
  `lastchange_datetime` datetime DEFAULT NULL,
  `lastchange_employee_id` int(11) DEFAULT NULL,
  `order_datetime` datetime DEFAULT NULL,
  `order_employee_id` int(11) DEFAULT NULL,
  `completed_datetime` datetime DEFAULT NULL,
  `completed_employee_id` int(11) DEFAULT NULL,
  `cancelled_datetime` datetime DEFAULT NULL,
  `cancelled_employee_id` int(11) DEFAULT NULL,
  `payment_type` int(8) DEFAULT NULL,
  `delivery_method` int(8) DEFAULT NULL,
  `manual` enum('yes','no') DEFAULT 'no',
  `image` varchar(255) DEFAULT NULL,
  `chart_of_account` int(11) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchases_loc_fk` (`location_id`),
  KEY `purchases_fk` (`vendor_id`),
  CONSTRAINT `purchases_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchases_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COMMENT='General Purchase information for Vendor Purchases';

/*Table structure for table `purchases_payments` */

DROP TABLE IF EXISTS `purchases_payments`;

CREATE TABLE `purchases_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `vendor_id` int(8) NOT NULL,
  `employee_id` int(8) DEFAULT NULL,
  `datetime` datetime NOT NULL,
  `type` enum('Cash','Check','Visa','MC','AMEX','other','transfer') DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `reference` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `applied_amount` decimal(10,2) NOT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchases_payments_loc_fk` (`location_id`),
  KEY `purchases_payments_vendor_fk` (`vendor_id`),
  KEY `purchases_payments_emp_fk` (`employee_id`),
  CONSTRAINT `purchases_payments_emp_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `purchases_payments_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchases_payments_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Payments added to a purchase order for a location';

/*Table structure for table `purchases_payments_applied` */

DROP TABLE IF EXISTS `purchases_payments_applied`;

CREATE TABLE `purchases_payments_applied` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `vendor_id` int(8) NOT NULL,
  `payment_id` int(8) NOT NULL,
  `employee_id` int(8) NOT NULL,
  `datetime` datetime NOT NULL,
  `purchase_id` int(8) NOT NULL,
  `amount_applied` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `puchases_payments_applied_emp_idx` (`employee_id`),
  KEY `puchases_payments_applied_location_idx` (`location_id`),
  KEY `purchases_payments_applied_purchase_idx` (`purchase_id`),
  KEY `purchases_payments_applied_payment_idx` (`payment_id`),
  KEY `purchases_payments_applied_vendor_idx` (`vendor_id`),
  CONSTRAINT `purchases_payments_applied_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `purchases_payments_applied_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `purchases_payments_applied_payment` FOREIGN KEY (`payment_id`) REFERENCES `purchases_payments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `purchases_payments_applied_purchase` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `purchases_payments_applied_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Applied payments for for a purchase order for a location';

/*Table structure for table `rating_types` */

DROP TABLE IF EXISTS `rating_types`;

CREATE TABLE `rating_types` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rating_types_code_idx` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Global rating scale. Used for client reviews';

/*Table structure for table `report_fields` */

DROP TABLE IF EXISTS `report_fields`;

CREATE TABLE `report_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_field` varchar(250) DEFAULT NULL,
  `sizefield` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `title_field` varchar(50) NOT NULL,
  `align` varchar(50) NOT NULL DEFAULT 'left',
  PRIMARY KEY (`id`),
  KEY `reports_field_report_fk_idx` (`report_id`),
  CONSTRAINT `reports_field_report_fk` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detailed fields used in custom admin reports';

/*Table structure for table `reports` */

DROP TABLE IF EXISTS `reports`;

CREATE TABLE `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_report` varchar(50) DEFAULT NULL,
  `type_report` varchar(50) DEFAULT NULL,
  `table` varchar(255) NOT NULL,
  `page_report` varchar(255) DEFAULT NULL,
  `whereclause` varchar(255) DEFAULT NULL,
  `columwith_locationid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Custom reports created in AdminPanel';

/*Table structure for table `revel_log` */

DROP TABLE IF EXISTS `revel_log`;

CREATE TABLE `revel_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `elapsed_time` decimal(10,0) DEFAULT NULL,
  `status` enum('Processing','Successful','Failed') DEFAULT NULL,
  `reason` varchar(45) DEFAULT NULL,
  `request` text,
  `response` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `schools` */

DROP TABLE IF EXISTS `schools`;

CREATE TABLE `schools` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `School` varchar(64) NOT NULL,
  `Country` int(4) NOT NULL,
  `City` varchar(64) NOT NULL,
  `State` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `School` (`School`),
  KEY `country_schools_idx` (`Country`),
  KEY `country_state_idx` (`State`),
  CONSTRAINT `country_schools` FOREIGN KEY (`Country`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `country_state` FOREIGN KEY (`State`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of Schools. Used for consumer applications';

/*Table structure for table `softpoint_queue` */

DROP TABLE IF EXISTS `softpoint_queue`;

CREATE TABLE `softpoint_queue` (
  `queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Development','Testing','Move to Live','Completed','Cancelled','InProgress') NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` text,
  `folder` varchar(255) DEFAULT NULL,
  `priority` int(11) NOT NULL,
  `user_id_owner` int(8) DEFAULT NULL,
  `user_id_development` int(8) DEFAULT NULL,
  `user_id_development_date` datetime DEFAULT NULL,
  `user_id_testing` int(8) DEFAULT NULL,
  `user_id_testing_date` datetime DEFAULT NULL,
  `user_id_moved_to_live` int(8) DEFAULT NULL,
  `user_id_moved_to_live_date` datetime DEFAULT NULL,
  `user_id_completed` int(8) DEFAULT NULL,
  `user_id_completed_date` datetime DEFAULT NULL,
  `user_id_cancelled` int(8) DEFAULT NULL,
  `user_id_cancelled_date` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datatime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `inprogress_start` datetime DEFAULT NULL,
  `inprogress_end` datetime DEFAULT NULL,
  PRIMARY KEY (`queue_id`),
  UNIQUE KEY `queue_id_UNIQUE` (`queue_id`),
  KEY `softpoint_queue_status_idx` (`status`),
  KEY `softpoint_queue_test_stat_idx` (`user_id_testing`,`status`,`priority`),
  KEY `softpoint_queue_dev_stat_idx` (`user_id_development`,`status`,`priority`),
  KEY `softpoint_queue_usrowner_idx` (`user_id_owner`)
) ENGINE=InnoDB AUTO_INCREMENT=473 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `softpoint_queue_audit` */

DROP TABLE IF EXISTS `softpoint_queue_audit`;

CREATE TABLE `softpoint_queue_audit` (
  `queue_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `queue_id` int(11) DEFAULT NULL,
  `status` enum('Development','Testing','Move to Live','Completed','Cancelled','InProgress') DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `user_id_owner` int(8) DEFAULT NULL,
  `user_id_development` int(8) DEFAULT NULL,
  `user_id_development_date` datetime DEFAULT NULL,
  `user_id_testing` int(8) DEFAULT NULL,
  `user_id_testing_date` datetime DEFAULT NULL,
  `user_id_moved_to_live` int(8) DEFAULT NULL,
  `user_id_moved_to_live_date` datetime DEFAULT NULL,
  `user_id_completed` int(8) DEFAULT NULL,
  `user_id_completed_date` datetime DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datatime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`queue_audit_id`),
  UNIQUE KEY `queue_id_UNIQUE` (`queue_audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3927 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `spa_appointments` */

DROP TABLE IF EXISTS `spa_appointments`;

CREATE TABLE `spa_appointments` (
  `spa_appointments_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `client_id` int(11) NOT NULL,
  `status` enum('Reserved','Cancelled','Noshow','Completed') NOT NULL,
  `date` date NOT NULL,
  `time_prepare` time NOT NULL,
  `time` time NOT NULL,
  `end_time` time NOT NULL,
  `time_cleanup` time NOT NULL,
  `spa_service` int(8) NOT NULL,
  `spa_price` decimal(14,2) DEFAULT NULL,
  `notes` text,
  `room` int(8) DEFAULT NULL,
  `service_employee_id` int(11) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `Last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`spa_appointments_id`),
  KEY `spa_appointments_loc_fk_idx` (`location_id`),
  CONSTRAINT `spa_appointments_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Holds the client spa appointments for a location';

/*Table structure for table `spa_treatments` */

DROP TABLE IF EXISTS `spa_treatments`;

CREATE TABLE `spa_treatments` (
  `spa_treatment_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `priority` mediumint(8) NOT NULL,
  `code` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `sales_posting_code` int(11) DEFAULT NULL,
  `required_rooms` text,
  `required_advance_notice` varchar(8) DEFAULT NULL,
  `required_time_to_prepare` varchar(8) DEFAULT NULL,
  `treatment_lenght_of_time` varchar(8) DEFAULT NULL,
  `treatment_firstpart` varchar(8) DEFAULT NULL,
  `treatment_waitpart` varchar(8) DEFAULT NULL,
  `treatment_secondpart` varchar(8) DEFAULT NULL,
  `treatment_cleanup_time` varchar(8) DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`spa_treatment_id`),
  KEY `spa_treatments_loc_fk_idx` (`location_id`),
  CONSTRAINT `spa_treatments_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Holds the spa treatments offered at a location';

/*Table structure for table `states` */

DROP TABLE IF EXISTS `states`;

CREATE TABLE `states` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `country_id` int(4) NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `code` varchar(8) NOT NULL,
  `description` text NOT NULL,
  `status` enum('A','S') NOT NULL,
  `country_numcode` int(3) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `daylight_back_date` varchar(45) DEFAULT NULL,
  `daylight_forward_date` varchar(45) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `states_country_fk_idx` (`country_id`),
  KEY `states_name` (`name`),
  KEY `states_code_idx` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4496 DEFAULT CHARSET=latin1 COMMENT='Global States configured by country';

/*Table structure for table `stylist_clients_items` */

DROP TABLE IF EXISTS `stylist_clients_items`;

CREATE TABLE `stylist_clients_items` (
  `stylist_client_items_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `status` enum('Not viewed','Viewed','Liked It','Disliked It','Hold','Buy','Own','Trade') NOT NULL,
  `item_source_type` enum('Employee Master','Client') NOT NULL,
  `stylist_items_id` int(11) NOT NULL,
  `employee_master_id_sent` int(11) DEFAULT NULL,
  `employee_master_send_datetime` datetime DEFAULT NULL,
  `client_view_datetime` datetime DEFAULT NULL,
  `client_status_change_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`stylist_client_items_id`),
  KEY `stylist_clients_items_client_idx` (`client_id`),
  KEY `stylist_clients_items_empmaster_idx` (`employee_master_id_sent`),
  KEY `stylist_clients_items_item_idx` (`stylist_items_id`),
  CONSTRAINT `stylist_clients_items_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `stylist_clients_items_empmaster` FOREIGN KEY (`employee_master_id_sent`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `stylist_clients_items_item` FOREIGN KEY (`stylist_items_id`) REFERENCES `stylist_items` (`stylist_items_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `stylist_items` */

DROP TABLE IF EXISTS `stylist_items`;

CREATE TABLE `stylist_items` (
  `stylist_items_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Active','Inactive') NOT NULL,
  `item_shortname` varchar(45) NOT NULL,
  `entered_by` enum('Location','Employee Master','Client') NOT NULL,
  `employee_master_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `location_employee_id` int(11) DEFAULT NULL,
  `location_item_id` int(11) DEFAULT NULL,
  `item_types` mediumtext NOT NULL,
  `Description` mediumtext,
  `sku` varchar(45) NOT NULL,
  `barcode` varchar(45) DEFAULT NULL,
  `qrcode` mediumtext,
  `manufacturer` varchar(45) DEFAULT NULL,
  `designer` varchar(45) DEFAULT NULL,
  `sex` varchar(45) DEFAULT 'Multiple; Men, Women or Unisex',
  `sizes` varchar(45) DEFAULT NULL,
  `sizes_type` varchar(45) DEFAULT NULL,
  `located` varchar(45) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`stylist_items_id`),
  KEY `stylist_items_loc_fk_idx` (`location_id`),
  CONSTRAINT `stylist_items_loc_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `stylist_items_images` */

DROP TABLE IF EXISTS `stylist_items_images`;

CREATE TABLE `stylist_items_images` (
  `stylist_items_images_id` int(11) NOT NULL AUTO_INCREMENT,
  `stylist_items_id` int(11) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `name` varchar(64) NOT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `priority` int(4) NOT NULL,
  `path` mediumtext NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`stylist_items_images_id`),
  KEY `stylist_items_images_item_idx` (`stylist_items_id`),
  CONSTRAINT `stylist_items_images_item` FOREIGN KEY (`stylist_items_id`) REFERENCES `stylist_items` (`stylist_items_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `stylist_items_types` */

DROP TABLE IF EXISTS `stylist_items_types`;

CREATE TABLE `stylist_items_types` (
  `stylist_items_types_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`stylist_items_types_id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;

/*Table structure for table `training_employee_lessons` */

DROP TABLE IF EXISTS `training_employee_lessons`;

CREATE TABLE `training_employee_lessons` (
  `tel_id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `employee_id` int(8) NOT NULL,
  `author_type` enum('Location','Corporate','Employee','Admin','Team') NOT NULL,
  `author_id` int(10) NOT NULL COMMENT 'Employee_Master ID#',
  `product_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `lesson_priority` int(11) NOT NULL,
  `lesson_req` enum('Yes','No') NOT NULL DEFAULT 'No',
  `lesson_started_datetime` datetime NOT NULL,
  `lesson_ended_datetime` datetime NOT NULL,
  `lesson_video_score` int(11) NOT NULL,
  `lesson_pass` enum('Yes','No') NOT NULL,
  `lesson_valid_period` int(11) NOT NULL,
  `lesson_taken_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`tel_id`),
  KEY `training_emp_lessons_emp_idx` (`employee_id`),
  KEY `training_emp_lessons_loc_idx` (`location_id`),
  KEY `training_emp_lessons_lesson_idx` (`lesson_id`),
  KEY `training_emp_lessons_product_idx` (`product_id`),
  CONSTRAINT `training_emp_lessons_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_emp_lessons_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `training_lessons` (`lesson_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_emp_lessons_loc` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_emp_lessons_product` FOREIGN KEY (`product_id`) REFERENCES `training_products` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=latin1 COMMENT='Lessons linked with an employee or emp master';

/*Table structure for table `training_employee_transactions` */

DROP TABLE IF EXISTS `training_employee_transactions`;

CREATE TABLE `training_employee_transactions` (
  `tet_id` int(10) NOT NULL AUTO_INCREMENT,
  `purchase_author_id` int(10) NOT NULL,
  `lesson_id` int(10) NOT NULL,
  `owner_author_id` int(10) NOT NULL,
  `price` float(10,2) NOT NULL,
  `purchase_datetime` datetime NOT NULL,
  `purchase_author_type` enum('Location','Corporate','Employee','Admin','Team') NOT NULL,
  `owner_author_type` enum('Location','Corporate','Employee','Admin','Team') NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`tet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Transactions made to purchase lessons';

/*Table structure for table `training_employee_video_quiz` */

DROP TABLE IF EXISTS `training_employee_video_quiz`;

CREATE TABLE `training_employee_video_quiz` (
  `tvq_id` int(11) NOT NULL AUTO_INCREMENT,
  `tvq_emp_id` int(11) NOT NULL,
  `tvq_emp_master_id` int(11) DEFAULT NULL,
  `tvq_lesson_id` int(11) NOT NULL,
  `tvq_video_id` int(11) NOT NULL,
  `tvq_video_question_id` int(11) NOT NULL,
  `tvq_answer` text NOT NULL,
  `tvq_datetime` datetime NOT NULL,
  PRIMARY KEY (`tvq_id`),
  KEY `training_emp_vid_quiz_emp_idx` (`tvq_emp_id`),
  KEY `training_emp_vid_quiz_empmaster_idx` (`tvq_emp_master_id`),
  KEY `training_emp_vid_quiz_lesson_idx` (`tvq_lesson_id`),
  KEY `training_emp_vid_quiz_vid_question_idx` (`tvq_video_question_id`),
  KEY `training_emp_vid_quiz_video_idx` (`tvq_video_id`),
  CONSTRAINT `training_emp_vid_quiz_emp` FOREIGN KEY (`tvq_emp_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_emp_vid_quiz_empmaster` FOREIGN KEY (`tvq_emp_master_id`) REFERENCES `employees_master` (`empmaster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_emp_vid_quiz_lesson` FOREIGN KEY (`tvq_lesson_id`) REFERENCES `training_lessons` (`lesson_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_emp_vid_quiz_vid_question` FOREIGN KEY (`tvq_video_question_id`) REFERENCES `training_video_questions` (`question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_emp_vid_quiz_video` FOREIGN KEY (`tvq_video_id`) REFERENCES `training_videos` (`video_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 COMMENT='Individual question results for a video quiz taken';

/*Table structure for table `training_employee_videos` */

DROP TABLE IF EXISTS `training_employee_videos`;

CREATE TABLE `training_employee_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(8) NOT NULL,
  `employee_id` int(8) NOT NULL COMMENT 'empmaster_id of employee who took quiz',
  `lesson_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `video_req` enum('No','Yes') NOT NULL,
  `video_priority` int(11) NOT NULL,
  `video_views` int(11) NOT NULL,
  `video_test_datetime` datetime NOT NULL,
  `video_score` int(11) NOT NULL,
  `video_pass` enum('Yes','No') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `training_employee_videos_lesson_fk` (`lesson_id`),
  KEY `training_employee_videos_video_fk` (`video_id`),
  KEY `training_employee_videos_location_idx` (`location_id`),
  KEY `training_employee_videos_employee_idx` (`employee_id`),
  CONSTRAINT `training_employee_videos_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_employee_videos_lesson_fk` FOREIGN KEY (`lesson_id`) REFERENCES `training_lessons` (`lesson_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `training_employee_videos_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_employee_videos_video_fk` FOREIGN KEY (`video_id`) REFERENCES `training_videos` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=latin1 COMMENT='Videos linked with an employee or emp master';

/*Table structure for table `training_lesson_videos` */

DROP TABLE IF EXISTS `training_lesson_videos`;

CREATE TABLE `training_lesson_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `video_req_to_continue_lesson` enum('Yes','No') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `training_lesson_videos_lesson_fk` (`lesson_id`),
  KEY `training_lesson_videos_video_fk` (`video_id`),
  CONSTRAINT `training_lesson_videos_lesson_fk` FOREIGN KEY (`lesson_id`) REFERENCES `training_lessons` (`lesson_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `training_lesson_videos_video_fk` FOREIGN KEY (`video_id`) REFERENCES `training_videos` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=latin1 COMMENT='Videos linked to a LearnTube lesson';

/*Table structure for table `training_lessons` */

DROP TABLE IF EXISTS `training_lessons`;

CREATE TABLE `training_lessons` (
  `lesson_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('active','pending','inactive') NOT NULL,
  `name` varchar(64) NOT NULL,
  `author_type` enum('Location','Corporate','Employee','Admin','Team') NOT NULL,
  `author` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `employee_id` int(10) NOT NULL,
  `module` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `lesson_image` varchar(200) NOT NULL COMMENT 'Lesson Image',
  `lesson_descr` text NOT NULL COMMENT 'Lesson Description',
  `lesson_count` int(10) NOT NULL COMMENT 'Most viewed counter',
  `public` enum('yes','no') NOT NULL,
  `price` float(10,2) NOT NULL,
  `purchased_on` datetime DEFAULT NULL,
  `valid_period` int(11) NOT NULL,
  `currency_id` int(8) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`lesson_id`),
  KEY `training_lessons_products_fk` (`product`),
  KEY `training_lessons_author_fk` (`author`),
  KEY `training_lessons_currency_idx` (`currency_id`),
  KEY `training_lessons_group_idx` (`group`),
  KEY `training_lessons_type_idx` (`type`),
  CONSTRAINT `training_lessons_currency` FOREIGN KEY (`currency_id`) REFERENCES `global_currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_lessons_group` FOREIGN KEY (`group`) REFERENCES `training_video_groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_lessons_products_fk` FOREIGN KEY (`product`) REFERENCES `training_products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `training_lessons_type` FOREIGN KEY (`type`) REFERENCES `training_video_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=latin1 COMMENT='General Lesson information for a LearnTube lesson';

/*Table structure for table `training_products` */

DROP TABLE IF EXISTS `training_products`;

CREATE TABLE `training_products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product` varchar(32) NOT NULL,
  `author_type` enum('Location','Corporate','Employee','Admin','Team') NOT NULL,
  `author_id` int(10) NOT NULL COMMENT 'employee_master ID#',
  `module` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `type` enum('Browser','App','Mobile Browser') NOT NULL,
  `vendor` varchar(255) NOT NULL,
  PRIMARY KEY (`product_id`),
  KEY `training_products_author_fk` (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1 COMMENT='Product categorization for LearnTube lessons';

/*Table structure for table `training_products_lessons` */

DROP TABLE IF EXISTS `training_products_lessons`;

CREATE TABLE `training_products_lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `training_product_lessons_product_idx` (`product_id`),
  KEY `training_product_lesson_lesson_idx` (`lesson_id`),
  CONSTRAINT `training_product_lesson_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `training_lessons` (`lesson_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_product_lessons_product` FOREIGN KEY (`product_id`) REFERENCES `training_products` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1 COMMENT='Lessons directly linked to a product';

/*Table structure for table `training_video_groups` */

DROP TABLE IF EXISTS `training_video_groups`;

CREATE TABLE `training_video_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(32) DEFAULT NULL,
  `Priority` int(4) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COMMENT='Group categories for LearnTube lessons';

/*Table structure for table `training_video_questions` */

DROP TABLE IF EXISTS `training_video_questions`;

CREATE TABLE `training_video_questions` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL,
  `multiple_choice` enum('MC','TF','INPUT') NOT NULL DEFAULT 'MC',
  `correct_answer` varchar(255) NOT NULL,
  `wrong_answer1` varchar(255) NOT NULL,
  `wrong_answer2` varchar(255) NOT NULL,
  `wrong_answer3` varchar(255) NOT NULL,
  `wrong_answer4` varchar(255) NOT NULL,
  PRIMARY KEY (`question_id`),
  KEY `training_video_questions_video_fk` (`video_id`),
  CONSTRAINT `training_video_questions_video_fk` FOREIGN KEY (`video_id`) REFERENCES `training_videos` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=latin1 COMMENT='Questions linked with a LearnTube lesson';

/*Table structure for table `training_video_questions_emp` */

DROP TABLE IF EXISTS `training_video_questions_emp`;

CREATE TABLE `training_video_questions_emp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL,
  `multiple_choice` enum('Yes','MC','TF','INPUT') NOT NULL DEFAULT 'MC',
  `correct_answer` varchar(255) NOT NULL,
  `wrong_answer1` varchar(255) NOT NULL,
  `wrong_answer2` varchar(255) NOT NULL,
  `wrong_answer3` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `training_video_questions_emp_emp_idx` (`emp_id`),
  KEY `training_video_questions_emp_video_idx` (`video_id`),
  KEY `training_video_questions_emp_question_idx` (`question_id`),
  CONSTRAINT `training_video_questions_emp_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_video_questions_emp_question` FOREIGN KEY (`question_id`) REFERENCES `training_video_questions` (`question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_video_questions_emp_video` FOREIGN KEY (`video_id`) REFERENCES `training_videos` (`video_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `training_video_types` */

DROP TABLE IF EXISTS `training_video_types`;

CREATE TABLE `training_video_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `types` varchar(32) NOT NULL,
  `priority` int(11) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `training_video_types_group_fk` (`group_id`),
  CONSTRAINT `training_video_types_group_fk` FOREIGN KEY (`group_id`) REFERENCES `training_video_groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=latin1 COMMENT='Type categorizatiosn for LearnTube lessons';

/*Table structure for table `training_videos` */

DROP TABLE IF EXISTS `training_videos`;

CREATE TABLE `training_videos` (
  `video_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('active','pending','inactive') NOT NULL,
  `name` varchar(64) NOT NULL,
  `group` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `author_type` enum('Location','Corporate','Employee','Admin','Team') NOT NULL,
  `author_id` int(10) NOT NULL COMMENT 'Employee_Master ID#',
  `product` int(11) NOT NULL,
  `module` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_small` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `video_youtube` text NOT NULL,
  `test_required` enum('No','Yes') NOT NULL DEFAULT 'No',
  `num_questions_display` int(11) NOT NULL,
  `req_num_correct_quesitons` int(11) NOT NULL,
  `question_priority` enum('Random','Manual') NOT NULL DEFAULT 'Random',
  `created_datetime` datetime NOT NULL,
  `training_videos` enum('Location','Corporate','Employee','Admin','Team') NOT NULL,
  PRIMARY KEY (`video_id`),
  KEY `training_videos_group_fk` (`group`),
  KEY `training_videos_type_fk` (`type`),
  KEY `training_videos_author_fk` (`author_id`),
  KEY `training_videos_product_fk` (`product`),
  CONSTRAINT `training_videos_group_fk` FOREIGN KEY (`group`) REFERENCES `training_video_groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_videos_product` FOREIGN KEY (`product`) REFERENCES `training_products` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `training_videos_type_fk` FOREIGN KEY (`type`) REFERENCES `training_video_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=latin1 COMMENT='Videos added as part of LearnTube';

/*Table structure for table `translate` */

DROP TABLE IF EXISTS `translate`;

CREATE TABLE `translate` (
  `Translate_id` int(11) NOT NULL AUTO_INCREMENT,
  `Word_on_page` text NOT NULL,
  `French` text,
  `Spanish` text,
  `Dutch` text,
  `Japanese` text,
  `Arabic` text,
  `Portuguese` text,
  `Italian` text,
  `Russian` text,
  `Korean` text,
  `Greek` text,
  `German` text,
  `Hindi` text,
  `Norwegian` text,
  `Finnish` text,
  `Swedish` text,
  `Chinese` text,
  PRIMARY KEY (`Translate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1797 DEFAULT CHARSET=utf8 COMMENT='Hold all custom translations done by SoftPoint';

/*Table structure for table `urls` */

DROP TABLE IF EXISTS `urls`;

CREATE TABLE `urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(45) DEFAULT NULL,
  `url_type` varchar(45) DEFAULT NULL,
  `ticket_id` varchar(45) DEFAULT NULL,
  `payment_id` varchar(45) DEFAULT NULL,
  `url` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36322 DEFAULT CHARSET=latin1;

/*Table structure for table `user_logs` */

DROP TABLE IF EXISTS `user_logs`;

CREATE TABLE `user_logs` (
  `user_logs_id` int(15) NOT NULL AUTO_INCREMENT,
  `user_id` int(8) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `status` enum('Signed In','Signed Out','Sign In Failure') NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(20) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`user_logs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44691 DEFAULT CHARSET=latin1 COMMENT='Holds log information for admin users';

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `location` int(11) DEFAULT NULL,
  `type` enum('Direct','Reseller') NOT NULL,
  `company` int(8) DEFAULT NULL,
  `title` varchar(64) NOT NULL,
  `state` int(4) DEFAULT NULL,
  `sales_territory` varchar(45) NOT NULL,
  `linkedin` text NOT NULL,
  `access_location` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_corporate` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_companies` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_menus` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_orders` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_sales` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_expensetab` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_events` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_quality` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_quick` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_concierge` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_client` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_employee` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_training` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_vendor` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_inventory` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_global` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_utilities` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_admin` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_internal` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_hotel` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_crs` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_user_support` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_user_sales` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_sales_level` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_user_billing` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_advertisement` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_user_reseller` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_user_inventory` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_user_crs` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_user_setup` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_user_datapoint` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_user_learntube` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_user_business_intelligence` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_clients_management` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_location_operations` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_api` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_setup_api` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_setup_backoffice` enum('Yes','No') NOT NULL DEFAULT 'No',
  `access_setup_delivery` enum('Yes','No') NOT NULL DEFAULT 'No',
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_company_idk` (`company`),
  KEY `users_email_idx` (`email`),
  KEY `users_user_idx` (`user`,`email`),
  KEY `users_stat_idx` (`status`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=latin1 COMMENT='General account information for admin users';

/*Table structure for table `users_audit` */

DROP TABLE IF EXISTS `users_audit`;

CREATE TABLE `users_audit` (
  `users_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(8) DEFAULT NULL,
  `user` varchar(32) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `location` int(11) DEFAULT NULL,
  `type` enum('Direct','Reseller') DEFAULT NULL,
  `company` int(8) DEFAULT NULL,
  `title` varchar(64) DEFAULT NULL,
  `state` int(4) DEFAULT NULL,
  `sales_territory` varchar(45) DEFAULT NULL,
  `linkedin` text,
  `access_location` enum('Yes','No') DEFAULT NULL,
  `access_corporate` enum('Yes','No') DEFAULT NULL,
  `access_companies` enum('Yes','No') DEFAULT NULL,
  `access_menus` enum('Yes','No') DEFAULT NULL,
  `access_orders` enum('Yes','No') DEFAULT NULL,
  `access_sales` enum('Yes','No') DEFAULT NULL,
  `access_expensetab` enum('Yes','No') DEFAULT NULL,
  `access_events` enum('Yes','No') DEFAULT NULL,
  `access_quality` enum('Yes','No') DEFAULT NULL,
  `access_quick` enum('Yes','No') DEFAULT NULL,
  `access_concierge` enum('Yes','No') DEFAULT NULL,
  `access_client` enum('Yes','No') DEFAULT NULL,
  `access_employee` enum('Yes','No') DEFAULT NULL,
  `access_training` enum('Yes','No') DEFAULT NULL,
  `access_vendor` enum('Yes','No') DEFAULT NULL,
  `access_inventory` enum('Yes','No') DEFAULT NULL,
  `access_global` enum('Yes','No') DEFAULT NULL,
  `access_utilities` enum('Yes','No') DEFAULT NULL,
  `access_admin` enum('Yes','No') DEFAULT NULL,
  `access_internal` enum('Yes','No') DEFAULT NULL,
  `access_hotel` enum('Yes','No') DEFAULT NULL,
  `access_crs` enum('Yes','No') DEFAULT NULL,
  `access_user_support` enum('Yes','No') DEFAULT NULL,
  `access_user_sales` enum('Yes','No') DEFAULT NULL,
  `access_sales_level` enum('Yes','No') DEFAULT NULL,
  `access_user_billing` enum('Yes','No') DEFAULT NULL,
  `access_advertisement` enum('Yes','No') DEFAULT NULL,
  `access_user_reseller` enum('Yes','No') DEFAULT NULL,
  `access_user_inventory` enum('Yes','No') DEFAULT NULL,
  `access_user_crs` enum('Yes','No') DEFAULT NULL,
  `access_user_setup` enum('Yes','No') DEFAULT NULL,
  `access_user_datapoint` enum('Yes','No') DEFAULT NULL,
  `access_user_learntube` enum('Yes','No') DEFAULT NULL,
  `access_user_business_intelligence` enum('Yes','No') DEFAULT NULL,
  `access_clients_management` enum('Yes','No') DEFAULT NULL,
  `access_location_operations` enum('Yes','No') DEFAULT NULL,
  `access_api` enum('Yes','No') DEFAULT NULL,
  `access_setup_api` enum('Yes','No') DEFAULT NULL,
  `access_setup_backoffice` enum('Yes','No') DEFAULT NULL,
  `access_setup_delivery` enum('Yes','No') DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`users_audit_id`),
  KEY `users_audit_lastdatetime_idx` (`last_datetime`),
  KEY `users_audit_id_idx` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1322 DEFAULT CHARSET=latin1 COMMENT='Records and changes made to an admin account';

/*Table structure for table `vendor_bays` */

DROP TABLE IF EXISTS `vendor_bays`;

CREATE TABLE `vendor_bays` (
  `vendor_bays_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `bay_code` varchar(8) NOT NULL,
  `bay_name` varchar(32) NOT NULL,
  `bay_description` varchar(64) DEFAULT NULL,
  `bay_size` varchar(64) NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` varchar(45) NOT NULL,
  PRIMARY KEY (`vendor_bays_id`),
  UNIQUE KEY `vendor_bays_id_UNIQUE` (`vendor_bays_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Table structure for table `vendor_distribution` */

DROP TABLE IF EXISTS `vendor_distribution`;

CREATE TABLE `vendor_distribution` (
  `vendors_distribution_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(8) NOT NULL,
  `distribution_date` date NOT NULL,
  `bay_id` int(11) DEFAULT NULL,
  `captain` varchar(45) DEFAULT NULL,
  `puller` varchar(45) DEFAULT NULL,
  `driver` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`vendors_distribution_id`),
  UNIQUE KEY `vendors_distribution_id_UNIQUE` (`vendors_distribution_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1597 DEFAULT CHARSET=latin1;

/*Table structure for table `vendor_distribution_routes` */

DROP TABLE IF EXISTS `vendor_distribution_routes`;

CREATE TABLE `vendor_distribution_routes` (
  `vendor_distribution_routes_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_distribution_id` int(11) NOT NULL,
  `routes` int(8) NOT NULL,
  `vehicle` int(8) NOT NULL,
  `route_time` varchar(45) DEFAULT NULL,
  `load_time` varchar(25) DEFAULT NULL,
  `time_out` varchar(25) DEFAULT NULL,
  `cartons` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`vendor_distribution_routes_id`),
  UNIQUE KEY `vendor_distribution_routes_id_UNIQUE` (`vendor_distribution_routes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1574 DEFAULT CHARSET=latin1;

/*Table structure for table `vendor_items` */

DROP TABLE IF EXISTS `vendor_items`;

CREATE TABLE `vendor_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(8) NOT NULL,
  `inv_item_id` int(8) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `vendor_internal_number` int(11) NOT NULL,
  `description` text,
  `notes` text,
  `pack_unittype` int(8) NOT NULL,
  `qty_in_pack` int(8) DEFAULT NULL,
  `qty_in_pack_unittype` int(8) DEFAULT NULL,
  `qty_in_pack_size` varchar(32) DEFAULT NULL,
  `price_by_weight` enum('Yes','No') DEFAULT NULL,
  `price_by_weight_unittype` int(8) DEFAULT NULL,
  `price` decimal(14,2) NOT NULL,
  `promotion` varchar(64) NOT NULL,
  `promotion_price` decimal(14,2) NOT NULL,
  `purchased_from_vendor` int(11) DEFAULT NULL,
  `purchased_price` decimal(14,2) DEFAULT NULL,
  `purchased_last` date DEFAULT NULL,
  `pack_size` varchar(32) NOT NULL,
  `pack_weight` varchar(32) DEFAULT NULL,
  `splitable` enum('Yes','No') DEFAULT 'No',
  `splitable_price` decimal(14,2) DEFAULT NULL,
  `splits` decimal(4,2) DEFAULT NULL,
  `splits_minimum` int(4) DEFAULT NULL,
  `taxable` enum('Yes','No') DEFAULT NULL,
  `tax_percentage` decimal(5,2) NOT NULL,
  `tax_type` enum('Additional','VAT') DEFAULT 'Additional',
  `tax_amount` decimal(14,2) DEFAULT NULL,
  `stock` enum('Yes','No') DEFAULT NULL,
  `lead_time` int(3) DEFAULT NULL,
  `manufacturer` varchar(64) DEFAULT NULL,
  `manufacturer_barcode` varchar(64) DEFAULT NULL,
  `model_number` varchar(64) DEFAULT NULL,
  `brand` varchar(64) DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_items_fk` (`vendor_id`),
  KEY `vendor_items_item_fk` (`inv_item_id`),
  KEY `vendor_items_pack_unit_idx` (`pack_unittype`),
  CONSTRAINT `vendor_items_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_items_item_fk` FOREIGN KEY (`inv_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1795 DEFAULT CHARSET=utf8 COMMENT='Items linked to a vendor';

/*Table structure for table `vendor_items_inventory_counts` */

DROP TABLE IF EXISTS `vendor_items_inventory_counts`;

CREATE TABLE `vendor_items_inventory_counts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(8) NOT NULL,
  `inv_item_id` int(8) NOT NULL,
  `pack_unittype` int(8) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `date_counted` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_items_inventory_counts_vendor_fk` (`vendor_id`),
  KEY `vendor_items_inventory_counts_fk` (`inv_item_id`),
  CONSTRAINT `vendor_items_inventory_counts_fk` FOREIGN KEY (`inv_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `vendor_items_inventory_counts_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=227 DEFAULT CHARSET=utf8 COMMENT='Inventory counts made by a vendor for their stock';

/*Table structure for table `vendor_locations` */

DROP TABLE IF EXISTS `vendor_locations`;

CREATE TABLE `vendor_locations` (
  `vendor_locations_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(8) NOT NULL,
  `location_id` int(8) NOT NULL,
  `sales_variance` decimal(5,2) DEFAULT NULL,
  `default_delivery_type` varchar(255) DEFAULT NULL,
  `default_terms` varchar(255) DEFAULT NULL,
  `default_payment_type` varchar(255) DEFAULT NULL,
  `primary_contact_employee_id` varchar(45) DEFAULT NULL,
  `primary_contact` varchar(45) DEFAULT NULL,
  `primary_contact_email` varchar(45) DEFAULT NULL,
  `primary_contact_phone` varchar(45) DEFAULT NULL,
  `notes` text,
  `reminder` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`vendor_locations_id`),
  KEY `vendor_locations_location_id_fk_idx` (`location_id`),
  CONSTRAINT `vendor_locations_location_id_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Table structure for table `vendor_purchases` */

DROP TABLE IF EXISTS `vendor_purchases`;

CREATE TABLE `vendor_purchases` (
  `vendor_purchases_id` int(8) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(8) NOT NULL,
  `buying_vendor_id` int(8) NOT NULL,
  `vendor_invoice_num` varchar(45) DEFAULT NULL,
  `status` enum('Shopping','Cancelled','Ordered','Completed','Shipped') NOT NULL,
  `buying_vendor_purchase_order` varchar(32) NOT NULL,
  `terms` text NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax_total` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `applied_amount` decimal(10,2) NOT NULL,
  `comments` text NOT NULL,
  `shopping_datetime` datetime DEFAULT NULL,
  `shopping_employee_id` int(11) DEFAULT NULL,
  `lastchange_datetime` datetime DEFAULT NULL,
  `lastchange_employee_id` int(11) DEFAULT NULL,
  `order_datetime` datetime DEFAULT NULL,
  `order_employee_id` int(11) DEFAULT NULL,
  `completed_datetime` datetime DEFAULT NULL,
  `completed_employee_id` int(11) DEFAULT NULL,
  `cancelled_datetime` datetime DEFAULT NULL,
  `cancelled_employee_id` int(11) DEFAULT NULL,
  `payment_type` int(8) DEFAULT NULL,
  `delivery_method` int(8) DEFAULT NULL,
  `manual` enum('yes','no') DEFAULT 'no',
  `image` varchar(255) DEFAULT NULL,
  `chart_of_account` int(11) DEFAULT NULL,
  `created_on` varchar(45) DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`vendor_purchases_id`),
  KEY `vendor_purchases_fk` (`vendor_id`),
  CONSTRAINT `vendor_purchases_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=267 DEFAULT CHARSET=utf8 COMMENT='General Purchase information for Vendor to Vendor Purchases';

/*Table structure for table `vendor_purchases_items` */

DROP TABLE IF EXISTS `vendor_purchases_items`;

CREATE TABLE `vendor_purchases_items` (
  `vendor_purchases_items_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_purchases_id` int(8) NOT NULL,
  `inv_item_id` int(8) NOT NULL COMMENT 'Foregin key to vendor_items.id\n\nIt is NOT inventory_item.id!',
  `vendor_id` int(10) NOT NULL,
  `buying_vendor_id` int(10) NOT NULL,
  `ordered_quantity` decimal(10,2) NOT NULL,
  `ordered_pack_size` int(11) NOT NULL,
  `ordered_pack_unittype` int(11) NOT NULL,
  `ordered_qty_in_pack` int(11) DEFAULT NULL,
  `ordered_qty_in_pack_unittype` int(11) DEFAULT NULL,
  `ordered_price` decimal(10,2) NOT NULL,
  `ordered_tax_percentage` decimal(10,2) NOT NULL,
  `received` enum('yes','no') NOT NULL DEFAULT 'no',
  `received_quantity` decimal(10,2) DEFAULT NULL,
  `received_pack_size` int(11) DEFAULT NULL,
  `received_pack_unittype` int(11) DEFAULT NULL,
  `received_qty_in_pack` int(11) DEFAULT NULL,
  `received_qty_in_pack_unittype` int(11) DEFAULT NULL,
  `received_price` decimal(10,2) DEFAULT NULL,
  `received_tax_percentage` decimal(10,2) DEFAULT NULL,
  `shipped` enum('yes','no') DEFAULT 'no',
  `shipped_quantity` decimal(10,2) DEFAULT NULL,
  `shipped_pack_size` int(11) DEFAULT NULL,
  `shipped_pack_unittype` int(11) DEFAULT NULL,
  `shipped_qty_in_pack` int(11) DEFAULT NULL,
  `shipped_qty_in_pack_unittype` int(11) DEFAULT NULL,
  `shipped_price` decimal(10,2) DEFAULT NULL,
  `shipped_tax_percentage` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`vendor_purchases_items_id`),
  KEY `purchase_items_fk` (`vendor_purchases_id`),
  CONSTRAINT `vendor_purchases_items_fk` FOREIGN KEY (`vendor_purchases_id`) REFERENCES `vendor_purchases` (`vendor_purchases_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=426 DEFAULT CHARSET=utf8 COMMENT='Items linked with a purchase made by a vendor';

/*Table structure for table `vendor_routes` */

DROP TABLE IF EXISTS `vendor_routes`;

CREATE TABLE `vendor_routes` (
  `vendor_routes_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `route_code` varchar(8) NOT NULL,
  `route_name` varchar(32) NOT NULL,
  `route_description` varchar(64) DEFAULT NULL,
  `route_estimated_time` varchar(32) DEFAULT NULL,
  `route_distance` varchar(16) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`vendor_routes_id`),
  UNIQUE KEY `vendor_routes_id_UNIQUE` (`vendor_routes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=big5;

/*Table structure for table `vendor_routes_locations` */

DROP TABLE IF EXISTS `vendor_routes_locations`;

CREATE TABLE `vendor_routes_locations` (
  `vendor_routes_locations_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `priority` int(3) NOT NULL,
  `location_id` int(8) NOT NULL,
  `est_time_from_previous` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`vendor_routes_locations_id`),
  UNIQUE KEY `vendor_routes_locations_id_UNIQUE` (`vendor_routes_locations_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

/*Table structure for table `vendor_vehicles` */

DROP TABLE IF EXISTS `vendor_vehicles`;

CREATE TABLE `vendor_vehicles` (
  `vendor_vehicles_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(8) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `vehicle_code` varchar(8) NOT NULL,
  `vehicle_name` varchar(32) NOT NULL,
  `vehicle_description` varchar(64) DEFAULT NULL,
  `vehicle_size` varchar(64) DEFAULT NULL,
  `vehicle_weight` varchar(64) DEFAULT NULL,
  `vehicle_range` varchar(32) DEFAULT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`vendor_vehicles_id`),
  UNIQUE KEY `vendor_vehicles_id_UNIQUE` (`vendor_vehicles_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

/*Table structure for table `vendors` */

DROP TABLE IF EXISTS `vendors`;

CREATE TABLE `vendors` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `status` enum('active','inactive','suspended','not_registered') NOT NULL,
  `name` varchar(64) NOT NULL,
  `location_link` int(8) DEFAULT NULL,
  `StorePoint_image` longtext,
  `contact` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(32) NOT NULL,
  `address` varchar(64) NOT NULL,
  `address2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `state` int(4) DEFAULT NULL,
  `zip` varchar(16) NOT NULL,
  `country` int(4) DEFAULT NULL,
  `phone` varchar(32) NOT NULL,
  `fax` varchar(18) NOT NULL,
  `website` varchar(64) NOT NULL,
  `storepoint_website_name` varchar(64) DEFAULT NULL,
  `longitude` varchar(12) NOT NULL,
  `latutide` varchar(12) NOT NULL,
  `currency_id` int(8) DEFAULT NULL,
  `description` longtext,
  `type` text,
  `payment_types` text,
  `delivery_types` text,
  `terms_types` text,
  `created_by` varchar(45) NOT NULL,
  `created_on` varchar(45) NOT NULL,
  `created_date` datetime NOT NULL,
  `last_by` varchar(45) DEFAULT NULL,
  `last_on` varchar(45) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_currency_idx` (`currency_id`),
  KEY `vendor_country_idx` (`country`),
  KEY `vendor_state_idx` (`state`),
  CONSTRAINT `vendor_country` FOREIGN KEY (`country`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `vendor_currency` FOREIGN KEY (`currency_id`) REFERENCES `global_currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `vendor_state` FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=521 DEFAULT CHARSET=latin1 COMMENT='General account information for vendors';
