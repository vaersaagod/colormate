// This file exists so we can haz HMR
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

app.use(
    '/',
    proxy({
        //target: 'http://talormade.test:3000',
        target: 'http://craft3latest.test',
        changeOrigin: true
    })
);

app.use(bundler.middleware());

app.listen(Number(process.env.PORT || 3000));
