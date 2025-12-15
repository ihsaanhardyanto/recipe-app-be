# Requirements Document

## Introduction

Recipe App adalah aplikasi web untuk menyimpan dan mengelola resep masakan. Aplikasi ini dibangun sebagai tugas web development perkuliahan dengan fitur utama CRUD (Create, Read, Update, Delete) untuk resep dan sistem authentication menggunakan Laravel Sanctum di backend (Laravel 12) dan Next.js di frontend. Fokus utama adalah kesederhanaan dan fungsionalitas dasar yang solid.

## Glossary

-   **Recipe_System**: Sistem backend Laravel yang mengelola data resep dan autentikasi pengguna
-   **User**: Pengguna yang terdaftar dan dapat mengelola resep miliknya
-   **Recipe**: Entitas resep masakan yang berisi informasi seperti judul, bahan, dan langkah pembuatan
-   **Sanctum_Token**: Token autentikasi yang dihasilkan Laravel Sanctum untuk mengamankan API
-   **API_Endpoint**: URL endpoint REST API yang menyediakan akses ke resource

## Requirements

### Requirement 1: User Registration

**User Story:** As a visitor, I want to register an account, so that I can create and manage my own recipes.

#### Acceptance Criteria

1. WHEN a visitor submits valid registration data (name, email, password) THEN the Recipe_System SHALL create a new User account and return a Sanctum_Token
2. WHEN a visitor submits an email that already exists THEN the Recipe_System SHALL reject the registration and return a validation error message
3. WHEN a visitor submits a password shorter than 8 characters THEN the Recipe_System SHALL reject the registration and return a validation error message
4. WHEN a visitor submits an invalid email format THEN the Recipe_System SHALL reject the registration and return a validation error message

### Requirement 2: User Login

**User Story:** As a registered user, I want to login to my account, so that I can access my recipes.

#### Acceptance Criteria

1. WHEN a User submits valid login credentials (email, password) THEN the Recipe_System SHALL authenticate the User and return a Sanctum_Token
2. WHEN a User submits incorrect credentials THEN the Recipe_System SHALL reject the login and return an authentication error message
3. WHEN a User submits a non-existent email THEN the Recipe_System SHALL reject the login and return an authentication error message

### Requirement 3: User Logout

**User Story:** As a logged-in user, I want to logout from my account, so that I can secure my session.

#### Acceptance Criteria

1. WHEN an authenticated User requests logout THEN the Recipe_System SHALL revoke the current Sanctum_Token and return a success confirmation
2. WHEN an unauthenticated request attempts logout THEN the Recipe_System SHALL reject the request and return an unauthorized error

### Requirement 4: Create Recipe

**User Story:** As a logged-in user, I want to create a new recipe, so that I can save my cooking recipes.

#### Acceptance Criteria

1. WHEN an authenticated User submits valid recipe data (title, description, ingredients, instructions) THEN the Recipe_System SHALL create a new Recipe associated with that User and return the created Recipe data
2. WHEN an authenticated User submits a recipe without a title THEN the Recipe_System SHALL reject the creation and return a validation error message
3. WHEN an unauthenticated request attempts to create a recipe THEN the Recipe_System SHALL reject the request and return an unauthorized error
4. WHEN an authenticated User submits a recipe THEN the Recipe_System SHALL store the ingredients as a JSON array and the instructions as text

### Requirement 5: Read Recipes (List Page)

**User Story:** As a user, I want to view a list of recipes with pagination and filtering, so that I can browse and find cooking inspiration easily.

#### Acceptance Criteria

1. WHEN any request fetches the recipe list THEN the Recipe_System SHALL return a paginated list of Recipes with summary information (id, title, description, author name, created_at)
2. WHEN a request includes a page parameter THEN the Recipe_System SHALL return the corresponding page of results with pagination metadata (current_page, total_pages, per_page, total_items)
3. WHEN a request includes a search filter parameter THEN the Recipe_System SHALL return only Recipes where the title or description contains the search term
4. WHEN an authenticated User requests their own recipes endpoint THEN the Recipe_System SHALL return only Recipes owned by that User with pagination

### Requirement 6: Read Recipe Detail (Detail Page)

**User Story:** As a user, I want to view the complete details of a recipe, so that I can see all ingredients and cooking instructions.

#### Acceptance Criteria

1. WHEN any request fetches a specific Recipe by ID THEN the Recipe_System SHALL return the complete Recipe data including title, description, ingredients (as array), instructions, author information, and timestamps
2. WHEN any request fetches a non-existent Recipe ID THEN the Recipe_System SHALL return a not found error message

### Requirement 7: Update Recipe

**User Story:** As a recipe owner, I want to update my recipe, so that I can correct or improve the recipe information.

#### Acceptance Criteria

1. WHEN an authenticated User submits valid update data for their own Recipe THEN the Recipe_System SHALL update the Recipe and return the updated Recipe data
2. WHEN an authenticated User attempts to update a Recipe owned by another User THEN the Recipe_System SHALL reject the update and return a forbidden error
3. WHEN an unauthenticated request attempts to update a recipe THEN the Recipe_System SHALL reject the request and return an unauthorized error
4. WHEN an authenticated User submits an update without a title THEN the Recipe_System SHALL reject the update and return a validation error message

### Requirement 8: Delete Recipe

**User Story:** As a recipe owner, I want to delete my recipe, so that I can remove recipes I no longer want to share.

#### Acceptance Criteria

1. WHEN an authenticated User requests deletion of their own Recipe THEN the Recipe_System SHALL delete the Recipe and return a success confirmation
2. WHEN an authenticated User attempts to delete a Recipe owned by another User THEN the Recipe_System SHALL reject the deletion and return a forbidden error
3. WHEN an unauthenticated request attempts to delete a recipe THEN the Recipe_System SHALL reject the request and return an unauthorized error
4. WHEN any request attempts to delete a non-existent Recipe THEN the Recipe_System SHALL return a not found error message

### Requirement 9: API Response Format

**User Story:** As a frontend developer, I want consistent API responses, so that I can easily integrate the backend with the Next.js frontend.

#### Acceptance Criteria

1. WHEN the Recipe_System returns a successful response THEN the response SHALL include a consistent JSON structure with status, message, and data fields
2. WHEN the Recipe_System returns an error response THEN the response SHALL include a consistent JSON structure with status, message, and errors fields
3. WHEN the Recipe_System serializes a Recipe THEN the response SHALL include id, title, description, ingredients (as array), instructions, author information, and timestamps
