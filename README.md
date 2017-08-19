# stanford-nlp-tagger
[![Version](https://img.shields.io/packagist/v/patrickschur/stanford-nlp-tagger.svg?style=flat-square)](https://packagist.org/packages/patrickschur/stanford-nlp-tagger)
[![Total Downloads](https://img.shields.io/packagist/dt/patrickschur/stanford-nlp-tagger.svg?style=flat-square)](https://packagist.org/packages/patrickschur/stanford-nlp-tagger)
[![Maintenance](https://img.shields.io/maintenance/yes/2017.svg?style=flat-square)](https://github.com/patrickschur/stanford-nlp-tagger) 
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-4AC51C.svg?style=flat-square)](http://php.net/)
[![License](https://img.shields.io/packagist/l/patrickschur/stanford-nlp-tagger.svg?style=flat-square)](https://opensource.org/licenses/MIT)

A PHP wrapper for the Stanford Natural Language Processing library. Supports POSTagger and CRFClassifier.
Loads automatically the right packages and detects the language of the given text.

## Requirements
- You have to install Java in version 1.8+ or higher.
- Download the right packages and extract them into the directory. 
(The script loads automatically the right packages, no matter where they are.)

## Installation with Composer
```bash
$ composer require patrickschur/stanford-nlp-tagger
```

### Example
- Download the required packages for the POSTagger [here](https://nlp.stanford.edu/software/stanford-postagger-2017-06-09.zip) for English only 
or [here](https://nlp.stanford.edu/software/stanford-postagger-full-2017-06-09.zip) for Arabic, Chinese, French, Spanish, and German.
- Extract the (**.zip**) package into your directory. (Please do not rename the packages, only if you want to add this packages manually.)

```text
$pos = new \StanfordTagger\POSTagger();
 
$pos->tag('My dog also likes eating sausage.');
```
Results in
```text
My_PRP$ dog_NN also_RB likes_VBZ eating_JJ sausage_NN ._.
```

## setOutputFormat()
There are three ways of output formats (**xml**, **slashTags** and **tsv**)
```php
$pos = new \StanfordTagger\POSTagger();
 
$pos->setOutputFormat(StanfordTagger::OUTPUT_FORMAT_XML);
 
$pos->tag('My dog also likes eating sausage.');
```
Result as XML:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<pos>
<sentence id="0">
  <word wid="0" pos="PRP$">My</word>
  <word wid="1" pos="NN">dog</word>
  <word wid="2" pos="RB">also</word>
  <word wid="3" pos="VBZ">likes</word>
  <word wid="4" pos="JJ">eating</word>
  <word wid="5" pos="NN">sausage</word>
  <word wid="6" pos=".">.</word>
</sentence>
</pos>
```
or use 
```php 
$pos->setOutputFormat(StanfordTagger::OUTPUT_FORMAT_TSV);
```
for
```text
My	PRP$
dog	NN
also	RB
likes	VBZ
eating	JJ
sausage	NN
.	.
```

## setModel(), setJarArchive() and setClassfier()
All packages are loaded automatically but if you want to change that you can set them manually.
```php
$pos = new \StanfordTagger\POSTagger();
 
$pos->setModel(__DIR__ . '/stanford-postagger-full-2017-06-09/models/english-bidirectional-distsim.tagger');
 
$pos->setJarArchive(__DIR__ . '/stanford-postagger-full-2017-06-09/stanford-postagger.jar');
```

## CRFClassifier
- For English only, download the required packages for the CRFClassifier [here](https://nlp.stanford.edu/software/stanford-ner-2017-06-09.zip).
- You have to download the language models separately:
    - [German models](https://nlp.stanford.edu/software/stanford-german-corenlp-2017-06-09-models.jar)
    - [Spanish models](https://nlp.stanford.edu/software/stanford-spanish-corenlp-2017-06-09-models.jar)
    - [Chinese models](https://nlp.stanford.edu/software/stanford-chinese-corenlp-2017-06-09-models.jar)
- Extract the (**.jar**) files if you downloaded a language model and add them into your directory.

### Example
```php
$ner = new \StanfordTagger\CRFClassifier();
 
$ner->tag('Albert Einstein was a theoretical physicist born in Germany.');
```
```text
Albert/PERSON Einstein/PERSON was/O theoretical/O physicist/O born/O in/O Germany/LOCATION./O 
```
## Contribute

Feel free to contribute to this repository. Any help is welcome.