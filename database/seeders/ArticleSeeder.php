<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Streetwear Trend 2025: Yang Wajib Kamu Punya',
                'content' => '<div>
                    <p>Tahun 2025 membawa angin segar untuk dunia streetwear. Dengan berbagai brand lokal yang terus berinovasi, termasuk The Paranoia, tren fashion jalanan semakin beragam dan menarik.</p>
                    
                    <h2>1. Oversized Silhouette</h2>
                    <p>Cutting oversized masih menjadi primadona. Kaos, hoodie, dan jacket dengan siluet longgar memberikan kesan kasual namun tetap stylish. The Paranoia menghadirkan berbagai pilihan oversized tee dengan graphic print yang unik.</p>
                    
                    <h2>2. Earth Tones</h2>
                    <p>Warna-warna earthy seperti olive, khaki, cream, dan brown menjadi favorit tahun ini. Kombinasikan dengan item statement berwarna hitam untuk look yang balanced.</p>
                    
                    <h2>3. Utility Details</h2>
                    <p>Cargo pants dan jacket dengan multiple pockets semakin diminati. Fungsional dan fashionable, perfect untuk aktivitas sehari-hari.</p>
                    
                    <h2>4. Minimalist Logo</h2>
                    <p>Logo-logo minimalis dan embroidery subtle menjadi pilihan dibanding graphic besar yang mencolok. Elegan tapi tetap menunjukkan identitas brand.</p>
                    
                    <blockquote>
                        "Streetwear bukan hanya tentang fashion, tapi tentang mengekspresikan diri dan komunitas." - The Paranoia Team
                    </blockquote>
                    
                    <p>Jangan lupa untuk selalu memilih kualitas dibanding kuantitas. Investasi pada pieces yang timeless dan bisa dipakai dalam berbagai kesempatan.</p>
                </div>',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(5),
            ],
            [
                'title' => 'Cara Merawat Hoodie Agar Tetap Awet',
                'content' => '<div>
                    <p>Hoodie adalah salah satu essential item dalam lemari setiap orang. Tapi tahukah kamu cara merawatnya dengan benar? Berikut tips dari kami.</p>
                    
                    <h2>Tips Mencuci Hoodie</h2>
                    <ul>
                        <li><strong>Balik hoodie sebelum mencuci</strong> - Ini melindungi print dan warna luar dari gesekan.</li>
                        <li><strong>Gunakan air dingin</strong> - Air panas bisa menyebabkan hoodie menyusut dan warna pudar.</li>
                        <li><strong>Hindari deterjen berlebih</strong> - Gunakan secukupnya untuk menghindari residu.</li>
                        <li><strong>Jangan gunakan pemutih</strong> - Bisa merusak serat kain dan warna.</li>
                    </ul>
                    
                    <h2>Tips Mengeringkan</h2>
                    <ul>
                        <li>Hindari mesin pengering dengan suhu tinggi</li>
                        <li>Jemur di tempat teduh untuk menghindari sinar matahari langsung</li>
                        <li>Gantung dengan hanger yang sesuai untuk menjaga bentuk</li>
                    </ul>
                    
                    <h2>Tips Penyimpanan</h2>
                    <p>Lipat hoodie dengan rapi dan simpan di tempat yang kering. Hindari menggantung terlalu lama karena bisa melar di bagian bahu.</p>
                    
                    <p>Dengan perawatan yang tepat, hoodie favoritmu dari The Paranoia bisa awet bertahun-tahun!</p>
                </div>',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(12),
            ],
            [
                'title' => 'Behind The Brand: Cerita The Paranoia',
                'content' => '<div>
                    <p>The Paranoia lahir dari kecintaan terhadap streetwear dan semangat untuk menghadirkan fashion berkualitas dengan harga yang accessible.</p>
                    
                    <h2>Awal Mula</h2>
                    <p>Bermula dari garasi kecil di Bandung, The Paranoia dimulai dengan modal semangat dan beberapa potong kaos. Nama "Paranoia" sendiri diambil dari rasa was-was yang selalu kami rasakan untuk terus berkarya dan memberikan yang terbaik.</p>
                    
                    <h2>Filosofi Desain</h2>
                    <p>Setiap desain The Paranoia dibuat dengan pemikiran matang. Kami percaya bahwa fashion harus:</p>
                    <ul>
                        <li>Timeless - Bisa dipakai kapan saja</li>
                        <li>Quality - Material terbaik yang nyaman</li>
                        <li>Accessible - Harga yang terjangkau</li>
                        <li>Sustainable - Produksi yang bertanggung jawab</li>
                    </ul>
                    
                    <h2>Komunitas</h2>
                    <p>Yang membuat The Paranoia istimewa adalah komunitasnya. Kalian, para supporter, adalah bagian dari keluarga besar kami. Setiap pembelian, setiap feedback, sangat berarti bagi kami.</p>
                    
                    <blockquote>
                        "We are not just selling clothes, we are building a community." - Founder, The Paranoia
                    </blockquote>
                    
                    <p>Terima kasih sudah menjadi bagian dari perjalanan kami. Stay paranoid, stay stylish!</p>
                </div>',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(20),
            ],
            [
                'title' => 'Mix and Match: 5 Outfit Ideas dengan Produk The Paranoia',
                'content' => '<div>
                    <p>Bingung mau pakai apa? Berikut 5 outfit ideas yang bisa kamu coba dengan produk The Paranoia!</p>
                    
                    <h2>1. Casual Weekend Look</h2>
                    <p><strong>Items:</strong> Paranoia Classic Tee + Relaxed Chino Pants + Dad Cap</p>
                    <p>Perfect untuk hangout santai di weekend. Simpel tapi tetap keren.</p>
                    
                    <h2>2. Street Style Ready</h2>
                    <p><strong>Items:</strong> Oversized Graphic Tee + Cargo Pants + Sling Bag</p>
                    <p>Untuk kamu yang suka tampil bold dan eye-catching.</p>
                    
                    <h2>3. Layering Master</h2>
                    <p><strong>Items:</strong> Classic Tee + Essential Hoodie + Coach Jacket</p>
                    <p>Cocok untuk cuaca yang tidak menentu. Stylish dan fungsional.</p>
                    
                    <h2>4. Monochrome Mood</h2>
                    <p><strong>Items:</strong> All Black - Tee, Pants, Cap, Sling Bag</p>
                    <p>Tampil sleek dengan outfit serba hitam. Timeless dan always on point.</p>
                    
                    <h2>5. Sporty Casual</h2>
                    <p><strong>Items:</strong> Essential Hoodie + Board Shorts + Snapback</p>
                    <p>Nyaman untuk aktivitas outdoor atau sekadar jalan-jalan.</p>
                    
                    <p>Tag kami di Instagram kalau kamu mencoba salah satu look di atas! @theparanoia.id</p>
                </div>',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(8),
            ],
            [
                'title' => 'The Paranoia Loyalty Program: Cara Mendapatkan dan Menukar Poin',
                'content' => '<div>
                    <p>Kami menghargai setiap customer setia The Paranoia! Dengan Loyalty Program kami, setiap pembelian akan memberikanmu poin yang bisa ditukar dengan produk exclusive.</p>
                    
                    <h2>Cara Mendapatkan Poin</h2>
                    <ul>
                        <li><strong>Setiap pembelian:</strong> Dapatkan 1 poin untuk setiap Rp 10.000 yang kamu belanjakan</li>
                        <li><strong>Review produk:</strong> Bonus poin untuk review yang helpful</li>
                        <li><strong>Referral:</strong> Ajak teman bergabung dan dapatkan bonus poin</li>
                    </ul>
                    
                    <h2>Cara Menukar Poin</h2>
                    <ol>
                        <li>Login ke akun The Paranoia kamu</li>
                        <li>Kunjungi halaman Rewards</li>
                        <li>Pilih produk yang ingin ditukar</li>
                        <li>Konfirmasi penukaran</li>
                        <li>Produk akan dikirim ke alamatmu!</li>
                    </ol>
                    
                    <h2>Produk Exclusive Rewards</h2>
                    <p>Ada berbagai produk exclusive yang hanya bisa didapatkan dengan poin:</p>
                    <ul>
                        <li>Limited Edition Sticker Pack (50 poin)</li>
                        <li>Exclusive Enamel Pin (100 poin)</li>
                        <li>Member Only Tee (500 poin)</li>
                        <li>Dan masih banyak lagi!</li>
                    </ul>
                    
                    <p>Mulai kumpulkan poinmu sekarang dan dapatkan rewards exclusive!</p>
                </div>',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(3),
            ],
            [
                'title' => 'Coming Soon: The Paranoia Summer Collection 2025',
                'content' => '<div>
                    <p>Get ready! The Paranoia Summer Collection 2025 akan segera hadir dengan berbagai pieces baru yang fresh dan vibrant.</p>
                    
                    <h2>Sneak Peek</h2>
                    <p>Koleksi musim panas ini akan menampilkan:</p>
                    <ul>
                        <li>New colorways untuk produk favorit</li>
                        <li>Lightweight materials untuk cuaca panas</li>
                        <li>Fresh graphic designs</li>
                        <li>Collaborative pieces dengan artist lokal</li>
                    </ul>
                    
                    <h2>Release Date</h2>
                    <p>Mark your calendar! Drop date akan diumumkan melalui Instagram @theparanoia.id</p>
                    
                    <h2>Early Access</h2>
                    <p>Member dengan status Gold dan Platinum akan mendapatkan early access 24 jam sebelum public release. Pastikan poinmu sudah cukup untuk upgrade membership!</p>
                    
                    <blockquote>
                        "Summer vibes, street style. Get ready for something new." - The Paranoia
                    </blockquote>
                    
                    <p>Stay tuned untuk update selanjutnya!</p>
                </div>',
                'is_published' => false, // Draft article
                'published_at' => null,
            ],
        ];

        foreach ($articles as $articleData) {
            $slug = Str::slug($articleData['title']);
            
            Article::updateOrCreate(
                ['slug' => $slug],
                array_merge($articleData, ['slug' => $slug])
            );
        }

        $this->command->info('Successfully created ' . count($articles) . ' articles.');
    }
}
