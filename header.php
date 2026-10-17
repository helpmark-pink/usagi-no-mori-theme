<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
// ページごとの説明文（検索結果に表示される文章）
$descriptions = array(
	'news'    => '大阪府うさぎ野市の子ども専門英会話スクール「うさぎの森 えいごスクール」のお知らせのページです。イベント・入会情報や各クラスのようすをお届けします。',
	'about'   => 'うさぎの森 えいごスクールが大切にしていることと、教室のご案内（所在地・アクセス・各階のマップ）です。大阪府うさぎ野市、もりのまち駅から徒歩4分。',
	'course'  => 'うさぎの森 えいごスクールのコース紹介。1さいごろの親子クラスから、ちいく・キッズ英会話・フォニックス・小学生クラス・英検対策まで、6つのコースをご案内します。',
	'faq'     => 'うさぎの森 えいごスクールによく寄せられるご質問をまとめました。レッスン・お預かり、カリキュラム、コース、持ちもの、おやつ、教室、入会・料金、アクセスについてお答えします。',
	'contact' => 'うさぎの森 えいごスクールへのお問い合わせはこちら。無料体験レッスン・見学・入会・採用についてなど、メールフォームまたはお電話でお気軽にご連絡ください。',
);
$description = '大阪府うさぎ野市の子ども専門英会話スクール。1さいごろの親子クラスから小学生まで、日本のこころと世界で通じる英語の力を育てます。無料体験レッスン受付中。';

if ( is_page() && isset( $descriptions[ get_post_field( 'post_name', get_queried_object_id() ) ] ) ) {
	$description = $descriptions[ get_post_field( 'post_name', get_queried_object_id() ) ];
} elseif ( is_singular( 'post' ) ) {
	// お知らせ：本文の書き出し（約60文字）＋スクール名
	$excerpt     = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', get_queried_object_id() ) ) ) ) );
	$excerpt     = mb_strimwidth( $excerpt, 0, 120, '…', 'UTF-8' );
	$description = ( $excerpt ? $excerpt : get_the_title( get_queried_object_id() ) ) . ' 大阪府うさぎ野市の子ども専門英会話スクール「うさぎの森 えいごスクール」のお知らせです。';
} elseif ( is_archive() || is_search() ) {
	$description = $descriptions['news'];
}
?>
<meta name="description" content="<?php echo esc_attr( $description ); ?>">
<link rel="icon" href="<?php echo esc_url( get_template_directory_uri() . '/images/logo-usagi.svg' ); ?>" type="image/svg+xml">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<?php if ( is_front_page() ) : ?>
<!-- トップページ：ロード画面を出す目印（JSが動かない環境ではロード画面を出さない）
     リロードしてもブラウザが前のスクロール位置に戻さないようにする（ロード後に main.js で一番上へ） -->
<script>
document.documentElement.classList.add('is-loading');
if ('scrollRestoration' in history) history.scrollRestoration = 'manual';
</script>
<?php endif; ?>

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php $img = get_template_directory_uri() . '/images'; ?>

<?php if ( is_front_page() ) : ?>
<!-- ============ ロード画面（画像・3Dモデルがすべて読み込まれたら main.js で消す） ============ -->
<div class="loader" id="loader" role="status" aria-label="読み込み中">
  <div class="loader__inner">
    <img class="loader__logo" src="<?php echo esc_url( $img . '/logo-usagi.svg' ); ?>" width="128" height="128" alt="" fetchpriority="high">
    <p class="model-loading__text loader__text">Loading<span>.</span><span>.</span><span>.</span></p>
  </div>
</div>
<?php endif; ?>

<!-- ============ ヘッダー ============ -->
<header class="site-header">
  <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
    <img src="<?php echo esc_url( $img . '/logo-usagi.svg' ); ?>" width="40" height="40" alt="">
    <span class="logo__text"><strong>うさぎの森</strong><small>ENGLISH SCHOOL</small></span>
  </a>

  <div class="header-actions">
    <a class="pill pill--blue" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a>
  </div>

  <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="drawer">
    <span class="nav-toggle__bar"></span>
    <span class="visually-hidden">メニューを開く</span>
  </button>
</header>

