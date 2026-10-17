<?php
/**
 * お知らせのページ（固定ページ スラッグ：news）
 */
get_header();

$categories = aa5_news_categories();

// 投稿をすべて取得（絞り込み・「つづきを見る」は main.js で行う）
$news_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'posts_per_page' => -1,
		'no_found_rows'  => true,
	)
);
?>

<main id="top">

<!-- ============ お知らせのページ ============ -->
<section class="news-detail news-archive">
  <!-- 左上の白い雲（詳細ページと共通の飾り） -->
  <svg class="news-detail__cloud" viewBox="0 0 240 150" aria-hidden="true">
    <path d="M0 0h240v70q-10 40-50 34-14 36-60 30-30 24-66 6-30 8-44-18-20-6-20-30z" fill="#fff"/>
  </svg>

  <div class="wrap">
    <h1 class="heading heading--center news-detail__heading">News<small>お知らせ</small></h1>

    <!-- カテゴリで絞り込み -->
    <nav class="news-filter" aria-label="カテゴリで絞り込む">
      <ul>
        <li><button class="news-filter__btn is-active" type="button" data-filter="all" aria-pressed="true">すべて</button></li>
        <?php foreach ( $categories as $slug => $label ) : ?>
        <li><button class="news-filter__btn" type="button" data-filter="<?php echo esc_attr( $slug ); ?>" aria-pressed="false"><?php echo esc_html( $label ); ?></button></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <!-- 一覧（最初は12件、「つづきを見る」で残りを表示） -->
    <ul class="news-list" id="newsList">
      <?php
      while ( $news_query->have_posts() ) :
        $news_query->the_post();
        get_template_part( 'template-parts/news-item' );
      endwhile;
      wp_reset_postdata();
      ?>
    </ul>
    <p class="news-empty"<?php echo $news_query->post_count ? ' hidden' : ''; ?>>このカテゴリのお知らせは、まだありません。</p>

    <p class="news-more"><button class="news-more__btn" type="button">つづきを見る</button></p>
  </div>
</section>

<!-- ============ お問い合わせ ============ -->
<section class="section contact" id="contact">
  <div class="wrap wrap--narrow">
    <h2 class="heading heading--center heading--light">Contact<small>お問い合わせ</small></h2>
    <div class="contact__actions">
      <a class="contact__btn" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
        <span>メールフォームで問い合わせる</span>
      </a>
      <a class="contact__btn contact__btn--tel" href="tel:0727000000">
        <strong>072-700-0000</strong>
        <small>お電話受付／月〜土 10:00〜18:00</small>
      </a>
    </div>
  </div>
</section>

</main>

<?php get_footer(); ?>
