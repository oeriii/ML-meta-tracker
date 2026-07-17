Setup (XAMPP)

1. Copy this whole `ml_meta_hero_hub` folder into `htdocs` (e.g. `C:/xampp/htdocs/ml_meta_hero_hub`).
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open **phpMyAdmin** (`http://localhost/phpmyadmin`), create a database if it isn't
   auto-created, then go to **Import** and select `ml_meta_hero_hub.sql`. This creates
   the database `ml_meta_hero_hub` and seeds it with heroes, roles, stats, builds,
   counters, patches, and demo users.
4. Check `config/db.php` — the defaults (`root` / no password / `localhost`) match a
   stock XAMPP install. Update them if your MySQL setup is different.
5. Visit `http://localhost/ml_meta_hero_hub/` in your browser.

## Demo accounts

| Username      | Password    | Role   |
|---------------|-------------|--------|
| admin         | admin123    | Admin  |
| shadowstrike  | player123   | Player |
| junglequeen   | player123   | Player |

## Pages

- `login.php` / `logout.php` — session-based auth
- `dashboard.php` — Meta Board: filter heroes by role, rank, patch; search by name; sort by win/pick/ban rate
- `hero_detail.php` — per-hero stats across rank brackets, recommended builds, and counter matchups
- `favorites.php` — the logged-in user's saved heroes
- `patches.php` — patch note history
- `toggle_favorite.php` — add/remove a hero from favorites (POST-only, redirects back)

## Notes

- Hero portrait images referenced in `heroes.image_url` (`assets/img/heroes/*.png`) are
  not included — drop your own hero art in `assets/img/heroes/` using those filenames,
  or swap in placeholder art.
- Passwords are stored in plain text in the seed data to match the existing schema.
  A natural "future enhancement" for the docs is switching to `password_hash()` /
  `password_verify()`.
