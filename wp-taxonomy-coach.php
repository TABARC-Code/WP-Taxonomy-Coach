<?php
/**
 * Plugin Name: WP Taxonomy Coach
 * Plugin URI: https://github.com/TABARC-Code/wp-taxonomy-coach
 * Description: Helps me keep categories and tags clean by finding unused terms, single-use tags, duplicates and general taxonomy clutter.
 * Version: 1.0.0
 * Author: TABARC-Code
 * Author URI: https://github.com/TABARC-Code
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * Copyright (c) 2025 TABARC-Code
 * Original work by TABARC-Code.
 * You may modify and redistribute this software under the terms of
 * the GNU General Public License version 3 or (at your option) any later version.
 * You must preserve this notice and clearly state any changes you make.
 *
 * My goal with this plugin is straightforward:
 * WordPress sites collect taxonomy debris over time. Tags with one post,
 * empty categories, strange duplicates. This plugin gives me a dashboard to see it clearly.
 *
 * TODO: allow merging redundant tags directly from the interface.
 * TODO: highlight near-duplicate terms using simple string similarity.
 * FIXME: some older sites may have malformed taxonomy entries; need to handle gracefully.
 */

if (!defined('ABSPATH')) {
    exit; // no direct access
}

class WP_Taxonomy_Coach {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_tools_page'));
    }

    public function add_tools_page() {
        add_management_page(
            'Taxonomy Coach',
            'Taxonomy Coach',
            'manage_categories',
            'wp-taxonomy-coach',
            array($this, 'render_page')
        );
    }

    public function render_page() {
        $taxonomies = array('category', 'post_tag');

        $data = array();

        foreach ($taxonomies as $tax) {
            $terms = get_terms(array(
                'taxonomy' => $tax,
                'hide_empty' => false,
            ));

            $unused = array();
            $single_use = array();
            $duplicates = array();

            $seen = array();

            foreach ($terms as $term) {
                if ($term->count == 0) {
                    $unused[] = $term;
                }

                if ($term->count == 1) {
                    $single_use[] = $term;
                }

                // Duplicate finder (very simple)
                $normalised = strtolower(trim($term->name));
                if (isset($seen[$normalised])) {
                    $duplicates[] = $term;
                } else {
                    $seen[$normalised] = true;
                }
            }

            $data[$tax] = array(
                'unused' => $unused,
                'single_use' => $single_use,
                'duplicates' => $duplicates,
                'total' => count($terms),
            );
        }

        ?>
        <div class="wrap">
            <h1>Taxonomy Coach</h1>
            <p>I use this tool to keep categories and tags tidy. It shows unused terms, single-use tags and duplicate-looking entries.</p>

            <?php foreach ($data as $tax => $report): ?>
                <h2><?php echo esc_html(ucfirst(str_replace('_', ' ', $tax))); ?></h2>

                <p>Total terms: <strong><?php echo intval($report['total']); ?></strong></p>

                <h3>Unused terms</h3>
                <?php $this->render_term_list($report['unused']); ?>

                <h3>Single-use terms</h3>
                <?php $this->render_term_list($report['single_use']); ?>

                <h3>Duplicate-looking terms</h3>
                <?php $this->render_term_list($report['duplicates']); ?>

                <hr>
            <?php endforeach; ?>
        </div>
        <?php
    }

    private function render_term_list($terms) {
        if (empty($terms)) {
            echo '<p>No items found.</p>';
            return;
        }

        echo '<ul>';
        foreach ($terms as $term) {
            $edit_link = get_edit_term_link($term->term_id, $term->taxonomy);
            echo '<li>';
            echo '<a href="' . esc_url($edit_link) . '">' . esc_html($term->name) . '</a>';
            echo ' (ID ' . intval($term->term_id) . ')';
            echo '</li>';
        }
        echo '</ul>';
    }
}

new WP_Taxonomy_Coach();
