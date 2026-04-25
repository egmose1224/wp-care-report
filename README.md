# WP Care Report

WP Care Report is a lightweight WordPress plugin that generates a practical maintenance, handover, and technical health report inside wp-admin.

It is built for freelancers, agencies, and site owners who need a clear overview of common WordPress update, security, SEO, maintenance, and environment concerns.

## Features

- Overall site care score from 0 to 100.
- Checks grouped by updates, security basics, SEO basics, maintenance readiness, and environment.
- Actionable recommendation for every finding.
- Care priorities that turn findings into a practical "Fix now / Schedule / Monitor" plan.
- Admin-only environment setting for production, staging, development, and legacy sites.
- Clear wp-admin banner for staging, development, and legacy environments.
- Markdown copy and download export.
- Basic WordPress Site Health integration.
- No external services, no tracking, and no automatic site changes.

## Screenshots

Screenshots will be added once the first public release is tagged.

## Installation

1. Download or clone this repository.
2. Copy the `wp-care-report` folder into `wp-content/plugins/`.
3. Activate **WP Care Report** in WordPress.
4. Open **Tools > WP Care Report**.

## Local Development

Requirements:

- PHP 8.0 or newer.
- WordPress 6.2 or newer.
- WP-CLI for the included demo workflow.
- MySQL/MariaDB locally, or Docker for the demo database.

Run a syntax check:

```powershell
Get-ChildItem -Recurse -Filter *.php -File |
  Where-Object { $_.FullName -notmatch '\\.demo-wp\\' } |
  ForEach-Object { php -l $_.FullName }
```

Build a release ZIP:

```powershell
powershell -ExecutionPolicy Bypass -File tools/build-release.ps1
```

The ZIP is written to `.dist/` and is ignored by git.

## WP-CLI Demo Setup

The local demo install lives in `.demo-wp/`, which is ignored by git.

Typical setup:

```powershell
wp core download --path=.demo-wp
wp config create --path=.demo-wp --dbname=wp_care_report_demo --dbuser=root --dbpass= --dbhost=127.0.0.1
wp db create --path=.demo-wp
wp core install --path=.demo-wp --url=http://localhost:8080 --title="WP Care Report Demo" --admin_user=admin --admin_password=password --admin_email=admin@example.com
wp plugin activate wp-care-report --path=.demo-wp
wp server --path=.demo-wp --host=127.0.0.1 --port=8080
```

If no local database is available, use Docker to run a MariaDB container and point `wp config create` to that database host and port.

## Security and Privacy

WP Care Report runs locally inside WordPress. It does not send report data to third-party services, does not track users, and does not automatically change settings. Findings are informational and should be reviewed by a site owner or maintainer before action is taken.

## Roadmap

- Improve check coverage based on real user feedback.
- Add screenshots and WordPress.org assets.
- Add optional export metadata for client handover workflows.
- Add automated test coverage around scoring and markdown export.
- Improve Site Health coverage without duplicating the full report.

## Contributing

Issues and pull requests are welcome. Keep contributions small, practical, and aligned with WordPress coding and security expectations. Do not add external service dependencies or telemetry.

## License

MIT. See [LICENSE](LICENSE).
