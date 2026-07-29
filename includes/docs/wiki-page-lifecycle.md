# Wiki page lifecycle

## Package identity

The checkout directory is `help`; the package is `wiki`. `BitPage` is the main
content class and extends `LibertyMime`, so body, title, history, permissions,
attachments, parsing, aliases, and caching build on Liberty.

## Tables

- `wiki_pages` — package `page_id`, shared `content_id`, cached size, edit
  comment, and flag/lock state.
- `wiki_footnotes` — user-specific notes keyed by user and page.

The unique index on `wiki_pages.content_id` enforces one Wiki row per Liberty
content object.

## Lookup and identity

Controllers can identify pages by `page_id`, `content_id`, or page name.
`findByPageName()`, `lookupObject()`, `pageExists()`, and
`findContentIdByPageId()` centralize those translations.

Page-name normalization, aliases, case sensitivity, and duplicate-name
configuration affect lookup. Do not reproduce page-name SQL in controllers.

## Store flow

`BitPage::verify()` validates Wiki fields and delegates shared content
verification. `store()` persists Liberty and `wiki_pages` state, edit comments,
link information, and cache/history consequences.

A safe edit:

1. Resolve the canonical page and verify view/edit permission.
2. Verify lock ownership/override.
3. Validate challenge/CSRF state.
4. Verify format/body/title through Liberty.
5. Persist a new version and edit comment.
6. Refresh links/backlinks and invalidate caches.
7. Redirect using the object's display URL.

## Links and backlinks

Wiki parsing records links through Liberty content-link infrastructure.
`getBacklinks()`, link structure methods, and rename/update behavior depend on
canonical titles. Renaming must preserve or intentionally update incoming
links/aliases; direct title updates can create broken links.

Unresolved page names are not authorization-safe identifiers. Creating a page
from a red link still requires create permission.

## Locks

`isLocked()`, `setLock()`, `lock()`, and `unlock()` control edit locking.
Lock state reduces edit collisions but does not replace update permission or
challenge validation. Administrator override must be explicit and auditable.

## History and rollback

Wiki uses Liberty history. `BitPage::rollbackVersion()` adds Wiki-specific
behavior around the shared rollback. History can contain content no longer
public; enforce history-view and page-view permissions.

## Books and structures

Wiki books use Liberty structures to arrange pages hierarchically.
Book permissions distinguish create/update/admin operations. Structure movement
must use Liberty structure APIs so parent, root, level, and position remain
consistent.

## Footnotes

Footnotes are per user/page. `storeFootnote()`, `getFootnote()`, and
`expungeFootnote()` must use the current authorized identity. They are private
user data unless a calling workflow explicitly states otherwise.

## Caching

`BitPage` implements `BitCacheable`. Edits, rollback, rename, parser/filter
configuration, service output, and permission-sensitive rendering can require
invalidation. Never share cached protected output with unauthorized users.

## Testing

- Lookup by ID/name/alias and case behavior.
- Create/edit with each configured format.
- Concurrent locks and administrator override.
- Rename with backlinks and aliases.
- History display and rollback permissions.
- Book navigation/movement.
- User footnote isolation.
- Cache invalidation after edit and permission change.
