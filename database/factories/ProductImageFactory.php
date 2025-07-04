<?php

namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ProductImage::class;

    public function definition(): array
    {
        $placeholdersFolder = resource_path('images/placeholders');
        $images = File::files($placeholdersFolder);

        $randomImage = collect($images)->random();
        $source = $randomImage->getRealPath();

        $filename = Str::random(20) . '.' . $randomImage->getExtension();
        $databasePath = "assets/img/products/{$filename}";
        $destination = public_path('tenancy/assets/tenant' . tenant()->id . '/' . $databasePath);

        File::ensureDirectoryExists(dirname($destination));

        copy($source, $destination);

        return [
            'path' => $databasePath,
        ];
    }
}
