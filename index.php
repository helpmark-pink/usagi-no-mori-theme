<?php get_header(); ?>
<?php
$theme_uri = get_template_directory_uri();
$img       = $theme_uri . '/images';

/**
 * お知らせ欄：新しい投稿8件を取得し、1〜4件目を「イベント＆入会のお知らせ」、5〜8件目を「最近のできごと」に出す
 * （お知らせのページ・個別ページと同じ投稿を使うので、投稿を追加・編集するとトップにも反映される）
 */
$latest_news = get_posts(
	array(
		'post_type'      => 'post',
		'posts_per_page' => 8,
	)
);
$news_pins  = array_slice( $latest_news, 0, 4 );
$news_cards = array_slice( $latest_news, 4, 4 );

// カテゴリ（スラッグ）→ タグの色
$news_tag_colors = array(
	'usagi'   => 'blue',
	'oyako'   => 'green',
	'chiiku'  => 'yellow',
	'after'   => 'coral',
	'phonics' => 'blue',
	'step'    => 'green',
	'eiken'   => 'purple',
);

// 投稿のカテゴリのうち、お知らせ用の最初の1つを「色・表示名」で返す
$aa5_news_tag = function ( $post ) use ( $news_tag_colors ) {
	$labels = aa5_news_categories();
	foreach ( get_the_category( $post->ID ) as $cat ) {
		if ( isset( $labels[ $cat->slug ] ) ) {
			return array( 'color' => $news_tag_colors[ $cat->slug ], 'label' => $labels[ $cat->slug ] );
		}
	}
	return null;
};
?>

<main id="top">

