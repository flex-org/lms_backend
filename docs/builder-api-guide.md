# Builder API - Complete Guide

## Base URL

```
{{BASE_URL}}/api/v1/builder
```

## Required Headers (All Requests)

| Header          | Value                       | Description                         |
|-----------------|-----------------------------|-------------------------------------|
| `Authorization` | `Bearer {admin_token}`      | Admin auth token (Sanctum)          |
| `domain`        | `example.platme.com`        | Platform domain for tenant scoping  |
| `Accept`        | `application/json`          | Standard JSON response              |
| `Content-Type`  | `application/json`          | For POST/PUT/PATCH requests         |
| `Accept-Language`| `en` or `ar`               | Controls which language values return in |

> **Middleware stack**: `auth:admins` → `domainAccess` → `featureAccess:builder`
>
> The admin must have the `builder` feature ability on their token, and the platform must have the builder feature enabled.

---

## 1. List Pages

**`GET /api/v1/builder/pages`**

Returns all pages for the current platform (summary view, no nested sections).

### Response

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "page_id": 1,
      "key": "home",
      "name": "Home",
      "active": true,
      "sections_count": 5
    },
    {
      "id": 2,
      "page_id": 2,
      "key": "categories",
      "name": "Categories",
      "active": true,
      "sections_count": 1
    },
    {
      "id": 3,
      "page_id": 3,
      "key": "courses",
      "name": "Courses",
      "active": true,
      "sections_count": 1
    },
    {
      "id": 4,
      "page_id": 4,
      "key": "subscription",
      "name": "Subscription",
      "active": true,
      "sections_count": 3
    }
  ]
}
```

> `name` is translated based on `Accept-Language` header.

---

## 2. Show Full Page (with all sections, structures & values)

**`GET /api/v1/builder/pages/{platformPageId}`**

Returns a single page with all nested content. This is the endpoint you use to load the editor for a page.

### Example

```
GET /api/v1/builder/pages/1
Accept-Language: en
```

### Response

```json
{
  "success": true,
  "data": {
    "id": 1,
    "page_id": 1,
    "key": "home",
    "name": "Home",
    "active": true,
    "sections": [
      {
        "id": 1,
        "section_id": 1,
        "name": "Hero",
        "active": true,
        "position": 0,
        "structures": [
          {
            "id": 1,
            "name": "title",
            "type": "text",
            "is_array": false,
            "label": "Title",
            "placeholder": "title",
            "value_id": 1,
            "value": "Mr. Mohamed Ahmed"
          },
          {
            "id": 2,
            "name": "subtitle",
            "type": "text",
            "is_array": false,
            "label": "Subtitle",
            "placeholder": "Learn everything, become anything",
            "value_id": 2,
            "value": "Learn everything, become anything"
          },
          {
            "id": 3,
            "name": "bio",
            "type": "description",
            "is_array": false,
            "label": "Bio",
            "placeholder": "Hi I'm Mr Mohamed Ahmed...",
            "value_id": 3,
            "value": "Hi I'm Mr Mohamed Ahmed. I'm a teacher..."
          },
          {
            "id": 4,
            "name": "image",
            "type": "image",
            "is_array": false,
            "label": "Hero Image",
            "placeholder": null,
            "value_id": 4,
            "value": null
          },
          {
            "id": 5,
            "name": "stats",
            "type": "composite",
            "is_array": true,
            "label": "Statistics",
            "placeholder": "{\"value\":\"40+\",\"label\":\"Students\"}",
            "value_id": 5,
            "value": [
              { "value": "40+", "label": "Students" },
              { "value": "120+", "label": "Courses" },
              { "value": "300+", "label": "Lessons" }
            ],
            "max": 0,
            "fields": ["value", "label"]
          }
        ]
      }
    ]
  }
}
```

---

## 3. List Sections for a Page (by page key)

**`GET /api/v1/builder/pages/{pageKey}/sections`**

Uses the page `key` (not ID). Available keys: `home`, `categories`, `courses`, `subscription`.

Returns an **object keyed by section key**. Each section contains its structures as an **object keyed by structure name**.

> This endpoint uses only `domainExists` middleware (no auth required). Suitable for the public website to render pages.

### Example

```
GET /api/v1/builder/pages/home/sections
Accept-Language: en
```

### Response

```json
{
  "success": true,
  "data": {
    "hero": {
      "id": 1,
      "section_id": 1,
      "name": "Hero",
      "key": "hero",
      "active": true,
      "position": 1,
      "structures": {
        "title": {
          "id": 1,
          "name": "title",
          "type": "text",
          "is_array": false,
          "label": "Title",
          "placeholder": "title",
          "value_id": 1,
          "value": "Mr. Mohamed Ahmed"
        },
        "subtitle": {
          "id": 2,
          "name": "subtitle",
          "type": "text",
          "is_array": false,
          "label": "Subtitle",
          "placeholder": "Learn everything, become anything",
          "value_id": 2,
          "value": "Learn everything, become anything"
        },
        "stats": {
          "id": 5,
          "name": "stats",
          "type": "composite",
          "is_array": true,
          "label": "Statistics",
          "placeholder": null,
          "value_id": 5,
          "value": [
            { "value": "40+", "label": "Students" },
            { "value": "120+", "label": "Courses" }
          ],
          "max": 0,
          "fields": ["value", "label"]
        }
      }
    },
    "why_us": {
      "id": 2,
      "section_id": 2,
      "name": "Why Us",
      "key": "why_us",
      "active": true,
      "position": 2,
      "structures": {
        "title": { "..." : "..." },
        "items": { "..." : "..." }
      }
    }
  }
}
```

> **Frontend usage:** Access values directly: `data.hero.structures.title.value`, `data.hero.structures.stats.value[0].label`, etc.

---

## 3b. Get Structures for a Single Section

**`GET /api/v1/builder/sections/{platformSectionId}/structures`**

Returns only the **structures object** (keyed by structure name) for a single section. Same middleware as the sections list (`domainExists`, no auth).

### Example

```
GET /api/v1/builder/sections/1/structures
Accept-Language: en
```

### Response

```json
{
  "success": true,
  "data": {
    "title": {
      "id": 1,
      "name": "title",
      "type": "text",
      "is_array": false,
      "label": "Title",
      "placeholder": "title",
      "value_id": 1,
      "value": "Mr. Mohamed Ahmed"
    },
    "stats": {
      "id": 5,
      "name": "stats",
      "type": "composite",
      "is_array": true,
      "label": "Statistics",
      "placeholder": null,
      "value_id": 5,
      "value": [
        { "value": "40+", "label": "Students" }
      ],
      "max": 0,
      "fields": ["value", "label"]
    }
  }
}
```

---

## 4. Toggle Section Active/Inactive

**`PATCH /api/v1/builder/sections/{platformSectionId}`**

Activate or deactivate a section. Cannot delete sections, only hide them.

> This endpoint is **only** for toggling visibility. It does NOT accept `position`. Use the reorder endpoint for ordering.

### Request Body

```json
{
  "active": false
}
```

### Response

```json
{
  "success": true,
  "message": "Updated successfully",
  "data": {
    "section": {
      "id": 1,
      "section_id": 1,
      "name": "Hero",
      "active": false,
      "position": 1,
      "structures": null
    }
  }
}
```

---

## 5. Reorder Sections (Drag & Drop)

**`POST /api/v1/builder/pages/{pageKey}/sections/reorder`**

Send the **full list** of section IDs in the new desired order. The backend assigns `position` values automatically (1, 2, 3...).

**How it works (drag & drop):**
- Frontend has sections in order: `[1, 2, 3, 4, 5]`
- User drags section `1` (Hero) and drops it after section `3` (CTA Banner)
- Frontend sends the new order: `[2, 3, 1, 4, 5]`
- Backend sets: section 2 → position 1, section 3 → position 2, section 1 → position 3, etc.

> You must send **ALL** section IDs for the page, not just the ones that moved.

### Request Body

```json
{
  "ordered_ids": [2, 3, 1, 4, 5]
}
```

### Response

```json
{
  "success": true,
  "message": "Updated successfully"
}
```

### Example: Move last section to first

Before: `[1, 2, 3, 4, 5]` → Send: `[5, 1, 2, 3, 4]`

### Example: Swap two sections

Before: `[1, 2, 3, 4, 5]`, swap 2 and 4 → Send: `[1, 4, 3, 2, 5]`

---

## 6. Update Section Values (Content Editing)

**`PUT /api/v1/builder/sections/{platformSectionId}/values`**

This is the main editing endpoint. You send one language at a time.

### Request Structure

```json
{
  "locale": "en",
  "values": [
    {
      "structure_id": 1,
      "value": "..."
    }
  ]
}
```

| Field              | Type     | Required | Description                              |
|--------------------|----------|----------|------------------------------------------|
| `locale`           | string   | yes      | `"en"` or `"ar"` - which language to save |
| `values`           | array    | yes      | Array of value items (min: 1)            |
| `values.*.structure_id` | integer | yes | The structure ID to update               |
| `values.*.value`   | mixed    | yes      | The value - format depends on type (see below) |

---

## Structure Types & Value Formats

### Type: `text`

Simple string value.

```json
{
  "structure_id": 1,
  "value": "Mr. Mohamed Ahmed"
}
```

**API returns:**
```json
{
  "id": 1,
  "name": "title",
  "type": "text",
  "is_array": false,
  "label": "Title",
  "placeholder": "title",
  "value_id": 1,
  "value": "Mr. Mohamed Ahmed"
}
```

---

### Type: `description`

Long text / paragraph (same format as text, just semantically different).

```json
{
  "structure_id": 3,
  "value": "Hi I'm Mr Mohamed Ahmed. I'm a teacher, a leader, and a creator."
}
```

**API returns:**
```json
{
  "id": 3,
  "name": "bio",
  "type": "description",
  "is_array": false,
  "label": "Bio",
  "placeholder": "Hi I'm Mr Mohamed Ahmed...",
  "value_id": 3,
  "value": "Hi I'm Mr Mohamed Ahmed. I'm a teacher, a leader, and a creator."
}
```

---

### Type: `image`

URL string or `null`.

```json
{
  "structure_id": 4,
  "value": "https://example.com/hero.jpg"
}
```

**API returns:**
```json
{
  "id": 4,
  "name": "image",
  "type": "image",
  "is_array": false,
  "label": "Hero Image",
  "placeholder": null,
  "value_id": 4,
  "value": "https://example.com/hero.jpg"
}
```

---

### Type: `composite` + `is_array: false`

A single object with multiple fields.

```json
{
  "structure_id": 10,
  "value": {
    "name": "John",
    "role": "Teacher"
  }
}
```

**API returns:**
```json
{
  "id": 10,
  "name": "author",
  "type": "composite",
  "is_array": false,
  "label": "Author Info",
  "placeholder": "{\"name\":\"...\",\"role\":\"...\"}",
  "value_id": 10,
  "value": {
    "name": "John",
    "role": "Teacher"
  },
  "fields": ["name", "role"]
}
```

> `fields` tells the frontend which input fields to render inside this composite.

---

### Type: `composite` + `is_array: true`

An array of objects. This is the most complex type.

```json
{
  "structure_id": 5,
  "value": [
    { "value": "40+", "label": "Students" },
    { "value": "120+", "label": "Courses" },
    { "value": "300+", "label": "Lessons" }
  ]
}
```

**API returns:**
```json
{
  "id": 5,
  "name": "stats",
  "type": "composite",
  "is_array": true,
  "label": "Statistics",
  "placeholder": "{\"value\":\"40+\",\"label\":\"Students\"}",
  "value_id": 5,
  "value": [
    { "value": "40+", "label": "Students" },
    { "value": "120+", "label": "Courses" },
    { "value": "300+", "label": "Lessons" }
  ],
  "max": 0,
  "fields": ["value", "label"]
}
```

Key fields for frontend form building:

| Field      | Description                                                      |
|------------|------------------------------------------------------------------|
| `fields`   | Array of keys the frontend should render as inputs per item      |
| `is_array` | `true` = user can add/remove items (render add/remove buttons)   |
| `max`      | Maximum number of items allowed. `0` = unlimited                 |

---

## Full Postman Example: Edit Hero Section (English)

**`PUT /api/v1/builder/sections/1/values`**

```json
{
  "locale": "en",
  "values": [
    {
      "structure_id": 1,
      "value": "Prof. Mohamed Ahmed"
    },
    {
      "structure_id": 2,
      "value": "Master everything, achieve anything"
    },
    {
      "structure_id": 3,
      "value": "Welcome! I am Professor Mohamed Ahmed, an experienced educator dedicated to transforming education."
    },
    {
      "structure_id": 4,
      "value": "https://cdn.example.com/images/hero-new.jpg"
    },
    {
      "structure_id": 5,
      "value": [
        { "value": "50+", "label": "Students" },
        { "value": "150+", "label": "Courses" },
        { "value": "400+", "label": "Lessons" },
        { "value": "10+", "label": "Years Experience" }
      ]
    }
  ]
}
```

## Full Postman Example: Edit Hero Section (Arabic)

**`PUT /api/v1/builder/sections/1/values`**

```json
{
  "locale": "ar",
  "values": [
    {
      "structure_id": 1,
      "value": "أ.د محمد أحمد"
    },
    {
      "structure_id": 2,
      "value": "أتقن كل شيء، حقق أي شيء"
    },
    {
      "structure_id": 3,
      "value": "مرحباً! أنا البروفيسور محمد أحمد، معلم متمرس مكرس لتحويل التعليم."
    },
    {
      "structure_id": 4,
      "value": "https://cdn.example.com/images/hero-new.jpg"
    },
    {
      "structure_id": 5,
      "value": [
        { "value": "50+", "label": "طالب" },
        { "value": "150+", "label": "كورس" },
        { "value": "400+", "label": "درس" },
        { "value": "10+", "label": "سنوات خبرة" }
      ]
    }
  ]
}
```

---

## Full Postman Example: Edit FAQ Section

**`PUT /api/v1/builder/sections/5/values`**

```json
{
  "locale": "en",
  "values": [
    {
      "structure_id": 15,
      "value": "Frequently Asked Questions"
    },
    {
      "structure_id": 16,
      "value": [
        {
          "question": "Can I switch plans later?",
          "answer": "Yes, you can upgrade or downgrade at any time."
        },
        {
          "question": "Do you offer a free trial?",
          "answer": "Yes, we offer a free trial period for new users."
        },
        {
          "question": "How do I cancel my subscription?",
          "answer": "You can cancel from your account settings at any time."
        }
      ]
    }
  ]
}
```

---

## All Existing Composite Structures

Here is every composite structure in the system and its field shape:

### Home Page

| Section       | Structure   | `is_array` | `max` | Fields                                     |
|---------------|-------------|------------|-------|--------------------------------------------|
| Hero          | `stats`     | `true`     | 0     | `value`, `label`                           |
| Why Us        | `items`     | `true`     | 0     | `title`, `description`                     |
| Testimonials  | `items`     | `true`     | 0     | `quote`, `author_name`, `author_role`      |
| FAQ           | `items`     | `true`     | 0     | `question`, `answer`                       |

### Subscription Page

| Section       | Structure   | `is_array` | `max` | Fields                                     |
|---------------|-------------|------------|-------|--------------------------------------------|
| Plan Card     | `features`  | `true`     | 0     | *(simple string array, not composite)*     |
| FAQ           | `items`     | `true`     | 0     | `question`, `answer`                       |

> `features` under Plan Card is `is_array: true` but type is `text`, not `composite`. Its value is an array of strings like `["Unlock all Courses", "Unlimited sessions"]`.

---

## Workflow Summary

```
1. GET /pages                                → See all pages + section counts
2. GET /pages/{id}                           → Load full page for editing
     or
   GET /pages/{pageKey}/sections             → Load sections as keyed object (public)
