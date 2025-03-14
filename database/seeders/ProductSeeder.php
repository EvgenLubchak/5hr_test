<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Property;
use App\Models\PropertyValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //test solution no time for better seeding
        $properties = [
            'Color' => ['Red', 'Green', 'Blue', 'Yellow', 'White'],
            'Size' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
            'Materials' => ['Red', 'Green', 'Blue', 'Yellow', 'White'],
            'Style' => ['Black', 'White', 'classic'],
        ];

        foreach ($properties as $propertyName => $values) {

            $property = Property::create([
                'name' => $propertyName,
                'type'=>'string'
            ]);

            $propertyIds[$propertyName] = [];
            foreach ($values as $value) {
                $propertyValue = PropertyValue::create([
                    'property_id' => $property->id,
                    'value' => $value,
                ]);
                $propertyIds[$propertyName][] = $propertyValue->id;
            }

            $products = ['Dress', 'Jeans', 'Jacket', 'Shorts'];
            $adjectives = ['Classic', 'Modern', 'Elegant', 'Sport'];

            $this->command->info('start create product');

            for ($i = 0; $i < 30; $i++) {
                for ($j = 0; $j < 1000; $j++) {

                    $product = Product::create([
                        'name' => $adjectives[array_rand($adjectives)] . " " . $products[array_rand($products)],
                        'description' => 'Description',
                        'price' => rand(1000, 5000) / 100,
                    ]);

                    foreach ($propertyIds as $propertyName => $values) {
                        if (in_array($propertyName, ['Color', 'Size'])) {
                            $count = rand(2,3);
                            $selectedValues = array_rand(array_flip($values), $count);
                            foreach ( (array)$selectedValues as $valueId) {
                                $productsLinks[] = [
                                    'product_id' => $product->id,
                                    'property_value_id' => $valueId,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }

                        } else {
                            $productsLinks[] = [
                                'product_id' => $product->id,
                                'property_value_id' => $values[array_rand($values)],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }
                foreach (array_chunk($productsLinks, 500) as $chunk) {
                    DB::table('product_properties')->insert($chunk);
                }
                $this->command->info(sprintf('Processed %d/30000 products', ($i + 1)*1000));
            }
        }
    }
}
