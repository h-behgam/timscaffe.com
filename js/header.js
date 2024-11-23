(function() {
    var navToggleBtn = document.querySelector('.nav-toggle');
    var navList = document.querySelector('.header-nav .nav-list');

    // toggle list on click
    navToggleBtn.onclick = function(e) {
        e.stopPropagation();
        navList.classList.toggle('opened');
    }

    // close list on outside click
    window.addEventListener("click", closeNavList);
    function closeNavList() {
        navList.classList.remove('opened');
    }
})()
 // Check distance to top and display back-to-top.
$( window ).scroll( function() {
	if ( $( this ).scrollTop() > 800 ) {
		$( '.back-to-top' ).addClass( 'show-back-to-top' );
	} else {
		$( '.back-to-top' ).removeClass( 'show-back-to-top' );
	}
});

// Click event to scroll to top.
$( '.back-to-top' ).click( function() {
	$( 'html, body' ).animate( { scrollTop : 0 }, 800 );
	return false;
});