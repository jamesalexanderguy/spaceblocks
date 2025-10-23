<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'spaceblocks_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function spaceblocks_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'spaceblocks_post_format_setup' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'spaceblocks_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function spaceblocks_editor_style() {
		add_editor_style( get_parent_theme_file_uri( 'assets/css/editor-style.css' ) );
	}
endif;
add_action( 'after_setup_theme', 'spaceblocks_editor_style' );

// Registers custom block styles.
if ( ! function_exists( 'spaceblocks_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function spaceblocks_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'spaceblocks' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'spaceblocks_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'spaceblocks_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function spaceblocks_pattern_categories() {

		register_block_pattern_category(
			'spaceblocks_page',
			array(
				'label'       => __( 'Pages', 'spaceblocks' ),
				'description' => __( 'A collection of full page layouts.', 'spaceblocks' ),
			)
		);

		register_block_pattern_category(
			'spaceblocks_post-format',
			array(
				'label'       => __( 'Post formats', 'spaceblocks' ),
				'description' => __( 'A collection of post format patterns.', 'spaceblocks' ),
			)
		);
	}
endif;
add_action( 'init', 'spaceblocks_pattern_categories' );

// Enqueues front-end assets.
add_action('wp_enqueue_scripts', function () {
    $manifest_path = get_theme_file_path('assets/build/dist/.vite/manifest.json');
    if (!file_exists($manifest_path)) {
        error_log('Vite manifest not found: ' . $manifest_path);
        return;
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);
    $entry = $manifest['src/main.js'] ?? null;

    if (!$entry) {
        error_log('Vite manifest entry not found for src/main.js');
        return;
    }

    $base_uri = get_theme_file_uri('assets/build/dist/');

    if (!empty($entry['css'][0])) {
        wp_enqueue_style(
            'spaceblocks-main',
            $base_uri . $entry['css'][0],
            [],
            null
        );
    }

    wp_enqueue_script(
        'spaceblocks-main',
        $base_uri . $entry['file'],
        [],
        null,
        true
    );
});

// enqueues editor assets.
// Enqueues editor assets INTO THE IFRAME
add_action('after_setup_theme', function () {
    $manifest_path = get_theme_file_path('assets/build/dist/.vite/manifest.json');
    if (!file_exists($manifest_path)) return;

    $manifest = json_decode(file_get_contents($manifest_path), true);
    $editor_entry = $manifest['src/editor.js'] ?? null;
    if (!$editor_entry) return;

    // Add CSS files to the editor iframe
    if (!empty($editor_entry['css'])) {
        foreach ($editor_entry['css'] as $css_file) {
            add_editor_style('assets/build/dist/' . $css_file);
        }
    }
}, 11);

// for editor UI scripts only (not styles)
add_action('enqueue_block_editor_assets', function () {
    $manifest_path = get_theme_file_path('assets/build/dist/.vite/manifest.json');
    if (!file_exists($manifest_path)) return;

    $manifest = json_decode(file_get_contents($manifest_path), true);
    $editor_entry = $manifest['src/editor.js'] ?? null;
    if (!$editor_entry) return;

    // Only enqueue the JS here, not the CSS
    wp_enqueue_script(
        'spaceblocks-editor-js',
        get_theme_file_uri('assets/build/dist/' . $editor_entry['file']),
        [],
        null,
        true
    );
});


// Registers block binding sources.
if ( ! function_exists( 'spaceblocks_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function spaceblocks_register_block_bindings() {
		register_block_bindings_source(
			'spaceblocks/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'spaceblocks' ),
				'get_value_callback' => 'spaceblocks_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'spaceblocks_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'spaceblocks_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function spaceblocks_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;
