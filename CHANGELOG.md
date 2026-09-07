# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## Unreleased

### Fixed

- Text content split across multiple nodes (e.g. by a comment or a CDATA section) is now concatenated instead of keeping only the last node.
- Non-element child nodes such as processing instructions are now skipped instead of triggering a warning and a fatal `TypeError`.

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
