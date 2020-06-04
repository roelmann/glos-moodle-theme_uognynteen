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
 * Links settings page file.
 *
 * @package    theme_uognynteen
 * @copyright  2016 Richard Oelmann
 * @copyright  theme_boost - MoodleHQ
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/* Links popup Settings */
$page = new admin_settingpage('theme_uognynteen_links', get_string('linkspage', 'theme_uognynteen'));
$page->add(new admin_setting_heading('theme_uognynteen_social', get_string('linkspagesub', 'theme_uognynteen'),
        format_text(get_string('linkspagedesc' , 'theme_uognynteen'), FORMAT_MARKDOWN)));

$name = 'theme_uognynteen/staffsubheading';
$heading = get_string('stafflinks', 'theme_uognynteen');
$information = get_string('stafflinksdesc', 'theme_uognynteen');
$setting = new admin_setting_heading($name, $heading, $information);
$page->add($setting);
for ($i = 1; $i <= 6; $i++) {

    // Staff Link - Name.
    $name = 'theme_uognynteen/stafflink' . $i . 'name';
    $title = get_string('stafflink', 'theme_uognynteen') . ' ' . $i;
    $description = get_string('stafflinkdesc', 'theme_uognynteen');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Staff Link - URL.
    $name = 'theme_uognynteen/stafflink' . $i . 'url';
    $title = get_string('stafflinkurl', 'theme_uognynteen');
    $description = get_string('stafflinkurldesc', 'theme_uognynteen');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $page->add($setting);

    // Staff Link - icon.
    $name = 'theme_uognynteen/stafflink' . $i . 'icon';
    $title = get_string('stafflinkicon', 'theme_uognynteen');
    $description = get_string('stafflinkicondesc', 'theme_uognynteen');
    $default = 'globe';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);
}

$name = 'theme_uognynteen/studentsubheading';
$heading = get_string('studentlinks', 'theme_uognynteen');
$information = get_string('studentlinksdesc', 'theme_uognynteen');
$setting = new admin_setting_heading($name, $heading, $information);
$page->add($setting);
for ($i = 1; $i <= 6; $i++) {

    // Student Link - Name.
    $name = 'theme_uognynteen/studentlink' . $i . 'name';
    $title = get_string('studentlink', 'theme_uognynteen');
    $description = get_string('studentlinkdesc', 'theme_uognynteen');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Student Link - URL.
    $name = 'theme_uognynteen/studentlink' . $i . 'url';
    $title = get_string('studentlinkurl', 'theme_uognynteen');
    $description = get_string('studentlinkurldesc', 'theme_uognynteen');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $page->add($setting);

    // Student Link - icon.
    $name = 'theme_uognynteen/studentlink' . $i . 'icon';
    $title = get_string('studentlinkicon', 'theme_uognynteen');
    $description = get_string('studentlinkicondesc', 'theme_uognynteen');
    $default = 'globe';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);
}


// Must add the page after definiting all the settings!
$settings->add($page);
