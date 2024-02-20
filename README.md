# Users Project

Brief description of the project.

## Table of Contents
- [Installation](#installation)
- [Configuration](#configuration)
  - [Backend Configuration](#backend-configuration)
  - [Frontend Configuration](#frontend-configuration)
- [Usage](#usage)
  - [Adding a User](#adding-a-user)
    - [Using Tinker](#using-tinker)
    - [Using Postman](#using-postman)
  - [After Adding a User](#after-adding-a-user)
- [Testing](#testing)

## Installation

To get started with the project, clone the repository and navigate to the project directory.
After cloning, navigate to the backend directory and run the following command to install backend dependencies:
```
composer install:
```
once installed run the next command:
```
npm install
```
then navigate to the front diretory and run the next command:
```
npm install
```
## Configuration

### Backend Configuration

Copy the `.env.example` file and rename it to `.env`.
Configure the following variables in the `.env` file:

```
DB_CONNECTION=    // Database connection type (e.g., mysql)
DB_HOST=          // Database host (e.g., 127.0.0.1)
DB_PORT=          // Database port (e.g., 3306)
DB_DATABASE=      // Database name (e.g., dbname)
DB_USERNAME=      // Database username
DB_PASSWORD=      // Database password
```

After configuring the `.env` file, run the following command to create the necessary tables in the database:

```
php artisan migrate
```

Generate a JWT secret key by running the following command:

```
php artisan jwt:secret
```

If the `JWT_SECRET` variable is not generated automatically, add it manually to the `.env` file.

### Frontend Configuration

Navigate to the frontend directory.
Create or edit the `.env` file and add the following configuration:

```
API_URL=http://127.0.0.1:8000
```

## Usage

Go to the frontend URL, default URL is [http://localhost:3000/](http://localhost:3000/), then log into the app with a registered user. If you're not a registered user yet, follow these steps to add one:

### Adding a User

#### Using Tinker

Open your terminal and navigate to the project directory.
Run the command `php artisan tinker` to open Tinker.
Execute the following code to create a new user:

```
$user = new App\Models\User;
$user->name = 'User Name';
$user->email = 'email@example.com';
$user->password = bcrypt('password');
$user->save();
```

#### Using Postman

Open Postman.
Create a new POST request and set the URL to [http://localhost:8000/api/users](http://localhost:8000/api/users).
In the "Body" tab, select "raw" and JSON format.
Enter the following data:

```json
{
    "name": "User Name",
    "email": "email@example.com",
    "password": "password"
}
```

Click on "Send" to submit the request and create the new user.

### After Adding a User

Once you have successfully added a user, return to the frontend URL and log in with the newly created credentials.

## Testing

To run test, execute the following command:

```
php artisan migrate --testsuite=Feature
```
