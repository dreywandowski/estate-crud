<p align="center"><a href="https://estate-api.dreywandowski.xyz/" target="_blank"><img src="https://dreywandowski.xyz/images/Student-Hour.svg" width="350" height="150"></a></p>

<p align="center">
<a href="#"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="#"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="#"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="#"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Books API

An API to create, read, update and delete books.
Also to get a specific book from an external API source.
 

 ### Documentation
 This is the live link to the root of the project: https://estate-api.dreywandowski.xyz/api. 


 However, how to access each of the specific routes for each endpoints have been defined in the documentation page here:
 https://dreywandowski.xyz/api_documentations/estate_books_api/
 

  ### Deployment Instructions for local use
  - PHP 7.4 at least is required.
  - Clone the repository: git clone https://github.com/dreywandowski/estate-crud.git
  - Create a .env file in the root of the project and fill in your necessary database credentials 
  - Install composer if not already installed: **composer install**, then run **composer update** to download necessary dependencies.
  - Set application key: **php artisan:key generate**
  - Clear application cache by typing each of these commands: **php artisan cache:clear, php artisan route:clear, php artisan config:clear, php artisan view:clear**
  - Run the database migrations: **php artisan migrate**
  - Start the application: **php artisan serve**
  - Run tests via **php artisan test**
 
 ### Comments
 - Feature testing Using the "BookTest" feature test file under the tests/Feature folder. Edit values in the various test methods to get consistent results with your database.
 <img src="https://dreywandowski.xyz/images/Screenshot 2022-11-03 at 09.46.21.png" width="320" height="200"></a></p>

 - In the Delete Endpoint instruction, it was specified that status code 204 be returned. However, according to information found here: https://www.rfc-editor.org/rfc/rfc2616#section-10.2.5, a 204 response doesn't have a message body, so the message required in the instruction will not show after a successful deletion.
 So I replaced that with a 200 code that will allow the response message show.

    



 
 
