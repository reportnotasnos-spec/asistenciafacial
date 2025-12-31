
I have created a migration to create the `users` table and a script to run it.

**To run the migration and create the `users` table, follow these steps:**

1.  **Verify Database Configuration:** Make sure your `config/database.php` file has the correct credentials for your Laragon database. You must create the database manually in HeidiSQL first.
    ```php
    // Example from config/database.php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', ''); // Default for Laragon
    define('DB_NAME', 'asistenciafacial'); // Make sure this database exists
    ```

2.  **Run the Migration Script:** Open your terminal or command prompt, navigate to the project directory (`F:\appdev\www\asistenciafacial`), and run the following command:
    ```bash
    php migrate.php
    ```

This will execute the migration, and you should see a `users` table and a `migrations` table in your database.

Once you have run the migration, I will proceed with the next steps of implementing user authentication (creating the model, controller, and views).
