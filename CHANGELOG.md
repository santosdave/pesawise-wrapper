# Changelog

All notable changes to `pesawise-wrapper` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.3] - 2024-09-26

### Changed
- Updated PesawiseProvider to use the config file correctly
- Modified Pesawise class to accept configuration options more flexibly
- Updated README with clearer usage instructions and examples

### Fixed
- Inconsistency between README instructions and actual package implementation

## [1.0.2] - 2024-09-25

### Added
- Configuration file `config/pesawise.php`
- Updated `PesawiseProvider` to correctly load and publish config

### Fixed
- Issue with missing configuration file

## [1.0.1] - 2024-09-25

### Changed
- Updated composer.json to use stable versions of dependencies
- Moved PHPUnit to require-dev section
- Added support for multiple Laravel versions

## [1.0.0] - 2024-09-25

### Added
- Initial release of the Pesawise wrapper package
- Support for all main Pesawise API endpoints
- Laravel service provider for easy integration
- Comprehensive test suite
- README with installation and usage instructions