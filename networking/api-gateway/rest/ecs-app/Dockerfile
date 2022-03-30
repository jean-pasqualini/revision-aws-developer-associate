FROM node:12-alpine

COPY ./index.js /app/index.js
COPY ./*.pem /app/
WORKDIR /app
CMD ["node", "index.js"]