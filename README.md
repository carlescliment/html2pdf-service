html2pdf-service
================

[![Build Status](https://travis-ci.org/carlescliment/html2pdf-service.png)](https://travis-ci.org/carlescliment/html2pdf-service)


A REST microservice that converts html input into pdf files. Written in Silex.


## Installation

Download or clone this repository. Then, add permissions to the documents folder:

```
$ APACHEUSER=`ps aux | grep -E '[a]pache|[h]ttpd' | grep -v root | head -1 | cut -d\  -f1`
$ sudo setfacl -R -m u:$APACHEUSER:rwX -m u:`whoami`:rwX documents
$ sudo setfacl -dR -m u:$APACHEUSER:rwX -m u:`whoami`:rwX documents
```

Update vendors running `php /path/to/your/composer.phar update`

Configure the web server to attend requests to your installation.


## Usage

Three different operations are provided:

| Verb          | Route            | Request parameters | Description         |
| ------------- | ---------------- | ------------------ | ------------------- |
| PUT           | /{document_name} | content            | Generates the PDF document {document_name} from the given html content |
| GET           | /{document_name} |                    | Brings the document |
| DELETE        | /{document_name} |                    | Deletes the document, if exists |


The response is always a json with a `body` key. In the GET case, it can also contain an `encoding` key with the encoding used to return the document.



## Override default configuration

In your `web/app.php` file, include your settings like the following example:

```
$app = new Application(__DIR__ . '/../', false);

$app['default_settings'] = array(
    'encoding' => 'latin-1',
    'page-size' => 'Letter',
    );


$app->run();
```


## Client-side implementations

* [Symfony 2 Bundle](https://github.com/carlescliment/Html2PdfServiceBundle)


## TO-DO

* Allow overriding default options from the client side
