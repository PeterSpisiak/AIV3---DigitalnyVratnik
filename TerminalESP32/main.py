from time import sleep_ms
from machine import Pin, SPI, PWM
from mfrc522 import MFRC522
import time
import urequests

sck = Pin(25, Pin.OUT)
mosi = Pin(26, Pin.OUT)
miso = Pin(27, Pin.OUT)
spi = SPI(baudrate=100000, polarity=0, phase=0, sck=sck, mosi=mosi, miso=miso)

sda = Pin(2, Pin.OUT)

adresa = "http://192.168.1.158:8000"

def do_connect():
    import network
    sta_if = network.WLAN(network.STA_IF)
    if not sta_if.isconnected():
        print('connecting to network...')
        sta_if.active(True)
        sta_if.connect('ASUS_CC', 'klopklop')
        while not sta_if.isconnected():
            pass
    print('network config:', sta_if.ifconfig())
    response = urequests.get(adresa)
    print(type(response))

def do_read():
    try:
        while True:
            rdr = MFRC522(spi, sda)
            uid = ""
            (stat, tag_type) = rdr.request(rdr.REQIDL)
            if stat == rdr.OK:
                (stat, raw_uid) = rdr.anticoll()
                if stat == rdr.OK:
                    uid = ("0x%02x%02x%02x%02x" % (raw_uid[0], raw_uid[1], raw_uid[2], raw_uid[3]))
                    print(uid)
                    response = urequests.get(adresa+"/"+uid)
                    print(type(response))
                    beeper = PWM(Pin(33, Pin.OUT), freq=400, duty=512)
                    sleep_ms(1000)
                    beeper.deinit()
    except KeyboardInterrupt:
        print("Bye")

do_connect()
do_read()