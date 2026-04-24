<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // ── Categories ──────────────────────────────────────────
        $categories = [
            [
                'name'    => 'Wisata Alam',
                'name_en' => 'Nature Tourism',
                'name_ko' => '자연 관광',
                'name_zh' => '自然旅游',
                'slug'    => 'wisata-alam',
            ],
            [
                'name'    => 'Kuliner',
                'name_en' => 'Culinary',
                'name_ko' => '음식',
                'name_zh' => '美食',
                'slug'    => 'kuliner',
            ],
            [
                'name'    => 'Budaya & Tradisi',
                'name_en' => 'Culture & Tradition',
                'name_ko' => '문화와 전통',
                'name_zh' => '文化与传统',
                'slug'    => 'budaya-tradisi',
            ],
            [
                'name'    => 'Panduan Perjalanan',
                'name_en' => 'Travel Guide',
                'name_ko' => '여행 가이드',
                'name_zh' => '旅行指南',
                'slug'    => 'panduan-perjalanan',
            ],
            [
                'name'    => 'Aktivitas & Petualangan',
                'name_en' => 'Activities & Adventure',
                'name_ko' => '활동 및 모험',
                'name_zh' => '活动与冒险',
                'slug'    => 'aktivitas-petualangan',
            ],
        ];

        $now = now();
        foreach ($categories as &$cat) {
            $cat['created_at'] = $now;
            $cat['updated_at'] = $now;
        }
        unset($cat);

        DB::table('blog_categories')->insertOrIgnore($categories);

        $catIds = DB::table('blog_categories')->pluck('id', 'slug');

        // ── Posts ────────────────────────────────────────────────
        $posts = [
            // 1
            [
                'category_slug' => 'wisata-alam',
                'title'    => 'Keajaiban Bawah Laut Bunaken: Surga Penyelam Dunia',
                'title_en' => 'The Underwater Wonders of Bunaken: A Diver\'s Paradise',
                'title_ko' => '부나켄의 수중 경이로움: 다이버의 천국',
                'title_zh' => '布纳肯的水下奇观：潜水者的天堂',
                'excerpt'    => 'Taman Nasional Bunaken adalah salah satu destinasi menyelam terbaik di dunia dengan keanekaragaman hayati laut yang luar biasa.',
                'excerpt_en' => 'Bunaken National Park is one of the world\'s best diving destinations with extraordinary marine biodiversity.',
                'excerpt_ko' => '부나켄 국립공원은 놀라운 해양 생물 다양성을 자랑하는 세계 최고의 다이빙 명소 중 하나입니다.',
                'excerpt_zh' => '布纳肯国家公园是世界上最好的潜水目的地之一，拥有非凡的海洋生物多样性。',
                'content'    => '<h2>Mengapa Bunaken Begitu Istimewa?</h2><p>Taman Nasional Bunaken terletak di Teluk Manado, Sulawesi Utara, dan mencakup area seluas 89.065 hektar. Kawasan ini dikenal sebagai salah satu pusat keanekaragaman hayati laut tertinggi di dunia, dengan lebih dari 390 spesies karang dan 90 spesies ikan yang telah teridentifikasi.</p><h2>Dinding Karang yang Menakjubkan</h2><p>Salah satu daya tarik utama Bunaken adalah dinding karang vertikal yang dramatis, beberapa di antaranya mencapai kedalaman 25-50 meter. Dinding-dinding ini dihiasi dengan berbagai jenis karang lunak dan keras, serta menjadi rumah bagi penyu hijau, hiu karang, dan berbagai spesies ikan tropis yang berwarna-warni.</p><h2>Tips Menyelam di Bunaken</h2><p>Waktu terbaik untuk menyelam di Bunaken adalah antara bulan April hingga Oktober ketika kondisi laut relatif tenang dan visibilitas air mencapai 20-40 meter. Tersedia berbagai operator selam lokal yang menawarkan paket menyelam untuk pemula maupun penyelam berpengalaman.</p>',
                'content_en' => '<h2>Why is Bunaken So Special?</h2><p>Bunaken National Park is located in Manado Bay, North Sulawesi, covering an area of 89,065 hectares. This area is known as one of the highest centers of marine biodiversity in the world, with more than 390 species of coral and 90 species of fish identified.</p><h2>Spectacular Coral Walls</h2><p>One of Bunaken\'s main attractions is its dramatic vertical coral walls, some reaching depths of 25-50 meters. These walls are adorned with various types of soft and hard corals, and are home to green turtles, reef sharks, and various colorful tropical fish species.</p><h2>Diving Tips in Bunaken</h2><p>The best time to dive in Bunaken is between April and October when sea conditions are relatively calm and water visibility reaches 20-40 meters. Various local dive operators offer diving packages for both beginners and experienced divers.</p>',
                'content_ko' => '<h2>부나켄이 특별한 이유</h2><p>부나켄 국립공원은 북술라웨시 마나도 만에 위치하며 89,065헥타르의 면적을 차지합니다. 이 지역은 390종 이상의 산호와 90종의 어류가 확인된 세계 최고의 해양 생물 다양성 중심지 중 하나로 알려져 있습니다.</p><h2>장관을 이루는 산호 벽</h2><p>부나켄의 주요 명소 중 하나는 25-50미터 깊이에 달하는 극적인 수직 산호 벽입니다. 이 벽들은 다양한 종류의 연산호와 경산호로 장식되어 있으며, 초록 거북이, 암초 상어, 다양한 열대어의 서식지입니다.</p>',
                'content_zh' => '<h2>为什么布纳肯如此特别？</h2><p>布纳肯国家公园位于北苏拉威西马纳多湾，面积89,065公顷。该地区被认为是世界上海洋生物多样性最高的中心之一，已确认有390多种珊瑚和90种鱼类。</p><h2>壮观的珊瑚墙</h2><p>布纳肯的主要景点之一是戏剧性的垂直珊瑚墙，有些深达25-50米。这些墙壁装饰着各种软珊瑚和硬珊瑚，是绿海龟、礁鲨和各种彩色热带鱼的家园。</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1200&q=80',
                'published_at' => '2025-01-15 08:00:00',
            ],
            // 2
            [
                'category_slug' => 'kuliner',
                'title'    => 'Tinutuan: Bubur Manado yang Kaya Rasa dan Sejarah',
                'title_en' => 'Tinutuan: Manado Porridge Rich in Flavor and History',
                'title_ko' => '티누투안: 풍부한 맛과 역사를 가진 마나도 죽',
                'title_zh' => '蒂努图安：风味丰富、历史悠久的马纳多粥',
                'excerpt'    => 'Tinutuan atau Bubur Manado adalah hidangan sarapan ikonik yang mencerminkan kekayaan kuliner Sulawesi Utara.',
                'excerpt_en' => 'Tinutuan or Manado Porridge is an iconic breakfast dish that reflects the culinary richness of North Sulawesi.',
                'excerpt_ko' => '티누투안 또는 마나도 죽은 북술라웨시의 요리적 풍요로움을 반영하는 상징적인 아침 식사입니다.',
                'excerpt_zh' => '蒂努图安或马纳多粥是一道标志性的早餐，反映了北苏拉威西的烹饪丰富性。',
                'content'    => '<h2>Apa Itu Tinutuan?</h2><p>Tinutuan adalah bubur beras yang dimasak bersama berbagai sayuran segar seperti labu kuning, singkong, jagung, kangkung, bayam, dan kemangi. Hidangan ini memiliki cita rasa yang segar dan gurih, serta kaya akan nutrisi dari berbagai sayuran yang digunakan.</p><h2>Sejarah Tinutuan</h2><p>Tinutuan telah menjadi bagian dari budaya kuliner Manado selama berabad-abad. Hidangan ini awalnya merupakan makanan rakyat yang sederhana namun bergizi tinggi. Kini, Tinutuan telah mendapat pengakuan sebagai warisan kuliner Sulawesi Utara dan bahkan telah ditetapkan sebagai makanan khas Kota Manado.</p><h2>Cara Menikmati Tinutuan</h2><p>Tinutuan biasanya disajikan dengan berbagai lauk pendamping seperti ikan cakalang fufu (ikan cakalang asap), sambal roa, perkedel jagung, dan tahu goreng. Untuk pengalaman terbaik, cobalah menikmati Tinutuan di warung-warung tradisional di sekitar Pasar 45 atau Jalan Wakeke di Manado.</p>',
                'content_en' => '<h2>What is Tinutuan?</h2><p>Tinutuan is rice porridge cooked with various fresh vegetables such as pumpkin, cassava, corn, water spinach, spinach, and basil. This dish has a fresh and savory taste, and is rich in nutrients from the various vegetables used.</p><h2>History of Tinutuan</h2><p>Tinutuan has been part of Manado\'s culinary culture for centuries. This dish was originally a simple but highly nutritious folk food. Today, Tinutuan has been recognized as a culinary heritage of North Sulawesi and has even been designated as the signature dish of Manado City.</p><h2>How to Enjoy Tinutuan</h2><p>Tinutuan is usually served with various side dishes such as cakalang fufu (smoked skipjack tuna), roa sambal, corn fritters, and fried tofu. For the best experience, try enjoying Tinutuan at traditional stalls around Pasar 45 or Jalan Wakeke in Manado.</p>',
                'content_ko' => '<h2>티누투안이란?</h2><p>티누투안은 호박, 카사바, 옥수수, 물시금치, 시금치, 바질 등 다양한 신선한 채소와 함께 조리한 쌀죽입니다. 이 요리는 신선하고 고소한 맛을 가지며, 사용된 다양한 채소에서 영양이 풍부합니다.</p><h2>티누투안의 역사</h2><p>티누투안은 수세기 동안 마나도의 요리 문화의 일부였습니다. 이 요리는 원래 단순하지만 영양가가 높은 서민 음식이었습니다.</p>',
                'content_zh' => '<h2>什么是蒂努图安？</h2><p>蒂努图安是与南瓜、木薯、玉米、空心菜、菠菜和罗勒等各种新鲜蔬菜一起烹制的米粥。这道菜口味清新鲜美，富含各种蔬菜的营养。</p><h2>蒂努图安的历史</h2><p>蒂努图安几个世纪以来一直是马纳多烹饪文化的一部分。这道菜最初是一种简单但营养丰富的民间食物。如今，蒂努图安已被认定为北苏拉威西的烹饪遗产。</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=1200&q=80',
                'published_at' => '2025-01-22 09:00:00',
            ],
            // 3
            [
                'category_slug' => 'budaya-tradisi',
                'title'    => 'Upacara Tulude: Tradisi Syukur Masyarakat Sangihe di Manado',
                'title_en' => 'Tulude Ceremony: The Gratitude Tradition of Sangihe People in Manado',
                'title_ko' => '툴루데 의식: 마나도 상기헤 사람들의 감사 전통',
                'title_zh' => '图卢德仪式：马纳多桑义赫人的感恩传统',
                'excerpt'    => 'Upacara Tulude adalah ritual tahunan masyarakat Sangihe yang penuh makna spiritual dan menjadi daya tarik wisata budaya di Manado.',
                'excerpt_en' => 'The Tulude ceremony is an annual ritual of the Sangihe people full of spiritual meaning and a cultural tourism attraction in Manado.',
                'excerpt_ko' => '툴루데 의식은 영적 의미가 가득한 상기헤 사람들의 연례 의식으로 마나도의 문화 관광 명소입니다.',
                'excerpt_zh' => '图卢德仪式是桑义赫人充满精神意义的年度仪式，也是马纳多的文化旅游景点。',
                'content'    => '<h2>Mengenal Upacara Tulude</h2><p>Tulude adalah upacara adat tahunan masyarakat Sangihe-Talaud yang dilaksanakan setiap tanggal 31 Januari. Kata "Tulude" berasal dari bahasa Sangihe yang berarti "menolak" atau "mengusir", merujuk pada ritual pengusiran roh jahat dan penyakit dari komunitas.</p><h2>Rangkaian Ritual Tulude</h2><p>Upacara ini berlangsung selama beberapa hari dan mencakup berbagai ritual seperti tari Masamper (tarian massal yang diiringi nyanyian), prosesi adat, penyajian makanan tradisional, dan doa bersama. Pakaian adat Sangihe yang berwarna-warni menjadi pemandangan yang memukau selama upacara berlangsung.</p><h2>Tulude di Manado</h2><p>Di Manado, komunitas Sangihe yang cukup besar secara rutin menggelar upacara Tulude yang terbuka untuk umum. Acara ini biasanya dipusatkan di Taman Kesatuan Bangsa atau di berbagai kelurahan yang memiliki komunitas Sangihe yang kuat.</p>',
                'content_en' => '<h2>Understanding the Tulude Ceremony</h2><p>Tulude is an annual traditional ceremony of the Sangihe-Talaud people held every January 31st. The word "Tulude" comes from the Sangihe language meaning "to reject" or "to expel", referring to the ritual of expelling evil spirits and diseases from the community.</p><h2>Tulude Ritual Sequence</h2><p>This ceremony lasts for several days and includes various rituals such as the Masamper dance (a mass dance accompanied by singing), traditional processions, serving traditional food, and communal prayers. The colorful traditional Sangihe attire is a stunning sight during the ceremony.</p><h2>Tulude in Manado</h2><p>In Manado, the fairly large Sangihe community regularly holds Tulude ceremonies open to the public. The event is usually centered at Taman Kesatuan Bangsa or in various sub-districts that have strong Sangihe communities.</p>',
                'content_ko' => '<h2>툴루데 의식 이해하기</h2><p>툴루데는 매년 1월 31일에 열리는 상기헤-탈라우드 사람들의 연례 전통 의식입니다. "툴루데"라는 단어는 공동체에서 악령과 질병을 쫓아내는 의식을 가리키는 상기헤어로 "거부하다" 또는 "추방하다"를 의미합니다.</p>',
                'content_zh' => '<h2>了解图卢德仪式</h2><p>图卢德是桑义赫-塔劳德人每年1月31日举行的年度传统仪式。"图卢德"一词来自桑义赫语，意为"拒绝"或"驱逐"，指的是从社区驱逐恶灵和疾病的仪式。</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?auto=format&fit=crop&w=1200&q=80',
                'published_at' => '2025-02-05 10:00:00',
            ],
            // 4
            [
                'category_slug' => 'panduan-perjalanan',
                'title'    => 'Panduan Lengkap Berkunjung ke Manado: Tips dan Informasi Penting',
                'title_en' => 'Complete Guide to Visiting Manado: Tips and Important Information',
                'title_ko' => '마나도 방문 완전 가이드: 팁과 중요 정보',
                'title_zh' => '马纳多完整旅游指南：提示和重要信息',
                'excerpt'    => 'Semua yang perlu Anda ketahui sebelum berkunjung ke Manado, dari transportasi hingga akomodasi dan tempat wisata terbaik.',
                'excerpt_en' => 'Everything you need to know before visiting Manado, from transportation to accommodation and the best tourist spots.',
                'excerpt_ko' => '교통부터 숙박, 최고의 관광지까지 마나도 방문 전 알아야 할 모든 것.',
                'excerpt_zh' => '访问马纳多前需要了解的一切，从交通到住宿以及最佳旅游景点。',
                'content'    => '<h2>Cara Menuju Manado</h2><p>Manado dapat dicapai melalui Bandara Internasional Sam Ratulangi yang melayani penerbangan langsung dari Jakarta, Surabaya, Makassar, dan beberapa kota besar lainnya. Dari bandara, Anda dapat menggunakan taksi, ojek online, atau rental mobil untuk menuju pusat kota yang berjarak sekitar 13 km.</p><h2>Waktu Terbaik Berkunjung</h2><p>Manado memiliki iklim tropis dengan dua musim utama. Musim kemarau (April-Oktober) adalah waktu terbaik untuk berkunjung, terutama jika Anda berencana untuk menyelam atau snorkeling di Bunaken. Suhu rata-rata berkisar antara 24-32°C sepanjang tahun.</p><h2>Akomodasi</h2><p>Manado menawarkan berbagai pilihan akomodasi mulai dari hotel bintang lima seperti Aryaduta Manado dan Swiss-Belhotel Maleosan, hingga guesthouse dan homestay yang terjangkau. Kawasan Boulevard dan Megamas adalah lokasi strategis dengan banyak pilihan hotel dan restoran.</p><h2>Transportasi Lokal</h2><p>Di dalam kota, Anda dapat menggunakan angkutan kota (angkot), taksi, atau ojek online seperti Gojek dan Grab. Untuk mengunjungi destinasi di luar kota seperti Tomohon atau Tondano, disarankan untuk menyewa mobil atau menggunakan jasa tur lokal.</p>',
                'content_en' => '<h2>How to Get to Manado</h2><p>Manado can be reached via Sam Ratulangi International Airport, which serves direct flights from Jakarta, Surabaya, Makassar, and several other major cities. From the airport, you can use taxis, online motorcycle taxis, or car rentals to reach the city center about 13 km away.</p><h2>Best Time to Visit</h2><p>Manado has a tropical climate with two main seasons. The dry season (April-October) is the best time to visit, especially if you plan to dive or snorkel in Bunaken. Average temperatures range from 24-32°C throughout the year.</p><h2>Accommodation</h2><p>Manado offers various accommodation options ranging from five-star hotels like Aryaduta Manado and Swiss-Belhotel Maleosan, to affordable guesthouses and homestays. The Boulevard and Megamas areas are strategic locations with many hotel and restaurant options.</p>',
                'content_ko' => '<h2>마나도 가는 방법</h2><p>마나도는 자카르타, 수라바야, 마카사르 및 기타 주요 도시에서 직항편을 운항하는 삼 라툴랑이 국제공항을 통해 갈 수 있습니다. 공항에서 약 13km 떨어진 시내까지는 택시, 오토바이 택시, 렌터카를 이용할 수 있습니다.</p><h2>방문 최적 시기</h2><p>마나도는 두 가지 주요 계절이 있는 열대 기후입니다. 건기(4월-10월)는 특히 부나켄에서 다이빙이나 스노클링을 계획하고 있다면 방문하기 가장 좋은 시기입니다.</p>',
                'content_zh' => '<h2>如何前往马纳多</h2><p>马纳多可通过萨姆·拉图兰吉国际机场抵达，该机场提供来自雅加达、泗水、望加锡和其他几个主要城市的直飞航班。从机场到约13公里外的市中心，可以乘坐出租车、网约摩托车或租车。</p><h2>最佳访问时间</h2><p>马纳多具有热带气候，有两个主要季节。旱季（4月至10月）是访问的最佳时间，特别是如果您计划在布纳肯潜水或浮潜。</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1200&q=80',
                'published_at' => '2025-02-12 08:30:00',
            ],
            // 5
            [
                'category_slug' => 'aktivitas-petualangan',
                'title'    => 'Mendaki Gunung Lokon: Petualangan Vulkanik di Tomohon',
                'title_en' => 'Climbing Mount Lokon: A Volcanic Adventure in Tomohon',
                'title_ko' => '로콘 산 등반: 토모혼의 화산 모험',
                'title_zh' => '攀登洛孔山：托莫洪的火山冒险',
                'excerpt'    => 'Gunung Lokon di Tomohon menawarkan pengalaman mendaki yang mendebarkan dengan pemandangan kawah aktif yang spektakuler.',
                'excerpt_en' => 'Mount Lokon in Tomohon offers a thrilling hiking experience with spectacular views of an active crater.',
                'excerpt_ko' => '토모혼의 로콘 산은 활화산 분화구의 장관을 이루는 전망과 함께 스릴 넘치는 하이킹 경험을 제공합니다.',
                'excerpt_zh' => '托莫洪的洛孔山提供令人兴奋的徒步体验，可欣赏活火山口的壮观景色。',
                'content'    => '<h2>Tentang Gunung Lokon</h2><p>Gunung Lokon adalah gunung berapi aktif yang terletak di Kota Tomohon, sekitar 25 km dari Manado. Dengan ketinggian 1.580 meter di atas permukaan laut, Gunung Lokon menjadi salah satu destinasi pendakian favorit di Sulawesi Utara. Kawah Tompaluan yang terletak di antara Gunung Lokon dan Gunung Empung adalah kawah aktif yang masih sering mengeluarkan asap belerang.</p><h2>Jalur Pendakian</h2><p>Pendakian Gunung Lokon biasanya dimulai dari Desa Kakaskasen di Tomohon. Jalur pendakian membutuhkan waktu sekitar 3-4 jam untuk mencapai puncak, melewati hutan tropis yang lebat dan padang rumput terbuka. Pemandangan dari puncak sangat menakjubkan, dengan panorama Kota Manado, Teluk Manado, dan Pulau Bunaken yang terlihat jelas di hari yang cerah.</p><h2>Tips Mendaki Gunung Lokon</h2><p>Selalu periksa status aktivitas vulkanik sebelum mendaki. Gunakan pemandu lokal yang berpengalaman, bawa perlengkapan mendaki yang memadai, dan mulailah pendakian pagi hari untuk menghindari cuaca buruk di sore hari. Pastikan untuk mendapatkan izin dari pos pemantauan setempat sebelum memulai pendakian.</p>',
                'content_en' => '<h2>About Mount Lokon</h2><p>Mount Lokon is an active volcano located in Tomohon City, about 25 km from Manado. At 1,580 meters above sea level, Mount Lokon is one of the favorite hiking destinations in North Sulawesi. The Tompaluan crater located between Mount Lokon and Mount Empung is an active crater that still frequently emits sulfurous smoke.</p><h2>Hiking Trail</h2><p>The ascent of Mount Lokon usually starts from Kakaskasen Village in Tomohon. The hiking trail takes about 3-4 hours to reach the summit, passing through dense tropical forest and open grasslands. The view from the summit is breathtaking, with panoramas of Manado City, Manado Bay, and Bunaken Island clearly visible on a clear day.</p><h2>Tips for Climbing Mount Lokon</h2><p>Always check volcanic activity status before climbing. Use experienced local guides, bring adequate climbing equipment, and start the climb in the morning to avoid bad weather in the afternoon.</p>',
                'content_ko' => '<h2>로콘 산 소개</h2><p>로콘 산은 마나도에서 약 25km 떨어진 토모혼 시에 위치한 활화산입니다. 해발 1,580미터의 로콘 산은 북술라웨시에서 가장 인기 있는 하이킹 목적지 중 하나입니다. 로콘 산과 엠풍 산 사이에 위치한 톰팔루안 분화구는 여전히 자주 유황 연기를 내뿜는 활화산 분화구입니다.</p>',
                'content_zh' => '<h2>关于洛孔山</h2><p>洛孔山是一座活火山，位于距马纳多约25公里的托莫洪市。海拔1,580米的洛孔山是北苏拉威西最受欢迎的徒步目的地之一。位于洛孔山和恩蓬山之间的汤帕卢安火山口是一个仍然经常喷出硫磺烟雾的活火山口。</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1200&q=80',
                'published_at' => '2025-02-20 07:00:00',
            ],
            // 6
            [
                'category_slug' => 'kuliner',
                'title'    => 'Cakalang Fufu: Ikan Asap Khas Manado yang Mendunia',
                'title_en' => 'Cakalang Fufu: Manado\'s World-Famous Smoked Fish',
                'title_ko' => '카칼랑 푸푸: 세계적으로 유명한 마나도의 훈제 생선',
                'title_zh' => '卡卡朗富富：马纳多享誉世界的熏鱼',
                'excerpt'    => 'Cakalang Fufu adalah ikan cakalang asap yang menjadi salah satu produk kuliner paling ikonik dari Manado dan Sulawesi Utara.',
                'excerpt_en' => 'Cakalang Fufu is smoked skipjack tuna that has become one of the most iconic culinary products from Manado and North Sulawesi.',
                'excerpt_ko' => '카칼랑 푸푸는 마나도와 북술라웨시에서 가장 상징적인 요리 제품 중 하나가 된 훈제 가다랑어입니다.',
                'excerpt_zh' => '卡卡朗富富是熏制的鲣鱼，已成为马纳多和北苏拉威西最具标志性的烹饪产品之一。',
                'content'    => '<h2>Apa Itu Cakalang Fufu?</h2><p>Cakalang Fufu adalah ikan cakalang (skipjack tuna) yang diasap menggunakan teknik tradisional khas Manado. Proses pengasapan dilakukan dengan cara mengikat ikan pada bambu kemudian diasap di atas bara api kayu selama beberapa jam hingga ikan menjadi kering dan berwarna kecokelatan dengan aroma asap yang khas.</p><h2>Proses Pembuatan</h2><p>Proses pembuatan Cakalang Fufu dimulai dengan membersihkan ikan cakalang segar, kemudian dibelah dan diikat pada bilah bambu. Ikan kemudian diasap menggunakan kayu pilihan selama 4-6 jam. Hasilnya adalah ikan dengan tekstur yang padat, rasa yang gurih dan sedikit smoky, serta daya tahan yang lebih lama dibandingkan ikan segar.</p><h2>Cara Menikmati Cakalang Fufu</h2><p>Cakalang Fufu dapat dinikmati dengan berbagai cara: disuwir dan ditumis dengan sambal dabu-dabu, dijadikan lauk pendamping Tinutuan, atau dimakan langsung dengan nasi putih dan sambal. Anda dapat membeli Cakalang Fufu di Pasar Bersehati atau berbagai toko oleh-oleh di Manado sebagai buah tangan yang tahan lama.</p>',
                'content_en' => '<h2>What is Cakalang Fufu?</h2><p>Cakalang Fufu is skipjack tuna smoked using traditional Manado techniques. The smoking process is done by tying the fish to bamboo then smoking it over wood embers for several hours until the fish becomes dry and brownish with a distinctive smoky aroma.</p><h2>Production Process</h2><p>The production process of Cakalang Fufu starts with cleaning fresh skipjack tuna, then splitting and tying it to bamboo strips. The fish is then smoked using selected wood for 4-6 hours. The result is fish with a dense texture, savory and slightly smoky taste, and longer shelf life compared to fresh fish.</p><h2>How to Enjoy Cakalang Fufu</h2><p>Cakalang Fufu can be enjoyed in various ways: shredded and stir-fried with dabu-dabu sambal, used as a side dish for Tinutuan, or eaten directly with white rice and sambal. You can buy Cakalang Fufu at Bersehati Market or various souvenir shops in Manado as a long-lasting gift.</p>',
                'content_ko' => '<h2>카칼랑 푸푸란?</h2><p>카칼랑 푸푸는 마나도 전통 기법을 사용하여 훈제한 가다랑어입니다. 훈제 과정은 생선을 대나무에 묶은 다음 특유의 훈제 향이 날 때까지 몇 시간 동안 장작 숯불 위에서 훈제하는 방식으로 이루어집니다.</p>',
                'content_zh' => '<h2>什么是卡卡朗富富？</h2><p>卡卡朗富富是使用马纳多传统技术熏制的鲣鱼。熏制过程是将鱼绑在竹子上，然后在木炭上熏制数小时，直到鱼变干变褐色，散发出独特的烟熏香气。</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1519984388953-d2406bc725e1?auto=format&fit=crop&w=1200&q=80',
                'published_at' => '2025-03-01 09:00:00',
            ],
            // 7
            [
                'category_slug' => 'wisata-alam',
                'title'    => 'Danau Tondano: Keindahan Danau Terbesar di Sulawesi Utara',
                'title_en' => 'Lake Tondano: The Beauty of the Largest Lake in North Sulawesi',
                'title_ko' => '톤다노 호수: 북술라웨시 최대 호수의 아름다움',
                'title_zh' => '通达诺湖：北苏拉威西最大湖泊的美丽',
                'excerpt'    => 'Danau Tondano adalah danau terbesar di Sulawesi Utara yang menawarkan pemandangan alam yang indah dan berbagai aktivitas wisata air.',
                'excerpt_en' => 'Lake Tondano is the largest lake in North Sulawesi offering beautiful natural scenery and various water tourism activities.',
                'excerpt_ko' => '톤다노 호수는 북술라웨시에서 가장 큰 호수로 아름다운 자연 경관과 다양한 수상 관광 활동을 제공합니다.',
                'excerpt_zh' => '通达诺湖是北苏拉威西最大的湖泊，提供美丽的自然风光和各种水上旅游活动。',
                'content'    => '<h2>Keindahan Danau Tondano</h2><p>Danau Tondano terletak di Kabupaten Minahasa, sekitar 40 km dari Kota Manado. Dengan luas sekitar 4.278 hektar dan ketinggian 600 meter di atas permukaan laut, danau ini menawarkan pemandangan yang sejuk dan menyegarkan. Dikelilingi oleh perbukitan hijau dan beberapa gunung berapi, Danau Tondano menciptakan panorama alam yang memukau.</p><h2>Aktivitas di Danau Tondano</h2><p>Pengunjung dapat menikmati berbagai aktivitas di Danau Tondano seperti memancing, naik perahu tradisional, bersepeda di sekitar danau, atau sekadar menikmati pemandangan dari tepi danau. Kuliner ikan air tawar khas danau seperti ikan mujair dan ikan mas yang dimasak dengan bumbu rica-rica juga menjadi daya tarik tersendiri.</p><h2>Desa-Desa Wisata di Sekitar Danau</h2><p>Di sekitar Danau Tondano terdapat beberapa desa wisata yang menarik untuk dikunjungi, seperti Desa Remboken yang terkenal dengan kerajinan tangan dan pertanian bunga, serta Desa Tondano yang memiliki nilai sejarah tinggi sebagai pusat peradaban Minahasa.</p>',
                'content_en' => '<h2>The Beauty of Lake Tondano</h2><p>Lake Tondano is located in Minahasa Regency, about 40 km from Manado City. With an area of about 4,278 hectares and an altitude of 600 meters above sea level, this lake offers cool and refreshing scenery. Surrounded by green hills and several volcanoes, Lake Tondano creates a stunning natural panorama.</p><h2>Activities at Lake Tondano</h2><p>Visitors can enjoy various activities at Lake Tondano such as fishing, riding traditional boats, cycling around the lake, or simply enjoying the view from the lakeside. Freshwater fish cuisine typical of the lake such as tilapia and carp cooked with rica-rica spices is also a special attraction.</p>',
                'content_ko' => '<h2>톤다노 호수의 아름다움</h2><p>톤다노 호수는 마나도 시에서 약 40km 떨어진 미나하사 군에 위치합니다. 약 4,278헥타르의 면적과 해발 600미터의 고도를 가진 이 호수는 시원하고 상쾌한 경치를 제공합니다.</p>',
                'content_zh' => '<h2>通达诺湖的美丽</h2><p>通达诺湖位于距马纳多市约40公里的米纳哈萨县。面积约4,278公顷，海拔600米，这个湖泊提供凉爽清新的风景。被绿色山丘和几座火山环绕，通达诺湖创造了令人叹为观止的自然全景。</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1501854140801-50d01698950b?auto=format&fit=crop&w=1200&q=80',
                'published_at' => '2025-03-10 08:00:00',
            ],
            // 8
            [
                'category_slug' => 'aktivitas-petualangan',
                'title'    => 'Snorkeling di Pulau Siladen: Surga Tersembunyi Dekat Bunaken',
                'title_en' => 'Snorkeling at Siladen Island: A Hidden Paradise Near Bunaken',
                'title_ko' => '실라덴 섬 스노클링: 부나켄 근처의 숨겨진 낙원',
                'title_zh' => '在西拉登岛浮潜：布纳肯附近的隐秘天堂',
                'excerpt'    => 'Pulau Siladen menawarkan pengalaman snorkeling yang luar biasa dengan terumbu karang yang masih sangat terjaga kelestariannya.',
                'excerpt_en' => 'Siladen Island offers an extraordinary snorkeling experience with coral reefs that are still very well preserved.',
                'excerpt_ko' => '실라덴 섬은 아직도 매우 잘 보존된 산호초와 함께 특별한 스노클링 경험을 제공합니다.',
                'excerpt_zh' => '西拉登岛提供非凡的浮潜体验，珊瑚礁保存得非常完好。',
                'content'    => '<h2>Mengenal Pulau Siladen</h2><p>Pulau Siladen adalah pulau kecil yang terletak di sebelah timur Pulau Bunaken, masih dalam kawasan Taman Nasional Bunaken. Pulau ini memiliki luas sekitar 24 hektar dan dikelilingi oleh terumbu karang yang sangat indah dan masih terjaga kelestariannya.</p><h2>Keunggulan Snorkeling di Siladen</h2><p>Berbeda dengan Bunaken yang lebih terkenal, Siladen menawarkan pengalaman yang lebih tenang dan eksklusif. Terumbu karang di sekitar Siladen sangat dangkal dan mudah dijangkau, membuatnya ideal untuk snorkeling bahkan bagi pemula. Anda dapat melihat berbagai jenis ikan tropis berwarna-warni, penyu, dan berbagai biota laut lainnya hanya beberapa meter dari pantai.</p><h2>Cara Menuju Siladen</h2><p>Untuk mencapai Pulau Siladen, Anda dapat menyewa perahu dari Pelabuhan Manado atau dari Pulau Bunaken. Perjalanan dari Manado membutuhkan waktu sekitar 45-60 menit, sementara dari Bunaken hanya sekitar 10-15 menit.</p>',
                'content_en' => '<h2>Getting to Know Siladen Island</h2><p>Siladen Island is a small island located east of Bunaken Island, still within the Bunaken National Park area. This island covers about 24 hectares and is surrounded by very beautiful and well-preserved coral reefs.</p><h2>Advantages of Snorkeling at Siladen</h2><p>Unlike the more famous Bunaken, Siladen offers a quieter and more exclusive experience. The coral reefs around Siladen are very shallow and easily accessible, making it ideal for snorkeling even for beginners. You can see various types of colorful tropical fish, turtles, and various other marine life just a few meters from the beach.</p>',
                'content_ko' => '<h2>실라덴 섬 알아보기</h2><p>실라덴 섬은 부나켄 국립공원 내에 있는 부나켄 섬 동쪽에 위치한 작은 섬입니다. 이 섬은 약 24헥타르의 면적을 가지며 매우 아름답고 잘 보존된 산호초로 둘러싸여 있습니다.</p>',
                'content_zh' => '<h2>了解西拉登岛</h2><p>西拉登岛是位于布纳肯岛东部的一个小岛，仍在布纳肯国家公园范围内。这个岛屿面积约24公顷，被非常美丽且保存完好的珊瑚礁所环绕。</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?auto=format&fit=crop&w=1200&q=80',
                'published_at' => '2025-03-18 10:00:00',
            ],
            // 9
            [
                'category_slug' => 'budaya-tradisi',
                'title'    => 'Mapalus: Filosofi Gotong Royong Masyarakat Minahasa',
                'title_en' => 'Mapalus: The Philosophy of Mutual Cooperation of the Minahasa People',
                'title_ko' => '마팔루스: 미나하사 사람들의 상호 협력 철학',
                'title_zh' => '马帕卢斯：米纳哈萨人的互助合作哲学',
                'excerpt'    => 'Mapalus adalah sistem gotong royong tradisional masyarakat Minahasa yang masih dipraktikkan hingga saat ini sebagai warisan budaya yang berharga.',
                'excerpt_en' => 'Mapalus is a traditional mutual cooperation system of the Minahasa people that is still practiced today as a valuable cultural heritage.',
                'excerpt_ko' => '마팔루스는 오늘날에도 귀중한 문화 유산으로 여전히 실천되고 있는 미나하사 사람들의 전통적인 상호 협력 시스템입니다.',
                'excerpt_zh' => '马帕卢斯是米纳哈萨人的传统互助合作制度，至今仍作为宝贵的文化遗产而实践。',
                'content'    => '<h2>Apa Itu Mapalus?</h2><p>Mapalus adalah sistem kerja sama tradisional masyarakat Minahasa di Sulawesi Utara. Kata "Mapalus" berasal dari bahasa Minahasa yang berarti bekerja bersama-sama untuk kepentingan bersama. Sistem ini telah ada sejak ratusan tahun lalu dan menjadi fondasi kehidupan sosial masyarakat Minahasa.</p><h2>Praktik Mapalus dalam Kehidupan Sehari-hari</h2><p>Mapalus diterapkan dalam berbagai aspek kehidupan, mulai dari pertanian (bekerja bersama di ladang), pembangunan rumah, hingga penyelenggaraan acara adat dan keagamaan. Dalam sistem Mapalus, setiap anggota komunitas berkontribusi tenaga dan waktu mereka secara bergantian untuk membantu satu sama lain.</p><h2>Relevansi Mapalus di Era Modern</h2><p>Meskipun modernisasi telah mengubah banyak aspek kehidupan, Mapalus tetap relevan dan dipraktikkan di banyak komunitas Minahasa. Nilai-nilai yang terkandung dalam Mapalus seperti solidaritas, kebersamaan, dan saling membantu menjadi modal sosial yang sangat berharga dalam menghadapi tantangan kehidupan modern.</p>',
                'content_en' => '<h2>What is Mapalus?</h2><p>Mapalus is a traditional cooperation system of the Minahasa people in North Sulawesi. The word "Mapalus" comes from the Minahasa language meaning working together for the common good. This system has existed for hundreds of years and has become the foundation of Minahasa social life.</p><h2>Mapalus Practice in Daily Life</h2><p>Mapalus is applied in various aspects of life, from agriculture (working together in the fields), house construction, to organizing traditional and religious events. In the Mapalus system, each community member contributes their energy and time in turns to help each other.</p>',
                'content_ko' => '<h2>마팔루스란?</h2><p>마팔루스는 북술라웨시 미나하사 사람들의 전통적인 협력 시스템입니다. "마팔루스"라는 단어는 공동의 이익을 위해 함께 일한다는 의미의 미나하사어에서 유래했습니다.</p>',
                'content_zh' => '<h2>什么是马帕卢斯？</h2><p>马帕卢斯是北苏拉威西米纳哈萨人的传统合作制度。"马帕卢斯"一词来自米纳哈萨语，意为为共同利益共同工作。这个制度已经存在了数百年，成为米纳哈萨社会生活的基础。</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1200&q=80',
                'published_at' => '2025-03-25 09:30:00',
            ],
            // 10
            [
                'category_slug' => 'panduan-perjalanan',
                'title'    => 'Wisata Kota Tua Manado: Menelusuri Jejak Sejarah di Pusat Kota',
                'title_en' => 'Manado Old Town Tourism: Tracing Historical Footsteps in the City Center',
                'title_ko' => '마나도 구시가지 관광: 도심에서 역사적 발자취 따라가기',
                'title_zh' => '马纳多老城旅游：追寻市中心的历史足迹',
                'excerpt'    => 'Kawasan kota tua Manado menyimpan berbagai bangunan bersejarah peninggalan era kolonial yang menjadi saksi bisu perjalanan panjang kota ini.',
                'excerpt_en' => 'The old town area of Manado holds various historic buildings from the colonial era that bear silent witness to the long journey of this city.',
                'excerpt_ko' => '마나도 구시가지 지역에는 이 도시의 긴 여정을 묵묵히 증언하는 식민지 시대의 다양한 역사적 건물들이 있습니다.',
                'excerpt_zh' => '马纳多老城区保存着殖民时代的各种历史建筑，默默见证着这座城市的漫长历程。',
                'content'    => '<h2>Sejarah Singkat Manado</h2><p>Manado adalah salah satu kota tertua di Indonesia bagian timur. Kota ini memiliki sejarah panjang sebagai pusat perdagangan dan pemerintahan sejak era VOC (Vereenigde Oost-Indische Compagnie) pada abad ke-17. Pengaruh kolonial Belanda sangat terasa dalam arsitektur dan tata kota Manado hingga saat ini.</p><h2>Destinasi Wisata Sejarah di Manado</h2><p>Beberapa destinasi wisata sejarah yang wajib dikunjungi di Manado antara lain: Benteng Nieuw Amsterdam (kini menjadi kawasan Pasar 45), Gereja Sentrum yang merupakan gereja tertua di Manado, Museum Negeri Sulawesi Utara yang menyimpan koleksi artefak budaya Minahasa, dan kawasan Chinatown di sekitar Jalan Yos Sudarso yang menyimpan bangunan-bangunan tua berarsitektur Tionghoa.</p><h2>Tips Wisata Kota Tua</h2><p>Waktu terbaik untuk menjelajahi kota tua Manado adalah pagi hari sebelum cuaca menjadi terlalu panas. Gunakan jasa pemandu wisata lokal untuk mendapatkan cerita dan informasi yang lebih mendalam tentang setiap bangunan bersejarah. Jangan lupa membawa kamera untuk mengabadikan berbagai sudut arsitektur yang unik dan bersejarah.</p>',
                'content_en' => '<h2>Brief History of Manado</h2><p>Manado is one of the oldest cities in eastern Indonesia. This city has a long history as a center of trade and government since the VOC (Dutch East India Company) era in the 17th century. The Dutch colonial influence is strongly felt in the architecture and urban planning of Manado to this day.</p><h2>Historical Tourism Destinations in Manado</h2><p>Some historical tourism destinations that must be visited in Manado include: Fort Nieuw Amsterdam (now the Pasar 45 area), Sentrum Church which is the oldest church in Manado, North Sulawesi State Museum which houses a collection of Minahasa cultural artifacts, and the Chinatown area around Jalan Yos Sudarso which holds old buildings with Chinese architecture.</p>',
                'content_ko' => '<h2>마나도의 간략한 역사</h2><p>마나도는 인도네시아 동부에서 가장 오래된 도시 중 하나입니다. 이 도시는 17세기 VOC(네덜란드 동인도 회사) 시대부터 무역과 정부의 중심지로서 긴 역사를 가지고 있습니다.</p>',
                'content_zh' => '<h2>马纳多简史</h2><p>马纳多是印度尼西亚东部最古老的城市之一。这座城市自17世纪VOC（荷兰东印度公司）时代起就有着作为贸易和政府中心的悠久历史。荷兰殖民影响在马纳多的建筑和城市规划中至今仍然强烈感受到。</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=1200&q=80',
                'published_at' => '2025-04-01 08:00:00',
            ],
        ];

        // ── Insert posts ─────────────────────────────────────────
        foreach ($posts as $post) {
            $catSlug = $post['category_slug'];
            unset($post['category_slug']);

            DB::table('blog_posts')->insert([
                'title'       => $post['title'],
                'slug'        => Str::slug($post['title']) . '-' . Str::random(6),
                'excerpt'     => $post['excerpt'],
                'content'     => $post['content'],
                'featured_image' => $post['featured_image'],
                'category_id' => $catIds[$catSlug] ?? null,
                'author'      => 'admin',
                'is_published' => true,
                'published_at' => $post['published_at'],
                'title_en'    => $post['title_en'],
                'title_ko'    => $post['title_ko'],
                'title_zh'    => $post['title_zh'],
                'excerpt_en'  => $post['excerpt_en'],
                'excerpt_ko'  => $post['excerpt_ko'],
                'excerpt_zh'  => $post['excerpt_zh'],
                'content_en'  => $post['content_en'],
                'content_ko'  => $post['content_ko'],
                'content_zh'  => $post['content_zh'],
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
