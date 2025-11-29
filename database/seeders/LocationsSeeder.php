<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\City;

class LocationsSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Seeding provinces...');
        $this->seedProvinces();
        
        $this->command->info('Seeding cities...');
        $this->seedCities();
        
        $this->command->info('Location data seeded successfully!');
    }
    
    private function seedProvinces()
    {
        $provinces = [
            ['province_id' => 1, 'name' => 'Bali'],
            ['province_id' => 2, 'name' => 'Bangka Belitung'],
            ['province_id' => 3, 'name' => 'Banten'],
            ['province_id' => 4, 'name' => 'Bengkulu'],
            ['province_id' => 5, 'name' => 'DI Yogyakarta'],
            ['province_id' => 6, 'name' => 'DKI Jakarta'],
            ['province_id' => 7, 'name' => 'Gorontalo'],
            ['province_id' => 8, 'name' => 'Jambi'],
            ['province_id' => 9, 'name' => 'Jawa Barat'],
            ['province_id' => 10, 'name' => 'Jawa Tengah'],
            ['province_id' => 11, 'name' => 'Jawa Timur'],
            ['province_id' => 12, 'name' => 'Kalimantan Barat'],
            ['province_id' => 13, 'name' => 'Kalimantan Selatan'],
            ['province_id' => 14, 'name' => 'Kalimantan Tengah'],
            ['province_id' => 15, 'name' => 'Kalimantan Timur'],
            ['province_id' => 16, 'name' => 'Kalimantan Utara'],
            ['province_id' => 17, 'name' => 'Kepulauan Riau'],
            ['province_id' => 18, 'name' => 'Lampung'],
            ['province_id' => 19, 'name' => 'Maluku'],
            ['province_id' => 20, 'name' => 'Maluku Utara'],
            ['province_id' => 21, 'name' => 'Nanggroe Aceh Darussalam (NAD)'],
            ['province_id' => 22, 'name' => 'Nusa Tenggara Barat (NTB)'],
            ['province_id' => 23, 'name' => 'Nusa Tenggara Timur (NTT)'],
            ['province_id' => 24, 'name' => 'Papua'],
            ['province_id' => 25, 'name' => 'Papua Barat'],
            ['province_id' => 26, 'name' => 'Riau'],
            ['province_id' => 27, 'name' => 'Sulawesi Barat'],
            ['province_id' => 28, 'name' => 'Sulawesi Selatan'],
            ['province_id' => 29, 'name' => 'Sulawesi Tengah'],
            ['province_id' => 30, 'name' => 'Sulawesi Tenggara'],
            ['province_id' => 31, 'name' => 'Sulawesi Utara'],
            ['province_id' => 32, 'name' => 'Sumatera Barat'],
            ['province_id' => 33, 'name' => 'Sumatera Selatan'],
            ['province_id' => 34, 'name' => 'Sumatera Utara'],
        ];
        
        foreach ($provinces as $province) {
            Province::create($province);
        }
    }
    
    private function seedCities()
    {
        $cities = [
            // DKI Jakarta (Province ID: 6)
            ['province_id' => 6, 'city_id' => 151, 'name' => 'Jakarta Barat', 'type' => 'Kota', 'postal_code' => '11220'],
            ['province_id' => 6, 'city_id' => 152, 'name' => 'Jakarta Pusat', 'type' => 'Kota', 'postal_code' => '10540'],
            ['province_id' => 6, 'city_id' => 153, 'name' => 'Jakarta Selatan', 'type' => 'Kota', 'postal_code' => '12230'],
            ['province_id' => 6, 'city_id' => 154, 'name' => 'Jakarta Timur', 'type' => 'Kota', 'postal_code' => '13330'],
            ['province_id' => 6, 'city_id' => 155, 'name' => 'Jakarta Utara', 'type' => 'Kota', 'postal_code' => '14140'],
            ['province_id' => 6, 'city_id' => 156, 'name' => 'Kepulauan Seribu', 'type' => 'Kabupaten', 'postal_code' => '14550'],
            
            // Jawa Barat (Province ID: 9)
            ['province_id' => 9, 'city_id' => 22, 'name' => 'Bandung', 'type' => 'Kabupaten', 'postal_code' => '40311'],
            ['province_id' => 9, 'city_id' => 23, 'name' => 'Bandung', 'type' => 'Kota', 'postal_code' => '40111'],
            ['province_id' => 9, 'city_id' => 24, 'name' => 'Bandung Barat', 'type' => 'Kabupaten', 'postal_code' => '40721'],
            ['province_id' => 9, 'city_id' => 25, 'name' => 'Banjar', 'type' => 'Kota', 'postal_code' => '46311'],
            ['province_id' => 9, 'city_id' => 26, 'name' => 'Bekasi', 'type' => 'Kabupaten', 'postal_code' => '17837'],
            ['province_id' => 9, 'city_id' => 27, 'name' => 'Bekasi', 'type' => 'Kota', 'postal_code' => '17121'],
            ['province_id' => 9, 'city_id' => 28, 'name' => 'Bogor', 'type' => 'Kabupaten', 'postal_code' => '16911'],
            ['province_id' => 9, 'city_id' => 29, 'name' => 'Bogor', 'type' => 'Kota', 'postal_code' => '16119'],
            ['province_id' => 9, 'city_id' => 30, 'name' => 'Ciamis', 'type' => 'Kabupaten', 'postal_code' => '46211'],
            ['province_id' => 9, 'city_id' => 31, 'name' => 'Cianjur', 'type' => 'Kabupaten', 'postal_code' => '43217'],
            ['province_id' => 9, 'city_id' => 32, 'name' => 'Cimahi', 'type' => 'Kota', 'postal_code' => '40512'],
            ['province_id' => 9, 'city_id' => 33, 'name' => 'Cirebon', 'type' => 'Kabupaten', 'postal_code' => '45611'],
            ['province_id' => 9, 'city_id' => 34, 'name' => 'Cirebon', 'type' => 'Kota', 'postal_code' => '45116'],
            ['province_id' => 9, 'city_id' => 35, 'name' => 'Depok', 'type' => 'Kota', 'postal_code' => '16416'],
            ['province_id' => 9, 'city_id' => 36, 'name' => 'Garut', 'type' => 'Kabupaten', 'postal_code' => '44126'],
            ['province_id' => 9, 'city_id' => 37, 'name' => 'Indramayu', 'type' => 'Kabupaten', 'postal_code' => '45214'],
            ['province_id' => 9, 'city_id' => 38, 'name' => 'Karawang', 'type' => 'Kabupaten', 'postal_code' => '41311'],
            ['province_id' => 9, 'city_id' => 39, 'name' => 'Kuningan', 'type' => 'Kabupaten', 'postal_code' => '45511'],
            ['province_id' => 9, 'city_id' => 40, 'name' => 'Majalengka', 'type' => 'Kabupaten', 'postal_code' => '45412'],
            ['province_id' => 9, 'city_id' => 41, 'name' => 'Pangandaran', 'type' => 'Kabupaten', 'postal_code' => '46511'],
            ['province_id' => 9, 'city_id' => 42, 'name' => 'Purwakarta', 'type' => 'Kabupaten', 'postal_code' => '41119'],
            ['province_id' => 9, 'city_id' => 43, 'name' => 'Subang', 'type' => 'Kabupaten', 'postal_code' => '41211'],
            ['province_id' => 9, 'city_id' => 44, 'name' => 'Sukabumi', 'type' => 'Kabupaten', 'postal_code' => '43311'],
            ['province_id' => 9, 'city_id' => 45, 'name' => 'Sukabumi', 'type' => 'Kota', 'postal_code' => '43114'],
            ['province_id' => 9, 'city_id' => 46, 'name' => 'Sumedang', 'type' => 'Kabupaten', 'postal_code' => '45326'],
            ['province_id' => 9, 'city_id' => 47, 'name' => 'Tasikmalaya', 'type' => 'Kabupaten', 'postal_code' => '46411'],
            ['province_id' => 9, 'city_id' => 48, 'name' => 'Tasikmalaya', 'type' => 'Kota', 'postal_code' => '46116'],
            
            // Jawa Tengah (Province ID: 10)
            ['province_id' => 10, 'city_id' => 49, 'name' => 'Banjarnegara', 'type' => 'Kabupaten', 'postal_code' => '53419'],
            ['province_id' => 10, 'city_id' => 50, 'name' => 'Banyumas', 'type' => 'Kabupaten', 'postal_code' => '53114'],
            ['province_id' => 10, 'city_id' => 51, 'name' => 'Batang', 'type' => 'Kabupaten', 'postal_code' => '51211'],
            ['province_id' => 10, 'city_id' => 52, 'name' => 'Blora', 'type' => 'Kabupaten', 'postal_code' => '58219'],
            ['province_id' => 10, 'city_id' => 53, 'name' => 'Boyolali', 'type' => 'Kabupaten', 'postal_code' => '57312'],
            ['province_id' => 10, 'city_id' => 54, 'name' => 'Brebes', 'type' => 'Kabupaten', 'postal_code' => '52212'],
            ['province_id' => 10, 'city_id' => 55, 'name' => 'Cilacap', 'type' => 'Kabupaten', 'postal_code' => '53211'],
            ['province_id' => 10, 'city_id' => 56, 'name' => 'Demak', 'type' => 'Kabupaten', 'postal_code' => '59511'],
            ['province_id' => 10, 'city_id' => 57, 'name' => 'Grobogan', 'type' => 'Kabupaten', 'postal_code' => '58111'],
            ['province_id' => 10, 'city_id' => 58, 'name' => 'Jepara', 'type' => 'Kabupaten', 'postal_code' => '59419'],
            ['province_id' => 10, 'city_id' => 59, 'name' => 'Karanganyar', 'type' => 'Kabupaten', 'postal_code' => '57718'],
            ['province_id' => 10, 'city_id' => 60, 'name' => 'Kebumen', 'type' => 'Kabupaten', 'postal_code' => '54319'],
            ['province_id' => 10, 'city_id' => 61, 'name' => 'Kendal', 'type' => 'Kabupaten', 'postal_code' => '51314'],
            ['province_id' => 10, 'city_id' => 62, 'name' => 'Klaten', 'type' => 'Kabupaten', 'postal_code' => '57411'],
            ['province_id' => 10, 'city_id' => 63, 'name' => 'Kudus', 'type' => 'Kabupaten', 'postal_code' => '59311'],
            ['province_id' => 10, 'city_id' => 64, 'name' => 'Magelang', 'type' => 'Kabupaten', 'postal_code' => '56519'],
            ['province_id' => 10, 'city_id' => 65, 'name' => 'Magelang', 'type' => 'Kota', 'postal_code' => '56133'],
            ['province_id' => 10, 'city_id' => 66, 'name' => 'Pati', 'type' => 'Kabupaten', 'postal_code' => '59114'],
            ['province_id' => 10, 'city_id' => 67, 'name' => 'Pekalongan', 'type' => 'Kabupaten', 'postal_code' => '51161'],
            ['province_id' => 10, 'city_id' => 68, 'name' => 'Pekalongan', 'type' => 'Kota', 'postal_code' => '51122'],
            ['province_id' => 10, 'city_id' => 69, 'name' => 'Pemalang', 'type' => 'Kabupaten', 'postal_code' => '52319'],
            ['province_id' => 10, 'city_id' => 70, 'name' => 'Purbalingga', 'type' => 'Kabupaten', 'postal_code' => '53317'],
            ['province_id' => 10, 'city_id' => 71, 'name' => 'Purworejo', 'type' => 'Kabupaten', 'postal_code' => '54111'],
            ['province_id' => 10, 'city_id' => 72, 'name' => 'Rembang', 'type' => 'Kabupaten', 'postal_code' => '59219'],
            ['province_id' => 10, 'city_id' => 73, 'name' => 'Salatiga', 'type' => 'Kota', 'postal_code' => '50711'],
            ['province_id' => 10, 'city_id' => 74, 'name' => 'Semarang', 'type' => 'Kabupaten', 'postal_code' => '50511'],
            ['province_id' => 10, 'city_id' => 75, 'name' => 'Semarang', 'type' => 'Kota', 'postal_code' => '50135'],
            ['province_id' => 10, 'city_id' => 76, 'name' => 'Sragen', 'type' => 'Kabupaten', 'postal_code' => '57211'],
            ['province_id' => 10, 'city_id' => 77, 'name' => 'Sukoharjo', 'type' => 'Kabupaten', 'postal_code' => '57514'],
            ['province_id' => 10, 'city_id' => 78, 'name' => 'Surakarta (Solo)', 'type' => 'Kota', 'postal_code' => '57113'],
            ['province_id' => 10, 'city_id' => 79, 'name' => 'Tegal', 'type' => 'Kabupaten', 'postal_code' => '52419'],
            ['province_id' => 10, 'city_id' => 80, 'name' => 'Tegal', 'type' => 'Kota', 'postal_code' => '52114'],
            ['province_id' => 10, 'city_id' => 81, 'name' => 'Temanggung', 'type' => 'Kabupaten', 'postal_code' => '56212'],
            ['province_id' => 10, 'city_id' => 82, 'name' => 'Wonogiri', 'type' => 'Kabupaten', 'postal_code' => '57619'],
            ['province_id' => 10, 'city_id' => 83, 'name' => 'Wonosobo', 'type' => 'Kabupaten', 'postal_code' => '56311'],
            
            // Jawa Timur (Province ID: 11)
            ['province_id' => 11, 'city_id' => 84, 'name' => 'Bangkalan', 'type' => 'Kabupaten', 'postal_code' => '69118'],
            ['province_id' => 11, 'city_id' => 85, 'name' => 'Banyuwangi', 'type' => 'Kabupaten', 'postal_code' => '68416'],
            ['province_id' => 11, 'city_id' => 86, 'name' => 'Batu', 'type' => 'Kota', 'postal_code' => '65311'],
            ['province_id' => 11, 'city_id' => 87, 'name' => 'Blitar', 'type' => 'Kabupaten', 'postal_code' => '66171'],
            ['province_id' => 11, 'city_id' => 88, 'name' => 'Blitar', 'type' => 'Kota', 'postal_code' => '66124'],
            ['province_id' => 11, 'city_id' => 89, 'name' => 'Bojonegoro', 'type' => 'Kabupaten', 'postal_code' => '62119'],
            ['province_id' => 11, 'city_id' => 90, 'name' => 'Bondowoso', 'type' => 'Kabupaten', 'postal_code' => '68219'],
            ['province_id' => 11, 'city_id' => 91, 'name' => 'Gresik', 'type' => 'Kabupaten', 'postal_code' => '61115'],
            ['province_id' => 11, 'city_id' => 92, 'name' => 'Jember', 'type' => 'Kabupaten', 'postal_code' => '68113'],
            ['province_id' => 11, 'city_id' => 93, 'name' => 'Jombang', 'type' => 'Kabupaten', 'postal_code' => '61415'],
            ['province_id' => 11, 'city_id' => 94, 'name' => 'Kediri', 'type' => 'Kabupaten', 'postal_code' => '64184'],
            ['province_id' => 11, 'city_id' => 95, 'name' => 'Kediri', 'type' => 'Kota', 'postal_code' => '64125'],
            ['province_id' => 11, 'city_id' => 96, 'name' => 'Lamongan', 'type' => 'Kabupaten', 'postal_code' => '62212'],
            ['province_id' => 11, 'city_id' => 97, 'name' => 'Lumajang', 'type' => 'Kabupaten', 'postal_code' => '67319'],
            ['province_id' => 11, 'city_id' => 98, 'name' => 'Madiun', 'type' => 'Kabupaten', 'postal_code' => '63153'],
            ['province_id' => 11, 'city_id' => 99, 'name' => 'Madiun', 'type' => 'Kota', 'postal_code' => '63122'],
            ['province_id' => 11, 'city_id' => 100, 'name' => 'Magetan', 'type' => 'Kabupaten', 'postal_code' => '63314'],
            ['province_id' => 11, 'city_id' => 101, 'name' => 'Malang', 'type' => 'Kabupaten', 'postal_code' => '65163'],
            ['province_id' => 11, 'city_id' => 102, 'name' => 'Malang', 'type' => 'Kota', 'postal_code' => '65112'],
            ['province_id' => 11, 'city_id' => 103, 'name' => 'Mojokerto', 'type' => 'Kabupaten', 'postal_code' => '61382'],
            ['province_id' => 11, 'city_id' => 104, 'name' => 'Mojokerto', 'type' => 'Kota', 'postal_code' => '61316'],
            ['province_id' => 11, 'city_id' => 105, 'name' => 'Nganjuk', 'type' => 'Kabupaten', 'postal_code' => '64414'],
            ['province_id' => 11, 'city_id' => 106, 'name' => 'Ngawi', 'type' => 'Kabupaten', 'postal_code' => '63219'],
            ['province_id' => 11, 'city_id' => 107, 'name' => 'Pacitan', 'type' => 'Kabupaten', 'postal_code' => '63512'],
            ['province_id' => 11, 'city_id' => 108, 'name' => 'Pamekasan', 'type' => 'Kabupaten', 'postal_code' => '69319'],
            ['province_id' => 11, 'city_id' => 109, 'name' => 'Pasuruan', 'type' => 'Kabupaten', 'postal_code' => '67153'],
            ['province_id' => 11, 'city_id' => 110, 'name' => 'Pasuruan', 'type' => 'Kota', 'postal_code' => '67118'],
            ['province_id' => 11, 'city_id' => 111, 'name' => 'Ponorogo', 'type' => 'Kabupaten', 'postal_code' => '63411'],
            ['province_id' => 11, 'city_id' => 112, 'name' => 'Probolinggo', 'type' => 'Kabupaten', 'postal_code' => '67282'],
            ['province_id' => 11, 'city_id' => 113, 'name' => 'Probolinggo', 'type' => 'Kota', 'postal_code' => '67215'],
            ['province_id' => 11, 'city_id' => 114, 'name' => 'Sampang', 'type' => 'Kabupaten', 'postal_code' => '69219'],
            ['province_id' => 11, 'city_id' => 115, 'name' => 'Sidoarjo', 'type' => 'Kabupaten', 'postal_code' => '61219'],
            ['province_id' => 11, 'city_id' => 116, 'name' => 'Situbondo', 'type' => 'Kabupaten', 'postal_code' => '68316'],
            ['province_id' => 11, 'city_id' => 117, 'name' => 'Sumenep', 'type' => 'Kabupaten', 'postal_code' => '69413'],
            ['province_id' => 11, 'city_id' => 118, 'name' => 'Surabaya', 'type' => 'Kota', 'postal_code' => '60119'],
            ['province_id' => 11, 'city_id' => 119, 'name' => 'Trenggalek', 'type' => 'Kabupaten', 'postal_code' => '66312'],
            ['province_id' => 11, 'city_id' => 120, 'name' => 'Tuban', 'type' => 'Kabupaten', 'postal_code' => '62381'],
            ['province_id' => 11, 'city_id' => 121, 'name' => 'Tulungagung', 'type' => 'Kabupaten', 'postal_code' => '66212'],
            
            // DI Yogyakarta (Province ID: 5)
            ['province_id' => 5, 'city_id' => 419, 'name' => 'Bantul', 'type' => 'Kabupaten', 'postal_code' => '55511'],
            ['province_id' => 5, 'city_id' => 420, 'name' => 'Gunung Kidul', 'type' => 'Kabupaten', 'postal_code' => '55812'],
            ['province_id' => 5, 'city_id' => 421, 'name' => 'Kulon Progo', 'type' => 'Kabupaten', 'postal_code' => '55611'],
            ['province_id' => 5, 'city_id' => 422, 'name' => 'Sleman', 'type' => 'Kabupaten', 'postal_code' => '55513'],
            ['province_id' => 5, 'city_id' => 423, 'name' => 'Yogyakarta', 'type' => 'Kota', 'postal_code' => '55111'],
            
            // Banten (Province ID: 3)
            ['province_id' => 3, 'city_id' => 122, 'name' => 'Cilegon', 'type' => 'Kota', 'postal_code' => '42417'],
            ['province_id' => 3, 'city_id' => 123, 'name' => 'Lebak', 'type' => 'Kabupaten', 'postal_code' => '42319'],
            ['province_id' => 3, 'city_id' => 124, 'name' => 'Pandeglang', 'type' => 'Kabupaten', 'postal_code' => '42212'],
            ['province_id' => 3, 'city_id' => 125, 'name' => 'Serang', 'type' => 'Kabupaten', 'postal_code' => '42182'],
            ['province_id' => 3, 'city_id' => 126, 'name' => 'Serang', 'type' => 'Kota', 'postal_code' => '42111'],
            ['province_id' => 3, 'city_id' => 127, 'name' => 'Tangerang', 'type' => 'Kabupaten', 'postal_code' => '15914'],
            ['province_id' => 3, 'city_id' => 128, 'name' => 'Tangerang', 'type' => 'Kota', 'postal_code' => '15111'],
            ['province_id' => 3, 'city_id' => 129, 'name' => 'Tangerang Selatan', 'type' => 'Kota', 'postal_code' => '15332'],
            
            // Bali (Province ID: 1)
            ['province_id' => 1, 'city_id' => 1, 'name' => 'Badung', 'type' => 'Kabupaten', 'postal_code' => '80351'],
            ['province_id' => 1, 'city_id' => 2, 'name' => 'Bangli', 'type' => 'Kabupaten', 'postal_code' => '80619'],
            ['province_id' => 1, 'city_id' => 3, 'name' => 'Buleleng', 'type' => 'Kabupaten', 'postal_code' => '81111'],
            ['province_id' => 1, 'city_id' => 4, 'name' => 'Denpasar', 'type' => 'Kota', 'postal_code' => '80114'],
            ['province_id' => 1, 'city_id' => 5, 'name' => 'Gianyar', 'type' => 'Kabupaten', 'postal_code' => '80511'],
            ['province_id' => 1, 'city_id' => 6, 'name' => 'Jembrana', 'type' => 'Kabupaten', 'postal_code' => '82251'],
            ['province_id' => 1, 'city_id' => 7, 'name' => 'Karangasem', 'type' => 'Kabupaten', 'postal_code' => '80811'],
            ['province_id' => 1, 'city_id' => 8, 'name' => 'Klungkung', 'type' => 'Kabupaten', 'postal_code' => '80719'],
            ['province_id' => 1, 'city_id' => 9, 'name' => 'Tabanan', 'type' => 'Kabupaten', 'postal_code' => '82119'],
            
            // Sumatera Utara (Province ID: 34)
            ['province_id' => 34, 'city_id' => 130, 'name' => 'Asahan', 'type' => 'Kabupaten', 'postal_code' => '21214'],
            ['province_id' => 34, 'city_id' => 131, 'name' => 'Binjai', 'type' => 'Kota', 'postal_code' => '20712'],
            ['province_id' => 34, 'city_id' => 132, 'name' => 'Dairi', 'type' => 'Kabupaten', 'postal_code' => '22211'],
            ['province_id' => 34, 'city_id' => 133, 'name' => 'Deli Serdang', 'type' => 'Kabupaten', 'postal_code' => '20511'],
            ['province_id' => 34, 'city_id' => 134, 'name' => 'Gunungsitoli', 'type' => 'Kota', 'postal_code' => '22813'],
            ['province_id' => 34, 'city_id' => 135, 'name' => 'Humbang Hasundutan', 'type' => 'Kabupaten', 'postal_code' => '22457'],
            ['province_id' => 34, 'city_id' => 136, 'name' => 'Karo', 'type' => 'Kabupaten', 'postal_code' => '22119'],
            ['province_id' => 34, 'city_id' => 137, 'name' => 'Labuhan Batu', 'type' => 'Kabupaten', 'postal_code' => '21412'],
            ['province_id' => 34, 'city_id' => 138, 'name' => 'Labuhan Batu Selatan', 'type' => 'Kabupaten', 'postal_code' => '21511'],
            ['province_id' => 34, 'city_id' => 139, 'name' => 'Labuhan Batu Utara', 'type' => 'Kabupaten', 'postal_code' => '21711'],
            ['province_id' => 34, 'city_id' => 140, 'name' => 'Langkat', 'type' => 'Kabupaten', 'postal_code' => '20811'],
            ['province_id' => 34, 'city_id' => 141, 'name' => 'Mandailing Natal', 'type' => 'Kabupaten', 'postal_code' => '22916'],
            ['province_id' => 34, 'city_id' => 142, 'name' => 'Medan', 'type' => 'Kota', 'postal_code' => '20228'],
            ['province_id' => 34, 'city_id' => 143, 'name' => 'Nias', 'type' => 'Kabupaten', 'postal_code' => '22876'],
            ['province_id' => 34, 'city_id' => 144, 'name' => 'Nias Barat', 'type' => 'Kabupaten', 'postal_code' => '22895'],
            ['province_id' => 34, 'city_id' => 145, 'name' => 'Nias Selatan', 'type' => 'Kabupaten', 'postal_code' => '22865'],
            ['province_id' => 34, 'city_id' => 146, 'name' => 'Nias Utara', 'type' => 'Kabupaten', 'postal_code' => '22856'],
            ['province_id' => 34, 'city_id' => 147, 'name' => 'Padang Lawas', 'type' => 'Kabupaten', 'postal_code' => '22763'],
            ['province_id' => 34, 'city_id' => 148, 'name' => 'Padang Lawas Utara', 'type' => 'Kabupaten', 'postal_code' => '22753'],
            ['province_id' => 34, 'city_id' => 149, 'name' => 'Padang Sidempuan', 'type' => 'Kota', 'postal_code' => '22727'],
            ['province_id' => 34, 'city_id' => 150, 'name' => 'Pakpak Bharat', 'type' => 'Kabupaten', 'postal_code' => '22272'],
            
            // Sumatera Barat (Province ID: 32)
            ['province_id' => 32, 'city_id' => 318, 'name' => 'Agam', 'type' => 'Kabupaten', 'postal_code' => '26411'],
            ['province_id' => 32, 'city_id' => 319, 'name' => 'Bukittinggi', 'type' => 'Kota', 'postal_code' => '26115'],
            ['province_id' => 32, 'city_id' => 320, 'name' => 'Dharmasraya', 'type' => 'Kabupaten', 'postal_code' => '27612'],
            ['province_id' => 32, 'city_id' => 321, 'name' => 'Kepulauan Mentawai', 'type' => 'Kabupaten', 'postal_code' => '25771'],
            ['province_id' => 32, 'city_id' => 322, 'name' => 'Lima Puluh Koto/Kota', 'type' => 'Kabupaten', 'postal_code' => '26671'],
            ['province_id' => 32, 'city_id' => 323, 'name' => 'Padang', 'type' => 'Kota', 'postal_code' => '25112'],
            ['province_id' => 32, 'city_id' => 324, 'name' => 'Padang Pariaman', 'type' => 'Kabupaten', 'postal_code' => '25583'],
            ['province_id' => 32, 'city_id' => 325, 'name' => 'Pariaman', 'type' => 'Kota', 'postal_code' => '25511'],
            ['province_id' => 32, 'city_id' => 326, 'name' => 'Pasaman', 'type' => 'Kabupaten', 'postal_code' => '26318'],
            ['province_id' => 32, 'city_id' => 327, 'name' => 'Pasaman Barat', 'type' => 'Kabupaten', 'postal_code' => '26511'],
            ['province_id' => 32, 'city_id' => 328, 'name' => 'Payakumbuh', 'type' => 'Kota', 'postal_code' => '26213'],
            ['province_id' => 32, 'city_id' => 329, 'name' => 'Pesisir Selatan', 'type' => 'Kabupaten', 'postal_code' => '25611'],
            ['province_id' => 32, 'city_id' => 330, 'name' => 'Sawah Lunto', 'type' => 'Kota', 'postal_code' => '27416'],
            ['province_id' => 32, 'city_id' => 331, 'name' => 'Sijunjung (Sawah Lunto Sijunjung)', 'type' => 'Kabupaten', 'postal_code' => '27511'],
            ['province_id' => 32, 'city_id' => 332, 'name' => 'Solok', 'type' => 'Kabupaten', 'postal_code' => '27365'],
            ['province_id' => 32, 'city_id' => 333, 'name' => 'Solok', 'type' => 'Kota', 'postal_code' => '27315'],
            ['province_id' => 32, 'city_id' => 334, 'name' => 'Solok Selatan', 'type' => 'Kabupaten', 'postal_code' => '27779'],
            ['province_id' => 32, 'city_id' => 335, 'name' => 'Tanah Datar', 'type' => 'Kabupaten', 'postal_code' => '27211'],
            
            // Kalimantan Timur (Province ID: 15)
            ['province_id' => 15, 'city_id' => 179, 'name' => 'Balikpapan', 'type' => 'Kota', 'postal_code' => '76111'],
            ['province_id' => 15, 'city_id' => 180, 'name' => 'Berau', 'type' => 'Kabupaten', 'postal_code' => '77311'],
            ['province_id' => 15, 'city_id' => 181, 'name' => 'Bontang', 'type' => 'Kota', 'postal_code' => '75313'],
            ['province_id' => 15, 'city_id' => 182, 'name' => 'Kutai Barat', 'type' => 'Kabupaten', 'postal_code' => '75711'],
            ['province_id' => 15, 'city_id' => 183, 'name' => 'Kutai Kartanegara', 'type' => 'Kabupaten', 'postal_code' => '75511'],
            ['province_id' => 15, 'city_id' => 184, 'name' => 'Kutai Timur', 'type' => 'Kabupaten', 'postal_code' => '75611'],
            ['province_id' => 15, 'city_id' => 185, 'name' => 'Paser', 'type' => 'Kabupaten', 'postal_code' => '76211'],
            ['province_id' => 15, 'city_id' => 186, 'name' => 'Penajam Paser Utara', 'type' => 'Kabupaten', 'postal_code' => '76311'],
            ['province_id' => 15, 'city_id' => 187, 'name' => 'Samarinda', 'type' => 'Kota', 'postal_code' => '75117'],
            
            // Sulawesi Selatan (Province ID: 28)
            ['province_id' => 28, 'city_id' => 282, 'name' => 'Bantaeng', 'type' => 'Kabupaten', 'postal_code' => '92411'],
            ['province_id' => 28, 'city_id' => 283, 'name' => 'Barru', 'type' => 'Kabupaten', 'postal_code' => '90719'],
            ['province_id' => 28, 'city_id' => 284, 'name' => 'Bone', 'type' => 'Kabupaten', 'postal_code' => '92713'],
            ['province_id' => 28, 'city_id' => 285, 'name' => 'Bulukumba', 'type' => 'Kabupaten', 'postal_code' => '92511'],
            ['province_id' => 28, 'city_id' => 286, 'name' => 'Enrekang', 'type' => 'Kabupaten', 'postal_code' => '91719'],
            ['province_id' => 28, 'city_id' => 287, 'name' => 'Gowa', 'type' => 'Kabupaten', 'postal_code' => '92111'],
            ['province_id' => 28, 'city_id' => 288, 'name' => 'Jeneponto', 'type' => 'Kabupaten', 'postal_code' => '92319'],
            ['province_id' => 28, 'city_id' => 289, 'name' => 'Luwu', 'type' => 'Kabupaten', 'postal_code' => '91994'],
            ['province_id' => 28, 'city_id' => 290, 'name' => 'Luwu Timur', 'type' => 'Kabupaten', 'postal_code' => '92981'],
            ['province_id' => 28, 'city_id' => 291, 'name' => 'Luwu Utara', 'type' => 'Kabupaten', 'postal_code' => '92911'],
            ['province_id' => 28, 'city_id' => 292, 'name' => 'Makassar', 'type' => 'Kota', 'postal_code' => '90111'],
            ['province_id' => 28, 'city_id' => 293, 'name' => 'Maros', 'type' => 'Kabupaten', 'postal_code' => '90511'],
            ['province_id' => 28, 'city_id' => 294, 'name' => 'Palopo', 'type' => 'Kota', 'postal_code' => '91911'],
            ['province_id' => 28, 'city_id' => 295, 'name' => 'Pangkajene Kepulauan', 'type' => 'Kabupaten', 'postal_code' => '90611'],
            ['province_id' => 28, 'city_id' => 296, 'name' => 'Parepare', 'type' => 'Kota', 'postal_code' => '91123'],
            ['province_id' => 28, 'city_id' => 297, 'name' => 'Pinrang', 'type' => 'Kabupaten', 'postal_code' => '91251'],
            ['province_id' => 28, 'city_id' => 298, 'name' => 'Selayar (Kepulauan Selayar)', 'type' => 'Kabupaten', 'postal_code' => '92812'],
            ['province_id' => 28, 'city_id' => 299, 'name' => 'Sidenreng Rappang/Rapang', 'type' => 'Kabupaten', 'postal_code' => '91613'],
            ['province_id' => 28, 'city_id' => 300, 'name' => 'Sinjai', 'type' => 'Kabupaten', 'postal_code' => '92615'],
            ['province_id' => 28, 'city_id' => 301, 'name' => 'Soppeng', 'type' => 'Kabupaten', 'postal_code' => '90812'],
            ['province_id' => 28, 'city_id' => 302, 'name' => 'Takalar', 'type' => 'Kabupaten', 'postal_code' => '92212'],
            ['province_id' => 28, 'city_id' => 303, 'name' => 'Tana Toraja', 'type' => 'Kabupaten', 'postal_code' => '91819'],
            ['province_id' => 28, 'city_id' => 304, 'name' => 'Toraja Utara', 'type' => 'Kabupaten', 'postal_code' => '91831'],
            ['province_id' => 28, 'city_id' => 305, 'name' => 'Wajo', 'type' => 'Kabupaten', 'postal_code' => '90911'],
        ];
        
        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
