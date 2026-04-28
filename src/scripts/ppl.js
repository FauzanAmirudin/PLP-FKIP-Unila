// Get the modal
var modalClose = document.getElementById('close_modal');
var modalOpen = document.getElementById('open_modal');
// When the user clicks anywhere outside of the modal, close it
if ( modalClose != null ) {
	modalClose.addEventListener("click", function(event) {
	    if (event.target != this.parentElement) {
		    document.getElementById("modal").style.display = 'none';
		    let target = this.dataset.target.split(",").map(function (curVal) { return curVal.trim() });
		    target.forEach(id => {
			    document.getElementById(id).style.display = 'none';
		    });
	    }
	});
}

// Close button pada form register
var modalCloseReg = document.getElementById('close_modal_reg');
if ( modalCloseReg != null ) {
	modalCloseReg.addEventListener("click", function() {
		document.getElementById("modal").style.display = 'none';
		document.getElementById("login").style.display = 'none';
		document.getElementById("register").style.display = 'none';
	});
}

function openModal (id, size) {
	document.getElementById("modal").children[0].style.width = size + "px";
	document.getElementById("modal").style.display='block';
	document.getElementById(id).style.display='block';
}

function resetCaptcha(id) {
	let element = document.getElementById(id);
	element.src = '?page=captcha&t=' + Math.random();
	element.focus()
}

// Beralih antara form login dan register tanpa menutup modal
function switchModal(hideId, showId) {
	document.getElementById(hideId).style.display = 'none';
	document.getElementById(showId).style.display = 'block';
}