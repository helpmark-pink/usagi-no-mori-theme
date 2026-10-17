<?php
/**
 * WordPress Playground のデモ用コンテンツ
 *
 * _playground/blueprint.json の runPHP ステップから読み込まれ、
 * 固定ページとお知らせを登録します。
 * （よくあるご質問は、テーマ側の aa5_seed_faq() が自動で登録します）
 * 通常のテーマ表示では読み込まれません。
 *
 * @package aa5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aa5_done = get_page_by_path( 'news' );
if ( $aa5_done ) {
	return;
}

/**
 * テーマ内の写真をメディアライブラリへ登録し、アイキャッチに設定する
 *
 * @param int    $post_id      対象の投稿ID。
 * @param string $relative_img テーマフォルダからの相対パス（例: images/news/news-01.jpg）。
 */
function aa5_demo_set_thumbnail( $post_id, $relative_img ) {
	$source = get_template_directory() . '/' . $relative_img;

	if ( ! file_exists( $source ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$filename = basename( $relative_img );
	$upload   = wp_upload_bits( $filename, null, file_get_contents( $source ) );

	if ( ! empty( $upload['error'] ) ) {
		return;
	}

	$filetype  = wp_check_filetype( $upload['file'], null );
	$attach_id = wp_insert_attachment(
		array(
			'post_mime_type' => $filetype['type'],
			'post_title'     => pathinfo( $filename, PATHINFO_FILENAME ),
			'post_status'    => 'inherit',
		),
		$upload['file'],
		$post_id
	);

	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
		return;
	}

	wp_update_attachment_metadata(
		$attach_id,
		wp_generate_attachment_metadata( $attach_id, $upload['file'] )
	);
	set_post_thumbnail( $post_id, $attach_id );
}

/**
 * 固定ページを作る。すでに同じスラッグがあればそのIDを返す。
 *
 * @param string $slug  スラッグ。テンプレートの page-{slug}.php と対応する。
 * @param string $title ページタイトル。
 * @return int 固定ページのID。
 */
function aa5_demo_add_page( $slug, $title ) {
	$existing = get_page_by_path( $slug );

	if ( $existing ) {
		return (int) $existing->ID;
	}

	return (int) wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_status'  => 'publish',
			'post_content' => '',
		)
	);
}

/* ------------------------------------------------------------------
   固定ページ
   中身はテンプレート（page-course.php など）が持っているので、
   ここではスラッグの合ったページを作るだけでよい。
   ------------------------------------------------------------------ */
$aa5_front_id = aa5_demo_add_page( 'home', 'うさぎの森 えいごスクール' );

aa5_demo_add_page( 'news', 'お知らせ' );
aa5_demo_add_page( 'course', 'コース' );
aa5_demo_add_page( 'about', 'うさぎの森のこだわり' );
aa5_demo_add_page( 'faq', 'よくあるご質問' );
aa5_demo_add_page( 'contact', 'お問い合わせ' );

// トップページを固定ページにして index.php をフロントページとして使わせる。
if ( $aa5_front_id ) {
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $aa5_front_id );
}

/* ------------------------------------------------------------------
   お知らせのカテゴリ
   スラッグは functions.php の aa5_news_categories() と合わせる。
   ------------------------------------------------------------------ */
$aa5_cat_ids = array();

foreach ( aa5_news_categories() as $aa5_slug => $aa5_name ) {
	$aa5_term = term_exists( $aa5_slug, 'category' );

	if ( ! $aa5_term ) {
		$aa5_term = wp_insert_term( $aa5_name, 'category', array( 'slug' => $aa5_slug ) );
	}

	if ( ! is_wp_error( $aa5_term ) ) {
		$aa5_cat_ids[ $aa5_slug ] = (int) $aa5_term['term_id'];
	}
}

/* ------------------------------------------------------------------
   お知らせ
   array( タイトル, カテゴリのスラッグ, 何日前か, 本文, 写真 )
   先頭4件がトップの「イベント＆入会のお知らせ」、
   次の4件が「最近のできごと」に出る。
   ------------------------------------------------------------------ */
