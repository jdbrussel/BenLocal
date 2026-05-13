# Junie Pro Context Library

## Algemene Instructies
Junie Pro moet ALTIJD eerst de beschikbare documentatie in dit project bekijken voordat er aan een opdracht wordt begonnen of wijzigingen worden doorgevoerd.

## Werkwijze
1. **Documentatie Zoeken**: Zoek bij elke nieuwe taak naar relevante bestanden in de `docs/` map of andere documentatiebestanden (zoals `README.md`).
2. **Context Begrijpen**: Lees de documentatie om de architectuur, standaarden en specifieke vereisten van het project te begrijpen.
3. **Uitvoering**: Pas de opgedane kennis toe bij het uitvoeren van de taak.

## Documentatie Locatie
De primaire documentatie bevindt zich in de `docs/` map in de root van het project.

## Update de Server
git add . ; 
git commit -m "Implementatie Fase 5 & 6: Recommendations, Reviews, Social Interactions & Filament Updates" --trailer

git pull origin main
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate --force
php artisan optimize
php artisan filament:optimize
php artisan queue:restart
