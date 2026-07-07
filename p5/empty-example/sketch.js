let bpm = 75;
let variabilidad = 10;
let sensorConectado = false;
let corazones = [];
let centroGravedad;
let idUsuarioReferencia = 1;
let familiaIdActual = 0;

function setup() {
  createCanvas(windowWidth, windowHeight);
  centroGravedad = createVector(width / 2, height / 2);

  let parametros = getURLParams();
  if (parametros.usuario_id) {
    idUsuarioReferencia = int(parametros.usuario_id);
  }

  cargarDatosBaseDeDatos();
  setInterval(cargarDatosBaseDeDatos, 10000);

  setInterval(() => {
    if (!sensorConectado) {
      bpm = noise(frameCount * 0.01) * 40 + 60;
    }
  }, 1000);
}

function draw() {
  background(5, 5, 10, 3);

  for (let c of corazones) {
    c.actualizar(centroGravedad, bpm, variabilidad);
    c.mostrarCorazon();
  }

  for (let c of corazones) {
    c.mostrarTexto();
  }

  mostrarFichaTecnica();
}

function cargarDatosBaseDeDatos() {
  fetch("../../consultafamilia.php?usuario_id=" + idUsuarioReferencia)
    .then((respuesta) => respuesta.json())
    .then((json) => {
      if (json.ok && json.datos.length > 0) {
        sensorConectado = true;
        background(5, 5, 10);
        corazones = [];

        for (let i = 0; i < json.datos.length; i++) {
          let reg = json.datos[i];

          let nuevoC = new Corazon();
          nuevoC.nombre = reg.nombre;
          nuevoC.bpm = reg.pulsaciones;
          nuevoC.oxigeno = reg.oxigeno;
          nuevoC.familiaId = reg.familia_id;
          nuevoC.edad = reg.edad;

          nuevoC.escala = map(nuevoC.edad, 1, 90, 2.5, 8.0);

          for (let p of nuevoC.particulas) {
            p.escalaBase = nuevoC.escala;
          }

          if (nuevoC.familiaId === 0) {
            nuevoC.colorBase = color(100, 150, 200, 35);
          } else if (nuevoC.familiaId % 2 === 0) {
            nuevoC.colorBase = color(255, 60, 120, 35);
          } else {
            nuevoC.colorBase = color(0, 255, 136, 35);
          }

          corazones.push(nuevoC);

          if (i === json.datos.length - 1) {
            bpm = reg.pulsaciones;
            variabilidad = int(map(reg.pulsaciones, 60, 120, 5, 40));
          }
        }
      }
    })
    .catch((error) => {
      console.log("Cargando datos...", error);
      sensorConectado = false;
    });
}

function agregarUsuario() {
  corazones.push(new Corazon());
}

class Corazon {
  constructor() {
    this.particulas = [];
    this.faseLatido = random(TWO_PI);
    this.escala = 5;
    this.nombre = "";
    this.bpm = 75;
    this.oxigeno = 98;
    this.familiaId = 0;
    this.edad = 20;
    this.colorBase = color(255, 255, 255, 35);

    let posX, posY;
    let colision;
    let intentos = 0;
    do {
      posX = random(-width / 2 + 100, width / 2 - 100);
      posY = random(-height / 2 + 100, height / 2 - 100);
      colision = false;
      for (let c of corazones) {
        let d = dist(posX, posY, c.offset.x, c.offset.y);
        if (d < 200) {
          colision = true;
          break;
        }
      }
      intentos++;
    } while (colision && intentos < 50);

    this.offset = createVector(posX, posY);

    let cantidadParticulas = 100;
    for (let i = 0; i < cantidadParticulas; i++) {
      let t = map(i, 0, cantidadParticulas, 0, TWO_PI);
      this.particulas.push(new ParticulaHumo(t, this.escala));
    }
  }

  actualizar(centroBase, latidosPorMinuto, turbulencia) {
    let ritmoActual = this.nombre !== "" ? this.bpm : latidosPorMinuto;
    let turbulenciaActual =
      this.nombre !== "" ? map(this.bpm, 60, 120, 5, 40) : turbulencia;

    this.centro = p5.Vector.add(centroBase, this.offset);

    let velocidadLatido = (ritmoActual / 60) * 0.07;
    this.faseLatido += velocidadLatido;

    let pulso = 1 + 0.15 * pow(sin(this.faseLatido), 6);
    let deformacionLatido = pow(sin(this.faseLatido), 12);

    for (let p of this.particulas) {
      p.velocidadFlujo = map(ritmoActual, 60, 120, 0.005, 0.03);
      p.actualizar(this.centro, pulso, turbulenciaActual, deformacionLatido);
    }
  }

