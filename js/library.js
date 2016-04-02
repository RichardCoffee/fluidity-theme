// js/library.js

jQuery(document).ready(function() {
  wp.heartbeat = '';
});

function appendElement(elParent,elType,elId,elName,elClass,elIType) {
  if (elParent && elType) {
    var newEl = document.createElement(elType);
    if (newEl) {
      if (elId)    { newEl.id        = elId; }    // jQuery(newEl).attr('id',elId); }
      if (elName)  { newEl.name      = elName; }  // jQuery(newEl).attr('name',elName); }
      if (elClass) { newEl.className = elClass; } // jQuery(newEl).addClass(elClass); }
      if (elIType) { newEl.type      = elIType; }
      elParent.appendChild(newEl);
    } else {
      alert('failed to create '+elType+' element');
    }
  }
  return newEl;
}

function deleteRoutine(deleteNode,stopID,deletion) {
  var thisNode = nextElementSibling(deleteNode);
  while (thisNode && thisNode.id!=stopID) {
    deletion(deleteNode,thisNode);
    deleteNode = thisNode;
    thisNode   = nextElementSibling(deleteNode);
  }
  thisNode = deleteNode.parentNode;
  thisNode.removeChild(deleteNode);
}

function findData(myEle,findMe) {
  var myData = jQuery(myEle).attr(findMe);
  while (typeof(myData)=='undefined') { // walk up DOM to find data item
    myEle  = myEle.parentNode;
    myData = jQuery(myEle).attr(findMe);
    if (myEle==null) {
      console.log('findData: unable to locate: '+findMe);
      return undefined;
    } // we hit DOM top
  }
  return myData;
}

function insertElement(elParent,elSpot,elType,elId,elName,elClass) {
  if (elParent && elSpot && elType) {
    var newEl = document.createElement(elType);
    if (newEl) {
      if (elId)    { newEl.id        = elId; }    // jQuery(newEl).attr('id',elId); }
      if (elName)  { newEl.name      = elName; }  // jQuery(newEl).attr('name',elName); }
      if (elClass) { newEl.className = elClass; } // jQuery(newEl).addClass(elClass); }
      elParent.insertBefore(newEl,elSpot);
    } else {
      alert('failed to create '+elType+' element');
    }
  }
  return newEl;
}

// Browser compatibility function taken from http://stackoverflow.com/questions/6548748/portability-of-nextelementsibling-nextsibling
function nextElementSibling(el) {
  if (el.nextElementSibling) return el.nextElementSibling;
  do { el = el.nextSibling } while (el && el.nodeType !== 1);
  return el;
}

// Browser compatibility function taken from http://stackoverflow.com/questions/6548748/portability-of-nextelementsibling-nextsibling
function previousElementSibling(el) {
  if (el.previousElementSibling) return el.previousElementSibling;
  do { el = el.previousSibling } while (el && el.nodeType !== 1);
  return el;
}

// http://www.dconnell.co.uk/blog/index.php/2012/03/12/scroll-to-any-element-using-jquery/
function scrollToElement(selector, time, verticalOffset) {
  time = time || 1000;
  verticalOffset = verticalOffset || 0;
  element = jQuery(selector);
  offset = element.offset();
  offsetTop = offset.top + verticalOffset - 28;
  jQuery('html, body').animate({
    scrollTop: offsetTop
  }, time);
}

