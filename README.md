# Jobicy Remote Jobs Widget

A self-contained WordPress plugin that renders cached Jobicy remote jobs using a shortcode.

## Install

1. Download this repository, then run `python3 build.py` to create `dist/jobicy-remote-jobs-widget.zip`. Alternatively, download the `jobicy-wordpress-plugin` artifact from a successful GitHub Actions run, unzip that artifact wrapper, and use the plugin ZIP inside.
2. In WordPress, open **Plugins → Add New Plugin → Upload Plugin**.
3. Upload the archive, install it, and activate **Jobicy Remote Jobs Widget**.
4. Add the shortcode to a page, post, shortcode block, or shortcode-compatible widget.

```text
[jobicy_jobs]
[jobicy_jobs count="10" geo="usa" industry="engineering"]
[jobicy_jobs count="5" geo="canada" tag="python"]
[jobicy_jobs count="12" industry="marketing"]
```

| Attribute | Default | Meaning |
| --- | --- | --- |
| `count` | `10` | Number of jobs, clamped to the widget display range of 1–100 |
| `geo` | Empty | Official Jobicy location slug |
| `industry` | Empty | Official Jobicy category slug |
| `tag` | Empty | Official Jobicy keyword search |

Discover current [location slugs](https://jobicy.com/api/jobs?get=locations) and [industry slugs](https://jobicy.com/api/jobs?get=industries).

The widget intentionally requests one page of recent listings; it does not crawl the entire seven-day feed. The API allows up to 200 jobs per page and supports cursors for full-feed integrations.

[OpenAPI JSON](https://jobicy.com/api/openapi.json) · [OpenAPI YAML](https://jobicy.com/api/openapi.yaml)

Requests use the WordPress HTTP API with a 12-second timeout. Successful responses are cached with WordPress transients for one hour, and a separate 24-hour stale cache is used if Jobicy becomes temporarily unavailable. Input is sanitized, displayed values are escaped, duplicate IDs are removed, canonical Jobicy URLs are validated, and no direct database calls are used.

The plugin requires WordPress 6.0+ and PHP 7.4+. It preserves visible **[Jobs powered by Jobicy](https://jobicy.com/)** attribution and links every job to its original Jobicy listing.

## Stored listings

If you extend this integration to retain listings, use the [batch status endpoint](https://github.com/Jobicy/remote-jobs-api#batch-status-check) to check up to 100 IDs at a time. Older open jobs can remain `active` after leaving the seven-day feed. Do not mark jobs closed just because a fresh feed omits them, or because a check returns `unknown` or fails. The sample does not automatically reconcile stored listings.

## Development checks

```bash
find jobicy-remote-jobs-widget tests -name '*.php' -exec php -l {} \;
php tests/widget.php
python3 build.py
```

The checks execute PHP with small WordPress adapters to verify caching, backoff, URL filtering, escaping, empty data and malformed responses. They do not boot a complete WordPress installation. Verify plugin activation, theme styling and shortcode rendering on a staging WordPress site before using it publicly.

## External service and data

The plugin contacts `https://jobicy.com/api/jobs` on a cache miss to retrieve public jobs, with only the configured count, location, category and keyword filters. No Jobicy account or key is required. The request passes through WordPress's HTTP API; Jobicy receives the server's normal HTTP connection information. The plugin does not send visitor accounts, form submissions or WordPress content. See [Jobicy API documentation and fair-use terms](https://github.com/Jobicy/remote-jobs-api#fair-use).

Successful responses are cached per filter combination. The widget shows a limited recent page and does not maintain an archival job database. Salary amounts without a disclosed currency are omitted rather than assigned an assumed currency.

[All integrations](https://github.com/Jobicy/jobicy-api-examples) · [MIT license](LICENSE) · [Data terms](PROVENANCE.md)
