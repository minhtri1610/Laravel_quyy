#!/bin/bash

# Define environment variables
WEB_SERVICE="web"
DB_SERVICE="mysql"
DB_NAME="base_db"

# Check if no argument is provided
if [ -z "$1" ]; then
    echo "❌ Please provide a command to run!"
    echo "Example: ./setup.sh migrate or ./setup.sh seed"
    exit 1
fi

case "$1" in
    install)
        echo "📦 Installing Laravel dependencies..."
        docker compose exec $WEB_SERVICE composer install
        ;;
    
    createdb)
        echo "🛢️ Creating database '$DB_NAME'..."
        docker compose exec $DB_SERVICE mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;"
        ;;
    
    migrate)
        echo "🛠️ Running migrations..."
        docker compose exec $WEB_SERVICE php artisan migrate
        ;;
    
    unit-seed)
        if [ -z "$2" ]; then
            echo "❌ Please provide a seeder class name!"
            exit 1
        fi
        echo "🌱 Running unit seeder: $2..."
        docker compose exec $WEB_SERVICE php artisan db:seed --class="$2"
        ;;

    seed)
        echo "🌱 Running database seeder..."
        docker compose exec $WEB_SERVICE php artisan db:seed
        ;;
    
    fresh)
        echo "🔄 Refreshing migrations and running seeders..."
        docker compose exec $WEB_SERVICE php artisan migrate:fresh --seed
        ;;
    
    key)
        echo "🔑 Generating application key..."
        docker compose exec $WEB_SERVICE php artisan key:generate
        ;;
    
    storage)
        echo "🔗 Creating storage symlink..."
        docker compose exec $WEB_SERVICE php artisan storage:link
        ;;
    
    permission)
        echo "🔒 Setting permissions for storage and cache..."
        docker compose exec $WEB_SERVICE chmod -R 777 storage bootstrap/cache
        ;;
    
    make:controller)
        if [ -z "$2" ]; then
            echo "❌ Please provide a controller name!"
            exit 1
        fi
        echo "🎮 Creating Controller: $2Controller..."
        docker compose exec $WEB_SERVICE php artisan make:controller "$2Controller"
        ;;
    
    make:model)
        if [ -z "$2" ]; then
            echo "❌ Please provide a model name!"
            exit 1
        fi
        echo "📌 Creating Model: Entities/$2..."
        docker compose exec $WEB_SERVICE php artisan make:model "Entities/$2"
        ;;
    
    make:service)
        if [ -z "$2" ]; then
            echo "❌ Please provide a service name!"
            exit 1
        fi
        echo "⚙️ Creating Service: $2Service..."
        docker compose exec $WEB_SERVICE php artisan make:service "$2Service"
        ;;
    
    make:repository)
        if [ -z "$2" ]; then
            echo "❌ Please provide a repository name!"
            exit 1
        fi
        echo "📂 Creating Repository: $2Repository..."
        docker compose exec $WEB_SERVICE php artisan make:repository "$2Repository"
        ;;
    
    url)
        echo "✅ Access the application at: http://localhost:3000/"
        ;;
    
    *)
        echo "❌ Invalid command!"
        echo "Available commands:"
        echo "  ./setup.sh install"
        echo "  ./setup.sh createdb"
        echo "  ./setup.sh migrate"
        echo "  ./setup.sh seed"
        echo "  ./setup.sh fresh"
        echo "  ./setup.sh key"
        echo "  ./setup.sh storage"
        echo "  ./setup.sh permission"
        echo "  ./setup.sh make:controller {Name}"
        echo "  ./setup.sh make:model {Name}"
        echo "  ./setup.sh make:service {Name}"
        echo "  ./setup.sh make:repository {Name}"
        echo "  ./setup.sh url"
        exit 1
        ;;
esac
