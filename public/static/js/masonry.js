var masonryClass = 'masonry';
var masonryItemClass = 'masonry-item';
var masonryContentSelector = '[data-masonry-content]';

function resizeGridItem(item) {
    var grid = document.getElementsByClassName(masonryClass)[0];

    var rowHeight = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-auto-rows'));
    var rowGap = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-row-gap'));
    var rowSpan = Math.ceil((item.querySelector(masonryContentSelector).getBoundingClientRect().height + rowGap) / (rowHeight + rowGap));

    item.style.gridRowEnd = 'span ' + rowSpan;
}

function resizeAllGridItems() {
    if (!document.getElementsByClassName(masonryClass).length) {
        return;
    }

    var allItems = document.getElementsByClassName(masonryItemClass);

    for(x = 0; x < allItems.length; x++) {
        resizeGridItem(allItems[x]);
    }
}

window.onload = resizeAllGridItems();
window.addEventListener('resize', resizeAllGridItems);
