import os
import requests
import csv
import lxml.html

# Constants
HOME_URL = "https://online.carrefour.com.tw"
OUTPUT_FILE = "scraped_data.csv"
INCREMENT = 24
MAX_PAGES = 10


def scrape_category_page(page_url, output_file):
    req = requests.get(page_url)
    html = req.text
    selector = lxml.html.fromstring(html)

    ID = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/@data-pid')
    Name = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/@data-name')
    Price = selector.xpath(
        '//*[@id="productgrid"]/div/div[1]/a/@data-price')
    Category = selector.xpath(
        '//*[@id="productgrid"]/div/div[1]/a/@data-category')
    Image = selector.xpath('//*[@id="productgrid"]/div/div[1]/a/img/@src')
    Description = selector.xpath(
        '//*[@id="productgrid"]/div/div[1]/a/@data-variant')

    for i in range(len(ID)):
        # Split Category by '/' and store in Category1 to Category4
        categories = ''.join(Category[i]).split('/')
        category1 = categories[0] if len(categories) > 0 else ''
        category2 = categories[1] if len(categories) > 1 else ''
        category3 = categories[2] if len(categories) > 2 else ''
        category4 = categories[3] if len(categories) > 3 else ''

        with open(output_file, "a+", encoding="utf-8") as file:
            writer = csv.writer(file)
            writer.writerow([
                ''.join(ID[i]),
                ''.join(Name[i]),
                round(float(Price[i])),
                category1,
                category2,
                category3,
                category4,
                ''.join(Image[i]),
                ''.join(Description[i])])
            file.close()

    return selector


def scrape_category(category_url, output_file):
    for url in category_url:
        page_url = HOME_URL + url
        selector = scrape_category_page(page_url, output_file)

        page = 0
        count = INCREMENT
        while (
            selector.xpath('//*[@id="pagination"]/li[10]/a/@href')
            and page < MAX_PAGES
        ):
            next_url = page_url + f'?start={count}'
            count += INCREMENT
            page += 1
            selector = scrape_category_page(next_url, output_file)


def scrape_web():
    if os.path.exists(OUTPUT_FILE):
        print("File already exists. Skipping scraping.")
        return
    res = requests.get(HOME_URL).content
    tree = lxml.html.fromstring(res)
    category_url = tree.xpath(
        '/html/body/div[4]/section/div[1]/div[1]/ul/li/a/@href')
    scrape_category(category_url, OUTPUT_FILE)


if __name__ == "__main__":
    scrape_web()
