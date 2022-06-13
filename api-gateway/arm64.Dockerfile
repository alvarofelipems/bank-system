#Dockerfile

FROM golang
WORKDIR /usr/var/
RUN apt-get install git
RUN git clone https://github.com/krakendio/krakend-ce.git
WORKDIR /usr/var/krakend-ce
RUN make build
COPY config/ /etc/krakend/
WORKDIR /etc/krakend/
CMD ["krakend", "run", "-d", "-c", "krakend.json"]
