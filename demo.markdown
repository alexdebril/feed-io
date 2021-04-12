---
layout: default
title: Demo
permalink: /demo/
---


<h2>Feed discovery</h2>
<p>
Scans a website and outputs the feeds it provides.
</p>
<input type="url" name="website" id="website" />
<button name="submit" onclick="discoverFeeds()">discover</button>

<div id="discovery-result"></div>

<h2>Feed reading</h2>
<input type="url" name="feedToRead" id="feedToRead" />
<button name="submit" onclick="readFeed()">read</button>

<div id="read-result"></div>

<img src="https://feed-io-api.herokuapp.com" width="0" height="0"/>

<script src="https://unpkg.com/react@16/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js" crossorigin></script>
<script src="/assets/js/app.js"></script>
