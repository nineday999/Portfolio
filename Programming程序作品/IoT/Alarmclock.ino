#include <WiFi.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <time.h>
#include <TimeLib.h>
#include "DHTesp.h"

#define SDA 13                    //Define SDA pins
#define SCL 14                    //Define SCL pins

const char *ssid = "JCOM_THGU";
const char *password = "820666204111";
const char *ntpServer = "pool.ntp.org";
const long gmtOffset_sec = 9 * 3600;
const int daylightOffset_sec = 0;
struct tm timeinfo;

const int BUTTON1 = 25;
const int BUTTON2 = 26;
const int BUTTON3 = 27;
const int BUZZER = 12;

int val1 = 0;
int val2 = 0;
int val3 = 0;
int old_val1 = 0;
int old_val2 = 0;
int old_val3 = 0;

int alarmHour = 8;
int alarmMinute = 30;
int state = 0;

int sTime[6] = {0,0,0,0,0,0}; //this will be use in set current Time page(state == 1)
int tmp = 0;

DHTesp dht;  // create dht object
LiquidCrystal_I2C lcd(0x27,16,2); //initialize the LCD
const int dhtPin = 18;                  // the number of the DHT11 sensor pin

void setup() {
  Serial.begin(115200);
  Wire.begin(SDA, SCL);           // attach the IIC pin
  if (!i2CAddrTest(0x27)) {
    lcd = LiquidCrystal_I2C(0x3F, 16, 2);
  }
  lcd.init();                     // LCD driver initialization
  lcd.backlight();                // Open the backlight
  dht.setup(dhtPin, DHTesp::DHT11); //attach the dht pin and initialize it

  pinMode(BUTTON1, INPUT);
  pinMode(BUTTON2, INPUT);
  pinMode(BUTTON3, INPUT);
  
  WiFi.begin(ssid, password);
  while(WiFi.status() != WL_CONNECTED){
    delay(500);
    Serial.print(".");
  }
  Serial.println("WiFi-Connected!");
  configTime(gmtOffset_sec, daylightOffset_sec, ntpServer);

  if(!getLocalTime(&timeinfo)){
    Serial.println("Failed to obtain time");
    return;
  }
  Serial.println(&timeinfo,"%F %T %A");
  setTime(timeinfo.tm_hour,timeinfo.tm_min,timeinfo.tm_sec,timeinfo.tm_mday,timeinfo.tm_mon+1,timeinfo.tm_year+1900); //set time, and UTC will use this time to continue automaticlly

  WiFi.disconnect(true);
  WiFi.mode(WIFI_OFF);
  Serial.println("WiFi-disconnected!");
  
}

