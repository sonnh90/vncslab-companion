/**
 * Package: vncslab-companion
 * Author: Leon Nguyen <sonnh2109@gmail.com>
 * Feature lists:
 * 1. Make the WooCommerce product's thumbnail pills organized as a carousel 
 * 
*/

/** 1. Declare important variables & constants */
const THUMBNAIL_PILLS_CAROUSEL_ID = 'vncslab-wc-product-thumbnails-pills-carousel';
const THUMBNAIL_PILLS_CAROUSEL_SELECTOR = `div.flexy-pills.pills-container#${THUMBNAIL_PILLS_CAROUSEL_ID}`;

// Relative selector from the outer thumbnail pills carousel container:
const THUMBNAIL_PILLS_INNER_CONTAINER_SELECTOR = `ol.pills-inner-container`;
const PREV_BUTTON_CONTROLLER_SELECTOR = `button.pills-control-prev[data-pills-target="${THUMBNAIL_PILLS_CAROUSEL_ID}"]`;
const NEXT_BUTTON_CONTROLLER_SELECTOR = `button.pills-control-next[data-pills-target="${THUMBNAIL_PILLS_CAROUSEL_ID}"]`;

// thumbnai item 
const THUMBNAIL_PILL_ITEM_SELECTOR = `li.pills-item`;
const THUMBNAIL_PILL_ITEM_SHOW_SELECTOR = `li.pills-item.show`;

// For transition effects
const SLIDE_TRANSITION_DURATION = 600;//600ms
const waitTransition = ( duration ) => new Promise( (resolve) => setTimeout(resolve, duration) );

// Thumb nail pill items

/** 1.2. Variables of important DOM elements */
var thumbnail_pills_carousel = document.querySelector( THUMBNAIL_PILLS_CAROUSEL_SELECTOR );
var thumbnail_pills_inner_container = thumbnail_pills_carousel.querySelector( THUMBNAIL_PILLS_INNER_CONTAINER_SELECTOR);

var prev_btn_controller = thumbnail_pills_carousel.querySelector( PREV_BUTTON_CONTROLLER_SELECTOR );
var next_btn_controller = thumbnail_pills_carousel.querySelector( NEXT_BUTTON_CONTROLLER_SELECTOR );

var thumbnail_pills_items = thumbnail_pills_inner_container.querySelectorAll( THUMBNAIL_PILL_ITEM_SELECTOR );
var thumbnail_pills_show_items = thumbnail_pills_inner_container.querySelectorAll( THUMBNAIL_PILL_ITEM_SHOW_SELECTOR );

const TOTAL_ITEMS = thumbnail_pills_items.length;//OK
const TOTAL_SHOW_ITEMS = thumbnail_pills_show_items.length;//OK


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
for(let i = 0; i < TOTAL_ITEMS; i++){
            
    if ( thumbnail_pills_items[i].classList.contains('show') ){
        minIndexShowItem = parseInt(i);
        maxIndexShowItem = parseInt( minIndexShowItem ) + parseInt( TOTAL_SHOW_ITEMS - 1 );
        break;
    }
}//let i = 0; i < TOTAL_ITEMS; i++

/** === Helper functions === */
/** === 1. Slide the carousel when clicking to prev button === */

async function click_prev_thumbnail_pills_carousel(e){
    e.stopPropagation();

    // 1. Guarding the index of all visible carousel items 

    // 1.1. If there is another async function handler is running - do nothing
    if( isSlidingPrev == true ) return false; 

    // 1.2. Validate the index
    // If min index = 0 - do nothing - no previous action is performed
    if( minIndexShowItem <= 0 || maxIndexShowItem > (TOTAL_ITEMS - 1) ) return false;    

    isSlidingPrev = true;

    // 2. Define the current minIndexShowItem, maxIndexShowItem:
    for(let i = TOTAL_ITEMS - 1; i >= 0 ; i--){
        if ( thumbnail_pills_items[i].classList.contains('show') ){
            maxIndexShowItem = parseInt(i);
            minIndexShowItem = parseInt( maxIndexShowItem ) - parseInt( TOTAL_SHOW_ITEMS - 1 );
            break;
        }
    }

    // 3. Implement the transition effect

    // 3.1. Define temp value for newMinIndex, newMaxIndex
    let newMaxIndex = maxIndexShowItem - 1;
    let newMinIndex = minIndexShowItem - 1;

    // 3.2. Implement the transition effects for carousel thumbnail pills

    // 3.2.1. Display the new slides
    thumbnail_pills_items[ newMinIndex ].classList.add('show');

    // For debug
    // await waitTransition( SLIDE_TRANSITION_DURATION );

    // 3.2.1. Translate all visible slides : assigning class 'carousel-sliding-prev' to all items
    for( let i = maxIndexShowItem; i >= newMinIndex; i-- ){
        thumbnail_pills_items[i].classList.add('carousel-sliding-prev');
    }

    // Wait for 600 milliseconds to finish transition effect SLIDE_TRANSITION_DURATION
    await waitTransition( SLIDE_TRANSITION_DURATION );

    // 3.2.2. Complete the transition: 
    for( let i = maxIndexShowItem; i >= newMinIndex; i-- ){
        thumbnail_pills_items[i].classList.remove('carousel-sliding-prev');
    }

    // 3.2.3. Hide the translated slide (old slide)
    thumbnail_pills_items[ maxIndexShowItem ].classList.remove('show');

    // 3.4.3. Reveal the new translated slide
    // thumbnail_pills_items[ newMinIndex ].classList.add('show');   

    // 4. Update the new minIndexShowItem & new maxIndexShowItem
    minIndexShowItem = newMinIndex;
    maxIndexShowItem = newMaxIndex;

    // 5. Re-enable the click prev button:
    isSlidingPrev = false;
};//click_prev_thumbnail_pills_carousel

