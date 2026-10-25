<?php
define('ABSPATH', __DIR__);
define('HOUR_IN_SECONDS', 3600);
define('DAY_IN_SECONDS', 86400);
$cache = []; $calls = 0;
$reply = ['status' => 200, 'body' => json_encode(['jobs' => [
    ['id' => 1, 'url' => 'https://jobicy.com/jobs/1', 'jobTitle' => '<script>alert(1)</script>Engineer'],
    ['id' => 2, 'url' => 'https://example.com/bad']
]])];
function absint($v) { return abs((int) $v); }
function sanitize_text_field($v) { return trim(strip_tags($v)); }
function sanitize_key($v) { return preg_replace('/[^a-z0-9_-]/', '', strtolower($v)); }
function wp_json_encode($v) { return json_encode($v); }
function get_transient($k) { global $cache; return $cache[$k] ?? false; }
function set_transient($k, $v, $ttl) { global $cache; $cache[$k] = $v; }
function add_query_arg($q, $url) { return $url . '?' . http_build_query($q); }
function wp_safe_remote_get($url, $args) { global $calls, $reply; $calls++; return $reply; }
function is_wp_error($v) { return false; }
function wp_remote_retrieve_response_code($v) { return $v['status']; }
function wp_remote_retrieve_body($v) { return $v['body']; }
function wp_remote_retrieve_header($v, $k) { return $v[$k] ?? ''; }
function wp_parse_url($v) { return parse_url($v); }
function shortcode_atts($defaults, $attrs, $tag) { return array_merge($defaults, $attrs); }
function wp_enqueue_style(...$args) {}
function __($v, $domain) { return $v; }
function esc_html($v) { return htmlspecialchars($v, ENT_QUOTES); }
function esc_attr__($v, $domain) { return esc_html($v); }
function esc_html__($v, $domain) { return esc_html($v); }
function esc_url($v) { return htmlspecialchars($v, ENT_QUOTES); }
function wp_strip_all_tags($v) { return strip_tags($v); }
function wp_trim_words($v, $n, $suffix) { return $v; }
function number_format_i18n($v) { return number_format($v); }
function check($condition, $message) { if (!$condition) throw new RuntimeException($message); }
require __DIR__ . '/../jobicy-remote-jobs-widget/includes/class-jobicy-api-client.php';
require __DIR__ . '/../jobicy-remote-jobs-widget/includes/class-jobicy-jobs-shortcode.php';
$client = new Jobicy_Remote_Jobs_Api_Client();
$first = $client->get_jobs(['count' => 10]);
check(count($first['jobs']) === 1, 'Reject noncanonical URLs');
$client->get_jobs(['count' => 10]);
check($calls === 1, 'Fresh cache suppresses HTTP');
foreach (array_keys($cache) as $key) if (strpos($key, 'jobicy_jobs_') === 0) unset($cache[$key]);
$reply = ['status' => 429, 'body' => '{}', 'retry-after' => '7200'];
$stale = $client->get_jobs(['count' => 10]);
check($stale['stale'] === true && count($stale['jobs']) === 1, '429 uses stale cached data');
$client->get_jobs(['count' => 10]);
check($calls === 2, '429 backoff suppresses another request');
$shortcode = new Jobicy_Remote_Jobs_Shortcode($client, '/style.css');
$html = $shortcode->render(['count' => '10']);
check(strpos($html, '<script>') === false, 'Untrusted title is escaped');
check(strpos($html, 'Jobs powered by Jobicy') !== false, 'Attribution is visible');
check(strpos($html, 'https://jobicy.com/jobs/1') !== false, 'Canonical link is preserved');
$cache = []; $reply = ['status' => 200, 'body' => '{broken'];
check($client->get_jobs([])['error'] !== '', 'Malformed JSON is an error');
$reply = ['status' => 200, 'body' => '{"jobs":[]}'];
check($client->get_jobs([])['error'] === '', 'Empty feed is valid');
echo "9 widget assertions passed\n";
