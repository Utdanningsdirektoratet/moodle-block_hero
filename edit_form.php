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
 * Block edit form class for the block_hero plugin.
 *
 * @package   block_hero
 * @copyright 2026 Utdanningsdirektoratet https://udir.no
 * @author    Sindre Kjelsrud
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_hero_edit_form extends block_edit_form {
    /**
     * Return filemanager options for the background image.
     *
     * @return array
     */
    protected function filemanager_options(): array {
        return [
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => ['image'],
        ];
    }

    protected function specific_definition($mform) {
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Hero title
        $mform->addElement('text', 'config_text', get_string('blocktitle', 'block_hero'));
        $mform->setDefault('config_text', 'default value');
        $mform->setType('config_text', PARAM_TEXT);

        // Background image
        $mform->addElement(
            'filemanager',
            'config_attachments',
            get_string('uploadimage', 'block_hero'),
            null,
            $this->filemanager_options() 
        );
    }

    /**
     * Populate the form before it's displayed.
     *
     * @param stdClass|array $defaults
     */
    public function set_data($defaults) {
        $itemid = 0; // Itemid for blocks is usually the instance ID
        $context = context_block::instance($this->block->instance->id);

        $draftitemid = file_get_submitted_draft_itemid('config_attachments');
        file_prepare_draft_area($draftitemid, $context->id, 'block_hero', 'content', 0, $filemanageroptions);

        // Add the draft ID to the data object so the form knows which files to show
        $defaults->attachments = $draftitemid;

        // Set the draft ID on defaults and block config so it is not overwritten by parent::set_data().
        $defaults->config_attachments = $draftitemid;
        if (!empty($this->block->config)) {
            $this->block->config->attachments = $draftitemid;
        }

        parent::set_data($defaults);
    }
}
