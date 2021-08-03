// This file exists so we can haz HMR
require('dotenv').config();
const proxy = require('http-proxy-middleware');
const Bundler = require('parcel-bundler');
const express = require('express');

const entries = [
    'src/field.js',
];

const bundler = new Bundler(entries, {
    cache: false,
});

const app = express();

const { PROXY_URL } = process.env;

if (!PROXY_URL) {
    throw new Error('No URL to proxy. Create a .env file in this directory and add a PROXY_URL variable to it');
}

app.use(
    '/',
    proxy({
        target: PROXY_URL,
        changeOrigin: true
    })
);

app.use(bundler.middleware());

app.listen(Number(process.env.PORT || 3000));
