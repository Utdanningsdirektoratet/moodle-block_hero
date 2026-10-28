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
 * Version metadata for the block_hero plugin.
 *
 * @package   block_hero
 * @copyright 2026 Utdanningsdirektoratet https://udir.no
 * @author    Sindre Kjelsrud
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version = 2026102601;
$plugin->requires = 2026042000; // Moodle 5.2
$plugin->supported = [502]; // Not tested on anything before Moodle 5.2
$plugin->component = 'block_hero';
$plugin->maturity = MATURITY_STABLE;
$plugin->release = 'v1.0.1';
