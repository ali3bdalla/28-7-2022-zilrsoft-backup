<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


//       $rass = City::where('name', 'Rass')->first();
//       $shippingMethods = [
//        //    ['name' => 'Free Shipping', 'ar_name' => 'Free Shipping', 'logo' => '/images/shipping_methods/free_shipping.jpg', 'max_base_weight' => 0, 'max_base_weight_cost' => 10, 'kg_after_max_weight_cost' => 0, 'max_base_weight_price' => 0, 'kg_rate_after_max_price' => 0],
//        //    ['name' => 'SMSAEXPRESS', 'ar_name' => 'سمسا اكبريس', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/SMSA_Express_logo_%28English_version%29.svg/816px-SMSA_Express_logo_%28English_version%29.svg.png', 'max_base_weight' => 15, 'max_base_weight_cost' => 30, 'kg_after_max_weight_cost' => 2, 'max_base_weight_price' => 30, 'kg_rate_after_max_price' => 2],
//        //    ['name' => 'DHL', 'ar_name' => 'DHL', 'logo' => '/images/shipping_methods/dhl.png', 'max_base_weight' => 15, 'max_base_weight_cost' => 30, 'kg_after_max_weight_cost' => 2, 'max_base_weight_price' => 30, 'kg_rate_after_max_price' => 2],
//        //    ['name' => 'Naqel', 'ar_name' => 'Naqel', 'logo' => '/images/shipping_methods/naqel.jpg', 'max_base_weight' => 15, 'max_base_weight_cost' => 30, 'kg_after_max_weight_cost' => 2, 'max_base_weight_price' => 30, 'kg_rate_after_max_price' => 2],
//           ['name' => 'Store Pickup', 'ar_name' => 'استلام من المتجر', 'logo' => '/images/shipping_methods/naqel.jpg', 'max_base_weight' => 15, 'max_base_weight_cost' => 30, 'kg_after_max_weight_cost' => 2, 'max_base_weight_price' => 30, 'kg_rate_after_max_price' => 2],
//       ];
//    //    ShippingMethod::where('id', '!=', 0)->delete();
//       foreach ($shippingMethods as $key => $shippingMethod) {
//           $createdShipping = ShippingMethod::create($shippingMethod);
//        //    if ($key == 1) {
//        //        $cities = City::where("id", '!=', $rass->id)->pluck('id');
//        //        foreach ($cities as $cityId)
//        //            $createdShipping->cities()->create(['city_id' => $cityId]);
//        //    }
//
//
//           if ($key == 0) {
//               $createdShipping->cities()->create(['city_id' => $rass->id]);
//           }
//       }


        // 		$cities = ["Dammam", "Dhahran", "Jeddah", "Jouf", "Khamis Mushayat", "Khayber", "Madinah", "Riyadh", "Tabuk", "Taif", "Yanbu", "Khubar", "Makkah", "Buraydah", "Unayzah", "Hail", "Abha", "Hufuf", "Jubail", "Qatif", "Khafji", "Ras Tannurah", "Buqaiq", "Sayhat", "Safwa", "Jazan", "Sabya", "Abu Arish", "Hafar Al Baten", "Rabigh", "Lith", "Ula", "Duwadimi", "Majmaah", "Zulfi", "Afif", "Arar", "Kharj", "Muzahmiyah", "Ranyah", "Turbah", "Taima", "Dhuba", "Qurayyat", "Turayf", "Wadi Dawasir", "Quwayiyah", "Muhayil", "Salwa", "Rafha", "Baha", "Baljurashi", "Qunfudhah", "Mukhwah", "Mandaq", "Qilwah", "Atawlah", "Aqiq", "Mudhaylif", "Nairiyah", "Qarya Al Uliya", "Tarut", "Anak", "Udhayliyah", "Najran", "Sharourah", "Habounah", "Samtah", "Ahad Al Masarhah", "Baysh", "Darb", "Dhamad", "Bani Malek", "Furasan", "Tuwal", "Shuqayq", "Badr", "Jamoum", "Khulais", "Bahrah", "Masturah", "Shaibah", "Rass", "Bukayriyah", "Badaya", "Riyadh Al Khabra", "Uyun Al Jiwa", "Nabhaniah", "Sajir", "Khabra", "Uqlat As Suqur", "Rafayaa Al Gimsh", "Nabhaniah", "Dukhnah", "Nifi", "Skakah", "Dawmat Al Jandal", "Namas", "Sapt Al Ulaya", "Bellasmar"];

        // 		foreach($cities as $ciy) {
        // 			City::create(
        // 				[
        // 					'country_id' => 1,
        // 					'name' => $ciy
        // 				]
        // 			);
        // 		}


