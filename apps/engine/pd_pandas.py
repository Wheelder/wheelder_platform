
'''import pandas as pd
URL = 'https://en.wikipedia.org/wiki/List_of_largest_banks'
tables = pd.read_html(URL)
df = tables[0]

print(df)


import pandas as pd
URL = 'https://en.wikipedia.org/wiki/List_of_countries_by_GDP_(nominal)'
tables = pd.read_html(URL)
df = tables[2] # the required table will have index 2
print(df)
'''
import pandas as pd
import requests

URL = "https://en.wikipedia.org/wiki/List_of_countries_by_GDP_(nominal)"

# Pretend to be a browser
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
}

# Fetch the page
response = requests.get(URL, headers=headers)
response.raise_for_status()  # raises if 403/404/500

# Parse tables from the HTML text
tables = pd.read_html(response.text)

print(f"Found {len(tables)} tables")
print(tables[0].head())  # show first table
