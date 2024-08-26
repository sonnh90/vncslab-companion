(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

/**
 * Package: vncslab-companion
 * Author: Leon Nguyen <sonnh2109@gmail.com>
 * Feature lists:
 * 1. Use InterObserver to trigger animation when elements enter the viewports.
 * - Trigger animation of the images & textual content when each media items enter the viewport
*/

/** I. Variable & constant declaration */
/** 1. constant*/
/** 1.1. Selectors info of the cocoa benefits's media items */
var ARTICLE_ID = 'post-4129';
var ARTICLE_SELECTOR = "article#".concat(ARTICLE_ID);
var POST_CONTENT_SELECTOR = "".concat(ARTICLE_SELECTOR, " > div.entry-content");
var COCOA_BENEFITS_CONTAINER_DESKTOP_SELECTOR = "".concat(POST_CONTENT_SELECTOR, " > div.wp-block-group.cocoa-benefits-container-desktop");
var COCOA_BENEFITS_MEDIA_ITEMS_SELECTOR = "".concat(COCOA_BENEFITS_CONTAINER_DESKTOP_SELECTOR, " > div.wp-block-media-text.chocolate-benefits");

/** SUB ITEM is the container which can contains image or textual content */
var FIRST_SUB_ITEM_CONTAINER_REL_SELECTOR = "*:first-child";
var SECOND_SUB_ITEM_CONTAINER_REL_SELECTOR = "*:last-child";

// These anmation are defined in CSS
var FIRST_SUB_ITEM_CONTAINER_ANIMATION = 'translate_in_left_item_animation';
var SECOND_SUB_ITEM_CONTAINER_ANIMATION = 'translate_in_right_item_animation';
var SUB_ITEM_ANIMATION_DURATION = '640ms';

/** 2. Variables */

/** III. Operational functions */

var mediaItemEnterViewportCb = function mediaItemEnterViewportCb(entries, observer) {
  entries.forEach(function (entry) {
    // entry.isIntersecting used to work good
    if (entry.intersectionRatio >= 0.2) {
      var firstItemContainer = entry.target.querySelector(':scope > *:first-child');
      var secondItemContainer = entry.target.querySelector(':scope > *:nth-child(2)');

      // console.log('--> firstItemContainer : ');
      // console.log(firstItemContainer)
      // console.log('--> secondItemContainer : ');
      // console.log(secondItemContainer);

      // Guarding if the firstItem or secondItem are undefined
      if ('undefined' === firstItemContainer || 'undefined' === secondItemContainer) return;
      if (firstItemContainer.classList.contains('hide')) {
        firstItemContainer.classList.remove('hide');
      }
      if (secondItemContainer.classList.contains('hide')) {
        secondItemContainer.classList.remove('hide');
      }

      // 2.1. Reflow the animation for 1st item container
      firstItemContainer.style.animation = 'none';
      firstItemContainer.offsetHeight; //trigger reflow
      firstItemContainer.style.animation = null;
      //firstItemContainer.style.animation = `${FIRST_SUB_ITEM_CONTAINER_ANIMATION} ${SUB_ITEM_ANIMATION_DURATION}` ;

      // 2.2. Reflow the animation for 2nd item container
      secondItemContainer.style.animation = 'none';
      secondItemContainer.offsetHeight; //trigger reflow
      secondItemContainer.style.animation = null;
      //secondItemContainer.style.animation = `${SECOND_SUB_ITEM_CONTAINER_ANIMATION} ${SUB_ITEM_ANIMATION_DURATION}` ;

      // 3. Stop observing once trigger reflow completed
      observer.unobserve(entry.target);
    } //if( entry.isIntersecting )
  });
};

/** IV. Main functions */

document.addEventListener('DOMContentLoaded', function () {
  var cocoaBenefitsMediaItems = document.querySelectorAll(COCOA_BENEFITS_MEDIA_ITEMS_SELECTOR); //OK
  // console.log('The cocoa benefits media items content is :');
  // console.log( cocoaBenefitsMediaItems );
  /* console.log( 'COCOA_BENEFITS_CONTAINER_DESKTOP_SELECTOR' );
  console.log( COCOA_BENEFITS_CONTAINER_DESKTOP_SELECTOR );
  
  var cocoaBenefitsGroup = document.querySelector( COCOA_BENEFITS_CONTAINER_DESKTOP_SELECTOR );
  console.log('COCOA_BENEFITS_CONTAINER_DESKTOP_SELECTOR');
  console.log( cocoaBenefitsGroup ); */

  if (cocoaBenefitsMediaItems && cocoaBenefitsMediaItems.length > 0) {
    var _loop = function _loop() {
      var mediaItem = cocoaBenefitsMediaItems[i];
      var mediaItemObserver = new IntersectionObserver(function (entries) {
        mediaItemEnterViewportCb(entries, mediaItemObserver);
      }, {
        threshold: 1
      });
      mediaItemObserver.observe(mediaItem);
    };
    // Iterate through each cooca benefits' media items
    for (var i = 0; i < cocoaBenefitsMediaItems.length; i++) {
      _loop();
    } //for( let i = 0; i < cocoaBenefitsMediaItems.length; i++ )
  } //cocoaBenefitsMediaItems && ( cocoaBenefitsMediaItems.length > 0 )
}); //document.addEventListener( 'DOMContentLoaded'

},{}]},{},[1]);
