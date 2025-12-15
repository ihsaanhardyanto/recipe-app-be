# Design Document

## Overview

Recipe App Backend adalah REST API yang dibangun dengan Laravel 12 untuk mengelola resep masakan. API ini menyediakan endpoint untuk authentication (register, login, logout) menggunakan Laravel Sanctum dan CRUD operations untuk recipes. Frontend Next.js akan mengkonsumsi API ini.

### Key Design Decisions

1. **Laravel Sanctum** untuk token-based authentication (simple, built for SPAs)
2. **MySQL** sebagai database utama (akan di-setup dari SQLite)
3. **API Resources** untuk consistent response formatting
4. **Form Requests** untuk validation logic separation
5. **Policy-based authorization** untuk recipe ownership checks

## Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        Next.js Frontend                          │
└─────────────────────────────────────────────────────────────────┘
                                │
                                │ HTTP/JSON
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                     Laravel API (Backend)                        │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────────┐  │
│  │   Routes    │──│ Middleware  │──│      Controllers        │  │
│  │  (api.php)  │  │  (Sanctum)  │  │ (Auth, Recipe)          │  │
│  └─────────────┘  └─────────────┘  └───────────┬─────────────┘  │
│                                                 │                │
│  ┌─────────────┐  ┌─────────────┐  ┌───────────▼─────────────┐  │
│  │  Policies   │──│Form Requests│──│        Models           │  │
│  │(RecipePolicy│  │ (Validation)│  │   (User, Recipe)        │  │
│  └─────────────┘  └─────────────┘  └───────────┬─────────────┘  │
│                                                 │                │
│  ┌─────────────────────────────────────────────▼─────────────┐  │
│  │                    API Resources                           │  │
│  │              (RecipeResource, UserResource)                │  │
│  └─────────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                       MySQL Database                             │
│         (users, recipes, personal_access_tokens)                 │
└─────────────────────────────────────────────────────────────────┘
```

## Components and Interfaces

### Controllers

#### AuthController

Handles user authentication operations.

```php
class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    public function login(LoginRequest $request): JsonResponse
    public function logout(Request $request): JsonResponse
}
```

#### RecipeController

Handles recipe CRUD operations.

```php
class RecipeController extends Controller
{
    public function index(Request $request): JsonResponse      // List all recipes (paginated)
    public function myRecipes(Request $request): JsonResponse  // List user's recipes
    public function show(Recipe $recipe): JsonResponse         // Get recipe detail
    public function store(StoreRecipeRequest $request): JsonResponse
    public function update(UpdateRecipeRequest $request, Recipe $recipe): JsonResponse
    public function destroy(Recipe $recipe): JsonResponse
}
```

### Form Requests (Validation)

#### RegisterRequest

```php
[
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|string|min:8|confirmed',
]
```

#### LoginRequest

```php
[
    'email' => 'required|email',
    'password' => 'required|string',
]
```

#### StoreRecipeRequest / UpdateRecipeRequest

```php
[
    'title' => 'required|string|max:255',
    'description' => 'nullable|string',
    'ingredients' => 'required|array|min:1',
    'ingredients.*' => 'required|string',
    'instructions' => 'required|string',
]
```

### Policies

#### RecipePolicy

```php
class RecipePolicy
{
    public function update(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }

    public function delete(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }
}
```

### API Resources

#### RecipeResource

```php
[
    'id' => $this->id,
    'title' => $this->title,
    'description' => $this->description,
    'ingredients' => $this->ingredients,  // JSON decoded to array
    'instructions' => $this->instructions,
    'author' => [
        'id' => $this->user->id,
        'name' => $this->user->name,
    ],
    'created_at' => $this->created_at->toISOString(),
    'updated_at' => $this->updated_at->toISOString(),
]
```

#### RecipeListResource (for list page - lighter)

```php
[
    'id' => $this->id,
    'title' => $this->title,
    'description' => $this->description,
    'author' => [
        'id' => $this->user->id,
        'name' => $this->user->name,
    ],
    'created_at' => $this->created_at->toISOString(),
]
```

## Data Models

### Users Table

```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Recipes Table

