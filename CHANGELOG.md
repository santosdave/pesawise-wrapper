# Changelog

All notable changes to `pesawise-wrapper` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.3.0] - 2024-09-29

### Added
- New request classes for all Pesawise API endpoints:
  - `BankRecipientValidationRequest`
  - `BulkPaymentRequest`
  - `CompletePaymentRequest`
  - `CreatePaymentOrderRequest`
  - `CreateWalletRequest`
  - `DirectPaymentRequest`
  - `GetPaymentFeeRequest`
  - `MpesaPaymentRequest`
  - `PesalinkPaymentRequest`
  - `ResendOtpRequest`
  - `StkPushRequest`
- Updated `BulkPayment` data type to include all fields specified in the API documentation
- Enhanced `TransferType` class with all possible transfer types

### Changed
- Updated `Pesawise` class to use new request classes for all API calls
- Improved validation logic in `ValidationTrait`
- Enhanced type safety and parameter validation across all classes

## [1.2.1] - 2024-09-28

### Added
- New data type classes for improved type safety and code clarity:
  - `TransferType`
  - `Currency`
  - `PaymentStatus`
  - `BulkPayment`
  - `CustomerData`

### Changed
- Updated `Pesawise` class to use new data type classes in its methods
- Improved type hinting and return types in `Pesawise` class methods

## [1.2.0] - 2024-09-27

### Changed
- Updated `Pesawise` class to always create a new Guzzle Client instance with correct configuration
- Modified `getAllSupportedBanks` method to use POST request instead of GET
- Improved error handling in `makeRequest` method

### Fixed
- Resolved issue with base URL not being properly set in Guzzle Client
- Fixed potential configuration loading issues in different application setups

## [1.1.0] - 2024-09-26

### Changed
- Updated Pesawise class to directly access configuration using Laravel's config helper and environment variables
- Removed dependency on PesawiseProvider for configuration
- Improved flexibility in how the Pesawise class can be instantiated and used

### Fixed
- Issue with configuration not being properly loaded in some application setups

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