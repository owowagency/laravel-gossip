# Gossip

A package of chatting features for the Laravel framework.

# Installation

Installing this package can be done easily via the following Artisan command.

```bash
composer require owowagency/gossip
```

# Setup

To install all the vendor files you can run the following command.

```bash
php artisan vendor:publish --provider="OwowAgency\Gossip\GossipServiceProvider"
```

This will copy all the vendor files, including configuration, migrations, resources and policies. If you wish to only install certain files you can use the command described in the next paragraphs. 

## Config

If  you wish to publish the configuration file, you can use the following command:

```bash
php artisan vendor:publish --provider="OwowAgency\Gossip\GossipServiceProvider" --tag=config
```

## Migrations

If  you wish to publish the migrations, you can use the following command:

```bash
php artisan vendor:publish --provider="OwowAgency\Gossip\GossipServiceProvider" --tag=migrations
```

## Routes

## Policies

# Usage