$aa5_news = array(
	array(
		'春の無料体験レッスン、受付を始めました',
		'usagi',
		1,
		"4月から新しく始まるクラスの、無料体験レッスンの受付を開始しました。\n\nおやこえいごクラスからそらとぶ英検クラスまで、すべてのコースでご参加いただけます。お申し込みはメールフォーム、またはお電話で承っております。\n\n体験レッスンの前に教室の見学だけ、というご希望も大歓迎です。お気軽にお声がけください。",
		'',
	),
	array(
		'4月スタートクラスの時間割を公開しました',
		'usagi',
		4,
		"新年度の時間割が決まりました。コースのページから、曜日と時間をご確認いただけます。\n\n今年度は、キッズアフタークラスの水曜日を1枠増やしました。人数に限りがありますので、お早めにご相談ください。",
		'',
	),
	array(
		'おやこえいごクラスに、土曜日の枠ができました',
		'oyako',
		8,
		"平日はお仕事でむずかしい、というお声を多くいただき、土曜日の午前に新しい枠をつくりました。\n\n10:30からの45分間、1歳半から3歳のお子さまと保護者の方でご参加いただけます。ごきょうだいでのご参加もできます。",
		'',
	),
	array(
		'そらとぶ英検クラス、二次試験の対策講座を開きます',
		'eiken',
		13,
		"英検の二次試験（面接）に向けた、短期の対策講座を開きます。\n\n全3回で、入室から退室までの流れを実際に練習します。ネイティブ講師と日本人スタッフの2名体制で、ひとりずつ丁寧に見ていきます。",
		'',
	),
	array(
		'みんなでイースターエッグを作りました',
		'chiiku',
		17,
		"ちいくのたねクラスで、イースターエッグの色づけをしました。\n\n「pink!」「blue!」と色の名前を英語で言いながら、ひとりひとつずつ仕上げました。できあがった卵は、教室の入り口に飾っています。",
		'images/news/news-01.jpg',
	),
	array(
		'フォニックスの発表会をひらきました',
		'phonics',
		24,
		"もりのフォニックスのクラスで、半年間の練習の成果を発表しました。\n\n保護者の方の前で、ひとりずつ好きな絵本を読み上げました。はじめは小さかった声が、最後にはしっかり届く大きさになっていました。",
		'images/news/news-02.jpg',
	),
	array(
		'キッズアフタークラスで、おやつづくりをしました',
		'after',
		31,
		"お預かりの時間に、みんなでおにぎりを作りました。\n\n「rice」「salt」「nori」と材料の名前を確かめながら、自分の分をにぎります。いつもは食が細いお子さまも、自分で作った分はしっかり食べていました。",
		'images/news/news-03.jpg',
	),
	array(
		'ステップアップジュニアの様子をお伝えします',
		'step',
		38,
		"小学生のクラスでは、グループで英語の発表をする時間を取り入れています。\n\n今回のテーマは「わたしの好きな場所」。写真を見せながら、3文から5文で紹介しました。聞いている側も、英語で質問をひとつ考えます。",
		'',
	),
	array(
		'ゴールデンウィークの休校日のお知らせ',
		'usagi',
		45,
		"4月29日（水）と、5月3日（日）から5月6日（水）まで休校とさせていただきます。\n\nお預かりもお休みとなります。この期間のレッスンは、同じ月のうちに振替をご用意しますので、受付までご相談ください。",
		'',
	),
	array(
		'保護者ラウンジをリニューアルしました',
		'usagi',
		52,
		"4Fの保護者ラウンジに、新しく椅子とテーブルを入れました。\n\nWi-Fiと電源もお使いいただけます。レッスンのあいだ、お仕事や読書をしながらお待ちいただけます。教室のようすは、ラウンジのモニターからもご覧になれます。",
		'',
	),
);

foreach ( $aa5_news as $aa5_item ) {
	$aa5_post_id = wp_insert_post(
		array(
			'post_type'    => 'post',
			'post_title'   => $aa5_item[0],
			'post_content' => $aa5_item[3],
			'post_status'  => 'publish',
			'post_date'    => gmdate( 'Y-m-d H:i:s', strtotime( '-' . (int) $aa5_item[2] . ' days' ) ),
		)
	);

	if ( ! $aa5_post_id || is_wp_error( $aa5_post_id ) ) {
		continue;
	}

	if ( isset( $aa5_cat_ids[ $aa5_item[1] ] ) ) {
		wp_set_post_categories( $aa5_post_id, array( $aa5_cat_ids[ $aa5_item[1] ] ) );
	}

	if ( '' !== $aa5_item[4] ) {
		aa5_demo_set_thumbnail( $aa5_post_id, $aa5_item[4] );
	}
}

// 「未分類」に残るサンプル記事は消しておく。
$aa5_hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
if ( $aa5_hello ) {
	wp_delete_post( $aa5_hello->ID, true );
}

$aa5_sample = get_page_by_path( 'sample-page' );
if ( $aa5_sample ) {
	wp_delete_post( $aa5_sample->ID, true );
}

flush_rewrite_rules();
