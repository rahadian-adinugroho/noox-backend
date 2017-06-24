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

        $readNews = $this->generateRNAchievements([
            [
            'name'  => 'national',
            'title' => 'Nationalist',
            ],
            [
            'name'  => 'business',
            'title' => 'Entrepreneur',
            ],
            [
            'name'  => 'crime',
            'title' => 'Enforcer',
            ],
            [
            'name'  => 'health',
            'title' => 'Regimen',
            ],
            [
            'name'  => 'lifestyle',
            'title' => 'Socialist',
            ],
            [
            'name'  => 'automotive',
            'title' => 'Mechanic',
            ],
            [
            'name'  => 'politic',
            'title' => 'Politician',
            ],
            [
            'name'  => 'sport',
            'title' => 'Marksman',
            ],
            [
            'name'  => 'technology',
            'title' => 'Geek',
            ],
        ]);

        $submitReport = [
            [
            'key'         => 'SR-1',
            'title'       => 'Amateurish Claimant',
            'description' => 'Submit a news report 5 times.',
            ],
            [
            'key'         => 'SR-2',
            'title'       => 'Novice Claimant',
            'description' => 'Submit a news report 25 times.',
            ],
            [
            'key'         => 'SR-3',
            'title'       => 'Enthusiastic Claimant',
            'description' => 'Submit a news report 45 times.',
            ],
            [
            'key'         => 'SR-4',
            'title'       => 'Veteran Claimant',
            'description' => 'Submit a news report 80 times.',
            ],
            [
            'key'         => 'SR-5',
            'title'       => 'Noble Claimant',
            'description' => 'Submit a news report 125 times.',
            ],
        ];

        $approvedReport = [
            [
            'key'         => 'AR-1',
            'title'       => 'Amateurish Trusted-Informant',
            'description' => 'Get your report approved 5 times.',
            ],
            [
            'key'         => 'AR-2',
            'title'       => 'Novice Trusted-Informant',
            'description' => 'Get your report approved 25 times.',
            ],
            [
            'key'         => 'AR-3',
            'title'       => 'Enthusiastic Trusted-Informant',
            'description' => 'Get your report approved 45 times.',
            ],
            [
            'key'         => 'AR-4',
            'title'       => 'Veteran Trusted-Informant',
            'description' => 'Get your report approved 80 times.',
            ],
            [
            'key'         => 'AR-5',
            'title'       => 'Noble Trusted-Informant',
            'description' => 'Get your report approved 125 times.',
            ],
        ];

        Achievement::insert(array_merge($readNews, $submitReport, $approvedReport));
        Model::reguard();
    }

    protected function generateRNAchievements(array $newsTypes)
    {
        $res = array();

        foreach ($newsTypes as $key => $types) {
            $res[] = [
                'key'         => "RN-{$types['name']}-1",
                'title'       => "Amateurish {$types['title']}",
                'description' => "Read {$types['name']} news 5 times.",
            ];
            $res[] = [
                'key'         => "RN-{$types['name']}-2",
                'title'       => "Novice {$types['title']}",
                'description' => "Read {$types['name']} news 25 times.",
            ];
            $res[] = [
                'key'         => "RN-{$types['name']}-3",
                'title'       => "Enthusiastic {$types['title']}",
                'description' => "Read {$types['name']} news 45 times.",
            ];
            $res[] = [
                'key'         => "RN-{$types['name']}-4",
                'title'       => "Veteran {$types['title']}",
                'description' => "Read {$types['name']} news 80 times.",
            ];
            $res[] = [
                'key'         => "RN-{$types['name']}-5",
                'title'       => "Noble {$types['title']}",
                'description' => "Read {$types['name']} news 125 times.",
            ];
        }

        return $res;
    }
}
