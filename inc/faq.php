<?php
/**
 * よくあるご質問（管理画面の「よくあるご質問」から追加・編集する）
 * ・質問 → タイトル、答え → 本文
 * ・カテゴリ（レッスン・お預かりについて など）を選ぶと、そのカテゴリの中に表示される
 * ・並び順は「ページ属性 → 順序」の小さい順
 */

/**
 * 投稿タイプ「よくあるご質問」と、カテゴリを登録
 */
function aa5_register_faq() {
	register_post_type(
		'faq',
		array(
			'labels'        => array(
				'name'          => 'よくあるご質問',
				'singular_name' => 'よくあるご質問',
				'add_new'       => '質問を追加',
				'add_new_item'  => '質問を追加',
				'edit_item'     => '質問を編集',
				'all_items'     => '質問の一覧',
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_rest'  => true,
			'menu_position' => 6,
			'menu_icon'     => 'dashicons-editor-help',
			'supports'      => array( 'title', 'editor', 'page-attributes' ),
		)
	);

	register_taxonomy(
		'faq_category',
		'faq',
		array(
			'labels'            => array(
				'name'          => 'カテゴリ',
				'singular_name' => 'カテゴリ',
				'add_new_item'  => 'カテゴリを追加',
			),
			'public'            => false,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'hierarchical'      => true,
		)
	);
}
add_action( 'init', 'aa5_register_faq' );

/**
 * 質問の一覧画面を「順序」の小さい順に並べる
 */
function aa5_faq_admin_order( $query ) {
	if ( is_admin() && $query->is_main_query() && 'faq' === $query->get( 'post_type' ) && ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'ASC' ) );
	}
}
add_action( 'pre_get_posts', 'aa5_faq_admin_order' );

/**
 * カテゴリごとの質問を取り出す（質問が1つもないカテゴリは除く）
 * 戻り値：array( array( 'term' => WP_Term, 'items' => WP_Post[] ), ... )
 */
function aa5_get_faq_groups() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'faq_category',
			'hide_empty' => true,
			'orderby'    => 'term_id', // 作った順（新しいカテゴリは最後に並ぶ）
		)
	);
	if ( is_wp_error( $terms ) ) {
		return array();
	}

	$groups = array();
	foreach ( $terms as $term ) {
		$items = get_posts(
			array(
				'post_type'      => 'faq',
				'posts_per_page' => -1,
				'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
				'tax_query'      => array(
					array(
						'taxonomy' => 'faq_category',
						'terms'    => $term->term_id,
					),
				),
			)
		);
		if ( $items ) {
			$groups[] = array( 'term' => $term, 'items' => $items );
		}
	}
	return $groups;
}

/**
 * 最初の1回だけ、これまでページに書いていた質問を登録する
 */
