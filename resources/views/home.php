<?php
$escape = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$whatsappUrl = (string) ($site['whatsapp_url'] ?? '');
$campaignEndAt = (string) ($site['campaign_end_at'] ?? '');
// Editorial data arrives from HomeController (see resources/data/landing.php).
$heroColumns = $content['hero']['columns'] ?? [];
$archiveItems = $content['archive'] ?? [];
$reviews = $content['reviews'] ?? [];
// Full class names are written out so Tailwind's content scanner keeps them.
$heroStreamClasses = ['hero-stream-0', 'hero-stream-1'];
$heroNoteClasses = ['rose' => 'hero-note-rose', 'cream' => 'hero-note-cream', 'sage' => 'hero-note-sage'];
$storyImage = $content['story']['resolved_image'] ?? '524757458d0ae984.webp';
$storyAlt = $content['story']['alt'] ?? '';
$chatbot = $content['chatbot'] ?? ['greeting' => '', 'questions' => [], 'fallback' => ''];
$partners = $content['partners'] ?? [];
?>
<header class="site-nav" data-nav>
    <a class="brand" href="#top" aria-label="Mersin Modern ana sayfa">
        <img class="brand-mark" src="/assets/images/logo.svg" alt="" width="44" height="44" decoding="async">
        <span class="wordmark"><span>mersin</span><strong>modern.</strong></span>
    </a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-navigation" data-nav-toggle>
        <span class="sr-only">Menüyü aç</span>
        <span></span><span></span>
    </button>
    <nav id="main-navigation" class="main-nav" aria-label="Ana menü" data-nav-menu>
        <a href="#experience">Deneyimler</a>
        <a href="#exhibitions">Sergiler</a>
        <a href="#programs">Atölyeler</a>
        <a href="#contact">İletişim</a>
    </nav>
    <a class="nav-cta" href="#contact">Ziyaretini planla <span aria-hidden="true">↗</span></a>
</header>

