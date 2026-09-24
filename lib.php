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
 * Lib class for the block_hero plugin.
 *
 * @package   block_hero
 * @copyright 2026 Utdanningsdirektoratet https://udir.no
 * @author    Sindre Kjelsrud
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Serve the requested file for the hero block.
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function block_hero_pluginfile($course, $cm, $context, string $filearea, array $args, bool $forcedownload, array $options = []) {
    global $CFG;
    
    // Check the contextlevel is as expected
    if ($context->contextlevel != CONTEXT_BLOCK) {
        return false;
    }

    // Make sure the filearea is one of those used by the plugin
    if ($filearea !== 'content') {
        return false;
    }

    // Ensure the user has permission to view the block
    require_login($course);

    // Extract the itemid, filename, and filepath from the $args array.
    $itemid = array_shift($args); // The first argument after filearea is usually the itemid
    $filename = array_pop($args); // The last item in the $args array.
    if (empty($args)) {
        $filepath = '/'; // $args is empty => the path is '/'.
    } else {
        $filepath = '/' . implode('/', $args) . '/'; // $args contains the remaining elements of the filepath.
    }

    // Retrieve the file from the Files API
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'block_hero', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // The file does not exist.
    }

    // We can now send the file back to the browser
    send_stored_file($file, 0, 0, $forcedownload, $options);
}