<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Noox\Models\Achievement;

class AchievementsTableSeeder extends Seeder
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
        Achievement::truncate();
        Schema::enableForeignKeyConstraints();

        $readNews = [
            [
            'key'         => 'RN-1',
            'title'       => 'Read News 1',
            'description' => 'Read news ? times.',
            'xpbonus'     => 10,
            ],
            [
            'key'         => 'RN-2',
            'title'       => 'Read News 2',
            'description' => 'Read news ? times.',
            'xpbonus'     => 10,
            ],
            [
            'key'         => 'RN-3',
            'title'       => 'Read News 3',
            'description' => 'Read news ? times.',
            'xpbonus'     => 10,
            ],
            [
            'key'         => 'RN-4',
            'title'       => 'Read News 4',
            'description' => 'Read news ? times.',
            'xpbonus'     => 10,
            ],
            [
            'key'         => 'RN-5',
            'title'       => 'Read News 5',
            'description' => 'Read news ? times.',
            'xpbonus'     => 10,
            ],
        ];

        $submitReport = [
            [
            'key'         => 'SR-1',
            'title'       => 'Submit Report 1',
            'description' => 'Submit a news report ? times.',
            'xpbonus'     => 10,
            ],
            [
            'key'         => 'SR-2',
            'title'       => 'Submit Report 2',
            'description' => 'Submit a news report ? times.',
            'xpbonus'     => 10,
            ],
            [
            'key'         => 'SR-3',
            'title'       => 'Submit Report 3',
            'description' => 'Submit a news report ? times.',
            'xpbonus'     => 10,
            ],
            [
            'key'         => 'SR-4',
            'title'       => 'Submit Report 4',
            'description' => 'Submit a news report ? times.',
            'xpbonus'     => 10,
            ],
            [
            'key'         => 'SR-5',
            'title'       => 'Submit Report 5',
            'description' => 'Submit a news report ? times.',
            'xpbonus'     => 10,
            ],
        ];

        $approvedReport = [
            [
            'key'         => 'AR-1',
            'title'       => 'Approved Report 1',
            'description' => 'Get your report approved ? times.',
            'xpbonus'     => 10,
            ],
            [
            'key'         => 'AR-2',
            'title'       => 'Approved Report 2',
            'description' => 'Get your report approved ? times.',
            'xpbonus'     => 10,
            ],
            [
            'key'         => 'AR-3',
            'title'       => 'Approved Report 3',
            'description' => 'Get your report approved ? times.',
            'xpbonus'     => 10,
            ],
            [
            'key'         => 'AR-4',
            'title'       => 'Approved Report 4',
            'description' => 'Get your report approved ? times.',
            'xpbonus'     => 10,
            ],
            [
            'key'         => 'AR-5',
            'title'       => 'Approved Report 5',
            'description' => 'Get your report approved ? times.',
            'xpbonus'     => 10,
            ],
        ];

        Achievement::insert(array_merge($readNews, $submitReport, $approvedReport));
        Model::reguard();
    }
}
