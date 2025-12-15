# Implementation Plan

-   [ ] 1. Setup Database dan Dependencies

    -   [x] 1.1 Configure MySQL database connection

        -   Update `.env` file dengan MySQL credentials (DB_CONNECTION=mysql, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
        -   **PENTING: Jangan lupa switch dari SQLite ke MySQL!**
        -   _Requirements: Database setup_

    -   [x] 1.2 Install Laravel Sanctum

        -   Run `composer require laravel/sanctum`
        -   Publish Sanctum config dengan `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`
        -   Add HasApiTokens trait ke User model
        -   _Requirements: 1.1, 2.1, 3.1_

    -   [x] 1.3 Create recipes migration

        -   Create migration untuk recipes table dengan fields: id, user_id (foreign key), title, description, ingredients (JSON), instructions, timestamps
        -   Run `php artisan migrate` untuk create tables di MySQL
        -   _Requirements: 4.1, 4.4_

-   [ ] 2. Setup API Response Structure

    -   [x] 2.1 Create base API response trait/helper

        -   Create trait untuk consistent response format (success dan error responses)
        -   Format: {status, message, data} untuk success, {status, message, errors} untuk error
        -   _Requirements: 9.1, 9.2_

-   [ ] 3. Implement Authentication

    -   [ ] 3.1 Create Form Requests untuk Auth
        -   Create RegisterRequest dengan validation rules (name, email unique, password min:8 confirmed)
        -   Create LoginRequest dengan validation rules (email, password)
        -   _Requirements: 1.2, 1.3, 1.4_
    -   [ ] 3.2 Create AuthController
        -   Implement register() - create user, generate Sanctum token
        -   Implement login() - validate credentials, generate token
        -   Implement logout() - revoke current token
        -   _Requirements: 1.1, 2.1, 2.2, 2.3, 3.1, 3.2_
    -   [ ] 3.3 Setup auth routes di api.php
        -   POST /api/auth/register
        -   POST /api/auth/login
        -   POST /api/auth/logout (protected)
        -   _Requirements: 1.1, 2.1, 3.1_
    -   [ ]\* 3.4 Write property tests untuk Authentication
        -   **Property 1: Registration creates valid user with token**
        -   **Property 4: Login returns valid token**
        -   **Property 5: Logout invalidates token (Round-trip)**
        -   **Validates: Requirements 1.1, 2.1, 3.1**

-   [ ] 4. Implement Recipe Model dan Resources

    -   [ ] 4.1 Create Recipe model dengan relationships
        -   Define belongsTo User relationship
        -   Add hasMany recipes ke User model
        -   Define fillable fields dan casts (ingredients as array)
        -   _Requirements: 4.1, 6.1_
    -   [ ] 4.2 Create API Resources
        -   Create RecipeResource untuk detail view (semua fields)
        -   Create RecipeListResource untuk list view (summary fields only)
        -   _Requirements: 5.1, 6.1, 9.3_
    -   [ ]\* 4.3 Write property test untuk Recipe serialization
        -   **Property 7: Ingredients stored as JSON array**
        -   **Property 11: Recipe detail contains all fields**
        -   **Validates: Requirements 4.4, 6.1, 9.3**

-   [ ] 5. Implement Recipe CRUD Operations

    -   [ ] 5.1 Create Form Requests untuk Recipe
        -   Create StoreRecipeRequest (title required, ingredients array, instructions required)
        -   Create UpdateRecipeRequest (same validation)
        -   _Requirements: 4.2, 7.4_
    -   [ ] 5.2 Create RecipePolicy untuk authorization
        -   Implement update() - check user owns recipe
        -   Implement delete() - check user owns recipe
        -   Register policy di AuthServiceProvider
        -   _Requirements: 7.2, 8.2_
    -   [ ] 5.3 Create RecipeController
        -   Implement index() - list all recipes with pagination dan search filter
        -   Implement myRecipes() - list user's own recipes
        -   Implement show() - get recipe detail
        -   Implement store() - create new recipe
        -   Implement update() - update recipe (with policy check)
        -   Implement destroy() - delete recipe (with policy check)
        -   _Requirements: 4.1, 5.1, 5.2, 5.3, 5.4, 6.1, 6.2, 7.1, 8.1_
    -   [ ] 5.4 Setup recipe routes di api.php
        -   GET /api/recipes (public, paginated)
        -   GET /api/recipes/{id} (public)
        -   GET /api/my-recipes (protected)
        -   POST /api/recipes (protected)
        -   PUT /api/recipes/{id} (protected, owner only)
        -   DELETE /api/recipes/{id} (protected, owner only)
        -   _Requirements: 4.3, 5.1, 6.1, 7.3, 8.3_
    -   [ ]\* 5.5 Write property tests untuk Recipe CRUD
        -   **Property 6: Recipe creation associates with user**
        -   **Property 12: Update persists changes**
        -   **Property 14: Delete removes recipe**
        -   **Validates: Requirements 4.1, 7.1, 8.1**

-   [ ] 6. Implement Pagination dan Search

    -   [ ]\* 6.1 Write property tests untuk Pagination dan Search
        -   **Property 8: Pagination returns correct subset**
        -   **Property 9: Search filter returns matching recipes only**
        -   **Property 10: My-recipes returns only owned recipes**
        -   **Validates: Requirements 5.2, 5.3, 5.4**

-   [ ] 7. Implement Authorization Tests

    -   [ ]\* 7.1 Write property tests untuk Authorization
        -   **Property 13: Ownership required for modification**
        -   **Validates: Requirements 7.2, 8.2**

-   [ ] 8. Implement Response Format Tests

    -   [ ]\* 8.1 Write property tests untuk Response Format
        -   **Property 15: Consistent response structure**
        -   **Validates: Requirements 9.1, 9.2**

-   [ ] 9. Final Checkpoint
    -   Ensure all tests pass, ask the user if questions arise.