3. GET /sections/{id}/structures             → Load structures for one section (public)
4. PUT /sections/{id}/values                 → Save content edits (one language at a time)
5. PATCH /sections/{id}                      → Toggle active/inactive only (no position)
6. POST /pages/{pageKey}/sections/reorder    → Drag & drop reorder (send ALL IDs in new order)
```

> **Activation** and **Reorder** are completely separate:
> - Use `PATCH` to show/hide a section (`active: true/false`)
> - Use `POST .../reorder` to change the order (drag & drop)

### Language Workflow

1. User selects a language in the editor (e.g., English)
2. Frontend sends `Accept-Language: en` header to GET endpoints to load English values
3. User edits values and saves with `"locale": "en"` in the PUT body
4. User switches to Arabic in the editor
5. Frontend sends `Accept-Language: ar` header to reload the page with Arabic values
6. User edits Arabic values and saves with `"locale": "ar"` in the PUT body

Each language is saved independently. Editing English does not affect Arabic and vice versa.

---

## Quick Reference: Understanding the Response Fields

| Field         | Meaning                                                           |
|---------------|-------------------------------------------------------------------|
| `id`          | Structure ID (use as `structure_id` when saving)                  |
| `name`        | Internal identifier (e.g., `title`, `stats`, `items`)            |
| `type`        | `text` / `description` / `image` / `composite`                   |
| `is_array`    | Can the value be repeated? (add/remove items)                     |
| `max`         | Max items when `is_array=true`. `0` = unlimited. Not shown when `is_array=false` |
| `label`       | Translated display label for the form field                       |
| `placeholder` | Translated placeholder/hint for the input                         |
| `value_id`    | Database ID of the value record (for reference, not needed in save) |
| `value`       | The actual content (format depends on type)                       |
| `fields`      | Only on `composite` type - array of sub-field keys for the form   |

---

## Error Responses

### 401 Unauthorized
```json
{ "message": "Unauthenticated." }
```

### 403 Forbidden (no builder feature)
```json
{ "success": false, "message": "Access denied: feature not available." }
```

### 404 Not Found (wrong page key)
```json
{ "success": false, "message": "No query results for model [PlatformPage]." }
```

### 422 Validation Error
```json
{
  "message": "The locale field is required.",
  "errors": {
    "locale": ["The locale field is required."],
    "values.0.structure_id": ["The values.0.structure_id field is required."]
  }
}
```
