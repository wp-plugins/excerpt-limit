<?php
/*
Plugin Name: Excerpt limit
Plugin URI: http://on-drupal.ru/322
Description: Plugin allows you to edit the length of the excerpt (announcements) posts.
Author URI: http://on-drupal.ru
Author: YandexBot
Version: 1.0.0
*/

/**
 * Префикс el_ - excerpt limit.
 */

add_filter('excerpt_length', 'el_excerpt_words_limit', 999, 0);
add_filter('get_the_excerpt', 'el_excerpt_symbols_limit', 999, 1);

/**
 * В анонсах статей отображать не более el_excerpt_length слов.
 */
function el_excerpt_words_limit() {
  $length = get_option('el_excerpt_words_limit');
  return $length;
}

/**
 * В анонсах статей отображать не более el_get_the_excerpt символов.
 */
function el_excerpt_symbols_limit($excerpt) {
  $length = get_option('el_excerpt_symbols_limit');
  $excerpt = (strlen($excerpt)>$length)? mb_substr($excerpt, 0, $length).'...':$excerpt;
  return $excerpt;
}

/**
 * Инициализация.
 */
add_action('admin_init', 'el_admin_init');

function el_admin_init() {
  load_plugin_textdomain('excerpt-limit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
  add_settings_field('el_settings_excerpt-words-limit', __('Words in the excerpts of the posts of not more than', 'excerpt-limit'), 'el_ewords_callback', 'reading');
  add_settings_field('el_settings_excerpt-symbols-limit', __('Characters in the excerpts of the posts of not more than', 'excerpt-limit'), 'el_esymbols_callback', 'reading');
  register_setting('reading', 'el_excerpt_words_limit', 'intval');
  register_setting('reading', 'el_excerpt_symbols_limit', 'intval');
}

function el_ewords_callback() {
	echo '<input type="text" class="small-text" type="number" value="'. get_option('el_excerpt_words_limit'). '" min="1" step="1" id="excerpt-words-limit" name="el_excerpt_words_limit">';
}

function el_esymbols_callback() {  
	echo '<input type="text" class="small-text" type="number" value="'. get_option('el_excerpt_symbols_limit'). '" min="1" step="1" id="excerpt-symbols-limit" name="el_excerpt_symbols_limit">';
}

/**
 * Деинсталляция плагина: удаление полей-настроек из БД.
 */
register_uninstall_hook(__FILE__, 'el_deinstall'); 

function el_deinstall() {
  delete_option('el_excerpt_words_limit');
  delete_option('el_excerpt_symbols_limit');
}

?>