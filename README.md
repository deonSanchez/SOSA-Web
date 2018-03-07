## Status

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/BlackrockDigital/startbootstrap-freelancer/master/LICENSE)
[![npm version](https://img.shields.io/npm/v/startbootstrap-freelancer.svg)](https://www.npmjs.com/package/startbootstrap-freelancer)
[![Build Status](https://travis-ci.org/BlackrockDigital/startbootstrap-freelancer.svg?branch=master)](https://travis-ci.org/BlackrockDigital/startbootstrap-freelancer)
[![dependencies Status](https://david-dm.org/BlackrockDigital/startbootstrap-freelancer/status.svg)](https://david-dm.org/BlackrockDigital/startbootstrap-freelancer)
[![devDependencies Status](https://david-dm.org/BlackrockDigital/startbootstrap-freelancer/dev-status.svg)](https://david-dm.org/BlackrockDigital/startbootstrap-freelancer?type=dev)

## Download and Installation

* Clone the repo: `git clone https://github.com/deonSanchez/SOSA-Web.git`

## Usage

### Basic Usage

After downloading, simply edit the HTML and CSS files included with the template in your favorite text editor to make changes. These are the only files you need to worry about, you can ignore everything else! To preview the changes you make to the code, you can open the `index.html` file in your web browser.

### Advanced Usage

After installation, run `npm install` and then run `gulp dev` which will open up a preview of the template in your default browser, watch for changes to core template files, and live reload the browser when changes are saved. You can view the `gulpfile.js` to see which tasks are included with the dev environment.

## Running on Windows

You will need to have node.js downloaded. Once downloaded `cd` to the SOSA-Web folder in node.js, type `npm install gulp` then go to your IDE of your choice and type `gulp dev` inside the command prompt, and it should run.

#### Gulp Tasks

- `gulp` the default task that builds everything
- `gulp dev` browserSync opens the project in your default browser and live reloads when changes are made
- `gulp sass` compiles SCSS files into CSS
- `gulp minify-css` minifies the compiled CSS file
- `gulp minify-js` minifies the themes JS file
- `gulp copy` copies dependencies from node_modules to the vendor directory
