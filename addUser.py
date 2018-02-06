from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import os
import time
import sys 

service = webdriver.chrome.service.Service('./chromedriver')
service.start()
options = webdriver.ChromeOptions()
options.add_argument('--headless')
options.add_argument('--no-sandbox')

options = options.to_capabilities()
browser = webdriver.Remote(service.service_url, options)

browser.get('http://www.facebook.com')
assert 'Facebook' in browser.title

notLoggedIn = browser.find_element_by_name('firstname')  # Find the search box


if(notLoggedIn):
	elem = browser.find_element_by_name('email')  # Find the search box
	elem.send_keys('muhammedsblackflames@hotmail.com')


	elem = browser.find_element_by_name('pass')  # Find the search box
	elem.send_keys('phpechoIloveHadi247' + Keys.RETURN)

	time.sleep(5)

	#elem = browser.find_element_by_css_selector('a.layerCancel')
	#if(elem):
	#	elem.click()

	## add user

	browser.get('https://www.facebook.com/groups/SignalProfitsVIP/?ref=bookmarks')
	#time.sleep(5)
	try: 
	    elem = WebDriverWait(browser, 10).until(
		EC.presence_of_element_located((By.NAME, "freeform"))
	    )
	finally:
	    elem = browser.find_element_by_name('freeform')
	    elem.send_keys(sys.argv[1] + Keys.RETURN)
	    time.sleep(3)
	    browser.close()
	    browser.quit()
	#elem = browser.find_element_by_name('freeform')  # Find the search box
	#elem.send_keys(sys.argv[1] + Keys.RETURN)
	
	#browser.quit()
else :

	time.sleep(5)

	#elem = browser.find_element_by_css_selector('a.layerCancel')
	#if(elem):
	#	elem.click()

	## add user

	browser.get('https://www.facebook.com/groups/SignalProfitsVIP/?ref=bookmarks')
	#time.sleep(5)
	try: 
	    elem = WebDriverWait(browser, 10).until(
		EC.presence_of_element_located((By.NAME, "freeform"))
	    )
	finally:
	    elem = browser.find_element_by_name('freeform')
	    elem.send_keys(sys.argv[1] + Keys.RETURN)
	    time.sleep(3)
	    browser.close()
	    browser.quit()
