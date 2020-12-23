# mrz-scanner
Passport MRZ Scanner

##Install
```bash
composer require bakhodir/passport-scanner
```

##INSTALL CONVERT IMAGE TO TEXT
###Install Tesseract
```bash
sudo apt install tesseract-ocr
sudo apt install libtesseract-dev
```
###Install PassportEye
```bash
pip3 install PassportEye
```

###Usage

```php
use PassportScanner\MRZScanner;

$mrzScanner= new MRZScanner();

$mrzScanner
    ->json() //parse result as json
    ->setFile('filepath/passport.jpg') //set the path of image
    ->execute(); //execute a command

$result = $mrzScanner->getResult(); // string/array
$isValid = $mrzScanner->isValid(); ///bool
$score = $mrzScanner->getScore(); //score of the result 0-100
```