<main id="top">
    <section class="hero-section" aria-labelledby="hero-title">
        <div class="hero-backdrop" aria-hidden="true"></div>
        <div class="hero-inner page-shell">
            <div class="hero-copy">
                <div class="hero-glass-panel">
                <p class="eyebrow text-blush">BU HAFTA / RENGİN BEŞ HÂLİ</p>
                <h1 id="hero-title">Sanat, bakışınla<br><em>yeniden başlar.</em></h1>
                <p class="hero-lead">Akdeniz’in ışığında çağdaş sanatla karşılaş. Sergileri kendi ritminde keşfet, atölyelerde izini bırak.</p>
                <div class="hero-actions">
                    <a class="btn-primary" href="#exhibitions">Bu haftayı keşfet <span aria-hidden="true">↗</span></a>
                    <a class="btn-glass" href="#contact">Ziyaretini planla</a>
                </div>
                </div>
                <div class="hero-proof" aria-label="Müze özeti">
                    <span><strong>32</strong> eser</span>
                    <span><strong>12</strong> sanatçı</span>
                    <span><strong>60 dk</strong> önerilen rota</span>
                </div>
            </div>

            <div class="hero-selection" data-hero-selection aria-label="Bu hafta sergilenen eserlerden bir seçki">
                <div class="hero-selection-heading"><span><i aria-hidden="true"></i> BU HAFTA MÜZEDE</span><button type="button" data-hero-pause aria-pressed="false" aria-label="Eser akışını duraklat">Ⅱ</button></div>
                <div class="hero-stream-window">
                    <?php foreach ($heroColumns as $column => $items): ?>
                        <div class="hero-stream <?= $heroStreamClasses[$column] ?? '' ?>">
                            <?php for ($copy = 0; $copy < 2; $copy++): ?>
                                <div class="hero-stream-group" <?= $copy ? 'aria-hidden="true" inert' : '' ?>>
                                    <?php foreach ($items as [$type, $value, $heroItemTitle, $label]): ?>
                                        <?php if ($type === 'art'): ?>
                                            <figure class="hero-work">
                                                <img src="/assets/images/weekly-<?= $value ?>.jpg" alt="<?= $copy ? '' : $escape($heroItemTitle) ?>" width="600" height="760" decoding="async">
                                                <figcaption><small><?= $escape($label) ?> / RENGİN BEŞ HÂLİ</small><span><?= $escape($heroItemTitle) ?></span></figcaption>
                                            </figure>
                                        <?php else: ?>
                                            <div class="hero-note <?= $heroNoteClasses[$label] ?? '' ?>"><small>RENGİN BEŞ HÂLİ</small><strong><?= $escape($value) ?></strong><span><?= $escape($heroItemTitle) ?></span></div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a class="hero-selection-link" href="#exhibitions">Haftanın seçkisini keşfet <span aria-hidden="true">↗</span></a>
            </div>
        </div>
        <a class="scroll-cue" href="#experience">Aşağı kaydır <span aria-hidden="true">↓</span></a>
    </section>

    <section id="experience" class="section page-shell" aria-labelledby="experience-title">
        <div class="section-heading">
            <div>
                <p class="eyebrow text-wine">MÜZEYİ KENDİ RİTMİNDE YAŞA</p>
                <h2 id="experience-title">Bir ziyaret.<br><em>Birçok karşılaşma.</em></h2>
            </div>
            <p>İlk bakıştan kendi üretimine uzanan, merakı merkeze alan bir müze deneyimi.</p>
        </div>
        <div class="feature-grid">
            <article class="feature-card feature-card-dark">
                <span class="feature-number">01</span>
                <div><p class="eyebrow">GÜNCEL SERGİLER</p><h3>Her hafta yeni bir bakış.</h3><p>Çağdaş sanatın farklı disiplinlerini bir araya getiren seçkileri keşfet.</p></div>
                <a href="#exhibitions" aria-label="Güncel sergileri incele">İncele <span aria-hidden="true">↗</span></a>
            </article>
            <article class="feature-card feature-card-wine">
                <span class="feature-number">02</span>
                <div><p class="eyebrow">REHBERLİ ZİYARET</p><h3>Rotanı merakın çizsin.</h3><p>Bir saatlik seçkilerle eserler arasında sana özel bağlantılar kur.</p></div>
                <a href="#contact" aria-label="Rehberli ziyaret için iletişime geç">Rota oluştur <span aria-hidden="true">↗</span></a>
            </article>
            <article class="feature-card feature-card-light">
                <span class="feature-number">03</span>
                <div><p class="eyebrow">YARATICI ATÖLYELER</p><h3>Bakmaktan fazlası.</h3><p>Çocuklar, aileler ve yetişkinler için uygulamalı sanat buluşmaları.</p></div>
                <a href="#programs" aria-label="Atölyeleri keşfet">Programı gör <span aria-hidden="true">↗</span></a>
            </article>
        </div>
    </section>

    <?php if ($partners !== []): ?>
    <section class="partners-section" aria-label="Destekçilerimiz">
        <div class="partners-marquee" data-marquee>
            <?php for ($copy = 0; $copy < 2; $copy++): ?>
                <ul class="partners-track" <?= $copy ? 'aria-hidden="true"' : '' ?>>
                    <?php foreach ($partners as $partner): ?>
                        <li class="partner">
                            <span class="partner-mark" aria-hidden="true"><?= $escape($partner['initials']) ?></span>
                            <span class="partner-name"><?= $escape($partner['name']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endfor; ?>
        </div>
    </section>
    <?php endif; ?>

    <section id="exhibitions" class="exhibition-section" aria-labelledby="exhibitions-title">
        <div class="page-shell">
            <article class="weekly-selection-card">
                <figure class="weekly-selection-image">
                    <img src="/assets/images/weekly-selection.jpg" alt="Siyah bir yüzey üzerine yerleştirilmiş kırmızı çiçek biçimli sanat enstalasyonu" loading="lazy" decoding="async" width="1600" height="1067">
                </figure>
                <div class="weekly-selection-mark"><small>HAFTANIN SEÇKİSİ</small><strong>01 / 2026</strong></div>
                <div class="weekly-glass-panel">
                    <div class="weekly-glass-reflection" aria-hidden="true"></div>
                    <p class="eyebrow">SERGİ KÜNYESİ / 01</p>
                    <div class="weekly-tags"><span>Resim</span><span>Yerleştirme</span><span>Yeni medya</span></div>
                    <h2 id="exhibitions-title">Rengin<br><em>beş hâli.</em></h2>
                    <p class="weekly-description">Renk yalnızca görülen bir yüzey değil; hatırlama, yakınlaşma ve yön bulma biçimi. On iki sanatçının otuz iki eseri, gündelik yaşamda rengin bıraktığı izleri bir araya getiriyor.</p>
                    <dl class="weekly-facts">
                        <div><dt>Kapsam</dt><dd>32 eser · 12 sanatçı</dd></div>
                        <div><dt>Ziyaret</dt><dd>Salı–Pazar · 10.00–18.00</dd></div>
                        <div><dt>Rota</dt><dd>60 dakika · Galeri 1 ve 2</dd></div>
                    </dl>
                    <div class="weekly-actions"><a class="btn-light" href="#contact">Ziyaretini planla <span aria-hidden="true">↗</span></a><small>18 Ekim’e kadar</small></div>
                </div>
            </article>
        </div>
    </section>

    <section class="archive-section" id="archive" aria-labelledby="archive-title">
        <div class="page-shell">
            <div class="section-heading section-heading-light">
                <div><p class="eyebrow text-blush">ÖNCEKİ SERGİLER</p><h2 id="archive-title">Geçip giden haftalar.<br><em>Bizde kalan izler.</em></h2></div>
                <p>Müzenin geçmiş karşılaşmalarından sekiz seçki. Bir karta dokunarak arşiv notlarını aç.</p>
            </div>
            <div class="archive-stage" data-archive-stage>
                <div class="archive-grid" aria-label="Önceki sergiler" data-archive-grid>
                    <?php foreach ($archiveItems as $index => $item): ?>
                        <button class="archive-card" type="button" data-archive-card data-index="<?= $index ?>"
                            data-title="<?= $escape($item['title']) ?>" data-meta="<?= $escape($item['meta']) ?>"
                            data-image="/assets/images/<?= $escape($item['image']) ?>"
                            data-description="<?= $escape($item['description']) ?>"
                            data-visitors="<?= $escape($item['visitors']) ?>" data-duration="<?= $escape($item['duration']) ?>"
                            data-works="<?= $escape($item['works']) ?>" data-artists="<?= $escape($item['artists']) ?>"
                            aria-expanded="false" aria-controls="archive-post">
                            <img src="/assets/images/<?= $escape(preg_replace('/\.jpg$/', '-card.jpg', $item['image'])) ?>" alt="<?= $escape($item['title']) ?> sergisinden örnek" loading="lazy" decoding="async">
                            <span class="archive-card-shade" aria-hidden="true"></span>
                            <span class="archive-card-copy"><small><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?> / <?= $escape($item['meta']) ?></small><strong><?= $escape($item['title']) ?></strong><span>Hikâyeyi aç <b>↗</b></span></span>
                        </button>
                    <?php endforeach; ?>
                </div>
                <article class="archive-post" id="archive-post" hidden data-archive-detail aria-live="polite">
                    <div class="archive-post-copy">
                        <div class="archive-post-topline">
                            <p class="eyebrow" data-archive-meta></p>
                            <button type="button" class="archive-close" aria-label="Sergi detayını kapat" data-archive-close><span aria-hidden="true">×</span><small>Arşive dön</small></button>
                        </div>
                        <p class="archive-post-index" data-archive-index>ARCHIVE / 01</p>
                        <h3 data-archive-title></h3>
                        <p class="archive-post-description" data-archive-description></p>
                        <dl class="archive-post-facts">
                            <div><dt>Eser</dt><dd data-archive-works></dd></div>
                            <div><dt>Sanatçı</dt><dd data-archive-artists></dd></div>
                            <div><dt>Ziyaretçi</dt><dd data-archive-visitors></dd></div>
                            <div><dt>Ortalama ziyaret</dt><dd data-archive-duration></dd></div>
                        </dl>
                        <div class="archive-post-controls" aria-label="Arşiv içinde gezin">
                            <button type="button" data-archive-prev aria-label="Önceki sergi">←</button>
                            <span data-archive-position>01 / 08</span>
                            <button type="button" data-archive-next aria-label="Sonraki sergi">→</button>
                        </div>
                        <p class="archive-scroll-hint">Kaydırarak kartlara geri dön ↓</p>
                    </div>
                    <figure class="archive-post-media" data-archive-media>
                        <img src="/assets/images/exhibitions/rengin-izinde.jpg" alt="" decoding="async" data-archive-image>
                        <figcaption><span data-archive-caption>Rengin izinde</span><small>Arşiv görseli · Unsplash</small></figcaption>
                    </figure>
                </article>
            </div>
            <p class="archive-disclaimer">Sergi başlıkları ve istatistikler tasarım senaryosudur; görseller referans amaçlıdır.</p>
        </div>
    </section>

    <section class="story-section page-shell" aria-labelledby="story-title">
        <div class="story-image-wrap">
            <img src="/assets/images/<?= $escape($storyImage) ?>" alt="<?= $escape($storyAlt) ?>" loading="lazy" decoding="async" width="1600" height="1070">
            <span class="glass-label">AKDENİZ’DEN DÜNYAYA</span>
        </div>
        <div class="story-copy">
            <p class="eyebrow text-wine">MERSİN MODERN’İN HİKÂYESİ</p>
            <h2 id="story-title">Bir şehir.<br><em>Binlerce karşılaşma.</em></h2>
            <p>Akdeniz’in ışığını çağdaş sanatın merakıyla buluşturan yaşayan bir alan. Her sergi yeni bir sohbet, her ziyaret yeni bir başlangıç.</p>
            <div class="stat-grid" data-counter-group>
                <div><strong data-count="48">0</strong><span>sergi</span></div>
                <div><strong data-count="125000">0</strong><span>ziyaret</span></div>
                <div><strong data-count="1240">0</strong><span>eser</span></div>
            </div>
        </div>
    </section>

    <section class="campaign-section" data-countdown data-deadline="<?= $escape($campaignEndAt) ?>" aria-labelledby="campaign-title">
        <div class="page-shell campaign-inner">
            <div><p class="eyebrow">SONBAHAR ATÖLYELERİ</p><h2 id="campaign-title">Erken kayıt dönemi<br>sona ermeden yerini ayır.</h2></div>
            <div class="countdown" aria-live="polite">
                <span><strong data-days>00</strong> gün</span><span><strong data-hours>00</strong> saat</span><span><strong data-minutes>00</strong> dk</span><span><strong data-seconds>00</strong> sn</span>
            </div>
            <a class="btn-light" href="#contact">Programı sor <span aria-hidden="true">↗</span></a>
        </div>
    </section>

    <section id="programs" class="program-section" aria-labelledby="programs-title">
        <div class="page-shell">
            <div class="section-heading">
                <div><p class="eyebrow text-wine">BAKMAKTAN FAZLASI</p><h2 id="programs-title">İçindeki sanatçıya<br><em>yer aç.</em></h2></div>
                <p>Malzemeye dokun, yeni bir teknik dene ve müzeden kendine ait bir izle ayrıl.</p>
            </div>
            <div class="program-grid" data-program-grid>
                <article class="program-card program-card-rose is-active" data-program-card>
                    <button type="button" class="program-summary" aria-expanded="true"><span>01 / ÇOCUKLAR & AİLELER</span><h3>Renk<br>avcıları.</h3><span class="program-action">Detayı keşfet <b>↗</b></span></button>
                    <div class="program-detail"><p class="program-meta">6–12 yaş + bir yetişkin<br>Cumartesi · 11.00 · 75 dakika · 12 kişi</p><p>Önce sergide üç renk buluyor, ardından bu renklerden kendi kolajımızı hazırlıyoruz. Kâğıt, boya ve kolaj malzemeleri dahildir.</p><a href="#contact">Atölye hakkında bilgi al ↗</a></div>
                </article>
                <article class="program-card program-card-plum" data-program-card>
                    <button type="button" class="program-summary" aria-expanded="false"><span>02 / YETİŞKİNLER</span><h3>Yavaş<br>bakma.</h3><span class="program-action">Detayı keşfet <b>↗</b></span></button>
                    <div class="program-detail" inert><p class="program-meta">18 yaş ve üzeri<br>Pazar · 14.00 · 90 dakika · 10 kişi</p><p>Bir esere beş dakika ayırarak başlıyoruz. Işık, kompozisyon ve duygu sohbetinin ardından kişisel bir eskiz defteri hazırlıyoruz.</p><a href="#contact">Atölye hakkında bilgi al ↗</a></div>
                </article>
                <article class="program-card program-card-ink" data-program-card>
                    <button type="button" class="program-summary" aria-expanded="false"><span>03 / HERKES İÇİN</span><h3>Bir iz,<br>bir hikâye.</h3><span class="program-action">Detayı keşfet <b>↗</b></span></button>
                    <div class="program-detail" inert><p class="program-meta">16 yaş ve üzeri<br>Cumartesi · 15.00 · 120 dakika · 10 kişi</p><p>Gündelik bir nesneyi çizgi, doku ve kısa metinlerle yeniden yorumluyoruz. Atölye sonunda kendi küçük sanat kitabını yanında götürüyorsun.</p><a href="#contact">Atölye hakkında bilgi al ↗</a></div>
                </article>
            </div>
        </div>
    </section>

    <section class="reviews-section" id="reviews" aria-labelledby="reviews-title" data-reviews>
        <div class="page-shell review-heading">
            <p class="eyebrow">ZİYARETTEN GERİYE KALAN</p>
            <h2 id="reviews-title">Herkes başka bir<br><em>hikâyeyle ayrılır.</em></h2>
            <p>Bir bakış, bir his, bir sonraki ziyaret.</p>
        </div>
        <div class="review-scene">
            <div class="review-track" data-review-track>
                <?php foreach ($reviews as $index => $review): ?>
                    <blockquote class="review-card glass-panel review-<?= $index ?>">
                        <div class="review-person"><span><?= $escape($review['initials']) ?></span><div><strong><?= $escape($review['name']) ?></strong><small>Ziyaretçi yorumu · örnek</small></div><span class="review-source" aria-hidden="true">↗</span></div>
                        <span class="review-stars" aria-label="5 üzerinden 5 yıldız">★★★★★</span>
                        <p>“<?= $escape($review['quote']) ?>”</p>
                        <small>Kurgu yorum · Google’dan alınmamıştır</small>
                    </blockquote>
                <?php endforeach; ?>
            </div>
        </div>
        <p class="review-disclaimer">YORUM TASARIMI ÖRNEĞİ · GERÇEK GOOGLE YORUMLARI DEĞİLDİR</p>
    </section>

    <section class="faq-section" aria-labelledby="faq-title">
        <div class="page-shell faq-layout">
            <div><p class="eyebrow text-wine">AKLINDA KALMASIN</p><h2 id="faq-title">Gelmeden önce.</h2></div>
            <div class="faq-list">
                <article><h3><button type="button" aria-expanded="false" aria-controls="faq-answer-1">Sergilerde fotoğraf çekebilir miyim?<span>+</span></button></h3><div class="faq-answer" id="faq-answer-1" aria-hidden="true" inert><p>Her serginin koşulları farklı olabilir. Eserlerin yanındaki yönlendirmeleri takip edebilirsin.</p></div></article>
                <article><h3><button type="button" aria-expanded="false" aria-controls="faq-answer-2">Atölyeler için rezervasyon gerekli mi?<span>+</span></button></h3><div class="faq-answer" id="faq-answer-2" aria-hidden="true" inert><p>Kontenjanlar sınırlıdır. Katılım bilgisini iletişim formu üzerinden önceden almanı öneririz.</p></div></article>
                <article><h3><button type="button" aria-expanded="false" aria-controls="faq-answer-3">Müzeyi gezmek ne kadar sürer?<span>+</span></button></h3><div class="faq-answer" id="faq-answer-3" aria-hidden="true" inert><p>Güncel seçki için yaklaşık 60 dakika ayırabilirsin. Atölye ve etkinliklerle bu süre uzayabilir.</p></div></article>
            </div>
        </div>
    </section>

    <section class="contact-section" id="contact" aria-labelledby="contact-title">
        <div class="page-shell contact-layout">
                <div class="contact-copy">
                    <p class="eyebrow text-blush">BİRLİKTE PLANLAYALIM</p>
                    <h2 id="contact-title">Bir merhaba ile<br><em>başlar.</em></h2>
                    <p>Ziyaret, grup turu veya atölye hakkında bilgi almak için formu doldur. Ekibimiz en kısa sürede sana ulaşsın.</p>
                    <div class="contact-meta"><span>Salı–Pazar</span><strong>10.00 — 18.00</strong><small>Pazartesi kapalı</small></div>
                </div>
                <form class="contact-form glass-panel" id="contact-form" action="/api/contact" method="post" novalidate>
                    <input type="hidden" name="_token" value="<?= $escape($csrfToken) ?>">
                    <input type="hidden" name="request_token" value="<?= $escape($requestToken) ?>">
                    <div class="honeypot" hidden inert aria-hidden="true"><label for="contact-check">Bu alanı boş bırakın</label><input id="contact-check" name="contact_check" type="text" tabindex="-1" autocomplete="off" readonly></div>
                    <div class="form-grid">
                        <label>Ad soyad<input name="full_name" autocomplete="name" minlength="2" maxlength="120" placeholder="Adınız Soyadınız" required><small data-error-for="full_name"></small></label>
                        <label>E-posta<input name="email" type="email" autocomplete="email" maxlength="254" placeholder="siz@ornek.com" required><small data-error-for="email"></small></label>
                    </div>
                    <label>Telefon<input name="phone" type="tel" autocomplete="tel" maxlength="25" placeholder="05xx xxx xx xx" required><small data-error-for="phone"></small></label>
                    <label>Mesaj<textarea name="message" rows="5" minlength="10" maxlength="5000" placeholder="Nasıl bir ziyaret planlıyorsunuz?" required></textarea><small data-error-for="message"></small></label>
                    <small class="form-error" data-error-for="form"></small>
                    <div class="form-footer"><p>Bilgileriniz yalnızca talebinize dönüş yapmak amacıyla kaydedilir.</p><button class="btn-primary" type="submit"><span data-submit-label>Mesajı gönder</span> <span aria-hidden="true">↗</span></button></div>
                </form>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="page-shell footer-inner">
        <a class="wordmark wordmark-red" href="#top"><span>mersin</span><strong>modern.</strong></a>
        <p>Sanat değişir.<br>Merak hep kalır.</p>
        <div><a href="#experience">Deneyimler</a><a href="#programs">Atölyeler</a><a href="#contact">İletişim</a></div>
        <small>Bağımsız tasarım prototipi · Görseller referans amaçlıdır.</small>
    </div>
</footer>

<div class="chatbot" data-chatbot>
    <section class="chatbot-panel glass-panel" id="chatbot-panel" role="dialog" aria-label="Mersin Modern asistanı" hidden inert data-chatbot-panel>
        <header class="chatbot-header">
            <img src="/assets/images/logo.svg" alt="" width="32" height="32" decoding="async">
            <div><strong>Mersin Modern asistanı</strong><small>Sık sorulan sorular</small></div>
            <button type="button" class="chatbot-close" aria-label="Asistanı kapat" data-chatbot-close>×</button>
        </header>
        <ol class="chatbot-log" aria-live="polite" aria-label="Sohbet" data-chatbot-log>
            <li class="chatbot-bubble chatbot-bubble-bot"><?= $escape($chatbot['greeting']) ?></li>
        </ol>
        <div class="chatbot-questions" role="group" aria-label="Soru seç" data-chatbot-questions>
            <?php foreach ($chatbot['questions'] as $item): ?>
                <button type="button" class="chatbot-chip" data-chatbot-question data-answer="<?= $escape($item['answer']) ?>"><?= $escape($item['question']) ?></button>
            <?php endforeach; ?>
        </div>
        <footer class="chatbot-footer">
            <small><?= $escape($chatbot['fallback']) ?></small>
            <a href="#contact" data-chatbot-close>Forma git ↗</a>
            <?php if ($whatsappUrl !== ''): ?>
                <a href="<?= $escape($whatsappUrl) ?>" target="_blank" rel="noopener noreferrer">WhatsApp ↗</a>
            <?php endif; ?>
        </footer>
    </section>
    <button type="button" class="chatbot-toggle" aria-expanded="false" aria-controls="chatbot-panel" data-chatbot-toggle>
        <span class="chatbot-toggle-icon" aria-hidden="true">✦</span><strong>Soru sor</strong>
    </button>
</div>

<div class="toast glass-panel" role="status" aria-live="polite" aria-atomic="true" hidden data-toast>
    <span data-toast-icon aria-hidden="true">✓</span><p data-toast-message></p><button type="button" aria-label="Bildirimi kapat" data-toast-close>×</button>
</div>
