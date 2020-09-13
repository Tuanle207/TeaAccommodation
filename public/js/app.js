/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

//import getMap from './map';
window.addEventListener('load', getMap);

function getCircle(loc, radius) {
  var R = 6371; // earth's mean radius in km

  var lat = loc.latitude * Math.PI / 180; //rad

  var lon = loc.longitude * Math.PI / 180; //rad

  var d = parseFloat(radius) / R; // d = angular distance covered on earth's surface

  var locs = [];

  for (x = 0; x <= 360; x++) {
    var p = {};
    brng = x * Math.PI / 180; //rad

    p.latitude = Math.asin(Math.sin(lat) * Math.cos(d) + Math.cos(lat) * Math.sin(d) * Math.cos(brng));
    p.longitude = (lon + Math.atan2(Math.sin(brng) * Math.sin(d) * Math.cos(lat), Math.cos(d) - Math.sin(lat) * Math.sin(p.latitude))) * 180 / Math.PI;
    p.latitude = p.latitude * 180 / Math.PI;
    locs.push(p);
  }

  return locs;
}

function getMap() {
  navigator.geolocation.getCurrentPosition(function (location) {
    var _location$coords = location.coords,
        latitude = _location$coords.latitude,
        longitude = _location$coords.longitude;
    var map = new Microsoft.Maps.Map('#map', {
      center: new Microsoft.Maps.Location(latitude, longitude),
      zoom: 12
    });
    var center = map.getCenter();
    var pin = new Microsoft.Maps.Pushpin(center, {
      title: 'Vị trí của bạn',
      subTitle: 'Vị trí hiện tại của bạn',
      color: 'green'
    });
    var circleShape = getCircle({
      latitude: latitude,
      longitude: longitude
    }, 10);
    var circle = new Microsoft.Maps.Polygon(circleShape, {
      fillColor: 'rgba(0, 255, 0, 0.5)',
      strokeColor: '#eb8f8f',
      strokeThickness: 2
    });
    map.entities.push(pin);
    map.entities.push(circle);
    Microsoft.Maps.loadModule('Microsoft.Maps.AutoSuggest', function () {
      var manager = new Microsoft.Maps.AutosuggestManager({
        map: map
      });
      manager.attachAutosuggest('#search_box', '#mapbox__search', selectedSuggestion);
    });
  });
}

function selectedSuggestion(result) {
  //Remove previously selected suggestions from the map.
  map.entities.clear(); //Show the suggestion as a pushpin and center map over it.

  var pin = new Microsoft.Maps.Pushpin(result.location);
  map.entities.push(pin);
  map.setView({
    bounds: result.bestView
  });
}

/***/ }),

/***/ "./resources/sass/main.scss":
/*!**********************************!*\
  !*** ./resources/sass/main.scss ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!**************************************************************!*\
  !*** multi ./resources/js/app.js ./resources/sass/main.scss ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! C:\xampp\htdocs\timphongtroserver\resources\js\app.js */"./resources/js/app.js");
module.exports = __webpack_require__(/*! C:\xampp\htdocs\timphongtroserver\resources\sass\main.scss */"./resources/sass/main.scss");


/***/ })

/******/ });