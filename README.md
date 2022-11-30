[![Build Status](https://app.travis-ci.com/magujun/fifaworldcups.svg?branch=main)](https://app.travis-ci.com/magujun/fifaworldcups)

# FIFAWorldCups

<h3 align="center">
     <img width="75px" src="">
    <img width="250px" src="">
        <img width="75px" src="">
    <br><br>
    <p align="center">
      <a href="#-technology">Technology</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
      <a href="#-setup">Setup</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
      <a href="#-contribute">Contribute</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
      <a href="#-license">License</a>
  </p>
</h3>

## 🔖 About

[![GitHub forks](https://img.shields.io/github/forks/magujun/fifaworldcups?style=social)](https://github.com/magujun/fifaworldcups/network/members/)
[![GitHub stars](https://img.shields.io/github/stars/magujun/fifaworldcups?style=social)](https://github.com/magujun/fifaworldcups/stargazers/)

A <strong>PHP</strong> based website that offers users data from all **[FIFA™](https://www.fifa.com/)** World Cups with data from **[Kaggle](https://www.kaggle.com/)** .

## 🚀 Technology

This project is being developed with the following technologies:

- [PHP](https://www.java.com/en/) :: PHP™ Language 
- [MySQL](https://mySQL.org/) :: MySQL Relational DB
- [Javascript](https://javascript.org/) :: ECMAScript/Javascript Language
- [HTML5](https://w3c.org/) :: HTML5 Hypertext Markup Language
- [CSS3](https://w3c.org/) :: CSS3 Cascading Style Sheets
- [Apache](https://apache.org/) :: Apache2 Web Server
- [NGINX](https://nginx.org/) :: NGINX Web Server
- [OpenSSL](https://openSSL.org/) :: OpenSSL
- [LetsEncrypt](https://openSSL.org/) :: LetsEncrypt CA
- [...Work in progress](https://github.com/magujun/fifaworldcups)

## ⤵ Setup

These instructions will take you to a copy of the project running on your local machine for testing and development purposes.

```bash
    - git clone https://github.com/magujun/fifaworldcups.git
    - cd FIFAWorldCups
```    

**NOTE:**

The following commands are an example for a GNU/Linux x86_64 environment (Debian-based).
```    
    - sudo apt-get install unzip
    - mkdir tmp/
    - curl -L https://download2.gluonhq.com/openjfx/18/openjfx-18_linux-x64_bin-sdk.zip > tmp/openjfx-18_linux-x64_bin-sdk.zip
    - unzip tmp/openjfx-18_linux-x64_bin-sdk.zip -d tmp/
 ```
 *For other platforms, please check [openJFX.io](https://gluonhq.com/products/javafx/) and download the openjfx SDK for your system.*
 
 **COMPILE**
 ```   
    - mkdir bin/
    - javac -d bin --module-path tmp/javafx-sdk-18/lib --add-modules=javafx.controls,javafx.media,javafx.graphics -classpath bin:tmp/javafx-sdk-18/lib/javafx-swt.jar:tmp/javafx-sdk-18/lib/javafx.base.jar:tmp/javafx-sdk-18/lib/javafx.controls.jar:tmp/javafx-sdk-18/lib/javafx.graphics.jar:tmp/javafx-sdk-18/lib/javafx.media.jar:tmp/javafx-sdk-18/lib/javafx.swing.jar:tmp/javafx-sdk-18/lib/javafx.web.jar src/*
```

**RUN**
```
    - java --module-path tmp/javafx-sdk-18/lib --add-modules=javafx.controls,javafx.media,javafx.graphics -classpath bin:tmp/javafx-sdk-18/lib/javafx-swt.jar:tmp/javafx-sdk-18/lib/javafx.base.jar:tmp/javafx-sdk-18/lib/javafx.controls.jar:tmp/javafx-sdk-18/lib/javafx.graphics.jar:tmp/javafx-sdk-18/lib/javafx.media.jar:tmp/javafx-sdk-18/lib/javafx.swing.jar:tmp/javafx-sdk-18/lib/javafx.web.jar Minepark
```

## 🎓 Who taught?

All the Java and PHP classes that led me to develop this project were taught by **[Dan K. Ling](https://github.com/dling)** as part of Okanagan College's Computer Information Systems **COSC-213** Course.

## 🤔 Contribute

- Fork this repository;
- Create a branch with your feature: `git checkout -b my-feature`;
- Commit your changes: `git commit -m 'feat: My new feature'`;
- Push to your branch: `git push origin my-feature`.

After the merge of your pull request is done, you can delete your branch.

## 📝 License

This project is under the MIT license.<br/>
See the [LICENSE](LICENSE) file for more details.

---

<h4 align="center">
  Done with ❤ by <a href="https://www.linkedin.com/in/marcelo-guimaraes-junior/" target="_blank">Marcelo Guimarães Junior.</a><br/>
</h4>
