from bs4 import BeautifulSoup
import requests
URL = "https://en.wikipedia.org/wiki/Artificial_intelligence"

page =requests.get(URL)

soup = BeautifulSoup(page.content, "html.parser")