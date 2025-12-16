<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $recipes = [
      [
        'title' => 'Nasi Goreng Spesial',
        'description' => 'Nasi goreng dengan bumbu rahasia keluarga',
        'ingredients' => ['nasi putih 2 piring', 'telur 2 butir', 'kecap manis 2 sdm', 'bawang merah 5 siung', 'bawang putih 3 siung', 'cabai rawit 5 buah', 'garam secukupnya'],
        'instructions' => 'Panaskan minyak, tumis bawang hingga harum. Masukkan telur, orak-arik. Tambahkan nasi, aduk rata. Bumbui dengan kecap dan garam. Sajikan hangat.',
      ],
      [
        'title' => 'Ayam Goreng Crispy',
        'description' => 'Ayam goreng renyah ala restoran',
        'ingredients' => ['ayam 1 ekor', 'tepung terigu 200g', 'tepung maizena 50g', 'bawang putih bubuk 1 sdt', 'garam 1 sdt', 'merica 1/2 sdt', 'air es 200ml'],
        'instructions' => 'Marinasi ayam dengan bumbu. Campur tepung kering. Celup ayam ke air es lalu gulingkan di tepung. Goreng hingga golden brown.',
      ],
      [
        'title' => 'Soto Ayam',
        'description' => 'Soto ayam kuning yang hangat dan gurih',
        'ingredients' => ['ayam 500g', 'kunyit 3 cm', 'serai 2 batang', 'daun jeruk 5 lembar', 'bawang merah 8 siung', 'bawang putih 5 siung', 'kemiri 3 butir'],
        'instructions' => 'Rebus ayam hingga empuk. Tumis bumbu halus hingga harum. Masukkan ke kuah ayam. Sajikan dengan pelengkap.',
      ],
      [
        'title' => 'Rendang Daging',
        'description' => 'Rendang daging sapi khas Padang',
        'ingredients' => ['daging sapi 1 kg', 'santan kental 1 liter', 'cabai merah 15 buah', 'bawang merah 10 siung', 'bawang putih 6 siung', 'lengkuas 5 cm', 'serai 3 batang'],
        'instructions' => 'Tumis bumbu halus. Masukkan daging dan santan. Masak dengan api kecil hingga santan mengering dan daging empuk.',
      ],
      [
        'title' => 'Mie Goreng',
        'description' => 'Mie goreng sederhana yang lezat',
        'ingredients' => ['mie telur 200g', 'telur 2 butir', 'sawi hijau 100g', 'kecap manis 3 sdm', 'bawang putih 3 siung', 'cabai rawit 3 buah'],
        'instructions' => 'Rebus mie, tiriskan. Tumis bawang putih, masukkan telur orak-arik. Tambahkan mie dan sawi. Bumbui dengan kecap.',
      ],
      [
        'title' => 'Gado-Gado',
        'description' => 'Salad sayuran dengan bumbu kacang',
        'ingredients' => ['kacang tanah 200g', 'tahu 2 potong', 'tempe 2 potong', 'kangkung 1 ikat', 'tauge 100g', 'kentang 2 buah', 'cabai merah 5 buah'],
        'instructions' => 'Rebus sayuran. Goreng tahu tempe. Haluskan kacang dengan bumbu. Siram sayuran dengan bumbu kacang.',
      ],
    ];

    $recipe = fake()->randomElement($recipes);

    return [
      'user_id' => User::factory(),
      'title' => $recipe['title'],
      'description' => $recipe['description'],
      'ingredients' => $recipe['ingredients'],
      'instructions' => $recipe['instructions'],
    ];
  }
}
