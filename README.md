Security Measures:

-JWT Authentication with Refresh Tokens:
Used tymon/jwt-auth for token-based authentication. Access and refresh tokens are stored securely in HttpOnly cookies to prevent XSS.

-CSRF Protection:
In Laravel, CSRF is mitigated for web routes by default. Laravel’s CSRF middleware ensures tokens are tied to user sessions.

-Rate Limiting & Brute-force Protection:
Using Laravel’s Rate Limitingauthentication routes were constricted to limited amount of login attempts and after going over the limit, it protect against brute-force attacks.

-SQL Injection Prevention:
A mixed approach of using laravel eloquent and DB statements is used. Raw SQL queries are avoided. When DB facade is used (instead of Eloquent), parameter binding is used in all queries. And whether it be eloquent or DB statements, both are using prepared statements to reduce the risk of SQL injection.

-Secure File Uploads:
Patient file uploads are restricted to PDFs using server-side MIME type validation and stored using Laravel’s Storage with file name sanitization.

-Input Validation (Form Requests):
Form Requests are used to centralize input validation and prevent malicious data from entering the system.

-Audit Logging (GDPR-compliant):
All critical model events are logged in the Activity log tab in the sidebar. Logs include description, user-causer ID, timestamps, and old/new data states.

-Role-based Password Policy:
In the Registration page, context-aware password validation implemented between the roles of admin and user. stronger passwords for admins (for e.g. min length=10) and different policies for regular users (for e.g. min length=6).

Performance Optimization Strategy:

-Eager Loading & Pagination [NOTE: Category Model was not paginated to test for large dataset loadtimes and redis caching]
Avoided N+1 issues with eager loading of related data. All large dataset outputs (like Patients) are paginated. 

-Redis Caching
Frequently accessed API endpoints or endpoints with possible large data (e.g., category listing) are cached using Redis via Laravel's Cache::remember().

-Optimized CSV Import
Used chunked reading and DB::insert() batching for efficient bulk CSV import of categories, ensuring memory-safe handling of 50,000+ rows.

-Database locking strategy (Optimistic Locking)
Implemented Optimistic Locking (in the ticket module) as it satisfies multiple requirements like concurrency, preventing race conditions,etc. Applied for concurrency safety using the updated_at field, preventing conflicting edits in highly concurrent environments.

Pattern Implementation Justifications

-Repository Pattern
Business logic is separated from controllers. DB interactions are abstracted into repositories to promote reusability, testability, and security.

-Service Layer
Services encapsulate logic that orchestrates operations from multiple repositories or performs business rules (e.g., PatientService).

Audit Logging (Observer Pattern)
Logs are triggered automatically using Laravel observers and the Spatie logging package.