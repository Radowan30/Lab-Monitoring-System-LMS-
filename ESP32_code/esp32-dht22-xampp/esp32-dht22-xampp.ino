#include <HTTPClient.h>
#include <WiFiManager.h>
#include <DHT.h>

#define TRIGGER_PIN 14

// Define RGB LED pins
const int redPin = 13;
const int greenPin = 27;
const int bluePin = 25;

// Define PWM frequency and resolution
const int freq = 5000;
const int resolution = 8;

// LED Color definitions
const int lavender[3] = {180, 80, 255}; // Light purple (lavender)
const int green[3] = {0, 255, 0};
const int red[3] = {255, 0, 0};
const int yellow[3] = {255, 200, 0};

DHT dht22(26, DHT22);

String URL = "http://192.168.231.125:8000/dashboard/sensor1";

double temperature = 0;
double humidity = 0;

void setup()
{
  Serial.begin(115200);
  pinMode(TRIGGER_PIN, INPUT_PULLUP);

  // Setup LED PWM
  ledcAttach(redPin, freq, resolution);
  ledcAttach(greenPin, freq, resolution);
  ledcAttach(bluePin, freq, resolution);

  dht22.begin();

  // Initialize WiFi
  WiFi.mode(WIFI_STA);
  WiFi.setAutoReconnect(true);
  WiFi.persistent(true);

  connectToWiFi();
}

void loop()
{
  // Check if the configuration portal is requested
  if (digitalRead(TRIGGER_PIN) == LOW)
  {
    startConfigPortal();
  }
  else
  {
    // Load data from DHT22 sensor
    Load_DHT22_Data();

    if (temperature == 0 || humidity == 0)
    {
      // Sensor not working
      setColor(red[0], red[1], red[2]);
    }
    else if (WiFi.status() != WL_CONNECTED)
    {
      // Not connected to WiFi
      setColor(yellow[0], yellow[1], yellow[2]);
      connectToWiFi(); // Attempt reconnection
    }
    else
    {
      // Connected to WiFi and sensor is working
      setColor(green[0], green[1], green[2]);

      // Send data via HTTP POST
      String postData = "temperature=" + String(temperature) + "&humidity=" + String(humidity);

      HTTPClient http;
      http.begin(URL);
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");

      int httpCode = http.POST(postData);
      String payload = http.getString();

      Serial.print("URL : ");
      Serial.println(URL);
      Serial.print("Data: ");
      Serial.println(postData);
      Serial.print("httpCode: ");
      Serial.println(httpCode);
      Serial.print("payload : ");
      Serial.println(payload);
      Serial.println("--------------------------------------------------");
      delay(3000);
    }
  }
}

void connectToWiFi()
{
  Serial.println("Attempting to connect to WiFi...");
  WiFi.begin();

  while (WiFi.status() != WL_CONNECTED)
  {
    setColor(yellow[0], yellow[1], yellow[2]); // Indicate trying to connect
    delay(500);

    // If the user presses the button during connection attempts, enter config portal
    if (digitalRead(TRIGGER_PIN) == LOW)
    {
      startConfigPortal();
      return; // Exit this function as WiFiManager takes over
    }
  }

  Serial.println("Connected to WiFi!");
  setColor(green[0], green[1], green[2]);
}

void startConfigPortal()
{
  setColor(lavender[0], lavender[1], lavender[2]);
  WiFiManager wm;

  wm.setConfigPortalTimeout(300); // Timeout after 5 minutes if no action is taken

  if (!wm.startConfigPortal("Prep_Lab_SM_AP"))
  {
    Serial.println("Failed to connect and hit timeout");
    delay(3000);
    ESP.restart();
    delay(5000);
  }

  Serial.println("Connected to WiFi through configuration portal :)");
}

void Load_DHT22_Data()
{
  temperature = dht22.readTemperature(); // Celsius
  humidity = dht22.readHumidity();

  if (isnan(temperature) || isnan(humidity))
  {
    Serial.println("Failed to read from DHT sensor!");
    temperature = 0;
    humidity = 0;
  }

  Serial.printf("Temperature: %.2f °C\n", temperature);
  Serial.printf("Humidity: %.2f %%\n", humidity);
}

void setColor(int red, int green, int blue)
{
  ledcWrite(redPin, red);
  ledcWrite(greenPin, green);
  ledcWrite(bluePin, blue);
}