function aa5_seed_faq() {
	if ( get_option( 'aa5_faq_seeded' ) ) {
		return;
	}
	update_option( 'aa5_faq_seeded', 1 );

	$data = array(
		'lesson'     => array(
			'name'  => 'レッスン・お預かりについて',
			'items' => array(
				array( 'レッスンのあとも預かってもらえますか？', 'キッズアフタークラスでは、レッスン後18:30までのお預かりがあります（事前予約制・別料金）。早めのお預かりは15:00から、おやつつきでご利用いただけます。' ),
				array( 'お休みしたレッスンは、ほかの日に振り替えられますか？', '前日の18:00までにご連絡いただければ、同じ月のうちに別の曜日のクラスへ振替ができます。空き状況は受付またはお電話でお伝えします。' ),
				array( '通いたい曜日に祝日が多いのですが、ほかの曜日よりレッスン回数が少なくなりませんか？', '年間のレッスン回数は、どの曜日でも同じになるように組んでいます。祝日でお休みになった分は、補講日をご用意しています。' ),
				array( '体験レッスンの前に、教室の見学だけでもできますか？', 'はい、見学だけでも大歓迎です。レッスン中の様子を10〜15分ほどご覧いただけます。ご希望の日時をお問い合わせフォームからお知らせください。' ),
			),
		),
		'curriculum' => array(
			'name'  => 'カリキュラムについて',
			'items' => array(
				array( '親が英語を話せません。先生への相談はどうすればいいですか？おうちでも英語で話しかけたほうがよいでしょうか？', '日本人スタッフがいつもいるので、ご相談はすべて日本語で大丈夫です。おうちでは無理に英語を使わず、レッスンで覚えた歌やことばを一緒に楽しんでいただくのがいちばんです。' ),
				array( 'じっと座っているのが苦手です。レッスンについていけますか？', '小さなお子さまのクラスは、歌やダンス、ゲームなど体を動かす時間をたっぷり取っています。座って取り組む時間も少しずつ増やしていくので、ご安心ください。' ),
				array( '絵をかいたり工作したりするのが大好きです。そういう時間はありますか？', 'ちいくのたねクラスを中心に、季節の工作や色あそびを英語で楽しむ時間があります。作ったものは、みんなの前で英語でしょうかいします。' ),
			),
		),
		'course'     => array(
			'name'  => 'コース・クラスについて',
			'items' => array(
				array( 'おやこえいごクラスから、キッズアフタークラスへ続けて通う場合、入会金はかかりますか？', 'うさぎの森の中でコースを移る場合は、あらためて入会金をいただくことはありません。テキスト代など、コースごとに必要な費用だけをご案内します。' ),
				array( '英語を習ったことがないのですが、小学生からでも始められますか？', 'はい、もちろんです。ステップアップジュニアは学年ではなく英語のレベルでクラスを分けているので、はじめてのお子さまも無理なくスタートできます。' ),
			),
		),
		'wear'       => array(
			'name'  => '持ちもの・服装について',
			'items' => array(
				array( 'レッスンに着ていく服装に決まりはありますか？', '決まった制服はありません。体を動かす時間があるので、動きやすく、よごれても大丈夫な服装でお越しください。' ),
				array( '毎回持っていくものを教えてください。', 'レッスンバッグ（入会時にお渡しします）、テキスト、水筒をお持ちください。お預かりを利用する日は、着替えとハンカチもあると安心です。' ),
			),
		),
		'snack'      => array(
			'name'  => 'おやつ・アレルギーについて',
			'items' => array(
				array( 'お預かりの時間に出るおやつは、どんなものですか？', 'おにぎりやふかし芋、季節のくだものなど、なるべく手づくりに近いやさしいおやつをご用意しています。献立は月ごとにお知らせします。' ),
				array( '食べものアレルギーがあります。おやつの対応はしてもらえますか？', '入会時にアレルギーの内容をくわしく伺い、除去したおやつをご用意しています。ご心配な場合は、おうちからおやつをお持ちいただくこともできます。' ),
			),
		),
		'facility'   => array(
			'name'  => '教室・施設について',
			'items' => array(
				array( 'レッスン中、保護者が待っていられる場所はありますか？', '4Fに保護者ラウンジがあります。Wi-Fiも使えるので、お仕事や読書をしながらお待ちいただけます。教室のようすは、ラウンジのモニターからもご覧いただけます。' ),
				array( 'ベビーカーで通っても大丈夫ですか？', 'はい、大丈夫です。1Fのエントランスにベビーカー置き場があり、各階へはエレベーターで移動できます。小さなごきょうだいと一緒でも安心してお越しください。' ),
			),
		),
		'join'       => array(
			'name'  => '入会・料金について',
			'items' => array(
				array( 'まだおむつが取れていません。入会できますか？', 'はい、大丈夫です。おやこえいごクラスとちいくのたねクラスは、おむつのお子さまも通われています。トイレのタイミングは、スタッフがお声がけします。' ),
				array( '学期の途中からでも入会できますか？', 'はい、いつからでも入会できます。月の途中から始める場合、その月の月謝は回数に合わせて日割りで計算します。' ),
			),
		),
		'access'     => array(
			'name'  => 'アクセス・送迎について',
			'items' => array(
				array( '車で送り迎えをしたいのですが、駐車場はありますか？', '教室の専用駐車場はありませんが、徒歩1分のところに提携のコインパーキングがあります。レッスン中は1時間分の駐車券をお渡ししています。' ),
				array( '送迎バスはありますか？', '小学生クラスのみ、うさぎ野市内の3つのルートで送迎バスを運行しています。くわしい停留所は、体験レッスンの際にお渡しします。' ),
			),
		),
	);

	foreach ( $data as $slug => $cat ) {
		$term = term_exists( $slug, 'faq_category' );
		if ( ! $term ) {
			$term = wp_insert_term( $cat['name'], 'faq_category', array( 'slug' => $slug ) );
		}
		if ( is_wp_error( $term ) ) {
			continue;
		}
		foreach ( $cat['items'] as $i => $item ) {
			$post_id = wp_insert_post(
				array(
					'post_type'    => 'faq',
					'post_status'  => 'publish',
					'post_title'   => $item[0],
					'post_content' => $item[1],
					'menu_order'   => ( $i + 1 ) * 10, // 10, 20, 30… あとから間に入れやすいように
				)
			);
			if ( $post_id && ! is_wp_error( $post_id ) ) {
				wp_set_object_terms( $post_id, (int) $term['term_id'], 'faq_category' );
			}
		}
	}
}
add_action( 'init', 'aa5_seed_faq', 20 );
