// https://www.youtube.com/watch?v=azdwsXLmrHE

document.addEventListener('DOMContentLoaded', function() {
	var team_grids = document.querySelectorAll('.expanding-grid'),
		team_grids_length = team_grids.length;

	//=========================================================================
	// Setup: add event listener and calculate heights on init
	//=========================================================================
	// for (var tg=0; tg < team_grids_length; tg++){
		// team_grids[tg].addEventListener('change', team_grid_row);
		// expanding_grid_height_calc(team_grids[tg]);
	// }

	//=========================================================================
	// Resize: re-calculate heights on resize because words wrap
	//=========================================================================
	var all_expanding_grid_height_fixing = debounce(function(evt){
		for (var i = 0; i < team_grids_length; i++) {
			expanding_grid_height_calc(team_grids[i]);
		}
	}, 250);
	if (team_grids_length){
		window.addEventListener('resize', all_expanding_grid_height_fixing, false);

		setTimeout(all_expanding_grid_height_fixing, 200);
	}

	//=========================================================================
	// On-change: everything works with togglers, but scroll into view for fun
	//=========================================================================
	function team_grid_row(e){
		e.target.parentNode.scrollIntoView({behavior: 'smooth', inline: 'center'});
	}

	//=========================================================================
	// Height Calc: loop through the items and make them uniform
	//=========================================================================
	// flex helps us here by letting us just use the parent height
	// so we can skip Math.max() comparisons and extra loops
	function expanding_grid_height_calc(el){

		var grid = el.querySelector('ul'),
			items,
			labels,
			open,
			label,
			padding_offset = 56; // 1em top padding + 1em bottom padding = 32 + 1.5em top margin on the label = 56

		if (grid){
			items = grid.children;
		}

		if (!grid || !items){ return; }

		// unset potential existing hard-coded height before things get weird
		labels = grid.querySelectorAll('label.toggler');
		for (var l = 0; l < labels.length; l++) {
			labels[l].style.height = 'auto';
		}

		// label heights only matter above 50em where .horiz will start working
		// but we still want to unset the heights on a resize under our breakpoint
		// so bail here, if necessary, after we've done that unsetting
		if (window.innerWidth < 800) { return; }

		// now, if we have a .toggle-target expanded, the math gets...not fun
		// we could increment padding_offset by the clientHeight of the aside
		// BUT that'll get screwy on x4 when the rows do their 4-up/2-up break
		open = grid.querySelector('input:checked');
		// SO we'll just close things before measuring and try to put them back after
		if (open){
			open.checked = false;
		}

		for (var i = 0; i < items.length; i++) {
			label = items[i].querySelector('label');
			if (label){
				// measure the natural height of the parent and set our label's height to that
				label.style.height = (items[i].clientHeight - padding_offset) + 'px';
			}
		}

		if (open){
			open.checked = true;
		}
	}
});
