(function(){
	"use strict";
	
	var cvs,ctx;
	//var nodes = 5;
	var waves = [];
	var waveHeight = 20;
	var colours = ["#d75e89","#87c3d8","#37c5c2"]; // #fcfae3
	
  // Initiator function
	function init() {
		cvs = document.getElementById("canvas");
		ctx = cvs.getContext("2d");
		resizeCanvas(cvs);
    
		for (var i = 0; i < colours.length; i++) {
			var temp = new wave(colours[i], 1, 5);
		}

		// Цвет шапки читаем один раз, а не в каждом кадре (getComputedStyle на каждом кадре заставляет браузер пересчитывать стили).
		fill = window.getComputedStyle(document.querySelector(".header"),null).getPropertyValue("background-color");

		// Без анимации для тех, кто попросил об этом в системе: рисуем один кадр.
		if (window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
			draw();
			return;
		}

		// Не рисуем, когда шапка прокручена за пределы экрана.
		if ("IntersectionObserver" in window) {
			new IntersectionObserver(function (entries) {
				visible = entries[0].isIntersecting;
				if (visible) schedule();
			}).observe(cvs);
		}

		// Окно стало шире холста — растягиваем холст и узлы волн пропорционально
		window.addEventListener("resize", function () {
			var oldW = cvs.width;
			resizeCanvas(cvs);
			if (cvs.width !== oldW) {
				for (var i = 0; i < waves.length; i++)
					for (var j = 0; j < waves[i].nodes.length; j++)
						waves[i].nodes[j][0] *= cvs.width / oldW;
				if (!pending) draw(0);
			}
		});

		schedule();
	}

	// Около 30 кадров в секунду (порог 30 мс с запасом на дрожание таймера при 60 Гц), независимо от частоты монитора (на 144/240 Гц раньше было 144/240 перерисовок в секунду).
	var FRAME_MS = 30, last = 0, fill, visible = true, pending = false;

	function schedule() {
		if (!pending) {
			pending = true;
			requestAnimationFrame(tick);
		}
	}

	function tick(now) {
		pending = false;
		if (!visible) return;
		if (now - last >= FRAME_MS) {
			// Фаза волн привязана к кадрам: при 30 к/с шаг удваиваем, чтобы скорость была как при 60.
			draw(2);
			last = now;
		}
		schedule();
	}

	function draw(steps) {
		ctx.fillStyle = fill;
		ctx.globalCompositeOperation = "source-over";
		ctx.fillRect(0,0,cvs.width,cvs.height);
		ctx.globalCompositeOperation = "screen"; //lighter
		for (var i = 0; i < waves.length; i++) {
			for (var j = 0; j < waves[i].nodes.length; j++) {
				for (var k = 0; k < (steps === undefined ? 1 : steps); k++) bounce(waves[i].nodes[j]);
			}
			drawWave(waves[i]);
		}
	}

	function wave(colour, lambda, nodes) {
    
		this.colour = colour;
		this.lambda = lambda;
		this.nodes = [];
		var tick = 1;
    
		for (var i = 0; i <= nodes+2; i++) {
			var temp = [(i-1) * cvs.width / nodes, 0, Math.random()*200, .3];
			this.nodes.push(temp);
		}
		waves.push(this);
	}

	function bounce(nodeArr) {
		nodeArr[1] = waveHeight/2*Math.sin(nodeArr[2]/20)+cvs.height/2;
		nodeArr[2] = nodeArr[2] + nodeArr[3];
		
	}
	
	function drawWave (obj) {
    
		var diff = function(a,b) {
			return (b - a)/2 + a;
		}
    
		ctx.fillStyle = obj.colour;
		ctx.beginPath();
		ctx.moveTo(0,cvs.height);
		ctx.lineTo(obj.nodes[0][0],obj.nodes[0][1]);
    
		for (var i = 0; i < obj.nodes.length; i++) {
      
			if (obj.nodes[i+1]) {
				ctx.quadraticCurveTo(
					obj.nodes[i][0],obj.nodes[i][1],
					diff(obj.nodes[i][0],obj.nodes[i+1][0]),diff(obj.nodes[i][1],obj.nodes[i+1][1])
				);
			}
      else {
				ctx.lineTo(obj.nodes[i][0],obj.nodes[i][1]);
				ctx.lineTo(cvs.width,cvs.height);
			}
		}
		ctx.closePath();
		ctx.fill();
	}

	function drawNodes (array) {
		ctx.strokeStyle = "#888";
    
		for (var i = 0; i < array.length; i++) {
			ctx.beginPath();
			ctx.arc(array[i][0],array[i][1],4,0,2*Math.PI);
			ctx.closePath();
			ctx.stroke();
		}
		
	}

	function drawLine (array) {
		ctx.strokeStyle = "#888";
    
		for (var i = 0; i < array.length; i++) {
      
			if (array[i+1]) {
				ctx.lineTo(array[i+1][0],array[i+1][1]);
			}
		}
    
		ctx.stroke();
	}

	function resizeCanvas(canvas,width,height) {
    
		if (width && height) {
			canvas.width = width;
			canvas.height = height;
		}
    else {
      
			// По ширине окна (не меньше 1920, как было в теме): волна не должна обрываться на широких мониторах
			canvas.width = Math.max(1920, window.innerWidth, document.documentElement.clientWidth || 0);
      
			canvas.height = waveHeight;
		}
	}
  
	document.addEventListener("DOMContentLoaded",init,false);
})();