<!-- ============ ヒーロー ============ -->
<section class="hero">

  <!-- 写真の雲形マスク・地球のクレヨン線（共通定義） -->
  <svg class="hero__defs" width="0" height="0" aria-hidden="true" focusable="false">
    <defs>
      <clipPath id="cloudClipA" clipPathUnits="objectBoundingBox">
        <path d="M.5 .03C.61 0 .71 .05 .76 .13C.87 .11 .97 .2 .96 .33C1 .43 .99 .56 .93 .63C.96 .77 .87 .9 .73 .9C.65 .99 .51 1 .42 .95C.31 1 .17 .96 .13 .85C.02 .8 0 .66 .05 .57C0 .46 .02 .32 .1 .26C.11 .13 .24 .04 .36 .08C.4 .04 .45 .03 .5 .03Z"/>
      </clipPath>
      <clipPath id="cloudClipB" clipPathUnits="objectBoundingBox">
        <path d="M.44 .02C.56 -.01 .66 .06 .7 .14C.83 .08 .97 .16 .98 .3C1 .42 .95 .5 .9 .55C.99 .66 .96 .82 .84 .88C.76 .98 .6 1 .5 .94C.39 1 .22 .98 .16 .88C.04 .84 0 .7 .06 .6C0 .5 .01 .36 .09 .29C.08 .15 .2 .05 .32 .07C.36 .04 .4 .03 .44 .02Z"/>
      </clipPath>
      <filter id="crayon" x="-5%" y="-5%" width="110%" height="110%">
        <feTurbulence type="fractalNoise" baseFrequency=".8" numOctaves="2" seed="3"/>
        <feDisplacementMap in="SourceGraphic" scale="5"/>
      </filter>
    </defs>
  </svg>

  <div class="hero__visual">
    <div class="hero__stage">

      <div class="hero__photo hero__photo--a"><img src="<?php echo esc_url( $img . '/hero-photo-01.jpg' ); ?>" alt=""></div>
      <div class="hero__photo hero__photo--b"><img src="<?php echo esc_url( $img . '/hero-photo-02.jpg' ); ?>" alt=""></div>
      <div class="hero__photo hero__photo--c"><img src="<?php echo esc_url( $img . '/hero-photo-03.jpg' ); ?>" alt=""></div>

      <div class="hero__cloud" aria-hidden="true"></div>

      <div class="hero__globe">
        <svg viewBox="0 0 400 400" aria-hidden="true">
          <g filter="url(#crayon)">
            <circle cx="200" cy="200" r="188" fill="#3FA7EE"/>
            <path d="M92 60q54-40 104-18t30 58-58 20-30 64 48 40-4 38-64-6-42-60 4-72 12-64z" fill="#C8DB7C"/>
            <path d="M232 86q18-12 30 2t-8 26-28 4 6-32z" fill="#C8DB7C"/>
            <path d="M196 140q20-6 22 12t-18 22-14-16 10-18z" fill="#C8DB7C"/>
            <path d="M280 54q46 8 76 50t14 70-44-8-42-26-26-40 22-46z" fill="#C8DB7C"/>
            <path d="M232 230q30-26 58 0t22 60-44 30-44-30 8-60z" fill="#C8DB7C"/>
            <path d="M120 250q18-8 26 6t-10 20-22-6 6-20z" fill="#C8DB7C"/>
            <path d="M340 250q22 2 18 30t-30 34-8-26 20-38z" fill="#C8DB7C"/>
          </g>
        </svg>

        <model-viewer
          id="heroModel"
          data-src="<?php echo esc_url( $theme_uri . '/models/teacher.glb' ); ?>"
          alt="うさぎの森 えいごスクールの先生キャラクター"
          camera-controls disable-zoom disable-pan autoplay
          max-camera-orbit="auto 90deg auto"
          interaction-prompt="none" shadow-intensity="0"
          exposure="1.05" environment-image="neutral"
          touch-action="pan-y" loading="eager">
          <!-- 読み込み中の表示（うさぎモデルと共通。モデルが出ると自動で消える） -->
          <div class="model-loading" slot="poster">
            <img class="model-loading__logo" src="<?php echo esc_url( $img . '/logo-usagi.svg' ); ?>" width="96" height="96" alt="">
            <p class="model-loading__text">Loading<span>.</span><span>.</span><span>.</span></p>
          </div>
        </model-viewer>
        <p class="stage__hint">Please drag to spin me!</p>
        <span class="bubble bubble--teacher">Let's speak English!</span>
      </div>

      <!-- イラスト（images/img-picture） -->
      <img class="hero__pic hero__pic--rainbow" src="<?php echo esc_url( $img . '/img-picture/3.png' ); ?>" width="500" height="500" alt="">
      <img class="hero__pic hero__pic--redflower" src="<?php echo esc_url( $img . '/img-picture/6.png' ); ?>" width="500" height="500" alt="">
      <img class="hero__pic hero__pic--tulip" src="<?php echo esc_url( $img . '/img-picture/1.png' ); ?>" width="500" height="500" alt="">
      <img class="hero__pic hero__pic--bee" src="<?php echo esc_url( $img . '/img-picture/7.png' ); ?>" width="500" height="500" alt="">
      <img class="hero__pic hero__pic--daisy" src="<?php echo esc_url( $img . '/img-picture/5.png' ); ?>" width="500" height="500" alt="">
      <img class="hero__pic hero__pic--ladybug" src="<?php echo esc_url( $img . '/img-picture/9.png' ); ?>" width="500" height="500" alt="">
      <img class="hero__pic hero__pic--yellow" src="<?php echo esc_url( $img . '/img-picture/4.png' ); ?>" width="500" height="500" alt="">

      <svg class="hero__doodle hero__doodle--zigzag" viewBox="0 0 220 120" aria-hidden="true">
        <path d="M4 114L64 26l6 80 40-70 12 62L214 6" stroke="#1FA3E6" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>

    <div class="hero__copy">
      <h1 class="hero__title">
        <span class="line">Where</span>
        <span class="line">English</span>
        <span class="line">Blooms</span>
      </h1>
      <p class="hero__sub">「話したい」が芽を出す場所。</p>
    </div>
  </div>

  <p class="hero__lead js-lines">
    <span class="lead-line">「はじめて聞く英語」から</span>
    <span class="lead-line" style="--i:1">「自分のことばで話せる力」へ。</span>
    <span class="lead-line" style="--i:2">一歩ずつ進める、うさぎの森のえいごプログラム</span>
  </p>

  <div class="hero__foot" aria-hidden="true">
    <img class="hero__usagi" src="<?php echo esc_url( $img . '/img-picture/2.png' ); ?>" alt="">
    <svg class="hero__hello" viewBox="0 0 130 80">
      <path d="M14 46q-10-26 30-36t66 4 6 34-50 14l-26 14 6-14q-26-2-32-16z" fill="#fff" stroke="#1C6FB8" stroke-width="3" stroke-linejoin="round"/>
      <text x="64" y="44" text-anchor="middle" transform="rotate(-14 64 40)" font-family="'Baloo 2', sans-serif" font-weight="800" font-size="20" fill="#1C6FB8">Hi!!</text>
    </svg>
    <svg class="hero__spiral" viewBox="0 0 340 200">
      <path d="M6 196q20-80 70-96t40 30-30 20 10-60 90-40 40 30-30 20 20-50 120-44" stroke="#F27A1A" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </div>

  <div class="hero__arc" aria-hidden="true">
    <svg viewBox="0 0 1440 160" preserveAspectRatio="none">
      <path d="M0 160V90Q720-50 1440 90v70z" fill="#FFD23F"/>
    </svg>
  </div>
