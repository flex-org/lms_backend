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
- `TenantContext` (singleton implementing `TenantContextInterface`) holds the resolved domain and platform reference for the current request lifecycle.
- Middleware resolves the domain and injects it into `TenantContext`.

### Isolation Strategy
Isolation is implemented at application level (not separate DB/schema):
1. `domainExists` middleware validates tenant domain via `TenantContext::getPlatformByDomain()`.
2. Domain value is stored in `TenantContext` (and synced to `config('platform.domain')` for backward compatibility).
3. Domain-aware models use `BelongsToDomain` trait + `DomainScope` — both read from `TenantContextInterface`.
4. Admin tokens include domain ability and `domainAccess` middleware validates token ability against current domain from `TenantContext`.

### Key Contracts
- `TenantContextInterface` — central tenant state (domain, platform).
- `TenantAware` — interface for domain-scoped entities.
- `PermissionRegistryInterface` — unified permission naming.

> Important: this is a **shared database, row-level isolation by domain and authorization**, not physical DB isolation.

---

## 3) Main Domain Entities

### 3.1 Platform (Tenant Aggregate Root)
`platforms` table contains tenant-level plan/config data:
- `domain` (unique) — validated via `DomainName` Value Object
- `storage` (integer) — validated via `StorageQuota` Value Object (min 50 MB)
- `capacity` (integer) — validated via `Capacity` Value Object (min 100 students)
- `has_mobile_app`
- billing lifecycle fields (`started_at`, `renew_at`, `cost`, `status`)

`Platform` relations:
- belongs to owner `User`
- belongs to a primary `Theme`
- many-to-many with `Feature` through `platform_features`
- many-to-many with `SellingSystem` through `platform_selling_systems`

On creation, platform auto-attaches free themes (`Theme::whereNull('price')`).

### 3.2 Features (Selectable product modules)
- Features are defined in `features` table and exposed via `FeatureService`.
- Tenants subscribe to features via pivot `platform_features` (stores per-feature price snapshot).
- Feature access is enforced by permissions (Spatie Permission package), named via `PermissionRegistry::featurePermission()` → `feature-{id}`.

### 3.3 Dynamic Features (Quantity-based pricing)
`dynamic_features` table stores quantity-priced dimensions such as:
- `storage`
- `capacity`
- `mobile_app`

`PlatformPricingService` (domain service injected with `DynamicFeatureRepositoryInterface`) computes:
`selected static feature prices + quantity-based dynamic price`.

### 3.4 Admins and Roles
- Tenant admins are in `admins` table.
- Admin model uses `HasRoles` + `BelongsToDomain`.
- Role seeding includes `owner` and `admin` under guard `admins`.
- Owner role gets all admin capabilities (seeded via `config('features.admin_capabilities')`).
- During tenant provisioning, owner admin is created and granted selected feature permissions.
- Authorization is enforced via `AdminPolicy` (registered through `AdminsModuleServiceProvider`).

### 3.5 Editor / Builder (Pages & Sections)
- Template pages (`pages`) and sections (`sections`) define the available building blocks.
- `platform_pages` and `platform_sections` are per-tenant active configurations.
- `section_values` store per-section content with translations.
- Builder endpoints are gated behind `featureAccess:builder` middleware.
- Full CRUD and section reordering via dedicated use cases.

---

## 4) Provisioning Flow (Tenant Creation)
Implemented in `CreatePlatformAction` inside DB transaction:
1. Validate domain uniqueness (`guardAgainstDuplicateDomain`).
2. Load selected feature records via `FeatureRepositoryInterface`.
3. Calculate total cost via `PlatformPricingService`.
4. Get default free theme via `ThemeRepositoryInterface`.
5. Create `Platform` with storage/capacity/mobile app and initial status `FREE_TRIAL`.
6. Attach selling systems.
7. Attach selected features to pivot with prices.
8. Grant same feature permissions to platform object (via `PermissionRegistry`).
9. Create owner admin (domain-scoped), assign `owner` role.
10. Grant owner admin permissions `feature-{id}` (via `PermissionRegistry`).
11. Return `platform_url` + owner token with domain abilities.

This flow guarantees consistency and rollback safety if any step fails.

---

## 5) Access Control and Authorization

### 5.1 Domain Validation
- `CheckDomainExistances` reads `domain` header.
- Verifies tenant exists via `TenantContext::getPlatformByDomain()`.
- Sets domain in `TenantContext` for downstream layers.

