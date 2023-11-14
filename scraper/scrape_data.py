# Add comments

# -*- coding: utf-8 -*-
import os
import re
import requests
import csv
# sudo apt-get install python3-lxml
import lxml.html


# Carrefour home page link
home_URL = "https://online.carrefour.com.tw" 

# Function: categories Scraping
def scrape_category(cat_URL, output_file):
  for c in range(1, len(cat_URL)):
    page_URL = home_URL + cat_URL[c]
    req = requests.get(page_URL)
    html = req.text
    selector = lxml.html.fromstring(html)
    # (ID, Name, Price, Category, Image, Description)
    ID = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/@data-pid')
    Name = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/@data-name')
    Price = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/@data-price')
    Category = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/@data-category')
    Image = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/img/@src')
    Description = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/@data-variant')
    
    for i in range(len(ID)):
      with open(output_file, "a+", encoding="utf-8") as file:
        writer = csv.writer(file)
        writer.writerow([
          ''.join(ID[i]),    
          ''.join(Name[i]),           
          round(float(Price[i])),
          ''.join(Category[i]), 
          ''.join(Image[i]),       
          ''.join(Description[i])])
        file.close()
    
    page = 0
    count = 24
    while ((selector.xpath('//*[@id="pagination"]/li[10]/a/@href')) and page < 10):
      next_URL = page_URL + '?start=' + str(count)
      count += 24
      page += 1
      req = requests.get(next_URL)
      html = req.text
      selector = lxml.html.fromstring(html)
      ID = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/@data-pid')
      Name = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/@data-name')
      Price = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/@data-price')
      Category = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/@data-category')
      Image = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/img/@src')
      Description = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/@data-variant')
      
      for i in range(len(ID)):
        with open(output_file, "a+", encoding="utf-8") as file:
          writer = csv.writer(file)
          writer.writerow([
            ''.join(ID[i]),    
            ''.join(Name[i]),           
            round(float(Price[i])),
            ''.join(Category[i]), 
            ''.join(Image[i]),       
            ''.join(Description[i])])
          file.close()
    
# Function: Web Scraping
def scrape_web(): 
  output_file = f"scraped_data.csv"
  res = requests.get(home_URL).content
  tree = lxml.html.fromstring(res)
  category_URL = tree.xpath('/html/body/div[4]/section/div[1]/div[1]/ul/li/a/@href')
  scrape_category(category_URL, output_file)

scrape_web()