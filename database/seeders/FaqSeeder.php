<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => [
                    'en' => 'What is BenLocal?',
                    'nl' => 'Wat is BenLocal?',
                    'es' => '¿Qué es BenLocal?',
                ],
                'answer' => [
                    'en' => '<p>BenLocal is a community-driven platform that helps you discover the best local spots in Tenerife, verified by people from your own community.</p>',
                    'nl' => '<p>BenLocal is een community-gedreven platform dat je helpt de beste lokale plekken in Tenerife te ontdekken, geverifieerd door mensen uit je eigen community.</p>',
                    'es' => '<p>BenLocal es una plataforma impulsada por la comunidad que te ayuda a descubrir los mejores lugares locales en Tenerife, verificados por personas de tu propia comunidad.</p>',
                ],
                'category' => 'General',
                'sort_order' => 1,
            ],
            [
                'question' => [
                    'en' => 'How do I add a new spot?',
                    'nl' => 'Hoe voeg ik een nieuwe plek toe?',
                    'es' => '¿Cómo añado un nuevo lugar?',
                ],
                'answer' => [
                    'en' => '<p>You can suggest a new spot by clicking the "Add Spot" button. Our team and the community will then verify the information.</p>',
                    'nl' => '<p>Je kunt een nieuwe plek voorstellen door op de knop "Plek toevoegen" te klikken. Ons team en de community zullen de informatie vervolgens verifiëren.</p>',
                    'es' => '<p>Puedes sugerir un nuevo lugar haciendo clic en el botón "Añadir lugar". Nuestro equipo y la comunidad verificarán la información.</p>',
                ],
                'category' => 'Spots',
                'sort_order' => 2,
            ],
            [
                'question' => [
                    'en' => 'What are Community Profiles?',
                    'nl' => 'Wat zijn Community Profielen?',
                    'es' => '¿Qué son los Perfiles de Comunidad?',
                ],
                'answer' => [
                    'en' => '<p>Community Profiles show which groups (e.g., Dutch, British, Locals) frequent a spot, helping you find places where you\'ll feel at home.</p>',
                    'nl' => '<p>Community Profielen laten zien welke groepen (bijv. Nederlanders, Britten, Locals) een plek bezoeken, zodat je plekken kunt vinden waar je je thuis voelt.</p>',
                    'es' => '<p>Los Perfiles de Comunidad muestran qué grupos (por ejemplo, holandeses, británicos, locales) frecuentan un lugar, ayudándote a encontrar sitios donde te sientas como en casa.</p>',
                ],
                'category' => 'Community',
                'sort_order' => 3,
            ],
        ];

        foreach ($faqs as $faqData) {
            Faq::create($faqData);
        }
    }
}
