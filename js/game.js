class Game {
	constructor(id, width, height) {
		this.id = id;
		this.canvas = document.getElementById("game");
		this.ctx = this.canvas.getContext("2d");
		this.cwidth = width;
		this.cheight = height;
		this.canvas.width = this.cwidth;
		this.canvas.height = this.cheight;
	}

	draw() {
		this.x = 20;
		this.ctx.beginPath();
		this.ctx.arc(this.x, 40, 20, Math.PI*2, false);
		this.ctx.fillStyle = "#FF0000";
		this.ctx.fill();
		this.ctx.closePath();
		this.x += 10;
	}

	start_drawing() {
		setInterval(function() { self.draw() }, 1000);
	}
}

const container = document.getElementById("canvas-container");
var game = new Game("game", container.offsetWidth, container.offsetHeight);
//game.start_drawing();
game.draw();
