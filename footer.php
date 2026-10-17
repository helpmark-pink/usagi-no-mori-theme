
<!-- ============ フッター ============ -->
<footer class="site-footer">
  <div class="wrap site-footer__top">
    <div class="site-footer__brand">
      <a class="logo logo--footer" href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <img loading="lazy" decoding="async" src="<?php echo esc_url( get_template_directory_uri() . '/images/logo-usagi.svg' ); ?>" width="52" height="52" alt="">
        <span class="logo__text"><strong>うさぎの森</strong><small>ENGLISH SCHOOL</small></span>
      </a>
      <p class="site-footer__addr">
        〒000-0000 大阪府うさぎ野市もりのまち1-2-3<br>
        うさぎの森ビル2F ／ TEL 072-700-0000
      </p>
    </div>

    <nav class="site-footer__nav" aria-label="フッターメニュー">
      <div>
        <p class="site-footer__navtitle"><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">お知らせ</a></p>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/#news' ) ); ?>">イベント・入会情報</a></li>
          <li><a href="<?php echo esc_url( home_url( '/#news' ) ); ?>">クラスのようす</a></li>
        </ul>
      </div>
      <div>
        <p class="site-footer__navtitle"><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><span class="nowrap">うさぎの森の</span><span class="nowrap">こだわり</span></a></p>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/#concept' ) ); ?>">教育の考え方</a></li>
          <li><a href="<?php echo esc_url( home_url( '/about/#map' ) ); ?>">教室のようす</a></li>
          <li><a href="<?php echo esc_url( home_url( '/#recruit' ) ); ?>">採用情報</a></li>
        </ul>
      </div>
      <div>
        <p class="site-footer__navtitle"><a href="<?php echo esc_url( home_url( '/course/' ) ); ?>">コース</a></p>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/course/#oyako' ) ); ?>">おやこえいごクラス</a></li>
          <li><a href="<?php echo esc_url( home_url( '/course/#chiiku' ) ); ?>">ちいくのたねクラス</a></li>
          <li><a href="<?php echo esc_url( home_url( '/course/#after' ) ); ?>">キッズアフタークラス</a></li>
          <li><a href="<?php echo esc_url( home_url( '/course/#phonics' ) ); ?>">もりのフォニックス</a></li>
          <li><a href="<?php echo esc_url( home_url( '/course/#step' ) ); ?>">ステップアップジュニア</a></li>
          <li><a href="<?php echo esc_url( home_url( '/course/#eiken' ) ); ?>">そらとぶ英検クラス</a></li>
        </ul>
      </div>
      <div>
        <p class="site-footer__navtitle"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a></p>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">よくあるご質問</a></li>
          <li><a href="<?php echo esc_url( home_url( '/contact/#privacy' ) ); ?>">個人情報の取り扱い</a></li>
        </ul>
      </div>
    </nav>
  </div>

  <!-- 無料体験ボタン：701px以上は右下に追従、700px以下はここ（コピーライトの上）に表示 -->
  <div class="floating-cta-area">
    <a class="floating-cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">無料体験<span>に申し込む</span></a>
  </div>

  <p class="copyright">&copy; USAGI NO MORI ENGLISH SCHOOL<span class="copyright__note">（架空のポートフォリオ作品です）</span></p>
</footer>

<?php wp_footer(); ?>
</body>
</html>
