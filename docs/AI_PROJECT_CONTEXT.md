# LMS Backend — AI Context Documentation

## 1) Project Purpose
This backend is a **multi-tenant LMS platform** built on Laravel with a **shared database** model.
Each tenant (called `platform`) can:
- Choose a subset of product features.
- Get access control based on selected features.
- Reserve dynamic resources such as:
  - `capacity` (student seats)
  - `storage` (video/files quota)
- Be billed according to selected features + dynamic resource quantities.

Core tenant creation flow starts from `POST /api/v1/platform/create` and provisions the tenant, owner admin, feature permissions, and initial token in one transaction.

---

## 2) Multi-Tenancy Model (Shared DB)
### Tenant Identity
- Tenant is represented by `Platform` model.
- Tenant key is `domain` (unique).
- Domain is passed through request header `domain`.
- Middleware checks domain existence and stores it in runtime config (`config('platform.domain')`).

### Isolation Strategy
Isolation is implemented at application level (not separate DB/schema):
1. `domainExists` middleware validates tenant domain.
2. Domain value is attached to request context via config.
3. Domain-aware models can use `BelongsToDomain` trait + `DomainScope` for filtering/auto-fill.
4. Admin tokens include domain ability and `domainAccess` middleware validates token ability against current domain.

> Important: this is a **shared database, row-level isolation by domain and authorization**, not physical DB isolation.

---

## 3) Main Domain Entities
## 3.1 Platform (Tenant)
`platforms` table contains tenant-level plan/config data:
- `domain` (unique)
- `storage` (integer)
- `capacity` (integer)
- `has_mobile_app`
- billing lifecycle fields (`started_at`, `renew_at`, `cost`, `status`)
- profile metadata (`name`, `about`, `key_words`)

`Platform` relations:
- belongs to owner `User`
- belongs to a primary `Theme`
- many-to-many with `Feature` through `platform_features`
- many-to-many with `SellingSystem` through `platform_selling_systems`

On creation, platform auto-attaches free themes (`Theme::whereNull('price')`).

## 3.2 Features (Selectable product modules)
- Features are defined in `features` table and exposed via `FeatureService`.
- Tenants subscribe to features via pivot `platform_features` (stores per-feature price snapshot).
- Feature access is enforced by permissions (Spatie Permission package), typically named `feature-{id}`.

## 3.3 Dynamic Features (Quantity-based pricing)
`dynamic_features` table stores quantity-priced dimensions such as:
- `storage`
- `capacity`
- `mobile_app`

Pricing service computes:
`selected static feature prices + quantity-based dynamic price`.

## 3.4 Admins and Roles
- Tenant admins are in `admins` table.
- Admin model uses `HasRoles` + `BelongsToDomain`.
- Role seeding includes `owner` and `admin` under guard `admins`.
- During tenant provisioning, owner admin is created and granted selected feature permissions.

---

## 4) Provisioning Flow (Tenant Creation)
Implemented in `CreatePlatformAction` inside DB transaction:
1. Load selected feature records.
2. Calculate total cost via `PlatformPricingService`.
3. Get default free theme.
4. Create `Platform` with storage/capacity/mobile app and initial status `FREE_TRIAL`.
5. Attach selling systems.
6. Attach selected features to pivot with prices.
7. Grant same feature permissions to platform object.
8. Create owner admin (domain-scoped), assign `owner` role.
9. Grant owner admin permissions `feature-{id}`.
10. Return `platform_url` + owner token.

This flow guarantees consistency and rollback safety if any step fails.

---

## 5) Access Control and Authorization
## 5.1 Domain Validation
- `CheckDomainExistances` reads `domain` header.
- Verifies tenant exists.
- Stores domain in config for downstream layers.

## 5.2 Domain Authorization
- `CheckDomainAccess` compares current token abilities with requested domain.
- Blocks cross-tenant access (`403`) if token lacks that domain ability.

## 5.3 Feature Authorization
- `CheckFeatureAccess` delegates to `TenantFeatureAccessService`.
- Service resolves feature key → permission name:
  - Numeric key `7` => `feature-7`
  - Alias mapping through `config/features.php` if defined.
- User must own resolved permission on correct guard.

---

## 6) Routing / API Surface (Current Observed)
Versioned routes are bootstrapped in `AppServiceProvider`:
- `/api/v1/*` → core user/public routes (login, signup, features, create platform)
- `/api/v1/enums/*` → enum endpoints (e.g. selling systems)
- `/api/v1/platform/*` and `/api/v1/dashboard/*` are wrapped with `domainExists` middleware for tenant-aware access.

Auth patterns:
- User auth uses Sanctum token abilities (`verified`, `not-verified`).
- Admin dashboard auth sets abilities including domain (`['dashboard', <domain>]`).

---

## 7) Pricing Model Summary
Price =
1. Sum of selected static features (monthly normalized by days), plus
2. Dynamic feature quantities (`storage`, `capacity`, `mobile_app`) using quantity-price formula:
   `price * max(1, requested_quantity / base_quantity)`.

This allows flexible tenant plans without creating many fixed package permutations.

---

## 8) Architectural Style
The codebase follows a modular structure (V1 modules) with separation by layers:
- `Domain` (models/enums/contracts)
- `Application` (use-cases/services/actions)
- `Infrastructure` (repositories/providers)
- `Presentation` (controllers/requests/resources)

Module providers are registered through `config/modules.php` and loaded by `ModulesServiceProvider`.

---

## 9) Notes for AI Agents Working on This Repo
1. Treat `Platform` as the tenant aggregate root.
2. Always account for `domain` header + tenant middleware when debugging APIs.
3. Feature gating relies on permissions, not just relational checks.
4. Tenant provisioning logic is concentrated in `CreatePlatformAction`.
5. Pricing changes should be made in `PlatformPricingService` and dynamic feature definitions.
6. If introducing a new gated feature:
   - create/seed feature record,
   - ensure permission mapping exists (`feature-{id}` or alias in config),
   - protect endpoints using `featureAccess:<key>` middleware.
7. Keep shared DB assumptions in mind; any tenant-sensitive model should be domain-scoped.

---

## 10) Suggested Next Documentation (Optional)
- ERD for tenant + feature + permissions relations.
- Sequence diagram for signup → platform create → owner login.
- Explicit list of protected routes with required middleware/abilities.
- Definition of quota enforcement points (where storage/capacity are checked at runtime).
