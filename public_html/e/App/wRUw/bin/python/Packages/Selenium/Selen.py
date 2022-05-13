from selenium import webdriver
from selenium.webdriver.firefox.firefox_binary import FirefoxBinary
driver = webdriver.Firefox(firefox_binary=FirefoxBinary("geckodriver.exe"))
class Selen:
	def __init__(self):return
	def Get(self):
		driver.get("http://avexeva.com")
		return driver.page_source
