# Shortcut cho các lệnh Docker / Laravel hay dùng.
# Chạy `make` hoặc `make help` để xem danh sách lệnh.
#
# Lệnh cần tham số truyền qua biến `name`, vd: make controller name=Admin/User

WEB_SERVICE := web
DB_SERVICE  := mysql
DB_NAME     := base_db
# Mật khẩu root MySQL (docker/config/dev.env mặc định để trống)
DB_PASSWORD ?=
WEB_PORT    := $(or $(shell grep -E '^WEB_PORT=' .env 2>/dev/null | cut -d= -f2 | tr -d ' "'),8080)

WEB     := docker compose exec $(WEB_SERVICE)
ARTISAN := $(WEB) php artisan

# Cấu trúc thư mục storage Laravel cần (src/storage bị .gitignore nên clone về không có)
STORAGE_DIRS := storage/app/public storage/framework/cache/data storage/framework/sessions \
                storage/framework/testing storage/framework/views storage/logs bootstrap/cache
MYSQL   := docker compose exec $(DB_SERVICE) mysql -u root $(if $(DB_PASSWORD),-p$(DB_PASSWORD))
# Bản không cần TTY, dùng cho lệnh chạy tự động
MYSQL_T := docker compose exec -T $(DB_SERVICE) mysql -u root $(if $(DB_PASSWORD),-p$(DB_PASSWORD))

# Thêm hậu tố nếu tên chưa có: $(call add_suffix,User,Service) -> UserService
add_suffix = $(if $(filter %$(2),$(1)),$(1),$(1)$(2))

# Báo lỗi nếu thiếu biến name: $(call need,ví dụ)
need = @test -n "$(name)" || { echo "❌ Thiếu tham số name, vd: make $@ name=$(1)"; exit 1; }

.DEFAULT_GOAL := help
.PHONY: help env wait-db dirs up down restart build ps logs shell mysql \
	    setup install key storage permission createdb \
	    migrate rollback seed fresh \
	    controller model service repository migration seeder \
	    artisan composer tinker routes clear test url

##@ Docker

up: .env ## Khởi động container (chạy nền)
	docker compose up -d

down: ## Dừng và xóa container
	docker compose down

restart: ## Khởi động lại container
	docker compose restart

build: .env ## Build lại image rồi khởi động
	docker compose up -d --build

ps: ## Xem trạng thái container
	docker compose ps

logs: ## Xem log, vd: make logs name=web (Ctrl+C để thoát)
	docker compose logs -f --tail=100 $(name)

shell: ## Mở bash trong container web
	$(WEB) bash

mysql: ## Mở MySQL client
	$(MYSQL) $(DB_NAME)

##@ Cài đặt

setup: env up wait-db createdb permission install key migrate seed storage ## Cài lần đầu: tạo .env, up, tạo DB, thư mục storage, install, key, migrate, seed, ...
	@echo "✅ Xong! Truy cập: http://localhost:$(WEB_PORT)/"

env: .env src/.env ## Tạo .env (Docker) và src/.env (Laravel) nếu chưa có

# Chỉ tạo khi file chưa tồn tại, không ghi đè file đã có
.env:
	cp .env-docker-example .env
	@echo "✅ Đã tạo .env từ .env-docker-example"

src/.env:
	cp src/.env.example src/.env
	sed -i \
	    -e 's|^DB_HOST=.*|DB_HOST=$(DB_SERVICE)|' \
	    -e 's|^DB_DATABASE=.*|DB_DATABASE=$(DB_NAME)|' \
	    -e 's|^DB_PASSWORD=.*|DB_PASSWORD=$(DB_PASSWORD)|' \
	    -e 's|^MAIL_HOST=.*|MAIL_HOST=mail|' \
	    -e 's|^APP_URL=.*|APP_URL=http://localhost:$(WEB_PORT)|' \
	    src/.env
	@echo "✅ Đã tạo src/.env (DB_HOST=$(DB_SERVICE), DB_DATABASE=$(DB_NAME))"

