# System-Integration-Mandatory-Web-Service
Mandatory semester assignment

#Features

The image recognition API trained on cifar10 dataset, can try to differentiate between images of planes and
birds.
Trained with 25 epochs, achieved accuraccy of average 75% based on the test data set.
Accurracy could be increased up to 80% by training with 100 epochs.

#Structure

Project is divided into 3 part:

**Python server** - handles image recognition

**PHP server** - handles REST / SOAP APIs

**Java client** - Consumes APIs

#Installation

### Python server

Requires a python environment with tensorflow, Keras (preferably GPU version)

Tensorflow installation guide - https://www.tensorflow.org/install/

Upon running the server, it will train the first model on cifar10 upon the first incoming request.

Additional dependencies:

    -Pillow

    -flask

### PHP server

Requires an apache server, with PHP 7 and a MySQL Database

Dependencies can be installed through composer - https://getcomposer.org/doc/00-intro.md

Database configuration can be edited in `config/db.php`

### Java client

Requires java

#Author

Kaspars Cinis