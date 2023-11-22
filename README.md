# DNS Configger

DNS Configger is a tool designed to simplify the management of DNS records, particularly for digital environments. It's built using Laravel, a robust PHP framework, ensuring a solid foundation for web application development.

## Features

- **DNS Record Management**: Manage your DNS records with an intuitive interface.
- **DigitalOcean Integration**: Seamless integration with DigitalOcean for DNS services.
- **User-Friendly Interface**: Built with Laravel, the tool provides a user-friendly web interface for managing DNS records.

## Why?
IDK.
I needed a tool,
so I could provide one of my customers with the ability
to change his dns-setup himself without compromising all my clients'
data on him.
Maybe you can find some use for this—I would love to hear it if you do.

## Getting Started—no docker

To get started with the DNS Configger, clone the repository and set up the environment.

### Prerequisites

- PHP
- Composer
- Laravel

### Installation

1. Clone the repository:
   ```
   git clone https://github.com/tvup/dns-configger.git
   ```
2. Install dependencies:
   ```
   composer install
   ```
3. Set up your environment file:
   ```
   cp .env.example .env
   ```
   And set your api-key for DigitalOcean and the domain in question.
   env variables to be filled are mainly
   in the last part of the file.

## Getting Started - docker/sail
## Installation
<p>It's strongly recommended that you add an alias to your bash/zsh config</p>
<p>It will make it much easier to run the sail command</p>

```bash
alias sail="./vendor/bin/sail"
```

### Install dependencies
You will have to install dependencies locally, because we use `sail` which is located in the `/vendor` folder.

```bash
composer install --ignore-platform-reqs
```

### Set default environment variables and api-keys etc.

```bash
cp .env.example .env
```

### Start application

#### Docker (might take a while first time)
```bash
sail up -d
```
[if you get "Docker is not running." this link might be helpful](https://docs.docker.com/engine/install/linux-postinstall/)

if you get bind problems for e.g., tcp4 0.0.0.0:80 (http) or tcp4 0.0.0.0:3306 (mysql),
you can change the forward ports in .env like these examples:
```.dotenv
APP_PORT=8001
FORWARD_DB_PORT=3308
```

### Generate a new App Key
```bash
sail artisan key:generate
```


### Build NPM & Vite components

```bash
sail npm install
sail npm run build 
```

### Now is a good time to view all the nice stuff
Navigate to http://localhost/
(if you set the APP_PORT, you should include this in link also, e.g.: http://localhost:8001)


## Usage

After installation, you can start managing your DNS records through the web interface.

## Contributing

Contributions to DNS Configger are welcome.

## License

DNS Configger is open-sourced software licensed under the MIT license.
