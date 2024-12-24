<?php
/**
 * Radiate functions and definitions
 *
 * @package    ThemeGrill
 * @subpackage Radiate
 * @since      Radiate 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 768; /* pixels */
}

if ( ! function_exists( 'radiate_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function radiate_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on radiate, use a find and replace
		 * to change 'radiate' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'radiate', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 * Post thumbail is used for pages that are shown in the featured section of Front page.
		 */
		add_theme_support( 'post-thumbnails' );

		// Gutenberg wide layout support.
		add_theme_support( 'align-wide' );

		// Gutenberg block layout support.
		add_theme_support( 'wp-block-styles' );

		// Gutenberg editor support.
		add_theme_support( 'responsive-embeds' );

		// Supporting title tag via add_theme_support (since WordPress 4.1)
		add_theme_support( 'title-tag' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'radiate' ),
			)
		);

		// Enable support for Post Formats.
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

		// Setup the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'radiate_custom_background_args',
				array(
					'default-color' => 'EAEAEA',
					'default-image' => '',
				)
			)
		);

		// Adding excerpt option box for pages as well
		add_post_type_support( 'page', 'excerpt' );

		// Cropping images to different sizes to be used in the theme
		add_image_size( 'featured-image-medium', 768, 350, true );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Gutenberg wide layout support.
		add_theme_support( 'align-wide' );

		// Gutenberg block styles support.
		add_theme_support( 'wp-block-styles' );

		// Gutenberg responsive embeds support.
		add_theme_support( 'responsive-embeds' );

		// Enable support for WooCommerce
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
endif; // radiate_setup
add_action( 'after_setup_theme', 'radiate_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function radiate_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'radiate' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}

add_action( 'widgets_init', 'radiate_widgets_init' );

/**
 * Assign the Radiate version to a variable.
 */
$radiate_theme = wp_get_theme( 'radiate' );

define( 'RADIATE_THEME_VERSION', $radiate_theme->get( 'Version' ) );

/**
 * Enqueue scripts and styles.
 */
function radiate_scripts() {
	// Load our main stylesheet.
	wp_enqueue_style( 'radiate-style', get_stylesheet_uri() );

	wp_enqueue_style( 'radiate-google-fonts', '//fonts.googleapis.com/css?family=Roboto|Merriweather:400,300&display=swap' );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'radiate-genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.3.1' );

	wp_enqueue_script( 'radiate-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'radiate-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	wp_enqueue_script( 'radiate-custom-js', get_template_directory_uri() . '/js/custom.js', array( 'jquery' ), false, true );

	$radiate_header_image_link = get_header_image();
	wp_localize_script( 'radiate-custom-js', 'radiateScriptParam', array( 'radiate_image_link' => $radiate_header_image_link ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/js/html5shiv.js', array(), '3.7.3', false );
	wp_script_add_data( 'html5shiv', 'conditional', 'lte IE 8' );

}

add_action( 'wp_enqueue_scripts', 'radiate_scripts' );

/**
 * Enqueue Google fonts and editor styles.
 */
function radiate_block_editor_styles() {
	wp_enqueue_style( 'radiate-editor-googlefonts', '//fonts.googleapis.com/css2?family=Roboto|Merriweather:400,300&display=swap' );
	wp_enqueue_style( 'radiate-block-editor-styles', get_template_directory_uri() . '/style-editor-block.css' );
}

add_action( 'enqueue_block_editor_assets', 'radiate_block_editor_styles', 1, 1 );


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Calling in the admin area for the Welcome Page as well as for the new theme notice too.
 */
if ( is_admin() ) {
	require get_template_directory() . '/inc/admin/class-radiate-admin.php';
	require get_template_directory() . '/inc/admin/class-radiate-dashboard.php';
	require get_template_directory() . '/inc/admin/class-radiate-notice.php';
	require get_template_directory() . '/inc/admin/class-radiate-welcome-notice.php';
	require get_template_directory() . '/inc/admin/class-radiate-upgrade-notice.php';
	require get_template_directory() . '/inc/admin/class-radiate-theme-review-notice.php';
}



// Começando aqui API ~~ Segue o fluxo
// Adicionar endpoints REST API
add_action('rest_api_init', function () {
    // Endpoint para cadastrar categorias de notícias
    register_rest_route('custom/v1', '/categorias', [
        'methods' => 'POST',
        'callback' => 'cadastrar_categoria',
        'permission_callback' => '__return_true', // Ajustar permissões se necessário
    ]);

    // Endpoint para listar categorias
    register_rest_route('custom/v1', '/categorias', [
        'methods' => 'GET',
        'callback' => 'listar_categorias',
        'permission_callback' => '__return_true', // Ajustar permissões se necessário
    ]);

    // Endpoint para listar as notícias
    register_rest_route('custom/v1', '/noticias', [
        'methods' => 'GET',
        'callback' => 'listar_noticias',
        'permission_callback' => '__return_true',
    ]);

    // Endpoint para editar categorias
    register_rest_route('custom/v1', '/categorias/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => 'editar_categoria',
        'permission_callback' => '__return_true',
    ]);

    // Endpoint para cadastrar notícias
    register_rest_route('custom/v1', '/noticias', [
        'methods' => 'POST',
        'callback' => 'cadastrar_noticia',
        'permission_callback' => '__return_true',
    ]);

    // Endpoint para editar notícias (Alterado para PUT)
    register_rest_route('custom/v1', '/noticias/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => 'editar_noticia',
        'permission_callback' => '__return_true',
    ]);

    // Endpoint para remover notícias
    register_rest_route('custom/v1', '/noticias/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'remover_noticia',
        'permission_callback' => '__return_true',
    ]);
	// Endpoint para excluir categorias
register_rest_route('custom/v1', '/categorias/(?P<id>\d+)', [
    'methods' => 'DELETE',
    'callback' => 'remover_categoria',
    'permission_callback' => '__return_true',
]);

});

