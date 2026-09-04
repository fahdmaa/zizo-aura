<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'author_name' => 'Sarah Laurent',
                'author_role' => 'Cliente vérifiée • Bare Vanilla Duo',
                'rating' => 5,
                'comment' => 'Commande reçue en 48h chrono ! Le pack Bare Vanilla est absolument divin et 100% authentique. Les petits échantillons offerts dans le colis sont une délicate attention.',
                'avatar' => '/images/reviews/sarah.jpg',
                'badge' => 'Achat vérifié',
                'ring_color' => 'pink',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'author_name' => 'Yasmine Benali',
                'author_role' => 'Cliente vérifiée • The Ritual of Sakura',
                'rating' => 5,
                'comment' => 'L\'emballage origami The Ritual of Sakura est splendide, prêt à être offert ! La mousse de douche est tellement onctueuse et le parfum de fleur de cerisier tient toute la journée.',
                'avatar' => '/images/reviews/yasmine.jpg',
                'badge' => 'Achat vérifié',
                'ring_color' => 'amber',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'author_name' => 'Camille Moreau',
                'author_role' => 'Cliente vérifiée • Bum Bum Jet Set',
                'rating' => 5,
                'comment' => 'Le Bum Bum Jet Set est un indispensable de l\'été ! L\'odeur de pistache et caramel salé est complètement addictive. Prix super avantageux avec la réduction.',
                'avatar' => '/images/reviews/camille.jpg',
                'badge' => 'Achat vérifié',
                'ring_color' => 'rose',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'author_name' => 'Léa Dubois',
                'author_role' => 'Cliente vérifiée • VS Bombshell Prestige',
                'rating' => 5,
                'comment' => 'Le flacon Bombshell en cristal avec son nœud satiné est une merveille. La crème pour le corps sublime la peau et fait tenir le parfum toute la soirée.',
                'avatar' => '/images/reviews/lea.jpg',
                'badge' => 'Achat vérifié',
                'ring_color' => 'purple',
                'is_visible' => true,
                'sort_order' => 4,
            ],
            [
                'author_name' => 'Nadia Fourati',
                'author_role' => 'Cliente vérifiée • The Ritual of Ayurveda',
                'rating' => 5,
                'comment' => 'The Ritual of Ayurveda est mon rituel réconfortant préféré. L\'accord rose indienne et amande douce laisse la peau nourrie et satinée. Colis très bien sécurisé.',
                'avatar' => '/images/reviews/nadia.jpg',
                'badge' => 'Achat vérifié',
                'ring_color' => 'emerald',
                'is_visible' => true,
                'sort_order' => 5,
            ],
            [
                'author_name' => 'Emma Vidal',
                'author_role' => 'Cliente vérifiée • Beija Flor Jet Set',
                'rating' => 5,
                'comment' => 'Le Beija Flor Jet Set avec la brume 68 sent divinement bon les fleurs fraîches et les vacances. Ma peau est visiblement plus rebondie avec la crème.',
                'avatar' => '/images/reviews/emma.jpg',
                'badge' => 'Achat vérifié',
                'ring_color' => 'teal',
                'is_visible' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($reviews as $data) {
            Review::updateOrCreate(
                ['author_name' => $data['author_name']],
                $data
            );
        }
    }
}
