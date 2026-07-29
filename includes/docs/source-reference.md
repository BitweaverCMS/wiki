# Wiki source reference

> Generated from the current checkout and then intended for human review.
> Paths are relative to the package root.

## Inventory summary

| Artifact | Count |
|---|---:|
| PHP files | 47 |
| Smarty templates | 51 |
| JavaScript files | 0 |
| CSS files | 0 |

## Bootstrap and schema artifacts

- `admin/schema_inc.php`
- `admin/upgrade_inc.php`
- `admin/upgrades/1.0.0.php`
- `admin/upgrades/1.0.1.php`
- `includes/bit_setup_inc.php`

## First-party classes and interfaces

- `admin/pump_wiki_inc.php:58` — `class phpClass() {`
- `includes/classes/BitBook.php:40` — `class BitBook extends BitPage {`
- `includes/classes/BitPage.php:21` — `class BitPage extends LibertyMime implements BitCacheable {`
- `includes/copyrights_lib.php:16` — `class CopyrightsLib extends BitBase {`
- `includes/export_lib.php:21` — `class ExportLib extends BitBase {`
- `includes/plugins_lib.php:158` — `    class PluginsLibUtil {`
- `includes/plugins_lib.php:30` — `    class PluginsLib extends BitBase {`

## Web-facing PHP controllers

- `admin/admin_external_wikis.php`
- `admin/admin_wiki_inc.php`
- `admin/index.php`
- `admin/pump_wiki_inc.php`
- `admin/schema_inc.php`
- `admin/upgrade_inc.php`
- `admin/upgrades/1.0.0.php`
- `admin/upgrades/1.0.1.php`
- `backlinks.php`
- `book_to_html.php`
- `books.php`
- `copyrights.php`
- `edit.php`
- `edit_book.php`
- `export_wiki_pages.php`
- `index.php`
- `like_pages.php`
- `list_pages.php`
- `modules/index.php`
- `modules/mod_comm_received_objects.php`
- `modules/mod_random_pages.php`
- `modules/mod_recent_page_changes.php`
- `modules/mod_top_pages.php`
- `modules/mod_wiki_last_comments.php`
- `orphan_pages.php`
- `page_history.php`
- `page_loader.php`
- `page_watches.php`
- `print_multi_pages.php`
- `print_pages.php`
- `rankings.php`
- `remove_page.php`
- `sitemap.php`
- `slideshow.php`
- `wiki_graph.php`
- `wiki_rss.php`

## Declared schema tables

- `wiki_footnotes`
- `wiki_pages`

## Plugin and module directories

- `liberty_plugins/`
- `modules/`

## Templates

- `modules/help_mod_comm_received_objects.tpl`
- `modules/help_mod_last_modif_pages.tpl`
- `modules/help_mod_random_pages.tpl`
- `modules/help_mod_top_pages.tpl`
- `modules/help_mod_wiki_last_comments.tpl`
- `modules/mod_comm_received_objects.tpl`
- `modules/mod_quick_edit.tpl`
- `modules/mod_random_pages.tpl`
- `modules/mod_recent_page_changes.tpl`
- `modules/mod_search_wiki_page.tpl`
- `modules/mod_top_pages.tpl`
- `modules/mod_wiki_last_comments.tpl`
- `templates/admin_external_wikis.tpl`
- `templates/admin_wiki.tpl`
- `templates/backlinks.tpl`
- `templates/books.tpl`
- `templates/center_wiki_page.tpl`
- `templates/copyrights.tpl`
- `templates/create_book.tpl`
- `templates/create_webhelp.tpl`
- `templates/edit_book.tpl`
- `templates/edit_page.tpl`
- `templates/html_head_inc.tpl`
- `templates/import_phpwiki.tpl`
- `templates/like_pages.tpl`
- `templates/list_books.tpl`
- `templates/list_pages.tpl`
- `templates/menu_wiki.tpl`
- `templates/menu_wiki_admin.tpl`
- `templates/orphan_pages.tpl`
- `templates/page_action_bar.tpl`
- `templates/page_date_bar.tpl`
- `templates/page_display.tpl`
- `templates/page_header.tpl`
- `templates/page_history.tpl`
- `templates/page_icons.tpl`
- `templates/page_permissions.tpl`
- `templates/page_select.tpl`
- `templates/page_watches.tpl`
- `templates/print.tpl`
- `templates/print_multi_pages.tpl`
- `templates/print_pages.tpl`
- `templates/remove_page.tpl`
- `templates/rename_page.tpl`
- `templates/rollback.tpl`
- `templates/show_page.tpl`
- `templates/simple_plugin.tpl`
- `templates/slideshow.tpl`
- `templates/user_watch_wiki_page_changed.tpl`
- `templates/user_watch_wiki_page_comment.tpl`
- `templates/wiki_change_notification.tpl`

## Reading cautions

- Presence in this inventory does not make a file a supported public API.
- Bundled third-party libraries must be distinguished from package-owned code.
- Base schema files do not prove the migration state of a deployed database.
- Controllers may rely on include files, globals, services, and template callbacks not visible from their filename alone.
