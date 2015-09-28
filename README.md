html2pdf-service
================

[![Build Status](https://travis-ci.org/carlescliment/html2pdf-service.png)](https://travis-ci.org/carlescliment/html2pdf-service)


A REST microservice that converts html input into pdf files. Written in PHP on Silex, it uses the [KNP snappy lib](https://github.com/KnpLabs/snappy), which works over [Google's khtmltopdf](http://code.google.com/p/wkhtmltopdf/).

## Prerequisites

- PHP 5.3 or higher.
- A web server able to execute php code.
- libcurl: `apt-get install php5-curl`
- intl: `apt-get install php5-intl`


## Installation

Download or clone this repository. Then, add permissions to the documents folder:

```
$ APACHEUSER=`ps aux | grep -E '[a]pache|[h]ttpd' | grep -v root | head -1 | cut -d\  -f1`
$ sudo setfacl -R -m u:$APACHEUSER:rwX -m u:`whoami`:rwX documents
$ sudo setfacl -dR -m u:$APACHEUSER:rwX -m u:`whoami`:rwX documents
```

Update vendors running `php /path/to/your/composer.phar update`

Setup the web server to attend requests to your installation.


## Configure the binary

The default implementation is for Linux x64. If you are installing this service in other machines, you need to override the application defaults in `web/app.php`:

```
$app = new Application(__DIR__ . '/../', false);

$app['platform'] = 'mac';

$app->run();
```

Available platforms are *mac*, *linux-x64* and *linux-i368*. If you run the server in a Windows machine, you will need to specify the full binary location:

```
$app = new Application(__DIR__ . '/../', false);

$app['binary'] = 'C:\path\to\khtmltopdf.exe';

$app->run();
```


## Override default pdf output configuration

In your `web/app.php` file, include your settings like the following example:

```
$app = new Application(__DIR__ . '/../', false);

$app['default_settings'] = array(
    'encoding' => 'latin-1',
    'page-size' => 'Letter',
    );


$app->run();
```

Take a look at the [wkhtmltopdf](http://madalgo.au.dk/~jakobt/wkhtmltoxdoc/wkhtmltopdf-0.9.9-doc.html) reference for further information.


## Usage

Three different operations are provided:

| Verb          | Route            | Request parameters | Description         |
| ------------- | ---------------- | ------------------ | ------------------- |
| PUT           | /{document_name} | content            | Generates the PDF document {document_name} from the given html content |
| GET           | /{document_name} |                    | Brings the document |
| DELETE        | /{document_name} |                    | Deletes the document, if exists |


The response is always a json with a `body` key. In the GET case, it can also contain an `encoding` key with the encoding used to return the document.



## Testing

Go to the servers root directory and execute phpunit.


## Client-side implementations

* [Symfony 2 Bundle](https://github.com/carlescliment/Html2PdfServiceBundle)


## TO-DO

* Allow overriding default options from the client side
