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
const ARTICLE_ID = 'post-4129';
const ARTICLE_SELECTOR = `article#${ARTICLE_ID}`;

const POST_CONTENT_SELECTOR = `${ARTICLE_SELECTOR} > div.entry-content`;

const COCOA_BENEFITS_CONTAINER_DESKTOP_SELECTOR =  `${POST_CONTENT_SELECTOR} > div.wp-block-group.cocoa-benefits-container-desktop`;

const COCOA_BENEFITS_MEDIA_ITEMS_SELECTOR = `${COCOA_BENEFITS_CONTAINER_DESKTOP_SELECTOR} > div.wp-block-media-text.chocolate-benefits`; 

/** SUB ITEM is the container which can contains image or textual content */
const FIRST_SUB_ITEM_CONTAINER_REL_SELECTOR = `*:first-child`;
const SECOND_SUB_ITEM_CONTAINER_REL_SELECTOR = `*:last-child`;

// These anmation are defined in CSS
const FIRST_SUB_ITEM_CONTAINER_ANIMATION = 'translate_in_left_item_animation';
const SECOND_SUB_ITEM_CONTAINER_ANIMATION = 'translate_in_right_item_animation';
const SUB_ITEM_ANIMATION_DURATION = '640ms';

/** 2. Variables */


/** III. Operational functions */

const mediaItemEnterViewportCb = (entries, observer) => {
    entries.forEach( entry => {
        // entry.isIntersecting used to work good
        if( entry.intersectionRatio >= 0.2 ){
            let firstItemContainer = entry.target.querySelector( ':scope > *:first-child' );
            let secondItemContainer = entry.target.querySelector( ':scope > *:nth-child(2)' );

            console.log('--> firstItemContainer : ');
            console.log(firstItemContainer)
            console.log('--> secondItemContainer : ');
            console.log(secondItemContainer);

            // Guarding if the firstItem or secondItem are undefined
            if( ('undefined' === firstItemContainer) || ( 'undefined' === secondItemContainer ) ) return ;
            
            if( firstItemContainer.classList.contains('hide') ){
                firstItemContainer.classList.remove('hide');
            }

            if( secondItemContainer.classList.contains('hide') ){
                secondItemContainer.classList.remove('hide');
            }

            // 2.1. Reflow the animation for 1st item container
            firstItemContainer.style.animation = 'none';
            firstItemContainer.offsetHeight;//trigger reflow
            firstItemContainer.style.animation = null;
            //firstItemContainer.style.animation = `${FIRST_SUB_ITEM_CONTAINER_ANIMATION} ${SUB_ITEM_ANIMATION_DURATION}` ;
            
            // 2.2. Reflow the animation for 2nd item container
            secondItemContainer.style.animation = 'none';
            secondItemContainer.offsetHeight;//trigger reflow
            secondItemContainer.style.animation = null;
            //secondItemContainer.style.animation = `${SECOND_SUB_ITEM_CONTAINER_ANIMATION} ${SUB_ITEM_ANIMATION_DURATION}` ;

            // 3. Stop observing once trigger reflow completed
            observer.unobserve( entry.target );
        }//if( entry.isIntersecting )

    });
};

/** IV. Main functions */

document.addEventListener( 'DOMContentLoaded' , function(){
    var cocoaBenefitsMediaItems = document.querySelectorAll( COCOA_BENEFITS_MEDIA_ITEMS_SELECTOR );//OK
    // console.log('The cocoa benefits media items content is :');
    // console.log( cocoaBenefitsMediaItems );
    /* console.log( 'COCOA_BENEFITS_CONTAINER_DESKTOP_SELECTOR' );
    console.log( COCOA_BENEFITS_CONTAINER_DESKTOP_SELECTOR );
    
    var cocoaBenefitsGroup = document.querySelector( COCOA_BENEFITS_CONTAINER_DESKTOP_SELECTOR );
    console.log('COCOA_BENEFITS_CONTAINER_DESKTOP_SELECTOR');
    console.log( cocoaBenefitsGroup ); */

    if( cocoaBenefitsMediaItems && ( cocoaBenefitsMediaItems.length > 0 ) ){

        // Iterate through each cooca benefits' media items
        for( let i = 0; i < cocoaBenefitsMediaItems.length; i++ ){
            let mediaItem = cocoaBenefitsMediaItems[i];
    
            const mediaItemObserver = new IntersectionObserver(
                (entries) => {
                    mediaItemEnterViewportCb( entries, mediaItemObserver);
                },
                {threshold: 1}    
            );
    
            mediaItemObserver.observe( mediaItem );
        }//for( let i = 0; i < cocoaBenefitsMediaItems.length; i++ )
    }//cocoaBenefitsMediaItems && ( cocoaBenefitsMediaItems.length > 0 )
    

});//document.addEventListener( 'DOMContentLoaded'






