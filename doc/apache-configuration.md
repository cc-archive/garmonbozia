Garmonbozia separates out the front-end, search ("data") and API code into
their own directories. Each search provider is its own file within the data
directory. This allows fine-grained separation of the code onto (multiple)
separate servers as required.

To run the code on a single server, the following Apache 2.4 configuration
should be used:

```
<VirtualHost *:80>
	ServerName garmonbozia.dev
	DocumentRoot /srv/garmonbozia/public
	Alias /data /srv/garmonbozia/data
	Alias /api /srv/garmonbozia/api

	<Directory /srv/garmonbozia/public>
	    Require all granted
	</Directory>

	<Directory /srv/garmonbozia/data>
	    Require all granted
	</Directory>

	<Directory /srv/garmonbozia/api/v1>
            Require all granted
            Options Indexes FollowSymLinks MultiViews
            AllowOverride All
            RewriteEngine On
            RewriteBase /api/v1/
            RewriteRule ^index\.php$ - [L]
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule . /api/v1/index.php [QSA,NC,L]
    	</Directory>

</VirtualHost>
```

Where "garmonbozia.dev" is replaced with the actual host name and
"/srv/garmonbozia" is replaced with the path to your checked out version of the
Garmonbozia source code.
