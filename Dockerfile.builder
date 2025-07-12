# Dockerfile.builder
FROM node:22-alpine

WORKDIR /app

# Install tools untuk build native packages
RUN apk add --no-cache make g++ python3

# Salin file yang dibutuhkan
COPY package*.json ./
RUN npm install

COPY . .

# Jalankan build saat container dijalankan
CMD ["npm", "run", "build"]