//        DB::insert("INSERT INTO `types` (`id`, `name`, `ar_name`, `created_at`, `updated_at`) VALUES(null , 'wholesales & retail sales', ' مبيعات الجملة والتجزئة', now(), now());");
//        DB::insert("
//INSERT INTO `countries` (`id`, `country_code`, `name`, `ar_name`, `nationality`, `ar_nationality`, `created_at`, `updated_at`) VALUES
//(1, 'SA', 'Saudi Arabia', 'المملكة العربية السعودية', 'Saudi Arabian', 'سعودي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(2, 'AF', 'Afghanistan', 'أفغانستان', 'Afghan', 'أفغانستاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(3, 'AL', 'Albania', 'ألبانيا', 'Albanian', 'ألباني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(4, 'AX', 'Aland Islands', 'جزر آلاند', 'Aland Islander', 'آلاندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(5, 'DZ', 'Algeria', 'الجزائر', 'Algerian', 'جزائري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(6, 'AS', 'American Samoa', 'ساموا-الأمريكي', 'American Samoan', 'أمريكي سامواني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(7, 'AD', 'Andorra', 'أندورا', 'Andorran', 'أندوري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(8, 'AO', 'Angola', 'أنغولا', 'Angolan', 'أنقولي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(9, 'AI', 'Anguilla', 'أنغويلا', 'Anguillan', 'أنغويلي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(10, 'AQ', 'Antarctica', 'أنتاركتيكا', 'Antarctican', 'أنتاركتيكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(11, 'AG', 'Antigua and Barbuda', 'أنتيغوا وبربودا', 'Antiguan', 'بربودي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(12, 'AR', 'Argentina', 'الأرجنتين', 'Argentinian', 'أرجنتيني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(13, 'AM', 'Armenia', 'أرمينيا', 'Armenian', 'أرميني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(14, 'AW', 'Aruba', 'أروبه', 'Aruban', 'أوروبهيني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(15, 'AU', 'Australia', 'أستراليا', 'Australian', 'أسترالي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(16, 'AT', 'Austria', 'النمسا', 'Austrian', 'نمساوي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(17, 'AZ', 'Azerbaijan', 'أذربيجان', 'Azerbaijani', 'أذربيجاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(18, 'BS', 'Bahamas', 'الباهاماس', 'Bahamian', 'باهاميسي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(19, 'BH', 'Bahrain', 'البحرين', 'Bahraini', 'بحريني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(20, 'BD', 'Bangladesh', 'بنغلاديش', 'Bangladeshi', 'بنغلاديشي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(21, 'BB', 'Barbados', 'بربادوس', 'Barbadian', 'بربادوسي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(22, 'BY', 'Belarus', 'روسيا البيضاء', 'Belarusian', 'روسي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(23, 'BE', 'Belgium', 'بلجيكا', 'Belgian', 'بلجيكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(24, 'BZ', 'Belize', 'بيليز', 'Belizean', 'بيليزي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(25, 'BJ', 'Benin', 'بنين', 'Beninese', 'بنيني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(26, 'BL', 'Saint Barthelemy', 'سان بارتيلمي', 'Saint Barthelmian', 'سان بارتيلمي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(27, 'BM', 'Bermuda', 'جزر برمودا', 'Bermudan', 'برمودي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(28, 'BT', 'Bhutan', 'بوتان', 'Bhutanese', 'بوتاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(29, 'BO', 'Bolivia', 'بوليفيا', 'Bolivian', 'بوليفي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(30, 'BA', 'Bosnia and Herzegovina', 'البوسنة و الهرسك', 'Bosnian / Herzegovinian', 'بوسني/هرسكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(31, 'BW', 'Botswana', 'بوتسوانا', 'Botswanan', 'بوتسواني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(32, 'BV', 'Bouvet Island', 'جزيرة بوفيه', 'Bouvetian', 'بوفيهي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(33, 'BR', 'Brazil', 'البرازيل', 'Brazilian', 'برازيلي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(34, 'IO', 'British Indian Ocean Territory', 'إقليم المحيط الهندي البريطاني', 'British Indian Ocean Territory', 'إقليم المحيط الهندي البريطاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(35, 'BN', 'Brunei Darussalam', 'بروني', 'Bruneian', 'بروني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(36, 'BG', 'Bulgaria', 'بلغاريا', 'Bulgarian', 'بلغاري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(37, 'BF', 'Burkina Faso', 'بوركينا فاسو', 'Burkinabe', 'بوركيني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(38, 'BI', 'Burundi', 'بوروندي', 'Burundian', 'بورونيدي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(39, 'KH', 'Cambodia', 'كمبوديا', 'Cambodian', 'كمبودي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(40, 'CM', 'Cameroon', 'كاميرون', 'Cameroonian', 'كاميروني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(41, 'CA', 'Canada', 'كندا', 'Canadian', 'كندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(42, 'CV', 'Cape Verde', 'الرأس الأخضر', 'Cape Verdean', 'الرأس الأخضر', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(43, 'KY', 'Cayman Islands', 'جزر كايمان', 'Caymanian', 'كايماني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(44, 'CF', 'Central African Republic', 'جمهورية أفريقيا الوسطى', 'Central African', 'أفريقي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(45, 'TD', 'Chad', 'تشاد', 'Chadian', 'تشادي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(46, 'CL', 'Chile', 'شيلي', 'Chilean', 'شيلي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(47, 'CN', 'China', 'الصين', 'Chinese', 'صيني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(48, 'CX', 'Christmas Island', 'جزيرة عيد الميلاد', 'Christmas Islander', 'جزيرة عيد الميلاد', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(49, 'CC', 'Cocos (Keeling) Islands', 'جزر كوكوس', 'Cocos Islander', 'جزر كوكوس', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(50, 'CO', 'Colombia', 'كولومبيا', 'Colombian', 'كولومبي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(51, 'KM', 'Comoros', 'جزر القمر', 'Comorian', 'جزر القمر', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(52, 'CG', 'Congo', 'الكونغو', 'Congolese', 'كونغي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(53, 'CK', 'Cook Islands', 'جزر كوك', 'Cook Islander', 'جزر كوك', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(54, 'CR', 'Costa Rica', 'كوستاريكا', 'Costa Rican', 'كوستاريكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(55, 'HR', 'Croatia', 'كرواتيا', 'Croatian', 'كوراتي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(56, 'CU', 'Cuba', 'كوبا', 'Cuban', 'كوبي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(57, 'CY', 'Cyprus', 'قبرص', 'Cypriot', 'قبرصي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(58, 'CW', 'Curaçao', 'كوراساو', 'Curacian', 'كوراساوي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(59, 'CZ', 'Czech Republic', 'الجمهورية التشيكية', 'Czech', 'تشيكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(60, 'DK', 'Denmark', 'الدانمارك', 'Danish', 'دنماركي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(61, 'DJ', 'Djibouti', 'جيبوتي', 'Djiboutian', 'جيبوتي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(62, 'DM', 'Dominica', 'دومينيكا', 'Dominican', 'دومينيكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(63, 'DO', 'Dominican Republic', 'الجمهورية الدومينيكية', 'Dominican', 'دومينيكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(64, 'EC', 'Ecuador', 'إكوادور', 'Ecuadorian', 'إكوادوري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(65, 'EG', 'Egypt', 'مصر', 'Egyptian', 'مصري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(66, 'SV', 'El Salvador', 'إلسلفادور', 'Salvadoran', 'سلفادوري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(67, 'GQ', 'Equatorial Guinea', 'غينيا الاستوائي', 'Equatorial Guinean', 'غيني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(68, 'ER', 'Eritrea', 'إريتريا', 'Eritrean', 'إريتيري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(69, 'EE', 'Estonia', 'استونيا', 'Estonian', 'استوني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(70, 'ET', 'Ethiopia', 'أثيوبيا', 'Ethiopian', 'أثيوبي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(71, 'FK', 'Falkland Islands (Malvinas)', 'جزر فوكلاند', 'Falkland Islander', 'فوكلاندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(72, 'FO', 'Faroe Islands', 'جزر فارو', 'Faroese', 'جزر فارو', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(73, 'FJ', 'Fiji', 'فيجي', 'Fijian', 'فيجي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(74, 'FI', 'Finland', 'فنلندا', 'Finnish', 'فنلندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(75, 'FR', 'France', 'فرنسا', 'French', 'فرنسي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(76, 'GF', 'French Guiana', 'غويانا الفرنسية', 'French Guianese', 'غويانا الفرنسية', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(77, 'PF', 'French Polynesia', 'بولينيزيا الفرنسية', 'French Polynesian', 'بولينيزيي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(78, 'TF', 'French Southern and Antarctic Lands', 'أراض فرنسية جنوبية وأنتارتيكية', 'French', 'أراض فرنسية جنوبية وأنتارتيكية', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(79, 'GA', 'Gabon', 'الغابون', 'Gabonese', 'غابوني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(80, 'GM', 'Gambia', 'غامبيا', 'Gambian', 'غامبي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(81, 'GE', 'Georgia', 'جيورجيا', 'Georgian', 'جيورجي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(82, 'DE', 'Germany', 'ألمانيا', 'German', 'ألماني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(83, 'GH', 'Ghana', 'غانا', 'Ghanaian', 'غاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(84, 'GI', 'Gibraltar', 'جبل طارق', 'Gibraltar', 'جبل طارق', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(85, 'GG', 'Guernsey', 'غيرنزي', 'Guernsian', 'غيرنزي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(86, 'GR', 'Greece', 'اليونان', 'Greek', 'يوناني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(87, 'GL', 'Greenland', 'جرينلاند', 'Greenlandic', 'جرينلاندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(88, 'GD', 'Grenada', 'غرينادا', 'Grenadian', 'غرينادي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(89, 'GP', 'Guadeloupe', 'جزر جوادلوب', 'Guadeloupe', 'جزر جوادلوب', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(90, 'GU', 'Guam', 'جوام', 'Guamanian', 'جوامي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(91, 'GT', 'Guatemala', 'غواتيمال', 'Guatemalan', 'غواتيمالي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(92, 'GN', 'Guinea', 'غينيا', 'Guinean', 'غيني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(93, 'GW', 'Guinea-Bissau', 'غينيا-بيساو', 'Guinea-Bissauan', 'غيني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(94, 'GY', 'Guyana', 'غيانا', 'Guyanese', 'غياني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(95, 'HT', 'Haiti', 'هايتي', 'Haitian', 'هايتي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(96, 'HM', 'Heard and Mc Donald Islands', 'جزيرة هيرد وجزر ماكدونالد', 'Heard and Mc Donald Islanders', 'جزيرة هيرد وجزر ماكدونالد', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(97, 'HN', 'Honduras', 'هندوراس', 'Honduran', 'هندوراسي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(98, 'HK', 'Hong Kong', 'هونغ كونغ', 'Hongkongese', 'هونغ كونغي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(99, 'HU', 'Hungary', 'المجر', 'Hungarian', 'مجري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(100, 'IS', 'Iceland', 'آيسلندا', 'Icelandic', 'آيسلندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(101, 'IN', 'India', 'الهند', 'Indian', 'هندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(102, 'IM', 'Isle of Man', 'جزيرة مان', 'Manx', 'ماني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(103, 'ID', 'Indonesia', 'أندونيسيا', 'Indonesian', 'أندونيسيي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(104, 'IR', 'Iran', 'إيران', 'Iranian', 'إيراني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(105, 'IQ', 'Iraq', 'العراق', 'Iraqi', 'عراقي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(106, 'IE', 'Ireland', 'إيرلندا', 'Irish', 'إيرلندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(107, 'IL', 'Israel', 'إسرائيل', 'Israeli', 'إسرائيلي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(108, 'IT', 'Italy', 'إيطاليا', 'Italian', 'إيطالي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(109, 'CI', 'Ivory Coast', 'ساحل العاج', 'Ivory Coastian', 'ساحل العاج', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(110, 'JE', 'Jersey', 'جيرزي', 'Jersian', 'جيرزي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(111, 'JM', 'Jamaica', 'جمايكا', 'Jamaican', 'جمايكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(112, 'JP', 'Japan', 'اليابان', 'Japanese', 'ياباني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(113, 'JO', 'Jordan', 'الأردن', 'Jordanian', 'أردني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(114, 'KZ', 'Kazakhstan', 'كازاخستان', 'Kazakh', 'كازاخستاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(115, 'KE', 'Kenya', 'كينيا', 'Kenyan', 'كيني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(116, 'KI', 'Kiribati', 'كيريباتي', 'I-Kiribati', 'كيريباتي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(117, 'KP', 'Korea(North Korea)', 'كوريا الشمالية', 'North Korean', 'كوري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(118, 'KR', 'Korea(South Korea)', 'كوريا الجنوبية', 'South Korean', 'كوري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(119, 'XK', 'Kosovo', 'كوسوفو', 'Kosovar', 'كوسيفي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(120, 'KW', 'Kuwait', 'الكويت', 'Kuwaiti', 'كويتي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(121, 'KG', 'Kyrgyzstan', 'قيرغيزستان', 'Kyrgyzstani', 'قيرغيزستاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(122, 'LA', 'Lao PDR', 'لاوس', 'Laotian', 'لاوسي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(123, 'LV', 'Latvia', 'لاتفيا', 'Latvian', 'لاتيفي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(124, 'LB', 'Lebanon', 'لبنان', 'Lebanese', 'لبناني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(125, 'LS', 'Lesotho', 'ليسوتو', 'Basotho', 'ليوسيتي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(126, 'LR', 'Liberia', 'ليبيريا', 'Liberian', 'ليبيري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(127, 'LY', 'Libya', 'ليبيا', 'Libyan', 'ليبي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(128, 'LI', 'Liechtenstein', 'ليختنشتين', 'Liechtenstein', 'ليختنشتيني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(129, 'LT', 'Lithuania', 'لتوانيا', 'Lithuanian', 'لتوانيي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(130, 'LU', 'Luxembourg', 'لوكسمبورغ', 'Luxembourger', 'لوكسمبورغي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(131, 'LK', 'Sri Lanka', 'سريلانكا', 'Sri Lankian', 'سريلانكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(132, 'MO', 'Macau', 'ماكاو', 'Macanese', 'ماكاوي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(133, 'MK', 'Macedonia', 'مقدونيا', 'Macedonian', 'مقدوني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(134, 'MG', 'Madagascar', 'مدغشقر', 'Malagasy', 'مدغشقري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(135, 'MW', 'Malawi', 'مالاوي', 'Malawian', 'مالاوي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(136, 'MY', 'Malaysia', 'ماليزيا', 'Malaysian', 'ماليزي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(137, 'MV', 'Maldives', 'المالديف', 'Maldivian', 'مالديفي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(138, 'ML', 'Mali', 'مالي', 'Malian', 'مالي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(139, 'MT', 'Malta', 'مالطا', 'Maltese', 'مالطي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(140, 'MH', 'Marshall Islands', 'جزر مارشال', 'Marshallese', 'مارشالي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(141, 'MQ', 'Martinique', 'مارتينيك', 'Martiniquais', 'مارتينيكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(142, 'MR', 'Mauritania', 'موريتانيا', 'Mauritanian', 'موريتانيي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(143, 'MU', 'Mauritius', 'موريشيوس', 'Mauritian', 'موريشيوسي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(144, 'YT', 'Mayotte', 'مايوت', 'Mahoran', 'مايوتي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(145, 'MX', 'Mexico', 'المكسيك', 'Mexican', 'مكسيكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(146, 'FM', 'Micronesia', 'مايكرونيزيا', 'Micronesian', 'مايكرونيزيي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(147, 'MD', 'Moldova', 'مولدافيا', 'Moldovan', 'مولديفي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(148, 'MC', 'Monaco', 'موناكو', 'Monacan', 'مونيكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(149, 'MN', 'Mongolia', 'منغوليا', 'Mongolian', 'منغولي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(150, 'ME', 'Montenegro', 'الجبل الأسود', 'Montenegrin', 'الجبل الأسود', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(151, 'MS', 'Montserrat', 'مونتسيرات', 'Montserratian', 'مونتسيراتي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(152, 'MA', 'Morocco', 'المغرب', 'Moroccan', 'مغربي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(153, 'MZ', 'Mozambique', 'موزمبيق', 'Mozambican', 'موزمبيقي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(154, 'MM', 'Myanmar', 'ميانمار', 'Myanmarian', 'ميانماري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(155, 'NA', 'Namibia', 'ناميبيا', 'Namibian', 'ناميبي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(156, 'NR', 'Nauru', 'نورو', 'Nauruan', 'نوري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(157, 'NP', 'Nepal', 'نيبال', 'Nepalese', 'نيبالي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(158, 'NL', 'Netherlands', 'هولندا', 'Dutch', 'هولندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(159, 'AN', 'Netherlands Antilles', 'جزر الأنتيل الهولندي', 'Dutch Antilier', 'هولندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(160, 'NC', 'New Caledonia', 'كاليدونيا الجديدة', 'New Caledonian', 'كاليدوني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(161, 'NZ', 'New Zealand', 'نيوزيلندا', 'New Zealander', 'نيوزيلندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(162, 'NI', 'Nicaragua', 'نيكاراجوا', 'Nicaraguan', 'نيكاراجوي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(163, 'NE', 'Niger', 'النيجر', 'Nigerien', 'نيجيري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(164, 'NG', 'Nigeria', 'نيجيريا', 'Nigerian', 'نيجيري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(165, 'NU', 'Niue', 'ني', 'Niuean', 'ني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(166, 'NF', 'Norfolk Island', 'جزيرة نورفولك', 'Norfolk Islander', 'نورفوليكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(167, 'MP', 'Northern Mariana Islands', 'جزر ماريانا الشمالية', 'Northern Marianan', 'ماريني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(168, 'NO', 'Norway', 'النرويج', 'Norwegian', 'نرويجي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(169, 'OM', 'Oman', 'عمان', 'Omani', 'عماني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(170, 'PK', 'Pakistan', 'باكستان', 'Pakistani', 'باكستاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(171, 'PW', 'Palau', 'بالاو', 'Palauan', 'بالاوي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(172, 'PS', 'Palestine', 'فلسطين', 'Palestinian', 'فلسطيني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(173, 'PA', 'Panama', 'بنما', 'Panamanian', 'بنمي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(174, 'PG', 'Papua New Guinea', 'بابوا غينيا الجديدة', 'Papua New Guinean', 'بابوي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(175, 'PY', 'Paraguay', 'باراغواي', 'Paraguayan', 'بارغاوي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(176, 'PE', 'Peru', 'بيرو', 'Peruvian', 'بيري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(177, 'PH', 'Philippines', 'الفليبين', 'Filipino', 'فلبيني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(178, 'PN', 'Pitcairn', 'بيتكيرن', 'Pitcairn Islander', 'بيتكيرني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(179, 'PL', 'Poland', 'بولونيا', 'Polish', 'بوليني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(180, 'PT', 'Portugal', 'البرتغال', 'Portuguese', 'برتغالي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(181, 'PR', 'Puerto Rico', 'بورتو ريكو', 'Puerto Rican', 'بورتي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(182, 'QA', 'Qatar', 'قطر', 'Qatari', 'قطري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(183, 'RE', 'Reunion Island', 'ريونيون', 'Reunionese', 'ريونيوني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(184, 'RO', 'Romania', 'رومانيا', 'Romanian', 'روماني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(185, 'RU', 'Russian', 'روسيا', 'Russian', 'روسي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(186, 'RW', 'Rwanda', 'رواندا', 'Rwandan', 'رواندا', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(187, 'KN', 'Saint Kitts and Nevis', 'سانت كيتس ونيفس,', 'Kittitian/Nevisian', 'سانت كيتس ونيفس', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(188, 'MF', 'Saint Martin (French part)', 'ساينت مارتن فرنسي', 'St. Martian(French)', 'ساينت مارتني فرنسي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(189, 'SX', 'Sint Maarten (Dutch part)', 'ساينت مارتن هولندي', 'St. Martian(Dutch)', 'ساينت مارتني هولندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(190, 'LC', 'Saint Pierre and Miquelon', 'سان بيير وميكلون', 'St. Pierre and Miquelon', 'سان بيير وميكلوني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(191, 'VC', 'Saint Vincent and the Grenadines', 'سانت فنسنت وجزر غرينادين', 'Saint Vincent and the Grenadines', 'سانت فنسنت وجزر غرينادين', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(192, 'WS', 'Samoa', 'ساموا', 'Samoan', 'ساموي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(193, 'SM', 'San Marino', 'سان مارينو', 'Sammarinese', 'ماريني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(194, 'ST', 'Sao Tome and Principe', 'ساو تومي وبرينسيبي', 'Sao Tomean', 'ساو تومي وبرينسيبي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(195, 'SN', 'Senegal', 'السنغال', 'Senegalese', 'سنغالي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(196, 'RS', 'Serbia', 'صربيا', 'Serbian', 'صربي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(197, 'SC', 'Seychelles', 'سيشيل', 'Seychellois', 'سيشيلي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(198, 'SL', 'Sierra Leone', 'سيراليون', 'Sierra Leonean', 'سيراليوني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(199, 'SG', 'Singapore', 'سنغافورة', 'Singaporean', 'سنغافوري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(200, 'SK', 'Slovakia', 'سلوفاكيا', 'Slovak', 'سولفاكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(201, 'SI', 'Slovenia', 'سلوفينيا', 'Slovenian', 'سولفيني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(202, 'SB', 'Solomon Islands', 'جزر سليمان', 'Solomon Island', 'جزر سليمان', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(203, 'SO', 'Somalia', 'الصومال', 'Somali', 'صومالي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(204, 'ZA', 'South Africa', 'جنوب أفريقيا', 'South African', 'أفريقي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(205, 'GS', 'South Georgia and the South Sandwich', 'المنطقة القطبية الجنوبية', 'South Georgia and the South Sandwich', 'لمنطقة القطبية الجنوبية', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(206, 'SS', 'South Sudan', 'السودان الجنوبي', 'South Sudanese', 'سوادني جنوبي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(207, 'ES', 'Spain', 'إسبانيا', 'Spanish', 'إسباني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(208, 'SH', 'Saint Helena', 'سانت هيلانة', 'St. Helenian', 'هيلاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(209, 'SD', 'Sudan', 'السودان', 'Sudanese', 'سوداني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(210, 'SR', 'Suriname', 'سورينام', 'Surinamese', 'سورينامي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(211, 'SJ', 'Svalbard and Jan Mayen', 'سفالبارد ويان ماين', 'Svalbardian/Jan Mayenian', 'سفالبارد ويان ماين', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(212, 'SZ', 'Swaziland', 'سوازيلند', 'Swazi', 'سوازيلندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(213, 'SE', 'Sweden', 'السويد', 'Swedish', 'سويدي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(214, 'CH', 'Switzerland', 'سويسرا', 'Swiss', 'سويسري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(215, 'SY', 'Syria', 'سوريا', 'Syrian', 'سوري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(216, 'TW', 'Taiwan', 'تايوان', 'Taiwanese', 'تايواني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(217, 'TJ', 'Tajikistan', 'طاجيكستان', 'Tajikistani', 'طاجيكستاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(218, 'TZ', 'Tanzania', 'تنزانيا', 'Tanzanian', 'تنزانيي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(219, 'TH', 'Thailand', 'تايلندا', 'Thai', 'تايلندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(220, 'TL', 'Timor-Leste', 'تيمور الشرقية', 'Timor-Lestian', 'تيموري', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(221, 'TG', 'Togo', 'توغو', 'Togolese', 'توغي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(222, 'TK', 'Tokelau', 'توكيلاو', 'Tokelaian', 'توكيلاوي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(223, 'TO', 'Tonga', 'تونغا', 'Tongan', 'تونغي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(224, 'TT', 'Trinidad and Tobago', 'ترينيداد وتوباغو', 'Trinidadian/Tobagonian', 'ترينيداد وتوباغو', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(225, 'TN', 'Tunisia', 'تونس', 'Tunisian', 'تونسي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(226, 'TR', 'Turkey', 'تركيا', 'Turkish', 'تركي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(227, 'TM', 'Turkmenistan', 'تركمانستان', 'Turkmen', 'تركمانستاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(228, 'TC', 'Turks and Caicos Islands', 'جزر توركس وكايكوس', 'Turks and Caicos Islands', 'جزر توركس وكايكوس', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(229, 'TV', 'Tuvalu', 'توفالو', 'Tuvaluan', 'توفالي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(230, 'UG', 'Uganda', 'أوغندا', 'Ugandan', 'أوغندي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(231, 'UA', 'Ukraine', 'أوكرانيا', 'Ukrainian', 'أوكراني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(232, 'AE', 'United Arab Emirates', 'الإمارات العربية المتحدة', 'Emirati', 'إماراتي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(233, 'GB', 'United Kingdom', 'المملكة المتحدة', 'British', 'بريطاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(234, 'US', 'United States', 'الولايات المتحدة', 'American', 'أمريكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(235, 'UM', 'US Minor Outlying Islands', 'قائمة الولايات والمناطق الأمريكية', 'US Minor Outlying Islander', 'أمريكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(236, 'UY', 'Uruguay', 'أورغواي', 'Uruguayan', 'أورغواي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(237, 'UZ', 'Uzbekistan', 'أوزباكستان', 'Uzbek', 'أوزباكستاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(238, 'VU', 'Vanuatu', 'فانواتو', 'Vanuatuan', 'فانواتي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(239, 'VE', 'Venezuela', 'فنزويلا', 'Venezuelan', 'فنزويلي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(240, 'VN', 'Vietnam', 'فيتنام', 'Vietnamese', 'فيتنامي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(241, 'VI', 'Virgin Islands (U.S.)', 'الجزر العذراء الأمريكي', 'American Virgin Islander', 'أمريكي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(242, 'VA', 'Vatican City', 'فنزويلا', 'Vatican', 'فاتيكاني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(243, 'WF', 'Wallis and Futuna Islands', 'والس وفوتونا', 'Wallisian/Futunan', 'فوتوني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(244, 'EH', 'Western Sahara', 'الصحراء الغربية', 'Sahrawian', 'صحراوي', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(245, 'YE', 'Yemen', 'اليمن', 'Yemeni', 'يمني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(246, 'ZM', 'Zambia', 'زامبيا', 'Zambian', 'زامبياني', '2019-09-27 07:00:00', '2019-09-27 07:00:00'),
//(247, 'ZW', 'Zimbabwe', 'زمبابوي', 'Zimbabwean', 'زمبابوي', '2019-09-27 07:00:00', '2019-09-27 07:00:00');
//");
    }
}
