## Description
- REST API for categories and products
- Data format - JSON
- Requests:
     1) Get all categories: GET /api/categories
     2) Get one category: GET /api/categories/1
     1) Get all products: GET /api/products
     2) Get one product: GET /api/products/1

Additional task:
Make inquiries:
     Create a category: POST /api/categories
     Edit category: PUT /api/categories/1
     Delete a category: DELETE /api/categories/1
Use authorization via Bearer token (you can use laravel/sanctum)

## Installation

I mainly used the ready-made Laravel components provided to me in the implementation of the project.
My database MySql database
Laravel 10
Installation Sanctum authentication

## Usage

- To use the project, you must first git clone link the code
- You change the env.example file to .env. Step by Step
- composer install
- php artisan key:generate
- connect to your database
- php artisan migrate:fresh --seed   
- After that, if you enter the web.php file, you can start testing all the routes
- You can find my API documentation at the link below
 link: https://www.postman.com/solar-eclipse-530889/workspace/animals/collection/21513257-428c8fca-fd4a-4f53-a1ae-f8e38718964b?action=share&creator=21513257


- After installing the project you can start the server and use the swaager documentation with the link below    
- route: http://http://127.0.0.1:8000/api/documentation

