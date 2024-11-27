use std::fs;

fn load_data(filename: &str) -> Vec<Vec<Vec<bool>>> {
	let mut cakes = vec![];
	let mut tmp_cake = vec![];

	let contents = fs::read_to_string(filename).unwrap();
	for line in contents.split('\n') {
		if line.is_empty() {
			cakes.push(tmp_cake);
			tmp_cake = vec![];
			continue;
		}

		let mut tmp_line = vec![];

		for ch in line.chars() {
			tmp_line.push(ch == '1');
		}

		tmp_cake.push(tmp_line);
	}
	if tmp_cake.len() > 1 {
		cakes.push(tmp_cake);
	}

	cakes
}

fn get_colour_count(cake: &Vec<Vec<bool>>, people: i32) -> Option<(i32, i32)> {
	let mut trues = 0;
	let mut falses = 0;

	for line in cake {
		for piece in line {
			match piece {
				true => trues += 1,
				false => falses += 1,
			}
		}
	}

	if trues % people != 0 || falses % people != 0 {
		None
	} else {
		Some((trues / people, falses / people))
	}
}

fn main() {
	const PEOPLE: i32 = 4;

	for cake in &load_data("buchty.txt") {
		print!("{}", if cut_cake(cake, PEOPLE) { "T" } else { "F" });
	}
	println!();
}

fn cut_cake(cake: &Vec<Vec<bool>>, people: i32) -> bool {
	let counts = match get_colour_count(cake, people) {
		Some((t, f)) => (t, f),
		None => return false,
	};

	let mut segments = {
		let mut tmp = vec![];
		for _ in 0..cake.len() {
			tmp.push(vec![0; cake[0].len()]);
		}
		tmp
	};

	find_next_segment(cake, &mut segments, 0, counts)
}

fn find_next_segment(
	cake: &Vec<Vec<bool>>,
	segments: &mut Vec<Vec<i32>>,
	segnum: i32,
	max_counts: (i32, i32),
) -> bool {
	let mut found = false;

	for y in 0..cake.len() {
		for x in 0..cake[0].len() {
			if segments[y][x] == 0 {
				let counts = if cake[y][x] { (1, 0) } else { (0, 1) };
				if build_segment(cake, segments, segnum + 1, x, y, counts, max_counts) {
					return true;
				}
				found = true;
				break;
			}
		}
		if found {
			break;
		}
	}

	!found
}

fn build_segment(
	cake: &Vec<Vec<bool>>,
	segments: &mut Vec<Vec<i32>>,
	segnum: i32,
	x: usize,
	y: usize,
	counts: (i32, i32),
	max_counts: (i32, i32),
) -> bool {
	segments[y][x] = segnum;

	if counts == max_counts {
		// find new segment
		if find_next_segment(cake, segments, segnum, max_counts) {
			return true;
		}
	} else if counts.0 <= max_counts.0 || counts.1 <= max_counts.1 {
		// build current segment
		if x > 0 && segments[y][x - 1] == 0 {
			let new_counts = if cake[y][x - 1] {
				(counts.0 + 1, counts.1)
			} else {
				(counts.0, counts.1 + 1)
			};
			if build_segment(cake, segments, segnum, x - 1, y, new_counts, max_counts) {
				return true;
			}
		}

		if x < cake[0].len() - 1 && segments[y][x + 1] == 0 {
			let new_counts = if cake[y][x + 1] {
				(counts.0 + 1, counts.1)
			} else {
				(counts.0, counts.1 + 1)
			};
			if build_segment(cake, segments, segnum, x + 1, y, new_counts, max_counts) {
				return true;
			}
		}

		if y > 0 && segments[y - 1][x] == 0 {
			let new_counts = if cake[y - 1][x] {
				(counts.0 + 1, counts.1)
			} else {
				(counts.0, counts.1 + 1)
			};
			if build_segment(cake, segments, segnum, x, y - 1, new_counts, max_counts) {
				return true;
			}
		}

		if y < cake.len() - 1 && segments[y + 1][x] == 0 {
			let new_counts = if cake[y + 1][x] {
				(counts.0 + 1, counts.1)
			} else {
				(counts.0, counts.1 + 1)
			};
			if build_segment(cake, segments, segnum, x, y + 1, new_counts, max_counts) {
				return true;
			}
		}
	}

	segments[y][x] = 0;
	false
}
