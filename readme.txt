=== WP Care Report ===
Contributors: wp-care-report-contributors
Tags: maintenance, site health, security, seo, reports
Requires at least: 6.2
Tested up to: 6.9
Requires PHP: 8.0
Stable tag: 0.1.0
License: MIT
License URI: https://opensource.org/licenses/MIT

Generate a practical WordPress maintenance, handover, and technical health report from wp-admin.

== Description ==

WP Care Report helps freelancers, agencies, and site owners review common WordPress maintenance, security, SEO, and environment basics.

The plugin adds a report under Tools > WP Care Report with:

* Overall site care score from 0 to 100.
* Grouped checks for updates, security basics, SEO basics, maintenance readiness, and environment status.
* Actionable recommendations for each finding.
* Care priorities that turn findings into a practical next-action plan.
* Markdown copy and download export.
* Basic WordPress Site Health integration.
* Admin-only environment banners for staging, development, and legacy sites.

WP Care Report does not send data to external services and does not change site settings automatically.

== Installation ==

1. Upload the `wp-care-report` folder to `/wp-content/plugins/`.
2. Activate the plugin through the Plugins screen in WordPress.
3. Go to Tools > WP Care Report.
4. Select the correct environment and review the generated report.

== Frequently Asked Questions ==

= Does this plugin fix issues automatically? =

No. WP Care Report only reports findings and recommendations. It does not change WordPress settings, update plugins, edit files, or write to `wp-config.php`.

= Does this plugin send site data anywhere? =

No. The report is generated locally inside WordPress.

= Why does my local demo show SSL as critical? =

Local development sites often run on HTTP. For public production sites, WordPress home and site URLs should use HTTPS.

= Can I export the report? =

Yes. The admin page includes copy and download buttons for a Markdown report.

== Changelog ==

= 0.1.0 =

* Initial MVP release.
* Added report scoring, grouped checks, care priorities, environment setting, admin banner, Markdown export, and basic Site Health integration.
