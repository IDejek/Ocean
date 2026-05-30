server {
    listen 80;
    server_name babaridadive.com www.babaridadive.com;
    return 301 https://babaridadive.com$request_uri;
}

server {
    listen 443 ssl http2;
    server_name babaridadive.com www.babaridadive.com;

    root /var/www/babaridadive.com/public_html;
    index index.php index.html;

    # SSL Paths dari Certbot
    ssl_certificate /etc/letsencrypt/live/babaridadive.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/babaridadive.com/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Permissions-Policy "geolocation=(self), camera=(), microphone=()" always;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml application/json application/javascript application/xml+rss application/atom+xml image/svg+xml;

    # Main WordPress Rules
    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    # PHP-FPM Processing
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_intercept_errors on;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }

    # Static Assets Caching
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot|avif|webp)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Disable Access to Sensitive Files
    location ~ /\.ht {
        deny all;
    }
    location ~ /wp-config\.php {
        deny all;
    }
    location ~ /xmlrpc\.php {
        deny all;
    }
    location ~* ^/wp-content/uploads/.*\.php$ {
        deny all;
    }

    # Webhook Endpoints (Allow external access)
    location ~ ^/(midtrans-webhook|stripe-webhook)/?$ {
        try_files $uri $uri/ /index.php?$args;
    }
}
