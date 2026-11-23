<?php

namespace Database\Seeders;

use App\Models\CeritaBudaya;
use App\Models\Destinasi;
use App\Models\KategoriMakanan;
use App\Models\MenuDestinasi;
use App\Models\Ulasan;
use App\Models\User;
use App\Models\Wilayah;
use App\Models\WisataAlam;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Pengguna (Admin, UMKM, Warga Lokal, Wisatawan)
        $admin = User::updateOrCreate(['email' => 'admin@tobakuliner.id'], [
            'nama'          => 'Ir. Togar Simanjuntak (Kurator)',
            'password'      => Hash::make('password123'),
            'nomor_telepon' => '081268001001',
            'role'          => 'admin',
            'tipe_penilai'  => 'lokal',
        ]);

        $umkm1 = User::updateOrCreate(['email' => 'mitra@tobakuliner.id'], [
            'nama'          => 'Ompu David Marbun',
            'password'      => Hash::make('password123'),
            'nomor_telepon' => '081375218890',
            'role'          => 'umkm',
            'tipe_penilai'  => 'lokal',
        ]);

        $lokal = User::updateOrCreate(['email' => 'lokal@tobakuliner.id'], [
            'nama'          => 'Binsar Situmorang',
            'password'      => Hash::make('password123'),
            'nomor_telepon' => '081234567890',
            'role'          => 'wisatawan',
            'tipe_penilai'  => 'lokal',
        ]);

        $wisatawan = User::updateOrCreate(['email' => 'wisatawan@tobakuliner.id'], [
            'nama'          => 'Sarah Siregar',
            'password'      => Hash::make('password123'),
            'nomor_telepon' => '082199881209',
            'role'          => 'wisatawan',
            'tipe_penilai'  => 'wisatawan',
        ]);

        // 2. Data Wilayah Kaldera Toba (Kabupaten & Kecamatan)
        $balige       = Wilayah::firstOrCreate(['nama_kecamatan' => 'Balige', 'kabupaten' => 'Toba']);
        $laguboti     = Wilayah::firstOrCreate(['nama_kecamatan' => 'Laguboti', 'kabupaten' => 'Toba']);
        $porsea       = Wilayah::firstOrCreate(['nama_kecamatan' => 'Porsea', 'kabupaten' => 'Toba']);
        $pangururan   = Wilayah::firstOrCreate(['nama_kecamatan' => 'Pangururan', 'kabupaten' => 'Samosir']);
        $simanindo    = Wilayah::firstOrCreate(['nama_kecamatan' => 'Simanindo (Tuktuk)', 'kabupaten' => 'Samosir']);
        $parapat      = Wilayah::firstOrCreate(['nama_kecamatan' => 'Girsang Sipangan Bolon (Parapat)', 'kabupaten' => 'Simalungun']);
        $tarutung     = Wilayah::firstOrCreate(['nama_kecamatan' => 'Tarutung', 'kabupaten' => 'Tapanuli Utara']);
        $siborong     = Wilayah::firstOrCreate(['nama_kecamatan' => 'Siborongborong', 'kabupaten' => 'Tapanuli Utara']);
        $doloksanggul = Wilayah::firstOrCreate(['nama_kecamatan' => 'Dolok Sanggul', 'kabupaten' => 'Humbang Hasundutan']);
        $lintong      = Wilayah::firstOrCreate(['nama_kecamatan' => 'Lintong Nihuta', 'kabupaten' => 'Humbang Hasundutan']);

        // 3. 6 Pilar Kategori Warisan Kuliner
        $katIkan    = KategoriMakanan::firstOrCreate(['nama' => 'Pusaka Ikan & Kuah']);
        $katMie     = KategoriMakanan::firstOrCreate(['nama' => 'Mie & Olahan Tepung']);
        $katKudapan = KategoriMakanan::firstOrCreate(['nama' => 'Kudapan & Kue Adat']);
        $katKopi    = KategoriMakanan::firstOrCreate(['nama' => 'Kopi & Teh Dataran Tinggi']);
        $katDaging  = KategoriMakanan::firstOrCreate(['nama' => 'Olahan Daging Tradisi']);
        $katSambal  = KategoriMakanan::firstOrCreate(['nama' => 'Bumbu & Sambal Khas']);

        // 4. Daftar Toko / Rumah Makan Nyata di Kawasan Danau Toba + Menu & Cerita Budaya per Menu
        $stores = [
            // TOKO 1: BALIGE
            [
                'wilayah_id' => $balige->id,
                'kategori_id' => $katMie->id,
                'nama' => 'Kedai Mie Gomak Kuah Andaliman Pak Marbun',
                'deskripsi' => 'Toko sarapan legendaris di jantung Pasar Onan Balige sejak 1982. Spesialis mie lidi kuah santan kuning kental dengan getar rasa andaliman pegunungan Toba yang dimasak di atas tungku kayu aren.',
                'alamat' => 'Jl. Bukit Barisan No. 14, Kompleks Pasar Onan Balige, Kab. Toba',
                'latitude' => 2.3338000,
                'longitude' => 99.0664000,
                'harga_min' => 15000,
                'harga_max' => 28000,
                'jam_buka' => '06:30:00',
                'jam_tutup' => '16:30:00',
                'is_otentik_batak' => true,
                'menus' => [
                    [
                        'nama_menu' => 'Mie Gomak Kuah Santan Andaliman',
                        'harga' => 18000,
                        'deskripsi' => 'Mie lidi kenyal khas Toba diguyur kuah santan kunyit, serai, asam rias, dan ulekan andaliman segar dengan telur rebus.',
                        'cerita' => [
                            'judul' => 'Mie Gomak Kuah Santan Andaliman: Spageti Batak Penjaga Kehangatan Onan',
                            'asal_usul' => 'Lahir sebagai santapan pagi para pedagang dan petani yang turun dari perbukitan menuju Pasar Onan Balige sejak era 1970-an. Kuah santan panas berpadu andaliman menghangatkan tubuh dari kabut pagi Danau Toba.',
                            'makna_adat' => 'Melambangkan kebersamaan dan penyatuan rezeki di hari pekan (onan), di mana warga lintas marga duduk berdampingan menikmati semangkuk kuah hangat sebelum berdagang.',
                            'istilah_lokal' => 'Gomak = cara tradisional mengambil segenggam mie lidi dengan jari tangan bersih ke dalam mangkuk sebelum disiram kuah mendidih.',
                        ],
                    ],
                    [
                        'nama_menu' => 'Mie Gomak Goreng Bumbu Rias',
                        'harga' => 20000,
                        'deskripsi' => 'Mie lidi ditumis di atas wajan besi tebal menggunakan bunga rias (kecombrang), sawi kampung, dan sambal andaliman hijau.',
                        'cerita' => [
                            'judul' => 'Mie Gomak Goreng Bumbu Rias: Harmoni Aroma Asap & Bunga Kecombrang',
                            'asal_usul' => 'Variasi kering dari Mie Gomak yang biasa dibungkus daun pisang oleh para ibu sepulang dari pasar untuk buah tangan anak-anak di huta (kampung).',
                            'makna_adat' => 'Sering disajikan sebagai hidangan pengiring dalam acara arisan marga, melambangkan ikatan persaudaraan yang panjang dan tidak mudah terputus seperti helai mie lidi.',
                            'istilah_lokal' => 'Rias / Asam Rias = batang dan bunga kecombrang liar yang memberi aroma wangi segar dan asam alami.',
                        ],
                    ],
                    [
                        'nama_menu' => 'Lontong Sayur Labu Siam Kuah Gomak',
                        'harga' => 17000,
                        'deskripsi' => 'Potongan lontong daun pisang disiram kuah gulai labu siam andaliman dan kerupuk merah.',
                        'cerita' => [
                            'judul' => 'Lontong Sayur Labu Siam Kuah Gomak: Perpaduan Padi Toba & Rempah Bukit',
                            'asal_usul' => 'Menggunakan beras lokal lembah Toba yang dipadatkan dalam selongsong daun pisang kepok, disajikan bersama kuah santan berbumbu kunyit tua.',
                            'makna_adat' => 'Beras yang padat melambangkan keteguhan hati (pir ni tondi) dalam memulai pekerjaan harian.',
                            'istilah_lokal' => 'Pir ni Tondi = doa agar jiwa dan raga selalu kuat serta diberkati.',
                        ],
                    ],
                ],
            ],

            // TOKO 2: PANGURURAN SAMOSIR
            [
                'wilayah_id' => $pangururan->id,
                'kategori_id' => $katIkan->id,
                'nama' => 'Rumah Makan & Lapau Naniura Raja Nainggolan',
                'deskripsi' => 'Rumah makan pusaka di tepi perairan Pangururan yang melestarikan seni mematangkan ikan mas Danau Toba menggunakan perasan asam jungga tanpa api sesuai pakem juru masak kerajaan Batak.',
                'alamat' => 'Jl. Putri Lopian No. 22, Pesisir Pangururan, Kab. Samosir',
                'latitude' => 2.6085000,
                'longitude' => 98.6942000,
                'harga_min' => 45000,
                'harga_max' => 95000,
                'jam_buka' => '09:30:00',
                'jam_tutup' => '20:30:00',
                'is_otentik_batak' => true,
                'menus' => [
                    [
                        'nama_menu' => 'Dekke Naniura Ikan Mas Asam Jungga',
                        'harga' => 85000,
                        'deskripsi' => 'Fillet ikan mas segar Danau Toba dimarinasi air jeruk jungga selama 3 jam lalu dilumuri bumbu kuning kemiri sangrai, rias, dan andaliman.',
                        'cerita' => [
                            'judul' => 'Dekke Naniura Ikan Mas Asam Jungga: Sashimi Pusaka Para Raja Batak',
                            'asal_usul' => 'Dahulu Naniura adalah hidangan eksklusif yang hanya boleh diracik oleh juru masak khusus untuk disajikan kepada Raja-raja Batak di Pulau Samosir.',
                            'makna_adat' => 'Simbol penghormatan tertinggi dan kemurnian niat karena ikan dimatangkan oleh sari alam (asam jungga) tanpa tersentuh bara api.',
                            'istilah_lokal' => 'Naniura = ikan yang diasami (diura) hingga dagingnya matang sempurna, kenyal, dan bebas bau amis.',
                        ],
                    ],
                    [
                        'nama_menu' => 'Ikan Mas Tombur Andaliman Pangururan',
                        'harga' => 65000,
                        'deskripsi' => 'Ikan mas bakar arang tempurung yang disiram bumbu tombur kental dari kemiri bakar, cabai rawit, rias, dan andaliman tumbuk.',
                        'cerita' => [
                            'judul' => 'Ikan Mas Tombur Andaliman Pangururan: Sajian Pesisir Penyambut Tamu Jauh',
                            'asal_usul' => 'Tradisi nelayan Samosir yang membakar tangkapan ikan danau langsung di tepi pantai lalu melumurinya dengan sambal kemiri hangat.',
                            'makna_adat' => 'Menjadi hidangan pengikat keakraban ketika menyambut kerabat yang baru menyeberangi Danau Toba.',
                            'istilah_lokal' => 'Tombur = melumuri atau menyiram ikan bakar dengan sambal rempah kental yang sudah disangrai.',
                        ],
                    ],
                    [
                        'nama_menu' => 'Sup Ikan Toba Asam Siala (Naniarsik Kuah)',
                        'harga' => 50000,
                        'deskripsi' => 'Sup bening kekuningan ikan mas dengan potongan buah asam siala (patikala) segar dan daun bawang Batak.',
                        'cerita' => [
                            'judul' => 'Sup Ikan Toba Asam Siala: Kesegaran Kaldera Peng체at Stamina',
                            'asal_usul' => 'Racikan rumahan keluarga pesisir Samosir yang memanfaatkan buah patikala segar untuk menetralkan lemak ikan air tawar.',
                            'makna_adat' => 'Biasa disajikan bagi orang tua atau ibu yang baru melahirkan untuk memulihkan tenaga dan menghangatkan tubuh.',
                            'istilah_lokal' => 'Siala / Patikala = buah dari tanaman kecombrang yang berasa asam segar aromatik.',
                        ],
                    ],
                ],
            ],

            // TOKO 3: BALIGE - ARSIK
            [
                'wilayah_id' => $balige->id,
                'kategori_id' => $katIkan->id,
                'nama' => 'RM Arsik Ikan Mas Pusaka Boru Tobing',
                'deskripsi' => 'Rumah makan keluarga di Balige yang terkenal dengan sajian Dekke Na Niarsik bersisik utuh yang dimasak perlahan selama 4 jam dengan alas batang rias dan kacang panjang lokal.',
                'alamat' => 'Jl. Sisingamangaraja No. 88, Soposurung, Balige, Kab. Toba',
                'latitude' => 2.3289000,
                'longitude' => 99.0582000,
                'harga_min' => 35000,
                'harga_max' => 90000,
                'jam_buka' => '08:00:00',
                'jam_tutup' => '21:00:00',
                'is_otentik_batak' => true,
                'menus' => [
                    [
                        'nama_menu' => 'Dekke Na Niarsik Ikan Mas Adat Utuh',
                        'harga' => 75000,
                        'deskripsi' => 'Ikan mas utuh bersisik dimasak kering bersama bumbu kuning andaliman, bunga kecombrang, asam gelugur, dan lokio (bawang Batak).',
                        'cerita' => [
                            'judul' => 'Dekke Na Niarsik Ikan Mas Adat Utuh:Dekke Sitiotio Pembawa Berkat Kehidupan',
                            'asal_usul' => 'Merupakan kuliner paling sakral dalam kebudayaan Batak Toba yang wajib hadir dalam setiap siklus kehidupan: kelahiran, pernikahan, hingga penghormatan orang tua.',
                            'makna_adat' => 'Disebut sebagai Dekke Simudur-udur; ikan disajikan utuh dari kepala hingga ekor menghadap ke depan sebagai doa agar kehidupan keluarga berjalan searah menuju kemakmuran.',
                            'istilah_lokal' => 'Na Niarsik = proses memasak ikan dengan bumbu rempah hingga air kuahnya menyusut kering (marsik) dan meresap ke tulang.',
                        ],
                    ],
                    [
                        'nama_menu' => 'Sayur Daun Ubi Tumbuk Rimbang Santan',
                        'harga' => 20000,
                        'deskripsi' => 'Daun singkong muda ditumbuk kasar di lesung batu bersama buah rimbang (cepokak), bunga kincung, dan santan gurih.',
                        'cerita' => [
                            'judul' => 'Sayur Daun Ubi Tumbuk Rimbang: Kearifan Kebun Pekarangan Rumah Batak',
                            'asal_usul' => 'Hampir setiap keluarga di Toba menanam singkong dan pohon rimbang di halaman belakang sebagai sumber pangan mandiri.',
                            'makna_adat' => 'Melambangkan kesederhanaan dan rasa syukur atas kesuburan tanah vulkanik Kaldera Toba.',
                            'istilah_lokal' => 'Bulung Gadong = daun ubi/singkong; Rimbang = buah cepokak hijau kecil yang dipercaya menjaga kesehatan mata.',
                        ],
                    ],
                ],
            ],

            // TOKO 4: LAGUBOTI - DAGING TRADISI
            [
                'wilayah_id' => $laguboti->id,
                'kategori_id' => $katDaging->id,
                'nama' => 'Rumah Makan Lomok-Lomok & Panggang Situmorang',
                'deskripsi' => 'Destinasi kuliner daging tradisional di tepi jalan lintas Sumatera Laguboti yang menghidangkan panggang garing beraroma arang kemiri dan sambal rias gota.',
                'alamat' => 'Jl. Lintas Sumatera Km 7, Pasar Laguboti, Kab. Toba',
                'latitude' => 2.3521000,
                'longitude' => 99.1254000,
                'harga_min' => 30000,
                'harga_max' => 60000,
                'jam_buka' => '10:00:00',
                'jam_tutup' => '20:00:00',
                'is_otentik_batak' => true,
                'menus' => [
                    [
                        'nama_menu' => 'Saksang & Lomok-Lomok Rempah Andaliman',
                        'harga' => 40000,
                        'deskripsi' => 'Potongan daging cincang dimasak dengan kelapa gongseng (sangrai) tumbuk, serai, daun jeruk purut, ketumbar, dan andaliman segar.',
                        'cerita' => [
                            'judul' => 'Saksang & Lomok-Lomok Rempah Andaliman: Sajian Utama Pesta Adat Bolon',
                            'asal_usul' => 'Sejak zaman leluhur, hidangan ini diracik secara gotong royong oleh kaum pria (parhobas) saat berlangsungnya pesta adat pernikahan atau syukuran kampung.',
                            'makna_adat' => 'Menjadi wujud sukacita dan pembagian berkat (jambar) yang adil kepada seluruh unsur Dalihan Na Tolu (Hula-hula, Dongan Tubu, dan Boru).',
                            'istilah_lokal' => 'Parhobas = juru masak dan pelayan adat yang menyiapkan hidangan besar dengan penuh ketulusan.',
                        ],
                    ],
                    [
                        'nama_menu' => 'Panggang Karo-Toba Sambal Rias Andaliman',
                        'harga' => 45000,
                        'deskripsi' => 'Daging panggang tiga lapis yang dipanggang di atas bara arang hingga kulitnya renyah, disajikan dengan kuah sop bening.',
                        'cerita' => [
                            'judul' => 'Panggang Sambal Rias Andaliman: Perpaduan Teknik Asap & Kesegaran Rempah',
                            'asal_usul' => 'Berkembang di sepanjang jalur niaga dataran tinggi Toba untuk melayani para pelintas jalan lintas Sumatera sejak dekade 1980-an.',
                            'makna_adat' => 'Menunjukkan keramahan pemilik lapau dalam menjamu musafir agar kembali bertenaga melanjutkan perjalanan.',
                            'istilah_lokal' => 'Sambal Rias = sambal ulek segar dari batang kecombrang rebus, bawang merah bakar, dan andaliman.',
                        ],
                    ],
                ],
            ],

            // TOKO 5: PORSEA - KUDAPAN & KUE ADAT
            [
                'wilayah_id' => $porsea->id,
                'kategori_id' => $katKudapan->id,
                'nama' => 'Toko Kue Adat Lapet & Ombus-Ombus Inang Boru Sitorus',
                'deskripsi' => 'Pusat kudapan tradisional Batak di Porsea yang selalu mengepulkan uap kukusan daun pisang hangat setiap pagi dan sore hari.',
                'alamat' => 'Jl. Patuan Nagari No. 31, Simpang Pasar Porsea, Kab. Toba',
                'latitude' => 2.4412000,
                'longitude' => 99.1538000,
                'harga_min' => 10000,
                'harga_max' => 35000,
                'jam_buka' => '06:00:00',
                'jam_tutup' => '19:00:00',
                'is_otentik_batak' => true,
                'menus' => [
                    [
                        'nama_menu' => 'Lapet Bulung Pisang Gula Aren (Isi 5)',
                        'harga' => 15000,
                        'deskripsi' => 'Kue tepung beras tumbuk bercampur parutan kelapa muda dengan inti gula aren cair, dibungkus daun pisang berbentuk limas piramida.',
                        'cerita' => [
                            'judul' => 'Lapet Bulung Pisang: Limas Manis Pengantar Doa Restu Keluarga',
                            'asal_usul' => 'Berasal dari tradisi mengolah hasil panen padi pertama di lembah Porsea dan Uluan yang ditumbuk menggunakan lesung kayu.',
                            'makna_adat' => 'Bentuk kerucut limas yang mengerucut ke satu titik di atas melambangkan bersatunya doa keluarga menuju Sang Pencipta (Mulajadi Nabolon).',
                            'istilah_lokal' => 'Paulak Une / Manaruhon = tradisi membawa keranjang berisi Lapet saat mengunjungi orang tua atau mertua.',
                        ],
                    ],
                    [
                        'nama_menu' => 'Pohul-Pohul Tepung Beras Kepal Adat',
                        'harga' => 20000,
                        'deskripsi' => 'Adonan tepung beras mentah/kukus, kelapa parut, dan gula merah yang dikepal langsung dengan lima jari tangan hingga menyisakan lekukan jari.',
                        'cerita' => [
                            'judul' => 'Pohul-Pohul Kepal Lima Jari: Simbol Kemufakatan Dalihan Na Tolu',
                            'asal_usul' => 'Merupakan bentuk tertua dari kue adat Batak sebelum dikenalnya teknik membungkus dengan daun pisang.',
                            'makna_adat' => 'Jejak kelima jari tangan yang mengepal kuat pada permukaan kue melambangkan eratnya kesepakatan dan jabat tangan antar dua keluarga dalam musyawarah adat (marhata sinamot).',
                            'istilah_lokal' => 'Pohul = menggenggam atau mengepal erat dengan telapak tangan.',
                        ],
                    ],
                    [
                        'nama_menu' => 'Sasagun Kelapa Gongseng Gula Merah',
                        'harga' => 25000,
                        'deskripsi' => 'Tepung beras yang disangrai kering bersama kelapa parut hingga berwarna cokelat keemasan, dinikmati bersama taburan gula aren.',
                        'cerita' => [
                            'judul' => 'Sasagun Kelapa Gongseng: Bekal Perjalanan Anak Rantau dari Huta',
                            'asal_usul' => 'Kudapan awet tahan lama yang selalu disiapkan oleh para ibu di kampung halaman untuk dibawa anak-anak mereka yang hendak merantau menuntut ilmu.',
                            'makna_adat' => 'Melambangkan kasih sayang orang tua yang tidak pernah basi dan selalu mengingatkan perantau untuk pulang ke kampung halaman.',
                            'istilah_lokal' => 'Sasagun = panganan sangrai kering dari beras tumbuk dan kelapa.',
                        ],
                    ],
                ],
            ],

            // TOKO 6: SIBORONGBORONG - OMBUS-OMBUS & KOPI
            [
                'wilayah_id' => $siborong->id,
                'kategori_id' => $katKudapan->id,
                'nama' => 'Kedai Ombus-Ombus No. 1 & Kopi Panas Pak L. Simatupang',
                'deskripsi' => 'Toko legendaris di terminal Siborongborong yang telah berdiri sejak tahun 1970-an, menghidangkan Ombus-ombus panas langsung dari dandang kukusan bersama kopi tubruk.',
                'alamat' => 'Jl. Siliwangi No. 10, Pasar Siborongborong, Kab. Tapanuli Utara',
                'latitude' => 2.2156000,
                'longitude' => 98.9721000,
                'harga_min' => 12000,
                'harga_max' => 30000,
                'jam_buka' => '06:00:00',
                'jam_tutup' => '21:00:00',
                'is_otentik_batak' => true,
                'menus' => [
                    [
                        'nama_menu' => 'Ombus-Ombus Panas Gula Merah (Seporsi 5 Buah)',
                        'harga' => 15000,
                        'deskripsi' => 'Kue tepung beras hangat bertekstur lembut dengan lelehan gula aren di bagian tengah, disajikan mengepul dari kukusan.',
                        'cerita' => [
                            'judul' => 'Ombus-Ombus Panas Siborongborong: Kue Hangat yang Harus Ditiup Sebelum Disantap',
                            'asal_usul' => 'Diciptakan oleh almarhum Bapak L. Simatupang di Siborongborong pada dekade 1960-an untuk menghangatkan penumpang bus malam di tengah udara dingin dataran tinggi.',
                            'makna_adat' => 'Mengajarkan kesabaran dan kenikmatan berbagi kehangatan di tengah dinginnya kabut pegunungan Tapanuli Utara.',
                            'istilah_lokal' => 'Ombus-ombus = tiup-tiup (mengembuskan napas perlahan karena kue selalu disajikan dalam keadaan sangat panas).',
                        ],
                    ],
                    [
                        'nama_menu' => 'Kopi Tubruk Arabika Siborongborong',
                        'harga' => 12000,
                        'deskripsi' => 'Seduhan kopi hitam murni gilingan kasar dari biji arabika dataran tinggi 1.300 mdpl dengan aroma cokelat dan rempah.',
                        'cerita' => [
                            'judul' => 'Kopi Tubruk Siborongborong: Sahabat Setia Sepiring Ombus-Ombus',
                            'asal_usul' => 'Tradisi minum kopi pagi di kedai kayu Siborongborong sebelum masyarakat berangkat ke ladang atau pasar.',
                            'makna_adat' => 'Pahitnya kopi dan manisnya gula aren pada Ombus-ombus melambangkan keseimbangan suka dan duka dalam kehidupan.',
                            'istilah_lokal' => 'Tes / Kopi = istilah akrab warga lokal saat mengajak sahabat bercengkerama di lapo.',
                        ],
                    ],
                ],
            ],

            // TOKO 7: LINTONG NIHUTA / DOLOK SANGGUL - KOPI DATARAN TINGGI
            [
                'wilayah_id' => $lintong->id,
                'kategori_id' => $katKopi->id,
                'nama' => 'Roastery & Kedai Kopi Arabika Lintong Nihuta Hutasoit',
                'deskripsi' => 'Kedai kopi kebun dan rumah sangrai di dataran tinggi Humbang Hasundutan yang menyajikan kopi Single Origin Lintong kelas dunia langsung dengan pemandangan perbukitan Toba.',
                'alamat' => 'Jl. Dolok Sanggul - Siborongborong Km 9, Lintong Nihuta, Kab. Humbang Hasundutan',
                'latitude' => 2.2684000,
                'longitude' => 98.8645000,
                'harga_min' => 15000,
                'harga_max' => 45000,
                'jam_buka' => '08:00:00',
                'jam_tutup' => '21:00:00',
                'is_otentik_batak' => true,
                'menus' => [
                    [
                        'nama_menu' => 'V60 Manual Brew Arabika Lintong Blue Batak',
                        'harga' => 25000,
                        'deskripsi' => 'Kopi seduh manual dari biji Arabika varietas Sigararutang proses giling basah (wet-hulled) dengan cita rasa herbal, spicy, dan keasaman bersih.',
                        'cerita' => [
                            'judul' => 'Arabika Lintong Varietas Sigararutang: Emas Hijau Penopang Ekonomi Keluarga Toba',
                            'asal_usul' => 'Ditanam di tanah vulkanik bekas letusan supervolcano Toba pada ketinggian 1.400 mdpl sejak abad ke-19.',
                            'makna_adat' => 'Varietas kopi ini dinamai "Sigararutang" (Si Pembayar Utang) karena panennya yang melimpah dan cepat berbuah mampu membiayai sekolah anak-anak dan melunasi kebutuhan adat keluarga.',
                            'istilah_lokal' => 'Sigararutang = pohon kopi penyelamat ekonomi keluarga petani dataran tinggi Toba.',
                        ],
                    ],
                    [
                        'nama_menu' => 'Teh Daun Kopi Tradisional (Kawa Daun Toba) & Pisang Goreng',
                        'harga' => 20000,
                        'deskripsi' => 'Seduhan pucuk daun kopi pilihan yang dikeringkan di atas perapian kayu, disajikan hangat dengan gula aren dan pisang kepok goreng.',
                        'cerita' => [
                            'judul' => 'Teh Daun Kopi & Pisang Kepok: Kearifan Tanpa Limbah Petani Humbang',
                            'asal_usul' => 'Ketika biji kopi dijual ke saudagar di pelabuhan, para petani zaman dahulu memanfaatkan pucuk daun kopi pangkas untuk diseduh menjadi minuman hangat keluarga.',
                            'makna_adat' => 'Menggambarkan ketangguhan dan kecerdikan masyarakat pegunungan dalam menghargai setiap bagian dari tanaman kehidupan.',
                            'istilah_lokal' => 'Bulung Kopi = pucuk daun kopi sangrai yang kaya antioksidan.',
                        ],
                    ],
                ],
            ],

            // TOKO 8: TARUTUNG - KACANG SIHOBUK & BANDREK
            [
                'wilayah_id' => $tarutung->id,
                'kategori_id' => $katKudapan->id,
                'nama' => 'Toko Pusat Oleh-Oleh Kacang Sihobuk Ny. Silitonga',
                'deskripsi' => 'Toko pusat kerajinan kuliner Kacang Sihobuk asli di lembah Silindung Tarutung yang menyangrai kacang tanah pilihan menggunakan pasir hitam gunung di atas kuali tanah liat.',
                'alamat' => 'Desa Sihobuk, Jl. Tarutung - Sibolga No. 19, Tarutung, Kab. Tapanuli Utara',
                'latitude' => 2.0235000,
                'longitude' => 98.9612000,
                'harga_min' => 20000,
                'harga_max' => 65000,
                'jam_buka' => '07:00:00',
                'jam_tutup' => '21:00:00',
                'is_otentik_batak' => true,
                'menus' => [
                    [
                        'nama_menu' => 'Kacang Sihobuk Sangrai Pasir Gunung (Kaleng Besar)',
                        'harga' => 45000,
                        'deskripsi' => 'Kacang tanah kulit ari yang direndam bumbu manis-gurih rahasia, dijemur matahari, lalu digongseng dengan pasir vulkanik panas hingga renyah merata.',
                        'cerita' => [
                            'judul' => 'Kacang Sihobuk Sangrai Pasir: Buah Tangan Kebanggaan Lembah Silindung',
                            'asal_usul' => 'Bermula pada tahun 1950-an di Desa Sihobuk, Tarutung, ketika para ibu menemukan teknik menyangrai kacang tanpa minyak menggunakan pasir sungai bersih yang menghantarkan panas merata.',
                            'makna_adat' => 'Menjadi simbol buah tangan wajib (silua) yang dibawa oleh siapa pun yang melintasi Tapanuli Utara sebagai tanda ingatan kepada sanak saudara di kota.',
                            'istilah_lokal' => 'Marhobuk = bunyi gemeretak kacang saat disangrai di dalam kuali berisi pasir panas.',
                        ],
                    ],
                    [
                        'nama_menu' => 'Bandrek Jahe Merah Andaliman Tarutung',
                        'harga' => 18000,
                        'deskripsi' => 'Minuman rempah hangat dari jahe merah tumbuk, kayu manis, cengkeh, gula aren, dan sedikit sentuhan bulir andaliman.',
                        'cerita' => [
                            'judul' => 'Bandrek Jahe Merah Andaliman: Penolak Angin Malam Lembah Tarutung',
                            'asal_usul' => 'Diracik untuk menemani warga dan peziarah wisata rohani Salib Kasih Tarutung di malam hari yang berhawa sejuk.',
                            'makna_adat' => 'Kehangatan rempahnya mempererat percakapan malam antar tetua adat saat berdiskusi di beranda rumah.',
                            'istilah_lokal' => 'Las ni Daging = penghangat tubuh alami dari perpaduan jahe dan merica Batak.',
                        ],
                    ],
                ],
            ],

            // TOKO 9: PARAPAT - IKAN BAKAR & SAMBAL
            [
                'wilayah_id' => $parapat->id,
                'kategori_id' => $katIkan->id,
                'nama' => 'Resto Apung Ikan Bakar & Sambal Tuktuk Pantai Bebas Parapat',
                'deskripsi' => 'Rumah makan tepi danau di kawasan Pantai Bebas Parapat dengan pemandangan langsung ke Pulau Samosir, menyajikan olahan ikan nila dan pora-pora segar hasil tangkapan nelayan pagi.',
                'alamat' => 'Jl. Sisingamangaraja No. 105, Kawasan Pantai Bebas Parapat, Kab. Simalungun',
                'latitude' => 2.6632000,
                'longitude' => 98.9341000,
                'harga_min' => 25000,
                'harga_max' => 80000,
                'jam_buka' => '09:00:00',
                'jam_tutup' => '22:00:00',
                'is_otentik_batak' => true,
                'menus' => [
                    [
                        'nama_menu' => 'Ikan Pora-Pora Goreng Tepung Renyah Sambal Andaliman',
                        'harga' => 35000,
                        'deskripsi' => 'Ikan kecil endemik perairan jernih Danau Toba yang digoreng garing utuh lalu disajikan bersama cocolan sambal andaliman jeruk nipis.',
                        'cerita' => [
                            'judul' => 'Ikan Pora-Pora Danau Toba: Anugerah Perairan Dalam bagi Nelayan Parapat',
                            'asal_usul' => 'Pora-pora adalah ikan air tawar berukuran mungil yang hidup berkelompok di perairan dingin Danau Toba dan menjadi lauk harian masyarakat pesisir Parapat.',
                            'makna_adat' => 'Mengajarkan bahwa berkat yang tampak kecil namun berkumpul banyak akan memberi penghidupan yang melimpah bagi seisi kampung.',
                            'istilah_lokal' => 'Ihan Pora-pora = ikan mungil danau yang dapat disantap utuh dari kepala hingga ekor sebagai sumber kalsium alami.',
                        ],
                    ],
                    [
                        'nama_menu' => 'Ikan Nila Bakar Bambu Sambal Tuktuk Asoč',
                        'harga' => 60000,
                        'deskripsi' => 'Ikan nila keramba air dalam dibakar dengan olesan bumbu kunyit kemiri, disajikan dengan Sambal Tuktuk khas dari ikan aso-aso kering.',
                        'cerita' => [
                            'judul' => 'Sambal Tuktuk Ikan Aso-Aso: Seni Mengawetkan Rasa di Tepian Kaldera',
                            'asal_usul' => 'Masyarakat Batak zaman dahulu mengeringkan ikan kecil (aso-aso) di bawah terik matahari lalu menumbuknya bersama andaliman dan bawang bakar agar tahan berminggu-minggu.',
                            'makna_adat' => 'Simbol ketekunan dan persiapan matang menghadapi musim angin kencang ketika nelayan tidak dapat turun ke danau.',
                            'istilah_lokal' => 'Tuktuk = menumbuk halus seluruh bahan sambal dan ikan kering di atas batu gilingan (batu lilis).',
                        ],
                    ],
                ],
            ],

            // TOKO 10: SIMANINDO / TUKTUK SAMOSIR - BUMBU & SAMBAL KHAS
            [
                'wilayah_id' => $simanindo->id,
                'kategori_id' => $katSambal->id,
                'nama' => 'Dapur Warisan Inang Tuktuk Siadong & Rumah Sambal Andaliman',
                'deskripsi' => 'Toko kuliner dan galeri rempah di semenanjung wisata Tuktuk Siadong, Samosir, tempat wisatawan mencicipi aneka olahan sambal Batak serta hidangan dali ni horbo (keju susu kerbau tradisional).',
                'alamat' => 'Jl. Lingkar Tuktuk Siadong No. 45, Kec. Simanindo, Pulau Samosir',
                'latitude' => 2.6718000,
                'longitude' => 98.8574000,
                'harga_min' => 25000,
                'harga_max' => 75000,
                'jam_buka' => '08:30:00',
                'jam_tutup' => '21:30:00',
                'is_otentik_batak' => true,
                'menus' => [
                    [
                        'nama_menu' => 'Dali Ni Horbo (Tahu Susu Kerbau Masak Arsik)',
                        'harga' => 65000,
                        'deskripsi' => 'Susu kerbau segar Samosir yang dipadatkan secara alami menggunakan sari buah pepaya dan air nanas tanpa bahan kimia, dimasak dengan kuah kuning arsik.',
                        'cerita' => [
                            'judul' => 'Dali Ni Horbo: Keju Tradisional Susu Kerbau Warisan Leluhur Samosir',
                            'asal_usul' => 'Sudah dikenal oleh masyarakat Batak sejak ratusan tahun silam ketika kerbau menjadi ternak paling berharga di padang rumput Samosir. Susu kerbau diperah pagi hari dan dikentalkan di dalam periuk tanah.',
                            'makna_adat' => 'Kerbau (horbo) adalah lambang kemakmuran tertinggi dalam adat Batak; menyajikan Dali Ni Horbo berarti memuliakan tamu dengan sajian paling bernilai gizi tinggi.',
                            'istilah_lokal' => 'Bagot ni Horbo = air susu induk kerbau yang diolah menjadi dadih lembut gurih.',
                        ],
                    ],
                    [
                        'nama_menu' => 'Paket Tiga Rasa Sambal Pusaka (Tuktuk, Rias, & Andaliman Hijau)',
                        'harga' => 30000,
                        'deskripsi' => 'Tiga jenis sambal khas Batak yang diulek segar di cobek batu, disajikan bersama nasi hangat, ikan goreng, dan lalapan terong rebus.',
                        'cerita' => [
                            'judul' => 'Trio Sambal Pusaka Toba: Identitas Rasa Merica Batak (Zanthoxylum acanthopodium)',
                            'asal_usul' => 'Tanaman andaliman hanya tumbuh liar secara optimal di tanah vulkanik sekeliling Danau Toba pada ketinggian 1.000-1.500 mdpl, menjadikannya rempah endemik yang tak tergantikan.',
                            'makna_adat' => 'Sensasi getir dan hangat andaliman di lidah melambangkan semangat hidup masyarakat Batak yang tegas, hangat, dan penuh kejujuran.',
                            'istilah_lokal' => 'Sihala & Andaliman = dua rempah kembar jiwa masakan Batak yang melahirkan harmoni asam wangi dan pedas bergetar.',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($stores as $storeData) {
            $menus = $storeData['menus'];
            unset($storeData['menus']);

            $dest = Destinasi::updateOrCreate(
                ['nama' => $storeData['nama']],
                array_merge($storeData, [
                    'pemilik_id'        => $umkm1->id,
                    'status_verifikasi' => 'disetujui',
                    'diverifikasi_oleh' => $admin->id,
                    'diverifikasi_pada' => now(),
                ])
            );

            foreach ($menus as $m) {
                MenuDestinasi::updateOrCreate(
                    ['destinasi_id' => $dest->id, 'nama_menu' => $m['nama_menu']],
                    [
                        'harga'     => $m['harga'],
                        'deskripsi' => $m['deskripsi'],
                    ]
                );

                if (!empty($m['cerita'])) {
                    CeritaBudaya::updateOrCreate(
                        ['destinasi_id' => $dest->id, 'judul' => $m['cerita']['judul']],
                        [
                            'asal_usul'     => $m['cerita']['asal_usul'],
                            'makna_adat'    => $m['cerita']['makna_adat'],
                            'istilah_lokal' => $m['cerita']['istilah_lokal'],
                        ]
                    );
                }
            }

            // Tambahkan ulasan warga lokal & wisatawan untuk setiap toko
            Ulasan::updateOrCreate(
                ['destinasi_id' => $dest->id, 'user_id' => $lokal->id],
                [
                    'tipe_penilai'       => 'lokal',
                    'rating_umum'        => 5,
                    'rating_rasa'        => 5,
                    'rating_harga'       => 5,
                    'rating_keotentikan' => 5,
                    'komentar'           => "Rasa masakan di {$dest->nama} sangat otentik! Takaran andaliman dan rempah adatnya benar-benar terjaga seperti racikan leluhur di huta.",
                    'status_moderasi'    => 'tampil',
                ]
            );

            Ulasan::updateOrCreate(
                ['destinasi_id' => $dest->id, 'user_id' => $wisatawan->id],
                [
                    'tipe_penilai'       => 'wisatawan',
                    'rating_umum'        => 5,
                    'rating_rasa'        => 5,
                    'rating_harga'       => 4,
                    'rating_keotentikan' => 5,
                    'komentar'           => "Toko {$dest->nama} tempatnya nyaman, pelayanannya ramah, dan cerita budaya di setiap menunya membuat pengalaman kuliner di Danau Toba makin berkesan!",
                    'status_moderasi'    => 'tampil',
                ]
            );
        }

        // 5. Titik Wisata Alam Kaldera Toba untuk Peta & Itinerary Builder
        $wisataAlams = [
            [
                'wilayah_id' => $pangururan->id,
                'nama'       => 'Bukit Holbung Samosir',
                'deskripsi'  => 'Gugusan bukit padang ilalang hijau dengan panorama 360 derajat Kaldera Danau Toba.',
                'latitude'   => 2.5364000,
                'longitude'  => 98.7189000,
            ],
            [
                'wilayah_id' => $balige->id,
                'nama'       => 'Pantai Lumban Bulbul Balige',
                'deskripsi'  => 'Pantai pasir putih air tawar di tepian Danau Toba dengan pemandangan perbukitan Toba.',
                'latitude'   => 2.3458000,
                'longitude'  => 99.0691000,
            ],
            [
                'wilayah_id' => $simanindo->id,
                'nama'       => 'Desa Adat Batu Kursi Raja Siallagan (Ambarita)',
                'deskripsi'  => 'Situs megalitikum kursi batu persidangan raja Batak kuno di tepi semenanjung Tuktuk Samosir.',
                'latitude'   => 2.6815000,
                'longitude'  => 98.8362000,
            ],
            [
                'wilayah_id' => $parapat->id,
                'nama'       => 'Batu Gantung & Dermaga Wisata Parapat',
                'deskripsi'  => 'Tebing batu legendaris di perairan Parapat yang menjadi ikon cerita rakyat Danau Toba.',
                'latitude'   => 2.6512000,
                'longitude'  => 98.9284000,
            ],
            [
                'wilayah_id' => $tarutung->id,
                'nama'       => 'Bukit Wisata Rohani Salib Kasih Tarutung',
                'deskripsi'  => 'Monumen di puncak Bukit Siatas Barita yang menghadap seluruh lembah Silindung Tarutung.',
                'latitude'   => 2.0418000,
                'longitude'  => 98.9795000,
            ],
            [
                'wilayah_id' => $lintong->id,
                'nama'       => 'Geosite Sipinsur Humbang Hasundutan',
                'deskripsi'  => 'Taman hutan pinus di tepi tebing kaldera dengan pemandangan Pulau Sibandang dari ketinggian.',
                'latitude'   => 2.3145000,
                'longitude'  => 98.9382000,
            ],
        ];

        foreach ($wisataAlams as $wa) {
            WisataAlam::updateOrCreate(['nama' => $wa['nama']], $wa);
        }
    }
}