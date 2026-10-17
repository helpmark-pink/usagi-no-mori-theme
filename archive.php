<?php
/**
 * お知らせの一覧ページ（カテゴリ・タグ・日付・投稿者ごとの一覧、検索結果）
 * 見た目はお知らせのページ（page-news.php）と同じ。「つづきを見る」は main.js で行う
 */

// お知らせのページと同じCSS（検索結果のページでも読み込む）
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'aa5-news',
			get_template_directory_uri() . '/css/news.css',
			array( 'aa5-style' ),
			filemtime( get_template_directory() . '/css/news.css' )
		);
	},
	20
);

add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = 'page-news-detail';
		$classes[] = 'page-news';
		return $classes;
	}
);

get_header();

// 見出しの下に出す名前（カテゴリ名・「2026年9月」・検索語など）
if ( is_search() ) {
	$archive_label = '「' . get_search_query() . '」の検索結果';
} elseif ( is_category() || is_tag() ) {
	$archive_label = single_term_title( '', false );
} else {
	$archive_label = wp_strip_all_tags( get_the_archive_title() );
}

// この一覧の投稿をすべて取得（ページ送りではなく「つづきを見る」で表示するため）
// （URLから読み取った条件 = カテゴリ・日付・検索語などはそのまま使う）
global $wp_query;
$news_query = new WP_Query(
	array_merge(
		$wp_query->query,
		array(
			'post_type'      => 'post',
			'posts_per_page' => -1,
			'paged'          => 1,
			'no_found_rows'  => true,
		)
	)
);
?>

<main id="top">

<!-- ============ お知らせの一覧 ============ -->
<section class="news-detail news-archive">
  <svg class="news-detail__cloud" viewBox="0 0 240 150" aria-hidden="true">
    <path d="M0 0h240v70q-10 40-50 34-14 36-60 30-30 24-66 6-30 8-44-18-20-6-20-30z" fill="#fff"/>
  </svg>

  <div class="wrap">
    <h1 class="heading heading--center news-detail__heading">News<small><?php echo esc_html( $archive_label ); ?></small></h1>

    <ul class="news-list" id="newsList">
      <?php
      while ( $news_query->have_posts() ) :
        $news_query->the_post();
        get_template_part( 'template-parts/news-item' );
      endwhile;
      wp_reset_postdata();
      ?>
    </ul>
    <p class="news-empty"<?php echo $news_query->post_count ? ' hidden' : ''; ?>>お知らせは、まだありません。</p>

    <p class="news-more"><button class="news-more__btn" type="button">つづきを見る</button></p>
    <p class="article__back"><a class="pill pill--white" href="<?php echo esc_url( home_url( '/news/' ) ); ?>">お知らせのページへもどる</a></p>
  </div>
</section>

</main>

<?php get_footer(); ?>
