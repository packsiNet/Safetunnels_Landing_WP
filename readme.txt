=== SafeTunnels ===
Contributors: zanatech
Tags: one-column, custom-logo, custom-menu, featured-images, full-width-template, translation-ready
Requires at least: 6.2
Tested up to: 6.7
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A conversion-focused, SEO-hardened WordPress theme for SafeTunnels — a full
port of the React /tunnels landing page.

== Description ==

This theme reproduces the SafeTunnels marketing site — hero, features,
device downloads, the Iranian app catalogue, live reviews, pricing with
RevenueCat hosted checkout, FAQ and every legal/support page — as a
self-contained WordPress theme with no page-builder or SEO plugin required.

== Installation ==

1. Upload the `safetunnels` folder to `wp-content/themes/`.
2. Activate the theme under Appearance → Themes. Activation automatically
   creates every page the theme needs (Home, Download, Pricing, Iranian
   Apps, FAQ, Support, Contact, Privacy, Terms, Refund Policy, Cookie
   Policy, Payment Result), assigns their templates, builds the primary
   navigation menu, and sets the front page.
3. Open Appearance → Customize → SafeTunnels and fill in:
   - Identity: app name, brand name, developer name, support email.
   - Backend & Tracking: the SafeTunnels API base URL (defaults to
     https://core.safetunnels.net). Leave "Proxy API calls through WordPress"
     enabled so the browser never needs a CORS exception on the API.
   - Download: the direct APK URL and Android package name.
   - Pricing & Checkout: each billing period's price and its RevenueCat
     hosted Purchase Link + package id, copied from the RevenueCat
     dashboard's Offerings screen.
4. If any page is later deleted by accident, go to Appearance →
   SafeTunnels Pages and click "Regenerate pages & menu" — existing
   content is never overwritten.

== Notes ==

- No SEO plugin is required: titles, meta descriptions, canonicals, Open
  Graph/Twitter tags, and a full JSON-LD graph (Organization, WebSite,
  SoftwareApplication with AggregateRating, BreadcrumbList, FAQPage,
  ItemList) are built in. If Yoast, Rank Math, SEOPress or AIOSEO is
  active, the theme's own output steps aside automatically.
- The reviews section and star rating are rendered server-side from the
  SafeTunnels API (cached briefly), so they are present in the page
  source search engines see, not only injected by JavaScript.
- The purchase flow — paste account key → verify → redirect to the
  RevenueCat hosted checkout — matches the original React app exactly.

== Changelog ==

= 1.0.0 =
* Initial release.