<div class="drawer" id="drawer" hidden>
  <nav class="drawer__inner" aria-label="メインメニュー">

    <!-- 1列目：トップ・お知らせ -->
    <div class="drawer__col">
      <p class="drawer__head"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">トップページ</a></p>
      <p class="drawer__head"><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">お知らせ</a></p>
      <ul class="drawer__sub">
        <li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">お知らせのページ</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#news' ) ); ?>">イベント・入会情報</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#news' ) ); ?>">クラスのようす</a></li>
      </ul>
      <p class="drawer__head"><a href="<?php echo esc_url( home_url( '/#concept' ) ); ?>">コンセプト</a></p>
    </div>

    <!-- 2列目：コース（カード） -->
    <div class="drawer__col drawer__col--course">
      <p class="drawer__head"><a href="<?php echo esc_url( home_url( '/course/' ) ); ?>">コース</a></p>
      <ul class="drawer__courses">
        <li><a class="drawer__course" href="<?php echo esc_url( home_url( '/course/#oyako' ) ); ?>"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/img-picture/2.webp' ); ?>" width="500" height="500" alt=""><span>おやこえいごクラス</span></a></li>
        <li><a class="drawer__course" href="<?php echo esc_url( home_url( '/course/#chiiku' ) ); ?>"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/img-picture/1.webp' ); ?>" width="500" height="500" alt=""><span>ちいくのたねクラス</span></a></li>
        <li><a class="drawer__course" href="<?php echo esc_url( home_url( '/course/#after' ) ); ?>"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/img-picture/7.webp' ); ?>" width="500" height="500" alt=""><span>キッズアフタークラス</span></a></li>
        <li><a class="drawer__course" href="<?php echo esc_url( home_url( '/course/#phonics' ) ); ?>"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/img-picture/6.webp' ); ?>" width="500" height="500" alt=""><span>もりのフォニックス</span></a></li>
        <li><a class="drawer__course" href="<?php echo esc_url( home_url( '/course/#step' ) ); ?>"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/img-picture/14.webp' ); ?>" width="500" height="500" alt=""><span>ステップアップジュニア</span></a></li>
        <li><a class="drawer__course" href="<?php echo esc_url( home_url( '/course/#eiken' ) ); ?>"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/img-picture/3.webp' ); ?>" width="500" height="500" alt=""><span>そらとぶ英検クラス</span></a></li>
      </ul>
    </div>

    <!-- 3列目：こだわり・FAQ・採用・ボタン -->
    <div class="drawer__col">
      <p class="drawer__head"><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><span class="nowrap">うさぎの森の</span><span class="nowrap">こだわり</span></a></p>
      <ul class="drawer__sub">
        <li><a href="<?php echo esc_url( home_url( '/#concept' ) ); ?>">教育の考え方</a></li>
        <li><a href="<?php echo esc_url( home_url( '/about/#map' ) ); ?>">教室のようす</a></li>
        <li><a href="<?php echo esc_url( home_url( '/about/#facility' ) ); ?>">アクセス</a></li>
      </ul>
      <p class="drawer__head"><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">よくあるご質問</a></p>
      <p class="drawer__head"><a href="<?php echo esc_url( home_url( '/#recruit' ) ); ?>">採用情報</a></p>
      <p class="drawer__small"><a href="<?php echo esc_url( home_url( '/contact/#privacy' ) ); ?>">個人情報の取り扱い</a></p>

      <a class="drawer__btn drawer__btn--mail" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2.5" y="5" width="19" height="14" rx="2" fill="#fff"/><path d="M3.5 6.5l8.5 6.5 8.5-6.5" fill="none" stroke="#4FA83C" stroke-width="1.8" stroke-linejoin="round"/></svg>
        <span>お問い合わせ</span>
      </a>
      <a class="drawer__btn drawer__btn--insta" href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">
        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="17.3" cy="6.7" r="1.2" fill="currentColor"/></svg>
        <span>Instagram</span>
      </a>
    </div>
  </nav>

  <!-- 下に流れる大きな文字 -->
  <div class="marquee drawer__marquee" aria-hidden="true">
    <div class="marquee__track">
      <span>USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> </span>
      <span>USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> </span>
    </div>
  </div>
</div>
<!-- ドロワー表示中に背景をうす暗くするマスク -->
<div class="drawer-mask" id="drawerMask" hidden></div>
