<?php

function get_html_attributes( $html, $depth = 1  ) {
	$obj = new stdClass();
	$doc = new DOMDocument();
	$doc->loadHTML( $html );

	$element = $doc->documentElement->firstChild->firstChild;
	$depth   = max( intval( $depth, 10 ), 1 );
	while ( $depth > 1 && ( ! empty( $element->firstChild ) ) ) {
		$element = $element->firstChild;
		$depth--;
	}
#print_r($element); #->attributes);
	$obj->attrs = array();
	$obj->tag   = $element->tagName;
	$obj->text  = $element->textContent;
	if ( $element->hasAttributes() ) {
		foreach ($element->attributes as $attr) {
			$obj->attrs[ $attr->nodeName ] = $attr->nodeValue;
		}
	} else {
		echo "\nno attributes\n";
	}
	return $obj;
}

#$atts = get_html_attributes( '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">' );
#$atts = get_html_attributes( '<img src="/image/fluffybunny.jpg" title="Harvey the bunny" alt="a cute little fluffy bunny" />' );
#$atts = get_html_attributes( '<div class="image-responsive"><img src="/image/fluffybunny.jpg" title="Harvey the bunny" alt="a cute little fluffy bunny" /></div>' );
$atts = get_html_attributes( '<div class="image-responsive"><img src="/image/fluffybunny.jpg" title="Harvey the bunny" alt="a cute little fluffy bunny" /></div>', 3 );
#$atts = get_html_attributes( '<img class="vote-up" src="/content/img/vote-arrow-up.png" alt="vote up" title="This was helpful (click again to undo)" />' );
#$atts = get_html_attributes( '<ul id="value" name="Bob" custom-tag="customData">' );
#$atts = get_html_attributes( "<html><body>Test<br><img src=\"myimage.jpg\" title=\"title\" alt=\"alt\"></body></html>" );
#$atts = get_html_attributes( "<img src='http://google.com/2af5e6ae749d523216f296193ab0b146.jpg' width='40' height='40'>" );

print_r( $atts );