</section>

<!-- ============ コンセプト ============ -->
<section class="section concept" id="concept">
  <div class="wrap">
    <div class="blob">
      <div class="blob__inner js-lines js-lines--early">
        <p class="blob__label lead-line">Concept</p>
        <h2 class="blob__title lead-line" style="--i:1">“世界にはばたく子”を育てる<br>ちょっと新しい英語の森</h2>
        <p class="blob__text lead-line" style="--i:2">
          日本のことばと心を大切にしながら、<br>
          海外でも通じる英語の力を。<br>
          あいさつや思いやりを忘れない<br>
          子どものための英語スクール、<br>
          それがうさぎの森です。
        </p>
      </div>
      <!-- 吹き出しの下にイラストを置き、しゃべっているように見せる -->
      <div class="talk talk--1">
        <span class="bubble">Here we go!!</span>
        <img loading="lazy" decoding="async" class="talk__pic talk__pic--cloud" src="<?php echo esc_url( $img . '/img-picture/10.webp' ); ?>" width="500" height="500" alt="">
      </div>
      <div class="talk talk--2">
        <span class="bubble">Ready?</span>
        <img loading="lazy" decoding="async" class="talk__pic talk__pic--flower" src="<?php echo esc_url( $img . '/img-picture/11.webp' ); ?>" width="500" height="500" alt="">
      </div>
    </div>
  </div>
</section>

<!-- ============ マーキー ============ -->
<div class="marquee" aria-hidden="true">
  <div class="marquee__track">
    <span>USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> </span>
    <span>USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> </span>
  </div>
</div>

<!-- ============ お知らせ ============ -->
<section class="section news" id="news">
  <div class="wrap">
    <div class="news__panel">
      <h2 class="heading">News<small>お知らせ</small></h2>

      <div class="news__box">
      <img loading="lazy" decoding="async" class="news__deco" src="<?php echo esc_url( $img . '/img-picture/6.webp' ); ?>" width="500" height="500" alt="">
      <p class="news__sub">イベント＆入会のお知らせ</p>
      <ul class="news-pins">
        <?php foreach ( $news_pins as $news_post ) : $tag = $aa5_news_tag( $news_post ); ?>
        <li class="news-pin reveal">
          <p class="news-pin__text"><a class="news-card__link" href="<?php echo esc_url( get_permalink( $news_post ) ); ?>"><?php echo esc_html( get_the_title( $news_post ) ); ?></a></p>
          <div class="news-pin__meta">
            <?php if ( $tag ) : ?><span class="tag tag--<?php echo esc_attr( $tag['color'] ); ?>"><?php echo esc_html( $tag['label'] ); ?></span><?php endif; ?>
            <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $news_post ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d', $news_post ) ); ?></time>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
      </div>

      <div class="news__box">
      <p class="news__sub">最近のできごと</p>
      <ul class="news-cards">
        <?php foreach ( $news_cards as $news_post ) : $tag = $aa5_news_tag( $news_post ); ?>
        <?php if ( has_post_thumbnail( $news_post ) ) : ?>
        <li class="news-card reveal">
          <div class="news-card__thumb"><?php echo get_the_post_thumbnail( $news_post, 'medium_large', array( 'alt' => '' ) ); ?></div>
        <?php else : ?>
        <li class="news-card news-card--logo reveal">
          <div class="news-card__thumb news-card__thumb--logo">
            <img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/logo-usagi.svg' ); ?>" width="72" height="72" alt="">
          </div>
        <?php endif; ?>
          <div class="news-card__body">
            <?php if ( $tag ) : ?><span class="tag tag--<?php echo esc_attr( $tag['color'] ); ?>"><?php echo esc_html( $tag['label'] ); ?></span><?php endif; ?>
            <p><a class="news-card__link" href="<?php echo esc_url( get_permalink( $news_post ) ); ?>"><?php echo esc_html( get_the_title( $news_post ) ); ?></a></p>
            <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $news_post ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d', $news_post ) ); ?></time>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
      </div>

      <p class="news__more"><a class="pill pill--blue" href="<?php echo esc_url( home_url( '/news/' ) ); ?>">お知らせをもっと見る</a></p>
    </div>
  </div>
