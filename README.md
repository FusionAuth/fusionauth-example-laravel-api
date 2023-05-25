# Example: Using a Laravel API with FusionAuth

This project contains an example project that illustrates using FusionAuth with Laravel10 for an API.

## Prerequisites

You will need the following things properly installed on your computer.

* [Git](http://git-scm.com/): Presumably you already have this on your machine if you are looking at this project locally; if not, use your platform's package manager to install git, and `git clone` this repo.
* [PHP](https://www.php.net/): PHP can be installed via a variety of methods
* [Docker](https://www.docker.com) and [Docker Compose](https://docs.docker.com/compose/): For standing up FusionAuth from within a Docker container. (You can [install it other ways](https://fusionauth.io/docs/v1/tech/installation-guide/), but for this example you'll need Docker.)

## Installation

* Clone this repository
  * `git clone https://github.com/FusionAuth/fusionauth-example-laravel-api`
* Enter the directory
  * `cd fusionauth-example-laravel-api`
* Start the FusionAuth instance _(this can take a while)_
  * `cd fusionauth && docker compose up -d`
* Enter the Laravel directory
  * `cd ../laravel`
* Install dependencies _(this can take a while)_
  * `composer install`
* Set up the database
  * `./vendor/bin/sail artisan migrate`
* Start the Laravel API
  * `./vendor/bin/sail up -d`

### To stop everything

In the `fusionauth-example-laravel-api` directory:

* `cd fusionauth && docker compose stop`
* `cd ../laravel && ./vendor/bin/sail stop`

## FusionAuth Configuration

This example assumes that you will run FusionAuth from a Docker container. In the `fusionauth` directory of this project are two files: [a Docker compose file](./fusionauth/docker-compose.yml) and an [environment variables configuration file](./fusionauth/.env). Assuming you have Docker installed on your machine, run `cd fusionauth && docker compose up -d` to bring FusionAuth up on your machine.

The FusionAuth configuration files also make use of a unique feature of FusionAuth, called Kickstart: when FusionAuth comes up for the first time, it will look at the [Kickstart file](./fusionauth/kickstart/kickstart.json) and mimic API calls to configure FusionAuth for use. It will perform all the necessary setup to make this demo work correctly, but if you are curious as to what the setup would look like by hand, the "FusionAuth configuration (by hand)" section of this README describes it in detail.

For now, get FusionAuth in Docker up and running (via `docker compose up`) if it is not already running; to see, [click here](http://localhost:9011/) to verify it is up and running.

> **NOTE**: If you ever want to reset the FusionAuth system, delete the volumes created by docker compose by executing `docker compose down -v`. FusionAuth will only apply the Kickstart settings when it is first run (e.g., it has no data configured for it yet).

## Running / Development

* `cd laravel && ./vendor/bin/sail up -d`

### Make an API Call

First, you need to retrieve the generated public key and import it in Laravel.

If you have [jq](https://stedolan.github.io/jq/download/) _(a script to parse JSON objects)_ installed, you can run the command below from the `laravel` folder to fetch it directly.

```shell
curl -H 'Authorization: this_really_should_be_a_long_random_alphanumeric_value_but_this_still_works' http://localhost:9011/api/key/1afa4d7e-76f0-45e9-bb46-98be5329ef37 | jq -r '.key.publicKey' > storage/public-key.pem
```

If you don't have it, log into the [FusionAuth admin screen](http://localhost:9011) using the admin user credentials ("admin@example.com"/"password"), navigate to `Settings > Key Master`, locate the key named `For exampleapp` and click its download button. Inside the downloaded `.zip` file, go to the `keys` folder and extract `public-key.pem` to the `laravel/storage` directory located in the `fusionauth-example-laravel-api` repository you cloned.

Now, you need to call FusionAuth to get an access token. For ease of use, these instructions will use the Login API, but you could also get the access token via the hosted login pages.

```shell
curl -H 'Authorization: this_really_should_be_a_long_random_alphanumeric_value_but_this_still_works' http://localhost:9011/api/login -H 'Content-type: application/json' -d '{"loginId": "richard@example.com", "password": "password", "applicationId": "e9fdb985-9173-4e01-9d73-ac2d60d1dc8e"}'
```

Now, copy the `token` value, then place it in the `Authorization` header when calling your Laravel API.

```shell
curl -H 'Accept: application/json' -H 'Authorization: Bearer <TOKEN_VALUE>' http://localhost/api/messages
```

You should get a success message:

```shell
{"messages":["Hello, world!"]}
```

### Further Exploration

Log into the [FusionAuth admin screen](http://localhost:9011) using the admin user credentials ("admin@example.com"/"password") to explore the admin user experience.

Give a user the role of `admin` and see what kind of message you get.
