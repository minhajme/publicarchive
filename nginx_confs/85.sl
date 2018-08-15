server {
	listen 85;
#	listen [::]:85;

	server_name sl;

    #return 302 https://localhost:485$request_uri;
    
   listen 485 ssl;
    include snippets/self-signed.conf;
    include snippets/ssl-params.conf;

	root /home/path/to/sl;
	index index.php index.html;

	location / {
		try_files $uri $uri/ =404;
	}
	
	location ~ \.php$ {
		include snippets/fastcgi-php.conf;
		
		fastcgi_pass unix:/run/php/php7.0-fpm.sock;
	}
}
