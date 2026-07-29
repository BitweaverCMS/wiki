# Wiki package documentation

> Engineering documentation derived from the source in this package. The
> package's `includes/` directory must be denied to direct HTTP requests.

## Purpose

Wiki provides collaborative pages, revision-aware editing, links, structures, and wiki-oriented presentation.

## Responsibility

Owns wiki page behavior and controllers while delegating common content persistence to Liberty.

## Dependencies

kernel, liberty, users, themes, languages.

Dependency direction matters: this package may depend on the packages above;
the dependencies do not thereby depend on this package.

## Boundary

Does not own the Liberty content engine, user identities, or global template resolution.

## Documentation map

- [Architecture](architecture.md) — initialization, components, and request flow.
- [Source reference](source-reference.md) — source-derived files, classes,
  controllers, schema artifacts, plugins, and templates.
- [Development guide](development.md) — safe change workflow, extension points,
  validation, and maintenance guidance.
- [Security](security.md) — trust boundaries and direct-HTTP access requirements.
- [Wiki page lifecycle](wiki-page-lifecycle.md) — page identity, editing,
  links, history, locks, books/structures, footnotes, and permissions.
