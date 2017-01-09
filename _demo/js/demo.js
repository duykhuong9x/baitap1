var $main = document.getElementById('main_wrapper'),
	$left = document.getElementById('bg_light'),
	$right = document.getElementById('bg_dark'),	
	$tour = document.getElementById('take_a_tour'),
	$logo = document.getElementById('logo'),
	$left_column = document.getElementById('left_column'),
	$right_column = document.getElementById('right_column'),
	$3_column = document.getElementById('3_column'),
	$subtitle = document.getElementById('subtitle'),
	$buy_this_theme = document.getElementById('buy_this_theme'),	
	$headings = document.getElementsByTagName('h6');
	$left_items = document.getElementsByTagName('ul')[0].children;
	$right_items = document.getElementsByTagName('ul')[1].children;
	$3_items = document.getElementsByTagName('ul')[2].children;

window.onload = function() {
	$left.className += 'animate';
	$right.className += 'animate';
	$left_column.className += ' animate';
	$right_column.className += ' animate';
	$3_column.className += ' animate';

	setTimeout(function() { $tour.className += 'animate'; }, 750);
    setTimeout(function() { $buy_this_theme.className += ' animate'; }, 750);

	$logo.className += 'animate';
	$subtitle.className += 'animate';

	$headings[0].className += 'animate';
	//$headings[1].className += 'animate';

	var i_l = 0, i_r = 0, i_3 = 0, max_l = $left_items.length, max_r = $right_items.length, max_3 = $3_items.length;
	function iterateItems($items, i, max) {
		setTimeout(function() {
			$items[i].className += ' animate';

			i++;
			if (i < max)
				iterateItems($items, i, max);
		}, 250);
	}
	iterateItems($left_items, i_l, max_l);
	iterateItems($right_items, i_r, max_r);
	iterateItems($3_items, i_3, max_3);
}