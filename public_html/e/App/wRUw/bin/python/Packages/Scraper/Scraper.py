class Scraper:
  def __init__(self):
    return
  def Links(code):
    links = re.findall("(https?://(?:www.)?(.*).(?:(?:com)|(?:net)|(?:org)|(?:gov))", code)
    for link in links:
      print("Found Link: " + link)

exec(Packages['Selenium']['Selen'])
scraper = Scraper()
selen = Selen()
scraper.Links(selen.Get())