wait-db: ## Chờ MySQL sẵn sàng nhận kết nối
	@echo "⏳ Đang chờ MySQL..."
	@for i in $$(seq 1 60); do \
	    $(MYSQL_T) -h 127.0.0.1 -e 'SELECT 1' >/dev/null 2>&1 && { echo "✅ MySQL đã sẵn sàng"; exit 0; }; \
	    sleep 2; \
	done; \
	echo "❌ MySQL chưa sẵn sàng sau 120s, xem log: make logs name=mysql"; exit 1

install: ## composer install
	$(WEB) composer install

key: ## Tạo APP_KEY
	$(ARTISAN) key:generate

storage: ## Tạo symlink storage
	$(ARTISAN) storage:link

dirs: ## Tạo các thư mục storage còn thiếu
	$(WEB) mkdir -p $(STORAGE_DIRS)

permission: dirs ## Tạo thư mục storage (nếu thiếu) và cấp quyền ghi
	$(WEB) chmod -R 777 storage bootstrap/cache

createdb: ## Tạo database nếu chưa có
	$(MYSQL_T) -e "CREATE DATABASE IF NOT EXISTS $(DB_NAME) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

##@ Database

migrate: ## Chạy migration
	$(ARTISAN) migrate

rollback: ## Rollback migration gần nhất
	$(ARTISAN) migrate:rollback

seed: ## Chạy seeder, vd: make seed hoặc make seed name=createRolesSeeder
	$(ARTISAN) db:seed $(if $(name),--class="$(name)")

fresh: ## Xóa hết bảng, migrate lại và seed (hỏi xác nhận)
	@read -r -p "⚠️  Lệnh này sẽ XÓA TOÀN BỘ dữ liệu trong database. Tiếp tục? [y/N] " ans; \
	    case "$$ans" in [yY]) ;; *) echo "Đã hủy."; exit 1 ;; esac
	$(ARTISAN) migrate:fresh --seed

##@ Tạo code (không cần gõ hậu tố, tự thêm nếu thiếu)

controller: ## vd: make controller name=Admin/User  -> Admin/UserController
	$(call need,Admin/User)
	$(ARTISAN) make:controller "$(call add_suffix,$(name),Controller)"

model: ## vd: make model name=User  -> Entities/User
	$(call need,User)
	$(ARTISAN) make:model "Entities/$(patsubst Entities/%,%,$(name))"

service: ## vd: make service name=User  -> app/Services/UserService/*
	$(call need,User)
	$(ARTISAN) make:service "$(call add_suffix,$(name),Service)"

repository: ## vd: make repository name=User  -> UserServiceRepository (+ Interface)
	$(call need,User)
	$(ARTISAN) make:repository "$(call add_suffix,$(patsubst %Repository,%,$(name)),Service)"

migration: ## vd: make migration name=create_posts_table
	$(call need,create_posts_table)
	$(ARTISAN) make:migration "$(name)"

seeder: ## vd: make seeder name=createPostsSeeder
	$(call need,createPostsSeeder)
	$(ARTISAN) make:seeder "$(name)"

##@ Tiện ích

artisan: ## Chạy artisan bất kỳ, vd: make artisan cmd="route:list"
	$(ARTISAN) $(cmd)

composer: ## Chạy composer bất kỳ, vd: make composer cmd="require x/y"
	$(WEB) composer $(cmd)

tinker: ## Mở php artisan tinker
	$(ARTISAN) tinker

routes: ## Liệt kê route
	$(ARTISAN) route:list

clear: ## Xóa toàn bộ cache (config, route, view, ...)
	$(ARTISAN) optimize:clear

test: ## Chạy test, vd: make test cmd="--filter=ExampleTest"
	$(ARTISAN) test $(cmd)

url: ## In địa chỉ truy cập ứng dụng
	@echo "✅ Truy cập ứng dụng: http://localhost:$(WEB_PORT)/"

help: ## Hiện hướng dẫn này
	@awk 'BEGIN {FS = ":.*## "; printf "Cách dùng: make <lệnh> [name=...] [cmd=...]\n"} \
	    /^##@/ {printf "\n\033[1m%s\033[0m\n", substr($$0, 5)} \
	    /^[a-zA-Z_-]+:.*## / {printf "  \033[36m%-12s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