// Função para cadastrar categorias
function cadastrar_categoria($request) {
    $nome_categoria = sanitize_text_field($request->get_param('nome'));

    if (empty($nome_categoria)) {
        return new WP_Error('campo_vazio', 'O nome da categoria é obrigatório.', ['status' => 400]);
    }

    $term = wp_insert_term($nome_categoria, 'category');

    if (is_wp_error($term)) {
        return $term;
    }

    return new WP_REST_Response(['success' => true, 'categoria_id' => $term['term_id']], 200);
}

// Função para listar categorias
function listar_categorias($request) {
    // Recuperar todas as categorias
    $categorias = get_categories([
        'hide_empty' => false, // Inclui categorias vazias
    ]);

    if (empty($categorias)) {
        return new WP_REST_Response(['success' => false, 'message' => 'Nenhuma categoria encontrada.'], 404);
    }

    // Formatar os dados das categorias
    $resultado = array_map(function ($categoria) {
        return [
            'id' => $categoria->term_id,
            'nome' => $categoria->name,
            'slug' => $categoria->slug,
            'contagem' => $categoria->count, // Número de posts associados à categoria
        ];
    }, $categorias);

    return new WP_REST_Response(['success' => true, 'categorias' => $resultado], 200);
}

// Função para listar notícias
function listar_noticias($request) {
    // Recupera todas as notícias
    $args = [
        'post_type' => 'post', // Tipo de post
        'post_status' => 'publish', // Apenas posts publicados
        'posts_per_page' => -1, // Não limitar a quantidade
    ];

    $noticias = get_posts($args);

    if (empty($noticias)) {
        return new WP_REST_Response(['success' => false, 'message' => 'Nenhuma notícia encontrada.'], 404);
    }
	$resultado = array_map(function ($noticia) {
        $imagem_url = get_the_post_thumbnail_url($noticia->ID, 'full');
        return [
            'id' => $noticia->ID,
            'titulo' => $noticia->post_title,
            'conteudo' => $noticia->post_content, // Certifique-se de que o conteúdo da notícia seja retornado
            'data_publicacao' => $noticia->post_date,
            'categoria' => get_the_category($noticia->ID)[0]->name ?? 'Sem categoria',
            'imagem' => $imagem_url ? $imagem_url : null,
        ];
    }, $noticias);
	    return new WP_REST_Response(['success' => true, 'noticias' => $resultado], 200);
	

    // Formatar os dados das notícias
    $resultado = array_map(function ($noticia) {
        // Obter imagem destacada
        $imagem_url = get_the_post_thumbnail_url($noticia->ID, 'full'); // URL da imagem completa

        return [
            'id' => $noticia->ID,
            'titulo' => $noticia->post_title,
            'conteudo' => wp_trim_words($noticia->post_content, 20), // Exibe apenas 20 palavras
            'data_publicacao' => $noticia->post_date,
            'categoria' => get_the_category($noticia->ID)[0]->name ?? 'Sem categoria', // Pega a primeira categoria
            'imagem' => $imagem_url ? $imagem_url : null, // URL da imagem destacada ou null se não houver
        ];
    }, $noticias);

    return new WP_REST_Response(['success' => true, 'noticias' => $resultado], 200);
}


