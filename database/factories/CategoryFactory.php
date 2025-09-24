<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $deviceCategories = [
            'Laptop',
            'Desktop PC',
            'Tablet',
            'Smartphone',
            'Printer',
            'Scanner',
            'Router',
            'Switch',
            'Projector',
            'Server',
            'Monitor',
            'Keyboard',
            'Mouse',
            'Webcam',
            'External Hard Drive',
            'UPS',
            'Firewall',
            'Access Point',
            'NAS',
            'Smart TV',
            'Network Cable ',
            'Network Adapter',
            'Headphones',
            'Speakers',
            'Microphone',
            'camera',
        ];
        return [
            'name' => $this->faker->unique()->randomElement($deviceCategories),
            'description' => $this->faker->sentence(),
        ];
    }
}
