# Online Shopping System

## Overview

This repository contains a dataset project focused on an online shopping system. The dataset was obtained by scraping data from the [Carrefour Online Shopping](https://online.carrefour.com.tw/zh/homepage/) website. The project is built on the LAMP (Linux, Apache, MySQL, PHP/Python) stack and hosted on Google Cloud Platform (GCP).

## Table of Contents

- [Introduction](#introduction)
- [Setup](#setup)
- [Data Scraping](#data-scraping)
- [Dataset Structure](#dataset-structure)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Introduction

The goal of this project is to provide a comprehensive dataset for an online shopping system, focusing on Carrefour's online platform in Taiwan. The dataset includes product information, pricing details, and other relevant data to facilitate analysis and research in the e-commerce domain.

## Setup

To set up the project locally, ensure you have the following components installed:

- Linux operating system
- Apache web server
- MySQL database
- PHP
- Python
- Google Cloud Platform account for hosting (optional)

Clone the repository to your local machine:

```bash
git clone https://github.com/SallySLJin/Online_Shopping_System.git
cd online-shopping-data
```

## Data Scraping

The data was scraped from the [Carrefour Online Shopping](https://online.carrefour.com.tw/zh/homepage/) website using Python. The scraping script is available in the `scraper` directory. To run the scraper, install the required dependencies:

```bash
pip install -r scraper/requirements.txt
```

Execute the scraper script:

```bash
python scraper/scrape_data.py
```

The scraped data will be saved to the `data` directory.

## Dataset Structure

The dataset is organized into the following directories:

- `scraper`: Contains the scraped data in CSV format, and includes any additional scripts for data processing or analysis.

## Usage

The dataset can be used for various purposes, such as:

- E-commerce market analysis
- Pricing strategies
- Product recommendation systems
- Machine learning models for predicting trends

Feel free to explore and analyze the data according to your research interests.

## Contributing

If you would like to contribute to the project, please follow these steps:

1. Fork the repository.
2. Create a new branch for your changes.
3. Make your changes and commit them.
4. Push your changes to your fork.
5. Submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE). Feel free to use, modify, and distribute the code and dataset for your own projects.

Happy coding!