// Função para editar categorias
function editar_categoria($request) {
    $categoria_id = $request->get_param('id');
    $nome_categoria = sanitize_text_field($request->get_param('nome'));

    // Verifica se o nome da categoria não está vazio
    if (empty($nome_categoria)) {
        return new WP_Error('campo_vazio', 'O nome da categoria é obrigatório.', ['status' => 400]);
    }

    // Atualiza a categoria
    $term = wp_update_term($categoria_id, 'category', [
        'name' => $nome_categoria,
    ]);

    if (is_wp_error($term)) {
        return $term;
    }

    return new WP_REST_Response(['success' => true, 'categoria' => $term], 200);
}

// Função para adicionar notícias
function cadastrar_noticia($request) {
    $titulo = sanitize_text_field($request->get_param('titulo'));
    $conteudo = sanitize_textarea_field($request->get_param('conteudo'));
    $imagem_id = intval($request->get_param('imagem_id'));
    $categoria = intval($request->get_param('categoria'));
    $data_publicacao = sanitize_text_field($request->get_param('data_publicacao'));

    if (empty($titulo) || empty($conteudo) || empty($categoria)) {
        return new WP_Error('campos_obrigatorios', 'Título, conteúdo e categoria são obrigatórios.', ['status' => 400]);
    }

    $post_id = wp_insert_post([
        'post_title' => $titulo,
        'post_content' => $conteudo,
        'post_status' => 'publish',
        'post_type' => 'post',
        'post_category' => [$categoria],
        'post_date' => $data_publicacao ?: current_time('mysql'),
    ]);

    if (is_wp_error($post_id)) {
        return $post_id;
    }

    if ($imagem_id > 0) {
        set_post_thumbnail($post_id, $imagem_id);
    }

    return new WP_REST_Response(['success' => true, 'noticia_id' => $post_id], 200);
}

// Editar notícia
function editar_noticia($request) {
    $post_id = intval($request->get_param('id'));
    $titulo = sanitize_text_field($request->get_param('titulo'));
    $conteudo = sanitize_textarea_field($request->get_param('conteudo'));
    $imagem_id = intval($request->get_param('imagem_id'));
    $categoria = intval($request->get_param('categoria'));
    $data_publicacao = sanitize_text_field($request->get_param('data_publicacao'));

    $post = get_post($post_id);

    if (!$post || $post->post_type !== 'post') {
        return new WP_Error('noticia_nao_encontrada', 'Notícia não encontrada.', ['status' => 404]);
    }

    $updated_post = [
        'ID' => $post_id,
        'post_title' => $titulo ?: $post->post_title,
        'post_content' => $conteudo ?: $post->post_content,
        'post_date' => $data_publicacao ?: $post->post_date,
    ];

    wp_update_post($updated_post);

    if ($categoria > 0) {
        wp_set_post_categories($post_id, [$categoria]);
    }

    if ($imagem_id > 0) {
        set_post_thumbnail($post_id, $imagem_id);
    }

    return new WP_REST_Response(['success' => true], 200);
}

// Função para remover notícias
function remover_noticia($request) {
    $post_id = intval($request->get_param('id'));

    if (get_post($post_id) && wp_delete_post($post_id, true)) {
        return ['success' => true];
    }

    return new WP_Error('erro_ao_remover', 'Erro ao remover a notícia ou notícia não encontrada.', ['status' => 404]);
}
// Função para remover categorias
function remover_categoria($request) {
    $categoria_id = intval($request->get_param('id'));

    // Verifica se a categoria existe
    $term = get_term($categoria_id, 'category');
    if (is_wp_error($term) || !$term) {
        return new WP_Error('categoria_nao_encontrada', 'Categoria não encontrada.', ['status' => 404]);
    }

    // Remove a categoria
    $result = wp_delete_term($categoria_id, 'category');
    if (is_wp_error($result)) {
        return new WP_Error('erro_ao_remover_categoria', 'Erro ao remover a categoria.', ['status' => 500]);
    }

    return new WP_REST_Response(['success' => true], 200);
}

function criar_nova_pagina() {
    // Verifica se a página já existe
    $pagina_titulo = 'Minha Nova Página';
    $pagina_check = get_page_by_title($pagina_titulo);
    
    // Se a página não existir, cria uma nova
    if (!isset($pagina_check->ID)) {
        $pagina_id = wp_insert_post(array(
            'post_title'    => $pagina_titulo,
            'post_content'  => 'Conteúdo da minha nova página.',
            'post_status'   => 'publish',
            'post_type'     => 'page',
        ));
    }
}





	

	