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
 * Block definition class for the block_hero plugin.
 *
 * @package   block_hero
 * @copyright 2026 Utdanningsdirektoratet https://udir.no
 * @author    Sindre Kjelsrud
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_hero extends block_base {

    /**
     * Initialises the block.
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_hero');
    }

    /**
     * Hides the block header
     *
     * @return bool
     */
    public function hide_header() {
        return true;
    }

    /**
     * Gets the block contents.
     *
     * @return string The block HTML.
     */
    public function get_content() {
        global $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->footer = '';

        $context = context_block::instance($this->instance->id);

        // fetch the uploaded background from storage
        $backgroundimage = '';
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'block_hero', 'content', 0, 'itemid, filepath, filename', false);
        foreach ($files as $file) {
            $url = moodle_url::make_pluginfile_url(
                $file->get_contextid(),
                'block_hero',
                'content',
                $file->get_itemid(),
                $file->get_filepath(),
                $file->get_filename(),
                false // Do not force download of the file.
            );
            $backgroundimage = $url->out(false);
            break;
        }

        $data = [
            'title' => !empty($this->config->text) ? $this->config->text : 'Default Hero Title',
            'backgroundimage' => $backgroundimage,
        ];

        $this->content->text = $OUTPUT->render_from_template('block_hero/content', $data);

        return $this->content;
    }

    public function instance_config_save($data,$nolastupdated = false) {
        $context = context_block::instance($this->instance->id);

        // Move the file from draft to permanent storage
        file_save_draft_area_files(
            $data->attachments,
            $context->id,
            'block_hero',
            'content',
            0, // itemid
            ['subdirs' => 0, 'maxfiles' => 1]
        );
    
        // Call the parent method to the data inside block_instance.configdata.
        return parent::instance_config_save($data, $nolastupdated);
    }

    /**
     * Delete files associated with this block instance when it is deleted.
     *
     * @return bool
     */
    public function instance_delete() {
        $fs = get_file_storage();
        $context = context_block::instance($this->instance->id);
        $fs->delete_area_files($context->id, 'block_hero');
        return parent::instance_delete();
    }

    /**
     * Defines in which pages this block can be added.
     *
     * @return array of the pages where the block can be added.
     */
    public function applicable_formats() {
        return [
            'admin' => false,
            'site-index' => true,
            'course-view' => true,
            'mod' => false,
            'my' => true,
        ];
    }

    /**
     * Allows multiple instances of the block on the same page.
     * @return true
     */
    public function instance_allow_multiple() {
        return true;
    }
}
