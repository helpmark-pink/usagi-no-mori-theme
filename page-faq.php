<?php
/**
 * よくあるご質問のページ（固定ページ スラッグ：faq）
 */

// このページ用のbodyクラスを付ける（style.css・faq.cssの指定用）
add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = 'page-news-detail';
		$classes[] = 'page-faq';
		return $classes;
	}
);

get_header();

$img = get_template_directory_uri() . '/images';
?>

<main id="top">

<!-- ============ よくあるご質問 ============ -->
<section class="news-detail faq-page">
  <svg class="news-detail__cloud" viewBox="0 0 240 150" aria-hidden="true"><path d="M0 0h240v70q-10 40-50 34-14 36-60 30-30 24-66 6-30 8-44-18-20-6-20-30z" fill="#fff"/></svg>
  <div class="wrap faq-page__inner">
    <h1 class="heading heading--center news-detail__heading">FAQ<small>よくあるご質問</small></h1>

    <?php $faq_groups = aa5_get_faq_groups(); ?>

    <!-- カテゴリへ移動 -->
    <nav class="faq-jump" aria-label="質問のカテゴリ">
      <ul>
        <?php foreach ( $faq_groups as $group ) : ?>
        <li><a href="#faq-<?php echo esc_attr( $group['term']->slug ); ?>"><?php echo esc_html( $group['term']->name ); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <!-- 質問（管理画面の「よくあるご質問」で追加・編集） -->
    <?php foreach ( $faq_groups as $group ) : ?>
    <section class="faq-cat" id="faq-<?php echo esc_attr( $group['term']->slug ); ?>">
      <h2 class="faq-cat__title"><?php echo esc_html( $group['term']->name ); ?></h2>
      <ul class="faq-list">
        <?php foreach ( $group['items'] as $faq ) : ?>
        <li class="faq-item">
          <button class="faq-item__q" type="button" aria-expanded="false"><?php echo esc_html( $faq->post_title ); ?></button>
          <div class="faq-item__a"><?php echo apply_filters( 'the_content', $faq->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
        </li>
        <?php endforeach; ?>
      </ul>
    </section>
    <?php endforeach; ?>

    <p class="faq-page__more">ここにないご質問は、お気軽に<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a>ください。</p>
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