</section>

<!-- ============ ワンダフルワールド ============ -->
<section class="section world">
  <div class="wrap">
    <div class="world__grid">
      <!-- 背景の雲：外側(span)が横に流れ、中のsvgが上下にふわふわ。写真や地球より後ろに来るよう先頭に置く -->
      <span class="cloud cloud--1" aria-hidden="true"><svg viewBox="0 0 120 58"><g fill="#fff"><circle cx="22" cy="30" r="14"/><circle cx="42" cy="21" r="16"/><circle cx="64" cy="18" r="18"/><circle cx="87" cy="24" r="15"/><circle cx="104" cy="32" r="12"/><circle cx="36" cy="41" r="12"/><circle cx="60" cy="43" r="13"/><circle cx="84" cy="41" r="11"/><ellipse cx="62" cy="31" rx="42" ry="13"/></g></svg></span>
      <span class="cloud cloud--2" aria-hidden="true"><svg viewBox="0 0 120 58"><g fill="#fff"><circle cx="22" cy="30" r="14"/><circle cx="42" cy="21" r="16"/><circle cx="64" cy="18" r="18"/><circle cx="87" cy="24" r="15"/><circle cx="104" cy="32" r="12"/><circle cx="36" cy="41" r="12"/><circle cx="60" cy="43" r="13"/><circle cx="84" cy="41" r="11"/><ellipse cx="62" cy="31" rx="42" ry="13"/></g></svg></span>
      <span class="cloud cloud--3" aria-hidden="true"><svg viewBox="0 0 120 58"><g fill="#fff"><circle cx="22" cy="30" r="14"/><circle cx="42" cy="21" r="16"/><circle cx="64" cy="18" r="18"/><circle cx="87" cy="24" r="15"/><circle cx="104" cy="32" r="12"/><circle cx="36" cy="41" r="12"/><circle cx="60" cy="43" r="13"/><circle cx="84" cy="41" r="11"/><ellipse cx="62" cy="31" rx="42" ry="13"/></g></svg></span>
      <span class="cloud cloud--4" aria-hidden="true"><svg viewBox="0 0 120 58"><g fill="#fff"><circle cx="22" cy="30" r="14"/><circle cx="42" cy="21" r="16"/><circle cx="64" cy="18" r="18"/><circle cx="87" cy="24" r="15"/><circle cx="104" cy="32" r="12"/><circle cx="36" cy="41" r="12"/><circle cx="60" cy="43" r="13"/><circle cx="84" cy="41" r="11"/><ellipse cx="62" cy="31" rx="42" ry="13"/></g></svg></span>
      <span class="cloud cloud--5" aria-hidden="true"><svg viewBox="0 0 120 58"><g fill="#fff"><circle cx="22" cy="30" r="14"/><circle cx="42" cy="21" r="16"/><circle cx="64" cy="18" r="18"/><circle cx="87" cy="24" r="15"/><circle cx="104" cy="32" r="12"/><circle cx="36" cy="41" r="12"/><circle cx="60" cy="43" r="13"/><circle cx="84" cy="41" r="11"/><ellipse cx="62" cy="31" rx="42" ry="13"/></g></svg></span>
      <span class="cloud cloud--6" aria-hidden="true"><svg viewBox="0 0 120 58"><g fill="#fff"><circle cx="22" cy="30" r="14"/><circle cx="42" cy="21" r="16"/><circle cx="64" cy="18" r="18"/><circle cx="87" cy="24" r="15"/><circle cx="104" cy="32" r="12"/><circle cx="36" cy="41" r="12"/><circle cx="60" cy="43" r="13"/><circle cx="84" cy="41" r="11"/><ellipse cx="62" cy="31" rx="42" ry="13"/></g></svg></span>
      <svg class="globe globe--sm globe--a" viewBox="0 0 400 400" aria-hidden="true">
        <!-- ヒーローの地球と同じ色：青い海＋黄緑の陸地 -->
        <circle cx="200" cy="200" r="190" fill="#3FA7EE"/>
        <path d="M118 78q42-22 70 6t-6 54-24 46 34 30 54-12 30 26-42 40-70-6-48-52-26-40-18-56 24-14 46-36z" fill="#C8DB7C"/>
        <path d="M262 92q34-14 56 14t-14 44-52 4-20-38z" fill="#C8DB7C"/>
        <!-- 追加の大陸・島 -->
        <path d="M236 196q30-26 62-6t26 60-40 44-50-22-12-50 14-26z" fill="#C8DB7C"/>
        <path d="M322 150q30 6 40 40t-14 40-26-26 0-54z" fill="#C8DB7C"/>
        <path d="M84 250q26-10 44 10t6 50-36 26-30-34 16-52z" fill="#C8DB7C"/>
        <path d="M170 300q22-8 34 8t-8 30-30 0 4-38z" fill="#C8DB7C"/>
        <path d="M250 320q20-6 30 8t-10 24-24-8 4-24z" fill="#C8DB7C"/>
        <path d="M40 170q14-10 24 2t-4 26-22-4 2-24z" fill="#C8DB7C"/>
        <path d="M216 40q16-6 24 4t-8 18-18-4 2-18z" fill="#C8DB7C"/>
      </svg>

      <!-- キャッチコピー：地球の上側の弧に沿って配置（地球は回転するので別要素） -->
      <svg class="world__arc" viewBox="0 0 600 600" role="img" aria-label="Hello, big world!">
        <path id="worldArcPath" d="M30 300A270 270 0 0 1 570 300" fill="none"/>
        <text><textPath href="#worldArcPath" startOffset="50%" text-anchor="middle">\ Hello, big world! /</textPath></text>
      </svg>
      <img loading="lazy" decoding="async" class="world__flower" src="<?php echo esc_url( $img . '/img-picture/1.webp' ); ?>" width="500" height="500" alt="">
      <img loading="lazy" decoding="async" class="world__cactus" src="<?php echo esc_url( $img . '/img-picture/14.webp' ); ?>" width="500" height="500" alt="">

      <div class="world__photo world__photo--1 reveal"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/world-01.webp' ); ?>" alt=""></div>
      <div class="world__photo world__photo--2 reveal"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/world-02.webp' ); ?>" alt=""></div>

      <div class="world__mascot reveal">
        <model-viewer
          id="rabbitModel"
          data-src="<?php echo esc_url( $theme_uri . '/models/rabbit.glb' ); ?>"
          alt="スクールのマスコット、モリー"
          camera-controls disable-zoom disable-pan auto-rotate
          max-camera-orbit="auto 90deg auto"
          auto-rotate-delay="800" rotation-per-second="18deg"
          interaction-prompt="none" shadow-intensity="0"
          exposure="1.05" environment-image="neutral"
          touch-action="pan-y" loading="eager">
          <!-- 読み込み中の表示（モデルが出ると自動で消える） -->
          <div class="model-loading" slot="poster">
            <img loading="lazy" decoding="async" class="model-loading__logo" src="<?php echo esc_url( $img . '/logo-usagi.svg' ); ?>" width="96" height="96" alt="">
            <p class="model-loading__text">Loading<span>.</span><span>.</span><span>.</span></p>
          </div>
        </model-viewer>
      </div>

      <!-- 吹き出しはパララックスで動かすため、モデルの外に置く -->
      <span class="bubble bubble--3">Nice to meet you!</span>
    </div>
  </div>
