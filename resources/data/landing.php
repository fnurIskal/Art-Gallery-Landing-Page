<?php

declare(strict_types=1);

/*
 * Editorial content for the landing page. Kept out of the view so templates
 * only render data handed to them by HomeController. Replace with a CMS or
 * repository source when real content becomes available.
 */
return [
    'hero' => [
        // Two vertical reels; each item is [type, value, title, label].
        // type "art" renders /assets/images/weekly-{value}.jpg, "fact" renders a note tile.
        'columns' => [
            [
                ['fact', '32 eser', 'Tek bir seçki, birçok bakış.', 'rose'],
                ['art', '1', 'Bir sofranın hikâyesi', 'RESİM'],
                ['art', '3', 'Birlikte çalmak', 'RİTİM'],
                ['fact', '60 dk', 'Kendi ritminde bir sanat rotası.', 'cream'],
            ],
            [
                ['art', '2', 'Sessiz bir an', 'PORTRE'],
                ['fact', '12 sanatçı', 'Farklı dünyalar, ortak bir karşılaşma.', 'sage'],
                ['art', '4', 'Seçkiden bir bakış', 'BU HAFTA'],
                ['art', '5', 'Rengin başka bir hâli', 'BU HAFTA'],
            ],
        ],
    ],
    'archive' => [
        ['title' => 'Rengin izinde', 'meta' => '2025 / RESİM', 'image' => 'exhibitions/rengin-izinde.jpg', 'description' => 'Duyguyu renk, yüzey ve fırça izleriyle takip eden; izleyiciyi modern resmin çok katmanlı hafızasına davet eden bir arşiv seçkisi.', 'visitors' => '12.400', 'duration' => '45 dk', 'works' => '28 eser', 'artists' => '11 sanatçı'],
        ['title' => 'Mekânın hafızası', 'meta' => '2024 / MİMARİ', 'image' => 'exhibitions/mekanin-hafizasi.jpg', 'description' => 'Boşluk, geometri ve ışığın sergi mekânını nasıl dönüştürdüğünü araştıran yalın bir fotoğraf ve mimari seçkisi.', 'visitors' => '8.600', 'duration' => '60 dk', 'works' => '19 eser', 'artists' => '8 sanatçı'],
        ['title' => 'Düşün ötesinde', 'meta' => '2025 / DENEYSEL', 'image' => 'exhibitions/dusun-otesinde.jpg', 'description' => 'Figür, renk ve gündelik gözlem üzerinden gerçeklikle hayal arasında yeni çağrışımlar kuran deneysel işler.', 'visitors' => '15.200', 'duration' => '50 dk', 'works' => '34 eser', 'artists' => '14 sanatçı'],
        ['title' => 'Birlikte hareket', 'meta' => '2024 / PERFORMANS', 'image' => 'exhibitions/birlikte-hareket.jpg', 'description' => 'İzleyici ile eser arasındaki mesafeyi azaltan; bakışı, bedeni ve karşılaşma anını aynı ritimde buluşturan bir seçki.', 'visitors' => '7.200', 'duration' => '35 dk', 'works' => '16 eser', 'artists' => '7 sanatçı'],
        ['title' => 'Kırmızı bir hikâye', 'meta' => '2023 / RESİM', 'image' => 'exhibitions/kirmizi-bir-hikaye.jpg', 'description' => 'Tek bir rengin taşıyabileceği gerilim, hafıza ve direnç duygularının farklı kuşaklardan eserlerle izini sürer.', 'visitors' => '11.300', 'duration' => '40 dk', 'works' => '21 eser', 'artists' => '9 sanatçı'],
        ['title' => 'Işığın geometrisi', 'meta' => '2023 / MEKÂN', 'image' => 'exhibitions/isigin-geometrisi.jpg', 'description' => 'Karanlık ve aydınlık arasındaki geçişlerin mekânı, figürü ve bakma süresini yeniden kurduğu sessiz bir rota.', 'visitors' => '9.800', 'duration' => '55 dk', 'works' => '24 eser', 'artists' => '10 sanatçı'],
        ['title' => 'Görünmeyenin izi', 'meta' => '2022 / DENEYSEL', 'image' => 'exhibitions/gorunmeyenin-izi.jpg', 'description' => 'Gölgede kalan ayrıntıları, tarihsel resmin anlatı katmanlarını ve hareket hâlindeki izleyiciyi birlikte düşünür.', 'visitors' => '6.700', 'duration' => '45 dk', 'works' => '18 eser', 'artists' => '6 sanatçı'],
        ['title' => 'Bir anın ritmi', 'meta' => '2022 / PERFORMANS', 'image' => 'exhibitions/bir-anin-ritmi.jpg', 'description' => 'Kalabalığın içinde durmayı, tek bir esere yaklaşmayı ve ortak bakışın değişen ritmini odağına alan final seçkisi.', 'visitors' => '10.400', 'duration' => '30 dk', 'works' => '15 eser', 'artists' => '5 sanatçı'],
    ],
    // Mock supporters for the marquee; replace with licensed logos before launch.
    'partners' => [
        ['name' => 'Akdeniz Belediyesi', 'initials' => 'AB', 'role' => 'Ana destekçi'],
        ['name' => 'Toros Holding', 'initials' => 'TH', 'role' => 'Sergi sponsoru'],
        ['name' => 'Liman Sanat Vakfı', 'initials' => 'LS', 'role' => 'Kurumsal ortak'],
        ['name' => 'Mersin Ticaret Odası', 'initials' => 'MT', 'role' => 'İş birliği'],
        ['name' => 'Narenciye Bank', 'initials' => 'NB', 'role' => 'Atölye sponsoru'],
        ['name' => 'Kızkalesi Otelleri', 'initials' => 'KO', 'role' => 'Konaklama ortağı'],
        ['name' => 'Çukurova Üniversitesi', 'initials' => 'ÇÜ', 'role' => 'Akademik ortak'],
        ['name' => 'Mavi Yol Lojistik', 'initials' => 'MY', 'role' => 'Taşıma sponsoru'],
    ],
    'story' => [
        // Drop the museum façade photo at public/assets/images/story-building.jpg;
        // HomeController falls back to the reference image until the file exists.
        'image' => 'story-building.jpg',
        'fallback' => '524757458d0ae984.webp',
        'alt' => 'Mersin Modern’in beyaz sütunlu ana giriş cephesi ve bayraklarla süslenmiş ön bahçesi',
    ],
    // Guided chatbot: visitors pick a question, the answer is rendered client-side
    // from these strings via textContent. No free-text input, no external service.
    'chatbot' => [
        'greeting' => 'Merhaba! Ben Mersin Modern asistanıyım. Aşağıdaki sorulardan birini seçebilirsin.',
        'questions' => [
            ['id' => 'hours', 'question' => 'Müze hangi saatlerde açık?', 'answer' => 'Salı–Pazar 10.00–18.00 arasında açığız. Pazartesi günleri kapalıyız; son giriş 17.15’tir.'],
            ['id' => 'tickets', 'question' => 'Bilet fiyatları nedir?', 'answer' => 'Tam bilet 120 TL, öğrenci ve 65 yaş üstü 60 TL. 12 yaş altı çocuklar ve engelli ziyaretçiler ücretsizdir. Her ayın ilk Salı günü giriş herkese ücretsiz.'],
            ['id' => 'workshops', 'question' => 'Atölyelere nasıl kayıt olurum?', 'answer' => 'Atölye kontenjanları 10–12 kişiyle sınırlı. İletişim formundan hangi atölyeye katılmak istediğini yaz; ekibimiz uygun tarih ve ödeme bilgisiyle sana dönüş yapar.'],
            ['id' => 'location', 'question' => 'Müzeye nasıl ulaşırım?', 'answer' => 'Müze Mersin şehir merkezinde, sahil yoluna 5 dakika yürüme mesafesindedir. Ziyaretçiler için ücretsiz otopark ve bisiklet parkı bulunur.'],
            ['id' => 'photo', 'question' => 'Fotoğraf çekebilir miyim?', 'answer' => 'Flaşsız fotoğraf çekimi çoğu sergide serbesttir. Kısıtlı eserler yanlarındaki işaretle belirtilir; tripod ve video çekimi için önceden izin gerekir.'],
            ['id' => 'groups', 'question' => 'Grup ziyareti planlamak istiyorum.', 'answer' => '10 kişi ve üzeri gruplar için rehberli tur düzenliyoruz. Tercih ettiğin tarihi ve grup büyüklüğünü iletişim formuna yaz; 1 iş günü içinde teklif gönderiyoruz.'],
        ],
        'fallback' => 'Sorunun yanıtını bulamadın mı? İletişim formundan yaz, en kısa sürede dönüş yapalım.',
    ],
    'reviews' => [
        ['initials' => 'DA', 'name' => 'Deniz A.', 'quote' => 'Renklerle kurduğum ilişkiyi hiç böyle düşünmemiştim. Rota tam bana göreydi.'],
        ['initials' => 'EK', 'name' => 'Ece K.', 'quote' => 'Bir saatliğine geldim, bütün öğleden sonramı burada geçirdim.'],
        ['initials' => 'MY', 'name' => 'Mert Y.', 'quote' => 'Atölyeden kendi yaptığım bir işle çıkmak yeniden gelmek için güzel bir sebep.'],
        ['initials' => 'SU', 'name' => 'Selin U.', 'quote' => 'Çocuğumla aynı tabloya bakıp bambaşka şeyler görmemizi çok sevdim.'],
        ['initials' => 'BA', 'name' => 'Bora A.', 'quote' => 'Mekân, ışık, eserler… Şehrin içinde kısa bir nefes gibi.'],
        ['initials' => 'İD', 'name' => 'İpek D.', 'quote' => 'Sadece bakmak için değil, biraz durup hissetmek için de güzel bir yer.'],
    ],
];
