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
   * Predefined Indonesian recipes for realistic data
   */
  private array $recipes = [
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
    [
      'title' => 'Bakso Sapi',
      'description' => 'Bakso daging sapi dengan kuah kaldu gurih',
      'ingredients' => ['daging sapi giling 500g', 'tepung tapioka 100g', 'bawang putih 5 siung', 'merica 1 sdt', 'garam 1 sdt', 'es batu 100g', 'mie kuning 200g'],
      'instructions' => 'Haluskan daging dengan bumbu dan es. Tambahkan tepung, aduk rata. Bentuk bulat, rebus hingga mengapung. Sajikan dengan kuah kaldu.',
    ],
    [
      'title' => 'Sate Ayam',
      'description' => 'Sate ayam dengan bumbu kacang khas Indonesia',
      'ingredients' => ['daging ayam 500g', 'kecap manis 3 sdm', 'bawang merah 5 siung', 'kacang tanah 150g', 'cabai merah 3 buah', 'gula merah 50g', 'tusuk sate 20 buah'],
      'instructions' => 'Potong ayam dadu, tusuk dengan tusuk sate. Bakar sambil olesi kecap. Sajikan dengan bumbu kacang dan lontong.',
    ],
    [
      'title' => 'Nasi Uduk',
      'description' => 'Nasi gurih dengan santan khas Betawi',
      'ingredients' => ['beras 500g', 'santan 400ml', 'serai 2 batang', 'daun salam 3 lembar', 'garam 1 sdt', 'daun pandan 2 lembar'],
      'instructions' => 'Cuci beras, masak dengan santan dan bumbu. Kukus hingga matang. Sajikan dengan lauk pelengkap.',
    ],
    [
      'title' => 'Pecel Lele',
      'description' => 'Lele goreng dengan sambal pecel pedas',
      'ingredients' => ['lele 4 ekor', 'tepung beras 100g', 'kunyit 2 cm', 'bawang putih 3 siung', 'garam 1 sdt', 'cabai rawit 10 buah', 'terasi 1 sdt'],
      'instructions' => 'Marinasi lele dengan kunyit dan garam. Goreng hingga crispy. Sajikan dengan sambal dan lalapan.',
    ],
    [
      'title' => 'Opor Ayam',
      'description' => 'Ayam masak santan dengan bumbu rempah',
      'ingredients' => ['ayam 1 ekor', 'santan 500ml', 'kemiri 5 butir', 'ketumbar 1 sdt', 'jintan 1/2 sdt', 'lengkuas 3 cm', 'serai 2 batang', 'daun salam 3 lembar'],
      'instructions' => 'Tumis bumbu halus hingga harum. Masukkan ayam, aduk rata. Tuang santan, masak hingga ayam empuk dan bumbu meresap.',
    ],
    [
      'title' => 'Tempe Mendoan',
      'description' => 'Tempe goreng tipis dengan tepung berbumbu',
      'ingredients' => ['tempe 2 papan', 'tepung terigu 150g', 'tepung beras 50g', 'daun bawang 2 batang', 'bawang putih 3 siung', 'ketumbar 1/2 sdt', 'garam 1 sdt'],
      'instructions' => 'Iris tempe tipis. Campur tepung dengan bumbu dan air. Celup tempe ke adonan, goreng setengah matang. Sajikan hangat.',
    ],
  ];

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $recipe = fake()->randomElement($this->recipes);

    return [
      'user_id' => User::factory(),
      'title' => $recipe['title'],
      'description' => $recipe['description'],
      'ingredients' => $recipe['ingredients'],
      'instructions' => $recipe['instructions'],
    ];
  }

  /**
   * Create a recipe with a specific title (for unique recipes)
   */
  public function withTitle(string $title): static
  {
    return $this->state(fn(array $attributes) => [
      'title' => $title,
    ]);
  }

  /**
   * Create a recipe for a specific user
   */
  public function forUser(User $user): static
  {
    return $this->state(fn(array $attributes) => [
      'user_id' => $user->id,
    ]);
  }
}