</section>

<!-- ============ スクールについて ============ -->
<section class="section about" id="about">
  <div class="wrap wrap--narrow">
    <h2 class="heading heading--center heading--light">About<small>うさぎの森について</small></h2>
    <p class="about__text reveal">
      うさぎの森で育てたいのは、日本のこころと、世界で通用することばの力。
      あいさつや思いやりを土台にして、英語で伝える力と広い視野を身につけます。
    </p>
    <p class="about__text reveal">
      ここで積み重ねた経験は、幼稚園や小学校へ進む子どもたちの自信になり、
      はじめての場所でも「やってみよう」と踏み出す力になっていきます。
    </p>
    <p class="about__text reveal">
      ちいさな一歩から世界へつながる英語スクール。それがうさぎの森です。
    </p>
    <p class="about__more"><a class="pill pill--white" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">うさぎの森のこだわり ／ 教室のようす</a></p>

    <div class="about__photo reveal"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/about-01.webp' ); ?>" alt="教室で活動する子どもたち"></div>

    <!-- イラスト（images/img-picture） -->
    <img loading="lazy" decoding="async" class="deco-pic deco-pic--sparkle" src="<?php echo esc_url( $img . '/img-picture/15.webp' ); ?>" width="500" height="500" alt="">
    <img loading="lazy" decoding="async" class="deco-pic deco-pic--usagi" src="<?php echo esc_url( $img . '/img-picture/13.webp' ); ?>" width="500" height="500" alt="">
  </div>

  <div class="wave wave--to-white" aria-hidden="true">
    <svg viewBox="0 0 1440 140" preserveAspectRatio="none">
      <path d="M0 58q200 52 400 16t380-36 340 40 320-28v90H0z" fill="#fff"/>
    </svg>
  </div>
