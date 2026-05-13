<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'how-it-works',
                'title' => [
                    'en' => 'How BenLocal Works',
                    'nl' => 'Hoe BenLocal Werkt',
                    'es' => 'Cómo funciona BenLocal',
                ],
                'content' => [
                    'en' => '<p>BenLocal is a community-driven platform for discovering hidden gems.</p>',
                    'nl' => '<p>BenLocal is een community-gedreven platform voor het ontdekken van verborgen parels.</p>',
                    'es' => '<p>BenLocal es una plataforma impulsada por la comunidad para descubrir joyas ocultas.</p>',
                ],
                'is_system_page' => true,
            ],
            [
                'slug' => 'what-is-a-local',
                'title' => [
                    'en' => 'What is a Local?',
                    'nl' => 'Wat is een Local?',
                    'es' => '¿Qué es un Local?',
                ],
                'content' => [
                    'en' => '<p>A local is someone who knows the area best.</p>',
                    'nl' => '<p>Een local is iemand die de omgeving het beste kent.</p>',
                    'es' => '<p>Un local es alguien que conoce mejor la zona.</p>',
                ],
                'is_system_page' => true,
            ],
            [
                'slug' => 'hidden-gems',
                'title' => [
                    'en' => 'Hidden Gems',
                    'nl' => 'Verborgen Parels',
                    'es' => 'Joyas Ocultas',
                ],
                'content' => [
                    'en' => '<p>Discover spots that aren\'t in the typical guidebooks.</p>',
                    'nl' => '<p>Ontdek plekken die niet in de typische reisgidsen staan.</p>',
                    'es' => '<p>Descubre lugares que no aparecen en las guías típicas.</p>',
                ],
                'is_system_page' => true,
            ],
            [
                'slug' => 'community-guidelines',
                'title' => [
                    'en' => 'Community Guidelines',
                    'nl' => 'Community Richtlijnen',
                    'es' => 'Pautas de la comunidad',
                ],
                'content' => [
                    'en' => '<p>Be respectful and helpful to others.</p>',
                    'nl' => '<p>Wees respectvol en behulpzaam naar anderen.</p>',
                    'es' => '<p>Sea respetuoso y servicial con los demás.</p>',
                ],
                'is_system_page' => true,
            ],
            [
                'slug' => 'review-policy',
                'title' => [
                    'en' => 'Review Policy',
                    'nl' => 'Reviewbeleid',
                    'es' => 'Política de reseñas',
                ],
                'content' => [
                    'en' => '<p>Reviews should be honest and based on personal experience.</p>',
                    'nl' => '<p>Reviews moeten eerlijk zijn en gebaseerd op persoonlijke ervaring.</p>',
                    'es' => '<p>Las reseñas deben ser honestas y basarse en la experiencia personal.</p>',
                ],
                'is_system_page' => true,
            ],
            [
                'slug' => 'local-verification-policy',
                'title' => [
                    'en' => 'Local Verification Policy',
                    'nl' => 'Local Verificatiebeleid',
                    'es' => 'Política de verificación local',
                ],
                'content' => [
                    'en' => '<p>How we verify our trusted locals.</p>',
                    'nl' => '<p>Hoe we onze vertrouwde locals verifiëren.</p>',
                    'es' => '<p>Cómo verificamos a nuestros locales de confianza.</p>',
                ],
                'is_system_page' => true,
            ],
            [
                'slug' => 'privacy-policy',
                'title' => [
                    'en' => 'Privacy Policy',
                    'nl' => 'Privacybeleid',
                    'es' => 'Política de privacidad',
                    'de' => 'Datenschutzerklärung',
                    'fr' => 'Politique de confidentialité',
                ],
                'content' => [
                    'en' => '<p>We value your privacy.</p>',
                    'nl' => '<p>Wij waarderen uw privacy.</p>',
                    'es' => '<p>Valoramos su privacidad.</p>',
                    'de' => '<p>Wir schätzen Ihre Privatsphäre.</p>',
                    'fr' => '<p>Nous accordons de l\'importance à votre vie privée.</p>',
                ],
                'is_system_page' => true,
            ],
            [
                'slug' => 'cookie-policy',
                'title' => [
                    'en' => 'Cookie Policy',
                    'nl' => 'Cookiebeleid',
                    'es' => 'Política de cookies',
                ],
                'content' => [
                    'en' => '<p>Information about the cookies we use.</p>',
                    'nl' => '<p>Informatie over de cookies die we gebruiken.</p>',
                    'es' => '<p>Información sobre las cookies que utilizamos.</p>',
                ],
                'is_system_page' => true,
            ],
            [
                'slug' => 'terms-of-service',
                'title' => [
                    'en' => 'Terms of Service',
                    'nl' => 'Algemene Voorwaarden',
                    'es' => 'Términos de servicio',
                ],
                'content' => [
                    'en' => '<p>Legal terms for using BenLocal.</p>',
                    'nl' => '<p>Juridische voorwaarden voor het gebruik van BenLocal.</p>',
                    'es' => '<p>Términos legales para usar BenLocal.</p>',
                ],
                'is_system_page' => true,
            ],
            [
                'slug' => 'ai-translation-disclaimer',
                'title' => [
                    'en' => 'AI Translation Disclaimer',
                    'nl' => 'AI Vertalingsdisclaimer',
                    'es' => 'Descargo de responsabilidad de traducción de IA',
                ],
                'content' => [
                    'en' => '<p>Content may be translated using AI.</p>',
                    'nl' => '<p>Inhoud kan vertaald zijn met behulp van AI.</p>',
                    'es' => '<p>El contenido puede ser traducido usando IA.</p>',
                ],
                'is_system_page' => true,
            ],
            [
                'slug' => 'business-owner-guidelines',
                'title' => [
                    'en' => 'Business Owner Guidelines',
                    'nl' => 'Richtlijnen voor Bedrijfseigenaren',
                    'es' => 'Directrices para propietarios de negocios',
                ],
                'content' => [
                    'en' => '<p>How business owners can interact with the community.</p>',
                    'nl' => '<p>Hoe bedrijfseigenaren kunnen communiceren met de community.</p>',
                    'es' => '<p>Cómo los dueños de negocios pueden interactuar con la comunidad.</p>',
                ],
                'is_system_page' => true,
            ],
            [
                'slug' => 'safety-trust',
                'title' => [
                    'en' => 'Safety & Trust',
                    'nl' => 'Veiligheid & Vertrouwen',
                    'es' => 'Seguridad y confianza',
                ],
                'content' => [
                    'en' => '<p>De community veilig houden.</p>',
                    'nl' => '<p>De community veilig houden.</p>',
                    'es' => '<p>Mantener segura a la comunidad.</p>',
                ],
                'is_system_page' => true,
            ],
            [
                'slug' => 'about',
                'title' => [
                    'en' => 'About BenLocal',
                    'nl' => 'Over BenLocal',
                    'es' => 'Sobre BenLocal',
                ],
                'content' => [
                    'en' => '<p>The story behind BenLocal.</p>',
                    'nl' => '<p>Het verhaal achter BenLocal.</p>',
                    'es' => '<p>La historia detrás de BenLocal.</p>',
                ],
                'is_system_page' => true,
            ],
            [
                'slug' => 'contact',
                'title' => [
                    'en' => 'Contact Us',
                    'nl' => 'Contacteer Ons',
                    'es' => 'Contáctenos',
                ],
                'content' => [
                    'en' => '<p>Get in touch with the team.</p>',
                    'nl' => '<p>Neem contact op met het team.</p>',
                    'es' => '<p>Póngase en contacto con el equipo.</p>',
                ],
                'is_system_page' => true,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                array_merge($pageData, ['published_at' => now()])
            );
        }
    }
}
