<?php

use Illuminate\Database\Seeder;

class JenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('jenis')->insert([
        	['nama' => 'Dichotomous',
            'is_multiple' => 0,
            'is_rule' => 1,
        	'is_anti' => 0,
            // 'is_reusable' => 0,
            ],
        	['nama' => 'Multiple Choices, Single Answer',
            'is_multiple' => 0,
            'is_rule' => 1,
        	'is_anti' => 0,
            // 'is_reusable' => 0,
            ],
        	['nama' => 'Multiple Choices, Multiple Answer',
            'is_multiple' => 1,
            'is_rule' => 0,
        	'is_anti' => 1,
            // 'is_reusable' => 0,
            ],
        	['nama' => 'Rating Scale',
            'is_multiple' => 0,
            'is_rule' => 1,
        	'is_anti' => 0,
            // 'is_reusable' => 0,
            ],
        	['nama' => 'Semantic Differential',
            'is_multiple' => 0,
            'is_rule' => 1,
        	'is_anti' => 0,
            // 'is_reusable' => 0,
            ],
        	['nama' => 'Open-Ended',
            'is_multiple' => 0,
            'is_rule' => 0,
        	'is_anti' => 0,
            // 'is_reusable' => 0,
            ],
        	['nama' => 'Matrix Table',
            'is_multiple' => 0,
            'is_rule' => 0,
        	'is_anti' => 0,
            // 'is_reusable' => 0,
            ],
        	['nama' => 'Side-by-side Matrix',
            'is_multiple' => 1,
            'is_rule' => 0,
        	'is_anti' => 0,
            // 'is_reusable' => 0,
            ],
        	// ['nama' => 'Static Content',
         //    'is_multiple' => 0,
         //    'is_rule' => 0,
        	// 'is_anti' => 0,
            // 'is_reusable' => 0,
         //    ],
            // ['nama' => 'Reusable',
            // 'is_multiple' => 0,
            // 'is_rule' => 0,
            // 'is_anti' => 0,
            // // 'is_reusable' => 1,
            // ],
            // ['nama' => 'Responden',
            // 'is_multiple' => 0,
            // 'is_rule' => 0,
            // 'is_anti' => 0,
            // // 'is_reusable' => 1,
            // ],
        ]);

        // \DB::table('tipe')->insert([
        //     ['nama' => 'Text'],
        //     ['nama' => 'Textarea'],
        //     ['nama' => 'Single Answer'],
        //     ['nama' => 'Multiple Answer'],
        // ]);
    }
}
