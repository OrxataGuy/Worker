<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use \App\Models\User;
use \App\Models\Technology;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->technologies();
    }

    public function technologies() : void
    {
        $this->backend();
        $this->database();
        $this->frontend();
        $this->devops();
        $this->platform();

    }

    public function backend() : void
    {
        Technology::create([
            'name' => 'Sin backend',
            'icon' => env('APP_URL').'/img/tech/none.png',
            'context' => 'BACKEND'
        ]);

        Technology::create([
            'name' => 'Pure PHP',
            'icon' => env('APP_URL').'/img/tech/php.png',
            'context' => 'BACKEND'
        ]);

        Technology::create([
            'name' => 'Laravel',
            'icon' => env('APP_URL').'/img/tech/laravel.png',
            'context' => 'BACKEND'
        ]);

        Technology::create([
            'name' => 'CodeIgniter',
            'icon' => env('APP_URL').'/img/tech/codeigniter.png',
            'context' => 'BACKEND'
        ]);

        Technology::create([
            'name' => 'NodeJS',
            'icon' => env('APP_URL').'/img/tech/nodejs.png',
            'context' => 'BACKEND'
        ]);

        Technology::create([
            'name' => 'NestJS',
            'icon' => env('APP_URL').'/img/tech/nest.png',
            'context' => 'BACKEND'
        ]);

        Technology::create([
            'name' => 'Java',
            'icon' => env('APP_URL').'/img/tech/java.png',
            'context' => 'BACKEND'
        ]);

        Technology::create([
            'name' => 'Spring Boot',
            'icon' => env('APP_URL').'/img/tech/spring.png',
            'context' => 'BACKEND'
        ]);

        Technology::create([
            'name' => 'DotNet',
            'icon' => env('APP_URL').'/img/tech/dotnet.svg',
            'context' => 'BACKEND'
        ]);

        Technology::create([
            'name' => 'DotNet Core',
            'icon' => env('APP_URL').'/img/tech/dotnet-core.png',
            'context' => 'BACKEND'
        ]);
    }

    public function database() : void
    {
        Technology::create([
            'name' => 'Sin base de datos',
            'icon' => env('APP_URL').'/img/tech/none.png',
            'context' => 'DATABASE'
        ]);

        Technology::create([
            'name' => 'MySQL',
            'icon' => env('APP_URL').'/img/tech/mysql.png',
            'context' => 'DATABASE'
        ]);

        Technology::create([
            'name' => 'SQL Server',
            'icon' => env('APP_URL').'/img/tech/sqlserver.png',
            'context' => 'DATABASE'
        ]);

        Technology::create([
            'name' => 'Oracle',
            'icon' => env('APP_URL').'/img/tech/oracledb.png',
            'context' => 'DATABASE'
        ]);

        Technology::create([
            'name' => 'Postgresql',
            'icon' => env('APP_URL').'/img/tech/postgresql.png',
            'context' => 'DATABASE'
        ]);

        Technology::create([
            'name' => 'MongoDB',
            'icon' => env('APP_URL').'/img/tech/mongodb.png',
            'context' => 'DATABASE'
        ]);
    }

    public function frontend() : void
    {
        Technology::create([
            'name' => 'Blade',
            'icon' => env('APP_URL').'/img/tech/laravel.png',
            'context' => 'FRONTEND'
        ]);

        Technology::create([
            'name' => 'Bootstrap',
            'icon' => env('APP_URL').'/img/tech/bootstrap.png',
            'context' => 'FRONTEND'
        ]);

        Technology::create([
            'name' => 'ASP.NET',
            'icon' => env('APP_URL').'/img/tech/asp.png',
            'context' => 'FRONTEND'
        ]);

        Technology::create([
            'name' => 'Vue',
            'icon' => env('APP_URL').'/img/tech/vue.png',
            'context' => 'FRONTEND'
        ]);

        Technology::create([
            'name' => 'React',
            'icon' => env('APP_URL').'/img/tech/react.png',
            'context' => 'FRONTEND'
        ]);

        Technology::create([
            'name' => 'Angular',
            'icon' => env('APP_URL').'/img/tech/angular.png',
            'context' => 'FRONTEND'
        ]);

        Technology::create([
            'name' => 'HTML',
            'icon' => env('APP_URL').'/img/tech/html.png',
            'context' => 'FRONTEND'
        ]);
    }

    public function platform() : void
    {
        Technology::create([
            'name' => 'Prestashop',
            'icon' => env('APP_URL').'/img/tech/prestashop.png',
            'context' => 'PLATFORM'
        ]);

        Technology::create([
            'name' => 'Wordpress',
            'icon' => env('APP_URL').'/img/tech/wordpress.png',
            'context' => 'PLATFORM'
        ]);
    }

    public function devops() : void
    {
        Technology::create([
            'name' => 'Azure',
            'icon' => env('APP_URL').'/img/tech/azure.png',
            'context' => 'DEVOPS'
        ]);

        Technology::create([
            'name' => 'Amazon Web Services',
            'icon' => env('APP_URL').'/img/tech/aws.png',
            'context' => 'DEVOPS'
        ]);

        Technology::create([
            'name' => 'Google Cloud',
            'icon' => env('APP_URL').'/img/tech/googlecloud.png',
            'context' => 'DEVOPS'
        ]);

        Technology::create([
            'name' => 'Jenkins',
            'icon' => env('APP_URL').'/img/tech/jenkins.png',
            'context' => 'DEVOPS'
        ]);

        Technology::create([
            'name' => 'Ansible',
            'icon' => env('APP_URL').'/img/tech/ansible.png',
            'context' => 'DEVOPS'
        ]);

        Technology::create([
            'name' => 'Terraform',
            'icon' => env('APP_URL').'/img/tech/terraform.png',
            'context' => 'DEVOPS'
        ]);

        Technology::create([
            'name' => 'Docker',
            'icon' => env('APP_URL').'/img/tech/docker.png',
            'context' => 'DEVOPS'
        ]);


    }
}
