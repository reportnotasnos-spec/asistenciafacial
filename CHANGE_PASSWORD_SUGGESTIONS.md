
Excellent idea. Implementing a secure "Change Password" feature is a crucial part of any application with user accounts. Here are my suggestions for how to implement this functionality.

## Process Overview

The process should be secure and user-friendly. A logged-in user should be able to change their own password, but no one else's.

**The user flow would be:**
1.  The user is logged in.
2.  They navigate to a dedicated "Change Password" page (we can link this from the "Profile" dropdown item).
3.  They are presented with a form asking for their **Current Password**, a **New Password**, and to **Confirm New Password**.
4.  Upon submission, the system validates their "Current Password" to ensure the request is legitimate.
5.  If validated, the system updates their password with the new one.
6.  The user is notified of the successful change.

---

## Implementation Plan

Here is a step-by-step plan to add this feature to our current project structure.

### Step 1: Create a `UserController`

It's best to manage user-specific actions like this in a dedicated controller.
-   Create a new controller file: `app/controllers/UserController.php`.
-   Inside this controller, create a method `changePassword()`. This method will handle both displaying the form (for GET requests) and processing the submission (for POST requests).

### Step 2: Create the "Change Password" View

We need a form for the user to enter the required information.
-   Create a new view file: `resources/views/users/change_password.php`.
-   The form in this view should have three fields:
    -   `current_password` (type="password")
    -   `new_password` (type="password")
    -   `confirm_new_password` (type="password")
-   The view should also display any validation errors sent from the controller.

### Step 3: Add Routes

We need to create routes to access the new controller method.
-   In `routes/routes.php`, add the following routes:
    -   `Route::get('users/change-password', 'UserController@changePassword');`
    -   `Route::post('users/change-password', 'UserController@changePassword');`

### Step 4: Update the `User` Model

The `User` model will need a new method to handle the password update logic.
-   In `app/models/User.php`, add a new method: `updatePassword($userId, $newPassword)`.
-   This method will securely hash the `$newPassword` and update the `password` field in the `users` table for the specified `$userId`.
-   We can reuse the logic from the existing `login()` method to verify the user's `current_password` against the hash in the database.

### Step 5: Update the Navbar Link

Finally, we can make the "Profile" link in the dropdown functional.
-   In `resources/views/layouts/default.php`, change the "Profile" link from `href="#"` to `href="<?php echo URL_ROOT; ?>/users/change-password"`. For now, we'll make "Profile" and "Change Password" the same page. Later, this could be a link on a more comprehensive profile page.

---

## Security Considerations

-   **Password Hashing:** Always hash passwords using PHP's `password_hash()` function. Our current implementation already does this, and we must continue to do so.
-   **Input Validation:** Sanitize and validate all user input. The controller should check for empty fields, password length, and matching confirmation passwords.
-   **Authentication Check:** The `changePassword()` method must first verify that a user is logged in before proceeding.

## Next Steps

If you agree with this plan, I can begin the implementation. Shall I proceed?
