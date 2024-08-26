(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }
function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }
/**
 * Package: vncslab-companion
 * Author: Leon Nguyen <sonnh2109@gmail.com>
 * Feature lists:
 * 1. Make the WooCommerce product's thumbnail pills organized as a carousel 
 * 
*/

/** 1. Declare important variables & constants */
var THUMBNAIL_PILLS_CAROUSEL_ID = 'vncslab-wc-product-thumbnails-pills-carousel';
var THUMBNAIL_PILLS_CAROUSEL_SELECTOR = "div.flexy-pills.pills-container#".concat(THUMBNAIL_PILLS_CAROUSEL_ID);

// Relative selector from the outer thumbnail pills carousel container:
var THUMBNAIL_PILLS_INNER_CONTAINER_SELECTOR = "ol.pills-inner-container";
var PREV_BUTTON_CONTROLLER_SELECTOR = "button.pills-control-prev[data-pills-target=\"".concat(THUMBNAIL_PILLS_CAROUSEL_ID, "\"]");
var NEXT_BUTTON_CONTROLLER_SELECTOR = "button.pills-control-next[data-pills-target=\"".concat(THUMBNAIL_PILLS_CAROUSEL_ID, "\"]");

// thumbnai item 
var THUMBNAIL_PILL_ITEM_SELECTOR = "li.pills-item";
var THUMBNAIL_PILL_ITEM_SHOW_SELECTOR = "li.pills-item.show";

// For transition effects
var SLIDE_TRANSITION_DURATION = 600; //600ms
var waitTransition = function waitTransition(duration) {
  return new Promise(function (resolve) {
    return setTimeout(resolve, duration);
  });
};

// Thumb nail pill items

/** 1.2. Variables of important DOM elements */
var thumbnail_pills_carousel = document.querySelector(THUMBNAIL_PILLS_CAROUSEL_SELECTOR);
var thumbnail_pills_inner_container = thumbnail_pills_carousel.querySelector(THUMBNAIL_PILLS_INNER_CONTAINER_SELECTOR);
var prev_btn_controller = thumbnail_pills_carousel.querySelector(PREV_BUTTON_CONTROLLER_SELECTOR);
var next_btn_controller = thumbnail_pills_carousel.querySelector(NEXT_BUTTON_CONTROLLER_SELECTOR);
var thumbnail_pills_items = thumbnail_pills_inner_container.querySelectorAll(THUMBNAIL_PILL_ITEM_SELECTOR);
var thumbnail_pills_show_items = thumbnail_pills_inner_container.querySelectorAll(THUMBNAIL_PILL_ITEM_SHOW_SELECTOR);
var TOTAL_ITEMS = thumbnail_pills_items.length; //OK
var TOTAL_SHOW_ITEMS = thumbnail_pills_show_items.length; //OK

// 1. Determine maxIndexShowItem, minIndexShowItem
var minIndexShowItem = 0;
var maxIndexShowItem = 0;
var isSlidingNext = false;
var isSlidingPrev = false;
var clickPrevCounter = 0;
var clickNextCounter = 0;
var showPillsList = Array();
var showPillsListIndex = Array();

// Determine the max index show item, min index show item:
for (var i = 0; i < TOTAL_ITEMS; i++) {
  if (thumbnail_pills_items[i].classList.contains('show')) {
    minIndexShowItem = parseInt(i);
    maxIndexShowItem = parseInt(minIndexShowItem) + parseInt(TOTAL_SHOW_ITEMS - 1);
    break;
  }
} //let i = 0; i < TOTAL_ITEMS; i++

