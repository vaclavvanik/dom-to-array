# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.2.0 - 2026-09-07

### Added

- `DomOptions::KEEP_MIXED_CONTENT` option to keep the text of an element that also has child elements (under the `@value` key) instead of dropping the child elements.
- `DomOptions::OMIT_ROOT_ELEMENT` option to return the root element children directly instead of wrapping them in the root element name.
- `DomOptions::USE_ATTRIBUTE_NODE_NAME` option to key attributes by their full node name (with namespace prefix) instead of the local name.
- Tested against PHP 7.3 up to PHP 8.5 on a CI matrix.
- Test suite runs on PHPUnit 9.6, 10.5 and 11.5, selected automatically per PHP version.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Text content split across multiple nodes (e.g. by a comment or a CDATA section) is now concatenated instead of keeping only the last node.
- Non-element child nodes such as processing instructions are now skipped instead of triggering a warning and a fatal `TypeError`.
- `DomOptions::fromArray` casts `skip_attributes` to bool instead of throwing a `TypeError` on a non-bool value.

## 1.1.0 - 2025-05-23

Skip converting XML comments.

## 1.0.0 - 2023-10-31

First stable release and first release as `dom-to-array`.

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.
