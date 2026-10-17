<?php
/**
 * ページが見つからないとき（404）
 */
add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = 'page-news-detail';
		return $classes;
	}
);

get_header();
?>

<main id="top">

<!-- ============ 404 ============ -->
<section class="news-detail not-found">
  <svg class="news-detail__cloud" viewBox="0 0 240 150" aria-hidden="true">
    <path d="M0 0h240v70q-10 40-50 34-14 36-60 30-30 24-66 6-30 8-44-18-20-6-20-30z" fill="#fff"/>
  </svg>

  <div class="wrap wrap--narrow">
    <h1 class="heading heading--center news-detail__heading">404<small>ページが見つかりませんでした</small></h1>

    <div class="not-found__body">
      <img class="not-found__pic" src="<?php echo esc_url( get_template_directory_uri() . '/images/img-picture/13.webp' ); ?>" width="500" height="500" alt="">
      <p>お探しのページは、移動または削除されたか、URLがまちがっている可能性があります。<br>
      お手数ですが、トップページやメニューからお探しください。</p>
    </div>

    <p class="not-found__actions">
      <a class="pill pill--blue" href="<?php echo esc_url( home_url( '/' ) ); ?>">トップページへ</a>
      <a class="pill pill--white" href="<?php echo esc_url( home_url( '/news/' ) ); ?>">お知らせのページへ</a>
    </p>
  </div>
</section>

</main>

<?php get_footer(); ?>
