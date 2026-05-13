<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'how-benlocal-works' => [
                'title' => [
                    'nl' => 'Hoe BenLocal werkt',
                    'en' => 'How BenLocal works',
                    'es' => 'Cómo funciona BenLocal'
                ],
                'content' => [
                    'nl' => 'Ontdek hoe wij lokale aanbevelingen verzamelen en verifiëren.',
                    'en' => 'Discover how we collect and verify local recommendations.',
                    'es' => 'Descubre cómo recopilamos y verificamos las recomendaciones locales.'
                ],
            ],
            'hidden-gems' => [
                'title' => [
                    'nl' => 'Verborgen parels',
                    'en' => 'Hidden Gems',
                    'es' => 'Joyas ocultas'
                ],
                'content' => [
                    'nl' => 'Wat is een verborgen parel? Plekken die geliefd zijn bij locals maar nog niet ontdekt door de massa.',
                    'en' => 'What is a hidden gem? Places loved by locals but not yet discovered by the masses.',
                    'es' => '¿Qué es una joya oculta? Lugares amados por los locales pero aún no descubiertos por las masas.'
                ],
            ],
            'privacy-policy' => [
                'title' => [
                    'nl' => 'Privacybeleid',
                    'en' => 'Privacy Policy',
                    'es' => 'Política de privacidad'
                ],
                'content' => [
                    'nl' => 'Wij gaan zorgvuldig om met uw gegevens.',
                    'en' => 'We handle your data with care.',
                    'es' => 'Tratamos sus datos con cuidado.'
                ],
            ],
            'cookie-policy' => [
                'title' => [
                    'nl' => 'Cookiebeleid',
                    'en' => 'Cookie Policy',
                    'es' => 'Política de cookies'
                ],
                'content' => [
                    'nl' => 'Informatie over hoe wij cookies gebruiken.',
                    'en' => 'Information about how we use cookies.',
                    'es' => 'Información sobre cómo utilizamos las cookies.'
                ],
            ],
        ];

        foreach ($pages as $slug => $data) {
            Page::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'is_system_page' => true,
                    'published_at' => now(),
                ]
            );
        }
    }
}
