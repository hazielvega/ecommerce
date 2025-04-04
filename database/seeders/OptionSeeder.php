<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            [
                'name' => 'Talla',
                'type' => 1,
                'features' => [
                    [
                        'value' => 's',
                        'description' => 'small',
                    ],
                    [
                        'value' => 'm',
                        'description' => 'medium',
                    ],
                    [
                        'value' => 'l',
                        'description' => 'large',
                    ],
                    [
                        'value' => 'xl',
                        'description' => 'extra large',
                    ],
                ]
            ],
            [
                'name' => 'Color',
                'type' => 2,
                'features' => [
                    [
                        'value' => '#000000',
                        'description' => 'negro',
                    ],
                    [
                        'value' => '#ffffff',
                        'description' => 'blanco',
                    ],
                    [
                        'value' => '#ff0000',
                        'description' => 'rojo',
                    ],
                    [
                        'value' => '#00ff00',
                        'description' => 'verde',
                    ],
                    [
                        'value' => '#0000ff',
                        'description' => 'azul',
                    ],
                    [
                        'value' => '#ffff00',
                        'description' => 'amarillo',
                    ],
                    [
                        'value' => '#00ffff',
                        'description' => 'cyan',
                    ],
                    [
                        'value' => '#ff00ff',
                        'description' => 'magenta',
                    ],
                ]
            ],
        ];

        foreach($options as $option) 
        {
            $optionModel = Option::create([
                'name' => $option['name'],
                'type' => $option['type'], 
            ]);

            foreach($option['features'] as $feature)
            {
                $optionModel->features()->create([
                    'value' => $feature['value'],
                    'description' => $feature['description'],
                ]);
            }
        }
    }
}
