build-frontend:
	sudo docker build -t node_builder -f Dockerfile.builder .
	sudo docker run --rm -v $(PWD):/app node_builder
