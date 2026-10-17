<?php
/**
 * お知らせ一覧の1件（ループの中で get_template_part( 'template-parts/news-item' ) で読み込む）
 * お知らせのページ・カテゴリなどの一覧ページで共通
 */
$categories = aa5_news_categories();

// この投稿のカテゴリのうち、お知らせ用のものだけ使う
$post_cats = array();
foreach ( get_the_category() as $cat ) {
	if ( isset( $categories[ $cat->slug ] ) ) {
		$post_cats[ $cat->slug ] = $categories[ $cat->slug ];
	}
}

ob_start();
?>
<div class="news-item__tags">
  <?php foreach ( $post_cats as $slug => $label ) : ?><span class="news-tag news-tag--<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></span><?php endforeach; ?>
</div>
<?php
$tags_html = ob_get_clean();
?>
<li class="news-item" data-cat="<?php echo esc_attr( implode( ' ', array_keys( $post_cats ) ) ); ?>">
  <a class="news-item__link" href="<?php the_permalink(); ?>">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="news-item__thumb"><?php the_post_thumbnail( 'medium_large', array( 'alt' => '', 'loading' => 'lazy' ) ); ?><?php echo $tags_html; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
    <?php else : ?>
    <div class="news-item__thumb news-item__thumb--logo"><img loading="lazy" decoding="async" class="news-item__logo" src="<?php echo esc_url( get_template_directory_uri() . '/images/logo-usagi.svg' ); ?>" width="72" height="72" alt=""><?php echo $tags_html; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
    <?php endif; ?>
    <p class="news-item__title"><?php the_title(); ?></p>
    <time class="news-item__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
  </a>
</li>
