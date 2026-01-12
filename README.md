# 1. Clone project
git clone https://github.com/oparin/test-blog.git \
cd test-blog \

# 2. Set environment variables
cp .env.example .env

# 3. Run Docker
docker-compose up -d --build

# 4. Run DB seeders
docker-compose exec app php /var/www/app/Seeds/Seeder.php