# Workopia PHP Project

This is a small job listing project developed as part of [Brad Traversy's PHP course](https://www.traversymedia.com/php-from-scratch). The project focuses on mastering PHP fundamentals and structuring an application using vanilla PHP without relying on frameworks.

---

## Features

- Organized file structure for scalability and maintainability.
- Separation of concerns: controllers, views, and utility functions.
- Basic routing system implemented from scratch.
- Session Management: built-in session handling for user interactions.

---

## Project Structure

### **Public Folder**

Contains all files accessible by the web server:

- `index.php`: The entry point of the application.
- Static assets like CSS and images.

### **App Folder**

Contains core application logic:

- **Controllers**: contains classes with methods invoked via routing.
- **Views**: handles rendering content for the user.
- (Optional) **Models**: not included in this project but can be added to manage database interactions.

### **Framework Folder**

Includes custom building blocks for the project, implemented as classes. These components provide core functionality and can be reused or extended as needed. Examples include:

- **Middleware**: handles tasks that should execute between routing and the controller, e.g., authentication checks (e.g., preventing logged-in users from accessing the login page).
- **Router Class**: manages URL routing and maps requests to the appropriate controllers and methods.
- **Database Class**: establishes and handles database connections and queries.
- **Session Class**: manages session lifecycle and variables.
- **Validation Class**: provides methods to validate strings, emails, and other inputs.

### **Config Folder**

Contains configuration files, such as:

- `database.php`: For managing database connection settings.

### **Root Files**

- `helpers.php`: provides reusable utility functions.
- `routes.php`: maps URLs to the corresponding controllers and actions.

---

## Key Takeaways

- Learned how to structure a project using vanilla PHP.
- Built a clear file organization system for scalability and ease of development.
- Gained practical experience in implementing routing and working with controllers and views.
