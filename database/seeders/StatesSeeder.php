<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->truncate();
        DB::table('states')->insert(['name' => 'Alaska', 'abbr' => 'AK']);
        DB::table('states')->insert(['name' => 'Alabama', 'abbr' => 'AL']);
        DB::table('states')->insert(['name' => 'Arizona', 'abbr' => 'AZ']);
        DB::table('states')->insert(['name' => 'Arkansas', 'abbr' => 'AR']);
        DB::table('states')->insert(['name' => 'California', 'abbr' => 'CA']);
        DB::table('states')->insert(['name' => 'Colorado', 'abbr' => 'CO']);
        DB::table('states')->insert(['name' => 'Connecticut', 'abbr' => 'CT']);
        DB::table('states')->insert(['name' => 'Delaware', 'abbr' => 'DE']);
        DB::table('states')->insert(['name' => 'Florida', 'abbr' => 'FL']);
        DB::table('states')->insert(['name' => 'Georgia', 'abbr' => 'GA']);
        DB::table('states')->insert(['name' => 'Hawaii', 'abbr' => 'HI']);
        DB::table('states')->insert(['name' => 'Idaho', 'abbr' => 'ID']);
        DB::table('states')->insert(['name' => 'Illinois', 'abbr' => 'IL']);
        DB::table('states')->insert(['name' => 'Indiana', 'abbr' => 'IN']);
        DB::table('states')->insert(['name' => 'Iowa', 'abbr' => 'IA']);
        DB::table('states')->insert(['name' => 'Kansas', 'abbr' => 'KS']);
        DB::table('states')->insert(['name' => 'Kentucky', 'abbr' => 'KY']);
        DB::table('states')->insert(['name' => 'Louisiana', 'abbr' => 'LA']);
        DB::table('states')->insert(['name' => 'Maine', 'abbr' => 'ME']);
        DB::table('states')->insert(['name' => 'Maryland', 'abbr' => 'MD']);
        DB::table('states')->insert(['name' => 'Massachusetts', 'abbr' => 'MA']);
        DB::table('states')->insert(['name' => 'Michigan', 'abbr' => 'MI']);
        DB::table('states')->insert(['name' => 'Minnesota', 'abbr' => 'MN']);
        DB::table('states')->insert(['name' => 'Mississippi', 'abbr' => 'MS']);
        DB::table('states')->insert(['name' => 'Missouri', 'abbr' => 'MO']);
        DB::table('states')->insert(['name' => 'Montana', 'abbr' => 'MT']);
        DB::table('states')->insert(['name' => 'Nerbraska', 'abbr' => 'NE']);
        DB::table('states')->insert(['name' => 'Nevada', 'abbr' => 'NV']);
        DB::table('states')->insert(['name' => 'New Hampshire', 'abbr' => 'NH']);
        DB::table('states')->insert(['name' => 'New Jersey', 'abbr' => 'NJ']);
        DB::table('states')->insert(['name' => 'New Mexico', 'abbr' => 'NM']);
        DB::table('states')->insert(['name' => 'New York', 'abbr' => 'NY']);
        DB::table('states')->insert(['name' => 'North Carolina', 'abbr' => 'NC']);
        DB::table('states')->insert(['name' => 'North Dakota', 'abbr' => 'ND']);
        DB::table('states')->insert(['name' => 'Ohio', 'abbr' => 'OH']);
        DB::table('states')->insert(['name' => 'Oklahoma', 'abbr' => 'OK']);
        DB::table('states')->insert(['name' => 'Oregon', 'abbr' => 'OR']);
        DB::table('states')->insert(['name' => 'Pennsylvania', 'abbr' => 'PA']);
        DB::table('states')->insert(['name' => 'Rhode Island', 'abbr' => 'RI']);
        DB::table('states')->insert(['name' => 'South Carolina', 'abbr' => 'SC']);
        DB::table('states')->insert(['name' => 'South Dakota', 'abbr' => 'SD']);
        DB::table('states')->insert(['name' => 'Tennessee', 'abbr' => 'TN']);
        DB::table('states')->insert(['name' => 'Texas', 'abbr' => 'TX']);
        DB::table('states')->insert(['name' => 'Utah', 'abbr' => 'UT']);
        DB::table('states')->insert(['name' => 'Vermont', 'abbr' => 'VT']);
        DB::table('states')->insert(['name' => 'Virginia', 'abbr' => 'VA']);
        DB::table('states')->insert(['name' => 'Washington', 'abbr' => 'WA']);
        DB::table('states')->insert(['name' => 'West Virginia', 'abbr' => 'WV']);
        DB::table('states')->insert(['name' => 'Wisconsin', 'abbr' => 'WI']);
        DB::table('states')->insert(['name' => 'Wyoming', 'abbr' => 'WY']);
    }
}
