<?php

namespace Database\Seeders;
use App\Models\Setting;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Seeder;

class SettingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::setmany([
              'default_locale' => 'ar',
              'default_timezone' => 'Africa/Cairo',
              'reviews_enabled' => 'true',
              'auto_approve_reviews' => 'true',
              'supported_currencies' => ['USD','LE','SAR'],
              'default_currency' => 'USD',
              'store_email' => 'admin@ecommerce.test',
              'search_engine' => 'mysql',
              'local_pickup_cost' => 0,
              'flat_rate_cost' => 0,
              'translatable' => [
                  'store_name' => 'متجر هنود ',
                  'free_shipping_label' => 'توصيل مجاني',
                  'local_label' => 'توصيل داخلي',
                  'outer_label' => 'توصيل خارجي',
              ],
        ]);
    }
}