  mostrarCorazon() {
    drawingContext.shadowBlur = 60;
    drawingContext.shadowColor = this.colorBase.toString();

    for (let p of this.particulas) {
      p.mostrar(this.colorBase);
    }
    drawingContext.shadowBlur = 0;
  }

  mostrarTexto() {
    if (this.nombre !== "") {
      let posTexto = p5.Vector.add(centroGravedad, this.offset);

      fill(5, 5, 10);
      noStroke();
      ellipse(posTexto.x, posTexto.y + this.escala * 14, 75, 35);

      fill(255, 230);
      noStroke();
      textAlign(CENTER, CENTER);
      textSize(10);
      textFont("monospace");
      text(
        `${this.nombre.split(" ")[0]}\n${this.bpm} BPM\n${this.edad} Años`,
        posTexto.x,
        posTexto.y + this.escala * 14,
      );
    }
  }
}

class ParticulaHumo {
  constructor(t, escalaBase) {
    this.t = t;
    this.escalaBase = escalaBase;
    this.pos = createVector(0, 0);
    this.prev = createVector(0, 0);
    this.ruidoZ = random(1000);
    this.velocidadFlujo = 0.01;
  }

  actualizar(centro, pulso, turbulencia, picoLatido) {
    if (this.pos.x !== 0) {
      this.prev.x = this.pos.x;
      this.prev.y = this.pos.y;
    }

    let hx = 16 * pow(sin(this.t), 3);
    let hy = -(
      13 * cos(this.t) -
      5 * cos(2 * this.t) -
      2 * cos(3 * this.t) -
      cos(4 * this.t)
    );

    let amplitudRuidoBase = map(turbulencia, 0, 100, 5, 40);
    let amplitudExplosion = amplitudRuidoBase + picoLatido * 30;

    let deformacionX = map(
      noise(this.t * 2, frameCount * 0.005, this.ruidoZ),
      0,
      1,
      -amplitudExplosion,
      amplitudExplosion,
    );
    let deformacionY = map(
      noise(this.t * 2 + 100, frameCount * 0.005, this.ruidoZ),
      0,
      1,
      -amplitudExplosion,
      amplitudExplosion,
    );

    let targetX = centro.x + hx * this.escalaBase * pulso + deformacionX;
    let targetY = centro.y + hy * this.escalaBase * pulso + deformacionY;

    if (this.pos.x === 0) {
      this.pos.x = targetX;
      this.pos.y = targetY;
      this.prev.x = targetX;
      this.prev.y = targetY;
    } else {
      this.pos.x = lerp(this.pos.x, targetX, 0.1);
      this.pos.y = lerp(this.pos.y, targetY, 0.1);
    }

    this.t += this.velocidadFlujo;
  }

  mostrar(colorOrigen) {
    strokeWeight(1.5);
    stroke(red(colorOrigen), green(colorOrigen), blue(colorOrigen), 18);
    line(this.pos.x, this.pos.y, this.prev.x, this.prev.y);
  }
}

function actualizarPulso(nuevoBPM, nuevaVariabilidad = 10) {
  bpm = nuevoBPM;
  variabilidad = nuevaVariabilidad;
  sensorConectado = true;
}

function mostrarFichaTecnica() {
  fill(255, 200);
  noStroke();
  textFont("monospace");
  textSize(12);
  textAlign(LEFT, TOP);
  text(`Tecnología de Diseño Multimedial III - UNM`, 20, 20);
  text(`PROYECTO: Pulsaciones`, 20, 38);
  fill(200, 200, 255);
  text(`USUARIOS CONECTADOS EN BASE: ${corazones.length}`, 20, 56);
  if (sensorConectado) {
    fill(0, 255, 136);
    text(`♥ BASE DE DATOS ACTIVA`, 20, 78);
  } else {
    fill(255, 150, 50);
    text(`○ MODO SIMULACIÓN`, 20, 78);
  }
  fill(200);
  text(`Ritmo Cardíaco Gral:  ${bpm.toFixed(1)} BPM`, 20, 103);
}

function windowResized() {
  resizeCanvas(windowWidth, windowHeight);
}
//solo quiero ver si se subio el git porque me tira error al poner commit
