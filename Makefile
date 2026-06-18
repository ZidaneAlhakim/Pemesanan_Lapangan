.PHONY: setup run db-init clean wasmer-run wasmer-deploy

# ─── Development ───────────────────────────────────────────

## Start PHP built-in server (no Apache needed)
run:
	php -S localhost:8000 -t public/ server.php

## Initialize database (auto-detect MySQL or SQLite)
db-init:
	php database/init.php

## Clean uploads & SQLite DB
clean:
	rm -rf public/assets/uploads/payments/*
	rm -f database/data.db
	rm -rf database/data.db-journal
	@echo "Cleaned uploads and SQLite database."

## Full setup: database init + upload dir
setup: db-init
	mkdir -p public/assets/uploads/payments
	@echo "SportVenue setup complete!"

# ─── Wasmer ───────────────────────────────────────────────

## Run with Wasmer (SQLite mode — no MySQL needed)
wasmer-run:
	wasmer run php -- php -S 0.0.0.0:8080 -t public/ server.php

## Deploy to Wasmer Edge
wasmer-deploy:
	wasmer deploy

## Login to Wasmer
wasmer-login:
	wasmer login

# ─── Info ──────────────────────────────────────────────────

help:
	@echo "SportVenue CLI"
	@echo "──────────────"
	@echo "make run          Start dev server (localhost:8000)"
	@echo "make db-init      Initialize database (MySQL or SQLite)"
	@echo "make setup        Full setup: DB + storage"
	@echo "make wasmer-run   Run with Wasmer (port 8080)"
	@echo "make wasmer-deploy Deploy to Wasmer Edge"
	@echo "make clean        Remove uploads & SQLite DB"
