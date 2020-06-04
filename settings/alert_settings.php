<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Colours settings page file.
 *
 * @package    theme_uognynteen
 * @copyright  2016 Richard Oelmann
 * @copyright  theme_boost - MoodleHQ
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_uognynteen_alert', get_string('alert_settings', 'theme_uognynteen'));
$page->add(new admin_setting_heading('theme_uognynteen_alerts', get_string('alert_settingssub', 'theme_uognynteen'),
    format_text(get_string('alertsdesc', 'theme_uognynteen'), FORMAT_MARKDOWN)));

$information = get_string('alertinfodesc', 'theme_uognynteen'); // Standard for each of the descriptors.

// This is the descriptor for Alert One.
$name = 'theme_uognynteen/alert1info';
$heading = get_string('alert1', 'theme_uognynteen');
$setting = new admin_setting_heading($name, $heading, $information);
$page->add($setting);

// Enable Alert.
$name = 'theme_uognynteen/enable1alert';
$title = get_string('enablealert', 'theme_uognynteen');
$description = get_string('enablealertdesc', 'theme_uognynteen');
$default = false;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Staff Only Alert.
$name = 'theme_uognynteen/staffonly1alert';
$title = get_string('staffonlyalert', 'theme_uognynteen');
$description = get_string('staffonlyalertdesc', 'theme_uognynteen');
$default = false;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Alert Type.
$name = 'theme_uognynteen/alert1type';
$title = get_string('alerttype', 'theme_uognynteen');
$description = get_string('alerttypedesc', 'theme_uognynteen');
$alertinfo = get_string('alert_info', 'theme_uognynteen');
$alertwarning = get_string('alert_warning', 'theme_uognynteen');
$alertgeneral = get_string('alert_general', 'theme_uognynteen');
$default = 'info';
$choices = array('info' => $alertinfo, 'warning' => $alertwarning, 'success' => $alertgeneral);
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Alert Title.
$name = 'theme_uognynteen/alert1title';
$title = get_string('alerttitle', 'theme_uognynteen');
$description = get_string('alerttitledesc', 'theme_uognynteen');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Alert Text.
$name = 'theme_uognynteen/alert1text';
$title = get_string('alerttext', 'theme_uognynteen');
$description = get_string('alerttextdesc', 'theme_uognynteen');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// This is the descriptor for Alert Two.
$name = 'theme_uognynteen/alert2info';
$heading = get_string('alert2', 'theme_uognynteen');
$setting = new admin_setting_heading($name, $heading, $information);
$page->add($setting);

// Enable Alert.
$name = 'theme_uognynteen/enable2alert';
$title = get_string('enablealert', 'theme_uognynteen');
$description = get_string('enablealertdesc', 'theme_uognynteen');
$default = false;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Staff Only Alert.
$name = 'theme_uognynteen/staffonly2alert';
$title = get_string('staffonlyalert', 'theme_uognynteen');
$description = get_string('staffonlyalertdesc', 'theme_uognynteen');
$default = false;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Alert Type.
$name = 'theme_uognynteen/alert2type';
$title = get_string('alerttype', 'theme_uognynteen');
$description = get_string('alerttypedesc', 'theme_uognynteen');
$alertinfo = get_string('alert_info', 'theme_uognynteen');
$alertwarning = get_string('alert_warning', 'theme_uognynteen');
$alertgeneral = get_string('alert_general', 'theme_uognynteen');
$default = 'info';
$choices = array('info' => $alertinfo, 'warning' => $alertwarning, 'success' => $alertgeneral);
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Alert Title.
$name = 'theme_uognynteen/alert2title';
$title = get_string('alerttitle', 'theme_uognynteen');
$description = get_string('alerttitledesc', 'theme_uognynteen');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Alert Text.
$name = 'theme_uognynteen/alert2text';
$title = get_string('alerttext', 'theme_uognynteen');
$description = get_string('alerttextdesc', 'theme_uognynteen');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// This is the descriptor for Alert Three.
$name = 'theme_uognynteen/alert3info';
$heading = get_string('alert3', 'theme_uognynteen');
$setting = new admin_setting_heading($name, $heading, $information);
$page->add($setting);

// Enable Alert.
$name = 'theme_uognynteen/enable3alert';
$title = get_string('enablealert', 'theme_uognynteen');
$description = get_string('enablealertdesc', 'theme_uognynteen');
$default = false;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Staff Only Alert.
$name = 'theme_uognynteen/staffonly3alert';
$title = get_string('staffonlyalert', 'theme_uognynteen');
$description = get_string('staffonlyalertdesc', 'theme_uognynteen');
$default = false;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Alert Type.
$name = 'theme_uognynteen/alert3type';
$title = get_string('alerttype', 'theme_uognynteen');
$description = get_string('alerttypedesc', 'theme_uognynteen');
$alertinfo = get_string('alert_info', 'theme_uognynteen');
$alertwarning = get_string('alert_warning', 'theme_uognynteen');
$alertgeneral = get_string('alert_general', 'theme_uognynteen');
$default = 'info';
$choices = array('info' => $alertinfo, 'warning' => $alertwarning, 'success' => $alertgeneral);
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Alert Title.
$name = 'theme_uognynteen/alert3title';
$title = get_string('alerttitle', 'theme_uognynteen');
$description = get_string('alerttitledesc', 'theme_uognynteen');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Alert Text.
$name = 'theme_uognynteen/alert3text';
$title = get_string('alerttext', 'theme_uognynteen');
$description = get_string('alerttextdesc', 'theme_uognynteen');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);