</section>

<!-- ============ コース紹介 ============ -->
<section class="section course" id="course">
  <div class="wrap">
    <h2 class="heading heading--center">Course<small>コース紹介</small></h2>

    <div class="course__viewport">
      <button class="course__arrow course__arrow--prev" type="button" aria-label="前のコースを見る"></button>

      <!-- コース画像：images/course/course-01.png〜06.png（600×600px の透過PNG）を置くと表示される -->
      <ul class="course-list" id="courseList">
        <li class="course-card course-card--green reveal" style="--i:0">
          <span class="course-card__tag">1さいごろ〜</span>
          <div class="course-card__img"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/course/course-01.webp' ); ?>" width="600" height="600" alt=""></div>
          <h3>おやこえいごクラス</h3>
          <p>おうちの方といっしょに参加。<br>英語のうたと手あそびで、<br>はじめての英語にふれます。</p>
          <a class="pill" href="<?php echo esc_url( home_url( '/course/#oyako' ) ); ?>">もっと見る</a>
        </li>
        <li class="course-card course-card--yellow reveal" style="--i:1">
          <span class="course-card__tag">3さい〜</span>
          <div class="course-card__img"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/course/course-02.webp' ); ?>" width="600" height="600" alt=""></div>
          <h3>ちいくのたねクラス</h3>
          <p>かず・いろ・かたちを英語で。<br>考える力の芽を、<br>あそびながら育てます。</p>
          <a class="pill" href="<?php echo esc_url( home_url( '/course/#chiiku' ) ); ?>">もっと見る</a>
        </li>
        <li class="course-card course-card--coral reveal" style="--i:2">
          <span class="course-card__tag">年少さん〜</span>
          <div class="course-card__img"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/course/course-03.webp' ); ?>" width="600" height="600" alt=""></div>
          <h3>キッズアフタークラス</h3>
          <p>年少〜年長さん向け。<br>園のあとに、しっかり英語力を<br>のばすキッズ英会話。</p>
          <a class="pill" href="<?php echo esc_url( home_url( '/course/#after' ) ); ?>">もっと見る</a>
        </li>
        <li class="course-card course-card--sky reveal" style="--i:3">
          <span class="course-card__tag">年中〜年長さん</span>
          <div class="course-card__img"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/course/course-04.webp' ); ?>" width="600" height="600" alt=""></div>
          <h3>もりのフォニックス</h3>
          <p>文字と音をつなげる<br>フォニックスで、<br>はじめての「読む」に挑戦。</p>
          <a class="pill" href="<?php echo esc_url( home_url( '/course/#phonics' ) ); ?>">もっと見る</a>
        </li>
        <li class="course-card course-card--green reveal" style="--i:4">
          <span class="course-card__tag">小学1〜4年生</span>
          <div class="course-card__img"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/course/course-05.webp' ); ?>" width="600" height="600" alt=""></div>
          <h3>ステップアップジュニア</h3>
          <p>学校でも使える英語を、<br>話す・聞く・読む・書くの<br>4つの力で積み上げます。</p>
          <a class="pill" href="<?php echo esc_url( home_url( '/course/#step' ) ); ?>">もっと見る</a>
        </li>
        <li class="course-card course-card--purple reveal" style="--i:5">
          <span class="course-card__tag">小学3〜6年生</span>
          <div class="course-card__img"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/course/course-06.webp' ); ?>" width="600" height="600" alt=""></div>
          <h3>そらとぶ英検クラス</h3>
          <p>英検5級〜3級が目標。<br>話す力はそのままに、<br>合格までしっかり伴走します。</p>
          <a class="pill" href="<?php echo esc_url( home_url( '/course/#eiken' ) ); ?>">もっと見る</a>
        </li>
      </ul>

      <button class="course__arrow course__arrow--next" type="button" aria-label="次のコースを見る"></button>
    </div>
  </div>
