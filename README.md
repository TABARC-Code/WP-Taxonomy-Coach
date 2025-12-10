<p align="center">
  <img src=".branding/tabarc-icon.svg" width="180" alt="TABARC-Code Icon">
</p>

# WP Taxonomy Coach

A small diagnostics tool that helps me understand the health of my WordPress categories and tags. Over time, most sites collect taxonomy clutter. This plugin gives me a simple dashboard that flags the common issues so I can decide what to clean up. etc

## What it detects

- **Unused categories and tags**
- **Tags used by only one post**
- **Duplicate-looking terms** (very simple exact-match normalisation)
- **Total taxonomy counts** so I can see growth over time

This does not modify your data. It simply shows what may need attention.

## Why it exists

Most WordPress sites end up with:

- Dozens of tags that only appear once
- Empty categories from old drafts or imports
- Duplicate terms like “news” and “News”
- Legacy taxonomy debris no one remembers creating

This plugin surfaces all of that in one page.

## Requirements

- WordPress 5.0+
- PHP 7.4+ recommended
- Access to Tools → Taxonomy Coach

## Installation

```bash
git clone https://github.com/TABARC-Code/wp-taxonomy-coach.git
Place into:

text
Copy code
wp-content/plugins/wp-taxonomy-coach
Activate it in Plugins, then visit:

nginx
Copy code
Tools → Taxonomy Coach
Roadmap
I plan to add:

Term merging

Near-duplicate detection (similarity scoring)

Term usage visualisation

Bulk delete interface for unused terms
