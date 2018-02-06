from selenium import webdriver
from selenium.webdriver.common.keys import Keys
import os
import time

os.environ["PATH"] += ":/home/muhammed/Downloads/"

browser = webdriver.Firefox()

browser.get('http://www.facebook.com')
assert 'Facebook' in browser.title

notLoggedIn = browser.find_element_by_name('firstname')  # Find the search box


if(notLoggedIn):
	elem = browser.find_element_by_name('email')  # Find the search box
	elem.send_keys('muhammedsblackflames@hotmail.com')


	elem = browser.find_element_by_name('pass')  # Find the search box
	elem.send_keys('phpechoIloveHadi247' + Keys.RETURN)

	time.sleep(2)
	## add user

	browser.get('https://www.facebook.com/groups/wingedwireless/members/')


	elem = browser.find_element_by_xpath('//*[@id="groupsMemberBrowser"]/div[1]/div/div[2]/div/div/label/input')
	elem.send_keys('Soloman Jobe')
	time.sleep(2)

	elem = browser.find_element_by_css_selector('#globalContainer div > ul > li:nth-child(3) > a')
	elem.click()
	time.sleep(4)

	#elem = browser.find_element_by_link_text('//*[@id="u_1l_0"]/div/ul/li[3]/a')
	#elem.click()

else :
	time.sleep(2)
	## add user
	browser.get('https://www.facebook.com/groups/wingedwireless/members/')
	elem = browser.find_element_by_xpath('//*[@id="groupsMemberBrowser"]/div[1]/div/div[2]/div/div/label/input')
	elem.send_keys('Soloman Jobe')
	time.sleep(2)

	elem = browser.find_element_by_css_selector('#globalContainer div > ul > li:nth-child(3) > a')
	elem.click()
	time.sleep(4)

	#elem = browser.find_element_by_link_text('//*[@id="u_1l_0"]/div/ul/li[3]/a')
	#elem.click()
#browser.quit()