```sql
CREATE TABLE recipes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    ingredients JSON NOT NULL,
    instructions TEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Entity Relationships

```
User (1) ──────< Recipe (many)
  │                  │
  │ id               │ id
  │ name             │ user_id (FK)
  │ email            │ title
  │ password         │ description
  │                  │ ingredients (JSON)
  │                  │ instructions
  └──────────────────┘
```

## API Endpoints

### Authentication

| Method | Endpoint             | Auth | Description       |
| ------ | -------------------- | ---- | ----------------- |
| POST   | `/api/auth/register` | No   | Register new user |
| POST   | `/api/auth/login`    | No   | Login user        |
| POST   | `/api/auth/logout`   | Yes  | Logout user       |

### Recipes

| Method | Endpoint            | Auth | Description                  |
| ------ | ------------------- | ---- | ---------------------------- |
| GET    | `/api/recipes`      | No   | List all recipes (paginated) |
| GET    | `/api/recipes/{id}` | No   | Get recipe detail            |
| GET    | `/api/my-recipes`   | Yes  | List user's own recipes      |
| POST   | `/api/recipes`      | Yes  | Create new recipe            |
| PUT    | `/api/recipes/{id}` | Yes  | Update recipe (owner only)   |
| DELETE | `/api/recipes/{id}` | Yes  | Delete recipe (owner only)   |

### Query Parameters for List Endpoints

-   `page` - Page number (default: 1)
-   `per_page` - Items per page (default: 10, max: 50)
-   `search` - Search term for title/description

## Correctness Properties

_A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees._

### Property 1: Registration creates valid user with token

_For any_ valid registration data (name, email, password >= 8 chars), registering SHALL create a user in the database and return a valid Sanctum token that can be used for authenticated requests.
**Validates: Requirements 1.1**

### Property 2: Duplicate email rejection

_For any_ existing user email, attempting to register with that same email SHALL be rejected with a validation error, and no new user SHALL be created.
**Validates: Requirements 1.2**

### Property 3: Password length validation

_For any_ password with length < 8 characters, registration SHALL be rejected with a validation error.
**Validates: Requirements 1.3**

### Property 4: Login returns valid token

_For any_ registered user, logging in with correct credentials SHALL return a valid Sanctum token that can be used for authenticated requests.
**Validates: Requirements 2.1, 2.2**

### Property 5: Logout invalidates token (Round-trip)

_For any_ authenticated user, after logout the previously valid token SHALL no longer be accepted for authenticated requests.
**Validates: Requirements 3.1**

### Property 6: Recipe creation associates with user

_For any_ valid recipe data submitted by an authenticated user, the created recipe SHALL have user_id matching the authenticated user's id.
**Validates: Requirements 4.1**

### Property 7: Ingredients stored as JSON array

_For any_ recipe with ingredients array, the stored ingredients SHALL be retrievable as the same array (round-trip consistency).
**Validates: Requirements 4.4**

### Property 8: Pagination returns correct subset

_For any_ collection of N recipes and page P with per_page size S, the returned recipes SHALL be exactly the items from index ((P-1)*S) to (P*S-1), and pagination metadata SHALL accurately reflect total_items = N.
**Validates: Requirements 5.2**

### Property 9: Search filter returns matching recipes only

_For any_ search term, all returned recipes SHALL have the search term present in either title OR description (case-insensitive).
**Validates: Requirements 5.3**

### Property 10: My-recipes returns only owned recipes

_For any_ authenticated user, the my-recipes endpoint SHALL return only recipes where user_id matches the authenticated user's id.
**Validates: Requirements 5.4**

### Property 11: Recipe detail contains all fields

_For any_ recipe, fetching its detail SHALL return all fields: id, title, description, ingredients (as array), instructions, author info, and timestamps.
**Validates: Requirements 6.1, 9.3**

### Property 12: Update persists changes

_For any_ valid update to a recipe by its owner, the updated fields SHALL be persisted and returned correctly.
**Validates: Requirements 7.1**

### Property 13: Ownership required for modification

_For any_ recipe owned by user A, user B (where A ≠ B) SHALL receive a 403 Forbidden error when attempting to update or delete that recipe.
**Validates: Requirements 7.2, 8.2**

### Property 14: Delete removes recipe

_For any_ recipe deleted by its owner, subsequent fetch attempts SHALL return 404 Not Found.
**Validates: Requirements 8.1**

### Property 15: Consistent response structure

_For any_ API response, successful responses SHALL contain {status, message, data} and error responses SHALL contain {status, message, errors}.
**Validates: Requirements 9.1, 9.2**

## Error Handling

### HTTP Status Codes

| Status | Usage                                 |
| ------ | ------------------------------------- |
| 200    | Successful GET, PUT, DELETE           |
| 201    | Successful POST (resource created)    |
| 400    | Bad Request (malformed JSON)          |
| 401    | Unauthorized (missing/invalid token)  |
| 403    | Forbidden (not owner of resource)     |
| 404    | Not Found (resource doesn't exist)    |
| 422    | Validation Error (invalid input data) |
| 500    | Internal Server Error                 |

### Error Response Format

```json
{
    "status": "error",
    "message": "Human readable error message",
    "errors": {
        "field_name": ["Specific validation error"]
    }
}
```

### Success Response Format

```json
{
    "status": "success",
    "message": "Operation completed successfully",
    "data": { ... }
}
```

## Testing Strategy

### Testing Framework

-   **PHPUnit** (already included in Laravel) for unit and feature tests
-   **Pest PHP** (optional, more expressive syntax) - can be added if preferred

### Unit Tests

Unit tests will cover:

-   Model relationships (User hasMany Recipes, Recipe belongsTo User)
-   Validation rules in Form Requests
-   Policy authorization logic
-   API Resource transformations

### Feature Tests (Integration)

Feature tests will cover:

-   Complete authentication flow (register → login → logout)
-   Recipe CRUD operations with database assertions
-   Authorization checks (owner vs non-owner)
-   Pagination and filtering behavior
-   Error response formats

### Property-Based Testing

Using **PHPUnit** with data providers to simulate property-based testing:

1. **Registration validation property**: Generate various invalid inputs (short passwords, invalid emails, duplicate emails) and verify consistent rejection
2. **Token round-trip property**: Register → Login → Use token → Logout → Verify token invalid
3. **Ingredients JSON round-trip**: Create recipe with ingredients array → Fetch → Verify array matches
4. **Ownership authorization property**: Create recipes for multiple users → Verify cross-user modification blocked
5. **Search filter property**: Create recipes with known content → Search → Verify only matching returned
6. **Pagination property**: Create N recipes → Request various pages → Verify correct subsets returned

### Test Organization

```
tests/
├── Feature/
│   ├── Auth/
│   │   ├── RegisterTest.php
│   │   ├── LoginTest.php
│   │   └── LogoutTest.php
│   └── Recipe/
│       ├── CreateRecipeTest.php
│       ├── ReadRecipeTest.php
│       ├── UpdateRecipeTest.php
│       └── DeleteRecipeTest.php
└── Unit/
    ├── Models/
    │   └── RecipeTest.php
    ├── Policies/
    │   └── RecipePolicyTest.php
    └── Resources/
        └── RecipeResourceTest.php
```

### Test Annotations

Each property-based test MUST include a comment referencing the correctness property:

```php
/**
 * Feature: recipe-crud-api, Property 7: Ingredients stored as JSON array
 * Validates: Requirements 4.4
 */
public function test_ingredients_round_trip(): void
```
