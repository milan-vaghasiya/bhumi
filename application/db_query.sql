INSERT INTO `sub_menu_master` (`id`, `sub_menu_seq`, `sub_menu_icon`, `sub_menu_name`, `sub_controller_name`, `menu_id`, `is_report`, `is_approve_req`, `is_system`, `report_id`, `notify_on`, `vou_name_long`, `vou_name_short`, `auto_start_no`, `vou_prefix`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_delete`, `cm_id`) VALUES (NULL, '8', 'icon-Record', 'Select Option', 'selectOption', '1', '0', '0', '0', NULL, '0,0,0', NULL, NULL, '0', NULL, '0', '2021-06-23 15:40:25', '0', '2021-06-23 15:40:25', '0', '0')



DELETE FROM `sub_menu_master` WHERE `sub_menu_master`.`id` = 48

UPDATE `menu_master` SET `menu_name` = 'Users' WHERE `menu_master`.`id` = 2
UPDATE `sub_menu_master` SET `sub_menu_name` = 'Users' WHERE `sub_menu_master`.`id` = 6
UPDATE `sub_menu_master` SET `is_delete` = '1' WHERE `sub_menu_master`.`id` = 52;


ALTER TABLE `so_trans` ADD `order_unit` TINYINT NOT NULL DEFAULT '0' COMMENT '0 = Default,1=Primary,2=Master Packing' AFTER `item_id`;
UPDATE `sub_menu_master` SET `is_delete` = '1' WHERE `sub_menu_master`.`id` = 46;
ALTER TABLE `se_trans` ADD `order_unit` TINYINT NOT NULL DEFAULT '0' COMMENT '0 = Default,1=Primary,2=Master Packing' AFTER `item_id`;


INSERT INTO `sub_menu_master` (`id`, `sub_menu_seq`, `sub_menu_icon`, `sub_menu_name`, `sub_controller_name`, `menu_id`, `is_report`, `is_approve_req`, `is_system`, `report_id`, `notify_on`, `vou_name_long`, `vou_name_short`, `auto_start_no`, `vou_prefix`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_delete`, `cm_id`) VALUES (NULL, '9', 'icon-Record', 'Meeting', 'meeting', '11', '0', '0', '0', NULL, '0,0,0', NULL, NULL, '0', NULL, '0', '2021-06-23 15:40:25', '0', '2021-06-23 15:40:25', '0', '0');

INSERT INTO `sub_menu_master` (`id`, `sub_menu_seq`, `sub_menu_icon`, `sub_menu_name`, `sub_controller_name`, `menu_id`, `is_report`, `is_approve_req`, `is_system`, `report_id`, `notify_on`, `vou_name_long`, `vou_name_short`, `auto_start_no`, `vou_prefix`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_delete`, `cm_id`) VALUES (NULL, '10', 'icon-Record', 'Event', 'meeting/eventIndex', '11', '0', '0', '0', NULL, '0,0,0', NULL, NULL, '0', NULL, '0', '2021-06-23 15:40:25', '0', '2021-06-23 15:40:25', '0', '0');


INSERT INTO `menu_master` (`id`, `menu_icon`, `menu_name`, `controller_name`, `menu_seq`, `is_master`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_delete`, `cm_id`) VALUES (NULL, 'far fa-list-alt', 'Meeting & Event', '', '8', '0', '0', '2021-06-23 22:40:25', '0', '2024-03-05 21:06:34', '0', '0')

ALTER TABLE `select_master` ADD `is_travel` TINYINT NOT NULL DEFAULT '0' COMMENT '0 = No,1 = Yes' AFTER `remark`, ADD `bike_expense` DOUBLE(12,3) NOT NULL DEFAULT '0' AFTER `is_travel`, ADD `car_expense` DOUBLE(12,3) NOT NULL DEFAULT '0' AFTER `bike_expense`, ADD `image_required` TINYINT NOT NULL DEFAULT '0' COMMENT '0 = No,1 = Yes' AFTER `car_expense`;

INSERT INTO `sub_menu_master` (`id`, `sub_menu_seq`, `sub_menu_icon`, `sub_menu_name`, `sub_controller_name`, `menu_id`, `is_report`, `is_approve_req`, `is_system`, `report_id`, `notify_on`, `vou_name_long`, `vou_name_short`, `auto_start_no`, `vou_prefix`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_delete`, `cm_id`) VALUES (NULL, '3', 'icon-Record', 'Sales Quotation', 'lead/salesQuotation', '7', '0', '0', '0', NULL, '0,0,0', 'Sales Quotation', '', '1', NULL, '0', '2021-06-24 12:10:25', '0', '2021-06-24 12:10:25', '0', '0')

ALTER TABLE `expense_manager` ADD `vehicle_type` TINYINT NOT NULL DEFAULT '0' COMMENT '1 = Bike,2 = Car' AFTER `notes`, ADD `start_km` DOUBLE(12,3) NOT NULL DEFAULT '0' AFTER `vehicle_type`, ADD `end_km` DOUBLE(12,3) NOT NULL DEFAULT '0' AFTER `start_km`;


ALTER TABLE `sq_trans` CHANGE `confirm_by` `approve_by` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `sq_trans` ADD `approve_at` DATETIME NULL DEFAULT NULL AFTER `approve_by`;

INSERT INTO `sub_menu_master` (`id`, `sub_menu_seq`, `sub_menu_icon`, `sub_menu_name`, `sub_controller_name`, `menu_id`, `is_report`, `is_approve_req`, `is_system`, `report_id`, `notify_on`, `vou_name_long`, `vou_name_short`, `auto_start_no`, `vou_prefix`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_delete`, `cm_id`) VALUES (NULL, '3', 'icon-Record', 'Sales Analysis', 'reports/salesReport/salesAnalysis', '7', '1', '0', '0', NULL, '0,0,0', NULL, NULL, '0', NULL, '0', '2021-06-24 12:10:25', '0', '2021-06-24 12:10:25', '0', '0')

ALTER TABLE `item_master` ADD `mrp` DOUBLE(12,2) NOT NULL DEFAULT '0' AFTER `price`;


ALTER TABLE `party_master` ADD `user_status` TINYINT NOT NULL DEFAULT '0' COMMENT '0 = no,1=yes' AFTER `is_active`;
ALTER TABLE `employee_master` CHANGE `emp_role` `emp_role` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=Admin, 2=Developer, 3=Tester, 4=HR, 5 =Customer';
ALTER TABLE `employee_master` ADD `party_id` INT NOT NULL DEFAULT '0' AFTER `auth_id`;

ALTER TABLE `so_master` ADD `approve_by` INT NOT NULL DEFAULT '0' AFTER `apply_round`, ADD `approve_at` DATETIME NULL DEFAULT NULL AFTER `approve_by`;


INSERT INTO `sub_menu_master` (`id`, `sub_menu_seq`, `sub_menu_icon`, `sub_menu_name`, `sub_controller_name`, `menu_id`, `is_report`, `is_approve_req`, `is_system`, `report_id`, `notify_on`, `vou_name_long`, `vou_name_short`, `auto_start_no`, `vou_prefix`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_delete`, `cm_id`) VALUES (NULL, '10', 'icon-Record', 'Discount Structure', 'discountStructure', '1', '0', '0', '0', NULL, '0,0,0', NULL, NULL, '0', NULL, '0', '2021-06-24 04:10:25', '0', '2021-06-24 04:10:25', '0', '0')


ALTER TABLE `party_master` ADD `discount_structure` VARCHAR(255) NULL DEFAULT NULL AFTER `user_status`;
