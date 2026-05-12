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
                'title' => ['nl' => 'Hoe BenLocal werkt', 'en' => 'How BenLocal works', 'es' => 'Cómo funciona BenLocal'],
                'content' => ['nl' => 'Content over hoe BenLocal werkt.', 'en' => 'Content about how BenLocal works.'],
            ],
            'what-is-a-local' => [
                'title' => ['nl' => 'Wat is een local?', 'en' => 'What is a local?', 'es' => '¿Qué es un local?'],
                'content' => ['nl' => 'Uitleg over wie we beschouwen als een local.', 'en' => 'Explanation of who we consider a local.'],
            ],
            'hidden-gems' => [
                'title' => ['nl' => 'Hidden Gems', 'en' => 'Hidden Gems', 'es' => 'Joyas ocultas'],
                'content' => ['nl' => 'Ontdek de verborgen parels van Tenerife.', 'en' => 'Discover the hidden gems of Tenerife.'],
            ],
            'community-guidelines' => [
                'title' => ['nl' => 'Community Richtlijnen', 'en' => 'Community Guidelines', 'es' => 'Pautas de la comunidad'],
                'content' => ['nl' => 'Onze regels voor de community.', 'en' => 'Our rules for the community.'],
            ],
            'review-policy' => [
                'title' => ['nl' => 'Review Beleid', 'en' => 'Review Policy', 'es' => 'Política de reseñas'],
                'content' => ['nl' => 'Hoe wij omgaan met reviews.', 'en' => 'How we handle reviews.'],
            ],
            'local-verification-policy' => [
                'title' => ['nl' => 'Local Verificatie Beleid', 'en' => 'Local Verification Policy', 'es' => 'Política de verificación de locales'],
                'content' => ['nl' => 'Hoe wij locals verifiëren.', 'en' => 'How we verify locals.'],
            ],
            'privacy-policy' => [
                'title' => ['nl' => 'Privacybeleid', 'en' => 'Privacy Policy', 'es' => 'Política de privacidad'],
                'content' => ['nl' => 'Uw privacy is belangrijk.', 'en' => 'Your privacy is important.'],
            ],
            'cookie-policy' => [
                'title' => ['nl' => 'Cookiebeleid', 'en' => 'Cookie Policy', 'es' => 'Política de cookies'],
                'content' => ['nl' => 'Informatie over cookies.', 'en' => 'Information about cookies.'],
            ],
            'terms-of-service' => [
                'title' => ['nl' => 'Algemene Voorwaarden', 'en' => 'Terms of Service', 'es' => 'Términos de servicio'],
                'content' => ['nl' => 'Onze algemene voorwaarden.', 'en' => 'Our terms of service.'],
            ],
            'ai-translation-disclaimer' => [
                'title' => ['nl' => 'AI Vertaling Disclaimer', 'en' => 'AI Translation Disclaimer', 'es' => 'Descargo de responsabilidad de traducción de IA'],
                'content' => ['nl' => 'Informatie over automatische vertalingen.', 'en' => 'Information about automatic translations.'],
            ],
            'business-owner-guidelines' => [
                'title' => ['nl' => 'Richtlijnen voor Ondernemers', 'en' => 'Business Owner Guidelines', 'es' => 'Pautas para propietarios de negocios'],
                'content' => ['nl' => 'Informatie voor eigenaren van spots.', 'en' => 'Information for spot owners.'],
            ],
            'safety-and-trust' => [
                'title' => ['nl' => 'Veiligheid en Vertrouwen', 'en' => 'Safety and Trust', 'es' => 'Seguridad y confianza'],
                'content' => ['nl' => 'Hoe wij een veilig platform waarborgen.', 'en' => 'How we ensure a safe platform.'],
            ],
            'about-benlocal' => [
                'title' => ['nl' => 'Over BenLocal', 'en' => 'About BenLocal', 'es' => 'Sobre BenLocal'],
                'content' => ['nl' => 'Het verhaal achter BenLocal.', 'en' => 'The story behind BenLocal.'],
            ],
            'contact' => [
                'title' => ['nl' => 'Contact', 'en' => 'Contact', 'es' => 'Contacto'],
                'content' => ['nl' => 'Neem contact met ons op.', 'en' => 'Contact us.'],
            ],
            'faq' => [
                'title' => ['nl' => 'Veelgestelde Vragen', 'en' => 'FAQ', 'es' => 'Preguntas frecuentes'],
                'content' => ['nl' => 'Antwoorden op veelgestelde vragen.', 'en' => 'Answers to frequently asked questions.'],
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