/** === 2. Slide the carousel when clicking to next button === */
async function click_next_thumbnail_pills_carousel(e){
    e.stopPropagation();

    // 1. Guarding the operation of the "click" event handler
    // 1.1. Prevent from calling "click next handler" multiple times
    if( isSlidingNext === true ) return false; 

    // 1.2 Guarding the index of all visible carousel items 
    // If min index = 0 - do nothing - no previous action is performed
    if( minIndexShowItem < 0 || maxIndexShowItem >= (TOTAL_ITEMS - 1) ) return false;

    isSlidingNext = true;
    // console.log(`change status of isSlidingNext : ${isSlidingNext}`);

    // 2. Define the current minIndexShowItem, maxIndexShowItem:
    // 2.1. Iterate thorugh all items in reversed orders:
    for( let i = 0; i <= TOTAL_ITEMS - 1 ; i++ ){
        if ( thumbnail_pills_items[i].classList.contains('show') ){
            minIndexShowItem = parseInt(i);
            maxIndexShowItem = parseInt( minIndexShowItem ) + parseInt( TOTAL_SHOW_ITEMS - 1 );
            break;
        }
    }//let i = 0; i <= TOTAL_ITEMS - 1 ; i++

    // 3. Implement the transition effect
    // 3.1. Define temp value for newMinIndex, newMaxIndex
    let newMinIndex = minIndexShowItem + 1;
    let newMaxIndex = maxIndexShowItem + 1;

    // 3.1.2. Assign the 'new-item' class for sliding previous activity
    // thumbnail_pills_items[ newMinIndex ].classList.add('new-item');

    // 3.2. Implementing the transition effect: assigning class 'carousel-sliding-prev' to all items
    
    // 3.2.1. Display the new slides 
    thumbnail_pills_items[ newMaxIndex ].classList.add('show');

    // 3.2.2. Translate all visible slides       
    for( let i = minIndexShowItem; i <= newMaxIndex; i++ ){
        thumbnail_pills_items[i].classList.add('carousel-sliding-next');
    }

    // 3.2.3 Wait for 600 milliseconds to finish transition effect SLIDE_TRANSITION_DURATION
    await waitTransition( SLIDE_TRANSITION_DURATION );

    // 3.2.4. Complete the transition: 
    for( let i = minIndexShowItem; i <= newMaxIndex; i++ ){
        thumbnail_pills_items[i].classList.remove('carousel-sliding-next');
    }

    // 3.4.2. Hide the translated slide (old slide)
    thumbnail_pills_items[ minIndexShowItem ].classList.remove('show');

    // 4. Update the new minIndexShowItem & new maxIndexShowItem
    minIndexShowItem = newMinIndex;
    maxIndexShowItem = newMaxIndex;

    // 5. Wait for the function to complete. Set the flag to false
    isSlidingNext = false;
    // await waitTransition( SLIDE_TRANSITION_DURATION );
};//click_next_thumbnail_pills_carousel 

/****************************************************************************/
/**
 *  2024-June-05 Additional helper function for running carousel 
 * 
 * **/
/****************************************************************************/

/** 1. Get current show thumbnails */
const get_current_show_thumbnails = function(){
    let thumbnail_pills_items_list = thumbnail_pills_inner_container.querySelectorAll( THUMBNAIL_PILL_ITEM_SELECTOR );

    let showPillsListIndex = Array();

    for( let i = 0; i <= TOTAL_ITEMS - 1 ; i++ ){
        if ( thumbnail_pills_items_list[i].classList.contains('show') ){
            showPillsListIndex.push(i);
        }
    }//let i = 0; i <= TOTAL_ITEMS - 1 ; i++

    return showPillsListIndex;
};//get_current_show_thumbnails

/** 2. Update show thumbnail when click next */
const update_show_thumbnails_next_direction = function(){

};

/** 3. Update show thumbnail when click previous */
const update_show_thumbnails_prev_direction = function(){
    
}




/*********************************************************/
/*** Main functions executions ***/
/*********************************************************/

/** Only implement carousels when there are more than 5 thumbnail pills */
if( TOTAL_ITEMS > 5 ){
    console.warn('--> There are more than 5 thumbnails pills - start rendering carousel for thumbnail pins ');

    prev_btn_controller.addEventListener('click', click_prev_thumbnail_pills_carousel );
    /** === 2. Slide the carousel when clicking to prev button === */

    // 2. Add the event handler for the previous button
    next_btn_controller.addEventListener('click', click_next_thumbnail_pills_carousel );
    //End of checking TOTAL_ITEMS > 5 

} else {
    console.warn('--> There are fewer than 6 thumbnail image pills. Use default format of WooCommerce ! ');
}

