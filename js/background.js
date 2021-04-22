class Dot {
	constructor(x, y, radius, color) {
		this.x = x;
		this.y = y;
		this.r = radius;
		this.color = color;
	}

	draw(context) {
		context.beginPath();
		context.arc(this.x, this.y, this.r, Math.PI*2, false);
		context.fillStyle = this.color;
		context.fill();
		context.closePath();
	}

	distance(dot) {
		return Math.sqrt((dot.x - this.x)*(dot.x - this.x) + (dot.y - this.y)*(dot.y - this.y));
	}
}

class Join {
	constructor(dot1, dot2) {
		this.dot1 = dot1;
		this.dot2 = dot2;
	}

	draw(context) {
		context.beginPath();
		context.moveTo(this.dot1.x, this.dot1.y);
		context.lineTo(this.dot2.x, this.dot2.y);
		context.strokeStyle = "white";
		context.lineWidth = 1;
		context.stroke(); 
	}
}

class Background {
	constructor(id, width, height) {
		this.id = id;
		this.canvas = document.getElementById("game");
		this.ctx = this.canvas.getContext("2d");
		this.width = width;
		this.height = height;
		this.canvas.width = this.width;
		this.canvas.height = this.height;
		this.dots = [];
		this.joins = [];
	}

	create_dots (num) {
		for (var i = 0; i < num; i++) {
			var x = Background.getRandomInt(0, this.width);
			var y = Background.getRandomInt(0, this.height);
			this.dots.push(new Dot(x, y, 3, "white"));
		}
	}

	create_joins (maxdistance, maxperdot) {
		for (var i = 0; i < this.dots.length; i++) {
			var nperdot = 0;
			for (var j = i; j < this.dots.length; j++) {
				if (nperdot >= maxperdot) {
					break;
				}
				if (i == j) {
					continue;
				}
				var d = this.dots[i].distance(this.dots[j]); 
				if (d < maxdistance) {
					this.joins.push(new Join(this.dots[i], this.dots[j]));
					nperdot++;
				}
			}
		}
	}

	draw() {
		this.clean();
		this.dots.forEach(element => element.draw(this.ctx));
		this.joins.forEach(element => element.draw(this.ctx));
	}

	clean () {
		var gradient = this.ctx.createLinearGradient(this.width/2, 0, this.width/2, this.height);
		gradient.addColorStop(0, '#011826');
		gradient.addColorStop(0.5, '#012840');
		gradient.addColorStop(1, '#025373');
		this.ctx.fillStyle = gradient;
		this.ctx.fillRect(0, 0, this.width, this.height);
	}

	set_size (width, height) {
		this.width = width;
		this.height = height;
		this.canvas.width = this.width;
		this.canvas.height = this.height;
	}

	static getRandomInt(min, max) {
		  return Math.floor(Math.random() * (max - min)) + min;
	}
}

const container = document.getElementById("canvas-container");
var background = new Background("game", container.offsetWidth, container.offsetHeight);
background.create_dots(50);
background.create_joins(100, 2);
background.draw();
window.onresize = fix_background_size;

function fix_background_size() {
	background.set_size(0,0);
	const container = document.getElementById("canvas-container");
	background.set_size(container.offsetWidth, container.offsetHeight);
	background.draw();
}
