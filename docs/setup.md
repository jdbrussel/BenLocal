# Project Setup

Dit project is opgezet met Laravel v12 en Filament v5.

## Technologie Stack
- **Framework**: Laravel 12.0.0
- **Admin Panel**: Filament v5.0.0
- **Frontend**: Livewire v4.0.0
- **Database**: SQLite (default)

## Installatie Details
De installatie is uitgevoerd op 12 mei 2026. Vanwege de vroege fase van Laravel 12 en Filament 5 in deze tijdlijn, zijn sommige configuraties handmatig toegepast.

### Belangrijke Bestanden
- `app/Providers/Filament/AdminPanelProvider.php`: De centrale plek voor Filament configuratie.
- `bootstrap/app.php`: Hier is de Filament Provider geregistreerd via `->withProviders()`.
- `app/Models/User.php`: Het User model implementeert `FilamentUser` voor toegang tot het admin panel.

## Gebruik
Het admin panel is bereikbaar via `/admin`.

### Gebruiker aanmaken
Hoewel `php artisan make:filament-user` beschikbaar zou moeten zijn, kan een gebruiker ook handmatig in de database worden aangemaakt met het `User` model.

## Server Updates
Om de externe productieserver bij te werken met de nieuwste code van de `main` branch, gebruik de volgende stappen op de server:

1. **Navigeer naar de projectmap**:
   ```bash
   cd /path/to/benlocal
   ```

2. **Haal de nieuwste wijzigingen op**:
   ```bash
   git pull origin main
   ```

3. **Installeer/update dependencies**:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   ```

4. **Voer database migraties uit**:
   ```bash
   php artisan migrate --force
   ```

5. **Optimaliseer de applicatie**:
   ```bash
   php artisan optimize
   php artisan filament:optimize
   ```

6. **Herstart wachtrijen (indien van toepassing)**:
   ```bash
   php artisan queue:restart
   ```
