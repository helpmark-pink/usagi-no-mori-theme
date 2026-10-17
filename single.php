<?php
/**
 * お知らせ詳細（投稿の個別ページ）
 */
get_header();

$categories = aa5_news_categories();
$news_url   = home_url( '/news/' );
?>

<main id="top">

<?php while ( have_posts() ) : the_post(); ?>
<!-- ============ お知らせ詳細 ============ -->
<section class="news-detail">
  <!-- 左上の白い雲（見本の飾り） -->
  <svg class="news-detail__cloud" viewBox="0 0 240 150" aria-hidden="true">
    <path d="M0 0h240v70q-10 40-50 34-14 36-60 30-30 24-66 6-30 8-44-18-20-6-20-30z" fill="#fff"/>
  </svg>

  <div class="wrap">
    <h1 class="heading heading--center news-detail__heading">News<small>お知らせ</small></h1>

    <article class="article">
      <nav class="breadcrumb" aria-label="パンくずリスト">
        <ol>
          <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">TOP</a></li>
          <li><a href="<?php echo esc_url( $news_url ); ?>">お知らせ</a></li>
          <li aria-current="page"><?php the_title(); ?></li>
        </ol>
      </nav>

      <header class="article__head">
        <div class="article__tags">
          <?php foreach ( get_the_category() as $cat ) : ?>
            <?php if ( isset( $categories[ $cat->slug ] ) ) : ?>
          <span class="tag news-tag--<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $categories[ $cat->slug ] ); ?></span>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <h2 class="article__title"><?php the_title(); ?></h2>
        <time class="article__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
      </header>

      <?php if ( has_post_thumbnail() ) : ?>
      <figure class="article__main-photo reveal">
        <?php the_post_thumbnail( 'large' ); ?>
      </figure>
      <?php endif; ?>

      <div class="article__body">
        <?php the_content(); ?>
      </div>

      <p class="article__back"><a class="pill pill--white" href="<?php echo esc_url( $news_url ); ?>">お知らせのページへもどる</a></p>
    </article>
  </div>
</section>
<?php endwhile; ?>

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