</section>

<!-- ============ FAQ ============ -->
<section class="section faq" id="faq">
  <div class="wrap faq__grid">
    <div class="faq__head">
      <h2 class="heading">FAQ<small>よくあるご質問</small></h2>
      <a class="pill pill--blue" href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">質問をもっと見る</a>
    </div>

    <ul class="faq-list">
      <li class="faq-item">
        <button class="faq-item__q" type="button" aria-expanded="false">レッスン後のお預かりはできますか？</button>
        <div class="faq-item__a"><p>キッズアフタークラスは、レッスン後18:30までお預かりできます（事前予約制・別料金）。お迎えの時間に合わせてご利用ください。</p></div>
      </li>
      <li class="faq-item">
        <button class="faq-item__q" type="button" aria-expanded="false">お休みしたレッスンの振替について教えてください。</button>
        <div class="faq-item__a"><p>前日までにご連絡いただければ、同じ月のうちに別の曜日のクラスへ振替できます。空き状況は受付でお伝えします。</p></div>
      </li>
      <li class="faq-item">
        <button class="faq-item__q" type="button" aria-expanded="false">通いたい曜日に祝日が多いと、ほかの曜日よりレッスン回数が減ってしまいませんか？</button>
        <div class="faq-item__a"><p>年間のレッスン回数はどの曜日でも同じになるように組んでいます。祝日でお休みになる分は、補講日をご用意しています。</p></div>
      </li>
      <li class="faq-item">
        <button class="faq-item__q" type="button" aria-expanded="false">親が英語を話せなくても大丈夫ですか？おうちでも英語で話しかけるべきでしょうか？</button>
        <div class="faq-item__a"><p>日本人スタッフがいつもいるので、ご相談はすべて日本語で大丈夫です。おうちでは無理に英語を使わず、レッスンで覚えた歌やことばを一緒に楽しんでいただくのがいちばんです。</p></div>
      </li>
    </ul>
  </div>
</section>

<!-- ============ 採用 ============ -->
<section class="section recruit" id="recruit">
  <div class="wrap">
    <p class="recruit__catch">&#65340; Work with us&#65281; &#65295;</p>

    <div class="recruit__card reveal">
      <div class="recruit__body">
        <h2 class="heading">Recruit<small>採用情報</small></h2>
        <p>
          うさぎの森で、<br>
          子どもたちと一緒に笑える講師・スタッフを募集中です！
        </p>
        <div class="recruit__actions">
          <a class="pill pill--coral" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">募集要項をみる</a>
          <a class="pill pill--blue" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">採用について問い合わせる</a>
        </div>
      </div>
      <div class="recruit__photo"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/recruit-01.webp' ); ?>" alt="教室で子どもと話す先生"></div>
    </div>
  </div>
</section>

<!-- ============ ギャラリー ============ -->
<!-- マーキーと同じく、同じ5枚を3セット並べて左へ流し続ける（広い画面でも途切れないように3セット） -->
<div class="gallery" aria-hidden="true">
  <div class="gallery__track">
    <?php for ( $set = 0; $set < 3; $set++ ) : ?>
      <?php for ( $n = 1; $n <= 5; $n++ ) : ?>
    <div class="gallery__item"><img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/gallery-0' . $n . '.webp' ); ?>" alt=""></div>
      <?php endfor; ?>
    <?php endfor; ?>
  </div>
</div>

<div class="marquee" aria-hidden="true">
  <div class="marquee__track">
    <span>USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> </span>
    <span>USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> USAGI NO MORI <em>&#127807;</em> ENGLISH SCHOOL <em>&#128048;</em> </span>
  </div>
</div>

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
