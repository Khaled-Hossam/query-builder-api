# Query Builder Api

## Installation

Clone the repository

    git clone git@github.com:Khaled-Hossam/query-builder-api.git

Switch to the repo folder

    cd query-builder-api

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate


Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

(or use whatever local enviroment you are using like valet,homeseted,etc)

(i personaly use valet, if you are using valet like me, just link the project after cd to its folder `valet link`)
    

**TL;DR command list**

    git clone git@github.com:Khaled-Hossam/query-builder-api.git
    cd query-builder-api
    composer install
    cp .env.example .env
    php artisan key:generate
    

    php artisan serve






----------

# Code overview


## Folders

- `app/Http/Controllers/Api` - Contains the api controllers
- `app/Http/Requests/Api` - Contains the api form requests
- `routes` - Contains the api routes defined in api.php file
- `app/Services` - Contains the services which encapsulate all the bussiness logic
- `app/Enums` - Contains the enums


----------

# Testing API

Generating query api

    /api/generate-query

Request headers

| **Required** 	| **Key**              	| **Value**            	|
|----------	|------------------	|------------------	|
| Yes      	| Content-Type     	| application/json 	|

Body form-data

key : input

value : the uploaded file that contains the filtration criteria


example for input [sample input](sample-input.txt)

expected output would be like [sample output](sample-output.txt)

----------

