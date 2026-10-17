<?php
/**
 * お問い合わせのページ（固定ページ スラッグ：contact）
 */

// このページ用のbodyクラスを付ける（style.css・contact.cssの指定用）
add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = 'page-news-detail';
		$classes[] = 'page-contact';
		return $classes;
	}
);

/* ---- メールフォームの送信処理 ----
   送信されたら内容をチェックし、問題なければ管理者のメールアドレス（設定 → 一般）へ送る。
   送信できたら ?sent=1 付きのURLへ移動して、お礼のメッセージを出す（再読み込みで二重送信しないように） */
$cf_types  = array(
	'trial'   => '無料体験について',
	'visit'   => '見学について',
	'join'    => '入会について',
	'recruit' => '採用について',
	'other'   => 'その他',
);
$cf_values = array(
	'type'    => '',
	'name'    => '',
	'email'   => '',
	'tel'     => '',
	'message' => '',
	'agree'   => false,
);
$cf_errors = array();
$cf_sent   = isset( $_GET['sent'] );

if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['aa5_contact_nonce'] ) ) {
	$cf_values = array(
		'type'    => sanitize_key( wp_unslash( $_POST['cf_type'] ?? '' ) ),
		'name'    => sanitize_text_field( wp_unslash( $_POST['cf_name'] ?? '' ) ),
		// まちがった形式でも入力欄に戻せるよう、ここでは文字列として受け取る（形式は下の is_email で確認）
		'email'   => sanitize_text_field( wp_unslash( $_POST['cf_email'] ?? '' ) ),
		'tel'     => sanitize_text_field( wp_unslash( $_POST['cf_tel'] ?? '' ) ),
		'message' => sanitize_textarea_field( wp_unslash( $_POST['cf_message'] ?? '' ) ),
		'agree'   => ! empty( $_POST['cf_agree'] ),
	);

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aa5_contact_nonce'] ) ), 'aa5_contact' ) ) {
		$cf_errors['form'] = '送信の有効期限が切れました。お手数ですが、もう一度送信してください。';
	}
	// 迷惑メール対策：人には見えない入力欄に何か入っていたら送らない
	$is_spam = ! empty( $_POST['cf_website'] );

	if ( ! isset( $cf_types[ $cf_values['type'] ] ) ) {
		$cf_errors['type'] = 'お問い合わせ種別を選んでください。';
	}
	if ( '' === $cf_values['name'] ) {
		$cf_errors['name'] = '氏名を入力してください。';
	}
	if ( ! is_email( $cf_values['email'] ) ) {
		$cf_errors['email'] = 'メールアドレスを正しく入力してください。';
	}
	if ( ! preg_match( '/^[0-9０-９\-－ー+()（） ]{10,20}$/u', $cf_values['tel'] ) ) {
		$cf_errors['tel'] = '電話番号を正しく入力してください。';
	}
	if ( '' === trim( $cf_values['message'] ) ) {
		$cf_errors['message'] = 'お問い合わせ内容を入力してください。';
	}
	if ( ! $cf_values['agree'] ) {
		$cf_errors['agree'] = '個人情報の取り扱いへの同意が必要です。';
	}

	if ( ! $cf_errors ) {
		$sent_ok = true;
		if ( ! $is_spam ) {
			$body    = "ホームページのメールフォームから、お問い合わせがありました。\n\n"
				. '■ お問い合わせ種別：' . $cf_types[ $cf_values['type'] ] . "\n"
				. '■ 氏名：' . $cf_values['name'] . "\n"
				. '■ メールアドレス：' . $cf_values['email'] . "\n"
				. '■ 電話番号：' . $cf_values['tel'] . "\n"
				. "■ お問い合わせ内容：\n" . $cf_values['message'] . "\n";
			$sent_ok = wp_mail(
				get_option( 'admin_email' ),
				'【お問い合わせ】' . $cf_types[ $cf_values['type'] ] . '（' . $cf_values['name'] . ' 様）',
				$body,
				array( 'Reply-To: ' . $cf_values['name'] . ' <' . $cf_values['email'] . '>' )
			);
		}
		if ( $sent_ok ) {
			wp_safe_redirect( add_query_arg( 'sent', '1', get_permalink() ) . '#form' );
			exit;
		}
		$cf_errors['form'] = '送信できませんでした。お手数ですが、時間をおいてもう一度お試しいただくか、お電話でお問い合わせください。';
	}
}

get_header();

$img = get_template_directory_uri() . '/images';

// 入力エラーのメッセージ（項目の下に出す）
$cf_error = function ( $key ) use ( $cf_errors ) {
	if ( isset( $cf_errors[ $key ] ) ) {
		echo '<p class="cf__error" id="cf-error-' . esc_attr( $key ) . '">' . esc_html( $cf_errors[ $key ] ) . '</p>';
	}
};
?>

<main id="top">

