(function() {
	var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
	                            window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
	window.requestAnimationFrame = requestAnimationFrame;
})();

class Dot {
	constructor(x, y, radius, color) {
		this.x = x;
		this.y = y;
		this.r = radius;
		this.color = color;
		this.speedx = Background.getRandomInt(-10, 10);
		this.speedy = Background.getRandomInt(-10, 10);
	}

	draw(context) {
		if (context.fillStyle != this.color) {
			context.fillStyle = this.color;
		}
		context.beginPath();
		context.arc(this.x, this.y, this.r, Math.PI*2, false);
		context.fillStyle = this.color;
		context.fill();
		context.closePath();
	}

	update(deltaTime) {
		this.x += this.speedx * deltaTime/1000;
		this.y += this.speedy * deltaTime/1000;
		if (Background.getRandomInt(0, 100) < 1) {
			this.speedx = Background.getRandomInt(-10, 10);
			this.speedy = Background.getRandomInt(-10, 10);
		}
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
		if (context.strokeStyle != "#0388A6") {
			context.strokeStyle = "#0388A6";
			context.lineWidth = 1;
		}
		context.beginPath();
		context.moveTo(this.dot1.x, this.dot1.y);
		context.lineTo(this.dot2.x, this.dot2.y);
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
			this.dots.push(new Dot(x, y, 3, "#0388A6"));
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

	clear_dots () {
		this.dots = [];
	}

	clear_joins() {
		this.joins = [];
	}

	loop(timestamp) {
		if ((timestamp - lastRender) < 30) {
			window.requestAnimationFrame(this.loop.bind(this));
			return;
		}
		var progress = timestamp - lastRender;
		this.update(progress)
		this.draw();

		lastRender = timestamp;
		window.requestAnimationFrame(this.loop.bind(this));
	}

	draw() {
		this.clean();
		this.dots.forEach(element => element.draw(this.ctx));
		this.joins.forEach(element => element.draw(this.ctx));
	}

	update(deltaTime) {
		for (var i = 0; i < this.dots.length; i++) {
			this.dots[i].update(deltaTime);
		}
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

var lastRender = 0;
const container = document.getElementById("canvas-container");
var background = new Background("game", container.offsetWidth, container.offsetHeight);
var numdots = container.offsetWidth / 14;
var joinsradius = container.offsetHeight / 7;
background.create_dots(numdots);
background.create_joins(joinsradius, 2);
background.draw();
window.onresize = fix_background_size;
window.requestAnimationFrame(background.loop.bind(background));

function fix_background_size() {
	background.clear_dots();
	background.clear_joins();
	background.set_size(0,0);
	const container = document.getElementById("canvas-container");
	background.set_size(container.offsetWidth, container.offsetHeight);
	var numdots = container.offsetWidth / 14;
	var joinsradius = container.offsetHeight / 7;
	background.create_dots(numdots);
	background.create_joins(joinsradius, 2);
	background.draw();
}
