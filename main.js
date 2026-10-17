/* うさぎの森 えいごスクール
   ※ 以降のステップでは末尾に追記していきます（既存コードは書き換えません） */

(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // トップページのロード画面。消えたら pageShown が完了する（ロード画面のないページはすぐ完了）
  // 画面に入ったら動く表示アニメーションは、ロード画面の裏で終わってしまわないよう、これを待ってから始める
  var loader = document.getElementById('loader');
  var showPage;
  var pageShown = new Promise(function (resolve) { showPage = resolve; });
  if (!loader) showPage();

  /* ---- ハンバーガーメニュー ---- */
  var toggle = document.querySelector('.nav-toggle');
  var drawer = document.getElementById('drawer');

  if (toggle && drawer) {
    toggle.addEventListener('click', function () {
      var open = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!open));
      drawer.hidden = open;
    });
    drawer.addEventListener('click', function (e) {
      // リンクの中の画像や文字（コースカードの img・span など）を押したときも閉じる
      if (e.target.closest('a')) {
        toggle.setAttribute('aria-expanded', 'false');
        drawer.hidden = true;
      }
    });
  }

  /* ---- スクロールで要素を表示 ---- */
  var targets = document.querySelectorAll('.reveal');

  if (reduceMotion || !('IntersectionObserver' in window)) {
    targets.forEach(function (el) { el.classList.add('is-in'); });
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-in');
        io.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
    pageShown.then(function () { targets.forEach(function (el) { io.observe(el); }); });
  }

  /* ---- FAQアコーディオン（箱の高さと答えの透明度をゆっくり変化させる） ---- */
  var faqReduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  document.querySelectorAll('.faq-item__q').forEach(function (q) {
    var item = q.parentElement;
    var a = q.nextElementSibling;
    var anims = [];
    q.addEventListener('click', function () {
      var open = q.getAttribute('aria-expanded') === 'true';
      q.setAttribute('aria-expanded', String(!open));
      if (faqReduce || !item.animate) { a.classList.toggle('is-open', !open); return; }

      // 途中で押し直されたら、今の高さから続けて動かす
      var from = item.offsetHeight;
      var fromOp = anims.length ? getComputedStyle(a).opacity : (open ? 1 : 0);
      anims.forEach(function (an) { an.cancel(); });
      a.classList.add('is-open');
      var to = open ? q.offsetHeight + (item.offsetHeight - item.clientHeight) : item.offsetHeight;
      var opt = { duration: 450, easing: 'cubic-bezier(.4,0,.2,1)' };
      item.style.overflow = 'hidden';
      anims = [
        item.animate([{ height: from + 'px' }, { height: to + 'px' }], opt),
        a.animate([{ opacity: fromOp }, { opacity: open ? 0 : 1 }], opt)
      ];
      anims[0].onfinish = function () {
        if (open) a.classList.remove('is-open');
        item.style.overflow = '';
        anims = [];
      };
    });
  });

  /* ---- コースカードの横スクロール ---- */
  var list = document.getElementById('courseList');
  var prev = document.querySelector('.course__arrow--prev');
  var next = document.querySelector('.course__arrow--next');

  if (list && prev && next) {
    var step = function () {
      var card = list.querySelector('.course-card');
      return card ? card.offsetWidth + 22 : 280;
    };
    // ぐるっとつながって見えるよう、6枚のコピーを左右に1組ずつ並べる（コピー｜本物｜コピー）
    // コピーは読み上げ・Tabキーの対象外。表示アニメーションは画面の外で済ませておく
    var originals = Array.prototype.slice.call(list.querySelectorAll('.course-card'));
    var makeClone = function (card) {
      var c = card.cloneNode(true);
      c.classList.add('is-in');
      c.setAttribute('aria-hidden', 'true');
      c.querySelectorAll('a').forEach(function (a) { a.tabIndex = -1; });
      return c;
    };
    originals.forEach(function (card) { list.appendChild(makeClone(card)); });
    originals.slice().reverse().forEach(function (card) { list.insertBefore(makeClone(card), list.firstChild); });

    // 6枚ぶんの幅
    var setWidth = function () {
      return originals[0].getBoundingClientRect().left - list.firstElementChild.getBoundingClientRect().left;
    };
    // 本物の6枚のあたりから外れていたら、同じ見た目のまま一瞬で本物側へ戻す
    var recenter = function () {
      var w = setWidth();
      if (list.scrollLeft < w * 0.5) list.scrollTo({ left: list.scrollLeft + w, behavior: 'instant' });
      else if (list.scrollLeft > w * 1.5) list.scrollTo({ left: list.scrollLeft - w, behavior: 'instant' });
    };
    list.scrollTo({ left: setWidth(), behavior: 'instant' }); // 最初は本物の1枚目から

    // スクロール（スワイプ・矢印）が止まったら戻す。scrollend 非対応のブラウザは止まって少ししたら
    if ('onscrollend' in window) {
      list.addEventListener('scrollend', recenter);
    } else {
      var recenterTimer = null;
      list.addEventListener('scroll', function () {
        clearTimeout(recenterTimer);
        recenterTimer = setTimeout(recenter, 150);
      }, { passive: true });
    }
    window.addEventListener('resize', recenter);

    prev.addEventListener('click', function () { recenter(); list.scrollBy({ left: -step(), behavior: 'smooth' }); });
    next.addEventListener('click', function () { recenter(); list.scrollBy({ left:  step(), behavior: 'smooth' }); });
  }

  /* ---- コース画像がまだ無い間は壊れた画像の枠を隠す（うすい丸だけ見せる） ---- */
  document.querySelectorAll('.course-card__img img').forEach(function (img) {
    var hide = function () { img.style.visibility = 'hidden'; };
    if (img.complete && img.naturalWidth === 0) hide();
    else img.addEventListener('error', hide);
  });

  /* ---- 追従CTA（ヒーローを過ぎたら表示） ---- */
  var cta = document.querySelector('.floating-cta');
  var hero = document.querySelector('.hero');

  if (cta && hero && 'IntersectionObserver' in window) {
    var ctaObserver = new IntersectionObserver(function (entries) {
      cta.classList.toggle('is-visible', !entries[0].isIntersecting);
    }, { threshold: 0 });
    ctaObserver.observe(hero);
  } else if (cta && !hero) {
    cta.classList.add('is-visible'); // ヒーローのない下層ページ（お知らせ詳細など）は最初から表示
  }

  /* ---- 追従CTAがコピーライトに重ならないよう、帯が見えている高さ分だけ持ち上げる ---- */
  var copyright = document.querySelector('.copyright');
  if (cta && copyright) {
    var liftTicking = false;
    var liftCta = function () {
      liftTicking = false;
      // 画面の下端からコピーライトの帯が見えている高さ（見えていなければ 0）
      var lift = Math.max(0, window.innerHeight - copyright.getBoundingClientRect().top);
      cta.style.setProperty('--copy-lift', lift + 'px');
      cta.classList.toggle('is-lifted', lift > 0);
    };
    window.addEventListener('scroll', function () {
      if (!liftTicking) { liftTicking = true; requestAnimationFrame(liftCta); }
    }, { passive: true });
    window.addEventListener('resize', liftCta);
    liftCta();
  }

  /* ---- ドロワー表示中の背景マスク ---- */
  var mask = document.getElementById('drawerMask');

  if (toggle && drawer && mask) {
    // 上のハンバーガー処理のあとに実行され、ドロワーの開閉にマスクを合わせる
    var syncMask = function () { mask.hidden = drawer.hidden; };
    toggle.addEventListener('click', syncMask);
    drawer.addEventListener('click', syncMask);
    // マスクをクリックしたらドロワーを閉じる
    mask.addEventListener('click', function () { toggle.click(); });
  }

  /* ---- ヒーローのリード文を一行ずつ表示 ---- */
  var lineGroups = document.querySelectorAll('.js-lines');

  // 動きを減らす設定・非対応ブラウザでは最初から全部表示のまま
  if (lineGroups.length && !reduceMotion && 'IntersectionObserver' in window) {
    var onLines = function (entries, observer) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-in');
        observer.unobserve(entry.target);
      });
    };
    var lineObserver = new IntersectionObserver(onLines, { threshold: 0.3, rootMargin: '0px 0px -15% 0px' });
    // .js-lines--early（Concept）は、画面に少し入った時点ですぐ動き出す
    var earlyLineObserver = new IntersectionObserver(onLines, { threshold: 0.05, rootMargin: '0px' });
    lineGroups.forEach(function (el) { el.classList.add('is-ready'); });
    pageShown.then(function () {
      lineGroups.forEach(function (el) {
        (el.classList.contains('js-lines--early') ? earlyLineObserver : lineObserver).observe(el);
      });
    });
  }

  /* ---- ワールド：景色を止めて、吹き出し・サボテンだけ流す（その後Aboutがかぶさる）----
     見た目の固定（sticky）とAboutのせり上がりはCSS（.js-parallax）。ここでは
     ・景色が画面より高いときの止める位置（--scene-top）
     ・止まっている間に流す吹き出し・サボテンの移動量
     を計算する */
  var world = document.querySelector('.world');
  var scene = world && world.querySelector(':scope > .wrap');

  if (world && scene && !reduceMotion) {
    document.documentElement.classList.add('js-parallax');

    // [要素, 速さ]：景色が止まってからスクロールした量 × 速さ だけ上へ流れる
    var parallaxItems = [
      [document.querySelector('.bubble--3'), 0.9],
      [document.querySelector('.world__cactus'), 0.6],
      [document.querySelector('.world__photo--2'), 0.6], // サボテンと一緒に流す左の写真
      [document.querySelector('.world__photo--1'), 0.6]  // 右の写真も同じ速さで流す
    ].filter(function (item) { return item[0]; });
    var sceneTop = 0;
    var worldTop = 0; // ページ先頭からワールド上端までの距離（スクロールのたびに測らないよう保存）
    var ticking = false;

    // CSSのスクロール連動アニメーションが使えるブラウザでは、流す動きをCSSに任せる
    // （3Dモデルの描画でJSが重くなっても、スクロールに遅れずなめらかに動く）
    var useScrollTimeline = window.CSS && CSS.supports && CSS.supports('animation-timeline: scroll()');
    if (useScrollTimeline) {
      document.documentElement.classList.add('js-parallax-css');
      parallaxItems.forEach(function (item) {
        item[0].classList.add('plx-item');
        item[0].style.setProperty('--plx-speed', item[1]);
      });
    }

    // 景色が画面より高いときは、下端が画面の下にそろった位置で止める
    var measure = function () {
      sceneTop = Math.min(0, window.innerHeight - scene.offsetHeight);
      worldTop = world.getBoundingClientRect().top + window.scrollY;
      world.style.setProperty('--scene-top', sceneTop + 'px');
      // CSS版：景色が止まるスクロール位置から、ワールドの高さぶんだけ流す
      world.style.setProperty('--plx-start', (worldTop - sceneTop) + 'px');
      world.style.setProperty('--plx-len', world.offsetHeight + 'px');
    };

    var update = function () {
      ticking = false;
      // 景色が止まった瞬間から 0 → 増えていく
      var p = Math.max(0, sceneTop - (worldTop - window.scrollY));
      parallaxItems.forEach(function (item) {
        item[0].style.translate = '0 ' + (-p * item[1]).toFixed(1) + 'px';
      });
    };

    var onScroll = function () {
      if (!ticking) { ticking = true; requestAnimationFrame(update); }
    };
    var remeasure = function () { measure(); if (!useScrollTimeline) onScroll(); };

    if (!useScrollTimeline) window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', remeasure);
    window.addEventListener('load', remeasure); // 画像読み込み後に高さが変わるため
    // 上の画像・フォントの読み込みでページの高さが変わったときも測り直す
    if ('ResizeObserver' in window) new ResizeObserver(remeasure).observe(document.body);
    measure();
    if (!useScrollTimeline) update();
  }

  /* ---- お知らせのページ（news.html）：カテゴリの絞り込み＋つづきを見る ---- */
  var newsList = document.getElementById('newsList');

  if (newsList) {
    var PAGE_SIZE = 12;  // 最初に表示する件数・1回に追加する件数（追加は4件ずつ）
    var MORE_SIZE = 4;
    var newsItems = Array.prototype.slice.call(newsList.querySelectorAll('.news-item'));
    var filterBtns = document.querySelectorAll('.news-filter__btn');
    var moreWrap = document.querySelector('.news-more');
    var moreBtn = document.querySelector('.news-more__btn');
    var emptyMsg = document.querySelector('.news-empty');
    var currentFilter = 'all';
    var shownCount = PAGE_SIZE;

    var renderNews = function (animate) {
      var matched = newsItems.filter(function (item) {
        return currentFilter === 'all' || item.dataset.cat.split(' ').indexOf(currentFilter) !== -1;
      });
      newsItems.forEach(function (item) {
        var idx = matched.indexOf(item);
        var show = idx !== -1 && idx < shownCount;
        var wasHidden = item.hidden;
        item.hidden = !show;
        // 新しく出てきたカードだけ、ふわっと表示
        if (show && (wasHidden || animate)) {
          item.classList.remove('is-appear');
          void item.offsetWidth; // アニメーションをやり直すため
          item.classList.add('is-appear');
        }
      });
      if (moreWrap) moreWrap.hidden = matched.length <= shownCount;
      if (emptyMsg) emptyMsg.hidden = matched.length !== 0;
    };

    filterBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        currentFilter = btn.dataset.filter;
        shownCount = PAGE_SIZE;
        filterBtns.forEach(function (b) {
          var on = b === btn;
          b.classList.toggle('is-active', on);
          b.setAttribute('aria-pressed', String(on));
        });
        renderNews(true);
      });
    });

    if (moreBtn) {
      moreBtn.addEventListener('click', function () {
        shownCount += MORE_SIZE;
        renderNews(false);
      });
    }

    renderNews(false);
    newsItems.forEach(function (item) { item.classList.remove('is-appear'); }); // 最初の表示はアニメーションなし
  }

  /* ---- 3Dモデルの読み込み：重い処理をスクロール中に行わない ----
     モデルは data-src に書いておき、ここで順番に読み込む。
     1. ファイルのダウンロードだけ先に済ませる（ダウンロードは画面を止めない）
     2. スクロールが止まっている間に model-viewer へ渡す（解析・描画準備の重い処理がここで走る）
     3. 先生の表示が終わってから、うさぎを同じ手順で読み込む（重い処理を重ねない） */
  var lastScrollAt = 0;
  window.addEventListener('scroll', function () { lastScrollAt = performance.now(); }, { passive: true });

  // スクロールが止まって 250ms たつまで待つ
  var waitScrollIdle = function () {
    return new Promise(function (resolve) {
      var check = function () {
        if (performance.now() - lastScrollAt >= 250) resolve();
        else setTimeout(check, 100);
      };
      check();
    });
  };

  // 1. ダウンロードだけ行い、model-viewer に渡す src を返す
  var fetchModel = function (mv) {
    var url = mv && mv.dataset.src;
    if (!url) return Promise.resolve(null);
    return fetch(url)
      .then(function (res) {
        if (!res.ok) throw new Error(res.status);
        return res.blob();
      })
      .then(function (blob) { return URL.createObjectURL(blob); })
      .catch(function () { return url; }); // 先にダウンロードできなかったときは、そのまま渡す
  };

  // 2. model-viewer の準備ができてから、スクロールが止まるのを待って渡す（表示まで終わったら完了）
  var showModel = function (mv, srcPromise) {
    return srcPromise.then(function (src) {
      if (!src) return;
      return customElements.whenDefined('model-viewer').then(waitScrollIdle).then(function () {
        return new Promise(function (resolve) {
          var done = function () { resolve(); };
          mv.addEventListener('load', done, { once: true });
          mv.addEventListener('error', done, { once: true });
          mv.setAttribute('src', src);
        });
      });
    });
  };

  var loadModel = function (mv) { return showModel(mv, fetchModel(mv)); };

  var teacherModel = document.getElementById('heroModel');
  var rabbitModel = document.getElementById('rabbitModel');
  var teacherReady = loadModel(teacherModel);
  var rabbitReady = Promise.resolve();

  if (rabbitModel && loader) {
    // ロード画面があるとき：うさぎも最初に読み込んでおく
    // （ダウンロードは先生と同時に始め、表示の準備は先生が終わってから。重い処理を重ねない）
    var rabbitSrc = fetchModel(rabbitModel);
    rabbitReady = teacherReady.then(function () { return showModel(rabbitModel, rabbitSrc); });
  } else if (rabbitModel) {
    // うさぎは、ワールドのセクションが画面の約2画面ぶん手前まで近づいたら読み込み始める
    // （見ない人には読み込ませない。先生の読み込みが終わってから始めて、重い処理を重ねない）
    var startRabbit = function () { teacherReady.then(function () { return loadModel(rabbitModel); }); };
    var rabbitSection = rabbitModel.closest('section') || rabbitModel;
    if ('IntersectionObserver' in window) {
      var rabbitObserver = new IntersectionObserver(function (entries) {
        if (entries.some(function (e) { return e.isIntersecting; })) {
          rabbitObserver.disconnect();
          startRabbit();
        }
      }, { rootMargin: '200% 0px' });
      rabbitObserver.observe(rabbitSection);
    } else {
      startRabbit();
    }
  }

  /* ---- ロード画面（トップページ）：画像・フォント・3Dモデルがすべてそろったら消す ---- */
  if (loader) {
    var root = document.documentElement;

    // あとから読み込む設定（loading="lazy"）の画像も、ロード画面の間にすべて読み込む
    var images = Array.prototype.slice.call(document.images);
    images.forEach(function (img) { if (img.loading === 'lazy') img.loading = 'eager'; });
    var imagesReady = Promise.all(images.map(function (img) {
      return new Promise(function (resolve) {
        if (img.complete) return resolve();
        img.addEventListener('load', resolve, { once: true });
        img.addEventListener('error', resolve, { once: true }); // 読み込めない画像があっても止まらない
      });
    }));
    var pageLoaded = new Promise(function (resolve) {
      if (document.readyState === 'complete') resolve();
      else window.addEventListener('load', resolve, { once: true }); // CSSの背景画像など
    });
    var fontsReady = document.fonts ? document.fonts.ready : Promise.resolve();
    // 最後に2コマ待って、モデルが1度描かれてから見せる
    var nextFrames = function () {
      return new Promise(function (resolve) {
        requestAnimationFrame(function () { requestAnimationFrame(resolve); });
      });
    };
    var allReady = Promise.all([pageLoaded, imagesReady, fontsReady, teacherReady, rabbitReady]).then(nextFrames);
    // 通信が遅くても、20秒たったらロード画面を消してページを見せる
    var giveUp = new Promise(function (resolve) { setTimeout(resolve, 20000); });

    Promise.race([allReady, giveUp]).then(function () {
      root.classList.remove('is-loading'); // ここからヒーローの登場アニメーションが動き出す
      // ロード後は必ずページの一番上から見せる（リロードしても途中から始まらない）
      // ただし /#news のような見出しへのリンクで来たときは、その見出しへ
      var hashTarget = null;
      try { hashTarget = location.hash && document.querySelector(decodeURIComponent(location.hash)); } catch (e) {}
      if (hashTarget) hashTarget.scrollIntoView({ behavior: 'instant' });
      else window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
      loader.classList.add('is-hidden');
      showPage();
      setTimeout(function () { loader.remove(); }, 700); // フェードアウト（.6s）が終わったら取り除く
    });
  }

  /* ---- ヒーローの先生（3D）：スクロール中だけアニメーションを止めて、スクロールを軽くする ----
     3Dの描画は毎コマ行われて重いため、スクロールが止まって少ししたら再開する */
  var heroModel = document.getElementById('heroModel');

  if (heroModel) {
    var resumeTimer = null;
    var pausedByScroll = false;

    window.addEventListener('scroll', function () {
      // 先生のアニメーション再生中だけ止める（model-viewer の読み込み前は何もしない）
      if (!pausedByScroll && typeof heroModel.pause === 'function' && !heroModel.paused) {
        heroModel.pause();
        pausedByScroll = true;
      }
      clearTimeout(resumeTimer);
      resumeTimer = setTimeout(function () {
        if (pausedByScroll) {
          heroModel.play();
          pausedByScroll = false;
        }
      }, 150);
    }, { passive: true });
  }

  /* ---- コースのページ：写真の帯（.cp-strip）をトップのギャラリーと同じように流し続ける ----
     5枚を1組として3組並べ、1組ぶん流したら最初に戻す（つなぎ目が見えない） */
  document.querySelectorAll('.cp-strip').forEach(function (strip) {
    var track = document.createElement('div');
    track.className = 'cp-strip__track';
    var items = Array.prototype.slice.call(strip.children);
    items.forEach(function (item) { track.appendChild(item); });
    for (var i = 0; i < 2; i++) {
      items.forEach(function (item) { track.appendChild(item.cloneNode(true)); });
    }
    strip.appendChild(track);
    strip.classList.add('is-loop');
  });
})();
