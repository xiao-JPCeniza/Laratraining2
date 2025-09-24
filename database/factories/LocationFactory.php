<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

       
        $officeLocations= ['DICT', 
        'Provincial',
         'Municipal',
                'Municipal Hall Office',
                'IT Office ',
                'RandD Office',
                'Data Center',
                'Tech Hub',
                'Training Room',
                'Main Office',
                'Branch Office',
                'Remote Site',
                'Field Office',
                'Support Center',
                'Operations Center',
                'Logistics Center',
            
            ];

        

         
        return [
            'name'=> $this->faker->unique()->randomElement($officeLocations),
            'address'=> $this->faker->address(),
        ];
    }
}