/** === Helper functions === */
/** === 1. Slide the carousel when clicking to prev button === */
function click_prev_thumbnail_pills_carousel(_x) {
  return _click_prev_thumbnail_pills_carousel.apply(this, arguments);
}
function _click_prev_thumbnail_pills_carousel() {
  _click_prev_thumbnail_pills_carousel = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee(e) {
    var _i2, newMaxIndex, newMinIndex, _i3, _i4;
    return _regeneratorRuntime().wrap(function _callee$(_context) {
      while (1) switch (_context.prev = _context.next) {
        case 0:
          e.stopPropagation();

          // 1. Guarding the index of all visible carousel items 

          // 1.1. If there is another async function handler is running - do nothing
          if (!(isSlidingPrev == true)) {
            _context.next = 3;
            break;
          }
          return _context.abrupt("return", false);
        case 3:
          if (!(minIndexShowItem <= 0 || maxIndexShowItem > TOTAL_ITEMS - 1)) {
            _context.next = 5;
            break;
          }
          return _context.abrupt("return", false);
        case 5:
          isSlidingPrev = true;

          // 2. Define the current minIndexShowItem, maxIndexShowItem:
          _i2 = TOTAL_ITEMS - 1;
        case 7:
          if (!(_i2 >= 0)) {
            _context.next = 15;
            break;
          }
          if (!thumbnail_pills_items[_i2].classList.contains('show')) {
            _context.next = 12;
            break;
          }
          maxIndexShowItem = parseInt(_i2);
          minIndexShowItem = parseInt(maxIndexShowItem) - parseInt(TOTAL_SHOW_ITEMS - 1);
          return _context.abrupt("break", 15);
        case 12:
          _i2--;
          _context.next = 7;
          break;
        case 15:
          // 3. Implement the transition effect
          // 3.1. Define temp value for newMinIndex, newMaxIndex
          newMaxIndex = maxIndexShowItem - 1;
          newMinIndex = minIndexShowItem - 1; // 3.2. Implement the transition effects for carousel thumbnail pills
          // 3.2.1. Display the new slides
          thumbnail_pills_items[newMinIndex].classList.add('show');

          // For debug
          // await waitTransition( SLIDE_TRANSITION_DURATION );

          // 3.2.1. Translate all visible slides : assigning class 'carousel-sliding-prev' to all items
          for (_i3 = maxIndexShowItem; _i3 >= newMinIndex; _i3--) {
            thumbnail_pills_items[_i3].classList.add('carousel-sliding-prev');
          }

          // Wait for 600 milliseconds to finish transition effect SLIDE_TRANSITION_DURATION
          _context.next = 21;
          return waitTransition(SLIDE_TRANSITION_DURATION);
        case 21:
          // 3.2.2. Complete the transition: 
          for (_i4 = maxIndexShowItem; _i4 >= newMinIndex; _i4--) {
            thumbnail_pills_items[_i4].classList.remove('carousel-sliding-prev');
          }

          // 3.2.3. Hide the translated slide (old slide)
          thumbnail_pills_items[maxIndexShowItem].classList.remove('show');

          // 3.4.3. Reveal the new translated slide
          // thumbnail_pills_items[ newMinIndex ].classList.add('show');   

          // 4. Update the new minIndexShowItem & new maxIndexShowItem
          minIndexShowItem = newMinIndex;
          maxIndexShowItem = newMaxIndex;

          // 5. Re-enable the click prev button:
          isSlidingPrev = false;
        case 26:
        case "end":
          return _context.stop();
      }
    }, _callee);
  }));
  return _click_prev_thumbnail_pills_carousel.apply(this, arguments);
}
; //click_prev_thumbnail_pills_carousel

