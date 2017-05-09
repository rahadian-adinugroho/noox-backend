<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Noox\Models\News;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Schema::disableForeignKeyConstraints();
        News::truncate();
        Schema::enableForeignKeyConstraints();
        
        $entries = [
            [
            'source_id' => 1,
            'cat_id'    => 9,
            'title'     => 'Snapdragon 660 dan 630 Jadi Jagoan Baru Qualcomm',
            'pubtime'   => '2017-05-09 14:52:00',
            'author'    => 'Yudhianto',
            'url'       => 'https://inet.detik.com/consumer/d-3496637/snapdragon-660-dan-630-jadi-jagoan-baru-qualcomm',
            'content'   => 'Jakarta - Qualcomm resmi mengumumkan prosesor terbarunya di kelas menengah. Keduanya dinamai Snapdragon 660 dan Snapdragon 630. Apa kelebihannya?

Snapdragon 660 adalah prosesor pertama di kelas Snapdragon 600 yang dibekali core Kryo dengan pabrikasi 14 nm. Yakni core yang sama yang ditanamkan Qualcomm di prosesor flagship-nya, Snapdragon 835. Hal ini membuat Snapdragon 660 mampu memberikan lompatan performa hingga 20% dibanding Snapdragon 653.

Secara teknis, Snapdragon 660 mengandalkan 8 core Kryo 260 dengan kecepatan clock di angka 2,2 GHz. Sementara chip grafisnya, yang adalah Adreno 512 diklaim Qualcomm menyodorkan performa 30% lebih tinggi dari Adreno 510 di Snapdragon 653.

Di sisi lain, prosesor ini juga mendukung modem X12 LTE, penggunan Bluetooth 5.0, USB 3.1, layar QHD (2.560 x 1.440 pixel), perekaman video 4K, hingga Quick Charge 4.0.

Beralih ke Snapdragon 630, prosesor ini sama-sama mengusung pabrikasi 14 nm dan punya kecepatan clock mencapai 2,2 GHz. Sedangkan teknologi octa core yang digunakan berbasis arsitektur ARM Cortex-A53. Dibanding Snapdragon 626, performa Snapdragon 630 dijanjikan 10% lebih tinggi.

Sementara di sisi grafis, Adreno 508 yang ditandem dalam Snapdragon 630 diklaim punya performa yang 30% lebih baik.

Improvisasi lainnya terletak pada kehadiran modem X12 LTE, dan dukungan Snapdragon 630 terhadap Bluetooth 5.0, perekaman video 4K, hingga Quick Charge 4.0.

Qualcomm mengklaim perangkat dengan Snapdragon 660 dan Snapdragon 630 sudah akan hadir ke pasaran sekitar bulan Juni mendatang, demikian seperti dijelaskan dalam keterangan resmi yang diterima detikINET, Selasa (9/5/2017).

(yud/fyk)'],
            [
            'source_id' => 2,
            'cat_id'    => 6,
            'title'     => 'Toyota Kembangkan Bodi Aluminium',
            'pubtime'   => '2017-05-09 16:22:00',
            'author'    => 'GHULAM MUHAMMAD NAYAZRI',
            'url'       => 'http://otomotif.kompas.com/read/2017/05/09/162200115/toyota.kembangkan.bodi.aluminium',
            'content'   => 'Michigan, KompasOtomotif – Produsen otomotif terbesar Jepang, Toyota, berencana untuk mengembangkan material ringan untuk memenuhi standar efisiensi bahan bakar yang ketat. Penggunaan aluminium adalah salah satu yang masuk dalam pertimbangan.

Mengutip Carscoop dan Autonews, Selasa (9/5/2017)  Jim Lentz, CEO Toyota Motor North America mengatakan, untuk mengejar ketatnya aturan tersebut, tidak cukup hanya sebatas memodifikasi mesin saja, tapi juga mengarah pada material bodi.

"Kami harus melihat banyak cara berbeda untuk memperbaiki ekonomi bahan bakar. Jadi, jelas, kita akan melihat lebih banyak cara untuk menggunakan bahan ringan seperti aluminium di produk masa depan Toyota,” ujar Lentz.

Robert Young, Vice President Toyota yang bertanggung jawab terkai belanja yang pemasok mengatakan, panel bodi luar seperti hood (kap mobil) misalnya, adalah tempat yang relatif mudah untuk mulai menggunakan aluminium, tapi langkah besar berikutnya adalah membuat komponen platform dari aluminium.

“Memutuskan pilihan material antara aluminium dan baja, bisa saja membuat pengorbanan pada biaya, keandalan, kompatibilitas cat, dan kekuatan,” ujar Young.

Pertimbangan lainnya, lagi, peningkatan penggunaan aluminium juga tidak akan murah bagi Toyota. Pasalnya saat ini, Presiden Trump sedang mempertimbangkan tarif dan beberapa ukuran lain pada aluminium dan baja yang tidak dibuat di Amerika.'],
            ['source_id' => 1,
            'cat_id'     => 9,
            'title'      => 'Far Cry Terbaru Beraksi September',
            'pubtime'    => '2017-05-09 17:48:00',
            'author'     => 'Muhammad Alif Goenawan',
            'url'        => 'https://inet.detik.com/games-news/d-3496855/far-cry-terbaru-beraksi-september',
            'content'    => 'Jakarta - Selain serial game Tom Clancy, Far Cry menjadi salah satu waralaba game tembak-tembakan yang terkenal dari Ubisoft. Nah, seri terbaru Far Cry kabarnya akan dirilis pada bulan September 2017. Apa benar?

Kabar Far Cry terbaru ini sebenarnya muncul dari salah seorang kru film, di mana menurut pengakuannya ia telah melihat proses syuting untuk trailer pengumuman game Far Cry.

Seperti yang dituturkan oleh kru film tersebut, dikutip detikINET dari Ubergizmo, Selasa (9/5/2017), proses syuting trailer Far Cry tersebut dilakukan di sebuah gereja di wilayah padang rumput Montana, Amerika Serikat. Lokasi syuting tersebut pun mengarah pada spekulasi bahwa Far Cry terbaru nanti kemungkinan akan memiliki tema Wild West.

Spekulasi itu pun coba dibenarkan oleh produser yang memiliki wewenang di lokasi syuting, yakni Jeff Guillot. Ia memberi sinyal apabila video trailer yang ia garap memang merupakan sekuel dari game yang ada saat ini.

Sebagaimana diketahui, Guillot sendiri memiliki ikatan yang cukup kuat dengan Ubisoft. Ia sebelumnya pernah didapuk Ubisoft untuk menggarap promo kampanye untuk sejumlah game-nya. Jadi, ketika mendengar kabar ia menggarap video trailer untuk game Ubisoft, mungkin bukan hal yang mengherankan lagi.

Karena disebut akan rilis September 2017, seharusnya kita bisa melihat trailer tersebut pada ajang E3 2017 bulan Juni 2017. Kita tunggu saja    (mag/yud)'],
            ['source_id' => 3,
            'cat_id'     => 7,
            'title'      => 'FPI Juga Bakal Dibubarkan? Ini Kata Wiranto',
            'pubtime'    => '2017-05-08 17:26:00',
            'author'     => 'Ahmad Romadoni',
            'url'        => 'http://news.liputan6.com/read/2945037/fpi-juga-bakal-dibubarkan-ini-kata-wiranto',
            'content'    => 'Liputan6.com, Jakarta - Pemerintah akhirnya membubarkan Hizbut Tahrir Indonesia (HTI). Salah satu alasan HTI dibubarkan karena dinilai membahayakan keutuhan negara kesatuan Republik Indonesia (NKRI).

Setelah pembubaran HTI, muncul dorongan untuk membubarkan ormas lain yang juga dinilai, tidak sejalan dengan Pancasila dan UUD 1945. Salah satu nama yang muncul ada Front Pembela Islam (FPI).

"Yang lain terus dipelajari, enggak usah semua. Satu-satu," kata Menko Polhukam Wiranto di kantornya, Senin (8/5/2017).

Ia memastikan, pembubaran HTI ini juga melalui kajian yang cukup panjang. Sampai akhirnya, siang ini difinalisasi dan diputuskan untuk membubarkan HTI.

"Kami memfinalisasi satu proses cukup panjang mempelajari ormas di Indonesia yang jumlah ribuan bahkan ratusan ribu untuk mengarahkan mereka dalam koridor yang telah ditetapkan pada undang-undang keormasan baik dalam tujuan, ciri, dan asas. Semua harus menuju satu titik yakni berdasar ideologi negara Pancasila," jelas dia.

HTI Bahayakan NKRI

Pemerintah memutuskan untuk membubarkan HTI. Beberapa hari belakangan, HTI memang menjadi sorotan karena ingin menegakkan khilafah di Indonesia.

Menko Polhukam Wiranto menjelaskan, pemerintah punya alasan khusus sampai akhirnya mengambil keputusan tersebut. Salah satunya, kegiatan HTI dinilai dapat membahayakan keutuhan negara kesatuan NKRI.

"Aktivitas yang dilakukan HTI nyata-nyata telah menimbulkan benturan di tengah masyarakat yang pada gilirannya mengancam keamanan dan ketertiban di tengah masyarakat serta membahayakan keutuhan NKRI," ujar Wiranto di kantornya, Senin (8/5/2017).

Selain itu, selama berdiri di Indonesia, HTI tidak melaksanakan peran positif dalam mengambil bagian pada proses pembangunan guna mencapai tujuan nasional.

Kemudian, kegiatan yang dilaksanakan HTI terindikasi kuat telah bertentangan dengan tujuan, asas, dan ciri yang berdasar Pancasila dan UUD 1945 sebagaimana diatur dalam UU No 17/2013 tentang Organisasi Kemasyarakatan.

"Mencermati pertimbangan di atas serta menyerap aspirasi, pemerintah perlu mengambil langkah hukum secara tegas untuk membubarkan HTI," ucap Wiranto.

'],
            ['source_id' => 2,
            'cat_id'     => 2,
            'title'      => 'Jadi Bank Lokal, HSBC Ingin Garap Pembiayaan Infrastruktur',
            'pubtime'    => '2017-05-09 18:00:00',
            'author'     => 'SAKINA RAKHMA DIAH SETIAWAN',
            'url'        => 'http://bisniskeuangan.kompas.com/read/2017/05/09/180000726/jadi.bank.lokal.hsbc.ingin.garap.pembiayaan.infrastruktur',
            'content'    => 'JAKARTA, KOMPAS.com - HSBC Indonesia sudah secara resmi berintegrasi dengan PT Bank Ekonomi Raharja sebagai bank lokal per 17 April 2017 lalu. Dengan demikian, kini HSBC menjadi PT Bank HSBC Indonesia.

Presiden Direktur HSBC Sumit Duta menjelaskan, pengintegrasian HSBC menjadi bank lokal dimaksudkan untuk pertumbuhan bisnis perseroan ke depan. Ini sejalan pula dengan prospek pertumbuhan ekonomi Indonesia yang semakin menjanjikan.

Sumit menuturkan, HSBC menggelontorkan investasi sebesar 1 miliar dollar AS untuk pengintegrasian menjadi bank lokal. Menurut dia, pihaknya melihat Indonesia semakin maju secara ekonomi ke depan, didukung berbagai upaya yang diinisiasi pemerintahan Presiden Joko Widodo.

"Sebagai emerging markets, Indonesia kami pandang paling vital dan cerah. Kami ingin menjadi bagian dari itu dan melihat kesempatan yang bagus di Indonesia," ujar Sumit dalam konferensi pers di Mercantile Athletic Club Jakarta, Selasa (9/5/2017).

Dengan menjadi bank lokal, HSBC ingin menangkap peluang bisnis yang sangat besar, khususnya dalam pembiayaan proyek-proyek infrastruktur.

Menurut Sumit, ini sejalan dengan komitmen Presiden Jokowi yang sangat besar dalam pembangunan beragam proyek infrastruktur.

Dalam kesempatan yang sama, Direktur Komersial HSBC Indonesia Catherine Hadiman menuturkan, target bisnis HSBC Indonesia saat ini adalah infrastruktur yang memang menjadi salah satu primadona di Indonesia. Perseroan, kata dia, akan berpartisipasi dalam pembiayaan proyek infrastruktur.

"Tidak hanya di infrastruktur dasar, tapi juga di faktor pendukungnya, seperti kontraktor, subkontraktor, dan pemasoknya," ujar Catherine.

Catherine mengakui, untuk membiayai proyek infrastruktur memang dibutuhkan dana besar dan cenderung berjangka panjang. Akan tetapi, ia menuturkan modal yang dimiliki HSBC Indonesia saat ini cukup untuk mendukung proyek-proyek infrastruktur.

"Kalau memang dananya butuh besar bisa dilakukan dengan sindikasi. Kita akan lihat, kalau pertumbuhan ekonomi Indonesia semakin cepat maka komitmen kita akan tinggi, kita tidak akan mau ketinggalan," tutur Catherine.'],
            ['source_id' => 1,
            'cat_id'     => 1,
            'title'      => 'Terdakwa e-KTP Bagi-bagi Duit Rp 10 juta ke 5 PNS Kemendagri',
            'pubtime'    => '2017-05-08 14:29:00',
            'author'     => 'Rina Atriana',
            'url'        => 'https://news.detik.com/berita/d-3495298/terdakwa-e-ktp-bagi-bagi-duit-rp-10-juta-ke-5-pns-kemendagri',
            'content'    => 'Jakarta - Mantan Direktur Pengelolaan Informasi Administrasi Kependudukan (PIAK) Ditjen Dukcapil Kemendagri yang kini jadi terdakwa e-KTP, Sugiharto, pernah bagi-bagi uang kepada 5 anak buahnya saat proyek e-KTP bergulir. Kelima orang tersebut menerima masing-masing Rp 10 juta. 

Pemberian uang tersebut diungkapkan salah seorang PNS Dukcapil Kemendagri yang menjabat sebagai Sekretaris Korwil 3, Lydia Ismu Martyati Anny Miryanti saat pengadaan e-KTP bergulir. Anny sebelumnya menjelaskan mengenai fungsi adanya Korwil dalam pengadaan e-KTP. 

"Pertama ada Ketua Korwil eselon 2, Wakil Ketua Korwil, Sekretaris Korwil, di bawah lagi ada penanggung jawab Provinsi dan Kabupaten/Kota," kata Anny saat bersaksi di Pengadilan Tindak Pidana Korupsi (Tipikor), Jl Bungur Besar Raya, Jakarta Pusat, Senin (8/5/2017). 

Korwil 3 menurut Anny bertanggung jawab untuk perekaman data e-KTP di wilayah Jambi, Bengkulu, Bangka Belitung, DKI Jakarta, Sulsel, Papua, dan Papua Barat. Sugiharto sendiri merupakan Ketua Korwil 3. 

Anny menyebut ada 4 orang Sekretaris Korwil lain yang dipanggil ke ruangan Sugiharto kala itu. Mereka adalah Sekretaris Korwil 1 Kristina, Sekretaris Korwil 2 Santi, Sekretaris Korwil 4 Wiwi, dan Sekretaris Korwil 5 Handoyo. 

"Lima-limanya dipanggil ke ruangan beliau, diberikan uang per korwil Rp 10 juta, untuk transport, operasional. Jadi bukan saya yang memberikan tapi kami diberikan di runagan beliau masing-masing kami diberi Rp 10 juta," tutur Anny. 

Saat ditanya jaksa apakah bertanya dari mana asal uang tersebut, Anny menjawab tidak. Mereka juga tak menandatangani apapun terkait penerimaan Rp 10 juta itu. 

"Kami tidak tahu (uang dari mana). Waktu itu kami sudah mau pulang," ujar Anny. 

Dalam persidangan belum diungkap apakah uang tersebut sudah dikembalikan ke KPK atau tidak. 
(rna/fdn)'],
            ['source_id' => 3,
            'cat_id'     => 1,
            'title'      => 'JK Pastikan Pembubaran HTI Melalui Pengadilan',
            'pubtime'    => '2017-05-09 16:20:00',
            'author'     => 'Putu Merta Surya Putra',
            'url'        => 'http://news.liputan6.com/read/2946062/jk-pastikan-pembubaran-hti-melalui-pengadilan?HouseAds&campaign=HTI_Home_STS1',
            'content'    => 'Liputan6.com, Jakarta Wakil Presiden Jusuf Kalla mengatakan, paham yang dijalankan Hizbut Tahrir Indonesia (HTI) adalah kekhalifahan. Jika itu yang terjadi, kata JK, maka Indonesia kembali kepada konsep masa lalu.

"Di zaman itu, kepala pemerintahan sama juga merangkap pimpinan agama, seperti zaman Umayyah, Khalifah, Abbasiyah, dan Ottoman. Jadi semacam lintas batas. Padahal sekarang ini sudah jelas. Negara itu punya ketentuan-ketentuan sendiri. Jadi paham itu, memang tidak sesuai dengan konsep kenegaraan yang kita anut sekarang ini," ucap JK di kantornya, Jakarta, Selasa (9/5/2017).

JK menjelaskan, yang salah apabila menggabungkan dua kepemimpinan yakni agama dan pemerintahan dengan tanpa batas. Ia mengaku, jika HTI berlandaskan agama saja, tidak masalah.

"Semua agama-agama juga punya rasa universal. Katakanlah faham Syiah itu kan berpusat di Iran, Katolik di Vatikan. Jadi ikut apa yang disampaikan di sana. Juga banyak orang Islam yang ikut fatwa-fatwa dari ulama-ulama, katakanlah di Mekah. Tapi kalau kenegaraan tidak boleh," beber Jusuf Kalla.

Atas dasar itulah, ia mengatakan, pemerintah menilai HTI tidak sesuai dengan Pancasila. "Begitu kan, jadi itu masalahnya. Jadi kalau itu, ya tentu melanggar dan kita tidak setuju," jelas JK.

Kendati begitu, JK memastikan pemerintah tetap menempuh jalur hukum melalui pengadilan terkait pembubaran HTI. Karena itu, ia mengajak semua pihak menunggu proses hukum tersebut.

"Seperti juga yang Anda baca, prosesnya itu nanti lewat hukum, pengadilan. Saya bicara sebelumnya juga dengan Pak Wiranto juga. Bahwa itu prosesnya (pembubaran HTI) proses hukum," pungkas Jusuf Kalla.'],
            ['source_id' => 1,
            'cat_id'     => 4,
            'title'      => 'Fidget Spinner Disebut Bisa Redakan Stres, Ini Tanggapan Psikolog',
            'pubtime'    => '2017-05-09 16:15:00',
            'author'     => 'Radian Nyi Sukmasari',
            'url'        => 'https://health.detik.com/read/2017/05/09/161044/3496715/763/fidget-spinner-disebut-bisa-redakan-stres-ini-tanggapan-psikolog?l991101755',
            'content'    => 'Jakarta, Fidget spinner atau spinner adalah mainan dengan bentuk piringan cakram berukuran kecil dan memiliki bearing yang membuatnya bisa berotasi. Mainan ini populer karena diklaim bisa meredakan stres, meningkatkan fokus, dan disebut bagus untuk anak dengan Attention Deficit Hyperactive Disorders (ADHD).

Benarkah hal tersebut? Psikolog anak dan remaja Ratih Zulhaqqi, M.Psi, dari RaQQi - Human Development & Learning Centre berkomentar bahwa yang jelas belum ada studi yang bisa menegakkan klaim efektivitas mainan tersebut.

Namun bila dilihat dari cara kerjanya bukan tidak mungkin juga bahwa spinner memang bermanfaat. Hal ini menurut Ratih karena ada juga mainan serupa seperti stres ball yang dulu kerap dipakai untuk membantu redakan stres.

"Kenapa dikatakan stres release? Karena ketika kita memandang sesuatu yang berulang-ulang secara terus menerus pada satu titik, kita terokupasi dengan situasi itu. Fokusnya ke situ. Sehingga yang lain-lain jadi turun, termasuk stresnya," ungkap Ratih kepada detikHealth dan ditulis pada Selasa (9/5/2017).

"Nggak ada salahnya kalau mau dicoba. Tapi ini bukan alat utama ya buat anak-anak yang mengalami gangguan konsentrasi atau masalah sensori. Ini semacam tools tambahan ajalah," lanjut Ratih.

Mungkin ada orang yang bisa merasa lebih tenang dengan fidget spinner, tapi bisa juga ada yang tidak. Mencari metode yang tepat adalah tugas dari ahli untuk diterapkan pada klien-kliennya.

"Belum pernah dicoba juga ke klien karena belum terlalu familiar. Mungkin suatu waktu ini bisa dicoba," pungkas Ratih.'],
            ['source_id' => 2,
            'cat_id'     => 2,
            'title'      => 'Mandiri Online Normal Kembali',
            'pubtime'    => '2017-05-07 21:21:00',
            'author'     => 'SAKINA RAKHMA DIAH SETIAWAN',
            'url'        => 'http://bisniskeuangan.kompas.com/read/2017/05/07/212133926/mandiri.online.normal.kembali',
            'content'    => 'JAKARTA, KOMPAS.com - Sistem Mandiri Online yang dimiliki oleh PT Bank Mandiri (Persero) Tbk sempat mengalami kendala teknis. Dana yang dimiliki sejumlah nasabah terdebet secara otomatis tanpa ada transaksi.

Bank Mandiri menegaskan permasalahan yang dialami sistem Mandiri Online adalah kendala teknis. Saat ini sistem tersebut sudah kembali diaktifkan.

"Dari pemeriksaan kami, ada 97 nasabah yang mendapat kekeliruan karena sistem. Kekeliruan itu telah kami koreksi kembali," kata Corporate Secretary Bank Mandiri Rohan Hafas ketika dihubungi Kompas.com, Minggu (7/5/2017).

Dihubungi secara terpisah melalui sambungan telepon, Deputi Bidang Usaha Jasa Keuangan, Jasa Survei dan Konsultan, Kementerian Badan Usaha Milik Negara (BUMN) Gatot Trihargo menyatakan, pihaknya terus melakukan komunikasi dengan manajemen Bank Mandiri terkait masalah yang dialami Mandiri Online. Bank Mandiri, kata Gatot, menyatakan sistem itu sudah kembali aktif.

"Intinya kalau ada kesalahan, dana akan digantikan oleh Bank Mandiri. Tidak ada masalah," tutur Gatot.

Gatot menyatakan pula bahwa manajemen Bank Mandiri sudah melakukan komunikasi dengan Bank Indonesia (BI) dan Otoritas Jasa Keuangan (OJK) selaku otoritas sistem pembayaran dan perbankan.

Bank Mandiri menegaskan bakal bertanggung jawab jika ada kesalahan yang berasal dari sistem layanannya.

"Ada jaminan dari (Bank) Mandiri, kalau ada dana yang hilang jangan khawatir. Kalau itu kesalahan bank, maka akan diganti oleh Bank Mandiri," terang Gatot.'],
            ['source_id' => 2,
            'cat_id'     => 1,
            'title'      => 'Tersangka Kasus BLBI Ajukan Praperadilan Lawan KPK',
            'pubtime'    => '2017-05-09 18:00:00',
            'author'     => 'Nur Indah Fatmawati',
            'url'        => 'https://news.detik.com/berita/d-3496872/tersangka-kasus-blbi-ajukan-praperadilan-lawan-kpk',
            'content'    => 'Jakarta - Mantan Ketua Badan Penyehatan Perbankan Nasional (BPPN) Syafruddin Arsyad Temenggung melawan KPK. Tersangka kasus korupsi Bantuan Likuidasi Bank Indonesia (BLBI) itu mengajukan gugatan praperadilan ke Pengadilan Negeri Jakarta Selatan (PN Jaksel).

"Kemarin 8 Mei, kami terima panggilan praperadilan BLBI. Diagendakan persidangan pertama 15 Mei 2017," kata Kabiro Humas KPK Febri Diansyah di kantornya, Jalan Kuningan Persada, Jakarta Selatan, Selasa (9/5/2017).

Febri menyebut salah satu permohonan Syafruddin yaitu KPK dinilai tidak berwenang mengusut BLBI. Febri mengatakan permohonan itu salah satunya mencantumkan bila kasus BLBI merupakan ranah perdata.

"Jadi pada permohonan praperadilan tersebut secara umum pemohon mengatakan SAT (Syafruddin Arsyad Temenggung) selaku tersangka, KPK tidak berwenang karena ini ranah perdata dan tidak bisa menangani kasus berlaku surat karena hanya berdasar Undang-undang nomor 30 tahun 2002 dan terkait SKL kami akan hadapi dengan argumentasi lebih lanjut," ujar Febri.

Untuk menghadapi praperadilan tersebut, Febri menegaskan KPK menyiapkan argumentasi yang presisi. KPK pun siap mengajukan bukti-bukti untuk memperkuat argumen dalam sidang praperadilan tersebut.

"Secara formil kami harus tunjukkan bukti-bukti yang ada karena KPK punya kewajiban saat meningkatkan ke penyidikan ada syarat bukti permulaan yang cukup tapi tidak bisa secara rinci karena itu ranah pokok perkara yang seharusnya di pengadilan Tipikor," ucap Febri.

Dalam kasus ini, Syafruddin Arsyad Temenggung ditetapkan sebagai tersangka selaku Ketua Badan Penyehatan Perbankan Nasional (BPPN). Dia menerbitkan SKL terhadap Sjamsul Nursalim selaku pemegang saham pengendali Bank Dagang Nasional Indonesia (BDNI) yang memiliki kewajiban kepada BPPN.

SKL itu dikeluarkan mengacu pada Inpres nomor 8 tahun 2002 yang dikeluarkan pada 30 Desember 2002 oleh Megawati Soekarnoputri yang saat itu menjabat sebagai Presiden RI. KPK menyebut perbuatan Syafruddin menyebabkan kerugian keuangan negara sebesar Rp 3,7 triliun.'],
        ];

        News::insert($entries);
        Model::reguard();
    }
}
