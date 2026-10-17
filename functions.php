<?php
/**
 * テーマの基本設定
 */
function aa5_setup() {
	// <title>タグをWordPressに出力させる
	add_theme_support( 'title-tag' );
	// 投稿のアイキャッチ画像を使えるようにする
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'aa5_setup' );

// よくあるご質問（管理画面から追加・編集）
require get_template_directory() . '/inc/faq.php';

/**
 * お知らせのカテゴリ（スラッグ => 表示名）
 * 投稿のカテゴリは、このスラッグで作成する
 */
function aa5_news_categories() {
	return array(
		'usagi'   => 'うさぎの森',
		'oyako'   => 'おやこえいご',
		'chiiku'  => 'ちいくのたね',
		'after'   => 'キッズアフター',
		'phonics' => 'もりのフォニックス',
		'step'    => 'ステップアップ',
		'eiken'   => 'そらとぶ英検',
	);
}

/**
 * お知らせのページ・投稿ページにbodyクラスを追加（style.cssの .page-news-detail 用）
 */
function aa5_body_class( $classes ) {
	if ( is_page( 'news' ) || is_singular( 'post' ) ) {
		$classes[] = 'page-news-detail';
		$classes[] = 'page-news';
	}
	return $classes;
}
add_filter( 'body_class', 'aa5_body_class' );

/**
 * CSS・JSの読み込み
 */
function aa5_enqueue_scripts() {
	$theme_uri  = get_template_directory_uri();
	$theme_path = get_template_directory();

	// Google Fonts
	wp_enqueue_style(
		'aa5-fonts',
		'https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;700;800&family=Zen+Maru+Gothic:wght@400;500;700;900&display=swap',
		array(),
		null
	);

	// 共通CSS
	wp_enqueue_style(
		'aa5-style',
		get_stylesheet_uri(),
		array( 'aa5-fonts' ),
		filemtime( $theme_path . '/style.css' )
	);

	// 3Dモデル表示（トップページのみ）
	if ( is_front_page() ) {
		wp_enqueue_script_module(
			'model-viewer',
			'https://unpkg.com/@google/model-viewer@3.5.0/dist/model-viewer.min.js',
			array(),
			null
		);
	}

	// ページ別CSS（固定ページのスラッグに応じて読み込み）
	$page_styles = array( 'about', 'course', 'news', 'faq', 'contact' );
	foreach ( $page_styles as $slug ) {
		if ( is_page( $slug ) || ( 'news' === $slug && ( ( is_home() && ! is_front_page() ) || is_singular( 'post' ) || is_archive() ) ) ) {
			wp_enqueue_style(
				'aa5-' . $slug,
				$theme_uri . '/css/' . $slug . '.css',
				array( 'aa5-style' ),
				filemtime( $theme_path . '/css/' . $slug . '.css' )
			);
		}
	}

	// 共通JS（フッターで読み込み）
	wp_enqueue_script(
		'aa5-main',
		$theme_uri . '/main.js',
		array(),
		filemtime( $theme_path . '/main.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'aa5_enqueue_scripts' );
