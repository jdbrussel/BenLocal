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
                    'en' => 'How do I create an account?',
                    'nl' => 'Hoe maak ik een account aan?',
                    'es' => '¿Cómo creo una cuenta?',
                ],
                'answer' => [
                    'en' => '<p>You can create an account by clicking the sign up button on the home page.</p>',
                    'nl' => '<p>U kunt een account aanmaken door op de registratieknop op de startpagina te klikken.</p>',
                    'es' => '<p>Puede crear una cuenta haciendo clic en el botón de registro en la página de inicio.</p>',
                ],
                'category' => 'Account',
                'sort_order' => 1,
            ],
            [
                'question' => [
                    'en' => 'Is BenLocal free to use?',
                    'nl' => 'Is BenLocal gratis te gebruiken?',
                    'es' => '¿BenLocal es de uso gratuito?',
                ],
                'answer' => [
                    'en' => '<p>Yes, BenLocal is free for all users.</p>',
                    'nl' => '<p>Ja, BenLocal is gratis voor alle gebruikers.</p>',
                    'es' => '<p>Sí, BenLocal es gratuito para todos los usuarios.</p>',
                ],
                'category' => 'General',
                'sort_order' => 2,
            ],
            [
                'question' => [
                    'en' => 'How can I become a Trusted Local?',
                    'nl' => 'Hoe kan ik een Trusted Local worden?',
                    'es' => '¿Cómo puedo convertirme en un Local de Confianza?',
                ],
                'answer' => [
                    'en' => '<p>You need to contribute quality reviews and recommendations to the community.</p>',
                    'nl' => '<p>U moet kwalitatieve reviews en aanbevelingen bijdragen aan de community.</p>',
                    'es' => '<p>Debe contribuir con reseñas y recomendaciones de calidad a la comunidad.</p>',
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