<!-- ============ お問い合わせ ============ -->
<section class="news-detail contact-page">
  <svg class="news-detail__cloud" viewBox="0 0 240 150" aria-hidden="true"><path d="M0 0h240v70q-10 40-50 34-14 36-60 30-30 24-66 6-30 8-44-18-20-6-20-30z" fill="#fff"/></svg>
  <div class="wrap contact-page__inner">
    <h1 class="heading heading--center news-detail__heading">Contact<small>お問い合わせ</small></h1>

    <!-- メールフォーム -->
    <div class="cf-card" id="form">
      <?php if ( $cf_sent ) : ?>
      <!-- 送信後のメッセージ -->
      <p class="cf__thanks" role="status">お問い合わせありがとうございました！<br>内容を確認のうえ、2〜3営業日以内にご連絡いたします。<span class="cf__thanks-note">※このサイトは架空のポートフォリオ作品のため、実際にはお問い合わせメールは送信されていません。</span></p>
      <?php else : ?>
      <!-- 入力はできない見本のフォーム。「送信する」はお礼のメッセージ（?sent=1）へのリンク -->
      <div class="cf">
        <?php if ( $cf_errors ) : ?>
        <p class="cf__alert" role="alert"><?php echo esc_html( $cf_errors['form'] ?? '入力内容にエラーがあります。赤字のメッセージをご確認ください。' ); ?></p>
        <?php endif; ?>

        <fieldset class="cf__row">
          <legend class="cf__label">お問い合わせ種別<span class="cf__req">必須</span></legend>
          <div class="cf__radios">
            <?php foreach ( $cf_types as $value => $label ) : ?>
            <label class="cf__radio"><input type="radio" name="cf_type" value="<?php echo esc_attr( $value ); ?>" disabled><span><?php echo esc_html( $label ); ?></span></label>
            <?php endforeach; ?>
          </div>
          <?php $cf_error( 'type' ); ?>
        </fieldset>

        <div class="cf__row">
          <label class="cf__label" for="cf-name">氏名<span class="cf__req">必須</span></label>
          <input class="cf__input" type="text" id="cf-name" name="cf_name" placeholder="例）うさぎ もりこ" disabled>
          <?php $cf_error( 'name' ); ?>
        </div>

        <div class="cf__row">
          <label class="cf__label" for="cf-email">メールアドレス<span class="cf__req">必須</span></label>
          <input class="cf__input" type="email" id="cf-email" name="cf_email" placeholder="例）hello@usagi-no-mori.example" disabled>
          <?php $cf_error( 'email' ); ?>
        </div>

        <div class="cf__row">
          <label class="cf__label" for="cf-tel">電話番号<span class="cf__req">必須</span></label>
          <input class="cf__input" type="tel" id="cf-tel" name="cf_tel" placeholder="例）072-700-0000" disabled>
          <?php $cf_error( 'tel' ); ?>
        </div>

        <div class="cf__row">
          <label class="cf__label" for="cf-message">お問い合わせ内容<span class="cf__req">必須</span></label>
          <textarea class="cf__input cf__textarea" id="cf-message" name="cf_message" rows="6" placeholder="お子さまの年齢や、ご希望の曜日などもお書きください。" disabled></textarea>
          <?php $cf_error( 'message' ); ?>
        </div>

        <div class="cf__agree">
          <label class="cf__check">
            <input type="checkbox" name="cf_agree" value="1" disabled>
            <span><a href="#privacy">個人情報の取り扱い</a>に同意する</span>
          </label>
          <?php $cf_error( 'agree' ); ?>
          <p class="cf__note">※お問い合わせ内容の確認画面はありません。<br>内容にお間違いがないかご確認の上、送信ボタンを押してください。</p>
        </div>

        <div class="cf__submit">
          <a class="cf__btn" href="<?php echo esc_url( add_query_arg( 'sent', '1', get_permalink() ) ); ?>#form">送信する</a>
        </div>
      </div>
      <?php endif; ?>

      <details class="cf-privacy" id="privacy">
        <summary>個人情報の取り扱いについて</summary>
        <p>お預かりした個人情報は、お問い合わせへのご返答・体験レッスンのご案内のためにのみ使用し、ご本人の同意なく第三者に提供することはありません。内容の確認・訂正・削除をご希望の場合は、お電話またはこのフォームからご連絡ください。</p>
      </details>
    </div>

    <!-- 電話でのお問い合わせ -->
    <div class="cf-tel">
      <svg class="cf-tel__icon" viewBox="0 0 48 48" aria-hidden="true">
        <path d="M6 18q0-8 18-8t18 8v3q0 2-2 2h-6q-2 0-2-2v-3q-8-3-16 0v3q0 2-2 2H8q-2 0-2-2z" fill="currentColor"/>
        <path d="M14 22h20l6 16q0 3-3 3H11q-3 0-3-3z" fill="currentColor"/>
        <g fill="#FDF9F5"><circle cx="18" cy="29" r="1.8"/><circle cx="24" cy="29" r="1.8"/><circle cx="30" cy="29" r="1.8"/><circle cx="18" cy="35" r="1.8"/><circle cx="24" cy="35" r="1.8"/><circle cx="30" cy="35" r="1.8"/></g>
      </svg>
      <div class="cf-tel__body">
        <p class="cf-tel__label">お電話でのお問い合わせ</p>
        <a class="cf-tel__num" href="tel:0727000000">072-700-0000</a>
        <p class="cf-tel__time">受付時間／月〜土 10:00〜18:00</p>
      </div>
    </div>
  </div>

  <!-- フッターの上のてんとう虫と吹き出し（トップページの .bubble と同じ見た目） -->
  <div class="contact-page__mascot" aria-hidden="true">
    <span class="bubble">Join us!</span>
    <img loading="lazy" decoding="async" src="<?php echo esc_url( $img . '/img-picture/9.webp' ); ?>" width="500" height="500" alt="">
  </div>
</section>


</main>

<script>
  (function () {
    /* 「個人情報の取り扱い」へのリンクで、折りたたみを開く（他ページから #privacy で来たときも） */
    if (location.hash === '#privacy') document.getElementById('privacy').open = true;
    document.querySelectorAll('a[href="#privacy"]').forEach(function (a) {
      a.addEventListener('click', function () { document.getElementById('privacy').open = true; });
    });
  })();
</script>

<?php get_footer(); ?>
