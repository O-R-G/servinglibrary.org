/* @pjs transparent=true; */
/* @pjs crisp=true; */

// O-R-G 

float powerCounter = 0;
int resonatorCounter = 0;
int displayCounter = 0;
int reverseSwitch = 1;


void setup() {
  size(200, 200);
  stroke(0);
  smooth();
  background(#FFFFFF);
}

void draw() {
  background(#FFFFFF, 0); 
  displayCounter++;

  if ( displayCounter % 14 == 0 ) {
    resonatorCounter++;
  }

  powerCounter = ( ( ( displayCounter % 300 ) / 3 ) * reverseSwitch ) + 2.0;

  noFill();
  stroke(#000000);
  strokeWeight(2);
  ellipse(100, 100, 160, 160);

  float s = map(displayCounter % 60, 0, 60, 0, TWO_PI) - HALF_PI;
  float m = map((resonatorCounter % 10) + 30, 0, 60, 0, TWO_PI) - HALF_PI;
  float h = map(hour() % 12, 0, 12, 0, TWO_PI) - HALF_PI;


  // Display
  translate(100,100);
  ellipse(cos(s) * 72, sin(s) * 72, 10, 10);
  line((cos(h) * 72) + 6, (sin(h) * 72) - 6, (cos(h) * 72), sin(h) * 72);
  line((cos(h) * 72), (sin(h) * 72) - 6, (cos(h) * 72) + 6, sin(h) * 72);


  // Resonator


  // Power

  beginShape();
  for(int i=0;i<60;i++) {
    curveVertex((i*1.2)*sin(i/powerCounter),(i*1.2)*cos(i/powerCounter));
  }
  endShape();
}
