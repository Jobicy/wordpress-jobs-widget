=== Jobicy Remote Jobs Widget ===
Contributors: jobicy
Tags: remote jobs, jobs, jobicy, shortcode
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 0.1.0
License: MIT

Display recent remote jobs with the [jobicy_jobs] shortcode. No API key required.

== Installation ==
Upload the plugin ZIP through Plugins > Add New Plugin > Upload Plugin, activate it and add [jobicy_jobs] to a shortcode block.

== External service ==
This plugin requests public job listings from https://jobicy.com/api/jobs when the hourly filter cache expires. Requests include the configured count and optional geo, industry and tag filters. No visitor or WordPress account information is sent by this plugin. Jobicy receives the server's ordinary HTTP connection information.
API documentation and fair-use terms: https://github.com/Jobicy/remote-jobs-api#fair-use

Every listing links to Jobicy and displays Jobs powered by Jobicy attribution.
