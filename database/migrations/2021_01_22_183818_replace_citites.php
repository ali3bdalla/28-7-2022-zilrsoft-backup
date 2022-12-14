<?php

use App\Models\City;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReplaceCitites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $cities = [
            ['name' => 'Dammam', 'ar_name' => 'الدمام'],
            ['name' => 'Dhahran', 'ar_name' => 'الظهران'],
            ['name' => 'Jeddah', 'ar_name' => 'جدة'],
            ['name' => 'Jouf', 'ar_name' => 'الجوف'],
            ['name' => 'Khamis Mushayt', 'ar_name' => 'خميس مشيط'],
            ['name' => 'Khayber', 'ar_name' => 'خيبر'],
            ['name' => 'Madinah', 'ar_name' => 'المدينة'],
            ['name' => 'Riyadh', 'ar_name' => 'الرياض'],
            ['name' => 'Tabuk', 'ar_name' => 'تبوك'],
            ['name' => 'Yanbu', 'ar_name' => 'ينبع'],
            ['name' => 'Khubar', 'ar_name' => 'الخبر'],
            ['name' => 'Makkah', 'ar_name' => 'مكة المكرمة'],
            ['name' => 'Buraydah', 'ar_name' => 'بريدة'],
            ['name' => 'Unayzah', 'ar_name' => 'عنيزة'],
            ['name' => 'Hail', 'ar_name' => 'حائل'],
            ['name' => 'Abha', 'ar_name' => 'ابها'],
            ['name' => 'Hufuf', 'ar_name' => 'الهفوف'],
            ['name' => 'Jubail', 'ar_name' => 'الجبيل'],
            ['name' => 'Qatif', 'ar_name' => 'القطيف'],
            ['name' => 'Khafji', 'ar_name' => 'الخفجي'],
            ['name' => 'Ras Tannurah', 'ar_name' => 'رأس تنورة'],
            ['name' => 'Buqaiq', 'ar_name' => 'بقيق'],
            ['name' => 'Sayhat', 'ar_name' => 'سيهات'],
            ['name' => 'Safwa', 'ar_name' => 'صفوى'],
            ['name' => 'Jazan', 'ar_name' => 'جازان'],
            ['name' => 'Sabya', 'ar_name' => 'صبيا'],
            ['name' => 'Abu Arish', 'ar_name' => 'ابو عريش'],
            ['name' => 'Hafar Al Baten', 'ar_name' => 'حفر الباطن'],
            ['name' => 'Rabigh', 'ar_name' => 'رابغ'],
            ['name' => 'Lith', 'ar_name' => 'الليث'],
            ['name' => 'Ula', 'ar_name' => 'العلا'],
            ['name' => 'Duwadimi', 'ar_name' => 'الدوادمي'],
            ['name' => 'Majmaah', 'ar_name' => 'المجمعة'],
            ['name' => 'Zulfi', 'ar_name' => 'الزلفي'],
            ['name' => 'Afif', 'ar_name' => 'عفيف'],
            ['name' => 'Arar', 'ar_name' => 'عرعر'],
            ['name' => 'Kharj', 'ar_name' => 'الخرج'],
            ['name' => 'Muzahmiyah', 'ar_name' => 'المزاحمية'],
            ['name' => 'Ranyah', 'ar_name' => 'رنية'],
            ['name' => 'Turbah', 'ar_name' => 'تربة'],
            ['name' => 'Taima', 'ar_name' => 'تيماء'],
            ['name' => 'Dhuba', 'ar_name' => 'ضبا'],
            ['name' => 'Qurayyat', 'ar_name' => 'القريات'],
            ['name' => 'Turayf', 'ar_name' => 'طريف'],
            ['name' => 'Wadi Dawasir', 'ar_name' => 'وادي الدواسر'],
            ['name' => 'Quwayiyah', 'ar_name' => 'القويعية'],
            ['name' => 'Muhayil', 'ar_name' => 'محايل عسير'],
            ['name' => 'Salwa', 'ar_name' => 'سلوى'],
            ['name' => 'Baha', 'ar_name' => 'الباحة'],
            ['name' => 'Baljurashi', 'ar_name' => 'بلجرشي'],
            ['name' => 'Qunfudhah', 'ar_name' => 'القنفذة'],
            ['name' => 'Mukhwah', 'ar_name' => 'المخواة'],
            ['name' => 'Mandaq', 'ar_name' => 'المندق'],
            ['name' => 'Qilwah', 'ar_name' => 'قلوة'],
            ['name' => 'Atawlah', 'ar_name' => 'الاطاولة'],
            ['name' => 'Aqiq', 'ar_name' => 'العقيق'],
            ['name' => 'Mudhaylif', 'ar_name' => 'المظيلف'],
            ['name' => 'Nairiyah', 'ar_name' => 'النعيرية'],
            ['name' => 'Qarya Al Uliya', 'ar_name' => 'قرية العليا'],
            ['name' => 'Tarut', 'ar_name' => 'تاروت'],
            ['name' => 'Anak', 'ar_name' => 'عنك'],
            ['name' => 'Udhayliyah', 'ar_name' => 'العضيلية'],
            ['name' => 'Najran', 'ar_name' => 'نجران'],
            ['name' => 'Sharourah', 'ar_name' => 'شرورة'],
            ['name' => 'Habounah', 'ar_name' => 'حبونا'],
            ['name' => 'Samtah', 'ar_name' => 'صامطة'],
            ['name' => 'Ahad Al Masarhah', 'ar_name' => 'احد المسارحة'],
            ['name' => 'Baysh', 'ar_name' => 'بيش'],
            ['name' => 'Darb', 'ar_name' => 'الدرب'],
            ['name' => 'Dhamad', 'ar_name' => 'ضمد'],
            ['name' => 'Bani Malek', 'ar_name' => 'بني مالك'],
            ['name' => 'Furasan', 'ar_name' => 'فرسان'],
            ['name' => 'Tuwal', 'ar_name' => 'الطوال'],
            ['name' => 'Shuqayq', 'ar_name' => 'الشقيق'],
            ['name' => 'Badr', 'ar_name' => 'بدر'],
            ['name' => 'Jamoum', 'ar_name' => 'الجموم'],
            ['name' => 'Khulais', 'ar_name' => 'خليص'],
            ['name' => 'Bahrah', 'ar_name' => 'بحرة'],
            ['name' => 'Masturah', 'ar_name' => 'مستورة'],
            ['name' => 'Shaibah', 'ar_name' => 'شيبة'],
            ['name' => 'Rass', 'ar_name' => 'الرس'],
            ['name' => 'Bukayriyah', 'ar_name' => 'البكيرية'],
            ['name' => 'Badaya', 'ar_name' => 'البدايع'],
            ['name' => 'Riyadh Al Khabra', 'ar_name' => 'رياض الخبراء'],
            ['name' => 'Uyun Al Jiwa', 'ar_name' => 'عيون الجواء'],
            ['name' => 'Sajir', 'ar_name' => 'ساجر'],
            ['name' => 'Khabra', 'ar_name' => 'الخبراء'],
            ['name' => 'Uqlat As Suqur', 'ar_name' => 'عقلة الصقور'],
            ['name' => 'Rafayaa Al Gimsh', 'ar_name' => 'رفائع الجمش'],
            ['name' => 'Nabhaniah', 'ar_name' => 'النبهانية'],
            ['name' => 'Dukhnah', 'ar_name' => 'دخنة'],
            ['name' => 'Nifi', 'ar_name' => 'نفي'],
            ['name' => 'Skakah', 'ar_name' => 'سكاكا'],
            ['name' => 'Dawmat Al Jandal', 'ar_name' => 'دومة الجندل'],
            ['name' => 'Namas', 'ar_name' => 'النماص'],
            ['name' => 'Bellasmar', 'ar_name' => 'بللسمر'],
            ['name' => 'Tanumah', 'ar_name' => 'تنومة'],
            ['name' => 'Bashayer', 'ar_name' => 'البشاير'],
            ['name' => 'Jadidah', 'ar_name' => 'جديدة عرعر'],
            ['name' => 'Rafha', 'ar_name' => 'رفحاء'],
            ['name' => 'Qaysumah', 'ar_name' => 'القيصومة'],
            ['name' => 'Baqaa', 'ar_name' => 'بقعاء'],
            ['name' => 'Shinan', 'ar_name' => 'الشنان'],
            ['name' => 'Muqiq', 'ar_name' => 'موقق'],
            ['name' => 'Shamli', 'ar_name' => 'الشملي'],
            ['name' => 'Bishah', 'ar_name' => 'بيشة'],
            ['name' => 'Dhahran Al Janoub', 'ar_name' => 'ظهران الجنوب'],
            ['name' => 'Taberjal', 'ar_name' => 'طبرجل'],
            ['name' => 'Sabt Alalayah', 'ar_name' => 'سبت العلايا'],
            ['name' => 'Rijal Alma', 'ar_name' => 'رجال المع'],
            ['name' => 'Tathleeth', 'ar_name' => 'تثليث'],
            ['name' => 'Bareq', 'ar_name' => 'بارق'],
            ['name' => 'Majardah', 'ar_name' => 'المجاردة'],
            ['name' => 'Ahad Rafidah', 'ar_name' => 'احد رفيدة'],
            ['name' => 'Al Hasa', 'ar_name' => 'الاحساء'],
            ['name' => 'Shaqra', 'ar_name' => 'شقراء'],
            ['name' => 'Aflaj', 'ar_name' => 'الافلاج'],
            ['name' => 'Al Qouz', 'ar_name' => 'القوز'],
            ['name' => 'Ummlujj', 'ar_name' => 'املج'],
            ['name' => 'Wajh', 'ar_name' => 'الوجه'],
            ['name' => 'Midhnab', 'ar_name' => 'المذنب'],
            ['name' => 'Artawiyah', 'ar_name' => 'الارطاوية'],
            ['name' => 'Hawtat Sudayr ', 'ar_name' => 'حوطة سدير'],
            ['name' => 'Ghat', 'ar_name' => 'الغاط'],
            ['name' => 'Mubarraz', 'ar_name' => 'المبرز'],
            ['name' => 'Thuwal', 'ar_name' => 'ثول'],
            ['name' => 'Dhalim', 'ar_name' => 'ظلم'],
            ['name' => 'Khurmah', 'ar_name' => 'الخرمة'],
            ['name' => 'Haql', 'ar_name' => 'حقل'],
            ['name' => 'Dhurma', 'ar_name' => 'ضرما'],
            ['name' => 'Rumah', 'ar_name' => 'رماح'],
            ['name' => 'Huraymila', 'ar_name' => 'حريملاء'],
            ['name' => 'Bani Malik (Addayer)', 'ar_name' => 'الداير'],
            ['name' => 'Batha', 'ar_name' => 'البطحاء'],
            ['name' => 'Hawtat Bani Tamim', 'ar_name' => 'حوطة بني تميم'],
            ['name' => 'Tareeb', 'ar_name' => 'طريب'],
            ['name' => 'Namerah', 'ar_name' => 'نمرة'],
            ['name' => 'Alardhah', 'ar_name' => 'العارضة'],
            ['name' => 'Sulayyil', 'ar_name' => 'السليل'],
            ['name' => 'Sarat Abida', 'ar_name' => 'سراة عبيده'],
            ['name' => 'Oyun', 'ar_name' => 'عيون'],
            ['name' => 'Adham', 'ar_name' => 'اضم'],
            ['name' => 'Muwayh', 'ar_name' => 'المويه'],
            ['name' => 'Tumair', 'ar_name' => 'تمير'],
            ['name' => 'Hanakiyah', 'ar_name' => 'الحناكية'],
            ['name' => 'Edabi', 'ar_name' => 'العيدابي'],
            ['name' => 'Marat', 'ar_name' => 'مرات'],
            ['name' => 'Aflaj Layla', 'ar_name' => 'أفلاج ليلى'],
            ['name' => 'Dilam', 'ar_name' => 'الدلم'],
            ['name' => 'Hurayyiq', 'ar_name' => 'الحريق'],
            ['name' => 'Haradh', 'ar_name' => 'حرض'],
            ['name' => 'Bijadiyah', 'ar_name' => 'البجادية'],
            ['name' => 'Ras AlKhair', 'ar_name' => 'رأس الخير'],
            ['name' => 'Tanajib', 'ar_name' => 'تناجيب'],
            ['name' => 'Sarrar', 'ar_name' => 'الصرار'],
            ['name' => 'Saffaniyah', 'ar_name' => 'السفانية'],
            ['name' => 'Al Hait', 'ar_name' => 'الحائط'],
            ['name' => 'Umlej', 'ar_name' => 'أملج'],
            ['name' => 'Taif', 'ar_name' => 'الطائف'],
            ['name' => 'Buraidah', 'ar_name' => 'بريده']
        ];

        City::truncate();

        Schema::table(
            'cities', function(Blueprint $table) {
            $table->string('ar_name')->nullable();

        }
        );
        foreach ($cities as $city) {
            $city['country_id'] = 1;
            City::create($city);
        }
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
