### Internet simple site crawler
Was written especially for a job appliance to travellizy.  
### How to run it
1. First [install docker](https://docs.docker.com/get-started/#download-and-install-docker-desktop) and test it works.
2. Then clone the repository
3. Build the image like `docker build --tag crawler:0.2.1.2 .` while within the cloned directory
4. Run the image like `docker run -it crawler:0.2.1.2 bash`
5. Run the crawler like `php ./crawl.php http://www.dolekemp96.org/main.htm` within the container. Please include the url trailing slash like after a domain name, if any.
6. Optionally copy the HTML report file back to docker host like `docker cp a307572a9698:/usr/src/crawler/report_08.05.2020.html ./logs/`