<p align="center">
  <h3 align="center">Url Shortening Website using Laravel as FullStack</h3>
  </p>
</p>

<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
        <li><a href="#built-with">Structure</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
        <li><a href="#test">Test</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#license">License</a></li>
  </ol>
</details>

<!-- ABOUT THE PROJECT -->

## About The Project

This project is a basic URL-shortening service.

### Built With

- [PHP](https://www.php.net/)
- [Laravel](https://laravel.com/)
- [TailwindCSS](https://tailwindcss.com/)

<!-- GETTING STARTED -->

## Getting Started

The project requires PHP v 8.02.
You can download [here](https://www.php.net/downloads.php).
And manually upgrade PHP version of your desktop using this [link](https://stackoverflow.com/a/49193976/3927450)
Alternatively, you can install xampp server from [here](https://www.apachefriends.org/download.html)

### Prerequisites

Make sure you have composer on your pc to run this project.
You can download from [here](https://getcomposer.org/download/)

### Installation

1. Clone the repository

   ```sh
   git clone https://github.com/codert0109/URL-Shortening-Service-Laravel-FullStack.git
   ```

2. Install NPM packages
   ```sh
   composer install
   ```
3. Create a `.env` file using the configuration in `.env.example`
   ```sh
   touch .env
   ```
4. Configurate the Database
   Create your own MySQL database which saves urls named urlshorted(You can change this db name in .env). If needs, change your username and password in .env
5. Migrate and Seed the Database
   ```sh
   php artisan migrate
   ```
   ```sh
   php artisan db:seed
   ```
6. Start the project
   ```sh
   php artisan serve
   ```
7. Deploy the project
   When deploy this project, make sure that you have changed DOMAIN_URL in .env. Also make sure that set the cron job to delete unactive links which is not visited for last 30 days/
### Test

1. Run below command

   ```sh
   php artisan test
   ```

<!-- USAGE -->

## Usage

### To add url, input the long-url and click submit.
### If a url is not visited for 30 days, then it will automatically be removed.

<!-- LICENSE -->

## License

Distributed under the MIT License.
