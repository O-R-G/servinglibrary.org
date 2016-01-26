// O-R-G 
// January 23, 2013
// based on CJoA.pde
// based on Boetti clock
// in the future, this could also be a clock!
// stripped down .js version for posting

boolean combi = false;
boolean debug = false;
boolean drawPoints = false;
boolean drawText = false;
boolean drawSpirals = true;
boolean paused = false;
boolean trails = false;

color backgroundColor = color(255);
color fillColor = color(0);
color strokeColor = color(0);

int thisFrameRate = 30;
int rotateDirection = -1;  // 1 clockwise [-1] counter
int rotateCounter = 0;  // for iterating animation
int thisSize = thisW;	// thisW declared in html file
int opacity = 255;   // for the line

//float thisScale = thisSize * .0125; // automatically adjusted based on thisSize
float thisScale = thisSize * .013; // automatically adjusted based on thisSize
float spiralPointsMax = 30; // [30] to be adjusted together with spiralDetail
float spiralDetail = .3;  // [1]
float rotateSpeed = 40.0;  // [40.0] the lower value, the faster



void setup() {
  // frame.setBackground(new java.awt.Color(0, 0, 0));  // Present-mode
  // size(thisSize, thisSize);
  // size(400, 570);	// set to match Bulletins page size
  size(thisW, thisH);	// thisW, thisH specified in the html file <script>
  frameRate(thisFrameRate);
  background(backgroundColor);
  stroke(strokeColor);
  smooth();
}



void draw() {

  if (!paused) {
    rotateCounter++;
    translate(width/2, thisH*.39);  
    scale(thisScale); 
    rotate( (TWO_PI / rotateSpeed) * (rotateCounter % rotateSpeed) );  // rotate in radians 
    drawRoll();
  }
}



void drawRoll() {
  if ((!combi) && (!trails)) background(backgroundColor);

  noFill();
  strokeWeight(3);
  stroke(strokeColor, opacity);
  ellipseMode(CENTER);
  curveTightness(-.25);

  // draw spirals

  if ( drawSpirals ) {
    beginShape();
    for (float i=0; i<spiralPointsMax; i+=spiralDetail) {
      curveVertex(rotateDirection*(i)*sin(i), (i)*cos(i));  // x, y points on Archimedean spiral plane
    }
    endShape();
  }

  // draw curve points

  if ( drawPoints ) {
    fill(fillColor);
    for (float i=0; i<spiralPointsMax-spiralDetail; i+=spiralDetail) {
      ellipse(rotateDirection*(i)*sin(i), (i)*cos(i), 1, 1);  // x, y, z points on Archimedean spiral plane
    }
  }
}



void mousePressed() {
    paused = !paused;
}



void keyPressed() {
  switch (key) {
  case '=':   // speed up
    rotateSpeed -= 1.0;
    break;
  case '-':   // slow down
    rotateSpeed += 1.0;
    break;
  case ' ':  // flip
    if ( rotateDirection == -1 ) {
      rotateDirection = 1;
    } 
    else {
      rotateDirection = -1;
    }
    break;
  default:
    break;
  }
}
