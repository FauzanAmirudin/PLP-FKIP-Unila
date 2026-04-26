let field = document.querySelectorAll(".field");
if (field !== null){
	field.forEach((e) => {
		const action = e.querySelectorAll(".field-action");
		action.forEach((b) => {
			var normalize = document.createElement("a");
			normalize.classList = 'btn btn-tiny btn-normalize';
			normalize.addEventListener("click", (e) => {
				e.path[3].style = ''
				e.path[2].nextElementSibling.style.display = 'block';
			});
			b.appendChild(normalize);
			var fullsize = document.createElement("a");
			fullsize.classList = 'btn btn-tiny btn-fullsize';
			fullsize.addEventListener("click", (e) => {
				e.path[3].style = 'position: absolute; top: 60px; left: 0px; width: 100%; z-index: 10; min-height: calc(100% - 5px);';
				e.path[2].nextElementSibling.style.display = 'block';
			});
			b.appendChild(fullsize);
			var minimize = document.createElement("a");
			minimize.classList = 'btn btn-tiny btn-minimize';
			minimize.addEventListener("click", (e) => {
				e.path[3].style = ''
				e.path[2].nextElementSibling.style.display = 'none';
			});
			b.appendChild(minimize);
		}
	
		)
	})
}