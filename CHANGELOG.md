# Changelog

All notable changes to this project will be documented in this file.

## [0.1.0.0] - 2026-03-21

### Added
- Public registration form with multi-participant support (Livewire 4 + Flux UI Pro)
- Stepper counters (+/-) for event quantity selection
- Live total calculation as user fills in event quantities
- Payment confirmation page with registration summary and IBAN/Revolut details
- Zero-total registrations show "No payment required" message
- Email confirmation sent to each participant on registration
- Graceful email failure handling (registration saves regardless)
- Filament 5 admin panel with Registration and Participant resources
- Payment statuses: Pending, Revolut, BCR, Cash
- Admin notes field (visible only to admins)
- Dashboard widgets: registration stats, event headcounts, revenue tracking
- Dashboard widgets: prefix breakdown, degree breakdown, lodge count
- Rate limiting on form submission (5 per hour per IP)
- Dark theme with amber/gold accents on charcoal background
- Configurable pricing, IBAN, Revolut link via config/simpozion.php
- 27 Pest tests covering all code paths (68 assertions)
