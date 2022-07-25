# Everyware-web
This website is used to configure data for the [Everyware android application](https://github.com/onyilam10/Everyware-android/). This webservice is built with Laravel 8.x and MongoDB.
## Getting started developers guide.
In order to start developing you'll need a few things. This doc explains how to get up and running with a local copy of Everyware-web.

### Requirements
1. `PHP` version `7.4.3`
2. Any recent version of `composer`
3. `MongoDB` version `4.4.6`

### Next steps
These steps should be taken in the root directory of this repository.
1. Run `composer install` (upgrade memory limit if needed).
2. Run `npm run watch` to build the SCSS.
3. Configure the `.env` -> `.env.example` has been added to get you started.
4. Run `php artisan key:generate` (this adds your custom key in the `.env`).
5. Run `php artisan migrate:fresh --seed` to create and seed the database.
6. Run `php artisan serve` to locally host the web application.
7. You should be able to visit `http://127.0.0.1:8000` (default link). 
8. Happy coding!

### Login
To simplify the login workflow, the seeder has default users to work with. 

```
Route: http://127.0.0.1:8000/login

Admin:
Email: admin@example.com
Password: password

Teacher:
Email: teacher@example.com
Password: password

Student:
Email: student@example.com
Password: password
```

### Topdown view
This project is built with the Laravel (8.x) framework. It utilizes the Laravel MongoDB package by jenssegers to support MongoDB as database. Documentation about this package can be found [here](https://github.com/jenssegers/laravel-mongodb). This project uses the MVC infrastructure.
1. Models can be found in `app/Models`
2. Controllers can be found in `app/Http/Controllers`
3. Views can be found in `resources/views`
4. API docs can be found at `http://127.0.0.1:8000/api`

Bootstrap is installed and mainly used to style the website. However if you please to add custom styles, you can find them in `resources/sass`. Don't forget to run `npm run watch` to (actively) build your SCSS to CSS. 

### Hardcodes files
```
All files can be found in the `public` directory.

login-logo.png -> Logo at the login page

Backgrounds
    login-bg.png -> Login Background
    home-bg.png -> Background of the home page
    materials-bg.png -> Background of all pages except the home and login page

Question Icons
    arithmatic-image-icon.png -> Math image preview icon
    icon-drag.jpg -> Drag image preview icon
    imgpreview.png -> Image question type preview icon
    listening-icon.pcng -> Listening question preview icon

Answer Icons
    text-icon.png -> Text answer preview icon
    voice-icon.png -> Speech answer preview icon
    writing-icon.png -> Writing answer preview icon 
```