void loop() {
  // read DHT11 data and save it 
  flag:TempAndHumidity DHT = dht.getTempAndHumidity();
  if (dht.getStatus() != 0) {       //Determine if the read is successful, and if it fails, go back to flag and re-read the data
    goto flag;
  }

  val1 = digitalRead(BUTTON1);
  val2 = digitalRead(BUTTON2);
  val3 = digitalRead(BUTTON3);

  int cHour = hour(); //get current hour from what has been seted in "setup()" by setTime() method
  int cMinute = minute();
  int cSecond = second();
  int cDay = day();
  int cMonth = month();
  int cYear = year();
  int cWeekday = weekday();
  char cHourStr[3];
  sprintf(cHourStr,"%02d",cHour);
  char cMinuteStr[3];
  sprintf(cMinuteStr,"%02d",cMinute);
  char cSecondStr[3];
  sprintf(cSecondStr,"%02d",cSecond);

  if(state == 0){ //main page(display time, date and weekday)
    lcd.setCursor(0, 0);
    lcd.printstr(cHourStr);
    lcd.print(":");
    lcd.printstr(cMinuteStr);
    lcd.print(":");
    lcd.printstr(cSecondStr);
    lcd.setCursor(13, 1);
    switch(cWeekday){
      case 1: 
        lcd.print("Sun");
        break;
      case 2: 
        lcd.print("Mon");
        break;
      case 3: 
        lcd.print("Tus");
        break;
      case 4:
        lcd.print("Wen");
        break;
      case 5:
        lcd.print("Thu");
        break;
      case 6:
        lcd.print("Fri");
        break;
      case 7:
        lcd.print("Sat");
      default: 
        lcd.print("Error");
        break;
    }
    lcd.setCursor(0,1);
    lcd.print(cYear);
    lcd.print("/");
    lcd.print(cMonth);
    lcd.print("/");
    lcd.print(cDay);
    delay(100);

    if(alarmHour == cHour && alarmMinute == cMinute && cSecond == 0){ //alarm clock rings
      for(int i=0; i<3; i++){
        tone(BUZZER,1319,500);
        tone(BUZZER,0,500);
      }
    }

    if((val1 == HIGH) && (old_val1 == LOW)){  //press left button to turn into set current time page
      state = 1;
      sTime[0] = cHour;
      sTime[1] = cMinute;
      sTime[2] = cSecond;
      sTime[3] = cYear;
      sTime[4] = cMonth;
      sTime[5] = cDay;
      lcd.clear();
      delay(20);
    }
    if((val1 == LOW) && (old_val1 == HIGH)){
      delay(20);
    }
    if((val2 == HIGH) && (old_val2 == LOW)){  //press middle button to turn into set alarm page
      state = 2;
      lcd.clear();
      delay(20);
    }
    if((val2 == LOW) && (old_val2 == HIGH)){
      delay(20);
    }
    if((val3 == HIGH) && (old_val3 == LOW)){  //press right button to see tempareture and humidity
      state = 3;
      lcd.clear();
      delay(20);
    }
    if((val3 == LOW) && (old_val3 == HIGH)){
      delay(20);
    }
  }

  if(state == 1){ //the page to set current time
    char sHourStr[3];
    sprintf(sHourStr,"%02d",sTime[0]);
    char sMinuteStr[3];
    sprintf(sMinuteStr,"%02d",sTime[1]);
    char sSecondStr[3];
    sprintf(sSecondStr,"%02d",sTime[2]);
    lcd.setCursor(0, 0);
    lcd.printstr(sHourStr);
    lcd.print(":");
    lcd.printstr(sMinuteStr);
    lcd.print(":");
    lcd.printstr(sSecondStr);
    lcd.setCursor(0,1);
    lcd.print(sTime[3]);
    lcd.print("/");
    lcd.print(sTime[4]);
    lcd.print("/");
    lcd.print(sTime[5]);

    lcd.setCursor(13, 1);
    lcd.setCursor(13, 0);
    lcd.print("SET");
    lcd.setCursor(12, 1);
    lcd.print("TIME");
    delay(100);

    val1 = digitalRead(BUTTON1);
    if((val1 == HIGH) && (old_val1 == LOW)){  //left button to save change and return to main page
      state = 0;
      setTime(sTime[0],sTime[1],sTime[2],sTime[5],sTime[4],sTime[3]);
      lcd.clear();
      tmp = 0;
      delay(20);
    }
    if((val1 == LOW) && (old_val1 == HIGH)){
      delay(20);
    }

    if((val2 == HIGH) && (old_val2 == LOW)){ //mid button to set the next position(EX: hour -> minute -> second)
      sTime[tmp]++;
      if(sTime[0] >= 24){
        sTime[0] = 0;
      }
      if(sTime[1] >= 60){
        sTime[1] = 0;
      }
      if(sTime[2] >= 60){
        sTime[2] = 0;
      }
      if(sTime[3] >= 2030){
        sTime[3] = 2020;
      }
      if(sTime[4] >= 13){
        sTime[4] = 1;
      }
      if(sTime[5] >= 32){
        sTime[5] = 1;
      }
      delay(20);
    }
    if((val2 == LOW) && (old_val2 == HIGH)){
      delay(20);
    }

    if((val3 == HIGH) && (old_val3 == LOW)){
      tmp++;
      if(tmp >= 6){
        state = 0;
        setTime(sTime[0],sTime[1],sTime[2],sTime[5],sTime[4],sTime[3]);
        tmp = 0;
        lcd.clear();
      }
      delay(20);
    }
    if((val3 == LOW) && (old_val3 == HIGH)){
      delay(20);
    }
  }
 
  if(state == 2){ //the page to set alarm
    char alarmHourStr[3];
    char alarmMinuteStr[3];
    sprintf(alarmHourStr,"%02d",alarmHour);
    sprintf(alarmMinuteStr,"%02d",alarmMinute);
    lcd.setCursor(0, 0);
    lcd.printstr(alarmHourStr);
    lcd.print(":");
    lcd.printstr(alarmMinuteStr);
    lcd.setCursor(11, 0);
    lcd.print("ALARM");
    if((val1 == HIGH) && (old_val1 == LOW)){
      state = 0;
      tmp = 0;
      lcd.clear();
      delay(20);
    }
    if((val1 == LOW) && (old_val1 == HIGH)){
      delay(20);
    }
    delay(200);
    val2 = digitalRead(BUTTON2);
    if((val2 == HIGH) && (old_val2 == LOW)){
      if(tmp == 0){
        alarmHour += 10;
      }else if(tmp == 1){
        alarmHour++;
      }else if(tmp == 2){
        alarmMinute += 10;
      }else if(tmp == 3){
        alarmMinute++;
      }
      if(alarmHour >= 24){
        alarmHour = 0;
      }
      if(alarmMinute >= 60){
        alarmMinute = 0;
      }
      delay(20);
    }
    if((val2 == LOW) && (old_val2 == HIGH)){
      delay(20);
    }
    if((val3 == HIGH) && (old_val3 == LOW)){
      tmp++;
      if(tmp > 3){
        state = 0;
        lcd.clear();
      }
      delay(20);
    }
    if((val3 == LOW) && (old_val3 == HIGH)){
      delay(20);
    }
  }

  if(state == 3){ //the page to display temperature and humidity(automaticlly return to main page after 2sec)
    lcd.setCursor(0, 0);              //set the cursor to column 0, line 1
    lcd.print("Temperature:");        //display the Humidity on the LCD1602
    lcd.print(DHT.temperature);   
    lcd.setCursor(0, 1);              //set the cursor to column 0, line 0 
    lcd.print("Humidity   :");        //display the Humidity on the LCD1602
    lcd.print(DHT.humidity);
    delay(2000);
    lcd.clear();
    state = 0;
  }

  old_val1 = val1;
  old_val2 = val2;
  old_val3 = val3;

}

bool i2CAddrTest(uint8_t addr) {
  Wire.begin();
  Wire.beginTransmission(addr);
  if (Wire.endTransmission() == 0) {
    return true;
  }
  return false;
}