### 5.2 Domain Authorization
- `CheckDomainAccess` compares current token abilities with domain from `TenantContext`.
- Blocks cross-tenant access (`403`) if token lacks that domain ability.

### 5.3 Feature Authorization
- `CheckFeatureAccess` delegates to `TenantFeatureAccessService`.
- Service uses `PermissionRegistry::resolveFeaturePermission()`:
  - Numeric key `7` => `feature-7`
  - Alias mapping through `config/features.php` if defined.
- User must own resolved permission on correct guard.

### 5.4 Admin Authorization
- `AdminPolicy` enforces owner-only access for admin management operations.
- Registered via `Gate::policy()` in `AdminsModuleServiceProvider`.
- Controllers use `$this->authorize()` — no inline authorization logic.

---

## 6) Routing / API Surface
Versioned routes are bootstrapped in `AppServiceProvider`:
- `/api/v1/*` → core user/public routes (login, signup, features, create platform)
- `/api/v1/enums/*` → enum endpoints (e.g. selling systems)
- `/api/v1/platform/*` → tenant-specific platform routes (domainExists middleware)
- `/api/v1/dashboard/*` → admin dashboard routes (domainExists + auth:admins + domainAccess)
- `/api/v1/builder/*` → editor/builder routes (domainExists + auth:admins + domainAccess + featureAccess:builder)

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

### Clean Architecture Layers
The codebase follows a modular structure (V1 modules) with strict layer separation:
- **Domain** — models, enums, value objects, repository interfaces, policies
- **Application** — use cases, DTOs, services
- **Infrastructure** — Eloquent repositories, module service providers
- **Presentation** — controllers, form requests, API resources

### Module Registration
Module providers are registered through `config/modules.php` and loaded by `ModulesServiceProvider`.

### Shared Kernel (`Modules/Shared`)
Contains cross-cutting contracts and infrastructure:
- `TenantContextInterface` / `TenantContext` — request-scoped tenant state
- `PermissionRegistryInterface` / `PermissionRegistry` — unified permission naming
- `TenantFeatureAccessService` — feature permission checks
- `AbstractModuleServiceProvider` — base for all module providers

### Value Objects
Domain-critical fields are protected by Value Objects with self-validation:
- `DomainName` — domain string validation and normalization
- `StorageQuota` — minimum 50 MB constraint
- `Capacity` — minimum 100 students constraint
- `Money` — non-negative amounts with currency

### Design Principles
- **SOLID**: Single Responsibility in use cases, Interface Segregation in repositories, Dependency Inversion via contracts.
- **DDD**: Platform as Aggregate Root, Value Objects for sensitive fields, Domain Services for pricing.
- **No breaking changes**: All refactoring preserves existing API contracts.

---

## 9) Notes for AI Agents Working on This Repo
1. Treat `Platform` as the tenant aggregate root.
2. Always account for `domain` header + tenant middleware when debugging APIs.
3. Feature gating relies on permissions, not just relational checks.
4. Tenant provisioning logic is concentrated in `CreatePlatformAction`.
5. Pricing changes should be made in `PlatformPricingService` (inject new repositories, never query directly).
6. If introducing a new gated feature:
   - create/seed feature record,
   - ensure permission mapping exists via `PermissionRegistry` (or alias in `config/features.php`),
   - protect endpoints using `featureAccess:<key>` middleware.
7. Keep shared DB assumptions in mind; any tenant-sensitive model should use `BelongsToDomain` trait.
8. Use `TenantContextInterface` (not `Config::get('platform.domain')`) in new code.
9. Use `PermissionRegistryInterface` (not hardcoded `'feature-'` prefix) for permission names.
10. Authorization logic goes in Policies, not Controllers.
11. New modules must extend `AbstractModuleServiceProvider` and register in `config/modules.php`.

---

## 10) Active Modules

| Module | Namespace | Service Provider | Status |
|---|---|---|---|
| Shared | `Modules\Shared` | `SharedModuleServiceProvider` | Active |
| Platforms | `Modules\V1\Platforms` | `PlatformModuleServiceProvider` | Active |
| Admins | `Modules\V1\Admins` | `AdminsModuleServiceProvider` | Active |
| Editor | `Modules\V1\Editor` | `EditorModuleServiceProvider` | Active |
| Features | `Modules\V1\Features` | — (routes via AppServiceProvider) | Active |
| Users | `Modules\V1\Users` | — (routes via AppServiceProvider) | Active |
| Themes | `Modules\V1\Themes` | — (model only) | Active |
| Utilities | `Modules\V1\Utilities` | — (shared services/contracts) | Active |
