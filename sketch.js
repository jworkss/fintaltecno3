// Usamos la constante inyectada globalmente por index.php
// const ID_USUARIO_LOGUEADO = ...;

let corazones = [];
let centroGravedad;
let familiaIdActual = 0;

JavaScript;
function setup() {
  let canvas = createCanvas(windowWidth, windowHeight);

  canvas.parent("contenedor");

  centroGravedad = createVector(width / 2, height / 2);

  cargarDatosFamilia();

  // Actualización automática cada 3 segundos
  setInterval(cargarDatosFamilia, 3000);
}

function draw() {
  background(5, 5, 10, 12); // Fondo con estela de humo

  // Renderizar y actualizar cada corazón con sus propios datos de la base
  for (let c of corazones) {
    c.actualizar(centroGravedad);
    c.mostrar();
  }

  mostrarFichaTecnica();
}

// FUNCIÓN CENTRAL: Carga asincrónica de datos vía JSON [cite: 624, 700]
function cargarDatosFamilia() {
  fetch("consultafamilia.php?usuario_id=" + ID_USUARIO_LOGUEADO)
    .then((respuesta) => respuesta.json()) // [cite: 702]
    .then((json) => {
      if (json.ok && json.datos.length > 0) {
        familiaIdActual = json.familia_id;

        // Mapeo e instanciación dinámica de los corazones
        corazones = [];
        for (let d of json.datos) {
          corazones.push(
            new Corazon(
              d.usuario_id,
              d.nombre,
              d.familia_id,
              d.pulsaciones,
              d.oxigeno,
            ),
          );
        }
      }
    })
    .catch((error) => console.log("Error al conectar con el backend:", error));
}

// CLASE CORAZÓN MODIFICADA
class Corazon {
  constructor(id, nombre, familiaId, bpm, oxigeno) {
    this.id = id;
    this.nombre = nombre;
    this.familiaId = familiaId;
    this.bpm = bpm;
    this.oxigeno = oxigeno;

    this.particulas = [];
    this.faseLatido = random(TWO_PI);

    // MAPEO: El nivel de oxígeno modifica el tamaño físico del corazón [cite: 625, 704]
    this.escala = map(this.oxigeno, 90, 100, 3, 7);

    // LÓGICA DE COLOR POR GRUPO FAMILIAR
    // Usamos el ID de la familia para estabilizar una paleta única para el grupo
    if (this.familiaId === 0) {
      // Sin grupo: Tonos grises/azulados fríos
      this.colorBase = color(100, 150, 200);
    } else if (this.familiaId % 2 === 0) {
      // Familias pares: Gama de Magentas/Rojos cálidos
      this.colorBase = color(255, map(this.bpm, 60, 120, 20, 100), 120);
    } else {
      // Familias impares: Gama de Verdes ciber/Bauhaus
      this.colorBase = color(30, 255, map(this.bpm, 60, 120, 100, 200));
    }

    // LÓGICA DE ATRACCIÓN: Modificación de distancias según el grupo familiar
    let posX, posY;
    let distanciaMaximaPermitida = this.familiaId !== 0 ? 150 : 400;
    // Si son de la misma familia se restringe el radio aleatorio para que aparezcan juntos

    posX = random(-distanciaMaximaPermitida, distanciaMaximaPermitida);
    posY = random(-distanciaMaximaPermitida, distanciaMaximaPermitida);

    this.offset = createVector(posX, posY);

    let cantidadParticulas = 80;
    for (let i = 0; i < cantidadParticulas; i++) {
      let t = map(i, 0, cantidadParticulas, 0, TWO_PI);
      this.particulas.push(new ParticulaHumo(t, this.escala));
    }
  }

  actualizar(centroBase) {
    this.centro = p5.Vector.add(centroBase, this.offset);

    // MAPEO: Los latidos reales alteran la velocidad de la animación
    let velocidadLatido = (this.bpm / 60) * 0.04;
    this.faseLatido += velocidadLatido;

    let pulso = 1 + 0.15 * pow(sin(this.faseLatido), 6);
    let deformacionLatido = pow(sin(this.faseLatido), 12);

    // MAPEO: Traducir las pulsaciones altas en mayor turbulencia en las partículas
    let turbulenciaCalculada = map(this.bpm, 60, 120, 5, 50);

    for (let p of this.particulas) {
      p.actualizar(this.centro, pulso, turbulenciaCalculada, deformacionLatido);
    }
  }

  mostrar() {
    drawingContext.shadowBlur = 40;
    drawingContext.shadowColor = this.colorBase.toString();

    for (let p of this.particulas) {
      p.mostrar(this.colorBase);
    }
    drawingContext.shadowBlur = 0;

    // Rótulo identificatorio flotante para cada miembro
    fill(255, 180);
    noStroke();
    textAlign(CENTER, CENTER);
    textSize(11);
    text(
      `${this.nombre}\n(${this.bpm} BPM)`,
      this.centro.x,
      this.centro.y + this.escala * 15,
    );
  }
}

// (La clase ParticulaHumo se mantiene idéntica a tu código original)

function mostrarFichaTecnica() {
  fill(255, 200);
  noStroke();
  textFont("monospace");
  textSize(12);
  textAlign(LEFT, TOP);

  text(`Tecnología de Diseño Multimedial III - UNM`, 20, 20);
  text(`SISTEMA: Red de Pulsaciones Familiares`, 20, 38);

  fill(0, 255, 136);
  text(
    `GRUPO FAMILIAR ID: ${familiaIdActual ? familiaIdActual : "Monousuario"}`,
    20,
    56,
  );
  text(`MIEMBROS EN PANTALLA: ${corazones.length}`, 20, 72);

  fill(200);
  text(`Estado: Monitoreo dinámico local activo (3s)`, 20, 95);
}

function windowResized() {
  resizeCanvas(windowWidth, windowHeight);
}
