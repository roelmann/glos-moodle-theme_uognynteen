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
 * Core renderer.
 *
 * @package    theme_uognynteen
 * @copyright  2016 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_uognynteen\output;

use coding_exception;
use html_writer;
use tabobject;
use tabtree;
use custom_menu_item;
use custom_menu;
use block_contents;
use navigation_node;
use action_link;
use stdClass;
use moodle_url;
use preferences_groups;
use action_menu;
use help_icon;
use single_button;
use single_select;
use paging_bar;
use url_select;
use context_course;
use pix_icon;
use progress;
use context_system;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_uognynteen
 * @copyright  2012 Bas Brands, www.basbrands.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Wrapper for header elements.
     * Rewritten for uognynteen to incorporate header images from Course Summary Files.
     * @copyright 2017 theme_uognynteen Richard Oelmann https://moodle.org/user/profile.php?id=480148
     * @package    theme_uognynteen
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {

        global $CFG, $COURSE, $PAGE;

        // Get course overview files.
        if (empty($CFG->courseoverviewfileslimit)) {
            return array();
        }
        require_once($CFG->libdir. '/filestorage/file_storage.php');
        require_once($CFG->dirroot. '/course/lib.php');
        $fs = get_file_storage();
        $context = context_course::instance($COURSE->id);
        $files = $fs->get_area_files($context->id, 'course', 'overviewfiles', false, 'filename', false);
        if (count($files)) {
            $overviewfilesoptions = course_overviewfiles_options($COURSE->id);
            $acceptedtypes = $overviewfilesoptions['accepted_types'];
            if ($acceptedtypes !== '*') {
                // Filter only files with allowed extensions.
                require_once($CFG->libdir. '/filelib.php');
                foreach ($files as $key => $file) {
                    if (!file_extension_in_typegroup($file->get_filename(), $acceptedtypes)) {
                        unset($files[$key]);
                    }
                }
            }
            if (count($files) > $CFG->courseoverviewfileslimit) {
                // Return no more than $CFG->courseoverviewfileslimit files.
                $files = array_slice($files, 0, $CFG->courseoverviewfileslimit, true);
            }
        }

        // Get course overview files as images - set $courseimage.
        // The loop means that the LAST stored image will be the one displayed if >1 image file.
        $courseimage = '';
        foreach ($files as $file) {
            $isimage = $file->is_valid_image();
            if ($isimage) {
                $courseimage = file_encode_url("$CFG->wwwroot/pluginfile.php",
                    '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                    $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
            }
        }

        $header = new stdClass();
        $header->image = $courseimage;
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($PAGE->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->courseheader = $this->course_header();

        return $this->render_from_template('theme_uognynteen/header', $header);
    }

    /**
     * Context for social icons mustache template.
     * @copyright 2017 theme_uognynteen Richard Oelmann https://moodle.org/user/profile.php?id=480148
     * @package    theme_uognynteen
     *
     * @return renderer context for displaying social icons.
     */
    public function social_icons() {
        global $PAGE;

        $hasfacebook    = (empty($PAGE->theme->settings->facebook)) ? false : $PAGE->theme->settings->facebook;
        $hastwitter     = (empty($PAGE->theme->settings->twitter)) ? false : $PAGE->theme->settings->twitter;
        $hasgoogleplus  = (empty($PAGE->theme->settings->googleplus)) ? false : $PAGE->theme->settings->googleplus;
        $haslinkedin    = (empty($PAGE->theme->settings->linkedin)) ? false : $PAGE->theme->settings->linkedin;
        $hasyoutube     = (empty($PAGE->theme->settings->youtube)) ? false : $PAGE->theme->settings->youtube;
        $hasflickr      = (empty($PAGE->theme->settings->flickr)) ? false : $PAGE->theme->settings->flickr;
        $hasvk          = (empty($PAGE->theme->settings->vk)) ? false : $PAGE->theme->settings->vk;
        $haspinterest   = (empty($PAGE->theme->settings->pinterest)) ? false : $PAGE->theme->settings->pinterest;
        $hasinstagram   = (empty($PAGE->theme->settings->instagram)) ? false : $PAGE->theme->settings->instagram;
        $hasskype       = (empty($PAGE->theme->settings->skype)) ? false : $PAGE->theme->settings->skype;
        $haswebsite     = (empty($PAGE->theme->settings->website)) ? false : $PAGE->theme->settings->website;
        $hasblog        = (empty($PAGE->theme->settings->blog)) ? false : $PAGE->theme->settings->blog;
        $hasvimeo       = (empty($PAGE->theme->settings->vimeo)) ? false : $PAGE->theme->settings->vimeo;
        $hastumblr      = (empty($PAGE->theme->settings->tumblr)) ? false : $PAGE->theme->settings->tumblr;
        $hassocial1     = (empty($PAGE->theme->settings->social1)) ? false : $PAGE->theme->settings->social1;
        $social1icon    = (empty($PAGE->theme->settings->socialicon1)) ? 'globe' : $PAGE->theme->settings->socialicon1;
        $hassocial2     = (empty($PAGE->theme->settings->social2)) ? false : $PAGE->theme->settings->social2;
        $social2icon    = (empty($PAGE->theme->settings->socialicon2)) ? 'globe' : $PAGE->theme->settings->socialicon2;
        $hassocial3     = (empty($PAGE->theme->settings->social3)) ? false : $PAGE->theme->settings->social3;
        $social3icon    = (empty($PAGE->theme->settings->socialicon3)) ? 'globe' : $PAGE->theme->settings->socialicon3;

        $socialcontext = [

            // If any of the above social networks are true, sets this to true.
            'hassocialnetworks' => ($hasfacebook || $hastwitter || $hasgoogleplus || $hasflickr || $hasinstagram
                || $hasvk || $haslinkedin || $haspinterest || $hasskype || $haslinkedin || $haswebsite || $hasyoutube
                || $hasblog ||$hasvimeo || $hastumblr || $hassocial1 || $hassocial2 || $hassocial3) ? true : false,

            'socialicons' => array(
                array('haslink' => $hasfacebook, 'linkicon' => 'facebook'),
                array('haslink' => $hastwitter, 'linkicon' => 'twitter'),
                array('haslink' => $hasgoogleplus, 'linkicon' => 'google-plus'),
                array('haslink' => $haslinkedin, 'linkicon' => 'linkedin'),
                array('haslink' => $hasyoutube, 'linkicon' => 'youtube'),
                array('haslink' => $hasflickr, 'linkicon' => 'flickr'),
                array('haslink' => $hasvk, 'linkicon' => 'vk'),
                array('haslink' => $haspinterest, 'linkicon' => 'pinterest'),
                array('haslink' => $hasinstagram, 'linkicon' => 'instagram'),
                array('haslink' => $hasskype, 'linkicon' => 'skype'),
                array('haslink' => $haswebsite, 'linkicon' => 'globe'),
                array('haslink' => $hasblog, 'linkicon' => 'bookmark'),
                array('haslink' => $hasvimeo, 'linkicon' => 'vimeo-square'),
                array('haslink' => $hastumblr, 'linkicon' => 'tumblr'),
                array('haslink' => $hassocial1, 'linkicon' => $social1icon),
                array('haslink' => $hassocial2, 'linkicon' => $social2icon),
                array('haslink' => $hassocial3, 'linkicon' => $social3icon),
            )
        ];

        return $this->render_from_template('theme_uognynteen/socialicons', $socialcontext);

    }

    /**
     * Context for user alerts mustache template.
     * @copyright 2017 theme_uognynteen Richard Oelmann https://moodle.org/user/profile.php?id=480148
     * @package    theme_uognynteen
     *
     * @return renderer context for displaying user alerts.
     */
    public function useralert() {
        global $PAGE;

        $alertinfo = '<span class="fa fa-2x fa-info"></span>';
        $alertwarning = '<span class="fa fa-2x fa-warning"></span>';
        $alertsuccess = '<span class="fa fa-2x fa-bullhorn"></span>';
        $context = context_system::instance();

        $enable1alert = (empty($PAGE->theme->settings->enable1alert)) ? false : $PAGE->theme->settings->enable1alert;
        $staffonly1alert = (empty($PAGE->theme->settings->staffonly1alert)) ? false : $PAGE->theme->settings->staffonly1alert;
        if ($staffonly1alert && !has_capability('moodle/course:viewhiddencourses', $context)) {
            $enable1alert = false;
        }
        $alert1type = (empty($PAGE->theme->settings->alert1type)) ? false : $PAGE->theme->settings->alert1type;
            $alert1icon = 'alert' . $alert1type;
            $alert1heading = (empty($PAGE->theme->settings->alert1title)) ? false :
                $PAGE->theme->settings->alert1title;
            $alert1text = (empty($PAGE->theme->settings->alert1text)) ? false :
                theme_uognynteen_get_setting('alert1text', 'format_html');
        $alert1content = '<div class="alertmessage">' . $$alert1icon . '<span class="title"> '
                . $alert1heading . '</span>  ' . $alert1text .'</div>';

        $enable2alert = (empty($PAGE->theme->settings->enable2alert)) ? false :
            $PAGE->theme->settings->enable2alert;
        $staffonly2alert = (empty($PAGE->theme->settings->staffonly2alert)) ? false :
            $PAGE->theme->settings->staffonly2alert;
        if ($staffonly2alert && !has_capability('moodle/course:viewhiddencourses', $context)) {
            $enable2alert = false;
        }
        $alert2type = (empty($PAGE->theme->settings->alert2type)) ? false :
            $PAGE->theme->settings->alert2type;
            $alert2icon = 'alert' . $alert2type;
            $alert2heading = (empty($PAGE->theme->settings->alert2title)) ? false :
                $PAGE->theme->settings->alert2title;
            $alert2text = (empty($PAGE->theme->settings->alert2text)) ? false :
                theme_uognynteen_get_setting('alert2text', 'format_html');
        $alert2content = '<div class="alertmessage">' . $$alert2icon . '<span class="title"> '
                . $alert2heading . '</span>  ' . $alert2text .'</div>';

        $enable3alert = (empty($PAGE->theme->settings->enable3alert)) ? false :
            $PAGE->theme->settings->enable3alert;
        $staffonly3alert = (empty($PAGE->theme->settings->staffonly3alert)) ? false :
            $PAGE->theme->settings->staffonly3alert;
        if ($staffonly3alert && !has_capability('moodle/course:viewhiddencourses', $context)) {
            $enable3alert = false;
        }
        $alert3type = (empty($PAGE->theme->settings->alert3type)) ? false :
            $PAGE->theme->settings->alert3type;
            $alert3icon = 'alert' . $alert3type;
            $alert3heading = (empty($PAGE->theme->settings->alert3title)) ? false :
                $PAGE->theme->settings->alert3title;
            $alert3text = (empty($PAGE->theme->settings->alert3text)) ? false :
                theme_uognynteen_get_setting('alert3text', 'format_html');
        $alert3content = '<div class="alertmessage">' . $$alert3icon . '<span class="title"> '
                . $alert3heading . '</span>  ' . $alert3text .'</div>';

        $useralertcontext = [
            'hasuseralert' => true,
            'useralert' => array(
                array(
                    'enablealert' => $enable1alert,
                    'alerttype' => $alert1type,
                    'alertcontent' => $alert1content,
                ),
                array(
                    'enablealert' => $enable2alert,
                    'alerttype' => $alert2type,
                    'alertcontent' => $alert2content,
                ),
                array(
                    'enablealert' => $enable3alert,
                    'alerttype' => $alert3type,
                    'alertcontent' => $alert3content,
                ),
            )
        ];

        return $this->render_from_template('theme_uognynteen/useralert', $useralertcontext);

    }


    /**
     * Get setting for footnote content.
     * @copyright 2017 theme_uognynteen Richard Oelmann https://moodle.org/user/profile.php?id=480148
     * @package    theme_uognynteen
     *
     * @return html string to display footnote.
     */
    public function footnote() {
        global $PAGE;
        $footnote = '';

        $footnote    = (empty($PAGE->theme->settings->footnote)) ? false : $PAGE->theme->settings->footnote;

        return $footnote;
    }

    /**
     * Context for block modal popup buttons.
     * @copyright 2017 theme_uognynteen Richard Oelmann https://moodle.org/user/profile.php?id=480148
     * @package    theme_uognynteen
     *
     * @return renderer context for displaying modal popup buttons.
     */
    public function blockmodalbuttons() {
        global $OUTPUT, $PAGE, $COURSE;
        $blocksslider2html = $OUTPUT->blocksmodal('side-slidertwo');
        $blocksslider3html = $OUTPUT->blocksmodal('side-sliderthree');
        $blocksslider4html = $OUTPUT->blocksmodal('side-sliderfour');

        $hasslidertwoblocks = strpos($blocksslider2html, 'data-block=') !== false;
        $hassliderthreeblocks = strpos($blocksslider3html, 'data-block=') !== false;
        $hassliderfourblocks = strpos($blocksslider4html, 'data-block=') !== false;

        $buttonshtml = '';
        $buttonshtml .= '<div class="blockmodalbuttons">';
        if (isloggedin() && ISSET($COURSE->id) && $COURSE->id > 1) {
            $course = $this->page->course;
            $context = context_course::instance($course->id);
            if (has_capability('moodle/course:viewhiddenactivities', $context)) {
                if (strpos($PAGE->bodyclasses, 'pagelayout-course') > 0) {
                    $buttonshtml .= '<button type="button" class="btn btn-warning pageblockbtn"
                        data-toggle="modal" data-toggle="tooltip" data-placement="left" title="Settings Popup"';
                    $buttonshtml .= 'data-target="#slider1_blocksmodal"><i class="fa fa-2x fa-cog"></i></button>';
                }
            }
        }
        if ($hasslidertwoblocks) {
            $buttonshtml .= '<button type="button" class="btn btn-info pageblockbtn" data-toggle="modal"';
            $buttonshtml .= 'data-target="#slider2_blocksmodal"><i class="fa fa-2x fa-arrow-circle-left"></i></button>';
        }
        if ($hassliderthreeblocks) {
            $buttonshtml .= '<button type="button" class="btn btn-info pageblockbtn" data-toggle="modal"';
            $buttonshtml .= 'data-target="#slider3_blocksmodal"><i class="fa fa-2x fa-arrow-circle-left"></i></button>';
        }
        if ($hassliderfourblocks) {
            $buttonshtml .= '<button type="button" class="btn btn-success pageblockbtn" data-toggle="modal"';
            $buttonshtml .= 'data-target="#slider4_blocksmodal"><i class="fa fa-2x fa-arrow-circle-left"></i></button>';
        }
        $buttonshtml .= '</div>';

        return $buttonshtml;
    }

    /**
     * Context for blocks modal pop mustache template.
     * @copyright 2017 theme_uognynteen Richard Oelmann https://moodle.org/user/profile.php?id=480148
     * @package    theme_uognynteen
     *
     * @param string $region The region to get HTML for.
     * @return renderer context for displaying blocks modal popup.
     */
    public function blocksmodal($region) {
        global $OUTPUT, $COURSE;
        $blocksmodalusersection = '';
        $blockscontent = $OUTPUT->blocks($region);

        $maintitle = get_string('defaultmodaltitle', 'theme_uognynteen');
        $subtitle = get_string('defaultmodaltitledesc', 'theme_uognynteen');

        if (isloggedin() && ISSET($COURSE->id) && $COURSE->id > 1) {
            $course = $this->page->course;
            $context = context_course::instance($course->id);

            if ($region == 'side-sliderone') {
                if (has_capability('moodle/course:viewhiddenactivities', $context)) {
                    $maintitle = get_string('staffmodal', 'theme_uognynteen');
                    $subtitle = get_string('staffmodaldesc', 'theme_uognynteen');
                    $blocksmodalusersection .= $OUTPUT->staffblocksmodal();
                }
            }
        }

        $blocksmodalcontext = [
            'maintitle' => $maintitle,
            'subtitle' => $subtitle,
            'blocksmodalusersection' => $blocksmodalusersection,
            'blockscontent' => $blockscontent
        ];

        return $this->render_from_template('theme_uognynteen/blocksmodal', $blocksmodalcontext);

    }

    /**
     * Context for staff user content on blocks modal popup mustache template.
     * @copyright 2017 theme_uognynteen Richard Oelmann https://moodle.org/user/profile.php?id=480148
     * @package    theme_uognynteen
     *
     * @return renderer context for staff user content.
     */
    public function staffblocksmodal() {
        global $PAGE, $DB, $COURSE, $CFG;
        if (ISSET($COURSE->id) && $COURSE->id > 1) {
            $hascoursegroup = array(
                'title' => get_string('modalcoursesettings', 'theme_uognynteen'),
                'icon' => 'cogs'
            );
            $hasusersgroup = array(
                'title' => get_string('modalusers', 'theme_uognynteen'),
                'icon' => 'users'
            );
            $hasreportsgroup = array(
                'title' => get_string('modalreports', 'theme_uognynteen'),
                'icon' => 'id-card'
            );
            $hasothergroup = array(
                'title' => get_string('modalstaffotherlinks', 'theme_uognynteen'),
                'icon' => 'external-link'
            );

            $coursegrouplinks = array(
                array(
                    'name' => get_string('editcourse', 'theme_uognynteen'),
                    'url' => new moodle_url('/course/edit.php', array('id' => $PAGE->course->id)),
                    'icon' => 'edit'
                ),
                array(
                    'name' => get_string('resetcourse', 'theme_uognynteen'),
                    'url' => new moodle_url('/course/reset.php', array('id' => $PAGE->course->id)),
                    'icon' => 'reply'
                ),
                array(
                    'name' => get_string('coursebackup', 'theme_uognynteen'),
                    'url' => new moodle_url('/backup/backup.php', array('id' => $PAGE->course->id)),
                    'icon' => 'copy'
                ),
                array(
                    'name' => get_string('courserestore', 'theme_uognynteen'),
                    'url' => new moodle_url('/backup/restorefile.php', array('contextid' => $PAGE->context->id)),
                    'icon' => 'clipboard'
                ),
                array(
                    'name' => get_string('courseimport', 'theme_uognynteen'),
                    'url' => new moodle_url('/backup/import.php', array('id' => $PAGE->course->id)),
                    'icon' => 'clipboard'
                ),
                array(
                    'name' => get_string('courseadmin', 'theme_uognynteen'),
                    'url' => new moodle_url('/course/admin.php', array('courseid' => $PAGE->course->id)),
                    'icon' => 'dashboard'
                ),
            );

            $enrol = $DB->get_record('enrol', array('courseid' => $COURSE->id, 'enrol' => 'manual'));
            $enrolinstance = $enrol->id;
            $courseid = $PAGE->course->id;
            if ($CFG->version > 2017092799 ) {
                 $newurl = new moodle_url('/user/index.php', array('id' => $courseid));
            } else {
                $newurl = new moodle_url('/enrol/users.php', array('id' => $courseid));
            }

            $usersgrouplinks = array(
                array(
                    'name' => get_string('manageusers', 'theme_uognynteen'),
                    'url' => $newurl,
                    'icon' => 'address-book-o'
                ),
                array(
                    'name' => get_string('manualenrol', 'theme_uognynteen'),
                    'url' => new moodle_url('/enrol/manual/manage.php',
                        array('enrolid' => $enrolinstance, 'id' => $courseid)),
                    'icon' => 'user-plus'
                ),
                array(
                    'name' => get_string('usergroups', 'theme_uognynteen'),
                    'url' => new moodle_url('/group/index.php', array('id' => $courseid)),
                    'icon' => 'group'
                ),
                array(
                    'name' => get_string('enrolmentmethods', 'theme_uognynteen'),
                    'url' => new moodle_url('/enrol/instances.php', array('id' => $courseid)),
                    'icon' => 'address-card-o'
                ),
            );
            $reportsgrouplinks = array(
                array(
                    'name' => get_string('usergrades', 'theme_uognynteen'),
                    'url' => new moodle_url('/grade/report/grader/index.php', array('id' => $courseid)),
                    'icon' => 'bar-chart'
                ),
                array(
                    'name' => get_string('logs', 'theme_uognynteen'),
                    'url' => new moodle_url('/report/log/index.php', array('id' => $courseid)),
                    'icon' => 'server'
                ),
                array(
                    'name' => get_string('livelogs', 'theme_uognynteen'),
                    'url' => new moodle_url('/report/loglive/index.php', array('id' => $courseid)),
                    'icon' => 'tasks'
                ),
                array(
                    'name' => get_string('participation', 'theme_uognynteen'),
                    'url' => new moodle_url('/report/participation/index.php', array('id' => $courseid)),
                    'icon' => 'street-view'
                ),
                array(
                    'name' => get_string('activity', 'theme_uognynteen'),
                    'url' => new moodle_url('/report/outline/index.php', array('id' => $courseid)),
                    'icon' => 'user-circle-o'
                ),
            );

            $othergrouplinks = array(
                array(
                    'name' => get_string('recyclebin', 'theme_uognynteen'),
                    'url' => new moodle_url('/admin/tool/recyclebin/index.php', array('contextid' => $PAGE->context->id)),
                    'icon' => 'trash-o'
                ),
            );
            for ($i = 1; $i <= 6; $i++) {
                if (strlen(theme_uognynteen_get_setting('stafflink' . $i . 'name')) > 0) {
                    $othergrouplinks[] = array(
                            'name' => theme_uognynteen_get_setting('stafflink' . $i . 'name'),
                            'url' => theme_uognynteen_get_setting('stafflink' . $i . 'url'),
                            'icon' => theme_uognynteen_get_setting('stafflink' . $i . 'icon')
                        );
                }
            }

            $staffmodalcontext = [
                'hascoursegroup' => $hascoursegroup,
                'coursegrouplinks' => $coursegrouplinks,
                'hasusersgroup' => $hasusersgroup,
                'usersgrouplinks' => $usersgrouplinks,
                'hasreportsgroup' => $hasreportsgroup,
                'reportsgrouplinks' => $reportsgrouplinks,
                'hasothergroup' => $hasothergroup,
                'othergrouplinks' => $othergrouplinks
            ];
            return $this->render_from_template('theme_uognynteen/staffmodal', $staffmodalcontext);
        } else {
            return '';
        }
    }

    /**
     * Render Editing link as a bootstrap style button with fontawesome icon.
     * @copyright 2017 theme_uognynteen Richard Oelmann https://moodle.org/user/profile.php?id=480148
     * @package    theme_uognynteen
     *
     * @param moodle_url $url
     * @return $output.
     */
    public function edit_button(moodle_url $url) {
        $url->param('sesskey', sesskey());
        if ($this->page->user_is_editing()) {
            $url->param('edit', 'off');
            $btn = 'btn-danger';
            $title = get_string('editoff' , 'theme_uognynteen');
            $icon = 'fa-power-off';
        } else {
            $url->param('edit', 'on');
            $btn = 'btn-success';
            $title = get_string('editon' , 'theme_uognynteen');
            $icon = 'fa-edit';
        }
        return html_writer::tag('a', html_writer::start_tag('i', array('class' => $icon . ' fa fa-fw')) .
            html_writer::end_tag('i') . $title, array('href' => $url, 'class' => 'btn  ' . $btn, 'title' => $title));
    }

    /**
     * Function to find course image for use in header and in course overview.
     * @copyright 2017 theme_uognynteen Richard Oelmann https://moodle.org/user/profile.php?id=480148
     * @package    theme_uognynteen
     *
     * @return image.
     */
    public function get_course_image () {
        global $CFG;
        if (empty($CFG->courseoverviewfileslimit)) {
            return array();
        }
        require_once($CFG->libdir. '/filestorage/file_storage.php');
        require_once($CFG->dirroot. '/course/lib.php');

        $courses = get_courses();
        $crsimagescss = '';

        foreach ($courses as $c) {

            // Get course overview files.
            $fs = get_file_storage();
            $context = context_course::instance($c->id);
            $files = $fs->get_area_files($context->id, 'course', 'overviewfiles', false, 'filename', false);
            if (count($files)) {
                $overviewfilesoptions = course_overviewfiles_options($c->id);
                $acceptedtypes = $overviewfilesoptions['accepted_types'];
                if ($acceptedtypes !== '*') {
                    // Filter only files with allowed extensions.
                    require_once($CFG->libdir. '/filelib.php');
                    foreach ($files as $key => $file) {
                        if (!file_extension_in_typegroup($file->get_filename(), $acceptedtypes)) {
                            unset($files[$key]);
                        }
                    }
                }
                if (count($files) > $CFG->courseoverviewfileslimit) {
                    // Return no more than $CFG->courseoverviewfileslimit files.
                    $files = array_slice($files, 0, $CFG->courseoverviewfileslimit, true);
                }
            }

            // Get course overview files as images - set $courseimage.
            // The loop means that the LAST stored image will be the one displayed if >1 image file.
            $courseimage = '';
            foreach ($files as $file) {
                $isimage = $file->is_valid_image();
                if ($isimage) {
                    $courseimage = file_encode_url("$CFG->wwwroot/pluginfile.php",
                    '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                        $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                }
            }

            $crsid = '#course-events-container-' . $c->id . ', .courses-view-course-item #course-info-container-' . $c->id;
            $crsimagescss .= $crsid . ' {background-image: url("' . $courseimage . '");
                background-size: 100% 100%; background-color:red;}';
        }

        return $crsimagescss;

    }

}