/** === 2. Slide the carousel when clicking to next button === */
function click_next_thumbnail_pills_carousel(_x2) {
  return _click_next_thumbnail_pills_carousel.apply(this, arguments);
}
function _click_next_thumbnail_pills_carousel() {
  _click_next_thumbnail_pills_carousel = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee2(e) {
    var _i5, newMinIndex, newMaxIndex, _i6, _i7;
    return _regeneratorRuntime().wrap(function _callee2$(_context2) {
      while (1) switch (_context2.prev = _context2.next) {
        case 0:
          e.stopPropagation();

          // 1. Guarding the operation of the "click" event handler
          // 1.1. Prevent from calling "click next handler" multiple times
          if (!(isSlidingNext === true)) {
            _context2.next = 3;
            break;
          }
          return _context2.abrupt("return", false);
        case 3:
          if (!(minIndexShowItem < 0 || maxIndexShowItem >= TOTAL_ITEMS - 1)) {
            _context2.next = 5;
            break;
          }
          return _context2.abrupt("return", false);
        case 5:
          isSlidingNext = true;
          // console.log(`change status of isSlidingNext : ${isSlidingNext}`);

          // 2. Define the current minIndexShowItem, maxIndexShowItem:
          // 2.1. Iterate thorugh all items in reversed orders:
          _i5 = 0;
        case 7:
          if (!(_i5 <= TOTAL_ITEMS - 1)) {
            _context2.next = 15;
            break;
          }
          if (!thumbnail_pills_items[_i5].classList.contains('show')) {
            _context2.next = 12;
            break;
          }
          minIndexShowItem = parseInt(_i5);
          maxIndexShowItem = parseInt(minIndexShowItem) + parseInt(TOTAL_SHOW_ITEMS - 1);
          return _context2.abrupt("break", 15);
        case 12:
          _i5++;
          _context2.next = 7;
          break;
        case 15:
          //let i = 0; i <= TOTAL_ITEMS - 1 ; i++
          // 3. Implement the transition effect
          // 3.1. Define temp value for newMinIndex, newMaxIndex
          newMinIndex = minIndexShowItem + 1;
          newMaxIndex = maxIndexShowItem + 1; // 3.1.2. Assign the 'new-item' class for sliding previous activity
          // thumbnail_pills_items[ newMinIndex ].classList.add('new-item');
          // 3.2. Implementing the transition effect: assigning class 'carousel-sliding-prev' to all items
          // 3.2.1. Display the new slides 
          thumbnail_pills_items[newMaxIndex].classList.add('show');

          // 3.2.2. Translate all visible slides       
          for (_i6 = minIndexShowItem; _i6 <= newMaxIndex; _i6++) {
            thumbnail_pills_items[_i6].classList.add('carousel-sliding-next');
          }

          // 3.2.3 Wait for 600 milliseconds to finish transition effect SLIDE_TRANSITION_DURATION
          _context2.next = 21;
          return waitTransition(SLIDE_TRANSITION_DURATION);
        case 21:
          // 3.2.4. Complete the transition: 
          for (_i7 = minIndexShowItem; _i7 <= newMaxIndex; _i7++) {
            thumbnail_pills_items[_i7].classList.remove('carousel-sliding-next');
          }

          // 3.4.2. Hide the translated slide (old slide)
          thumbnail_pills_items[minIndexShowItem].classList.remove('show');

          // 4. Update the new minIndexShowItem & new maxIndexShowItem
          minIndexShowItem = newMinIndex;
          maxIndexShowItem = newMaxIndex;

          // 5. Wait for the function to complete. Set the flag to false
          isSlidingNext = false;
          // await waitTransition( SLIDE_TRANSITION_DURATION );
        case 26:
        case "end":
          return _context2.stop();
      }
    }, _callee2);
  }));
  return _click_next_thumbnail_pills_carousel.apply(this, arguments);
}
; //click_next_thumbnail_pills_carousel 

/****************************************************************************/
/**
 *  2024-June-05 Additional helper function for running carousel 
 * 
 * **/
/****************************************************************************/

/** 1. Get current show thumbnails */
var get_current_show_thumbnails = function get_current_show_thumbnails() {
  var thumbnail_pills_items_list = thumbnail_pills_inner_container.querySelectorAll(THUMBNAIL_PILL_ITEM_SELECTOR);
  var showPillsListIndex = Array();
  for (var _i = 0; _i <= TOTAL_ITEMS - 1; _i++) {
    if (thumbnail_pills_items_list[_i].classList.contains('show')) {
      showPillsListIndex.push(_i);
    }
  } //let i = 0; i <= TOTAL_ITEMS - 1 ; i++

  return showPillsListIndex;
}; //get_current_show_thumbnails

/** 2. Update show thumbnail when click next */
var update_show_thumbnails_next_direction = function update_show_thumbnails_next_direction() {};

/** 3. Update show thumbnail when click previous */
var update_show_thumbnails_prev_direction = function update_show_thumbnails_prev_direction() {};

/*********************************************************/
/*** Main functions executions ***/
/*********************************************************/

/** Only implement carousels when there are more than 5 thumbnail pills */
if (TOTAL_ITEMS > 5) {
  console.warn('--> There are more than 5 thumbnails pills - start rendering carousel for thumbnail pins ');
  prev_btn_controller.addEventListener('click', click_prev_thumbnail_pills_carousel);
  /** === 2. Slide the carousel when clicking to prev button === */

  // 2. Add the event handler for the previous button
  next_btn_controller.addEventListener('click', click_next_thumbnail_pills_carousel);
  //End of checking TOTAL_ITEMS > 5 
} else {
  console.warn('--> There are fewer than 6 thumbnail image pills. Use default format of WooCommerce ! ');
}

},{}]},{},[1]);
