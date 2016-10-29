#Skyblivion - Script Converter

This project consists of the following subsystems:

* Oblivion's OBScript Language Lexer and Parser, allows for converting OBScript to an abstract syntax tree
* AST to TES5 Skyrim Papyrus transpiler
* Build tool to orchestrate compiling transpiled scripts using Papyrus Compiler

#Installation

This project requires a few files that can not be included in the repository, and must be filled in manually:

* Original Skyrim Scripts ( to be put into BuildTargets/<Target>/Dependencies folder of your build )
* Oblivion.esm file ( Should be put into the root folder )
* Papyrus Compiler ( Put it under ./Compiler folder )

PHP is compiled using following command to enable pthreads, however, it is not required as AMP can use Native reactor implementation instead:
```
./configure --prefix=/your/php/installation --with-config-file-path=/your/php/installation/conf --with-config-file-scan-dir=/your/php/installation/conf.d --disable-all --enable-maintainer-zts --with-curl --with-openssl --with-gd --enable-filter --enable-libxml --enable-phar --enable-xml --enable-gd-native-ttf --enable-intl --enable-json --enable-mbstring --enable-hash --enable-dom --enable-sqlite3 --enable-tokenizer --with-mcrypt --with-mysqli=mysqlnd --with-zlib --with-bz2 --enable-exif --with-pdo-mysql=mysqlnd --with-libedit --enable-zip --enable-pdo --enable-pcntl --enable-sockets --enable-mbregex --with-tsrm-pthreads
```

Install the dependencies:
```
composer install
```

Install:
* Lua
* Mono 

Make sure both are available in PATH.

#Credits

* Sky Indent beautifier - PROXiCiDE aka Taewon
